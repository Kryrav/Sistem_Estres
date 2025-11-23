<div class="modal fade" id="modalFormPregunta" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="titleModal">Nueva Pregunta para Encuesta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formPregunta" name="formPregunta">
                    <input type="hidden" id="id" name="id" value="">
                    
                    <div class="row">
                        <div class="col-md-6">
                            
                            <div class="form-group">
                                <label for="listCategoria">Categoría de Indicador <span class="text-danger">*</span></label>
                                <select class="form-control selectpicker" id="listCategoria" name="listCategoria" required >
                                    </select>
                            </div>

                            <div class="form-group">
                                <label for="txtTextoPregunta">Texto de la Pregunta <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="txtTextoPregunta" name="txtTextoPregunta" rows="4" placeholder="Escriba la pregunta (ítem) de la encuesta" required=""></textarea>
                            </div>

                            <div class="form-group">
                                <label for="listTipoPregunta">Tipo de Respuesta <span class="text-danger">*</span></label>
                                <select class="form-control selectpicker" id="listTipoPregunta" name="listTipoPregunta" required >
                                    <option value="ESCALA">Escala (Ej. Likert 1-5)</option>
                                    <option value="OPCION">Opción Múltiple (Ej. Sí/No)</option>
                                    <option value="TEXTO">Texto Libre</option>
                                </select>
                            </div>

                            <div class="form-group" id="estado-pregunta-group">
                                <label for="listActivo">Estado <span class="text-danger">*</span></label>
                                <select class="form-control selectpicker" id="listActivo" name="listActivo" required >
                                    <option value="1">Activa</option>
                                    <option value="0">Inactiva</option>
                                </select>
                            </div>

                        </div>
                        
                        <div class="col-md-6">
                            <div id="opciones-panel" class="tile">
                                <h5 class="tile-title text-center">Opciones de Respuesta y Valoración</h5>
                                <div id="opciones-alerta" class="alert alert-info text-center">
                                    Seleccione **Escala** u **Opción Múltiple** para agregar opciones.
                                </div>
                                
                                <button type="button" class="btn btn-sm btn-success btn-block my-2" id="btnAddOpcion" style="display: none;">
                                    <i class="fas fa-plus"></i> Agregar Opción
                                </button>

                                <div class="form-group table-responsive" id="opciones-table-container">
                                    <table class="table table-bordered table-sm" id="tableOpciones">
                                        <thead>
                                            <tr>
                                                <th>Texto de Opción</th>
                                                <th style="width: 100px;">Valor Numérico</th>
                                                <th style="width: 50px;">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody id="opciones-container">
                                            </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tile-footer text-right mt-3">
                        <button id="btnActionForm" class="btn btn-primary" type="submit">
                            <i class="fa fa-fw fa-lg fa-check-circle"></i>
                            <span id="btnText">Guardar Pregunta</span>
                        </button>
                        &nbsp;&nbsp;&nbsp;
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">
                            <i class="fa fa-fw fa-lg fa-times-circle"></i>
                            Cerrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>