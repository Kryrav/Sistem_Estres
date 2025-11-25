
<div class="modal fade" id="modalEstadoTarea" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header headerPrimary">
                <h5 class="modal-title">Actualizar Estado de Tarea</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEstadoTarea" name="formEstadoTarea">
                    <input type="hidden" id="idTarea" name="idTarea">
                    
                    <div class="form-group">
                        <label for="estado">Estado *</label>
                        <select class="form-control" id="estado" name="estado" required>
                            <option value="">Seleccionar...</option>
                            <option value="en_progreso">🟦 En Progreso</option>
                            <option value="bloqueado">🟨 Bloqueado</option>
                            <option value="revision">🟪 En Revisión</option>
                            <option value="terminado">🟩 Terminado</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="minutos_reales">Minutos Reales Invertidos</label>
                        <input type="number" class="form-control" id="minutos_reales" name="minutos_reales" 
                               placeholder="Minutos realmente trabajados">
                    </div>
                    
                    <div class="form-group">
                        <label for="motivo_bloqueo">Motivo de Bloqueo</label>
                        <textarea class="form-control" id="motivo_bloqueo" name="motivo_bloqueo" 
                                  rows="3" placeholder="Describe el motivo del bloqueo..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="fntGuardarEstado()">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
            </div>
        </div>
    </div>
</div>