<?php 
class TrabajadorModel extends Mysql
{
    private $intId; 
    private $intIdPersona;
    private $intDepartamentoId;
    private $intSupervisorId;
    private $strCargo;
    private $intHorasDiarias;
    private $intActivo; // Coincide con el campo 'activo' (tinyint(1))

    public function __construct()
    {
        parent::__construct();
    }

    // =========================================================================
    // CONSULTAS PRINCIPALES (CRUD)
    // =========================================================================

    /**
     * Obtiene todos los trabajadores con sus detalles de persona y departamento.
     */
    public function selectTrabajadores()
    {
        $sql = "SELECT 
                    t.id, 
                    p.idpersona,
                    CONCAT(p.nombres, ' ', p.apellidos) AS nombre_completo,
                    d.nombre AS departamento,
                    t.cargo,
                    (SELECT CONCAT(nombres, ' ', apellidos) FROM persona WHERE idpersona = t.supervisor_id) AS supervisor_nombre,
                    t.fecha_ingreso,
                    t.activo 
                FROM trabajador t
                JOIN persona p ON t.idpersona = p.idpersona
                JOIN departamentos d ON t.departamento_id = d.id
                WHERE t.activo != 0"; // Filtrar por campo 'activo'
        
        $request = $this->select_all($sql);
        return $request;
    }
    
    /**
     * Obtiene la información detallada de un trabajador por su ID.
     */
    public function selectTrabajador(int $id)
    {
        $this->intId = $id;
        $sql = "SELECT 
                    t.id,
                    t.idpersona,
                    t.departamento_id,
                    d.nombre AS nombre_departamento,  
                    t.supervisor_id,
                    CONCAT(s.nombres, ' ', s.apellidos) AS nombre_supervisor, 
                    p.rolid,
                    r.nombrerol,                       
                    t.cargo,
                    t.horas_trabajo_diarias,
                    t.fecha_ingreso,
                    t.activo,
                    p.nombres,
                    p.apellidos,
                    p.email_user,
                    p.telefono,                        
                    p.identificacion                   
                FROM trabajador t
                JOIN persona p ON t.idpersona = p.idpersona
                JOIN rol r ON p.rolid = r.idrol        
                JOIN departamentos d ON t.departamento_id = d.id 
                LEFT JOIN persona s ON t.supervisor_id = s.idpersona 
                WHERE t.id = '{$this->intId}' AND t.activo != 0";
        
        $request = $this->select($sql);
        
        // Si la solicitud devuelve datos, formatea la fecha de ingreso
        if(!empty($request)) {
            $request['fecha_ingreso'] = date('d/m/Y', strtotime($request['fecha_ingreso']));
        }
        
        return $request;
    }
    
    /**
     * Inserta un nuevo registro en la tabla trabajador.
     */
    public function insertTrabajador(int $idPersona, int $idDepartamento, int $idSupervisor, string $cargo, int $horasDiarias, int $activo)
    {
        $this->intIdPersona = $idPersona;
        $this->intDepartamentoId = $idDepartamento;
        $this->intSupervisorId = $idSupervisor;
        $this->strCargo = $cargo;
        $this->intHorasDiarias = $horasDiarias; // NUEVO
        $this->intActivo = $activo; 

        // Validar si la persona ya está registrada como trabajador
        $sql_exist = "SELECT idpersona FROM trabajador WHERE idpersona = '{$this->intIdPersona}'";
        $check = $this->select($sql_exist);

        if (empty($check)) {
            $fecha_ingreso = date('Y-m-d'); // Usar solo fecha para coincidir con el campo
            $query_insert = "INSERT INTO trabajador(idpersona, departamento_id, supervisor_id, cargo, horas_trabajo_diarias, fecha_ingreso, activo) 
                             VALUES(?,?,?,?,?,?,?)";
            $arrData = array(
                $this->intIdPersona, 
                $this->intDepartamentoId, 
                $this->intSupervisorId, 
                $this->strCargo, 
                $this->intHorasDiarias, 
                $fecha_ingreso, 
                $this->intActivo 
            );
            $request = $this->insert($query_insert, $arrData);
        } else {
            $request = 'exist';
        }
        return $request;
    }

