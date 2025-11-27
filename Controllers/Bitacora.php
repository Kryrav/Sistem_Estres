<?php 
class Bitacora extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if(empty($_SESSION['login']))
        {
            header('Location: '.base_url().'/login');
        }
        getPermisos(32); // Módulo 7: Bitácora Emocional
    }

    public function bitacora()
    {
        if(empty($_SESSION['permisosMod']['r'])){
            header("Location:".base_url().'/dashboard');
        }
        $data['page_tag'] = "Bitácora Emocional";
        $data['page_name'] = "bitacora";
        $data['page_title'] = "Bitácora Emocional <small>Sistema de Estrés</small>";
        $data['page_functions_js'] = "functions_bitacora.js";
        $this->views->getView($this,"bitacora",$data);
    }
    // =========================================================================
    // MÉTODOS PRINCIPALES
    // =========================================================================

    public function getRegistros()
    {
        if($_SESSION['permisosMod']['r']){
            $arrData = $this->model->selectByTrabajador($_SESSION['idUser']);

            for ($i=0; $i < count($arrData); $i++) {
                // Formatear fecha y hora
                $arrData[$i]['fecha_formateada'] = date('d/m/Y', strtotime($arrData[$i]['fecha']));
                $arrData[$i]['hora_formateada'] = date('h:i A', strtotime($arrData[$i]['hora']));

                // Badge para tipo de registro
                $badgeColor = $this->getBadgeColorTipo($arrData[$i]['tipo_registro']);
                $arrData[$i]['tipo_badge'] = '<span class="badge '.$badgeColor.'">'.$this->getTipoTexto($arrData[$i]['tipo_registro']).'</span>';

                // Badge para nivel de estrés
                if($arrData[$i]['nivel_stress_percibido'] !== null) {
                    $arrData[$i]['stress_badge'] = $this->getBadgeStress($arrData[$i]['nivel_stress_percibido']);
                } else {
                    $arrData[$i]['stress_badge'] = '<span class="badge badge-secondary">No registrado</span>';
                }

                // Badge para energía
                if($arrData[$i]['nivel_energia'] !== null) {
                    $arrData[$i]['energia_badge'] = $this->getBadgeEnergia($arrData[$i]['nivel_energia']);
                } else {
                    $arrData[$i]['energia_badge'] = '<span class="badge badge-secondary">No registrado</span>';
                }

                // Sentimiento
                if($arrData[$i]['sentimiento_predominante'] !== null) {
                    $arrData[$i]['sentimiento_texto'] = $this->getSentimientoTexto($arrData[$i]['sentimiento_predominante']);
                } else {
                    $arrData[$i]['sentimiento_texto'] = 'No registrado';
                }

                // Botones de acción
                $btnView = '';
                $btnEdit = '';
                $btnDelete = '';

                if($_SESSION['permisosMod']['r']){
                    $btnView = '<button class="btn btn-info btn-sm btnViewRegistro" onClick="fntViewRegistro('.$arrData[$i]['id'].')" title="Ver Detalles"><i class="fas fa-eye"></i></button>';
                }
                if($_SESSION['permisosMod']['u']){
                    $btnEdit = '<button class="btn btn-primary btn-sm btnEditRegistro" onClick="fntEditRegistro('.$arrData[$i]['id'].')" title="Editar"><i class="fas fa-pencil-alt"></i></button>';
                }
                if($_SESSION['permisosMod']['d']){
                    $btnDelete = '<button class="btn btn-danger btn-sm btnDelRegistro" onClick="fntDelRegistro('.$arrData[$i]['id'].')" title="Eliminar"><i class="far fa-trash-alt"></i></button>';
                }
                $arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>';
            }
            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getRegistro(int $idRegistro)
    {
        if($_SESSION['permisosMod']['r']){
            $intIdRegistro = intval(strClean($idRegistro));
            if($intIdRegistro > 0)
            {
                $arrData = $this->model->selectRegistro($intIdRegistro);
                if(empty($arrData))
                {
                    $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
                }else{
                    // Verificar que el registro pertenezca al usuario actual
                    $trabajadorModel = new TrabajadorModel();
                    $trabajador = $trabajadorModel->getTrabajadorByPersona($_SESSION['idUser']);
                    
                    if($trabajador && $arrData['trabajador_id'] == $trabajador['id']){
                        $arrResponse = array('status' => true, 'data' => $arrData);
                    }else{
                        $arrResponse = array('status' => false, 'msg' => 'No tienes permisos para ver este registro.');
                    }
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }

    public function setRegistro(){
        if($_POST){
            // Obtener ID del trabajador actual
            $trabajadorModel = new TrabajadorModel();
            $trabajador = $trabajadorModel->getTrabajadorByPersona($_SESSION['idUser']);
            
            if(!$trabajador){
                $arrResponse = array('status' => false, 'msg' => 'No se encontró información del trabajador.');
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                die();
            }

            $intIdRegistro = intval($_POST['idRegistro']);
            $intTareaId = !empty($_POST['listTarea']) ? intval($_POST['listTarea']) : null;
            $strTipoRegistro = strClean($_POST['listTipoRegistro']);
            $intEnergia = !empty($_POST['txtEnergia']) ? intval($_POST['txtEnergia']) : null;
            $intStress = !empty($_POST['txtStress']) ? intval($_POST['txtStress']) : null;
            $strSentimiento = !empty($_POST['listSentimiento']) ? strClean($_POST['listSentimiento']) : null;
            $strComentario = !empty($_POST['txtComentario']) ? strClean($_POST['txtComentario']) : null;

            // Validaciones
            if($strTipoRegistro == ""){
                $arrResponse = array('status' => false, 'msg' => 'El tipo de registro es obligatorio.');
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                die();
            }

            if($intIdRegistro == 0)
            {
                // Crear nuevo registro
                if($_SESSION['permisosMod']['w']){
                    $request_registro = $this->model->insertRegistro(
                        $trabajador['id'], 
                        $intTareaId, 
                        $strTipoRegistro, 
                        $intEnergia, 
                        $intStress, 
                        $strSentimiento, 
                        $strComentario
                    );
                    
                    if($request_registro > 0){
                        $arrResponse = array('status' => true, 'msg' => 'Registro guardado correctamente.');
                    }else{
                        $arrResponse = array('status' => false, 'msg' => 'No es posible almacenar los datos.');
                    }
                }
            }else{
                // Actualizar registro existente
                if($_SESSION['permisosMod']['u']){
                    // Verificar propiedad del registro
                    $registroActual = $this->model->selectRegistro($intIdRegistro);
                    if($registroActual && $registroActual['trabajador_id'] == $trabajador['id']){
                        // Implementar update cuando sea necesario
                        $arrResponse = array('status' => false, 'msg' => 'Función de edición en desarrollo.');
                    }else{
                        $arrResponse = array('status' => false, 'msg' => 'No tienes permisos para editar este registro.');
                    }
                }       
            }
            echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function delRegistro()
    {
        if($_POST){
            if($_SESSION['permisosMod']['d']){
                $intIdRegistro = intval($_POST['idRegistro']);
                
                // Verificar propiedad del registro
                $trabajadorModel = new TrabajadorModel();
                $trabajador = $trabajadorModel->getTrabajadorByPersona($_SESSION['idUser']);
                $registroActual = $this->model->selectRegistro($intIdRegistro);
                
                if($registroActual && $registroActual['trabajador_id'] == $trabajador['id']){
                    $requestDelete = $this->model->deleteRegistro($intIdRegistro);
                    if($requestDelete)
                    {
                        $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el registro');
                    }else{
                        $arrResponse = array('status' => false, 'msg' => 'Error al eliminar el registro.');
                    }
                }else{
                    $arrResponse = array('status' => false, 'msg' => 'No tienes permisos para eliminar este registro.');
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }

    // =========================================================================
    // MÉTODOS PARA BOTÓN PÁNICO Y REGISTROS AUTOMÁTICOS
    // =========================================================================

    public function registroPanico()
    {
        if($_POST){
            $trabajadorModel = new TrabajadorModel();
            $trabajador = $trabajadorModel->getTrabajadorByPersona($_SESSION['idUser']);
            
            if($trabajador){
                $request_registro = $this->model->insertRegistroAutomatico(
                    $trabajador['id'], 
                    'boton_panico'
                );
                
                if($request_registro > 0){
                    $arrResponse = array('status' => true, 'msg' => 'Alerta de estrés registrada. El sistema notificará a tu supervisor.');
                }else{
                    $arrResponse = array('status' => false, 'msg' => 'Error al registrar la alerta.');
                }
            }else{
                $arrResponse = array('status' => false, 'msg' => 'No se encontró información del trabajador.');
            }
            echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    // En Bitacora.php - actualizar el método getMetricasPersonales
    public function getMetricasPersonales()
    {
        if($_SESSION['permisosMod']['r']){
            $trabajadorModel = new TrabajadorModel();
            $trabajador = $trabajadorModel->getTrabajadorByPersona($_SESSION['idUser']);
            
            if($trabajador){
                $metricas = $this->model->getMetricasTrabajador($trabajador['id'], 30);
                $patrones = $this->model->getPatronesEstres($trabajador['id']); // Sin parámetro límite
                $recientes = $this->model->selectRegistrosRecientes($trabajador['id']); // Sin parámetro límite
                
                $arrResponse = array(
                    'status' => true, 
                    'metricas' => $metricas,
                    'patrones' => $patrones,
                    'recientes' => $recientes
                );
            }else{
                $arrResponse = array('status' => false, 'msg' => 'No se encontró información del trabajador.');
            }
            echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    // =========================================================================
    // MÉTODOS HELPERS
    // =========================================================================

    private function getBadgeColorTipo($tipo)
    {
        $colores = [
            'login_checkin' => 'badge-success',
            'cierre_tarea' => 'badge-info',
            'boton_panico' => 'badge-danger',
            'logout' => 'badge-warning',
            'auto' => 'badge-secondary'
        ];
        return $colores[$tipo] ?? 'badge-secondary';
    }

    private function getTipoTexto($tipo)
    {
        $textos = [
            'login_checkin' => 'Check-in',
            'cierre_tarea' => 'Cierre Tarea',
            'boton_panico' => 'Alerta Estrés',
            'logout' => 'Logout',
            'auto' => 'Automático'
        ];
        return $textos[$tipo] ?? $tipo;
    }

    private function getBadgeStress($nivel)
    {
        if($nivel <= 3) return '<span class="badge badge-success">'.$nivel.' - Bajo</span>';
        if($nivel <= 7) return '<span class="badge badge-warning">'.$nivel.' - Medio</span>';
        return '<span class="badge badge-danger">'.$nivel.' - Alto</span>';
    }

    private function getBadgeEnergia($nivel)
    {
        if($nivel <= 3) return '<span class="badge badge-danger">'.$nivel.' - Baja</span>';
        if($nivel <= 7) return '<span class="badge badge-warning">'.$nivel.' - Media</span>';
        return '<span class="badge badge-success">'.$nivel.' - Alta</span>';
    }

    private function getSentimientoTexto($sentimiento)
    {
        $textos = [
            'motivado' => '😊 Motivado',
            'cansado' => '😴 Cansado',
            'frustrado' => '😠 Frustrado',
            'ansioso' => '😰 Ansioso',
            'satisfecho' => '😌 Satisfecho',
            'otro' => '❓ Otro'
        ];
        return $textos[$sentimiento] ?? $sentimiento;
    }

    public function getTareasTrabajador()
    {
        if($_SESSION['permisosMod']['r']){
            // Obtener ID del trabajador actual
            $trabajadorModel = new TrabajadorModel();
            $trabajador = $trabajadorModel->getTrabajadorByPersona($_SESSION['idUser']);
            
            if($trabajador){
                // Cargar modelo de tareas
                $tareasModel = new TareasModel();
                $tareas = $tareasModel->selectTareasByTrabajador($trabajador['id']);
                
                $arrResponse = array('status' => true, 'tareas' => $tareas);
            }else{
                $arrResponse = array('status' => false, 'msg' => 'No se encontró información del trabajador.');
            }
            echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
        }
        die();
    }
}
?>