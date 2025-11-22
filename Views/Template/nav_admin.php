<!-- Sidebar menu-->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
  <div class="app-sidebar__user">
    <img class="app-sidebar__user-avatar" src="<?= media();?>/images/avatar.png" alt="User Image">
    <div>
      <p class="app-sidebar__user-name"><?= $_SESSION['userData']['nombres']; ?></p>
      <p class="app-sidebar__user-designation"><?= $_SESSION['userData']['nombrerol']; ?></p>
    </div>
  </div>
  <ul class="app-menu">

    <!-- PERMISOS DASHBOARD -->
    <?php 
    // Verifica el permiso de Lectura (r) para el Módulo 1 (Dashboard)
    if(!empty($_SESSION['permisos'][1]['r'])){ 
    ?>
    <li>
      <a class="app-menu__item" href="<?= base_url(); ?>/dashboard">
        <i class="app-menu__icon fa fa-dashboard"></i>
        <span class="app-menu__label">Dashboard</span>
      </a>
    </li>
    <?php } ?>

    <?php 
    // Verifica si tiene permiso de Lectura para el Módulo 2 (Usuarios) O Módulo 3 (Roles)
    if(!empty($_SESSION['permisos'][2]['r']) || !empty($_SESSION['permisos'][3]['r'])){ 
    ?>
    <li class="treeview">
      <a class="app-menu__item" href="#" data-toggle="treeview">
        <i class="app-menu__icon fa fa-users" aria-hidden="true"></i>
        <span class="app-menu__label">Gestión de Seguridad</span>
        <i class="treeview-indicator fa fa-angle-right"></i>
      </a>
      <ul class="treeview-menu">
        <?php if(!empty($_SESSION['permisos'][2]['r'])){ ?>
        <li><a class="treeview-item" href="<?= base_url(); ?>/usuarios"><i class="icon fa fa-circle-o"></i> Usuarios</a></li>
        <?php } ?>
        <?php if(!empty($_SESSION['permisos'][3]['r'])){ ?>
        <li><a class="treeview-item" href="<?= base_url(); ?>/roles"><i class="icon fa fa-circle-o"></i> Roles y Permisos</a></li>
        <?php } ?>
      </ul>
    </li>
    <?php } ?>
    
    <?php 
    // Verifica el permiso de Lectura (r) para el Módulo 4 (Departamentos)
    if(!empty($_SESSION['permisos'][4]['r'])){ 
    ?>
    <li>
      <a class="app-menu__item" href="<?= base_url(); ?>/departamentos">
        <i class="app-menu__icon fa fa-building" aria-hidden="true"></i>
        <span class="app-menu__label">Departamentos</span>
      </a>
    </li>
    <?php } ?>
    
    <?php if (!empty($_SESSION['permisos'][5]['r'])): ?>

        <li class="treeview">
            <a class="app-menu__item" href="#" data-toggle="treeview">
                <i class="app-menu__icon fas fa-users-cog"></i> 
                <span class="app-menu__label">Recursos Humanos</span>
                <i class="treeview-indicator fa fa-angle-right"></i>
            </a>
            <ul class="treeview-menu">
                
                <li>
                    <a class="treeview-item" href="<?= base_url(); ?>/Trabajador">
                        <i class="icon fas fa-id-badge"></i>
                        Trabajadores
                    </a>
                </li>
                
                </ul>
        </li>

    <?php endif; ?>

    <?php 
    // Verifica el permiso de Lectura (r) para el Módulo 5 (Tareas)
    if(!empty($_SESSION['permisos'][12]['r'])){ 
    ?>
    <li>
      <a class="app-menu__item" href="<?= base_url(); ?>/tareas">
        <i class="app-menu__icon fa fa-tasks" aria-hidden="true"></i>
        <span class="app-menu__label">Tareas y Carga Laboral</span>
      </a>
    </li>
    <?php } ?>

    <?php 
    // Verifica el permiso de Lectura (r) para el Módulo 6 (Indicadores)
    if(!empty($_SESSION['permisos'][13]['r'])){ 
    ?>
    <li>
      <a class="app-menu__item" href="<?= base_url(); ?>/indicadores">
        <i class="app-menu__icon fa fa-chart-line" aria-hidden="true"></i>
        <span class="app-menu__label">Indicadores de Estrés</span>
      </a>
    </li>
    <?php } ?>
    
    <?php 
    // Verifica el permiso de Lectura (r) para el Módulo 7 (Bitácora Emocional)
    if(!empty($_SESSION['permisos'][14]['r'])){ 
    ?>
    <li>
      <a class="app-menu__item" href="<?= base_url(); ?>/bitacora">
        <i class="app-menu__icon fa fa-book" aria-hidden="true"></i>
        <span class="app-menu__label">Bitácora Emocional</span>
      </a>
    </li>
    <?php } ?>
    
    <li>
      <a class="app-menu__item" href="<?= base_url(); ?>/logout">
        <i class="app-menu__icon fa fa-sign-out" aria-hidden="true"></i>
        <span class="app-menu__label">Logout</span>
      </a>
    </li>
  </ul>
</aside>