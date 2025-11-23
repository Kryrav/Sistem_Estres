<?php 

    class PreguntasModel extends Mysql
    {
        // Propiedades de la tabla preguntas
        public $intId;
        public $intCategoriaId;
        public $strTextoPregunta;
        public $strTipoPregunta;
        public $intActivo;

        public function __construct()
        {
            parent::__construct();
        }

        /**
         * Selecciona todas las preguntas con su categoría asociada.
         */
        public function selectPreguntas()
        {
            $sql = "SELECT 
                        p.id, 
                        p.texto_pregunta, 
                        p.tipo_pregunta, 
                        c.nombre AS categoria, 
                        p.activo 
                    FROM preguntas p
                    INNER JOIN categorias_indicadores c ON p.categoria_id = c.id
                    WHERE p.activo IN (0, 1) 
                    ORDER BY p.id DESC";
            $request = $this->select_all($sql);
            return $request;
        }

        /**
         * Obtiene los detalles de una pregunta y sus opciones relacionadas.
         */
        public function selectPregunta(int $id)
        {
            $this->intId = $id;
            // 1. Obtener la pregunta principal
            $sql_pregunta = "SELECT 
                                p.id, 
                                p.categoria_id, 
                                p.texto_pregunta, 
                                p.tipo_pregunta, 
                                p.activo, 
                                c.nombre AS categoria 
                            FROM preguntas p
                            INNER JOIN categorias_indicadores c ON p.categoria_id = c.id
                            WHERE p.id = $this->intId AND p.activo IN (0, 1)";
            $request_pregunta = $this->select($sql_pregunta);
            
            if (!empty($request_pregunta)) {
                // 2. Si la pregunta existe, obtener sus opciones
                $request_opciones = $this->selectOpciones($this->intId);
                $request_pregunta['opciones'] = $request_opciones;
            }
            
            return $request_pregunta;
        }

        /**
         * Función auxiliar para obtener las opciones de respuesta de una pregunta.
         */
        private function selectOpciones(int $preguntaId)
        {
            $sql = "SELECT 
                        id, 
                        texto_opcion, 
                        valor_numerico 
                    FROM opciones_pregunta 
                    WHERE pregunta_id = $preguntaId
                    ORDER BY id ASC"; // Se asume que el orden está dado por el ID o se puede añadir una columna 'orden'
            return $this->select_all($sql);
        }

        /**
         * Inserta una nueva pregunta y sus opciones relacionadas (Transaccional).
         */
        public function insertPregunta(int $categoriaId, string $textoPregunta, string $tipoPregunta, array $opciones)
        {
            $this->intCategoriaId = $categoriaId;
            $this->strTextoPregunta = $textoPregunta;
            $this->strTipoPregunta = $tipoPregunta;

            $this->startTransaction(); // Inicia la transacción

            try {
                // 1. Insertar la pregunta
                $query_insert_pregunta = "INSERT INTO preguntas(categoria_id, texto_pregunta, tipo_pregunta, activo) VALUES(?,?,?,?)";
                $arrDataPregunta = array($this->intCategoriaId, $this->strTextoPregunta, $this->strTipoPregunta, 1);
                $pregunta_id = $this->insert($query_insert_pregunta, $arrDataPregunta);

                if ($pregunta_id > 0) {
                    // 2. Insertar las opciones (solo si es ESCALA u OPCION)
                    if (($this->strTipoPregunta == 'ESCALA' || $this->strTipoPregunta == 'OPCION') && !empty($opciones)) {
                        
                        foreach ($opciones as $opcion) {
                            $textoOpcion = strClean($opcion['texto']);
                            $valorNumerico = intval($opcion['valor']);

                            $query_insert_opcion = "INSERT INTO opciones_pregunta(pregunta_id, texto_opcion, valor_numerico) VALUES(?,?,?)";
                            $arrDataOpcion = array($pregunta_id, $textoOpcion, $valorNumerico);
                            $this->insert($query_insert_opcion, $arrDataOpcion);
                        }
                    }
                    $this->commitTransaction(); // Confirma si todo fue exitoso
                    return $pregunta_id; 
                } else {
                    $this->rollbackTransaction(); // Deshace si la inserción de la pregunta falla
                    return 0;
                }
            } catch (Exception $e) {
                $this->rollbackTransaction(); // Deshace ante cualquier error
                // Loggear error o manejarlo
                return 0;
            }
        } 

        /**
         * Actualiza una pregunta y sus opciones relacionadas (Transaccional).
         */
        public function updatePregunta(int $id, int $categoriaId, string $textoPregunta, string $tipoPregunta, int $activo, array $opciones){
            $this->intId = $id;
            $this->intCategoriaId = $categoriaId;
            $this->strTextoPregunta = $textoPregunta;
            $this->strTipoPregunta = $tipoPregunta;
            $this->intActivo = $activo;
            
            $this->startTransaction(); // Inicia la transacción

            try {
                // 1. Actualizar la pregunta
                $sql = "UPDATE preguntas SET categoria_id = ?, texto_pregunta = ?, tipo_pregunta = ?, activo = ? WHERE id = $this->intId";
                $arrDataPregunta = array($this->intCategoriaId, $this->strTextoPregunta, $this->strTipoPregunta, $this->intActivo);
                $request_update = $this->update($sql, $arrDataPregunta);

                if ($request_update) { // Si la actualización fue exitosa o no hubo cambios
                    
                    // 2. Eliminar las opciones antiguas asociadas a esta pregunta
                    $sql_delete_opciones = "DELETE FROM opciones_pregunta WHERE pregunta_id = $this->intId";
                    $this->delete($sql_delete_opciones); 
                    
                    // 3. Insertar las nuevas opciones (solo si es ESCALA u OPCION)
                    if (($this->strTipoPregunta == 'ESCALA' || $this->strTipoPregunta == 'OPCION') && !empty($opciones)) {
                        
                        foreach ($opciones as $opcion) {
                            $textoOpcion = strClean($opcion['texto']);
                            $valorNumerico = intval($opcion['valor']);

                            $query_insert_opcion = "INSERT INTO opciones_pregunta(pregunta_id, texto_opcion, valor_numerico) VALUES(?,?,?)";
                            $arrDataOpcion = array($this->intId, $textoOpcion, $valorNumerico);
                            $this->insert($query_insert_opcion, $arrDataOpcion);
                        }
                    }
                    $this->commitTransaction(); // Confirma si todo fue exitoso
                    return $this->intId; 
                } else {
                    $this->rollbackTransaction();
                    return 0;
                }
            } catch (Exception $e) {
                $this->rollbackTransaction();
                return 0;
            }
        }

        /**
         * Elimina lógicamente una pregunta.
         */
        public function deletePregunta(int $id)
        {
            $this->intId = $id;
            
            // Eliminación Lógica (cambio de estado a 0)
            $sql = "UPDATE preguntas SET activo = ? WHERE id = $this->intId";
            $arrData = array(0);
            $request = $this->update($sql, $arrData);
            
            if($request)
            {
                return true; 
            }else{
                return false;
            }
        }

    }
?>