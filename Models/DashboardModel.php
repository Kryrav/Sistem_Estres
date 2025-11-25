<?php
class DashboardModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

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
        $sql = "SELECT d.nombre AS departamento, AVG(i.nivel_estres) AS promedio, COUNT(i.id) AS muestras
                FROM indicadores_estres i
                JOIN departamentos d ON i.departamento_id = d.id
                GROUP BY d.id, d.nombre";
        return $this->select_all($sql);
    }

    public function getUltimasBitacoras()
    {
        $sql = "SELECT b.id, p.nombres, p.apellidos, b.nivel_stress_percibido
                FROM bitacora_emocional b
                JOIN trabajador t ON b.trabajador_id = t.id
                JOIN persona p ON t.idpersona = p.idpersona
                ORDER BY b.fecha DESC, b.hora DESC
                LIMIT 5";
        return $this->select_all($sql);
    }
}
?>
