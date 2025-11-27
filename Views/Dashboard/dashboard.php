<?php 
headerAdmin($data); 
?>
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> <?= $data['page_title'] ?></h1>
            <p>Panel de Control - Sistema de Gestión del Estrés Laboral MSB</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>/dashboard">Dashboard</a></li>
        </ul>
    </div>

    <!-- Loading -->
    <div id="divLoading" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.8); z-index: 9999;">
        <div class="d-flex justify-content-center align-items-center h-100">
            <div class="text-center">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="sr-only">Cargando dashboard...</span>
                </div>
                <p class="mt-3">Cargando gráficos del dashboard...</p>
            </div>
        </div>
    </div>

    <!-- MÉTRICAS PRINCIPALES -->
    <div class="row">
        <div class="col-md-2 col-sm-6">
            <div class="widget-small primary coloured-icon">
                <i class="icon fa fa-users fa-3x"></i>
                <div class="info">
                    <h4>Trabajadores</h4>
                    <p><b><?= $data['trabajadores'] ?></b></p>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-sm-6">
            <div class="widget-small info coloured-icon">
                <i class="icon fa fa-building fa-3x"></i>
                <div class="info">
                    <h4>Departamentos</h4>
                    <p><b><?= $data['departamentos'] ?></b></p>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-sm-6">
            <div class="widget-small warning coloured-icon">
                <i class="icon fa fa-clipboard-list fa-3x"></i>
                <div class="info">
                    <h4>Encuestas Activas</h4>
                    <p><b><?= $data['encuestas_activas'] ?></b></p>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-sm-6">
            <div class="widget-small danger coloured-icon">
                <i class="icon fa fa-exclamation-triangle fa-3x"></i>
                <div class="info">
                    <h4>Alertas Pendientes</h4>
                    <p><b><?= $data['intervenciones_pendientes'] ?></b></p>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-sm-6">
            <div class="widget-small info coloured-icon">
                <i class="icon fa fa-tasks fa-3x"></i>
                <div class="info">
                    <h4>Tareas Pendientes</h4>
                    <p><b><?= $data['tareas']['pendientes'] ?></b></p>
                </div>
            </div>
        </div>

        <div class="col-md-2 col-sm-6">
            <div class="widget-small primary coloured-icon">
                <i class="icon fa fa-check-circle fa-3x"></i>
                <div class="info">
                    <h4>Respuestas 7d</h4>
                    <p><b><?= $data['respuestas_recientes'] ?></b></p>
                </div>
            </div>
        </div>
    </div>

    <!-- RESUMEN EJECUTIVO -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="tile bg-light">
                <div class="tile-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h4>Estrés Promedio General</h4>
                            <h2 class="<?= $data['estres_promedio_general'] > 6.5 ? 'text-danger' : ($data['estres_promedio_general'] > 3.5 ? 'text-warning' : 'text-success') ?>">
                                <?= $data['estres_promedio_general'] ?>
                            </h2>
                            <small>/10 puntos</small>
                        </div>
                        <div class="col-md-3">
                            <h4>Departamento Crítico</h4>
                            <h5 class="text-danger"><?= $data['depto_mayor_estres'] ?></h5>
                            <small>Nivel: <?= $data['nivel_mayor_estres'] ?></small>
                        </div>
                        <div class="col-md-3">
                            <h4>Tareas Completadas</h4>
                            <h2 class="text-success"><?= $data['porcentaje_completadas'] ?>%</h2>
                            <small><?= $data['tareas']['completadas'] ?> de <?= $data['tareas']['completadas'] + $data['tareas']['pendientes'] ?> tareas</small>
                        </div>
                        <div class="col-md-3">
                            <h4>Intervenciones Totales</h4>
                            <h2 class="text-info"><?= $data['intervenciones_total'] ?></h2>
                            <small>30 días</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- GRÁFICOS PRINCIPALES -->
    <div class="row">
        <div class="col-md-6">
            <div class="tile">
                <h3 class="tile-title">
                    <i class="fa fa-chart-bar"></i> Estrés por Departamento
                    <small class="text-muted float-right">Últimos 7 días</small>
                </h3>
                <div class="embed-responsive embed-responsive-16by9">
                    <canvas class="embed-responsive-item" id="chartStressDeptos"></canvas>
                </div>
                <div id="errorStressDeptos" class="alert alert-warning mt-2" style="display:none;"></div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="tile">
                <h3 class="tile-title">
                    <i class="fa fa-chart-line"></i> Tendencia de Estrés
                    <small class="text-muted float-right">Últimos 30 días</small>
                </h3>
                <div class="embed-responsive embed-responsive-16by9">
                    <canvas class="embed-responsive-item" id="chartTendenciaEstres"></canvas>
                </div>
                <div id="errorTendencia" class="alert alert-warning mt-2" style="display:none;"></div>
            </div>
        </div>
    </div>

    <!-- GRÁFICOS SECUNDARIOS -->
    <div class="row">
        <div class="col-md-4">
            <div class="tile">
                <h3 class="tile-title"><i class="fa fa-chart-pie"></i> Distribución de Estrés</h3>
                <div class="embed-responsive embed-responsive-16by9">
                    <canvas class="embed-responsive-item" id="chartDistribucionEstres"></canvas>
                </div>
                <div id="errorDistribucion" class="alert alert-warning mt-2" style="display:none;"></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="tile">
                <h3 class="tile-title"><i class="fa fa-tasks"></i> Estado de Tareas</h3>
                <div class="embed-responsive embed-responsive-16by9">
                    <canvas class="embed-responsive-item" id="chartTareas"></canvas>
                </div>
                <div id="errorTareas" class="alert alert-warning mt-2" style="display:none;"></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="tile">
                <h3 class="tile-title"><i class="fa fa-life-ring"></i> Intervenciones por Tipo</h3>
                <div class="embed-responsive embed-responsive-16by9">
                    <canvas class="embed-responsive-item" id="chartIntervenciones"></canvas>
                </div>
                <div id="errorIntervenciones" class="alert alert-warning mt-2" style="display:none;"></div>
            </div>
        </div>
    </div>

    <!-- ALERTAS Y BITÁCORA -->
    <div class="row">
        <div class="col-md-6">
            <div class="tile">
                <h3 class="tile-title">
                    <i class="fa fa-exclamation-circle text-danger"></i> Alertas Críticas Pendientes
                    <span class="badge badge-danger float-right"><?= count($data['alertas_criticas']) ?></span>
                </h3>
                <div class="list-group" style="max-height: 400px; overflow-y: auto;">
                    <?php if(!empty($data['alertas_criticas'])): ?>
                        <?php foreach($data['alertas_criticas'] as $alerta): ?>
                            <a href="<?= base_url() ?>/intervenciones" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">
                                        <?php 
                                            $badge_class = '';
                                            switch($alerta['tipo_alerta']){
                                                case 'alerta_burnout': 
                                                    $badge_class = 'danger';
                                                    break;
                                                case 'redistribucion_carga':
                                                    $badge_class = 'warning';
                                                    break;
                                                case 'descanso_sugerido':
                                                    $badge_class = 'info';
                                                    break;
                                                case 'felicitacion':
                                                    $badge_class = 'success';
                                                    break;
                                                default:
                                                    $badge_class = 'secondary';
                                            }
                                        ?>
                                        <span class="badge badge-<?= $badge_class ?>"><?= strtoupper(str_replace('_', ' ', $alerta['tipo_alerta'])) ?></span>
                                        <?= $alerta['nombres'] . ' ' . $alerta['apellidos'] ?>
                                        <?php if($alerta['departamento']): ?>
                                            <small class="text-muted">(<?= $alerta['departamento'] ?>)</small>
                                        <?php endif; ?>
                                    </h6>
                                    <small><?= date('d/m H:i', strtotime($alerta['fecha_generada'])) ?></small>
                                </div>
                                <p class="mb-1 text-truncate"><?= $alerta['mensaje'] ?></p>
                                <small class="text-muted">Hacer clic para gestionar</small>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="list-group-item text-center text-success">
                            <i class="fa fa-check-circle fa-2x mb-2"></i>
                            <p class="mb-1">No hay alertas críticas pendientes</p>
                            <small>El sistema está funcionando correctamente</small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="tile">
                <h3 class="tile-title">
                    <i class="fa fa-book"></i> Últimas Entradas - Bitácora Emocional
                    <span class="badge badge-info float-right"><?= count($data['bitacora']) ?></span>
                </h3>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th>Trabajador</th>
                                <th>Estrés</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($data['bitacora'])): ?>
                                <?php foreach($data['bitacora'] as $entry): ?>
                                    <tr>
                                        <td>
                                            <strong><?= $entry['nombres'] . ' ' . $entry['apellidos'] ?></strong>
                                        </td>
                                        <td>
                                            <?php 
                                                $nivel = $entry['nivel_stress_percibido'];
                                                $badge_class = $nivel <= 3 ? 'success' : ($nivel <= 6 ? 'warning' : 'danger');
                                            ?>
                                            <span class="badge badge-<?= $badge_class ?>"><?= $nivel ?>/10</span>
                                        </td>
                                        <td>
                                            <?php 
                                                $sentimiento = $entry['sentimiento_predominante'] ?? 'otro';
                                                $icon_class = '';
                                                $text_class = '';
                                                switch($sentimiento){
                                                    case 'motivado': 
                                                        $icon_class = 'fa-smile';
                                                        $text_class = 'text-success';
                                                        break;
                                                    case 'satisfecho': 
                                                        $icon_class = 'fa-smile-beam';
                                                        $text_class = 'text-success';
                                                        break;
                                                    case 'cansado': 
                                                        $icon_class = 'fa-tired';
                                                        $text_class = 'text-warning';
                                                        break;
                                                    case 'frustrado': 
                                                        $icon_class = 'fa-angry';
                                                        $text_class = 'text-danger';
                                                        break;
                                                    case 'ansioso': 
                                                        $icon_class = 'fa-flushed';
                                                        $text_class = 'text-danger';
                                                        break;
                                                    default: 
                                                        $icon_class = 'fa-meh';
                                                        $text_class = 'text-secondary';
                                                }
                                            ?>
                                            <i class="fas <?= $icon_class ?> <?= $text_class ?>" title="<?= ucfirst($sentimiento) ?>"></i>
                                            <small class="<?= $text_class ?>"><?= ucfirst($sentimiento) ?></small>
                                        </td>
                                        <td>
                                            <small><?= date('d/m H:i', strtotime($entry['fecha'] . ' ' . $entry['hora'])) ?></small>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        <i class="fa fa-inbox fa-2x mb-2"></i>
                                        <p>No hay entradas recientes en la bitácora</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Datos PHP convertidos a JavaScript - CORREGIDO
