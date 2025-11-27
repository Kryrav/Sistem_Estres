<!-- Modal -->
<div class="modal fade" id="modalFormReporte" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <h5 class="modal-title" id="titleModal">Nuevo Reporte</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="tile">
            <div class="tile-body">
              <form id="formReporte" name="formReporte">
                <input type="hidden" id="idReporte" name="idReporte" value="">
                
                <div class="form-group">
                  <label class="control-label">Departamento</label>
                  <select class="form-control" id="listDepartamento" name="listDepartamento" required="">
                    <option value="">Seleccionar Departamento</option>
                    <?php 
                      // Esto debería cargarse dinámicamente desde la base de datos
                      $departamentos = array(
                        array('id' => 1, 'nombre' => 'Gerencia de Sistemas'),
                        array('id' => 2, 'nombre' => 'Recursos Humanos'),
                        array('id' => 3, 'nombre' => 'Ventas y Marketing')
                      );
                      foreach($departamentos as $depto){
                        echo '<option value="'.$depto['id'].'">'.$depto['nombre'].'</option>';
                      }
                    ?>
                  </select>
                </div>

                <div class="form-group">
                  <label class="control-label">Nivel de Estrés (0-10)</label>
                  <input class="form-control" id="txtNivelEstres" name="txtNivelEstres" type="number" 
                         min="0" max="10" step="0.1" placeholder="Ej: 4.5" required="">
                  <small class="form-text text-muted">
                    <span class="text-success">0-3.5: Bajo</span> | 
                    <span class="text-warning">3.6-6.5: Medio</span> | 
                    <span class="text-danger">6.6-10: Alto</span>
                  </small>
                </div>

                <div class="form-group">
                  <label class="control-label">Observaciones y Análisis</label>
                  <textarea class="form-control" id="txtObservaciones" name="txtObservaciones" 
                            rows="4" placeholder="Análisis del nivel de estrés, causas identificadas, recomendaciones..." 
                            required=""></textarea>
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