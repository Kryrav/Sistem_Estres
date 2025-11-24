<?php 
class EncuestasModel extends Mysql
{
    // Propiedades para mapear los atributos de la tabla 'encuestas'
    public $intIdEncuesta;
    public $strTitulo; // Coincide con el campo 'titulo' de la tabla 'encuestas'
    public $strDescripcion;
    public $strFechaInicio;
    public $strFechaFin;
    public $strEstado; // ACTIVA, INACTIVA, BORRADOR (según ENUM)

    public function __construct()
    {
        parent::__construct();
    }

    // =======================================================================
    // MÉTODOS CRUD DE DATOS GENERALES (Usando Propiedades de Clase)
    // =======================================================================

    /**
     * Selecciona todas las Encuestas en el sistema para DataTables.
     * Se ajusta para usar el campo 'estado' en lugar de 'status'.
     * @return array Registros de las encuestas.
     */
    public function selectEncuestas()
    {
        $sql = "SELECT 
                    id, 
                    titulo, 
                    descripcion, 
                    DATE_FORMAT(fecha_inicio, '%d/%m/%Y') AS fecha_inicio,
                    DATE_FORMAT(fecha_fin, '%d/%m/%Y') AS fecha_fin, 
                    estado 
                FROM encuestas 
                ORDER BY fecha_creacion DESC";
        $request = $this->select_all($sql);
        return $request;
    }

    /**
     * Obtiene una Encuesta específica por su ID.
     * @param int $idEncuesta ID de la encuesta a buscar.
     * @return array|null El registro de la encuesta o null si no existe.
     */
    public function selectEncuesta(int $idEncuesta)
    {
        $this->intIdEncuesta = $idEncuesta;
        // Se utiliza la propiedad en la consulta directamente
        $sql = "SELECT 
                    id, 
                    titulo, 
                    descripcion, 
                    DATE_FORMAT(fecha_inicio, '%Y-%m-%d') AS fecha_inicio, -- Formato Y-m-d para inputs HTML
                    DATE_FORMAT(fecha_fin, '%Y-%m-%d') AS fecha_fin, 
                    estado 
                FROM encuestas 
                WHERE id = $this->intIdEncuesta";
        $request = $this->select($sql); 
        return $request;
    }

    /**
     * Inserta un nuevo registro de Encuesta.
     * @return mixed El ID del nuevo registro insertado o "exist" si ya existe un título igual.
     */
    public function insertEncuesta(string $titulo, string $descripcion, string $fecha_inicio, string $fecha_fin, string $estado, int $usuario_creador_id)
    {
        $return = "";
        $this->strTitulo = $titulo;
        $this->strDescripcion = $descripcion;
        $this->strFechaInicio = $fecha_inicio;
        $this->strFechaFin = $fecha_fin;
        $this->strEstado = $estado;

        // Validar si el título ya existe (usando la propiedad)
        $sql = "SELECT * FROM encuestas WHERE titulo = '{$this->strTitulo}' ";
        $request = $this->select_all($sql);

        if(empty($request))
        {
            $query_insert = "INSERT INTO encuestas(titulo, descripcion, fecha_inicio, fecha_fin, estado, usuario_creador_persona_id) 
                             VALUES(?,?,?,?,?,?)";
            // Se usa $arrData para la ejecución con prepared statements del método insert()
            $arrData = array($this->strTitulo, $this->strDescripcion, $this->strFechaInicio, $this->strFechaFin, $this->strEstado, $usuario_creador_id);
            $request_insert = $this->insert($query_insert, $arrData);
            $return = $request_insert;
        }else{
            $return = "exist";
        }
        return $return;
    }

