<?php 
    headerAdmin($data); 
    getModal('modalBitacora',$data);
?>
<div id="contentAjax"></div> 
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fas fa-book-medical"></i> <?= $data['page_title'] ?>
                <?php if($_SESSION['permisosMod']['w']){ ?>
                    <button class="btn btn-primary" type="button" onclick="openModal();" ><i class="fas fa-plus-circle"></i> Nuevo Registro</button>
                    <button class="btn btn-danger ml-2" type="button" onclick="fntRegistroPanico();" ><i class="fas fa-exclamation-triangle"></i> Botón Pánico</button>
                <?php } ?> 
            </h1>
            <p class="text-muted">Sistema de monitoreo de bienestar emocional y estrés laboral</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>/bitacora"><?= $data['page_title'] ?></a></li>
        </ul>
    </div>

    <!-- ==================== -->
    <!-- MÉTRICAS DE BIENESTAR -->
    <!-- ==================== -->
    
    <!-- Tarjetas de Métricas Rápidas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="widget-small primary coloured-icon">
                <i class="icon fas fa-heartbeat fa-3x"></i>
                <div class="info">
                    <h4>Stress Promedio</h4>
                    <p><b id="stressPromedio">--</b>/10</p>
                    <small class="text-muted">Últimos 30 días</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="widget-small info coloured-icon">
                <i class="icon fas fa-battery-three-quarters fa-3x"></i>
                <div class="info">
                    <h4>Energía Promedio</h4>
                    <p><b id="energiaPromedio">--</b>/10</p>
                    <small class="text-muted">Últimos 30 días</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="widget-small warning coloured-icon">
                <i class="icon fas fa-clipboard-list fa-3x"></i>
                <div class="info">
                    <h4>Registros Totales</h4>
                    <p><b id="registrosTotales">--</b></p>
                    <small class="text-muted">Total de registros</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="widget-small danger coloured-icon">
                <i class="icon fas fa-exclamation-triangle fa-3x"></i>
                <div class="info">
                    <h4>Alertas Activas</h4>
                    <p><b id="totalAlertas">--</b></p>
                    <small class="text-muted">Pánico + Stress Alto</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjetas de Estado Emocional -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h5 class="card-title">😊 Estado Emocional</h5>
                    <h3 id="sentimientoPredominante" class="text-success">--</h3>
                    <p class="card-text text-muted">Sentimiento más frecuente</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-info">
                <div class="card-body text-center">
                    <h5 class="card-title">📊 Días Activos</h5>
                    <h3 id="diasActivos" class="text-info">--</h3>
                    <p class="card-text text-muted">Días con registro</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <h5 class="card-title">⚡ Rango Stress</h5>
                    <h3 id="rangoStress" class="text-warning">--</h3>
                    <p class="card-text text-muted">Mín: <span id="stressMinimo">--</span> / Máx: <span id="stressMaximo">--</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerta de Análisis de Bienestar -->
    <div id="alertaAnalisisContainer" class="mb-4">
        <!-- La alerta se generará dinámicamente aquí -->
    </div>

    <!-- ==================== -->
    <!-- TABLA DE REGISTROS -->
    <!-- ==================== -->
    
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-header d-flex justify-content-between align-items-center mb-3">
                    <h3 class="tile-title">📝 Historial de Registros Emocionales</h3>
                    <div class="btn-group">
                        <button class="btn btn-outline-secondary btn-sm" onclick="cargarMetricasPersonales()" title="Actualizar métricas">
                            <i class="fas fa-sync-alt"></i> Actualizar
                        </button>
                    </div>
                </div>
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="tableBitacora">
                            <thead class="thead-dark">
                                <tr>
                                    <th>📅 Fecha</th>
                                    <th>🕒 Hora</th>
                                    <th>🎯 Tipo</th>
                                    <th>😰 Nivel Stress</th>
                                    <th>⚡ Nivel Energía</th>
                                    <th>😊 Sentimiento</th>
                                    <th>📋 Tarea Relacionada</th>
                                    <th>🔧 Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== -->
    <!-- RESUMEN RÁPIDO -->
    <!-- ==================== -->
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line"></i> Resumen de Bienestar
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>📈 Indicadores Clave:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success"></i> <strong>Stress Promedio:</strong> <span id="resumenStress">--</span>/10</li>
                                <li><i class="fas fa-check text-success"></i> <strong>Energía Promedio:</strong> <span id="resumenEnergia">--</span>/10</li>
                                <li><i class="fas fa-check text-success"></i> <strong>Registros:</strong> <span id="resumenRegistros">--</span> totales</li>
                                <li><i class="fas fa-exclamation-triangle text-warning"></i> <strong>Alertas:</strong> <span id="resumenAlertas">--</span> activas</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>🎯 Recomendaciones:</h6>
                            <div id="recomendacionesContainer">
                                <p class="text-muted">Cargando análisis de bienestar...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php footerAdmin($data); ?>