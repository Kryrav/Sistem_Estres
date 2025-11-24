<?php 
class Encuestas extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new EncuestasModel(); 
        
        session_start();
        // Verificar sesión de login
        if(empty($_SESSION['login']))
        {
            header('Location: '.base_url().'/login');
            die();
        }
        // Módulo 12: Encuestas de Estrés (Ajuste el ID si es necesario)
        getPermisos(12); 
    }

    /**
     * Muestra la vista principal de gestión de encuestas.
     */
    public function Encuestas()
    {
        // Se verifica que el usuario tenga permiso de lectura (r) para el módulo
        if(empty($_SESSION['permisosMod']['r'])){
            header('Location: '.base_url().'/dashboard');
            die();
        }
        
        $data['page_tag'] = "Encuestas de Estrés";
        $data['page_title'] = "Gestión de Encuestas";
        $data['page_name'] = "encuestas";
        // Indica el archivo JavaScript a cargar para el módulo
        $data['page_functions_js'] = "functions_encuestas.js";
        $this->views->getView($this, "encuestas", $data);
    }

    // ----------------------------------------------------------------------
    //                   Operaciones AJAX (CRUD PRINCIPAL)
    // ----------------------------------------------------------------------

    /**
     * Obtiene todas las encuestas para DataTables (AJAX).
     */
    public function getEncuestas()
    {
        if($_SESSION['permisosMod']['r']){
            $arrData = $this->model->selectEncuestas();
            
            for ($i=0; $i < count($arrData); $i++) {
                $id = $arrData[$i]['id'];
                
                // 1. Formato de Estado (ACTIVA, INACTIVA, BORRADOR)
                $estado = $arrData[$i]['estado'];
                if($estado == 'ACTIVA'){
                    $arrData[$i]['estado'] = '<span class="badge badge-success">ACTIVA</span>';
                } elseif($estado == 'INACTIVA') {
                    $arrData[$i]['estado'] = '<span class="badge badge-warning">INACTIVA</span>';
                } else { // BORRADOR
                    $arrData[$i]['estado'] = '<span class="badge badge-info">BORRADOR</span>';
                }

                // 2. Formato de Fechas
                // Se asume que el Modelo ya formatea las fechas a D-M-A para la visualización.
                
                // 3. Botones de Acción (Ver, Editar, Eliminar)
                $btnView = '';
                $btnEdit = '';
                $btnDelete = '';

                // Botón VER (r)
                if($_SESSION['permisosMod']['r']){
                    $btnView = '<button class="btn btn-sm btn-info btnViewEncuesta" onClick="fntViewEncuesta('.$id.')" title="Ver Detalle / Asignar Preguntas"><i class="fas fa-eye"></i></button>';
                }

                // Botón EDITAR (u)
                if($_SESSION['permisosMod']['u']){
                    $btnEdit = '<button class="btn btn-sm btn-primary btnEditEncuesta" onClick="fntEditEncuesta(this, '.$id.')" title="Editar Encuesta"><i class="fas fa-pencil-alt"></i></button>';
                }

                // Botón ELIMINAR (d)
                if($_SESSION['permisosMod']['d']){
                    $btnDelete = '<button class="btn btn-sm btn-danger btnDelEncuesta" onClick="fntDelEncuesta('.$id.')" title="Eliminar Encuesta"><i class="fas fa-trash-alt"></i></button>';
                }
                
                $arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>';
            }

            echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    /**
     * Inserta o Actualiza una Encuesta (AJAX).
     */
    public function setEncuesta()
    {
        if ($_POST) {
            $id = empty($_POST['id']) ? 0 : intval($_POST['id']);
            $titulo = strClean($_POST['txtTitulo']);
            $descripcion = strClean($_POST['txtDescripcion']);
            $fecha_inicio = strClean($_POST['txtFechaInicio']);
            $fecha_fin = strClean($_POST['txtFechaFin']);
            $estado = strClean($_POST['listEstado']); 
            $usuario_creador_id = $_SESSION['idUser']; 

            // Validaciones básicas
            if (empty($titulo) || empty($fecha_inicio) || empty($fecha_fin) || empty($estado)) {
                $arrResponse = array("status" => false, "msg" => 'Todos los campos marcados con * son obligatorios.');
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                die();
            }

            if ($id == 0) {
                // Crear (W)
                if($_SESSION['permisosMod']['w']){
                    // Asume que el modelo tiene el método insertEncuesta()
                    $request = $this->model->insertEncuesta($titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado, $usuario_creador_id);
                    $option = 1;
                }
            } else {
                // Actualizar (U)
                if($_SESSION['permisosMod']['u']){
                    // Asume que el modelo tiene el método updateEncuesta()
                    $request = $this->model->updateEncuesta($id, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado);
                    $option = 2;
                }
            }

            if (isset($request)) {
                if ($request > 0) {
                    $msg = ($option == 1) ? 'Encuesta creada exitosamente. Ahora puede añadir preguntas.' : 'Encuesta actualizada correctamente.';
                    $arrResponse = array('status' => true, 'msg' => $msg);
                } else if ($request === 'exist') {
                    $arrResponse = array('status' => false, 'msg' => '¡Atención! Ya existe una encuesta con ese título.');
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
     * Obtiene una encuesta por ID para edición o visualización (AJAX).
     */
    public function getEncuesta(int $id)
    {
        if($_SESSION['permisosMod']['r']){
            $id = intval(strClean($id)); 
            if ($id > 0) {
                // Selecciona los datos principales de la encuesta
                $arrData = $this->model->selectEncuesta($id);
                
                if (empty($arrData)) {
                    $arrResponse = array('status' => false, 'msg' => 'Datos de encuesta no encontrados.');
                } else {
                    // Obtener las preguntas asignadas para la vista de detalle/edición
                    $arrData['preguntas_asignadas'] = $this->model->selectPreguntasAsignadas($id);
                    
                    $arrResponse = array('status' => true, 'data' => $arrData);
                }
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }
    
    /**
     * Elimina una encuesta verificando dependencias (AJAX).
     */
    public function delEncuesta()
    {
        if ($_POST && $_SESSION['permisosMod']['d']) {
            $id = intval($_POST['id']);
            $request_delete = $this->model->deleteEncuesta($id);
            
            if ($request_delete === 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Encuesta eliminada correctamente.');
            } else if ($request_delete === 'exist') {
                $arrResponse = array('status' => false, 'msg' => 'No es posible eliminar. La encuesta ya tiene respuestas asociadas.');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al intentar eliminar la encuesta.');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
    
    // ----------------------------------------------------------------------
    //                MÉTODOS DE UTILIDAD Y ASIGNACIÓN
    // ----------------------------------------------------------------------

    /**
     * Obtiene todas las encuestas ACTIVA/INACTIVA (para SELECT en otros módulos).
     */
    public function getSelectEncuestas()
    {
        $htmlOptions = "";
        $arrData = $this->model->selectEncuestas(); // Usa selectEncuestas que filtra por !BORRADOR
        
        if(count($arrData) > 0){
            for ($i=0; $i < count($arrData); $i++) {
                // Solo incluye aquellas en estado que permita la selección, excluyendo status 0
                if($arrData[$i]['estado'] != 'BORRADOR'){
                    $htmlOptions .= '<option value="'.$arrData[$i]['id'].'">'.$arrData[$i]['titulo'].' ('.$arrData[$i]['estado'].')</option>';
                }
            }
        }
        echo $htmlOptions;
        die();
    }

    /**
     * Obtiene las preguntas ya asignadas a una encuesta específica (AJAX - Columna Derecha).
     * El frontend lo usa para llenar la lista al cargar la pestaña de asignación.
     */
    public function getPreguntasAsignadas(int $idEncuesta)
    {
        // Resuelve el error de PHP: llama al método del Modelo, no implementa SQL aquí.
        if($_SESSION['permisosMod']['r']){
            $idEncuesta = intval(strClean($idEncuesta));
            $arrData = $this->model->selectPreguntasAsignadas($idEncuesta);
            $htmlList = '';
            
            if(count($arrData) > 0){
                foreach ($arrData as $pregunta) {
                    $htmlList .= '
                        <div class="list-group-item list-group-item-action list-group-item-success draggable-item" data-id="'.$pregunta['id_pregunta'].'">
                            <span class="badge badge-secondary float-right">'.$pregunta['tipo_pregunta'].'</span>
                            <i class="fas fa-arrows-alt handle-icon mr-2"></i> 
                            (Orden: '.$pregunta['orden'].') '.$pregunta['texto_pregunta'].'
                        </div>';
                }
            } else {
                $htmlList = '<div class="list-group-item text-center p-3 text-muted">Arrastre preguntas aquí para asignar y ordenar.</div>';
            }

            // Devuelve JSON para que el JS haga JSON.parse() (soluciona el error de JavaScript)
            $arrResponse = array('status' => true, 'data' => $htmlList, 'count' => count($arrData));
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    /**
     * Obtiene todas las preguntas disponibles (Banco) para la asignación (AJAX - Columna Izquierda)
     */
    public function getPreguntasDisponibles()
    {
        if($_SESSION['permisosMod']['r']){
            // Parámetros de filtrado
            $categoriaId = isset($_POST['categoriaId']) ? intval(strClean($_POST['categoriaId'])) : 0;
            $search = isset($_POST['search']) ? strClean($_POST['search']) : '';
            // Se asume que el frontend envía los IDs ya asignados (aunque no se usen para filtrar en DB, son útiles para la vista)
            $assignedIds = isset($_POST['assignedIds']) ? json_decode($_POST['assignedIds'], true) : [];
            
            // Llama al método del modelo que filtra y obtiene las preguntas
            $arrData = $this->model->selectPreguntasDisponibles($categoriaId, $search);
            $htmlList = '';
            
            if(count($arrData) > 0){
                foreach ($arrData as $pregunta) {
                     if (!in_array($pregunta['id'], $assignedIds)) { // Filtrado visual
                        $htmlList .= '
                            <div class="list-group-item list-group-item-action list-group-item-light draggable-item" data-id="'.$pregunta['id'].'">
                                <span class="badge badge-secondary float-right">'.$pregunta['tipo_pregunta'].'</span>
                                <i class="fas fa-arrows-alt handle-icon mr-2"></i> 
                                ('.$pregunta['categoria'].') '.$pregunta['texto_pregunta'].'
                            </div>';
                    }
                }
            } else {
                $htmlList = '<div class="list-group-item text-center p-3 text-muted">No se encontraron preguntas disponibles con los filtros aplicados.</div>';
            }
            
            echo $htmlList;
        }
        die();
    }

    /**
     * Guarda la asignación y el orden de las preguntas para una encuesta (AJAX)
     */
    public function setAsignacionPreguntas()
    {
        if ($_POST && $_SESSION['permisosMod']['u']) {
            $idEncuesta = intval(strClean($_POST['assignEncuestaId']));
            // El array de IDs de preguntas ordenadas se envía como JSON desde el JS
            $preguntasIds = json_decode($_POST['preguntas_ids'], true); 
            
            if ($idEncuesta <= 0 || !is_array($preguntasIds)) {
                $arrResponse = array("status" => false, "msg" => 'Datos incompletos o incorrectos.');
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                die();
            }

            // Llama al método transaccional en el modelo
            $request = $this->model->updateAsignacionPreguntas($idEncuesta, $preguntasIds);
            
            if ($request) {
                $arrResponse = array('status' => true, 'msg' => 'Asignación y orden de preguntas guardados correctamente.');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al guardar la asignación. Intente de nuevo o revise los logs de la base de datos.');
            }

            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }


    // Mantenemos el type hint 'int' pero añadimos manejo seguro del parámetro
    public function delPreguntaEncuesta($idAsignacion) {
        // Convertir el valor a entero. Si es 'undefined', intval() devuelve 0.
        $idAsignacion = intval($idAsignacion); 
        
        if ($idAsignacion <= 0) {
            // Manejo del caso 'undefined' o ID no válido
            $arrResponse = array('status' => false, 'msg' => 'Error: ID de asignación no válido. No se puede eliminar.');
        } else {
            // El resto de la lógica de eliminación
            $request_delete = $this->Encuestas_Model->deletePreguntaEncuesta($idAsignacion);

            if ($request_delete) {
                $arrResponse = array('status' => true, 'msg' => 'La pregunta ha sido desasignada correctamente.');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al desasignar la pregunta en la base de datos.');
            }
        }
        
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }
}
?>