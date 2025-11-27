<?php 
    class AnaliticasModel extends Mysql
    {
        public function __construct()
        {
            parent::__construct();
        }

        // Obtener reportes existentes
        public function selectReportes()
        {
            $sql = "SELECT r.*, d.nombre as departamento_nombre, 
                           p.nombres as generado_por_nombre,
                           p.apellidos as generado_por_apellido
                    FROM reportes r
                    LEFT JOIN departamentos d ON r.departamento_id = d.id
                    LEFT JOIN persona p ON r.generado_por_persona_id = p.idpersona
                    ORDER BY r.fecha_reporte DESC";
            $request = $this->select_all($sql);
            return $request;
        }

        // Obtener un reporte específico
        public function selectReporte(int $idreporte)
        {
            $this->intIdreporte = $idreporte;
            $sql = "SELECT r.*, d.nombre as departamento_nombre,
                           p.nombres as generado_por_nombre,
                           p.apellidos as generado_por_apellido
                    FROM reportes r
                    LEFT JOIN departamentos d ON r.departamento_id = d.id
                    LEFT JOIN persona p ON r.generado_por_persona_id = p.idpersona
                    WHERE r.id = $this->intIdreporte";
            $request = $this->select($sql);
            return $request;
        }

        // Insertar nuevo reporte
        public function insertReporte(int $departamento_id, $nivel_estres, string $observaciones, int $usuario_id)
        {
            $query_insert = "INSERT INTO reportes(departamento_id, nivel_general_estres, observaciones, generado_por_persona_id) 
                            VALUES(?,?,?,?)";
            $arrData = array($departamento_id, $nivel_estres, $observaciones, $usuario_id);
            $request_insert = $this->insert($query_insert, $arrData);
            return $request_insert;
        }

        // Actualizar reporte
        public function updateReporte(int $idreporte, int $departamento_id, $nivel_estres, string $observaciones)
        {
            $this->intIdreporte = $idreporte;
            $sql = "UPDATE reportes SET departamento_id = ?, nivel_general_estres = ?, observaciones = ? 
                    WHERE id = $this->intIdreporte";
            $arrData = array($departamento_id, $nivel_estres, $observaciones);
            $request = $this->update($sql, $arrData);
            return $request;
        }

        // Eliminar reporte (soft delete)
        public function deleteReporte(int $idreporte)
        {
            $this->intIdreporte = $idreporte;
            $sql = "DELETE FROM reportes WHERE id = $this->intIdreporte";
            $request = $this->delete($sql);
            return $request;
        }

        // Métodos para analytics en tiempo real
        public function getEstresPorDepartamento()
        {
            $sql = "SELECT d.id, d.nombre as departamento, 
                        COALESCE(AVG(i.nivel_estres), 0) as promedio_estres,
                        COUNT(i.id) as total_muestras,
                        d.umbral_alerta_stress
                    FROM departamentos d
                    LEFT JOIN indicadores_estres i ON d.id = i.departamento_id 
                        AND i.fecha_calculo >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                    WHERE d.id IN (1, 2, 3)  -- Fuerza los 3 departamentos
                    GROUP BY d.id, d.nombre, d.umbral_alerta_stress
                    ORDER BY d.id";
            $request = $this->select_all($sql);
            return $request;
        }

        public function getTendenciasEstres()
        {
            $sql = "SELECT DATE(fecha_calculo) as fecha,
                           AVG(nivel_estres) as promedio_diario,
                           COUNT(*) as muestras
                    FROM indicadores_estres 
                    WHERE fecha_calculo >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                    GROUP BY DATE(fecha_calculo)
                    ORDER BY fecha DESC";
            $request = $this->select_all($sql);
            return $request;
        }

        public function getTopIntervenciones()
        {
            $sql = "SELECT tipo_alerta, estado, COUNT(*) as total,
                           DATE(fecha_generada) as fecha
                    FROM intervenciones_sistema 
                    WHERE fecha_generada >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                    GROUP BY tipo_alerta, estado, DATE(fecha_generada)
                    ORDER BY total DESC";
            $request = $this->select_all($sql);
            return $request;
        }

        public function getMetricasEncuestas()
        {
            $sql = "SELECT e.titulo, 
                           COUNT(er.id) as total_respuestas,
                           AVG(CASE WHEN r.valor_respuesta > 0 THEN r.valor_respuesta ELSE NULL END) as promedio_respuestas
                    FROM encuestas e
                    LEFT JOIN encuesta_respondida er ON e.id = er.encuesta_id
                    LEFT JOIN respuestas r ON er.trabajador_id = r.persona_id
                    WHERE e.estado = 'ACTIVA'
                    GROUP BY e.id, e.titulo";
            $request = $this->select_all($sql);
            return $request;
        }
    }
?>