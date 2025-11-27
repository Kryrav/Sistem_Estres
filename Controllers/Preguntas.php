<?php 
class Preguntas extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if(empty($_SESSION['login']))
        {
            header('Location: '.base_url().'/login');
        }
        getPermisos(21); // Módulo 11: Banco de Preguntas
    }

    public function Preguntas()
    {
        $data['page_tag'] = "Banco de Preguntas";
        $data['page_title'] = "Banco de Preguntas";
        $data['page_name'] = "preguntas";
        $data['page_functions_js'] = "functions_preguntas.js";
        $this->views->getView($this, "Preguntas", $data);
    }

    /**
     * Obtiene todas las Preguntas para DataTables (AJAX)
     */
    public function getPreguntas()
    {
        if($_SESSION['permisosMod']['r']){
            $arrData = $this->model->selectPreguntas();
            
            for ($i=0; $i < count($arrData); $i++) {
                $id = $arrData[$i]['id'];
                
                // Formato de Estado
                if($arrData[$i]['activo'] == 1){
                    $arrData[$i]['activo'] = '<span class="badge badge-success">Activa</span>';
                } else {
                    $arrData[$i]['activo'] = '<span class="badge badge-danger">Inactiva</span>';
                }

                // Botones de Acción (Revisar permisos U y D)
                $arrData[$i]['options'] = '
                    <div class="text-center">';
                
                // Botón VER
                $arrData[$i]['options'] .= '<button class="btn btn-sm btn-info btnViewPregunta" onClick="fntViewPregunta('.$id.')" title="Ver Opciones">
                        <i class="fas fa-eye"></i>
                    </button>';

                if($_SESSION['permisosMod']['u']){
                    $arrData[$i]['options'] .= '<button class="btn btn-sm btn-primary btnEditPregunta" onClick="fntEditPregunta(this, '.$id.')" title="Editar Pregunta">
                        <i class="fas fa-pencil-alt"></i>
                    </button>';
                }

                if($_SESSION['permisosMod']['d']){
                    $arrData[$i]['options'] .= '<button class="btn btn-sm btn-danger btnDelPregunta" onClick="fntDelPregunta('.$id.')" title="Eliminar Pregunta">
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
     * Inserta o Actualiza una pregunta y sus opciones
     */
    public function setPregunta()
    {
        if ($_POST) {
            $id = empty($_POST['id']) ? 0 : intval($_POST['id']);
            $categoriaId = intval($_POST['listCategoria']);
            $textoPregunta = strClean($_POST['txtTextoPregunta']);
            $tipoPregunta = strClean($_POST['listTipoPregunta']);
            $activo = intval($_POST['listActivo']);
            
            // 🚨 SOLUCIÓN CRUCIAL: Deserializar la cadena JSON de opciones a un array de PHP.
            $opcionesJson = isset($_POST['opciones']) ? $_POST['opciones'] : '[]';
            $opciones = json_decode($opcionesJson, true);

            // Validaciones básicas
            if (empty($categoriaId) || empty($textoPregunta) || empty($tipoPregunta)) {
                $arrResponse = array("status" => false, "msg" => 'Faltan campos obligatorios (*).');
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                die();
            }

            // Validar que si es de tipo ESCALA u OPCION, tenga opciones definidas
            if (($tipoPregunta == 'ESCALA' || $tipoPregunta == 'OPCION') && empty($opciones)) {
                $arrResponse = array("status" => false, "msg" => 'Debe agregar al menos una opción de respuesta.');
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                die();
            }

            // Lógica de Inserción / Actualización
            if ($id == 0) {
                // Crear
                if($_SESSION['permisosMod']['w']){
                    // Se inserta por defecto activa (1)
                    $request = $this->model->insertPregunta($categoriaId, $textoPregunta, $tipoPregunta, $opciones);
                    $option = 1;
                }
            } else {
                // Actualizar
                if($_SESSION['permisosMod']['u']){
                    $request = $this->model->updatePregunta($id, $categoriaId, $textoPregunta, $tipoPregunta, $activo, $opciones);
                    $option = 2;
                }
            }

            if (isset($request)) {
                if ($request > 0) {
                    $msg = ($option == 1) ? 'Pregunta creada y opciones guardadas exitosamente.' : 'Pregunta y opciones actualizadas correctamente.';
                    $arrResponse = array('status' => true, 'msg' => $msg);
                } else {
                    $arrResponse = array("status" => false, "msg" => "Error al procesar la solicitud o no se realizaron cambios.");
                }
            } else {
                 $arrResponse = array("status" => false, "msg" => "No tiene permisos para realizar esta acción.");
            }

            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    /**
     * Obtiene una pregunta y sus opciones por ID para edición (AJAX)
     */
    public function getPregunta(int $id)
    {
        if($_SESSION['permisosMod']['r']){
            $id = intval(strClean($id)); 
            if ($id > 0) {
                // El modelo trae los datos de la pregunta principal y sus opciones
                $arrData = $this->model->selectPregunta($id);
                
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
     * Elimina lógicamente una pregunta (AJAX)
     */
    public function delPregunta()
    {
        if ($_POST && $_SESSION['permisosMod']['d']) {
            $id = intval($_POST['id']);
            $request_delete = $this->model->deletePregunta($id);
            
            if ($request_delete) {
                $arrResponse = array('status' => true, 'msg' => 'Pregunta eliminada lógicamente.');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al intentar eliminar la pregunta.');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
}
?>