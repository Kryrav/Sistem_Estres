<?php
class Tareas extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if(empty($_SESSION['login'])){
            header('Location: '.base_url().'/login');
            exit;
        }
        getPermisos(5); // 5 = ID del módulo Tareas
    }

    // Vista principal de Tareas (Admin/Supervisor)
    public function Tareas()
    {
        if(empty($_SESSION['permisosMod']['r'])){
            header("Location: ".base_url().'/dashboard');
            exit;
        }

        $data['page_id'] = 5;
        $data['page_tag'] = "Tareas";
        $data['page_name'] = "tareas";
        $data['page_title'] = "Gestión de Tareas <small>Sistema</small>";
        $data['page_functions_js'] = "functions_tareas.js";

        // Traer trabajadores y tipos de tarea
        $data['trabajadores'] = $this->model->selectTrabajadores();
        $data['tipos_tarea'] = $this->model->selectTiposTarea();

        $this->views->getView($this,"tareas",$data);
    }

    // Vista de Mis Tareas (para trabajadores)
    public function misTareas()
    {
        if(empty($_SESSION['permisosMod']['r'])){
            header("Location: ".base_url().'/dashboard');
            exit;
        }

        $data['page_id'] = 5;
        $data['page_tag'] = "Mis Tareas";
        $data['page_name'] = "mis_tareas";
        $data['page_title'] = "Mis Tareas Asignadas <small>Sistema</small>";
        $data['page_functions_js'] = "functions_mis_tareas.js";

        $this->views->getView($this,"mistareas",$data);
    }

    // =========================================================================
    // MÉTODOS PARA ADMIN/SUPERVISOR
    // =========================================================================

    // Listar todas las tareas para DataTable
    public function listar()
    {
        if($_SESSION['permisosMod']['r']){
            $arrData = $this->model->selectTareas();
            for($i = 0; $i < count($arrData); $i++){
                $btnEdit = $btnDelete = "";

                if($_SESSION['permisosMod']['u']){
                    $btnEdit = '<button class="btn btn-primary btn-sm" onclick="fntEditTarea('.$arrData[$i]['id'].')" title="Editar"><i class="fas fa-pencil-alt"></i></button>';
                }
                if($_SESSION['permisosMod']['d']){
                    $btnDelete = '<button class="btn btn-danger btn-sm" onclick="fntDelTarea('.$arrData[$i]['id'].')" title="Eliminar"><i class="far fa-trash-alt"></i></button>';
                }

                $arrData[$i]['options'] = '<div class="text-center">'.$btnEdit.' '.$btnDelete.'</div>';
            }
            echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    // Guardar nueva tarea o actualizar existente
    public function guardar()
    {
        $id = intval($_POST['idTarea']);
        $titulo = strClean($_POST['titulo']);
        $descripcion = strClean($_POST['descripcion']);
        $trabajador_id = intval($_POST['trabajador_id']);
        $tipo_tarea_id = intval($_POST['tipo_tarea_id']);
        $minutos_estimados = intval($_POST['minutos_estimados']);
        $estado = strClean($_POST['estado']);

        if($id == 0){
            if($_SESSION['permisosMod']['w']){
                $request = $this->model->insertTarea($titulo, $descripcion, $trabajador_id, $tipo_tarea_id, $minutos_estimados, $estado);
            }
        } else {
            if($_SESSION['permisosMod']['u']){
                $request = $this->model->updateTarea($id, $titulo, $descripcion, $trabajador_id, $tipo_tarea_id, $minutos_estimados, $estado);
            }
        }

        $msg = $request > 0 ? "Datos guardados correctamente" : "Error al guardar los datos";
        echo json_encode(array("status" => ($request > 0), "msg" => $msg), JSON_UNESCAPED_UNICODE);
        die();
    }

    // Eliminar tarea
    public function eliminar(int $id)
    {
        if($_SESSION['permisosMod']['d']){
            $request = $this->model->deleteTarea($id);
            $msg = $request > 0 ? "Tarea eliminada correctamente" : "Error al eliminar la tarea";
            echo json_encode(array("status" => ($request > 0), "msg" => $msg), JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    // Obtener datos de una tarea específica
    public function getTarea(int $id)
    {
        $request = $this->model->selectTarea($id);
        if(!empty($request)){
            $arrResponse = ['status' => true, 'data' => $request];
        } else {
            $arrResponse = ['status' => false, 'msg' => 'Datos no encontrados'];
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    // =========================================================================
    // MÉTODOS PARA TRABAJADORES
    // =========================================================================

    // Listar tareas del trabajador actual para DataTable
    public function listarMisTareas()
    {
        if($_SESSION['permisosMod']['r']){
            // Obtener ID del trabajador actual
            $trabajadorModel = new TrabajadorModel();
            $trabajador = $trabajadorModel->getTrabajadorByPersona($_SESSION['idUser']);
            
            if($trabajador){
                $arrData = $this->model->selectTareasByTrabajador($trabajador['id']);
                
                for($i = 0; $i < count($arrData); $i++){
                    // Formatear estados con badges
                    $arrData[$i]['estado_badge'] = $this->getBadgeEstado($arrData[$i]['estado']);
                    
                    // Formatear fechas
                    if($arrData[$i]['fecha_vencimiento']){
                        $arrData[$i]['fecha_vencimiento_formateada'] = 
                            date('d/m/Y', strtotime($arrData[$i]['fecha_vencimiento']));
                    } else {
                        $arrData[$i]['fecha_vencimiento_formateada'] = 'Sin fecha';
                    }
                    
                    // Botones de acción
                    $btnUpdate = '';
                    if($_SESSION['permisosMod']['u']){
                        $btnUpdate = '<button class="btn btn-primary btn-sm" onclick="fntUpdateEstado('.$arrData[$i]['id'].')" title="Actualizar Estado"><i class="fas fa-edit"></i></button>';
                    }
                    
                    $arrData[$i]['options'] = '<div class="text-center">'.$btnUpdate.'</div>';
                }
                
                echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode([], JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }

    // Actualizar estado de tarea (para trabajadores)
    public function actualizarEstado()
    {
        if($_POST && $_SESSION['permisosMod']['u']){
            $id = intval($_POST['idTarea']);
            $estado = strClean($_POST['estado']);
            $minutos_reales = !empty($_POST['minutos_reales']) ? intval($_POST['minutos_reales']) : null;
            $motivo_bloqueo = !empty($_POST['motivo_bloqueo']) ? strClean($_POST['motivo_bloqueo']) : null;
            
            // Verificar que la tarea pertenezca al trabajador actual
            $trabajadorModel = new TrabajadorModel();
            $trabajador = $trabajadorModel->getTrabajadorByPersona($_SESSION['idUser']);
            
            if($trabajador && $this->model->checkTareaTrabajador($id, $trabajador['id'])){
                $request = $this->model->updateEstadoTarea($id, $estado, $minutos_reales, $motivo_bloqueo);
                
                // Si la tarea se marca como terminada, registrar en bitácora
                if($request && $estado == 'terminado'){
                    $bitacoraModel = new BitacoraModel();
                    $bitacoraModel->insertRegistroAutomatico(
                        $trabajador['id'], 
                        'cierre_tarea', 
                        $id
                    );
                }
                
                $msg = $request ? "Estado actualizado correctamente" : "Error al actualizar el estado";
                $arrResponse = array("status" => $request, "msg" => $msg);
            } else {
                $arrResponse = array("status" => false, "msg" => "No tienes permisos para actualizar esta tarea");
            }
            
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    // Helper para badges de estado
    private function getBadgeEstado($estado)
    {
        $estados = [
            'backlog' => ['badge' => 'secondary', 'text' => 'Pendiente'],
            'listo' => ['badge' => 'info', 'text' => 'Listo'],
            'en_progreso' => ['badge' => 'primary', 'text' => 'En Progreso'],
            'bloqueado' => ['badge' => 'warning', 'text' => 'Bloqueado'],
            'revision' => ['badge' => 'info', 'text' => 'En Revisión'],
            'terminado' => ['badge' => 'success', 'text' => 'Terminado']
        ];
        
        $estadoData = $estados[$estado] ?? $estados['backlog'];
        return '<span class="badge badge-'.$estadoData['badge'].'">'.$estadoData['text'].'</span>';
    }
}
?>