const dashboardData = {
    estresDepartamentos: {
        labels: <?= json_encode(array_column($data['estres_departamentos'], 'departamento')) ?>,
        values: <?= json_encode(array_map(fn($d)=>floatval($d['promedio']), $data['estres_departamentos'])) ?>,
        thresholds: <?= json_encode(array_map(fn($d)=>floatval($d['umbral_alerta_stress']), $data['estres_departamentos'])) ?>
    },
    tendenciasEstres: {
        dates: <?= json_encode(array_map(fn($t)=>date('d/m', strtotime($t['fecha'])), $data['tendencias_estres'])) ?>,
        levels: <?= json_encode(array_map(fn($t)=>floatval($t['promedio_diario']), $data['tendencias_estres'])) ?>
    },
    distribucionEstres: {
        bajo: <?= $data['distribucion_estres']['bajo'] ?? 0 ?>,
        medio: <?= $data['distribucion_estres']['medio'] ?? 0 ?>,
        alto: <?= $data['distribucion_estres']['alto'] ?? 0 ?>
    },
    tareas: {
        completadas: <?= $data['tareas']['completadas'] ?>,
        pendientes: <?= $data['tareas']['pendientes'] ?>
    },
    intervenciones: {
        descanso: <?= $data['intervenciones_descanso'] ?? 0 ?>,
        redistribucion: <?= $data['intervenciones_redistribucion'] ?? 0 ?>,
        burnout: <?= $data['intervenciones_burnout'] ?? 0 ?>,
        felicitacion: <?= $data['intervenciones_felicitacion'] ?? 0 ?>
    }
};

