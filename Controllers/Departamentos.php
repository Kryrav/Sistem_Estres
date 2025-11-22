<?php 

class Departamentos extends Controllers{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if(empty($_SESSION['login']))
        {
            header('Location: '.base_url().'/login');
        }
        // Asumiendo Módulo 4: Departamentos (según tabla 'modulo' en el dump)
        getPermisos(4); 
    }

    public function Departamentos()
    {
        if(empty($_SESSION['permisosMod']['r'])){
            header("Location:".base_url().'/dashboard');
        }
        $data['page_id'] = 4;
        $data['page_tag'] = "Departamentos";
        $data['page_name'] = "departamentos_sistema";
        $data['page_title'] = "Departamentos <small> Sistema</small>";
        $data['page_functions_js'] = "functions_departamentos.js";
        $this->views->getView($this,"departamentos",$data);
    }

    public function getDepartamentos()
    {
        if($_SESSION['permisosMod']['r']){
            $btnEdit = '';
            $btnDelete = '';
            $arrData = $this->model->selectDepartamentos();

            for ($i=0; $i < count($arrData); $i++) {
                
                $idDepartamento = $arrData[$i]['id'];

                // Como la tabla no tiene 'status', forzamos el display a 'Activo'
                $arrData[$i]['status'] = '<span class="badge badge-success">Activo</span>'; 
                
                // Botones de acción
                if($_SESSION['permisosMod']['u']){
                    $btnEdit = '<button class="btn btn-primary btn-sm btnEditDepartamento" onClick="fntEditDepartamento('.$idDepartamento.')" title="Editar"><i class="fas fa-pencil-alt"></i></button>';
                }
                if($_SESSION['permisosMod']['d']){
                    $btnDelete = '<button class="btn btn-danger btn-sm btnDelDepartamento" onClick="fntDelDepartamento('.$idDepartamento.')" title="Eliminar"><i class="far fa-trash-alt"></i></button>';
                }
                $arrData[$i]['options'] = '<div class="text-center">'.$btnEdit.' '.$btnDelete.'</div>';
                
                // Mapeo de ID para el front-end (si tu DataTables lo necesita)
                $arrData[$i]['iddepartamento'] = $idDepartamento; 
            }
            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getDepartamento(int $idDepartamento)
    {
        if($_SESSION['permisosMod']['r']){
            $intIdDepartamento = intval(strClean($idDepartamento));
            if($intIdDepartamento > 0)
            {
                $arrData = $this->model->selectDepartamento($intIdDepartamento);
                if(empty($arrData))
                {
                    $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
                }else{
                    $arrResponse = array('status' => true, 'data' => $arrData);
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }

    public function setDepartamento(){
        if($_POST){
            $intIdDepartamento = intval($_POST['idDepartamento']);
            $strNombre = strClean($_POST['txtNombre']);
            $strDescripcion = strClean($_POST['txtDescripcion']);
            $intUmbral = intval($_POST['intUmbralAlerta']); // Nuevo campo

            if(empty($strNombre) || empty($strDescripcion) || empty($intUmbral)){
                $arrResponse = array("status" => false, "msg" => 'Todos los campos son obligatorios.');
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                die();
            }

            $request_depto = "";
            $option = 0;

            if($intIdDepartamento == 0)
            {
                // Crear
                if($_SESSION['permisosMod']['w']){
                    $request_depto = $this->model->insertDepartamento($strNombre, $strDescripcion, $intUmbral);
                    $option = 1;
                }
            }else{
                // Actualizar
                if($_SESSION['permisosMod']['u']){
                    $request_depto = $this->model->updateDepartamento($intIdDepartamento, $strNombre, $strDescripcion, $intUmbral);
                    $option = 2;
                }   
            }

            if($request_depto > 0 )
            {
                $msg = ($option == 1) ? 'Departamento guardado correctamente.' : 'Departamento actualizado correctamente.';
                $arrResponse = array('status' => true, 'msg' => $msg);
            }else if($request_depto == 'exist'){
                $arrResponse = array('status' => false, 'msg' => '¡Atención! El Departamento ya existe.');
            }else{
                $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
            }
        }else{
            $arrResponse = array("status" => false, "msg" => 'Datos no recibidos.');
        }
        echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
        die();
    }

    public function delDepartamento()
    {
        if($_POST){
            if($_SESSION['permisosMod']['d']){
                $intIdDepartamento = intval($_POST['idDepartamento']);
                $requestDelete = $this->model->deleteDepartamento($intIdDepartamento);
                if($requestDelete == 'ok')
                {
                    $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Departamento');
                }else if($requestDelete == 'exist'){
                    $arrResponse = array('status' => false, 'msg' => 'No es posible eliminar un Departamento asociado a trabajadores.');
                }else{
                    $arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Departamento.');
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }
}