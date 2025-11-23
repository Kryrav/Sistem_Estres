<?php 
    
    class Mysql extends Conexion
    {
        private $conexion;
        private $strquery;
        private $arrValues;

        function __construct()
        {
            $this->conexion = new Conexion();
            $this->conexion = $this->conexion->conect();
        }

        // Insertar un registro (YA USA PREPARED STATEMENTS)
        public function insert(string $query, array $arrValues)
        {
            $this->strquery = $query;
            $this->arrValues = $arrValues;
            $insert = $this->conexion->prepare($this->strquery);
            $resInsert = $insert->execute($this->arrValues);
            if($resInsert)
            {
                $lastInsert = $this->conexion->lastInsertId();
            }else{
                $lastInsert = 0;
            }
            return $lastInsert; 
        }

        // Busca un registro (AHORA SEGURO con Prepared Statements opcionales)
        public function select(string $query, array $arrValues = [])
        {
            $this->strquery = $query;
            $result = $this->conexion->prepare($this->strquery);
            
            // Usamos $arrValues para execute, protegiendo la consulta
            $result->execute($arrValues); 
            
            $data = $result->fetch(PDO::FETCH_ASSOC);
            return $data;
        }

        // Devuelve todos los registros (AHORA SEGURO con Prepared Statements opcionales)
        public function select_all(string $query, array $arrValues = [])
        {
            $this->strquery = $query;
            $result = $this->conexion->prepare($this->strquery);
            
            // Usamos $arrValues para execute, protegiendo la consulta
            $result->execute($arrValues); 
            
            $data = $result->fetchall(PDO::FETCH_ASSOC);
            return $data;
        }

        // Actualiza registros (YA USA PREPARED STATEMENTS)
        public function update(string $query, array $arrValues)
        {
            $this->strquery = $query;
            $this->arrValues = $arrValues;
            $update = $this->conexion->prepare($this->strquery);
            $resExecute = $update->execute($this->arrValues);
            return $resExecute;
        }

        // Eliminar un registros (AHORA SEGURO con Prepared Statements opcionales)
        public function delete(string $query, array $arrValues = [])
        {
            $this->strquery = $query;
            $result = $this->conexion->prepare($this->strquery);
            
            // Usamos $arrValues para execute, protegiendo la consulta
            $del = $result->execute($arrValues);
            return $del;
        }

        

        // Inicia una Transacción
        public function startTransaction()
        {
            $this->conexion->beginTransaction();
        }

        // Confirma la Transacción
        public function commitTransaction()
        {
            $this->conexion->commit();
        }

        // Revierte la Transacción
        public function rollbackTransaction()
        {
            $this->conexion->rollBack();
        }
    }
?>