console.log('Datos del dashboard cargados:', dashboardData);
</script>

<script>
// Inicialización corregida
document.addEventListener("DOMContentLoaded", function() {
    console.log("DOM cargado - Verificando Chart.js...");
    
    // Mostrar loading
    document.getElementById('divLoading').style.display = 'block';
    
    // Verificar si Chart.js está disponible
    if (typeof Chart === 'undefined') {
        console.error('❌ Chart.js NO está disponible');
        showAllChartErrors('Error: Chart.js no se pudo cargar. Recarga la página.');
        document.getElementById('divLoading').style.display = 'none';
        return;
    }
    
    console.log('✅ Chart.js está disponible, versión:', Chart.version);
    
    // Esperar un poco para asegurar que todo esté listo
    setTimeout(() => {
        if (typeof initDashboardCharts === 'function') {
            console.log('✅ Inicializando gráficos...');
            initDashboardCharts();
        } else {
            console.error('❌ La función initDashboardCharts no está disponible');
            showAllChartErrors('Error: No se pudo inicializar los gráficos. Verifica el archivo functions_dashboard.js');
        }
        document.getElementById('divLoading').style.display = 'none';
    }, 100);
});

function showAllChartErrors(message) {
    const errorContainers = [
        'errorStressDeptos', 'errorTendencia', 'errorDistribucion', 
        'errorTareas', 'errorIntervenciones'
    ];
    
    errorContainers.forEach(containerId => {
        const container = document.getElementById(containerId);
        if (container) {
            container.style.display = 'block';
            container.innerHTML = `<i class="fa fa-exclamation-triangle"></i> ${message}`;
        }
    });
}

function updateDashboardData() {
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Actualizando...';
    btn.disabled = true;

    setTimeout(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        document.getElementById('lastUpdate').textContent = 'Última actualización: ' + new Date().toLocaleTimeString();
        swal("Actualizado", "Los datos del dashboard han sido actualizados", "success");
    }, 1000);
}
</script>

<?php 
footerAdmin($data); 
?>