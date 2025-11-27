<?php 
    class Intervenciones extends Controllers{
        public function __construct()
        {
            parent::__construct();
            session_start();
            if(empty($_SESSION['login']))
            {
                header('Location: '.base_url().'/login');
            }
            getPermisos(41); // Módulo 40 para Intervenciones
        }

        public function Intervenciones()
        {
            if(empty($_SESSION['permisosMod']['r'])){
                header("Location:".base_url().'/dashboard');
            }
            $data['page_id'] = 14;
            $data['page_tag'] = "Intervenciones del Sistema";
            $data['page_name'] = "intervenciones";
            $data['page_title'] = "Intervenciones <small> Sistema MSB</small>";
            $data['page_functions_js'] = "functions_intervenciones.js";
            $this->views->getView($this,"intervenciones",$data);
        }

        public function getIntervenciones()
        {
            if($_SESSION['permisosMod']['r']){
                $btnView = '';
                $btnEdit = '';
                $btnDelete = '';
                $arrData = $this->model->selectIntervenciones();

                for ($i=0; $i < count($arrData); $i++) {

                    // Formatear tipo de alerta
                    switch($arrData[$i]['tipo_alerta']){
                        case 'descanso_sugerido':
                            $arrData[$i]['tipo_alerta_formatted'] = '<span class="badge badge-info"><i class="fas fa-coffee"></i> Descanso Sugerido</span>';
                            break;
                        case 'redistribucion_carga':
                            $arrData[$i]['tipo_alerta_formatted'] = '<span class="badge badge-warning"><i class="fas fa-balance-scale"></i> Redistribución Carga</span>';
                            break;
                        case 'alerta_burnout':
                            $arrData[$i]['tipo_alerta_formatted'] = '<span class="badge badge-danger"><i class="fas fa-exclamation-triangle"></i> Alerta Burnout</span>';
                            break;
                        case 'felicitacion':
                            $arrData[$i]['tipo_alerta_formatted'] = '<span class="badge badge-success"><i class="fas fa-trophy"></i> Felicitación</span>';
                            break;
                        default:
                            $arrData[$i]['tipo_alerta_formatted'] = '<span class="badge badge-secondary">'.$arrData[$i]['tipo_alerta'].'</span>';
                    }

                    // Formatear estado
                    switch($arrData[$i]['estado']){
                        case 'pendiente':
                            $arrData[$i]['estado_formatted'] = '<span class="badge badge-warning">Pendiente</span>';
                            break;
                        case 'leida':
                            $arrData[$i]['estado_formatted'] = '<span class="badge badge-primary">Leída</span>';
                            break;
                        case 'aplicada':
                            $arrData[$i]['estado_formatted'] = '<span class="badge badge-success">Aplicada</span>';
                            break;
                        case 'ignorada':
                            $arrData[$i]['estado_formatted'] = '<span class="badge badge-secondary">Ignorada</span>';
                            break;
                        default:
                            $arrData[$i]['estado_formatted'] = '<span class="badge badge-light">'.$arrData[$i]['estado'].'</span>';
                    }

                    // Formatear fecha
                    $arrData[$i]['fecha_formatted'] = date('d/m/Y H:i', strtotime($arrData[$i]['fecha_generada']));

                    // Botones de acción
                    if($_SESSION['permisosMod']['u']){
                        $btnEdit = '<button class="btn btn-primary btn-sm btnEditIntervencion" onClick="fntEditIntervencion('.$arrData[$i]['id'].')" title="Editar Estado"><i class="fas fa-pencil-alt"></i></button>';
                    }
                    if($_SESSION['permisosMod']['d']){
                        $btnDelete = '<button class="btn btn-danger btn-sm btnDelIntervencion" onClick="fntDelIntervencion('.$arrData[$i]['id'].')" title="Eliminar"><i class="far fa-trash-alt"></i></button>';
                    }

                    $arrData[$i]['options'] = '<div class="text-center">'.$btnEdit.' '.$btnDelete.'</div>';
                }
                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
            }
            die();
        }

        public function getIntervencion(int $idintervencion)
        {
            if($_SESSION['permisosMod']['r']){
                $intIdintervencion = intval(strClean($idintervencion));
                if($intIdintervencion > 0)
                {
                    $arrData = $this->model->selectIntervencion($intIdintervencion);
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

        public function setIntervencion(){
            $intIdIntervencion = intval($_POST['idIntervencion']);
            $strEstado = strClean($_POST['listEstado']);
            
            $request_intervencion = "";
            
            if($intIdIntervencion == 0)
            {
                // CREAR NUEVA INTERVENCIÓN
                $intTrabajador = intval($_POST['listTrabajador']);
                $strTipoAlerta = strClean($_POST['listTipoAlerta']);
                $strMensaje = strClean($_POST['txtMensaje']);
                
                if($_SESSION['permisosMod']['w']){
                    $request_intervencion = $this->model->insertIntervencion($intTrabajador, $strTipoAlerta, $strMensaje, $strEstado);
                    $option = 1;
                }
            }else{
                // ACTUALIZAR SOLO EL ESTADO (los demás campos vienen disabled)
                if($_SESSION['permisosMod']['u']){
                    $request_intervencion = $this->model->updateIntervencion($intIdIntervencion, $strEstado);
                    $option = 2;
                }		
            }

            if($request_intervencion > 0 )
            {
                if($option == 1)
                {
                    $arrResponse = array('status' => true, 'msg' => 'Intervención guardada correctamente.');
                }else{
                    $arrResponse = array('status' => true, 'msg' => 'Intervención actualizada correctamente.');
                }
            }else{
                $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
            }
            echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            die();
        }

        public function delIntervencion()
        {
            if($_POST){
                if($_SESSION['permisosMod']['d']){
                    $intIdintervencion = intval($_POST['idintervencion']);
                    $requestDelete = $this->model->deleteIntervencion($intIdintervencion);
                    if($requestDelete)
                    {
                        $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado la Intervención');
                    }else{
                        $arrResponse = array('status' => false, 'msg' => 'Error al eliminar la Intervención.');
                    }
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }

        // Obtener estadísticas para dashboard
        public function getEstadisticas()
        {
            if($_SESSION['permisosMod']['r']){
                $arrData = $this->model->getEstadisticasIntervenciones();
                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
            }
            die();
        }

        // Obtener intervenciones pendientes
        public function getPendientes()
        {
            if($_SESSION['permisosMod']['r']){
                $arrData = $this->model->getIntervencionesPendientes();
                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
            }
            die();
        }

        // Obtener intervenciones por trabajador
        public function getIntervencionesTrabajador(int $idTrabajador)
        {
            if($_SESSION['permisosMod']['r']){
                $intIdTrabajador = intval(strClean($idTrabajador));
                if($intIdTrabajador > 0)
                {
                    $arrData = $this->model->selectIntervencionesTrabajador($intIdTrabajador);
                    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }
    }
?>