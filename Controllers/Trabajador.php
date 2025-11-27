<?php 
class Trabajador extends Controllers
{
    public function __construct()
    {
        parent::__construct();
			session_start();
			if(empty($_SESSION['login']))
			{
				header('Location: '.base_url().'/login');
			}
			getPermisos(11); // Asumiendo Módulo 5: Trabajadores
    }

    public function Trabajador()
    {
        $data['page_title'] = "Gestión de Trabajadores";
        $data['page_name'] = "Trabajadores";
        $data['page_tag'] = "trabajadores";
        $data['page_functions_js'] = "functions_trabajador.js";
        $this->views->getView($this, "Trabajador", $data);
    }

    /**
     * Obtiene todos los Trabajadores para DataTables (AJAX)
     */
    public function getTrabajadores()
    {
        $arrData = $this->model->selectTrabajadores();

        // Formatear los datos para DataTables y agregar botones de acción
        for ($i=0; $i < count($arrData); $i++) {
            // CORREGIDO: Usar el campo 'id' de la tabla trabajador
            $id = $arrData[$i]['id']; 
            
            // CORREGIDO: Usar el campo 'activo'
            if($arrData[$i]['activo'] == 1){
                $arrData[$i]['activo'] = '<span class="badge badge-success">Activo</span>';
            } else {
                $arrData[$i]['activo'] = '<span class="badge badge-danger">Inactivo</span>';
            }

            // Formato de Fecha
            $arrData[$i]['fecha_ingreso'] = date('d/m/Y', strtotime($arrData[$i]['fecha_ingreso']));

            // Botones de Acción
            $arrData[$i]['options'] = '
                <div class="text-center">
                    <button class="btn btn-sm btn-info btnViewTrabajador" onClick="fntViewTrabajador('.$id.')" title="Ver Trabajador">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-primary btnEditTrabajador" onClick="fntEditTrabajador(this, '.$id.')" title="Editar Trabajador">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btnDelTrabajador" onClick="fntDelTrabajador('.$id.')" title="Eliminar Trabajador">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>';
        }

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getSelects()
    {
        $arrData['personas_disponibles'] = $this->model->selectPersonasDisponibles();
        $arrData['departamentos'] = $this->model->selectDepartamentos();
        $arrData['supervisores'] = $this->model->selectSupervisores();

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        die();
    }

    /**
     * Inserta o Actualiza un registro en la tabla trabajador
     */
    public function setTrabajador()
    {
        if ($_POST) {            
            // CORRECCIÓN: Manejo de clave indefinida para listSupervisor (Línea 86 corregida)
            $idSupervisor = !empty($_POST['listSupervisor']) ? intval($_POST['listSupervisor']) : 0; 
            $id = empty($_POST['id']) ? 0 : intval($_POST['id']); //Id Trabajador
            $idPersona = !empty($_POST['listIdPersona']) ? intval($_POST['listIdPersona']) : 0; // Se establece a 0 si no se envía (caso de actualización)

            $idDepartamento = intval($_POST['listDepartamento']);
            $cargo = strClean($_POST['txtCargo']);
            $horasDiarias = intval($_POST['listHorasDiarias']); // NUEVO CAMPO
            $activo = intval($_POST['listActivo']); // CORREGIDO: Usar 'activo'

            // Validaciones básicas
            if (empty($idDepartamento) || empty($cargo) || empty($activo) || empty($horasDiarias)) {
                $arrResponse = array("status" => false, "msg" => 'Faltan campos obligatorios (*).');
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                die();
            }

            // Lógica de Inserción / Actualización
            if ($id == 0) {
                // *** CREAR NUEVO TRABAJADOR (Asignación) ***
                if (empty($idPersona)) {
                    $arrResponse = array("status" => false, "msg" => 'Debe seleccionar una persona para asignar como trabajador.');
                    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                    die();
                }
                // CORREGIDO: Parámetros del insert
                $request_trabajador = $this->model->insertTrabajador($idPersona, $idDepartamento, $idSupervisor, $cargo, $horasDiarias, $activo);
                $option = 1; 
            } else {
                // *** ACTUALIZAR TRABAJADOR EXISTENTE ***
                // CORREGIDO: Parámetros del update
                $request_trabajador = $this->model->updateTrabajador($id, $idDepartamento, $idSupervisor, $cargo, $horasDiarias, $activo);
                $option = 2;
            }

            // Manejo de la respuesta
            if ($request_trabajador > 0) {
                $msg = ($option == 1) ? 'Trabajador asignado exitosamente.' : 'Datos del Trabajador actualizados correctamente.';
                $arrResponse = array('status' => true, 'msg' => $msg);
            } else if ($request_trabajador === 'exist') {
                $arrResponse = array('status' => false, 'msg' => '¡Atención! La persona ya está asignada como Trabajador.');
            } else {
                $arrResponse = array("status" => false, "msg" => "Error al procesar la solicitud.");
            }

            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
    
    /**
     * Obtiene un trabajador por ID para edición o vista (AJAX)
     */
    public function getTrabajador(int $id)
    {
        $id = intval(strClean($id)); 

        if ($id > 0) {
            $arrData = $this->model->selectTrabajador($id);
            
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
    
    /**
     * Elimina lógicamente un trabajador (AJAX)
     */
    public function delTrabajador()
    {
        if ($_POST) {
            $id = intval($_POST['id']); // CORREGIDO: Usar 'id'
            $request_delete = $this->model->deleteTrabajador($id);
            
            if ($request_delete) {
                $arrResponse = array('status' => true, 'msg' => 'Trabajador eliminado lógicamente.');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al intentar eliminar el trabajador.');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
}