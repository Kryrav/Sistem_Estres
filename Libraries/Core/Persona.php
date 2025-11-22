<?php 
class Persona extends Mysql
{
    // Campos comunes a la tabla 'persona'
    public $intIdPersona;
    public $strIdentificacion;
    public $strNombre;
    public $strApellido;
    public $intTelefono;
    public $strEmail;
    public $strPassword; // Almacenará el hash
    public $intTipoId; // Corresponde a rolid
    public $intStatus;

    public function __construct()
    {
        parent::__construct();
    }

    // [HELPER] Selecciona los Roles
    public function selectRoles()
    {
        // Se asume que la tabla de roles tiene 'idrol' y 'nombrerol'
        $sql = "SELECT idrol, nombrerol AS nombre FROM rol WHERE status != 0";
        $request = $this->select_all($sql);
        return $request;
    }

    // Inserta un nuevo registro en la tabla 'persona'
    public function insertPersona(string $identificacion, string $nombre, string $apellido, int $telefono, string $email, string $passwordHash, int $tipoid, int $status)
    {
        $return = 0;

        // Validación de existencia por Identificación y Email (Usando parámetros seguros)
        $sql = "SELECT * FROM persona WHERE identificacion = ? OR email_user = ? ";
        $arrCheck = array($identificacion, $email);
        $request = $this->select_all($sql, $arrCheck);

        if(empty($request))
        {
            // Nota clave: Usamos 'password_hash' para el campo de la DB
            $query_insert = "INSERT INTO persona(identificacion, nombres, apellidos, telefono, email_user, password_hash, rolid, status) 
                             VALUES(?,?,?,?,?,?,?,?)";
            $arrData = array($identificacion, $nombre, $apellido, $telefono, $email, $passwordHash, $tipoid, $status);
            $request_insert = $this->insert($query_insert, $arrData);
            $return = $request_insert; 
        } else {
            $return = "exist";
        }
        return $return;
    }

    // Actualiza un registro en la tabla 'persona'
    public function updatePersona(int $idpersona, string $identificacion, string $nombre, string $apellido, int $telefono, string $email, string $passwordHash, int $tipoid, int $status)
    {
        // Validación de existencia con exclusión del ID actual (Usando parámetros seguros)
        $sql = "SELECT idpersona FROM persona 
                WHERE (identificacion = ? OR email_user = ?) 
                AND idpersona != ?"; 
        $arrCheck = array($identificacion, $email, $idpersona);
        $request = $this->select_all($sql, $arrCheck);

        if(empty($request))
        {
            if(!empty($passwordHash)) // Si se proporciona un nuevo hash
            {
                $sql_update = "UPDATE persona 
                                SET identificacion=?, nombres=?, apellidos=?, telefono=?, email_user=?, password_hash=?, rolid=?, status=? 
                                WHERE idpersona = ?";
                $arrData = array($identificacion, $nombre, $apellido, $telefono, $email, $passwordHash, $tipoid, $status, $idpersona);
            } else { // Si no se proporciona contraseña, no se actualiza el campo
                $sql_update = "UPDATE persona 
                                SET identificacion=?, nombres=?, apellidos=?, telefono=?, email_user=?, rolid=?, status=? 
                                WHERE idpersona = ?";
                $arrData = array($identificacion, $nombre, $apellido, $telefono, $email, $tipoid, $status, $idpersona);
            }
            $request = $this->update($sql_update, $arrData);
            return $request;
        }else{
            return "exist";
        }
    }

    // Elimina lógicamente (Soft Delete) un registro de la tabla 'persona'
    public function deletePersona(int $idpersona)
    {
        $sql = "UPDATE persona SET status = ? WHERE idpersona = ?";
        $arrData = array(0, $idpersona);
        $request = $this->update($sql, $arrData);

        if($request)
        {
            return 'ok';
        } else {
            return 'error';
        }
    }
}