    /**
     * Actualiza un registro de Encuesta existente.
     */
    public function updateEncuesta(int $idEncuesta, string $titulo, string $descripcion, string $fecha_inicio, string $fecha_fin, string $estado)
    {
        $this->intIdEncuesta = $idEncuesta;
        $this->strTitulo = $titulo;
        $this->strDescripcion = $descripcion;
        $this->strFechaInicio = $fecha_inicio;
        $this->strFechaFin = $fecha_fin;
        $this->strEstado = $estado;

        // Validar si otra encuesta con el mismo título ya existe (excluyendo la actual)
        $sql = "SELECT * FROM encuestas 
                WHERE titulo = '$this->strTitulo' AND id != $this->intIdEncuesta";
        $request = $this->select_all($sql);

        if(empty($request))
        {
            // Se utiliza la propiedad en la cláusula WHERE
            $sql = "UPDATE encuestas 
                    SET titulo = ?, descripcion = ?, fecha_inicio = ?, fecha_fin = ?, estado = ?
                    WHERE id = $this->intIdEncuesta ";
            // Se usa $arrData para la ejecución con prepared statements
            $arrData = array($this->strTitulo, $this->strDescripcion, $this->strFechaInicio, $this->strFechaFin, $this->strEstado);
            $request = $this->update($sql, $arrData);
        }else{
            $request = "exist";
        }
        return $request;
    }
    
    /**
     * Elimina un registro de Encuesta (Borrado físico).
     * Verifica la tabla `encuesta_respondida` antes de eliminar.
     */
    public function deleteEncuesta(int $idEncuesta)
    {
        $this->intIdEncuesta = $idEncuesta;
        
        // 1. Verificar si existen respuestas asociadas (dependencias en la tabla encuesta_respondida)
        $sql = "SELECT * FROM encuesta_respondida WHERE encuesta_id = $this->intIdEncuesta"; 
        $request = $this->select_all($sql);
        
        if(empty($request))
        {
            // Borrado físico (asumiendo que DELETE ON CASCADE gestiona encuesta_pregunta)
            $sql = "DELETE FROM encuestas WHERE id = $this->intIdEncuesta ";
            $request = $this->delete($sql);
            
            if($request)
            {
                $request = 'ok';
            }else{
                $request = 'error';
            }
        }else{
            $request = 'exist'; // Tiene respuestas asociadas, no se puede borrar
        }
        return $request;
    }

    // =======================================================================
    // MÉTODOS DE ASIGNACIÓN Y DETALLE DE PREGUNTAS
    // =======================================================================

    /**
     * Obtiene las preguntas asignadas a una encuesta específica, ordenadas.
     * Usada por el controlador para cargar el modal de asignación (columna derecha).
     */
    public function selectPreguntasAsignadas(int $idEncuesta)
    {
        $this->intIdEncuesta = $idEncuesta;
        $sql = "SELECT 
                    ep.orden, 
                    p.id AS id_pregunta, 
                    p.texto_pregunta, 
                    p.tipo_pregunta,
                    ci.nombre AS categoria 
                FROM encuesta_pregunta ep
                INNER JOIN preguntas p ON ep.pregunta_id = p.id
                -- **AJUSTE DE TABLA:** Usando 'categorias_indicadores'
                INNER JOIN categorias_indicadores ci ON p.categoria_id = ci.id 
                WHERE ep.encuesta_id = $this->intIdEncuesta 
                ORDER BY ep.orden ASC";
        
        $request = $this->select_all($sql); 
        return $request;
    }

    /**
     * Obtiene todas las preguntas activas disponibles del banco para el modal de asignación (columna izquierda).
     * @param int $categoriaId ID de la categoría para filtrar (0 para todas).
     * @param string $search Texto para buscar en la pregunta.
     * @return array
     */
    public function selectPreguntasDisponibles(int $categoriaId = 0, string $search = '')
    {
        // Se usa la inyección directa de variables de filtro para simplificar.
        $where = "p.activo = 1";
        
        if ($categoriaId > 0) {
            $where .= " AND p.categoria_id = {$categoriaId}";
        }

        if (!empty($search)) {
            // Asegurando la sanitización (que se espera venga de strClean en el controlador)
            $search = "%{$search}%"; 
            $where .= " AND p.texto_pregunta LIKE '{$search}'";
        }

        $sql = "SELECT 
                    p.id, 
                    p.texto_pregunta, 
                    p.tipo_pregunta,
                    ci.nombre AS categoria 
                FROM preguntas p
                -- **AJUSTE DE TABLA:** Usando 'categorias_indicadores'
                INNER JOIN categorias_indicadores ci ON p.categoria_id = ci.id
                WHERE {$where}
                ORDER BY p.id DESC";

        $request = $this->select_all($sql);
        return $request;
    }

