<?php 
    headerAdmin($data); 
    getModal('modalReportes',$data);
?>
    <div id="contentAjax"></div> 
    <main class="app-content">
      <div class="app-title">
        <div>
            <h1><i class="fas fa-chart-line"></i> <?= $data['page_title'] ?>
              <?php if($_SESSION['permisosMod']['w']){ ?>
                <button class="btn btn-primary" type="button" onclick="openModal();" ><i class="fas fa-plus-circle"></i> Nuevo Reporte</button>
              <?php } ?> 
            </h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="<?= base_url(); ?>/analiticas"><?= $data['page_title'] ?></a></li>
        </ul>
      </div>

      <!-- Dashboard de Métricas en Tiempo Real -->
      <div class="row">
        <div class="col-md-3">
          <div class="widget-small primary coloured-icon">
            <i class="icon fas fa-heartbeat fa-3x"></i>
            <div class="info">
              <h4>Nivel Estrés Promedio</h4>
              <p><b id="avgStress">Cargando...</b></p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="widget-small info coloured-icon">
            <i class="icon fas fa-exclamation-triangle fa-3x"></i>
            <div class="info">
              <h4>Alertas Activas</h4>
              <p><b id="activeAlerts">Cargando...</b></p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="widget-small warning coloured-icon">
            <i class="icon fas fa-clipboard-check fa-3x"></i>
            <div class="info">
              <h4>Encuestas Completadas</h4>
              <p><b id="completedSurveys">Cargando...</b></p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="widget-small danger coloured-icon">
            <i class="icon fas fa-users fa-3x"></i>
            <div class="info">
              <h4>Departamentos Críticos</h4>
              <p><b id="criticalDepts">Cargando...</b></p>
            </div>
          </div>
        </div>
      </div>

      <!-- Gráficos y Visualizaciones -->
      <div class="row">
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Estrés por Departamento</h3>
            <div class="embed-responsive embed-responsive-16by9">
              <canvas class="embed-responsive-item" id="barChartDemo"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Tendencia Semanal</h3>
            <div class="embed-responsive embed-responsive-16by9">
              <canvas class="embed-responsive-item" id="lineChartDemo"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabla de Reportes -->
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body">
              <div class="table-responsive">
                <table class="table table-hover table-bordered" id="tableReportes">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Departamento</th>
                      <th>Nivel Estrés</th>
                      <th>Observaciones</th>
                      <th>Generado Por</th>
                      <th>Fecha</th>
                      <th>Acciones</th>
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
    </main>
<?php footerAdmin($data); ?>