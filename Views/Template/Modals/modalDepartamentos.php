<div class="modal fade" id="modalFormDepartamento" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" >
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <h5 class="modal-title" id="titleModal">Nuevo Departamento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formDepartamento" name="formDepartamento" class="form-horizontal">
          <input type="hidden" id="idDepartamento" name="idDepartamento" value="">
          <p class="text-primary">Todos los campos son obligatorios.</p>

          <div class="form-group">
            <label for="txtNombre">Nombre del Departamento</label>
            <input type="text" class="form-control" id="txtNombre" name="txtNombre" required="">
          </div>
          <div class="form-group">
            <label for="txtDescripcion">Descripción</label>
            <textarea class="form-control" id="txtDescripcion" name="txtDescripcion" rows="2" required=""></textarea>
          </div>
          <div class="form-group">
            <label for="intUmbralAlerta">Umbral de Alerta de Estrés (1-10)</label>
            <input type="number" class="form-control" id="intUmbralAlerta" name="intUmbralAlerta" min="1" max="10" required="">
            <small class="form-text text-muted">Nivel de estrés a partir del cual el sistema sugiere intervención. (Defecto: 7)</small>
          </div>
          
          <div class="tile-footer">
            <button id="btnActionForm" class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Guardar</span></button>&nbsp;&nbsp;&nbsp;
            <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cerrar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>