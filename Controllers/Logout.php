<?php
class Logout
{
    public function __construct()
    {
        session_start();
        
        // ✅ REGISTRAR EN BITÁCORA ANTES DE CERRAR SESIÓN
        $this->registrarBitacoraLogout();
        
        session_unset();
        session_destroy();
        header('location: '.base_url().'/login');
        exit();
    }

    private function registrarBitacoraLogout()
    {
        try {
            if(isset($_SESSION['idUser'])) {
                // Los modelos se cargan automáticamente con el autoload
                $trabajadorModel = new TrabajadorModel();
                $bitacoraModel = new BitacoraModel();
                
                // Obtener información del trabajador
                $trabajador = $trabajadorModel->getTrabajadorByPersona($_SESSION['idUser']);
                
                if($trabajador && $trabajador['activo'] == 1) {
                    $bitacoraModel->insertRegistroAutomatico(
                        $trabajador['id'], 
                        'logout'
                    );
                }
            }
        } catch (Exception $e) {
            // Log del error pero no interrumpir el logout
            error_log("Error al registrar bitácora logout: " . $e->getMessage());
        }
    }
}
?>