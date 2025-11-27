<!-- En modalBitacora.php - actualizar el select de tareas -->
<div class="modal fade" id="modalFormBitacora" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="titleModal">Nuevo Registro Emocional</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formBitacora" name="formBitacora">
                    <input type="hidden" id="idRegistro" name="idRegistro" value="">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="listTipoRegistro">Tipo de Registro *</label>
                                <select class="form-control" id="listTipoRegistro" name="listTipoRegistro" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="login_checkin">Check-in Diario</option>
                                    <option value="cierre_tarea">Cierre de Tarea</option>
                                    <option value="auto">Registro Automático</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="listTarea">Tarea Relacionada</label>
                                <select class="form-control" id="listTarea" name="listTarea">
                                    <option value="">Seleccionar tarea...</option>
                                    <!-- Las tareas se cargarán dinámicamente via JavaScript -->
                                </select>
                                <small class="form-text text-muted">Selecciona una tarea si este registro está relacionado con alguna actividad específica</small>
                            </div>
                        </div>
                    </div>

                    <!-- Resto del formulario se mantiene igual -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="txtStress">Nivel de Estrés Percibido (1-10)</label>
                                <input type="range" class="form-control-range" id="txtStress" name="txtStress" min="1" max="10" value="5">
                                <div class="d-flex justify-content-between">
                                    <small>1 - Muy Bajo</small>
                                    <small id="stressValue">5 - Moderado</small>
                                    <small>10 - Muy Alto</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="txtEnergia">Nivel de Energía (1-10)</label>
                                <input type="range" class="form-control-range" id="txtEnergia" name="txtEnergia" min="1" max="10" value="5">
                                <div class="d-flex justify-content-between">
                                    <small>1 - Muy Baja</small>
                                    <small id="energiaValue">5 - Moderada</small>
                                    <small>10 - Muy Alta</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="listSentimiento">Sentimiento Predominante</label>
                                <select class="form-control" id="listSentimiento" name="listSentimiento" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="motivado">😊 Motivado</option>
                                    <option value="cansado">😴 Cansado</option>
                                    <option value="frustrado">😠 Frustrado</option>
                                    <option value="ansioso">😰 Ansioso</option>
                                    <option value="satisfecho">😌 Satisfecho</option>
                                    <option value="otro">❓ Otro</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="txtComentario">Comentario Libre</label>
                        <textarea class="form-control" id="txtComentario" name="txtComentario" rows="3" placeholder="Describe cómo te sientes, qué te preocupa o cualquier observación..."></textarea>
                    </div>

                    <div class="tile-footer">
                        <button id="btnActionForm" class="btn btn-primary" type="submit">
                            <i class="fa fa-fw fa-lg fa-check-circle"></i>
                            <span id="btnText">Guardar Registro</span>
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
<!-- Modal Ver Detalles -->
<div class="modal fade" id="modalViewRegistro" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-primary">
                <h5 class="modal-title">Detalles del Registro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="detallesRegistro">
                    <!-- Los detalles se cargarán via AJAX -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
