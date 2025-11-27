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
        getPermisos(1);

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

        // ========== DATOS PRINCIPALES ==========
        $data['trabajadores'] = $this->model->getTrabajadoresActivos();
        $data['departamentos'] = $this->model->getDepartamentos();
        $data['tareas'] = $this->model->getTareasResumen();
        $data['encuestas_activas'] = $this->model->getEncuestasActivas();
        $data['respuestas_recientes'] = $this->model->getRespuestasRecientes();

        // ========== DATOS PARA GRÁFICOS ==========
        $data['estres_departamentos'] = $this->model->getEstresDepartamentos();
        $data['tendencias_estres'] = $this->model->getTendenciasEstres();
        $data['bitacora'] = $this->model->getUltimasBitacoras();
        $data['alertas_criticas'] = $this->model->getAlertasCriticas();

        // ========== DATOS DE INTERVENCIONES ==========
        $metricasIntervenciones = $this->model->getMetricasIntervenciones();
        $data['intervenciones_total'] = $metricasIntervenciones['total'];
        $data['intervenciones_pendientes'] = $metricasIntervenciones['pendientes'];
        $data['intervenciones_aplicadas'] = $metricasIntervenciones['aplicadas'];

        // ========== DISTRIBUCIÓN DE ESTRÉS ==========
        $distribucionData = $this->model->getDistribucionEstres();
        $data['distribucion_estres'] = ['bajo' => 0, 'medio' => 0, 'alto' => 0];
        
        foreach ($distribucionData as $dist) {
            $data['distribucion_estres'][$dist['categoria']] = $dist['total'];
        }

        // ========== INTERVENCIONES POR TIPO ==========
        $estadisticasIntervenciones = $this->model->getEstadisticasIntervenciones();
        $data['intervenciones_descanso'] = 0;
        $data['intervenciones_redistribucion'] = 0;
        $data['intervenciones_burnout'] = 0;
        $data['intervenciones_felicitacion'] = 0;

        foreach ($estadisticasIntervenciones as $estad) {
            switch ($estad['tipo_alerta']) {
                case 'descanso_sugerido':
                    $data['intervenciones_descanso'] = $estad['total'];
                    break;
                case 'redistribucion_carga':
                    $data['intervenciones_redistribucion'] = $estad['total'];
                    break;
                case 'alerta_burnout':
                    $data['intervenciones_burnout'] = $estad['total'];
                    break;
                case 'felicitacion':
                    $data['intervenciones_felicitacion'] = $estad['total'];
                    break;
            }
        }

        // ========== CÁLCULOS ADICIONALES ==========
        // Estrés promedio general
        $data['estres_promedio_general'] = 0;
        $totalMuestras = 0;
        $sumaEstres = 0;
        
        foreach ($data['estres_departamentos'] as $depto) {
            $sumaEstres += $depto['promedio'] * $depto['muestras'];
            $totalMuestras += $depto['muestras'];
        }
        
        if ($totalMuestras > 0) {
            $data['estres_promedio_general'] = round($sumaEstres / $totalMuestras, 2);
        }

        // Departamento con mayor estrés
        $data['depto_mayor_estres'] = 'N/A';
        $data['nivel_mayor_estres'] = 0;
        
        if (!empty($data['estres_departamentos'])) {
            $mayorEstres = $data['estres_departamentos'][0];
            $data['depto_mayor_estres'] = $mayorEstres['departamento'];
            $data['nivel_mayor_estres'] = round($mayorEstres['promedio'], 2);
        }

        // Porcentaje de tareas completadas
        $totalTareas = $data['tareas']['completadas'] + $data['tareas']['pendientes'];
        $data['porcentaje_completadas'] = $totalTareas > 0 ? 
            round(($data['tareas']['completadas'] / $totalTareas) * 100, 1) : 0;

        $this->views->getView($this, "dashboard", $data);
    }

    // Método para AJAX - datos en tiempo real
    public function getEstadisticasTiempoReal()
    {
        if ($_SESSION['permisosMod']['r']) {
            $datos = $this->model->getDatosTiempoReal();
            $datos['ultima_actualizacion'] = date('Y-m-d H:i:s');
            echo json_encode($datos, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
}
?>