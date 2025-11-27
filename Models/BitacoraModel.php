<?php

class BitacoraModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    // =========================================================================
    // MÉTODOS PRINCIPALES (CRUD) - SIN CAMBIOS
    // =========================================================================

    public function insertRegistro($trabajador_id, $tarea_id = null, $tipo_registro, $nivel_energia = null, $stress = null, $sentimiento = null, $comentario = null)
    {
        // Validar tipo_registro según ENUM de la BD
        $tipos_permitidos = ['login_checkin', 'cierre_tarea', 'boton_panico', 'logout', 'auto'];
        if (!in_array($tipo_registro, $tipos_permitidos)) {
            return 0;
        }

        $sql = "INSERT INTO bitacora_emocional(
                    trabajador_id,
                    tarea_relacionada_id,
                    fecha,
                    hora,
                    tipo_registro,
                    nivel_energia,
                    nivel_stress_percibido,
                    sentimiento_predominante,
                    comentario_libre
                ) VALUES (?, ?, CURDATE(), CURTIME(), ?, ?, ?, ?, ?)";

        $arrData = [
            $trabajador_id,
            $tarea_id,
            $tipo_registro,
            $nivel_energia,
            $stress,
            $sentimiento,
            $comentario
        ];

        return $this->insert($sql, $arrData);
    }

    public function insertRegistroAutomatico($trabajador_id, $tipo_registro, $tarea_id = null)
    {
        // Validar tipo_registro según ENUM de la BD
        $tipos_permitidos = ['login_checkin', 'cierre_tarea', 'boton_panico', 'logout', 'auto'];
        if (!in_array($tipo_registro, $tipos_permitidos)) {
            return 0;
        }

        $sql = "INSERT INTO bitacora_emocional(
                    trabajador_id,
                    tarea_relacionada_id,
                    fecha,
                    hora,
                    tipo_registro
                ) VALUES (?, ?, CURDATE(), CURTIME(), ?)";

        return $this->insert($sql, [$trabajador_id, $tarea_id, $tipo_registro]);
    }

    public function selectByTrabajador($trabajador_id)
    {
        $sql = "SELECT b.*, 
                       t.titulo AS tarea,
                       CONCAT(p.nombres,' ',p.apellidos) AS nombre_trabajador
                FROM bitacora_emocional b
                LEFT JOIN tareas t ON b.tarea_relacionada_id = t.id
                INNER JOIN trabajador tr ON b.trabajador_id = tr.id
                INNER JOIN persona p ON tr.idpersona = p.idpersona
                WHERE b.trabajador_id = ?
                ORDER BY b.fecha DESC, b.hora DESC";

        return $this->select_all($sql, [$trabajador_id]);
    }

    public function selectByTarea($tarea_id)
    {
        $sql = "SELECT b.*, 
                       CONCAT(p.nombres,' ',p.apellidos) AS trabajador,
                       d.nombre AS departamento
                FROM bitacora_emocional b
                INNER JOIN trabajador tb ON b.trabajador_id = tb.id
                INNER JOIN persona p ON tb.idpersona = p.idpersona
                LEFT JOIN departamentos d ON tb.departamento_id = d.id
                WHERE b.tarea_relacionada_id = ?
                ORDER BY b.fecha DESC, b.hora DESC";

        return $this->select_all($sql, [$tarea_id]);
    }

    public function selectByFecha($trabajador_id, $fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT b.*, 
                       t.titulo AS tarea,
                       CONCAT(p.nombres,' ',p.apellidos) AS nombre_trabajador
                FROM bitacora_emocional b
                LEFT JOIN tareas t ON b.tarea_relacionada_id = t.id
                INNER JOIN trabajador tr ON b.trabajador_id = tr.id
                INNER JOIN persona p ON tr.idpersona = p.idpersona
                WHERE b.trabajador_id = ?
                  AND b.fecha BETWEEN ? AND ?
                ORDER BY b.fecha DESC, b.hora DESC";

        return $this->select_all($sql, [$trabajador_id, $fecha_inicio, $fecha_fin]);
    }

    public function selectRegistro($id)
    {
        $sql = "SELECT b.*, 
                       t.titulo AS tarea,
                       CONCAT(p.nombres,' ',p.apellidos) AS trabajador,
                       d.nombre AS departamento,
                       tb.cargo
                FROM bitacora_emocional b
                LEFT JOIN tareas t ON b.tarea_relacionada_id = t.id
                INNER JOIN trabajador tb ON b.trabajador_id = tb.id
                INNER JOIN persona p ON tb.idpersona = p.idpersona
                LEFT JOIN departamentos d ON tb.departamento_id = d.id
                WHERE b.id = ?";

        return $this->select($sql, [$id]);
    }

    public function deleteRegistro($id)
    {
        $sql = "DELETE FROM bitacora_emocional WHERE id=?";
        return $this->delete($sql, [$id]);
    }

    // =========================================================================
    // MÉTODOS DE ANÁLISIS Y MÉTRICAS - CORREGIDOS
    // =========================================================================

    /**
     * Métricas para análisis de estrés por trabajador - CORREGIDO
     */
    public function getMetricasTrabajador($trabajador_id, $dias = 30)
    {
        $sql = "SELECT 
                    COUNT(*) as total_registros,
                    AVG(nivel_stress_percibido) as stress_promedio,
                    AVG(nivel_energia) as energia_promedio,
                    MAX(nivel_stress_percibido) as stress_maximo,
                    MIN(nivel_stress_percibido) as stress_minimo,
                    sentimiento_predominante as sentimiento_frecuente,
                    COUNT(DISTINCT DATE(fecha)) as dias_con_registro,
                    SUM(CASE WHEN tipo_registro = 'boton_panico' THEN 1 ELSE 0 END) as total_alertas_panico,
                    SUM(CASE WHEN nivel_stress_percibido >= 7 THEN 1 ELSE 0 END) as alertas_stress_alto
                FROM bitacora_emocional 
                WHERE trabajador_id = ? 
                AND fecha >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                GROUP BY trabajador_id";

        return $this->select($sql, [$trabajador_id, $dias]);
    }

    /**
     * Detección de patrones de estrés por día/hora - CORREGIDO (sin LIMIT)
     */
    public function getPatronesEstres($trabajador_id)
    {
        $sql = "SELECT 
                    DAYNAME(fecha) as dia_semana,
                    HOUR(hora) as hora_dia,
                    AVG(nivel_stress_percibido) as stress_promedio,
                    AVG(nivel_energia) as energia_promedio,
                    COUNT(*) as muestras
                FROM bitacora_emocional 
                WHERE trabajador_id = ? 
                AND nivel_stress_percibido IS NOT NULL
                GROUP BY DAYNAME(fecha), HOUR(hora)
                HAVING muestras >= 3
                ORDER BY stress_promedio DESC";

        $result = $this->select_all($sql, [$trabajador_id]);
        // Limitar a 10 resultados en PHP
        return array_slice($result, 0, 10);
    }

    /**
     * Registros recientes para dashboard - CORREGIDO (sin LIMIT)
     */
    public function selectRegistrosRecientes($trabajador_id)
    {
        $sql = "SELECT b.*, 
                       t.titulo AS tarea,
                       CASE 
                         WHEN b.nivel_stress_percibido <= 3 THEN 'bajo'
                         WHEN b.nivel_stress_percibido <= 7 THEN 'medio'
                         ELSE 'alto'
                       END as categoria_stress,
                       CASE 
                         WHEN b.nivel_energia <= 3 THEN 'bajo'
                         WHEN b.nivel_energia <= 7 THEN 'medio'
                         ELSE 'alto'
                       END as categoria_energia
                FROM bitacora_emocional b
                LEFT JOIN tareas t ON b.tarea_relacionada_id = t.id
                WHERE b.trabajador_id = ?
                ORDER BY b.fecha DESC, b.hora DESC";

        $result = $this->select_all($sql, [$trabajador_id]);
        // Limitar a 5 resultados en PHP
        return array_slice($result, 0, 5);
    }

    /**
     * Obtener estadísticas por departamento - CORREGIDO
     */
    public function getEstadisticasDepartamento($departamento_id, $dias = 30)
    {
        $sql = "SELECT 
                    d.nombre as departamento,
                    COUNT(b.id) as total_registros,
                    AVG(b.nivel_stress_percibido) as stress_promedio,
                    AVG(b.nivel_energia) as energia_promedio,
                    COUNT(DISTINCT b.trabajador_id) as trabajadores_activos,
                    SUM(CASE WHEN b.tipo_registro = 'boton_panico' THEN 1 ELSE 0 END) as alertas_panico,
                    SUM(CASE WHEN b.nivel_stress_percibido >= 7 THEN 1 ELSE 0 END) as alertas_stress_alto
                FROM bitacora_emocional b
                INNER JOIN trabajador t ON b.trabajador_id = t.id
                INNER JOIN departamentos d ON t.departamento_id = d.id
                WHERE t.departamento_id = ?
                AND b.fecha >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                GROUP BY d.id, d.nombre";

        return $this->select($sql, [$departamento_id, $dias]);
    }

    /**
     * Obtener tendencia semanal de estrés - CORREGIDO (sin LIMIT)
     */
    public function getTendenciaSemanal($trabajador_id)
    {
        $sql = "SELECT 
                    DATE_FORMAT(fecha, '%Y-%u') as semana,
                    AVG(nivel_stress_percibido) as stress_promedio,
                    AVG(nivel_energia) as energia_promedio,
                    COUNT(*) as total_registros
                FROM bitacora_emocional 
                WHERE trabajador_id = ?
                AND fecha >= DATE_SUB(CURDATE(), INTERVAL 8 WEEK)
                GROUP BY DATE_FORMAT(fecha, '%Y-%u')
                ORDER BY semana DESC";

        $result = $this->select_all($sql, [$trabajador_id]);
        // Limitar a 8 resultados en PHP
        return array_slice($result, 0, 8);
    }

    /**
     * Buscar registros con filtros avanzados - CORREGIDO (sin LIMIT)
     */
    public function searchRegistros($filtros = [])
    {
        $sql = "SELECT b.*, 
                       CONCAT(p.nombres,' ',p.apellidos) AS trabajador,
                       t.titulo AS tarea,
                       d.nombre AS departamento
                FROM bitacora_emocional b
                INNER JOIN trabajador tr ON b.trabajador_id = tr.id
                INNER JOIN persona p ON tr.idpersona = p.idpersona
                LEFT JOIN tareas t ON b.tarea_relacionada_id = t.id
                LEFT JOIN departamentos d ON tr.departamento_id = d.id
                WHERE 1=1";

        $arrData = [];

        // Filtro por trabajador
        if (!empty($filtros['trabajador_id'])) {
            $sql .= " AND b.trabajador_id = ?";
            $arrData[] = $filtros['trabajador_id'];
        }

        // Filtro por departamento
        if (!empty($filtros['departamento_id'])) {
            $sql .= " AND tr.departamento_id = ?";
            $arrData[] = $filtros['departamento_id'];
        }

        // Filtro por tipo de registro
        if (!empty($filtros['tipo_registro'])) {
            $sql .= " AND b.tipo_registro = ?";
            $arrData[] = $filtros['tipo_registro'];
        }

        // Filtro por rango de fechas
        if (!empty($filtros['fecha_inicio']) && !empty($filtros['fecha_fin'])) {
            $sql .= " AND b.fecha BETWEEN ? AND ?";
            $arrData[] = $filtros['fecha_inicio'];
            $arrData[] = $filtros['fecha_fin'];
        }

        // Filtro por nivel de estrés mínimo
        if (!empty($filtros['stress_minimo'])) {
            $sql .= " AND b.nivel_stress_percibido >= ?";
            $arrData[] = $filtros['stress_minimo'];
        }

        $sql .= " ORDER BY b.fecha DESC, b.hora DESC";

        $result = $this->select_all($sql, $arrData);
        
        // Aplicar límite en PHP si se especifica
        if (!empty($filtros['limite'])) {
            $result = array_slice($result, 0, (int)$filtros['limite']);
        }
        
        return $result;
    }

    /**
     * Obtener resumen para dashboard gerencial - CORREGIDO
     */
    public function getResumenDashboard($dias = 7)
    {
        $sql = "SELECT 
                    COUNT(*) as total_registros,
                    COUNT(DISTINCT trabajador_id) as trabajadores_activos,
                    AVG(nivel_stress_percibido) as stress_promedio_global,
                    SUM(CASE WHEN tipo_registro = 'boton_panico' THEN 1 ELSE 0 END) as total_botones_panico,
                    SUM(CASE WHEN nivel_stress_percibido >= 8 THEN 1 ELSE 0 END) as alertas_criticas,
                    (SELECT sentimiento_predominante 
                     FROM bitacora_emocional 
                     WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                     GROUP BY sentimiento_predominante 
                     ORDER BY COUNT(*) DESC 
                     LIMIT 1) as sentimiento_predominante
                FROM bitacora_emocional 
                WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL ? DAY)";

        return $this->select($sql, [$dias, $dias]);
    }

    /**
     * Verificar si ya existe registro hoy del mismo tipo para evitar duplicados
     */
    public function existeRegistroHoy($trabajador_id, $tipo_registro)
    {
        $sql = "SELECT COUNT(*) as total 
                FROM bitacora_emocional 
                WHERE trabajador_id = ? 
                AND tipo_registro = ? 
                AND fecha = CURDATE()";
        
        $result = $this->select($sql, [$trabajador_id, $tipo_registro]);
        return ($result && $result['total'] > 0);
    }
}
?>