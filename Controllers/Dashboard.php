<?php
class Dashboard extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        session_regenerate_id(true);
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url() . '/login');
        }
        getPermisos(1); // Permiso dashboard

        // Inicializar el modelo
        $this->model = new DashboardModel();
    }

    public function dashboard()
    {
        $data['page_id'] = 2;
        $data['page_tag'] = "Dashboard - Sistema MSB";
        $data['page_title'] = "Dashboard - Sistema MSB";
        $data['page_name'] = "dashboard";
        $data['page_functions_js'] = "functions_dashboard.js";

        // Datos desde el modelo
        $data['trabajadores'] = $this->model->getTrabajadoresActivos();
        $data['departamentos'] = $this->model->getDepartamentos();
        $data['tareas'] = $this->model->getTareasResumen();
        $data['estres_departamentos'] = $this->model->getEstresDepartamentos();
        $data['bitacora'] = $this->model->getUltimasBitacoras();

        $this->views->getView($this, "dashboard", $data);
    }
}
?>
