<!-- Modal -->
<div class="modal fade" id="modalFormIndicador" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <h5 class="modal-title" id="titleModal">Nuevo Indicador</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="tile">
            <div class="tile-body">
              <form id="formIndicador" name="formIndicador">
                <input type="hidden" id="idIndicador" name="idIndicador" value="">
                
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
                  <label class="control-label">Departamento</label>
                  <select class="form-control" id="listDepartamento" name="listDepartamento" required="">
                    <option value="">Seleccionar Departamento</option>
                    <?php 
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
                  <label class="control-label">Categoría</label>
                  <select class="form-control" id="listCategoria" name="listCategoria" required="">
                    <option value="">Seleccionar Categoría</option>
                    <option value="bajo">Bajo</option>
                    <option value="medio">Medio</option>
                    <option value="alto">Alto</option>
                    <option value="critico">Crítico</option>
                  </select>
                </div>

                <div class="form-group">
                  <label class="control-label">Método de Cálculo</label>
                  <select class="form-control" id="listMetodoCalculo" name="listMetodoCalculo" required="">
                    <option value="">Seleccionar Método</option>
                    <option value="promedio_encuesta">Promedio Encuesta</option>
                    <option value="promedio_bitacora">Promedio Bitácora</option>
                    <option value="combinado">Combinado</option>
                    <option value="manual">Manual</option>
                    <option value="automatico">Automático</option>
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