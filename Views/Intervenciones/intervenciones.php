<?php 
    headerAdmin($data); 
    getModal('modalIntervenciones',$data);
?>
    <div id="contentAjax"></div> 
    <main class="app-content">
      <div class="app-title">
        <div>
            <h1><i class="fas fa-life-ring"></i> <?= $data['page_title'] ?>
              <?php if($_SESSION['permisosMod']['w']){ ?>
                <button class="btn btn-primary" type="button" onclick="openModal();" ><i class="fas fa-plus-circle"></i> Nueva Intervención</button>
              <?php } ?> 
            </h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="<?= base_url(); ?>/intervenciones"><?= $data['page_title'] ?></a></li>
        </ul>
      </div>

      <!-- Dashboard de Intervenciones -->
      <div class="row">
        <div class="col-md-3">
          <div class="widget-small primary coloured-icon">
            <i class="icon fas fa-clock fa-3x"></i>
            <div class="info">
              <h4>Pendientes</h4>
              <p><b id="totalPendientes">0</b></p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="widget-small info coloured-icon">
            <i class="icon fas fa-check-circle fa-3x"></i>
            <div class="info">
              <h4>Aplicadas</h4>
              <p><b id="totalAplicadas">0</b></p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="widget-small warning coloured-icon">
            <i class="icon fas fa-exclamation-triangle fa-3x"></i>
            <div class="info">
              <h4>Alertas Burnout</h4>
              <p><b id="totalBurnout">0</b></p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="widget-small danger coloured-icon">
            <i class="icon fas fa-balance-scale fa-3x"></i>
            <div class="info">
              <h4>Redistribuciones</h4>
              <p><b id="totalRedistribuciones">0</b></p>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabla de Intervenciones -->
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body">
              <div class="table-responsive">
                <table class="table table-hover table-bordered" id="tableIntervenciones">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Trabajador</th>
                      <th>Tipo Alerta</th>
                      <th>Mensaje</th>
                      <th>Estado</th>
                      <th>Fecha Generada</th>
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