    /**
     * Actualiza un registro existente en la tabla trabajador.
     */
    public function updateTrabajador(int $id, int $idDepartamento, int $idSupervisor, string $cargo, int $horasDiarias, int $activo)
    {
        $this->intId = $id;
        $this->intDepartamentoId = $idDepartamento;
        $this->intSupervisorId = $idSupervisor;
        $this->strCargo = $cargo;
        $this->intHorasDiarias = $horasDiarias; 
        $this->intActivo = $activo; 

        $sql = "UPDATE trabajador SET 
                    departamento_id = ?, 
                    supervisor_id = ?, 
                    cargo = ?, 
                    horas_trabajo_diarias = ?,
                    activo = ? 
                WHERE id = '{$this->intId}'"; 
        
        $arrData = array(
            $this->intDepartamentoId, 
            $this->intSupervisorId, 
            $this->strCargo, 
            $this->intHorasDiarias, 
            $this->intActivo 
        );

        $request = $this->update($sql, $arrData);
        return $request;
    }

    /**
     * Elimina lógicamente un trabajador (cambia 'activo' a 0).
     */
    public function deleteTrabajador(int $id)
    {
        $this->intId = $id;
        $sql = "UPDATE trabajador SET activo = ? WHERE id = '{$this->intId}'"; 
        $arrData = array(0);
        $request = $this->update($sql, $arrData);
        return $request;
    }
    
    // =========================================================================
    // CONSULTAS HELPERS (sin cambios, solo usan nombres de tablas)
    // =========================================================================
    public function selectPersonasDisponibles()
    {
        $sql = "SELECT 
                    p.idpersona, 
                    CONCAT(p.nombres, ' ', p.apellidos) AS nombre_completo, 
                    r.nombrerol
                FROM persona p 
                JOIN rol r ON p.rolid = r.idrol
                LEFT JOIN trabajador t ON p.idpersona = t.idpersona
                WHERE t.idpersona IS NULL AND p.status = 1";
        
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectDepartamentos()
    {
        $sql = "SELECT id, nombre FROM departamentos";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectSupervisores()
    {
        // Solo incluye personas que están registradas como trabajadores Y tienen el rol 1 o 2.
        $sql = "SELECT 
                    t.idpersona, 
                    CONCAT(p.nombres, ' ', p.apellidos) AS nombre_completo 
                FROM trabajador t 
                JOIN persona p ON t.idpersona = p.idpersona
                -- Filtramos por los roles permitidos (1: Admin, 2: Supervisor)
                WHERE t.activo = 1 AND p.rolid IN (1, 2)"; 
        
        $request = $this->select_all($sql);
        return $request;
    }

    // =========================================================================
    // MÉTODOS PARA INTEGRACIÓN CON BITÁCORA EMOCIONAL
    // =========================================================================

    /**
     * Obtiene trabajador por ID de persona - PARA INTEGRACIÓN CON LOGIN
     */
    public function getTrabajadorByPersona($idPersona)
    {
        $sql = "SELECT t.id, t.departamento_id, t.cargo, t.activo,
                       p.nombres, p.apellidos, p.email_user
                FROM trabajador t
                INNER JOIN persona p ON t.idpersona = p.idpersona
                WHERE t.idpersona = ? AND t.activo = 1 
                LIMIT 1";
        return $this->select($sql, [$idPersona]);
    }

    /**
     * Obtiene trabajador por ID de trabajador - PARA BITÁCORA
     */
    public function getTrabajadorById($idTrabajador)
    {
        $sql = "SELECT t.*, p.nombres, p.apellidos, p.email_user,
                       d.nombre as departamento, d.umbral_alerta_stress
                FROM trabajador t
                INNER JOIN persona p ON t.idpersona = p.idpersona
                LEFT JOIN departamentos d ON t.departamento_id = d.id
                WHERE t.id = ? AND t.activo = 1";
        return $this->select($sql, [$idTrabajador]);
    }

    /**
     * Obtiene trabajadores por departamento - PARA REPORTES
     */
    public function getTrabajadoresByDepartamento($departamentoId)
    {
        $sql = "SELECT t.id, CONCAT(p.nombres, ' ', p.apellidos) as nombre_completo,
                       t.cargo, p.email_user
                FROM trabajador t
                INNER JOIN persona p ON t.idpersona = p.idpersona
                WHERE t.departamento_id = ? AND t.activo = 1";
        return $this->select_all($sql, [$departamentoId]);
    }

    /**
     * Verifica si una persona es supervisor - PARA PERMISOS
     */
    public function esSupervisor($idPersona)
    {
        $sql = "SELECT COUNT(*) as es_supervisor 
                FROM trabajador t
                INNER JOIN persona p ON t.idpersona = p.idpersona
                WHERE t.idpersona = ? AND p.rolid IN (1,2) AND t.activo = 1";
        $result = $this->select($sql, [$idPersona]);
        return ($result && $result['es_supervisor'] > 0);
    }
}