<div class="modal fade" id="modalFormCategoria" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="titleModal">Nueva Categoría de Indicador</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="tile">
                    <div class="tile-body">
                        <form id="formCategoria" name="formCategoria">
                            <input type="hidden" id="id" name="id" value="">
                            
                            <div class="form-group">
                                <label class="control-label">Nombre <span class="text-danger">*</span></label>
                                <input class="form-control" id="txtNombre" name="txtNombre" type="text" placeholder="Nombre de la categoría" required="">
                            </div>

                            <div class="form-group">
                                <label class="control-label">Descripción</label>
                                <textarea class="form-control" id="txtDescripcion" name="txtDescripcion" rows="3" placeholder="Descripción breve de la categoría"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="listActivo">Estado <span class="text-danger">*</span></label>
                                <select class="form-control selectpicker" id="listActivo" name="listActivo" required >
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
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
        </div>
    </div>
</div>