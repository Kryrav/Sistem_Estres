<?php 
    headerAdmin($data); 
    getModal('modalIndicadores',$data);
?>
    <div id="contentAjax"></div> 
    <main class="app-content">
      <div class="app-title">
        <div>
            <h1><i class="fas fa-heartbeat"></i> <?= $data['page_title'] ?>
              <?php if($_SESSION['permisosMod']['w']){ ?>
                <button class="btn btn-primary" type="button" onclick="openModal();" ><i class="fas fa-plus-circle"></i> Nuevo Indicador</button>
              <?php } ?> 
            </h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="<?= base_url(); ?>/indicadores"><?= $data['page_title'] ?></a></li>
        </ul>
      </div>

      <!-- Dashboard de Indicadores -->
      <div class="row">
        <div class="col-md-3">
          <div class="widget-small primary coloured-icon">
            <i class="icon fas fa-chart-line fa-3x"></i>
            <div class="info">
              <h4>Total Indicadores</h4>
              <p><b id="totalIndicadores">0</b></p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="widget-small info coloured-icon">
            <i class="icon fas fa-users fa-3x"></i>
            <div class="info">
              <h4>Trabajadores Monitoreados</h4>
              <p><b id="totalTrabajadores">0</b></p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="widget-small warning coloured-icon">
            <i class="icon fas fa-building fa-3x"></i>
            <div class="info">
              <h4>Departamentos</h4>
              <p><b id="totalDepartamentos">0</b></p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="widget-small danger coloured-icon">
            <i class="icon fas fa-exclamation-triangle fa-3x"></i>
            <div class="info">
              <h4>Estrés Promedio</h4>
              <p><b id="promedioEstres">0.00</b></p>
            </div>
          </div>
        </div>
      </div>

      <!-- Gráficos de Análisis -->
      <div class="row">
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Tendencia de Estrés (30 días)</h3>
            <div class="embed-responsive embed-responsive-16by9">
              <canvas class="embed-responsive-item" id="chartTendenciaIndicadores"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Distribución por Categoría</h3>
            <div class="embed-responsive embed-responsive-16by9">
              <canvas class="embed-responsive-item" id="chartDistribucionCategoria"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabla de Indicadores -->
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body">
              <div class="table-responsive">
                <table class="table table-hover table-bordered" id="tableIndicadores">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Trabajador</th>
                      <th>Departamento</th>
                      <th>Nivel Estrés</th>
                      <th>Categoría</th>
                      <th>Método Cálculo</th>
                      <th>Fecha Cálculo</th>
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