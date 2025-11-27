<?php 
// Views/Tareas/mis_tareas.php
headerAdmin($data);

getModal('modalMisTareas',$data); 
?>
<div id="contentAjax"></div> 
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fas fa-tasks"></i> <?= $data['page_title'] ?>
                <button class="btn btn-info btn-sm ml-2" onclick="cargarMisTareas()" title="Actualizar">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </h1>
            <p class="text-muted">Gestiona el estado de tus tareas asignadas</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>/tareas/misTareas"><?= $data['page_title'] ?></a></li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="tableMisTareas">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Tipo</th>
                                    <th>Tiempo Estimado</th>
                                    <th>Tiempo Real</th>
                                    <th>Fecha Vencimiento</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Modal para actualizar estado -->
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
                        <small class="form-text text-muted">Solo si aplica para esta actualización</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="motivo_bloqueo">Motivo de Bloqueo</label>
                        <textarea class="form-control" id="motivo_bloqueo" name="motivo_bloqueo" 
                                  rows="3" placeholder="Describe el motivo del bloqueo..."></textarea>
                        <small class="form-text text-muted">Solo necesario si el estado es "Bloqueado"</small>
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
<?php footerAdmin($data); ?>