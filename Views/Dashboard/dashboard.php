<?php headerAdmin($data); ?>
<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-tachometer-alt"></i> <?= $data['page_title'] ?></h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="<?= base_url(); ?>/dashboard">Dashboard</a></li>
    </ul>
  </div>

  <div class="row">
    <!-- Nivel de Estrés Promedio -->
    <div class="col-md-4">
      <div class="tile tile-stress">
        <div class="tile-header">
          <i class="fa fa-heartbeat fa-2x"></i>
          <h3>Nivel de Estrés Promedio</h3>
        </div>
        <canvas id="chartStress"></canvas>
      </div>
    </div>

    <!-- Carga Laboral -->
    <div class="col-md-4">
      <div class="tile tile-tasks">
        <div class="tile-header">
          <i class="fa fa-tasks fa-2x"></i>
          <h3>Tareas Pendientes / Completadas</h3>
        </div>
        <canvas id="chartTasks"></canvas>
      </div>
    </div>

    <!-- Bitácora Emocional -->
    <div class="col-md-4">
      <div class="tile tile-log">
        <div class="tile-header">
          <i class="fa fa-book fa-2x"></i>
          <h3>Últimas entradas Bitácora</h3>
        </div>
        <ul class="list-group list-group-flush">
          <?php if(!empty($data['bitacora'])): ?>
            <?php foreach($data['bitacora'] as $entry): ?>
              <li class="list-group-item">
                <i class="fa fa-user"></i> <?= $entry['nombres'] . " " . $entry['apellidos'] ?>: Estrés <?= $entry['nivel_stress_percibido'] ?>
              </li>
            <?php endforeach; ?>
          <?php else: ?>
            <li class="list-group-item">No hay entradas recientes</li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>

  <div class="row mt-4">
    <!-- Resumen Empleados -->
    <div class="col-md-6">
      <div class="tile tile-summary bg-primary text-white">
        <div class="tile-header">
          <i class="fa fa-users fa-2x"></i>
          <h3>Empleados Activos</h3>
        </div>
        <p class="tile-number"><?= $data['trabajadores'] ?></p>
      </div>
    </div>

    <!-- Resumen Departamentos -->
    <div class="col-md-6">
      <div class="tile tile-summary bg-success text-white">
        <div class="tile-header">
          <i class="fa fa-building fa-2x"></i>
          <h3>Departamentos</h3>
        </div>
        <p class="tile-number"><?= $data['departamentos'] ?></p>
      </div>
    </div>
  </div>
</main>

<!-- Scripts para gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {

  // Gráfico de Estrés Promedio por Departamento
  const ctxStress = document.getElementById('chartStress').getContext('2d');
  const chartStress = new Chart(ctxStress, {
    type: 'bar',
    data: {
      labels: <?= json_encode(array_column($data['estres_departamentos'], 'departamento')) ?>,
      datasets: [{
        label: 'Estrés Promedio',
        data: <?= json_encode(array_map(fn($d)=>floatval($d['promedio']), $data['estres_departamentos'])) ?>,
        backgroundColor: '#ff4d4d'
      }]
    },
    options: {
      responsive: true,
      scales: { y: { beginAtZero: true, max: 10 } }
    }
  });

  // Gráfico de Tareas
  const ctxTasks = document.getElementById('chartTasks').getContext('2d');
  const chartTasks = new Chart(ctxTasks, {
    type: 'doughnut',
    data: {
      labels: ['Completadas', 'Pendientes'],
      datasets: [{
        data: [<?= $data['tareas']['completadas'] ?>, <?= $data['tareas']['pendientes'] ?>],
        backgroundColor: ['#28a745', '#ffc107']
      }]
    },
    options: { responsive: true }
  });

});
</script>

<?php footerAdmin($data); ?>
