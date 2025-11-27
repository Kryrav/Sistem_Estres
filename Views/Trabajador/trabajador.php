<?php 
    headerAdmin($data); // Incluye el encabezado y el inicio del layout
    
    // Incluir el Modal de Asignación/Edición
    getModal('modalTrabajador', $data); 
    
?>

<main class="app-content"> 
    <div class="app-title">
        <div>
            <h1><i class="fas fa-users-cog"></i> <?= $data['page_title'] ?>
                <?php if($_SESSION['permisosMod']['w']){ // Permiso de Escritura/Creación ?>
                <button class="btn btn-primary" type="button" onclick="openModal();" ><i class="fas fa-plus-circle"></i> Asignar Trabajador</button>
                <?php } ?>
            </h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="<?= base_url(); ?>/trabajadores"><?= $data['page_title'] ?></a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="tableTrabajadores">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre Completo</th>
                                    <th>Departamento</th>
                                    <th>Cargo</th>
                                    <th>Supervisor</th>
                                    <th>F. Ingreso</th>
                                    <th>Activo</th>
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

<?php 
    footerAdmin($data); // Incluye el pie de página
?>