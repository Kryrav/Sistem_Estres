<?php 
class Categorias extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if(empty($_SESSION['login']))
        {
            header('Location: '.base_url().'/login');
        }
        getPermisos(7); // Módulo 7: Categorías de Indicadores
    }

    public function Categorias()
    {
        $data['page_tag'] = "Categorías de Indicadores";
        $data['page_title'] = "Categorías de Indicadores";
        $data['page_name'] = "categorias";
        $data['page_functions_js'] = "functions_categorias.js";
        $this->views->getView($this, "categorias", $data);
    }

    /**
     * Obtiene todas las categorías para DataTables (AJAX)
     */
    public function getCategorias()
    {
        if($_SESSION['permisosMod']['r']){
            $arrData = $this->model->selectCategorias();
            
            for ($i=0; $i < count($arrData); $i++) {
                $id = $arrData[$i]['id'];
                
                // Formato de Estado
                if($arrData[$i]['activo'] == 1){
                    $arrData[$i]['activo'] = '<span class="badge badge-success">Activo</span>';
                } else {
                    $arrData[$i]['activo'] = '<span class="badge badge-danger">Inactivo</span>';
                }

                // Botones de Acción (Revisar permisos U y D)
                $arrData[$i]['options'] = '
                    <div class="text-center">';
                
                if($_SESSION['permisosMod']['u']){
                    $arrData[$i]['options'] .= '<button class="btn btn-sm btn-primary btnEditCategoria" onClick="fntEditCategoria(this, '.$id.')" title="Editar Categoría">
                        <i class="fas fa-pencil-alt"></i>
                    </button>';
                }

                if($_SESSION['permisosMod']['d']){
                    $arrData[$i]['options'] .= '<button class="btn btn-sm btn-danger btnDelCategoria" onClick="fntDelCategoria('.$id.')" title="Eliminar Categoría">
                        <i class="fas fa-trash-alt"></i>
                    </button>';
                }
                
                $arrData[$i]['options'] .= '</div>';
            }

            echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    /**
     * Inserta o Actualiza una categoría
     */
    public function setCategoria()
    {
        if ($_POST) {
            $id = empty($_POST['id']) ? 0 : intval($_POST['id']);
            $nombre = strClean($_POST['txtNombre']);
            $descripcion = strClean($_POST['txtDescripcion']);
            $activo = intval($_POST['listActivo']);

            // Validaciones
            if (empty($nombre)) {
                $arrResponse = array("status" => false, "msg" => 'El campo Nombre es obligatorio.');
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                die();
            }

            if ($id == 0) {
                // Crear
                if($_SESSION['permisosMod']['w']){
                    $request = $this->model->insertCategoria($nombre, $descripcion);
                    $option = 1;
                }
            } else {
                // Actualizar
                if($_SESSION['permisosMod']['u']){
                    $request = $this->model->updateCategoria($id, $nombre, $descripcion, $activo);
                    $option = 2;
                }
            }

            if (isset($request)) {
                if ($request > 0) {
                    $msg = ($option == 1) ? 'Categoría creada exitosamente.' : 'Categoría actualizada correctamente.';
                    $arrResponse = array('status' => true, 'msg' => $msg);
                } else if ($request === 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención! Ya existe una categoría con ese nombre.');
                } else {
                    $arrResponse = array("status" => false, "msg" => "Error al procesar la solicitud.");
                }
            } else {
                 $arrResponse = array("status" => false, "msg" => "No tiene permisos para realizar esta acción.");
            }

            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
    
    /**
     * Obtiene una categoría por ID para edición (AJAX)
     */
    public function getCategoria(int $id)
    {
        if($_SESSION['permisosMod']['r']){
            $id = intval(strClean($id)); 
            if ($id > 0) {
                $arrData = $this->model->selectCategoria($id);
                
                if (empty($arrData)) {
                    $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
                } else {
                    $arrResponse = array('status' => true, 'data' => $arrData);
                }
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }
    
    /**
     * Elimina lógicamente una categoría (AJAX)
     */
    public function delCategoria()
    {
        if ($_POST && $_SESSION['permisosMod']['d']) {
            $id = intval($_POST['id']);
            $request_delete = $this->model->deleteCategoria($id);
            
            if ($request_delete) {
                $arrResponse = array('status' => true, 'msg' => 'Categoría eliminada lógicamente.');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al intentar eliminar la categoría.');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    /**
     * Obtiene todas las categorías activas (para SELECT en otros módulos)
     */
    public function getSelectCategorias()
    {
        $htmlOptions = "";
        $arrData = $this->model->selectCategoriasActivas();
        
        if(count($arrData) > 0){
            for ($i=0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="'.$arrData[$i]['id'].'">'.$arrData[$i]['nombre'].'</option>';
            }
        }
        echo $htmlOptions;
        die();
    }
}
?>