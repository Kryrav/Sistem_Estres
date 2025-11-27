<?php 
    class IndicadoresModel extends Mysql
    {
        private $intIdIndicador;
        private $intTrabajadorId;
        private $intDepartamentoId;
        private $decNivelEstres;
        private $strCategoria;
        private $strMetodoCalculo;

        public function __construct()
        {
            parent::__construct();
        }

        // Obtener todos los indicadores
        public function selectIndicadores()
        {
            $sql = "SELECT i.*, 
                           p.nombres as trabajador_nombre,
                           p.apellidos as trabajador_apellido,
                           d.nombre as departamento_nombre,
                           t.cargo as trabajador_cargo
                    FROM indicadores_estres i
                    LEFT JOIN trabajador t ON i.trabajador_id = t.id
                    LEFT JOIN persona p ON t.idpersona = p.idpersona
                    LEFT JOIN departamentos d ON i.departamento_id = d.id
                    ORDER BY i.fecha_calculo DESC";
            $request = $this->select_all($sql);
            return $request;
        }

        // Obtener un indicador específico
        public function selectIndicador(int $idindicador)
        {
            $this->intIdIndicador = $idindicador;
            $sql = "SELECT i.*, 
                           p.nombres as trabajador_nombre,
                           p.apellidos as trabajador_apellido,
                           d.nombre as departamento_nombre,
                           t.cargo as trabajador_cargo
                    FROM indicadores_estres i
                    LEFT JOIN trabajador t ON i.trabajador_id = t.id
                    LEFT JOIN persona p ON t.idpersona = p.idpersona
                    LEFT JOIN departamentos d ON i.departamento_id = d.id
                    WHERE i.id = $this->intIdIndicador";
            $request = $this->select($sql);
            return $request;
        }

        // Insertar nuevo indicador
        public function insertIndicador(int $trabajador_id, int $departamento_id, $nivel_estres, string $categoria, string $metodo_calculo)
        {
            $this->intTrabajadorId = $trabajador_id;
            $this->intDepartamentoId = $departamento_id;
            $this->decNivelEstres = $nivel_estres;
            $this->strCategoria = $categoria;
            $this->strMetodoCalculo = $metodo_calculo;

            $query_insert = "INSERT INTO indicadores_estres(trabajador_id, departamento_id, nivel_estres, categoria, metodo_calculo) 
                            VALUES(?,?,?,?,?)";
            $arrData = array($this->intTrabajadorId, $this->intDepartamentoId, $this->decNivelEstres, $this->strCategoria, $this->strMetodoCalculo);
            $request_insert = $this->insert($query_insert, $arrData);
            return $request_insert;
        }

        // Actualizar indicador
        public function updateIndicador(int $idindicador, $nivel_estres, string $categoria, string $metodo_calculo)
        {
            $this->intIdIndicador = $idindicador;
            $this->decNivelEstres = $nivel_estres;
            $this->strCategoria = $categoria;
            $this->strMetodoCalculo = $metodo_calculo;

            $sql = "UPDATE indicadores_estres SET nivel_estres = ?, categoria = ?, metodo_calculo = ? 
                    WHERE id = $this->intIdIndicador";
            $arrData = array($this->decNivelEstres, $this->strCategoria, $this->strMetodoCalculo);
            $request = $this->update($sql, $arrData);
            return $request;
        }

        // Eliminar indicador
        public function deleteIndicador(int $idindicador)
        {
            $this->intIdIndicador = $idindicador;
            $sql = "DELETE FROM indicadores_estres WHERE id = $this->intIdIndicador";
            $request = $this->delete($sql);
            return $request;
        }

        // Obtener indicadores por trabajador
        public function selectIndicadoresTrabajador(int $trabajador_id)
        {
            $this->intTrabajadorId = $trabajador_id;
            $sql = "SELECT i.*, d.nombre as departamento_nombre
                    FROM indicadores_estres i
                    LEFT JOIN departamentos d ON i.departamento_id = d.id
                    WHERE i.trabajador_id = $this->intTrabajadorId
                    ORDER BY i.fecha_calculo DESC";
            $request = $this->select_all($sql);
            return $request;
        }

        // Obtener indicadores por departamento
        public function selectIndicadoresDepartamento(int $departamento_id)
        {
            $this->intDepartamentoId = $departamento_id;
            $sql = "SELECT i.*, 
                           p.nombres as trabajador_nombre,
                           p.apellidos as trabajador_apellido
                    FROM indicadores_estres i
                    LEFT JOIN trabajador t ON i.trabajador_id = t.id
                    LEFT JOIN persona p ON t.idpersona = p.idpersona
                    WHERE i.departamento_id = $this->intDepartamentoId
                    ORDER BY i.fecha_calculo DESC";
            $request = $this->select_all($sql);
            return $request;
        }

        // Obtener estadísticas de indicadores
        public function getEstadisticasIndicadores()
        {
            $sql = "SELECT 
                        COUNT(*) as total_indicadores,
                        AVG(nivel_estres) as promedio_general,
                        MIN(nivel_estres) as minimo,
                        MAX(nivel_estres) as maximo,
                        COUNT(DISTINCT trabajador_id) as trabajadores_monitoreados,
                        COUNT(DISTINCT departamento_id) as departamentos_monitoreados
                    FROM indicadores_estres 
                    WHERE fecha_calculo >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
            $request = $this->select($sql);
            return $request;
        }

        // Obtener tendencias por período
        public function getTendenciasPeriodo(string $periodo = '30 DAY')
        {
            $sql = "SELECT 
                        DATE(fecha_calculo) as fecha,
                        AVG(nivel_estres) as promedio_diario,
                        COUNT(*) as total_registros
                    FROM indicadores_estres 
                    WHERE fecha_calculo >= DATE_SUB(NOW(), INTERVAL $periodo)
                    GROUP BY DATE(fecha_calculo)
                    ORDER BY fecha ASC";
            $request = $this->select_all($sql);
            return $request;
        }

        // Obtener distribución por categoría
        public function getDistribucionCategoria()
        {
            $sql = "SELECT 
                        categoria,
                        COUNT(*) as total,
                        AVG(nivel_estres) as promedio
                    FROM indicadores_estres 
                    WHERE fecha_calculo >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                    GROUP BY categoria
                    ORDER BY total DESC";
            $request = $this->select_all($sql);
            return $request;
        }

        // Obtener top trabajadores con mayor estrés
        public function getTopTrabajadoresEstres(int $limite = 10)
        {
            $sql = "SELECT 
                        t.id as trabajador_id,
                        p.nombres,
                        p.apellidos,
                        d.nombre as departamento,
                        AVG(i.nivel_estres) as promedio_estres,
                        COUNT(i.id) as total_registros
                    FROM indicadores_estres i
                    INNER JOIN trabajador t ON i.trabajador_id = t.id
                    INNER JOIN persona p ON t.idpersona = p.idpersona
                    LEFT JOIN departamentos d ON t.departamento_id = d.id
                    WHERE i.fecha_calculo >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                    GROUP BY t.id, p.nombres, p.apellidos, d.nombre
                    ORDER BY promedio_estres DESC
                    LIMIT $limite";
            $request = $this->select_all($sql);
            return $request;
        }
    }
?>