<!-- Modal -->
<div class="modal fade" id="modalFormIntervencion" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <h5 class="modal-title" id="titleModal">Nueva Intervención</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="tile">
            <div class="tile-body">
              <form id="formIntervencion" name="formIntervencion">
                <input type="hidden" id="idIntervencion" name="idIntervencion" value="">
                
                <div class="form-group">
                  <label class="control-label">Trabajador</label>
                  <select class="form-control" id="listTrabajador" name="listTrabajador" required="">
                    <option value="">Seleccionar Trabajador</option>
                    <?php 
                      // Esto debería cargarse dinámicamente desde la base de datos
                      $trabajadores = array(
                        array('id' => 1, 'nombre' => 'Rene Vasquez'),
                        array('id' => 2, 'nombre' => 'Miriam Montecinos'),
                        array('id' => 3, 'nombre' => 'Carlos Mendoza'),
                        array('id' => 4, 'nombre' => 'Ana Garcia'),
                        array('id' => 5, 'nombre' => 'Luis Fernandez')
                      );
                      foreach($trabajadores as $trab){
                        echo '<option value="'.$trab['id'].'">'.$trab['nombre'].'</option>';
                      }
                    ?>
                  </select>
                </div>

                <div class="form-group">
                  <label class="control-label">Tipo de Alerta</label>
                  <select class="form-control" id="listTipoAlerta" name="listTipoAlerta" required="">
                    <option value="">Seleccionar Tipo</option>
                    <option value="descanso_sugerido">Descanso Sugerido</option>
                    <option value="redistribucion_carga">Redistribución de Carga</option>
                    <option value="alerta_burnout">Alerta Burnout</option>
                    <option value="felicitacion">Felicitación</option>
                  </select>
                </div>

                <div class="form-group">
                  <label class="control-label">Mensaje de Intervención</label>
                  <textarea class="form-control" id="txtMensaje" name="txtMensaje" 
                            rows="4" placeholder="Descripción detallada de la intervención recomendada..." 
                            required=""></textarea>
                  <small class="form-text text-muted">
                    Incluya recomendaciones específicas y acciones a tomar.
                  </small>
                </div>

                <div class="form-group">
                  <label class="control-label">Estado</label>
                  <select class="form-control" id="listEstado" name="listEstado" required="">
                    <option value="pendiente">Pendiente</option>
                    <option value="leida">Leída</option>
                    <option value="aplicada">Aplicada</option>
                    <option value="ignorada">Ignorada</option>
                  </select>
                </div>

                <div class="tile-footer">
                  <button id="btnActionForm" class="btn btn-primary" type="submit">
                    <i class="fa fa-fw fa-lg fa-check-circle"></i>
                    <span id="btnText">Guardar</span>
                  </button>
                  &nbsp;&nbsp;&nbsp;
                  <a class="btn btn-secondary" href="#" data-dismiss="modal">
                    <i class="fa fa-fw fa-lg fa-times-circle"></i>Cancelar
                  </a>
                </div>
              </form>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>