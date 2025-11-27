<?php 

    class CategoriasModel extends Mysql
    {
        // Propiedades de la tabla categorias_indicadores
        public $intId;
        public $strNombre;
        public $strDescripcion;
        public $intActivo;

        public function __construct()
        {
            parent::__construct();
        }

        /**
         * Selecciona todas las categorías activas e inactivas.
         */
        public function selectCategorias()
        {
            // Extrae Categorías
            $sql = "SELECT id, nombre, descripcion, activo FROM categorias_indicadores WHERE activo IN (0, 1) ORDER BY id DESC";
            $request = $this->select_all($sql);
            return $request;
        }

        /**
         * Selecciona una categoría por ID.
         */
        public function selectCategoria(int $id)
        {
            $this->intId = $id;
            // BUSCAR CATEGORÍA
            $sql = "SELECT id, nombre, descripcion, activo FROM categorias_indicadores WHERE id = $this->intId";
            $request = $this->select($sql);
            return $request;
        }

        /**
         * Inserta una nueva categoría.
         */
        public function insertCategoria(string $nombre, string $descripcion){

            $return = "";
            $this->strNombre = $nombre;
            $this->strDescripcion = $descripcion;
            $this->intActivo = 1; // Por defecto, se inserta activa

            // 1. Validar si ya existe una categoría con el mismo nombre
            $sql = "SELECT * FROM categorias_indicadores WHERE nombre = '{$this->strNombre}' AND activo = 1";
            $request = $this->select_all($sql);

            if(empty($request))
            {
                // 2. Insertar
                $query_insert  = "INSERT INTO categorias_indicadores(nombre, descripcion, activo) VALUES(?,?,?)";
                $arrData = array($this->strNombre, $this->strDescripcion, $this->intActivo);
                $request_insert = $this->insert($query_insert,$arrData);
                $return = $request_insert;
            }else{
                $return = "exist";
            }
            return $return;
        }

        /**
         * Actualiza una categoría existente.
         */
        public function updateCategoria(int $id, string $nombre, string $descripcion, int $activo){
            $this->intId = $id;
            $this->strNombre = $nombre;
            $this->strDescripcion = $descripcion;
            $this->intActivo = $activo;

            // 1. Validar que no exista otra categoría con el mismo nombre (excluyendo la actual)
            $sql = "SELECT * FROM categorias_indicadores WHERE nombre = '$this->strNombre' AND id != $this->intId AND activo = 1";
            $request = $this->select_all($sql);

            if(empty($request))
            {
                // 2. Actualizar
                $sql = "UPDATE categorias_indicadores SET nombre = ?, descripcion = ?, activo = ? WHERE id = $this->intId";
                $arrData = array($this->strNombre, $this->strDescripcion, $this->intActivo);
                $request = $this->update($sql,$arrData);
            }else{
                $request = "exist";
            }
            return $request;
        }

        /**
         * Elimina lógicamente una categoría (cambia activo a 0).
         */
        public function deleteCategoria(int $id)
        {
            $this->intId = $id;
            

            // 1. Eliminación Lógica
            $sql = "UPDATE categorias_indicadores SET activo = ? WHERE id = $this->intId";
            $arrData = array(0);
            $request = $this->update($sql,$arrData);
            
            if($request)
            {
                $request = 'ok'; 
            }else{
                $request = 'error';
            }
            return $request;
        }

        /**
         * Obtiene solo categorías activas (útil para SELECTs en otros formularios).
         */
        public function selectCategoriasActivas()
        {
            $sql = "SELECT id, nombre FROM categorias_indicadores WHERE activo = 1 ORDER BY id ASC";
            $request = $this->select_all($sql);
            return $request;
        }
    }
?>