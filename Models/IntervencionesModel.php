<?php 
    class IntervencionesModel extends Mysql
    {
        private $intIdIntervencion;
        private $intTrabajadorId;
        private $strTipoAlerta;
        private $strMensaje;
        private $strEstado;
        private $dateFechaGenerada;

        public function __construct()
        {
            parent::__construct();
        }

        // Obtener todas las intervenciones
        public function selectIntervenciones()
        {
            $sql = "SELECT i.*, 
                           p.nombres as trabajador_nombre,
                           p.apellidos as trabajador_apellido,
                           t.departamento_id,
                           d.nombre as departamento_nombre
                    FROM intervenciones_sistema i
                    INNER JOIN trabajador t ON i.trabajador_id = t.id
                    INNER JOIN persona p ON t.idpersona = p.idpersona
                    LEFT JOIN departamentos d ON t.departamento_id = d.id
                    ORDER BY i.fecha_generada DESC";
            $request = $this->select_all($sql);
            return $request;
        }

        // Obtener una intervención específica
        public function selectIntervencion(int $idintervencion)
        {
            $this->intIdIntervencion = $idintervencion;
            $sql = "SELECT i.*, 
                           p.nombres as trabajador_nombre,
                           p.apellidos as trabajador_apellido,
                           t.departamento_id,
                           d.nombre as departamento_nombre
                    FROM intervenciones_sistema i
                    INNER JOIN trabajador t ON i.trabajador_id = t.id
                    INNER JOIN persona p ON t.idpersona = p.idpersona
                    LEFT JOIN departamentos d ON t.departamento_id = d.id
                    WHERE i.id = $this->intIdIntervencion";
            $request = $this->select($sql);
            return $request;
        }

        // Insertar nueva intervención
        public function insertIntervencion(int $trabajador_id, string $tipo_alerta, string $mensaje, string $estado)
        {
            $this->intTrabajadorId = $trabajador_id;
            $this->strTipoAlerta = $tipo_alerta;
            $this->strMensaje = $mensaje;
            $this->strEstado = $estado;

            $query_insert = "INSERT INTO intervenciones_sistema(trabajador_id, tipo_alerta, mensaje, estado) 
                            VALUES(?,?,?,?)";
            $arrData = array($this->intTrabajadorId, $this->strTipoAlerta, $this->strMensaje, $this->strEstado);
            $request_insert = $this->insert($query_insert, $arrData);
            return $request_insert;
        }

        // Actualizar intervención
        public function updateIntervencion(int $idintervencion, string $estado)
        {
            $this->intIdIntervencion = $idintervencion;
            $this->strEstado = $estado;

            $sql = "UPDATE intervenciones_sistema SET estado = ? WHERE id = $this->intIdIntervencion";
            $arrData = array($this->strEstado);
            $request = $this->update($sql, $arrData);
            return $request;
        }

        // Eliminar intervención
        public function deleteIntervencion(int $idintervencion)
        {
            $this->intIdIntervencion = $idintervencion;
            $sql = "DELETE FROM intervenciones_sistema WHERE id = $this->intIdIntervencion";
            $request = $this->delete($sql);
            return $request;
        }

        // Obtener intervenciones por trabajador
        public function selectIntervencionesTrabajador(int $trabajador_id)
        {
            $this->intTrabajadorId = $trabajador_id;
            $sql = "SELECT i.*, 
                           p.nombres as trabajador_nombre,
                           p.apellidos as trabajador_apellido
                    FROM intervenciones_sistema i
                    INNER JOIN trabajador t ON i.trabajador_id = t.id
                    INNER JOIN persona p ON t.idpersona = p.idpersona
                    WHERE i.trabajador_id = $this->intTrabajadorId
                    ORDER BY i.fecha_generada DESC";
            $request = $this->select_all($sql);
            return $request;
        }

        // Obtener estadísticas de intervenciones
        public function getEstadisticasIntervenciones()
        {
            $sql = "SELECT 
                        tipo_alerta,
                        estado,
                        COUNT(*) as total,
                        DATE(fecha_generada) as fecha
                    FROM intervenciones_sistema 
                    WHERE fecha_generada >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                    GROUP BY tipo_alerta, estado, DATE(fecha_generada)
                    ORDER BY total DESC";
            $request = $this->select_all($sql);
            return $request;
        }

        // Obtener intervenciones pendientes
        public function getIntervencionesPendientes()
        {
            $sql = "SELECT i.*, 
                           p.nombres as trabajador_nombre,
                           p.apellidos as trabajador_apellido
                    FROM intervenciones_sistema i
                    INNER JOIN trabajador t ON i.trabajador_id = t.id
                    INNER JOIN persona p ON t.idpersona = p.idpersona
                    WHERE i.estado = 'pendiente'
                    ORDER BY i.fecha_generada ASC";
            $request = $this->select_all($sql);
            return $request;
        }
    }
?>