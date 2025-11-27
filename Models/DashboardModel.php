<?php
class DashboardModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    // Métodos existentes (mantener estos)
    public function getTrabajadoresActivos()
    {
        $sql = "SELECT COUNT(*) AS total FROM trabajador WHERE activo = 1";
        $res = $this->select($sql);
        return $res['total'] ?? 0;
    }

    public function getDepartamentos()
    {
        $sql = "SELECT COUNT(*) AS total FROM departamentos";
        $res = $this->select($sql);
        return $res['total'] ?? 0;
    }

    public function getTareasResumen()
    {
        $sql = "SELECT
                    SUM(CASE WHEN estado = 'terminado' THEN 1 ELSE 0 END) AS completadas,
                    SUM(CASE WHEN estado != 'terminado' THEN 1 ELSE 0 END) AS pendientes
                FROM tareas";
        return $this->select($sql);
    }

    public function getEstresDepartamentos()
    {
        $sql = "SELECT d.nombre AS departamento, 
                       AVG(i.nivel_estres) AS promedio, 
                       COUNT(i.id) AS muestras,
                       d.umbral_alerta_stress
                FROM indicadores_estres i
                JOIN departamentos d ON i.departamento_id = d.id
                WHERE i.fecha_calculo >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                GROUP BY d.id, d.nombre, d.umbral_alerta_stress
                ORDER BY promedio DESC";
        return $this->select_all($sql);
    }

    public function getUltimasBitacoras()
    {
        $sql = "SELECT b.id, p.nombres, p.apellidos, b.nivel_stress_percibido,
                       b.sentimiento_predominante, b.fecha, b.hora
                FROM bitacora_emocional b
                JOIN trabajador t ON b.trabajador_id = t.id
                JOIN persona p ON t.idpersona = p.idpersona
                ORDER BY b.fecha DESC, b.hora DESC
                LIMIT 6";
        return $this->select_all($sql);
    }

    // ========== NUEVOS MÉTODOS REQUERIDOS ==========

    public function getMetricasIntervenciones()
    {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) as pendientes,
                    SUM(CASE WHEN estado = 'aplicada' THEN 1 ELSE 0 END) as aplicadas,
                    SUM(CASE WHEN tipo_alerta = 'alerta_burnout' THEN 1 ELSE 0 END) as burnout
                FROM intervenciones_sistema 
                WHERE fecha_generada >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
        $result = $this->select($sql);
        return $result ? $result : ['total' => 0, 'pendientes' => 0, 'aplicadas' => 0, 'burnout' => 0];
    }

    public function getEncuestasActivas()
    {
        $sql = "SELECT COUNT(*) as total 
                FROM encuestas 
                WHERE estado = 'ACTIVA' 
                AND fecha_fin >= CURDATE()";
        $res = $this->select($sql);
        return $res['total'] ?? 0;
    }

    public function getRespuestasRecientes()
    {
        $sql = "SELECT COUNT(*) as total 
                FROM encuesta_respondida 
                WHERE fecha_completada >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        $res = $this->select($sql);
        return $res['total'] ?? 0;
    }

    public function getTendenciasEstres()
    {
        $sql = "SELECT DATE(fecha_calculo) as fecha,
                       AVG(nivel_estres) as promedio_diario,
                       COUNT(*) as muestras
                FROM indicadores_estres 
                WHERE fecha_calculo >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY DATE(fecha_calculo)
                ORDER BY fecha ASC";
        $result = $this->select_all($sql);
        return $result ? $result : [];
    }

    public function getAlertasCriticas()
    {
        $sql = "SELECT i.*, p.nombres, p.apellidos, d.nombre as departamento
                FROM intervenciones_sistema i
                JOIN trabajador t ON i.trabajador_id = t.id
                JOIN persona p ON t.idpersona = p.idpersona
                LEFT JOIN departamentos d ON t.departamento_id = d.id
                WHERE i.estado = 'pendiente'
                ORDER BY i.fecha_generada DESC
                LIMIT 5";
        $result = $this->select_all($sql);
        return $result ? $result : [];
    }

    public function getDistribucionEstres()
    {
        $sql = "SELECT 
                    CASE 
                        WHEN nivel_estres <= 3.5 THEN 'bajo'
                        WHEN nivel_estres <= 6.5 THEN 'medio' 
                        ELSE 'alto'
                    END as categoria,
                    COUNT(*) as total
                FROM indicadores_estres 
                WHERE fecha_calculo >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                GROUP BY categoria";
        $result = $this->select_all($sql);
        return $result ? $result : [];
    }

    public function getEstadisticasIntervenciones()
    {
        $sql = "SELECT 
                    tipo_alerta,
                    COUNT(*) as total
                FROM intervenciones_sistema 
                WHERE fecha_generada >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY tipo_alerta";
        $result = $this->select_all($sql);
        return $result ? $result : [];
    }

    // Método para datos en tiempo real
    public function getDatosTiempoReal()
    {
        $sql = "SELECT 
                    (SELECT COUNT(*) FROM intervenciones_sistema WHERE estado = 'pendiente') as intervenciones_pendientes,
                    (SELECT COUNT(*) FROM encuestas WHERE estado = 'ACTIVA' AND fecha_fin >= CURDATE()) as encuestas_activas,
                    (SELECT COUNT(*) FROM tareas WHERE estado != 'terminado') as tareas_pendientes";
        return $this->select($sql);
    }
}
?>