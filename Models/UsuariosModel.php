<?php 

class UsuariosModel extends Persona // <--- ¡Herencia Aplicada!
{
    private $intIdUsuario;
    private $strNit;
    private $strNomFiscal;
    private $strDirFiscal;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Inserta un nuevo usuario/persona en la DB. 
     * Delega la lógica de inserción y validación de existencia a la Superclase Persona.
     * @param string $passwordHash El hash seguro de la contraseña (usando password_hash()).
     */
    public function insertUsuario(string $identificacion, string $nombre, string $apellido, int $telefono, string $email, string $passwordHash, int $tipoid, int $status)
    {
        // Se llama directamente al método de la clase padre.
        // La Superclase maneja la validación de existencia y la inserción en la tabla 'persona'.
        return parent::insertPersona($identificacion, $nombre, $apellido, $telefono, $email, $passwordHash, $tipoid, $status);
    }

    /**
     * Actualiza un usuario.
     * Delega la lógica de actualización y validación de existencia a la Superclase Persona.
     */
    public function updateUsuario(int $idUsuario, string $identificacion, string $nombre, string $apellido, int $telefono, string $email, string $passwordHash, int $tipoid, int $status)
    {
        // Se llama directamente al método de la clase padre.
        // La Superclase se encarga de no actualizar el hash si es vacío.
        return parent::updatePersona($idUsuario, $identificacion, $nombre, $apellido,  $telefono, $email, $passwordHash,  $tipoid,  $status);
    }
    
    /**
     * Desactiva un usuario.
     * Delega la lógica de desactivación (soft delete) a la Superclase Persona.
     */
    public function deleteUsuario(int $intIdpersona)
    {
        // Se llama directamente al método de la clase padre.
        return parent::deletePersona($intIdpersona);
    }

    // ----------------------------------------------------------------------------------
    // MÉTODOS ESPECÍFICOS O DE VISTA (Se mantienen en la Subclase)
    // ----------------------------------------------------------------------------------

    /**
     * Selecciona todos los usuarios para la vista principal (incluye el filtro de SuperAdmin).
     */
    public function selectUsuarios()
    {
        $whereAdmin = "";
        $arrData = [];
        
        // La lógica de WHERE se mantiene en PHP
        if(isset($_SESSION['idUser']) && $_SESSION['idUser'] != 1 ){
            $whereAdmin = " AND p.idpersona != ?";
            $arrData[] = 1; 
        }
        
        $sql = "SELECT p.idpersona,p.identificacion,p.nombres,p.apellidos,p.telefono,p.email_user,p.status,r.idrol,r.nombrerol 
                FROM persona p 
                INNER JOIN rol r ON p.rolid = r.idrol
                WHERE p.status != 0 ".$whereAdmin;
        
        $request = $this->select_all($sql, $arrData); 
        return $request;
    }
    
    /**
     * Selecciona un usuario específico (incluye campos fiscales).
     */
    public function selectUsuario(int $idpersona)
    {
        $this->intIdUsuario = $idpersona;
        
        $sql = "SELECT p.idpersona,p.identificacion,p.nombres,p.apellidos,p.telefono,p.email_user,p.nit,p.nombrefiscal,p.direccionfiscal,r.idrol,r.nombrerol,p.status, DATE_FORMAT(p.datecreated, '%d-%m-%Y') as fechaRegistro 
                FROM persona p
                INNER JOIN rol r ON p.rolid = r.idrol
                WHERE p.idpersona = ?";
        
        $arrData = array($this->intIdUsuario);
        $request = $this->select($sql, $arrData);
        
        return $request;
    }

    /**
     * Actualiza solo los datos del perfil (sin rol ni status).
     * Nota: Se debe mantener esta lógica aquí ya que es un update parcial de la Superclase.
     */
    public function updatePerfil(int $idUsuario, string $identificacion, string $nombre, string $apellido, int $telefono, string $passwordHash)
    {
        $this->intIdUsuario = $idUsuario;
        $this->strIdentificacion = $identificacion;
        $this->strNombre = $nombre;
        $this->strApellido = $apellido;
        $this->intTelefono = $telefono;
        $this->strPassword = $passwordHash; 

        // Lógica de update simplificada (no se puede usar updatePersona directamente ya que se necesitan rol y status)
        if(!empty($this->strPassword)) 
        {
            $sql = "UPDATE persona SET identificacion=?, nombres=?, apellidos=?, telefono=?, password_hash=? 
                    WHERE idpersona = ?";
            $arrData = array($this->strIdentificacion, $this->strNombre, $this->strApellido, $this->intTelefono, $this->strPassword, $this->intIdUsuario);
        }else{ 
            $sql = "UPDATE persona SET identificacion=?, nombres=?, apellidos=?, telefono=? 
                    WHERE idpersona = ?";
            $arrData = array($this->strIdentificacion, $this->strNombre, $this->strApellido, $this->intTelefono, $this->intIdUsuario);
        }
        $request = $this->update($sql,$arrData);
        return $request;
    }

    /**
     * Actualiza solo los datos fiscales (nit, nombrefiscal, direccionfiscal).
     */
    public function updateDataFiscal(int $idUsuario, string $strNit, string $strNomFiscal, string $strDirFiscal)
    {
        $this->intIdUsuario = $idUsuario;
        $this->strNit = $strNit;
        $this->strNomFiscal = $strNomFiscal;
        $this->strDirFiscal = $strDirFiscal;
        
        $sql = "UPDATE persona SET nit=?, nombrefiscal=?, direccionfiscal=? 
                WHERE idpersona = ?";
        $arrData = array($this->strNit, $this->strNomFiscal, $this->strDirFiscal, $this->intIdUsuario);
        $request = $this->update($sql,$arrData);
        return $request;
    }
}