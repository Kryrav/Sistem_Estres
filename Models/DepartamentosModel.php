<?php 

class DepartamentosModel extends Mysql
{
    public $intIdDepartamento;
    public $strNombre;
    public $strDescripcion;
    public $intUmbralAlertaStress;

    public function __construct()
    {
        parent::__construct();
    }

    // Selecciona todos los Departamentos
    public function selectDepartamentos()
    {
        $sql = "SELECT id, nombre, descripcion, umbral_alerta_stress 
                FROM departamentos";
        $request = $this->select_all($sql);
        return $request;
    }

    // Obtiene un Departamento por ID
    public function selectDepartamento(int $idDepartamento)
    {
        $this->intIdDepartamento = $idDepartamento;
        $sql = "SELECT id, nombre, descripcion, umbral_alerta_stress 
                FROM departamentos 
                WHERE id = $this->intIdDepartamento";
        $request = $this->select($sql); 
        return $request;
    }

    // Inserta un nuevo Departamento
    public function insertDepartamento(string $nombre, string $descripcion, int $umbral){

        $return = "";
        $this->strNombre = $nombre;
        $this->strDescripcion = $descripcion;
        $this->intUmbralAlertaStress = $umbral;

        // Validar si el nombre ya existe
        $sql = "SELECT * FROM departamentos WHERE nombre = '{$this->strNombre}' ";
        $request = $this->select_all($sql);

        if(empty($request))
        {
            $query_insert = "INSERT INTO departamentos(nombre, descripcion, umbral_alerta_stress) 
                             VALUES(?,?,?)";
            $arrData = array($this->strNombre, $this->strDescripcion, $this->intUmbralAlertaStress);
            $request_insert = $this->insert($query_insert, $arrData);
            $return = $request_insert;
        }else{
            $return = "exist";
        }
        return $return;
    }

    // Actualiza un Departamento existente
    public function updateDepartamento(int $idDepartamento, string $nombre, string $descripcion, int $umbral){
        $this->intIdDepartamento = $idDepartamento;
        $this->strNombre = $nombre;
        $this->strDescripcion = $descripcion;
        $this->intUmbralAlertaStress = $umbral;

        // Validar si otro departamento con el mismo nombre ya existe
        $sql = "SELECT * FROM departamentos 
                WHERE nombre = '$this->strNombre' AND id != $this->intIdDepartamento";
        $request = $this->select_all($sql);

        if(empty($request))
        {
            $sql = "UPDATE departamentos 
                    SET nombre = ?, descripcion = ?, umbral_alerta_stress = ?
                    WHERE id = $this->intIdDepartamento ";
            $arrData = array($this->strNombre, $this->strDescripcion, $this->intUmbralAlertaStress);
            $request = $this->update($sql, $arrData);
        }else{
            $request = "exist";
        }
        return $request;         
    }

    // Elimina un Departamento (Borrado físico, verifica si está asociado a un trabajador)
    public function deleteDepartamento(int $idDepartamento)
    {
        $this->intIdDepartamento = $idDepartamento;
        
        // Verificar si existen trabajadores asociados (tabla 'trabajador')
        $sql = "SELECT * FROM trabajador WHERE departamento_id = $this->intIdDepartamento"; 
        $request = $this->select_all($sql);
        
        if(empty($request))
        {
            // No hay trabajadores, proceder con la eliminación
            $sql = "DELETE FROM departamentos WHERE id = $this->intIdDepartamento ";
            $request = $this->delete($sql);
            if($request)
            {
                $request = 'ok';    
            }else{
                $request = 'error';
            }
        }else{
            $request = 'exist'; // Tiene trabajadores asociados
        }
        return $request;
    }
}