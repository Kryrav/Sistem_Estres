<?php 

    class LoginModel extends Mysql
    {
        private $intIdUsuario;
        private $strUsuario;
        private $strPassword;
        private $strToken;

        public function __construct()
        {
            parent::__construct();
        } 

        /**
         * Verifica credenciales de usuario usando Prepared Statements y password_verify().
         * @param string $usuario Email del usuario.
         * @param string $password Contraseña en texto plano.
         * @return array|false Datos del usuario si el login es exitoso, false si falla.
         */
        public function loginUser(string $usuario, string $password)
        {
            $this->strUsuario = $usuario;
            $this->strPassword = $password;

            // 1. Consulta segura (pide el hash y status)
            // Se usa el marcador de posición (?) y se pasa el valor al método select
            $sql = "SELECT idpersona, status, password_hash FROM persona WHERE 
                    email_user = ? AND status != 0 LIMIT 1";
            
            $arrData = array($this->strUsuario);
            $request = $this->select($sql, $arrData);

            if (empty($request)) {
                return false; // Usuario no encontrado o inactivo
            }

            // 2. Comprobar la contraseña hasheada
            // Compara la contraseña en texto plano ($this->strPassword) con el hash almacenado en la DB.
            if (password_verify($this->strPassword, $request['password_hash'])) {
                
                // Si la contraseña es correcta, quitamos el hash antes de retornar.
                unset($request['password_hash']);
                return $request;
            } else {
                return false; // Contraseña incorrecta
            }
        }

        public function sessionLogin(int $iduser){
            $this->intIdUsuario = $iduser;
            
            // BUSCAR ROLE - Consulta segura (Prepared Statement)
            $sql = "SELECT p.idpersona,
                            p.identificacion,
                            p.nombres,
                            p.apellidos,
                            p.telefono,
                            p.email_user,
                            p.nit,
                            p.nombrefiscal,
                            p.direccionfiscal,
                            r.idrol,r.nombrerol,
                            p.status 
                     FROM persona p
                     INNER JOIN rol r
                     ON p.rolid = r.idrol
                     WHERE p.idpersona = ?";
            
            $arrData = array($this->intIdUsuario);
            $request = $this->select($sql, $arrData);
            
            // Nota: Aquí el controlador (o una función externa) maneja la sesión
            $_SESSION['userData'] = $request; 
            
            return $request;
        }

        /**
         * Busca usuario por email (para reseteo de contraseña).
         * @param string $strEmail Email del usuario.
         * @return array|false Datos del usuario o false.
         */
        public function getUserEmail(string $strEmail){
            $this->strUsuario = $strEmail;
            
            // Consulta segura (Prepared Statement)
            $sql = "SELECT idpersona,nombres,apellidos,status FROM persona WHERE 
                    email_user = ? AND status = 1 LIMIT 1";
            
            $arrData = array($this->strUsuario);
            $request = $this->select($sql, $arrData);
            return $request;
        }

        public function setTokenUser(int $idpersona, string $token){
            $this->intIdUsuario = $idpersona;
            $this->strToken = $token;
            
            // Consulta de UPDATE ya estaba segura (usa ?)
            $sql = "UPDATE persona SET token = ? WHERE idpersona = ?";
            $arrData = array($this->strToken, $this->intIdUsuario);
            $request = $this->update($sql,$arrData);
            return $request;
        }

        /**
         * Verifica la validez del token de recuperación.
         * @param string $email Email.
         * @param string $token Token de recuperación.
         * @return array|false Datos del usuario o false.
         */
        public function getUsuario(string $email, string $token){
            $this->strUsuario = $email;
            $this->strToken = $token;
            
            // Consulta segura (Prepared Statement)
            $sql = "SELECT idpersona FROM persona WHERE 
                    email_user = ? AND 
                    token = ? AND 
                    status = 1 LIMIT 1";
            
            $arrData = array($this->strUsuario, $this->strToken);
            $request = $this->select($sql, $arrData);
            return $request;
        }

        /**
         * Inserta el hash de la nueva contraseña.
         * @param int $idPersona ID de la persona.
         * @param string $password El hash de la contraseña (generado con password_hash() en el Controller).
         * @return bool Resultado de la operación.
         */
        public function insertPassword(int $idPersona, string $passwordHash){
            $this->intIdUsuario = $idPersona;
            $this->strPassword = $passwordHash; 
            
            // Usamos password_hash para guardar el hash seguro, y limpiamos el token.
            // Asumo que la columna para el hash es 'password_hash' y no 'password'
            // basado en las buenas prácticas y el contexto del proyecto.
            $sql = "UPDATE persona SET password_hash = ?, token = ? WHERE idpersona = ?";
            $arrData = array($this->strPassword,"", $this->intIdUsuario);
            $request = $this->update($sql,$arrData);
            return $request;
        }
    }
?>