    /**
     * Guarda la asignación y el orden de las preguntas para una encuesta.
     * Es una operación transaccional (asumiendo que Mysql soporta begin/commit/rollback).
     */
    public function updateAsignacionPreguntas(int $idEncuesta, array $preguntasIds)
    {
        $this->intIdEncuesta = $idEncuesta; // Usar propiedad
        
        try {
            // Asumiendo que su clase Mysql tiene un método para iniciar la transacción.
            // $this->startTransaction(); 

            // 1. Eliminar todas las asignaciones existentes (usando la propiedad)
            $deleteSql = "DELETE FROM encuesta_pregunta WHERE encuesta_id = $this->intIdEncuesta";
            $this->delete($deleteSql); 

            // 2. Insertar las nuevas asignaciones
            if (!empty($preguntasIds)) {
                $orden = 1;
                $exito = true;
                
                // Insertar cada pregunta individualmente para manejar el orden
                foreach ($preguntasIds as $preguntaId) {
                    $insertSql = "INSERT INTO encuesta_pregunta (encuesta_id, pregunta_id, orden) VALUES (?, ?, ?)";
                    $arrData = array($this->intIdEncuesta, intval($preguntaId), $orden++);
                    
                    if (!$this->insert($insertSql, $arrData)) {
                         $exito = false;
                         break;
                    }
                }
            } else {
                $exito = true; // No hay preguntas para asignar
            }

            // if ($exito) { $this->commit(); } else { $this->rollback(); } 
            return $exito;

        } catch (Exception $e) {
            // $this->rollback();
            error_log("Error en updateAsignacionPreguntas: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtiene las preguntas y opciones de respuesta relacionadas a una encuesta (para vista de detalle).
     * Este método respeta la estructura de placeholders del ejemplo proporcionado.
     */
    public function selectDetalleEncuesta(int $idEncuesta)
    {
        $sql = "SELECT 
                    ep.id AS id_asignacion,  ep.orden, 
                    p.id AS id_pregunta, 
                    p.texto_pregunta, 
                    p.tipo_pregunta,
                    ci.nombre AS categoria
                FROM encuesta_pregunta ep
                INNER JOIN preguntas p ON ep.pregunta_id = p.id
                INNER JOIN categorias_indicadores ci ON p.categoria_id = ci.id
                WHERE ep.encuesta_id = ?
                ORDER BY ep.orden ASC";
        
        $arrData = array($idEncuesta);
        $request = $this->select_all($sql, $arrData);

        // Lógica adicional para obtener opciones si es necesario (ej. ESCALA/OPCION)
        foreach ($request as $key => $pregunta) {
            if (in_array($pregunta['tipo_pregunta'], ['ESCALA', 'OPCION'])) {
                $sqlOpciones = "SELECT id, texto_opcion, valor_numerico 
                                 FROM opciones_pregunta 
                                 WHERE pregunta_id = ?";
                $arrDataOpciones = array($pregunta['id_pregunta']);
                $opciones = $this->select_all($sqlOpciones, $arrDataOpciones);
                $request[$key]['opciones'] = $opciones;
            }
        }

        return $request;
    }
    /**
     * Elimina una pregunta de una encuesta (desasigna).
     *
     * @param int $idAsignacion El ID de la fila en la tabla 'encuesta_pregunta'.
     * @return bool Retorna el resultado de la operación DELETE (TRUE/FALSE).
     */
    public function deletePreguntaEncuesta(int $idAsignacion)
    {
        // 1. Definir la sentencia SQL para la eliminación.
        // El 'idAsignacion' corresponde al campo 'id' de la tabla encuesta_pregunta.
        $sql = "DELETE FROM encuesta_pregunta 
                WHERE id = ?"; 
        
        // 2. Definir el array de datos para la sentencia preparada.
        $arrData = array($idAsignacion);
        
        // 3. Ejecutar la operación.
        // NOTA: Se asume que $this->delete_row() es el método de tu framework/clase 
        // que ejecuta una sentencia de DELETE y devuelve un booleano.
        $request = $this->delete($sql, $arrData); 

        return $request;
    }

}


?>