<?php 

    class UsuariosModel extends Mysql
    {
        private $intIdUsuario;
        private $strIdentificacion;
        private $strNombre;
        private $strApellido;
        private $intTelefono;
        private $strEmail;
        private $strPassword; // Ahora contendrá el hash seguro
        private $strToken;
        private $intTipoId;
        private $intStatus;
        private $strNit;
        private $strNomFiscal;
        private $strDirFiscal;

        public function __construct()
        {
            parent::__construct();
        }   

        public function insertUsuario(string $identificacion, string $nombre, string $apellido, int $telefono, string $email, string $passwordHash, int $tipoid, int $status){

            $this->strIdentificacion = $identificacion;
            $this->strNombre = $nombre;
            $this->strApellido = $apellido;
            $this->intTelefono = $telefono;
            $this->strEmail = $email;
            $this->strPassword = $passwordHash; // Contiene el hash seguro de password_hash()
            $this->intTipoId = $tipoid;
            $this->intStatus = $status;
            $return = 0;

            // CONSULTA SEGURA: Se usa ? en el SQL y se pasan los valores en $arrCheck
            $sql = "SELECT * FROM persona WHERE email_user = ? OR identificacion = ?";
            $arrCheck = array($this->strEmail, $this->strIdentificacion);
            $request = $this->select_all($sql, $arrCheck);

            if(empty($request))
            {
                // NOTA: Se cambia 'password' por 'password_hash' para usar el campo seguro
                $query_insert  = "INSERT INTO persona(identificacion,nombres,apellidos,telefono,email_user,password_hash,rolid,status) 
                                     VALUES(?,?,?,?,?,?,?,?)";
                $arrData = array($this->strIdentificacion,
                                 $this->strNombre,
                                 $this->strApellido,
                                 $this->intTelefono,
                                 $this->strEmail,
                                 $this->strPassword, // El hash seguro
                                 $this->intTipoId,
                                 $this->intStatus);
                $request_insert = $this->insert($query_insert,$arrData);
                $return = $request_insert;
            }else{
                $return = "exist";
            }
            return $return;
        }

        public function selectUsuarios()
        {
            $whereAdmin = "";
            $arrData = []; // Inicializamos el array de datos para el select
            
            // La lógica de WHERE se mantiene en PHP, el SQL sigue siendo seguro.
            if($_SESSION['idUser'] != 1 ){
                $whereAdmin = " AND p.idpersona != ?";
                $arrData[] = 1; // Añadimos el ID 1 al array de parámetros
            }
            
            // Consulta base segura (la concatenación es solo una condición fija)
            $sql = "SELECT p.idpersona,p.identificacion,p.nombres,p.apellidos,p.telefono,p.email_user,p.status,r.idrol,r.nombrerol 
                    FROM persona p 
                    INNER JOIN rol r ON p.rolid = r.idrol
                    WHERE p.status != 0 ".$whereAdmin;
            
            // Si $whereAdmin está vacío, $arrData está vacío y Mysql::select_all() lo manejará.
            $request = $this->select_all($sql, $arrData); 
            return $request;
        }
        
        public function selectUsuario(int $idpersona){
            $this->intIdUsuario = $idpersona;
            
            // CONSULTA SEGURA: Se usa ?
            $sql = "SELECT p.idpersona,p.identificacion,p.nombres,p.apellidos,p.telefono,p.email_user,p.nit,p.nombrefiscal,p.direccionfiscal,r.idrol,r.nombrerol,p.status, DATE_FORMAT(p.datecreated, '%d-%m-%Y') as fechaRegistro 
                    FROM persona p
                    INNER JOIN rol r ON p.rolid = r.idrol
                    WHERE p.idpersona = ?";
            
            $arrData = array($this->intIdUsuario);
            $request = $this->select($sql, $arrData);
            
            return $request;
        }

        public function updateUsuario(int $idUsuario, string $identificacion, string $nombre, string $apellido, int $telefono, string $email, string $passwordHash, int $tipoid, int $status){

            $this->intIdUsuario = $idUsuario;
            $this->strIdentificacion = $identificacion;
            $this->strNombre = $nombre;
            $this->strApellido = $apellido;
            $this->intTelefono = $telefono;
            $this->strEmail = $email;
            $this->strPassword = $passwordHash; // Contiene hash seguro o ""
            $this->intTipoId = $tipoid;
            $this->intStatus = $status;

            // CONSULTA SEGURA para verificar existencia
            $sql = "SELECT idpersona FROM persona WHERE (email_user = ? AND idpersona != ?)
                                                 OR (identificacion = ? AND idpersona != ?) ";
            $arrCheck = array($this->strEmail, $this->intIdUsuario, $this->strIdentificacion, $this->intIdUsuario);
            $request = $this->select_all($sql, $arrCheck);

            if(empty($request))
            {
                if($this->strPassword != "") // Si se va a cambiar la contraseña
                {
                    // NOTA: Se cambia 'password' por 'password_hash'
                    $sql = "UPDATE persona SET identificacion=?, nombres=?, apellidos=?, telefono=?, email_user=?, password_hash=?, rolid=?, status=? 
                            WHERE idpersona = ?";
                    $arrData = array($this->strIdentificacion,
                                     $this->strNombre,
                                     $this->strApellido,
                                     $this->intTelefono,
                                     $this->strEmail,
                                     $this->strPassword, // El hash seguro
                                     $this->intTipoId,
                                     $this->intStatus,
                                     $this->intIdUsuario);
                }else{ // Si la contraseña se deja igual
                    $sql = "UPDATE persona SET identificacion=?, nombres=?, apellidos=?, telefono=?, email_user=?, rolid=?, status=? 
                            WHERE idpersona = ?";
                    $arrData = array($this->strIdentificacion,
                                     $this->strNombre,
                                     $this->strApellido,
                                     $this->intTelefono,
                                     $this->strEmail,
                                     $this->intTipoId,
                                     $this->intStatus,
                                     $this->intIdUsuario);
                }
                $request = $this->update($sql,$arrData);
            }else{
                $request = "exist";
            }
            return $request;
        
        }
        
        public function deleteUsuario(int $intIdpersona)
        {
            $this->intIdUsuario = $intIdpersona;
            
            // UPDATE SEGURA: Se usa ?
            $sql = "UPDATE persona SET status = ? WHERE idpersona = ?";
            $arrData = array(0, $this->intIdUsuario);
            $request = $this->update($sql,$arrData);
            
            return $request;
        }

        public function updatePerfil(int $idUsuario, string $identificacion, string $nombre, string $apellido, int $telefono, string $passwordHash){
            $this->intIdUsuario = $idUsuario;
            $this->strIdentificacion = $identificacion;
            $this->strNombre = $nombre;
            $this->strApellido = $apellido;
            $this->intTelefono = $telefono;
            $this->strPassword = $passwordHash; // Contiene hash seguro o ""

            if($this->strPassword != "") // Si se va a cambiar la contraseña
            {
                // UPDATE SEGURA: Se usa ? y se actualiza 'password_hash'
                $sql = "UPDATE persona SET identificacion=?, nombres=?, apellidos=?, telefono=?, password_hash=? 
                        WHERE idpersona = ?";
                $arrData = array($this->strIdentificacion,
                                 $this->strNombre,
                                 $this->strApellido,
                                 $this->intTelefono,
                                 $this->strPassword, // El hash seguro
                                 $this->intIdUsuario);
            }else{ // Si la contraseña se deja igual
                // UPDATE SEGURA: Se usa ?
                $sql = "UPDATE persona SET identificacion=?, nombres=?, apellidos=?, telefono=? 
                        WHERE idpersona = ?";
                $arrData = array($this->strIdentificacion,
                                 $this->strNombre,
                                 $this->strApellido,
                                 $this->intTelefono,
                                 $this->intIdUsuario);
            }
            $request = $this->update($sql,$arrData);
            return $request;
        }

        public function updateDataFiscal(int $idUsuario, string $strNit, string $strNomFiscal, string $strDirFiscal){
            $this->intIdUsuario = $idUsuario;
            $this->strNit = $strNit;
            $this->strNomFiscal = $strNomFiscal;
            $this->strDirFiscal = $strDirFiscal;
            
            // UPDATE SEGURA: Se usa ?
            $sql = "UPDATE persona SET nit=?, nombrefiscal=?, direccionfiscal=? 
                    WHERE idpersona = ?";
            $arrData = array($this->strNit,
                             $this->strNomFiscal,
                             $this->strDirFiscal,
                             $this->intIdUsuario);
            $request = $this->update($sql,$arrData);
            return $request;
        }
    }
?>