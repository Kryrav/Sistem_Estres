<!-- Modal Tarea -->
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
