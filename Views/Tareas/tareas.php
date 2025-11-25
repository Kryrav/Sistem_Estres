<?php headerAdmin($data); ?>
<main class="app-content">

  <div class="app-title">
    <div>
      <h1><i class="fas fa-tasks"></i> <?= $data['page_tag']; ?></h1>
      <p>Gestión de tareas y carga laboral</p>
    </div>
  </div>

  <button class="btn btn-success mb-3" onclick="openModalTarea()"><i class="fas fa-plus-circle"></i> Nueva Tarea</button>

  <div class="table-responsive">
      <table id="tableTareas" class="table table-hover table-bordered" style="width:100%">
          <thead>
              <tr>
                  <th>ID</th>
                  <th>Título</th>
                  <th>Trabajador</th>
                  <th>Tipo</th>
                  <th>Minutos Estimados</th>
                  <th>Estado</th>
                  <th>Opciones</th>
              </tr>
          </thead>
          <tbody></tbody>
      </table>
  </div>
</main>

<!-- Modal -->
<div class="modal fade" id="modalTarea" tabindex="-1" role="dialog" aria-labelledby="modalTareaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="formTarea" name="formTarea">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTareaLabel">Nueva Tarea</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="idTarea" name="idTarea" value="">
          <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" required>
          </div>
          <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
          </div>
          <div class="form-group">
            <label for="trabajador_id">Trabajador</label>
            <select class="form-control" id="trabajador_id" name="trabajador_id" required>
              <option value="">Seleccione...</option>
              <?php foreach($data['trabajadores'] as $trab): ?>
                <option value="<?= $trab['id'] ?>"><?= $trab['nombre'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="tipo_tarea_id">Tipo de Tarea</label>
            <select class="form-control" id="tipo_tarea_id" name="tipo_tarea_id" required>
              <option value="">Seleccione...</option>
              <?php foreach($data['tipos_tarea'] as $tipo): ?>
                <option value="<?= $tipo['id'] ?>"><?= $tipo['nombre'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="minutos_estimados">Minutos Estimados</label>
            <input type="number" class="form-control" id="minutos_estimados" name="minutos_estimados" required>
          </div>
          <div class="form-group">
            <label for="estado">Estado</label>
            <select class="form-control" id="estado" name="estado" required>
              <option value="backlog">Backlog</option>
              <option value="listo">Listo</option>
              <option value="en_progreso">En progreso</option>
              <option value="bloqueado">Bloqueado</option>
              <option value="revision">Revisión</option>
              <option value="terminado">Terminado</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php footerAdmin($data); ?>

<script>
let tableTareas;

document.addEventListener("DOMContentLoaded", function() {

    tableTareas = $('#tableTareas').DataTable({
        "ajax": {
            "url": "<?= base_url(); ?>/Tareas/getTareas",
            "dataSrc": "data"
        },
        "columns": [
            { "data": "id" },
            { "data": "titulo" },
            { "data": "trabajador" },
            { "data": "tipo" },
            { "data": "minutos_estimados" },
            { "data": "estado" },
            { "data": "options" }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        },
        "responsive": true,
        "scrollX": true
    });

    // Submit del formulario
    $('#formTarea').submit(function(e){
        e.preventDefault();
        const formData = new FormData(this);
        fetch("<?= base_url(); ?>/Tareas/setTarea", {
            method: "POST",
            body: formData
        }).then(res => res.json()).then(data => {
            if(data.status){
                $('#modalTarea').modal('hide');
                tableTareas.ajax.reload(null, false);
            } else {
                alert(data.msg);
            }
        });
    });

});

function openModalTarea(){
    document.getElementById("formTarea").reset();
    document.getElementById("idTarea").value = "";
    $('#modalTareaLabel').text('Nueva Tarea');
    $('#modalTarea').modal('show');
}

function editTarea(id){
    fetch("<?= base_url(); ?>/Tareas/getTarea/"+id)
    .then(res => res.json())
    .then(data => {
        if(data.status){
            const tarea = data.data;
            $('#idTarea').val(tarea.id);
            $('#titulo').val(tarea.titulo);
            $('#descripcion').val(tarea.descripcion);
            $('#trabajador_id').val(tarea.trabajador_id);
            $('#tipo_tarea_id').val(tarea.tipo_tarea_id);
            $('#minutos_estimados').val(tarea.minutos_estimados);
            $('#estado').val(tarea.estado);
            $('#modalTareaLabel').text('Editar Tarea');
            $('#modalTarea').modal('show');
        }
    });
}

function deleteTarea(id){
    if(confirm("¿Está seguro de eliminar esta tarea?")){
        fetch("<?= base_url(); ?>/Tareas/delTarea/"+id)
        .then(res => res.json())
        .then(data => {
            if(data.status){
                tableTareas.ajax.reload(null, false);
            } else {
                alert(data.msg);
            }
        });
    }
}
</script>
