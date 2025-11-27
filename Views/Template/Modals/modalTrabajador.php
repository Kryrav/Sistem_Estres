<?php 
    // Ubicación: Views/Template/Modals/modalTrabajador.php
?>
<div class="modal fade" id="modalFormTrabajador" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="titleModal">Asignar Nuevo Trabajador</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formTrabajador" name="formTrabajador" class="form-horizontal">
                    <input type="hidden" id="id" name="id" value="0"> 
                    
                    <div class="form-group" id="containerIdPersona">
                        <label for="listIdPersona">Persona a Asignar <span class="required">*</span></label>
                        <select class="form-control selectpicker" data-live-search="true" id="listIdPersona" name="listIdPersona" required>
                            </select>
                        <small class="form-text text-muted">Solo usuarios sin asignación de trabajador.</small>
                    </div>

                    <div class="form-group">
                        <label for="listDepartamento">Departamento <span class="required">*</span></label>
                        <select class="form-control selectpicker" data-live-search="true" id="listDepartamento" name="listDepartamento" required>
                            </select>
                    </div>

                    <div class="form-group">
                        <label for="txtCargo">Cargo / Posición <span class="required">*</span></label>
                        <input type="text" class="form-control" id="txtCargo" name="txtCargo" required>
                    </div>

                    <div class="form-group">
                        <label for="listHorasDiarias">Horas Diarias <span class="required">*</span></label>
                        <input type="number" class="form-control" id="listHorasDiarias" name="listHorasDiarias" min="1" max="16" required value="8">
                        <small class="form-text text-muted">Horas efectivas de trabajo por día.</small>
                    </div>

                    <div class="form-group">
                        <label for="listSupervisor">Supervisor</label>
                        <select class="form-control selectpicker" data-live-search="true" id="listSupervisor" name="listSupervisor">
                            <option value="0">Sin Supervisor Asignado</option>
                            </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="listActivo">Estado <span class="required">*</span></label>
                        <select class="form-control selectpicker" id="listActivo" name="listActivo" required>
                            <option value="1">Activo</option>
                            <option value="2">Inactivo</option>
                        </select>
                    </div>

                    <div class="tile-footer">
                        <button id="btnActionForm" class="btn btn-primary" type="submit">
                            <i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Guardar</span>
                        </button>&nbsp;&nbsp;&nbsp;
                        <button class="btn btn-danger" type="button" data-dismiss="modal">
                            <i class="fa fa-fw fa-lg fa-times-circle"></i>Cerrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Ver Trabajador -->

<div class="modal fade" id="modalViewTrabajador" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header header-primary">
                <h5 class="modal-title" id="titleModalView">Datos del Trabajador</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>ID Trabajador:</td>
                            <td id="celIdTrabajador"></td>
                        </tr>
                        <tr>
                            <td>Identificación:</td>
                            <td id="celIdentificacion"></td>
                        </tr>
                        <tr>
                            <td>Nombre Completo:</td>
                            <td id="celNombreCompleto"></td>
                        </tr>
                        <tr>
                            <td>Rol de Sistema:</td>
                            <td id="celRolSistema"></td>
                        </tr>
                        <tr>
                            <td>Correo Electrónico:</td>
                            <td id="celEmail"></td>
                        </tr>
                        <tr>
                            <td>Teléfono:</td>
                            <td id="celTelefono"></td>
                        </tr>
                        <tr>
                            <td>Departamento:</td>
                            <td id="celDepartamento"></td>
                        </tr>
                        <tr>
                            <td>Cargo Asignado:</td>
                            <td id="celCargo"></td>
                        </tr>
                        <tr>
                            <td>Supervisor Asignado:</td>
                            <td id="celSupervisor"></td>
                        </tr>
                        <tr>
                            <td>Horas Diarias:</td>
                            <td id="celHorasDiarias"></td>
                        </tr>
                        <tr>
                            <td>Fecha de Ingreso:</td>
                            <td id="celFechaIngreso"></td>
                        </tr>
                        <tr>
                            <td>Estado:</td>
                            <td id="celEstado"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>