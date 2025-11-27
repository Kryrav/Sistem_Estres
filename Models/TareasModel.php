<?php
class TareasModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }

    // Obtener todas las tareas
    public function selectTareas()
    {
        $sql = "SELECT t.id, t.titulo, t.descripcion_detallada AS descripcion,
                       tr.id AS trabajador_id, CONCAT(p.nombres,' ',p.apellidos) AS trabajador,
                       tp.id AS tipo_id, tp.nombre AS tipo,
                       t.minutos_estimados, t.estado
                FROM tareas t
                LEFT JOIN trabajador tr ON t.trabajador_id = tr.id
                LEFT JOIN persona p ON tr.idpersona = p.idpersona
                LEFT JOIN tipos_tarea tp ON t.tipo_tarea_id = tp.id
                ORDER BY t.fecha_creacion DESC";
        return $this->select_all($sql);
    }

    // Insertar tarea
    public function insertTarea($titulo, $descripcion, $trabajador_id, $tipo_tarea_id, $minutos_estimados, $estado)
    {
        $sql = "INSERT INTO tareas(titulo, descripcion_detallada, trabajador_id, tipo_tarea_id, minutos_estimados, estado) VALUES(?,?,?,?,?,?)";
        $arrData = [$titulo, $descripcion, $trabajador_id, $tipo_tarea_id, $minutos_estimados, $estado];
        return $this->insert($sql, $arrData);
    }

    // Actualizar tarea
    public function updateTarea($id, $titulo, $descripcion, $trabajador_id, $tipo_tarea_id, $minutos_estimados, $estado)
    {
        $sql = "UPDATE tareas SET titulo=?, descripcion_detallada=?, trabajador_id=?, tipo_tarea_id=?, minutos_estimados=?, estado=? WHERE id=?";
        $arrData = [$titulo, $descripcion, $trabajador_id, $tipo_tarea_id, $minutos_estimados, $estado, $id];
        return $this->update($sql, $arrData);
    }

    // Eliminar tarea
    public function deleteTarea($id)
    {
        $sql = "DELETE FROM tareas WHERE id=?";
        return $this->delete($sql, [$id]);
    }

    // Obtener tarea específica
    public function selectTarea(int $id)
    {
        $sql = "SELECT t.id, t.titulo, t.descripcion_detallada AS descripcion,
                       t.trabajador_id, t.tipo_tarea_id, t.minutos_estimados, t.estado
                FROM tareas t
                WHERE t.id=?";
        return $this->select($sql, [$id]);
    }

    // Obtener lista de trabajadores activos
    public function selectTrabajadores()
    {
        $sql = "SELECT tr.id, CONCAT(p.nombres, ' ', p.apellidos) AS nombre
                FROM trabajador tr
                INNER JOIN persona p ON tr.idpersona = p.idpersona
                WHERE tr.activo = 1
                ORDER BY p.nombres ASC";
        $request = $this->select_all($sql);
        return $request;
    }

    // Obtener lista de tipos de tarea
    public function selectTiposTarea()
    {
        $sql = "SELECT id, nombre FROM tipos_tarea ORDER BY nombre ASC";
        return $this->select_all($sql);
    }

    /**
     * Obtener tareas por trabajador específico
     */

    /**
     * Verificar si una tarea pertenece a un trabajador
     */
    public function checkTareaTrabajador($tarea_id, $trabajador_id)
    {
        $sql = "SELECT COUNT(*) as pertenece FROM tareas 
                WHERE id = ? AND trabajador_id = ?";
        $result = $this->select($sql, [$tarea_id, $trabajador_id]);
        return ($result && $result['pertenece'] > 0);
    }

    /**
     * Obtener tareas por trabajador específico
     */
    public function selectTareasByTrabajador($trabajador_id)
    {
        $sql = "SELECT t.id, t.titulo, t.descripcion_detallada AS descripcion,
                       tp.nombre AS tipo, tp.factor_carga_cognitiva,
                       t.minutos_estimados, t.minutos_reales_invertidos, 
                       t.estado, t.importancia, t.urgencia,
                       t.fecha_vencimiento, t.fecha_creacion,
                       t.motivo_bloqueo
                FROM tareas t
                LEFT JOIN tipos_tarea tp ON t.tipo_tarea_id = tp.id
                WHERE t.trabajador_id = ?
                ORDER BY 
                    CASE t.estado
                        WHEN 'en_progreso' THEN 1
                        WHEN 'listo' THEN 2
                        WHEN 'revision' THEN 3
                        WHEN 'bloqueado' THEN 4
                        WHEN 'terminado' THEN 5
                        ELSE 6
                    END,
                    t.fecha_creacion DESC";
        return $this->select_all($sql, [$trabajador_id]);
    }

    /**
     * Actualizar estado de tarea (para trabajadores)
     */
    public function updateEstadoTarea($id, $estado, $minutos_reales = null, $motivo_bloqueo = null)
    {
        $sql = "UPDATE tareas SET estado = ?, motivo_bloqueo = ?";
        $arrData = [$estado, $motivo_bloqueo];
        
        if ($minutos_reales !== null) {
            $sql .= ", minutos_reales_invertidos = ?";
            $arrData[] = $minutos_reales;
        }
        
        $sql .= " WHERE id = ?";
        $arrData[] = $id;
        
        return $this->update($sql, $arrData);
    }
}
?>
