<div class="modal fade" id="modalFormEncuesta" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document"> <div class="modal-content">
            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="titleModal">Nueva Encuesta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="datos-tab" data-toggle="tab" href="#datosGenerales" role="tab" aria-controls="datosGenerales" aria-selected="true">1. Datos Generales</a>
                    </li>
                    <li class="nav-item" id="tabPreguntasLink" style="display:none;"> 
                        <a class="nav-link" id="preguntas-tab" data-toggle="tab" href="#asignarPreguntas" role="tab" aria-controls="asignarPreguntas" aria-selected="false">2. Asignar Preguntas</a>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    
                    <div class="tab-pane fade show active" id="datosGenerales" role="tabpanel" aria-labelledby="datos-tab">
                        <div class="tile">
                            <div class="tile-body">
                                <form id="formEncuesta" name="formEncuesta" class="form-horizontal">
                                    <input type="hidden" id="id" name="id" value="">
                                    <p class="text-primary">Todos los campos con asterisco (<span class="text-danger">*</span>) son obligatorios.</p>

                                    <div class="form-group">
                                        <label class="control-label">Título <span class="text-danger">*</span></label>
                                        <input class="form-control" id="txtTitulo" name="txtTitulo" type="text" placeholder="Título de la Encuesta" required="">
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label">Descripción</label>
                                        <textarea class="form-control" id="txtDescripcion" name="txtDescripcion" rows="3" placeholder="Descripción breve y objetivo de la encuesta"></textarea>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Fecha de Inicio <span class="text-danger">*</span></label>
                                            <input class="form-control" id="txtFechaInicio" name="txtFechaInicio" type="date" required="">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="control-label">Fecha de Fin <span class="text-danger">*</span></label>
                                            <input class="form-control" id="txtFechaFin" name="txtFechaFin" type="date" required="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="listEstado">Estado <span class="text-danger">*</span></label>
                                        <select class="form-control selectpicker" id="listEstado" name="listEstado" required >
                                            <option value="BORRADOR">Borrador</option>
                                            <option value="INACTIVA">Inactiva</option>
                                            <option value="ACTIVA">Activa</option>
                                        </select>
                                    </div>

                                    <div class="tile-footer">
                                        <button id="btnActionForm" class="btn btn-primary" type="submit">
                                            <i class="fa fa-fw fa-lg fa-check-circle"></i>
                                            <span id="btnText">Guardar</span>
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

                    <div class="tab-pane fade" id="asignarPreguntas" role="tabpanel" aria-labelledby="preguntas-tab">
                        <div class="tile-body">
                             <form id="formAsignacion" name="formAsignacion" class="form-horizontal">
                                <input type="hidden" id="assignEncuestaId" name="assignEncuestaId" value="">
                                <p class="text-primary">Arrastre preguntas de la izquierda a la derecha para asignarlas. Puede reordenarlas en la lista de "Asignadas".</p>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="tile">
                                            <h5 class="text-center text-info">Banco de Preguntas Disponibles</h5>
                                            
                                            <div class="form-group row">
                                                <div class="col-sm-6 mb-2">
                                                    <input type="text" class="form-control" id="inputSearchPreguntas" placeholder="Buscar por texto de pregunta...">
                                                </div>
                                                <div class="col-sm-6">
                                                    <select class="form-control selectpicker" data-live-search="true" id="selectCategoryFilter" name="selectCategoryFilter" title="-- Todas las Categorías --">
                                                        </select>
                                                </div>
                                            </div>

                                            <div class="list-group list-group-flush" id="preguntasDisponiblesList" style="max-height: 500px; overflow-y: auto; border: 1px solid #ddd; padding: 5px;">
                                                <div class="list-group-item text-center p-3 text-muted">Cargando preguntas...</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="tile">
                                            <h5 class="text-center text-success">Preguntas Asignadas <span class="badge badge-success" id="countAsignadas">0</span> (Orden y Drag & Drop)</h5>
                                            <div class="list-group list-group-flush" id="preguntasAsignadasList" style="min-height: 500px; max-height: 500px; overflow-y: auto; border: 1px solid #ddd; padding: 5px; background-color: #f7f7f7;">
                                                <div class="list-group-item text-center p-3 text-muted">Arrastre preguntas aquí.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="tile-footer mt-4">
                                    <button id="btnActionFormAssign" class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnTextAssign">Guardar Asignación y Orden</span></button>&nbsp;&nbsp;&nbsp;
                                    <button class="btn btn-secondary" type="button" data-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cerrar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<!--  Vista de la encuesta -->
  <div class="modal fade" id="modalViewEncuesta" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-primary">
                <h5 class="modal-title" id="titleModalView">Detalles de la Encuesta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="tile">
                    <div class="tile-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h4 class="text-info" id="cellTitulo">Título de la Encuesta</h4>
                                <hr>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <p><strong>Descripción:</strong> <span id="cellDescripcion"></span></p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Fecha Inicio:</strong> <span id="cellFechaInicio"></span></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Fecha Fin:</strong> <span id="cellFechaFin"></span></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Estado:</strong> <span id="cellEstado"></span></p>
                            </div>
                        </div>

                        <h5 class="mt-4 text-success">Preguntas Asignadas y Orden</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">Orden</th>
                                        <th>Pregunta</th>
                                        <th style="width: 150px;">Tipo</th>
                                        <th style="width: 150px;">Categoría</th>
                                    </tr>
                                </thead>
                                <tbody id="preguntasContainerView">
                                    </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>