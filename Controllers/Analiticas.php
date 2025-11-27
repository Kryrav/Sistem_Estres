<?php 
    class Analiticas extends Controllers{
        public function __construct()
        {
            parent::__construct();
            session_start();
            if(empty($_SESSION['login']))
            {
                header('Location: '.base_url().'/login');
            }
            getPermisos(42); 
        }

        public function Analiticas()
        {
            if(empty($_SESSION['permisosMod']['r'])){
                header("Location:".base_url().'/dashboard');
            }
            $data['page_id'] = 13;
            $data['page_tag'] = "Analíticas y Reportes";
            $data['page_name'] = "analiticas";
            $data['page_title'] = "Analíticas de Estrés <small> Sistema MSB</small>";
            $data['page_functions_js'] = "functions_analiticas.js";
            $this->views->getView($this,"analiticas",$data);
        }

        public function getReportes()
        {
            if($_SESSION['permisosMod']['r']){
                $btnView = '';
                $btnEdit = '';
                $btnDelete = '';
                $arrData = $this->model->selectReportes();

                for ($i=0; $i < count($arrData); $i++) {

                    // Formatear nivel de estrés
                    if($arrData[$i]['nivel_general_estres'] <= 3.5){
                        $arrData[$i]['nivel_estres_formatted'] = '<span class="badge badge-success">Bajo ('.$arrData[$i]['nivel_general_estres'].')</span>';
                    }else if($arrData[$i]['nivel_general_estres'] <= 6.5){
                        $arrData[$i]['nivel_estres_formatted'] = '<span class="badge badge-warning">Medio ('.$arrData[$i]['nivel_general_estres'].')</span>';
                    }else{
                        $arrData[$i]['nivel_estres_formatted'] = '<span class="badge badge-danger">Alto ('.$arrData[$i]['nivel_general_estres'].')</span>';
                    }

                    // Botones de acción
                    if($_SESSION['permisosMod']['u']){
                        $btnEdit = '<button class="btn btn-primary btn-sm btnEditReporte" onClick="fntEditReporte('.$arrData[$i]['id'].')" title="Editar"><i class="fas fa-pencil-alt"></i></button>';
                    }
                    if($_SESSION['permisosMod']['d']){
                        $btnDelete = '<button class="btn btn-danger btn-sm btnDelReporte" onClick="fntDelReporte('.$arrData[$i]['id'].')" title="Eliminar"><i class="far fa-trash-alt"></i></button>';
                    }

                    $arrData[$i]['options'] = '<div class="text-center">'.$btnEdit.' '.$btnDelete.'</div>';
                }
                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
            }
            die();
        }

        public function getReporte(int $idreporte)
        {
            if($_SESSION['permisosMod']['r']){
                $intIdreporte = intval(strClean($idreporte));
                if($intIdreporte > 0)
                {
                    $arrData = $this->model->selectReporte($intIdreporte);
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

        public function setReporte(){
            $intIdreporte = intval($_POST['idReporte']);
            $intDepartamento = intval($_POST['listDepartamento']);
            $strNivelEstres = strClean($_POST['txtNivelEstres']);
            $strObservaciones = strClean($_POST['txtObservaciones']);
            
            $request_report = "";
            
            if($intIdreporte == 0)
            {
                //Crear
                if($_SESSION['permisosMod']['w']){
                    $request_report = $this->model->insertReporte($intDepartamento, $strNivelEstres, $strObservaciones, $_SESSION['idUser']);
                    $option = 1;
                }
            }else{
                //Actualizar
                if($_SESSION['permisosMod']['u']){
                    $request_report = $this->model->updateReporte($intIdreporte, $intDepartamento, $strNivelEstres, $strObservaciones);
                    $option = 2;
                }		
            }

            if($request_report > 0 )
            {
                if($option == 1)
                {
                    $arrResponse = array('status' => true, 'msg' => 'Reporte guardado correctamente.');
                }else{
                    $arrResponse = array('status' => true, 'msg' => 'Reporte actualizado correctamente.');
                }
            }else{
                $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
            }
            echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            die();
        }

        public function delReporte()
        {
            if($_POST){
                if($_SESSION['permisosMod']['d']){
                    $intIdreporte = intval($_POST['idreporte']);
                    $requestDelete = $this->model->deleteReporte($intIdreporte);
                    if($requestDelete)
                    {
                        $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Reporte');
                    }else{
                        $arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Reporte.');
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }

        // Métodos para analytics en tiempo real
        public function getEstresDepartamentos()
        {
            if($_SESSION['permisosMod']['r']){
                $arrData = $this->model->getEstresPorDepartamento();
                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
            }
            die();
        }

        public function getTendencias()
        {
            if($_SESSION['permisosMod']['r']){
                $arrData = $this->model->getTendenciasEstres();
                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
            }
            die();
        }

        public function getMetricasGenerales()
        {
            if($_SESSION['permisosMod']['r']){
                $data = array(
                    'estres_departamentos' => $this->model->getEstresPorDepartamento(),
                    'tendencias' => $this->model->getTendenciasEstres(),
                    'intervenciones' => $this->model->getTopIntervenciones(),
                    'metricas_encuestas' => $this->model->getMetricasEncuestas()
                );
                echo json_encode($data,JSON_UNESCAPED_UNICODE);
            }
            die();
        }
    }
?>