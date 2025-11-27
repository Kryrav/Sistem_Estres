<?php 
    class Indicadores extends Controllers{
        public function __construct()
        {
            parent::__construct();
            session_start();
            if(empty($_SESSION['login']))
            {
                header('Location: '.base_url().'/login');
            }
            getPermisos(31); // Módulo 31 para Indicadores de Estrés
        }

        public function Indicadores()
        {
            if(empty($_SESSION['permisosMod']['r'])){
                header("Location:".base_url().'/dashboard');
            }
            $data['page_id'] = 31;
            $data['page_tag'] = "Indicadores de Estrés";
            $data['page_name'] = "indicadores";
            $data['page_title'] = "Indicadores de Estrés <small> Sistema MSB</small>";
            $data['page_functions_js'] = "functions_indicadores.js";
            $this->views->getView($this,"indicadores",$data);
        }

        public function getIndicadores()
        {
            if($_SESSION['permisosMod']['r']){
                $btnView = '';
                $btnEdit = '';
                $btnDelete = '';
                $arrData = $this->model->selectIndicadores();

                for ($i=0; $i < count($arrData); $i++) {

                    // Formatear nivel de estrés
                    if($arrData[$i]['nivel_estres'] <= 3.5){
                        $arrData[$i]['nivel_estres_formatted'] = '<span class="badge badge-success">Bajo ('.$arrData[$i]['nivel_estres'].')</span>';
                    }else if($arrData[$i]['nivel_estres'] <= 6.5){
                        $arrData[$i]['nivel_estres_formatted'] = '<span class="badge badge-warning">Medio ('.$arrData[$i]['nivel_estres'].')</span>';
                    }else{
                        $arrData[$i]['nivel_estres_formatted'] = '<span class="badge badge-danger">Alto ('.$arrData[$i]['nivel_estres'].')</span>';
                    }

                    // Formatear categoría
                    $arrData[$i]['categoria_formatted'] = '<span class="badge badge-info">'.$arrData[$i]['categoria'].'</span>';

                    // Formatear fecha
                    $arrData[$i]['fecha_formatted'] = date('d/m/Y H:i', strtotime($arrData[$i]['fecha_calculo']));

                    // Botones de acción
                    if($_SESSION['permisosMod']['u']){
                        $btnEdit = '<button class="btn btn-primary btn-sm btnEditIndicador" onClick="fntEditIndicador('.$arrData[$i]['id'].')" title="Editar"><i class="fas fa-pencil-alt"></i></button>';
                    }
                    if($_SESSION['permisosMod']['d']){
                        $btnDelete = '<button class="btn btn-danger btn-sm btnDelIndicador" onClick="fntDelIndicador('.$arrData[$i]['id'].')" title="Eliminar"><i class="far fa-trash-alt"></i></button>';
                    }

                    $arrData[$i]['options'] = '<div class="text-center">'.$btnEdit.' '.$btnDelete.'</div>';
                }
                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
            }
            die();
        }

        public function getIndicador(int $idindicador)
        {
            if($_SESSION['permisosMod']['r']){
                $intIdindicador = intval(strClean($idindicador));
                if($intIdindicador > 0)
                {
                    $arrData = $this->model->selectIndicador($intIdindicador);
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

        public function setIndicador(){
            $intIdIndicador = intval($_POST['idIndicador']);
            $intTrabajador = intval($_POST['listTrabajador']);
            $intDepartamento = intval($_POST['listDepartamento']);
            $strNivelEstres = strClean($_POST['txtNivelEstres']);
            $strCategoria = strClean($_POST['listCategoria']);
            $strMetodoCalculo = strClean($_POST['listMetodoCalculo']);
            
            $request_indicador = "";
            
            if($intIdIndicador == 0)
            {
                //Crear
                if($_SESSION['permisosMod']['w']){
                    $request_indicador = $this->model->insertIndicador($intTrabajador, $intDepartamento, $strNivelEstres, $strCategoria, $strMetodoCalculo);
                    $option = 1;
                }
            }else{
                //Actualizar
                if($_SESSION['permisosMod']['u']){
                    $request_indicador = $this->model->updateIndicador($intIdIndicador, $strNivelEstres, $strCategoria, $strMetodoCalculo);
                    $option = 2;
                }		
            }

            if($request_indicador > 0 )
            {
                if($option == 1)
                {
                    $arrResponse = array('status' => true, 'msg' => 'Indicador guardado correctamente.');
                }else{
                    $arrResponse = array('status' => true, 'msg' => 'Indicador actualizado correctamente.');
                }
            }else{
                $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
            }
            echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            die();
        }

        public function delIndicador()
        {
            if($_POST){
                if($_SESSION['permisosMod']['d']){
                    $intIdindicador = intval($_POST['idindicador']);
                    $requestDelete = $this->model->deleteIndicador($intIdindicador);
                    if($requestDelete)
                    {
                        $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Indicador');
                    }else{
                        $arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Indicador.');
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }

        // Métodos para estadísticas
        public function getEstadisticas()
        {
            if($_SESSION['permisosMod']['r']){
                $arrData = $this->model->getEstadisticasIndicadores();
                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
            }
            die();
        }

        public function getTendencias()
        {
            if($_SESSION['permisosMod']['r']){
                $periodo = isset($_GET['periodo']) ? strClean($_GET['periodo']) : '30 DAY';
                $arrData = $this->model->getTendenciasPeriodo($periodo);
                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
            }
            die();
        }

        public function getDistribucion()
        {
            if($_SESSION['permisosMod']['r']){
                $arrData = $this->model->getDistribucionCategoria();
                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
            }
            die();
        }

        public function getTopTrabajadores()
        {
            if($_SESSION['permisosMod']['r']){
                $limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
                $arrData = $this->model->getTopTrabajadoresEstres($limite);
                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
            }
            die();
        }

        // Obtener indicadores por trabajador
        public function getIndicadoresTrabajador(int $idTrabajador)
        {
            if($_SESSION['permisosMod']['r']){
                $intIdTrabajador = intval(strClean($idTrabajador));
                if($intIdTrabajador > 0)
                {
                    $arrData = $this->model->selectIndicadoresTrabajador($intIdTrabajador);
                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }

        // Obtener indicadores por departamento
        public function getIndicadoresDepartamento(int $idDepartamento)
        {
            if($_SESSION['permisosMod']['r']){
                $intIdDepartamento = intval(strClean($idDepartamento));
                if($intIdDepartamento > 0)
                {
                    $arrData = $this->model->selectIndicadoresDepartamento($intIdDepartamento);
                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
    }
?>