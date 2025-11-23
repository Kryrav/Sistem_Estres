<!-- Sidebar overlay -->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>

<aside class="app-sidebar">
  <!-- Usuario -->
  <div class="app-sidebar__user">
    <img class="app-sidebar__user-avatar" src="<?= media();?>/images/avatar.png" alt="Avatar de usuario">
    <div>
      <p class="app-sidebar__user-name"><?= $_SESSION['userData']['nombres']; ?></p>
      <p class="app-sidebar__user-designation"><?= $_SESSION['userData']['nombrerol']; ?></p>
    </div>
  </div>

  <ul class="app-menu">

    <!-- Dashboard -->
    <?php if(!empty($_SESSION['permisos'][1]['r'])): ?>
      <li>
        <a class="app-menu__item" href="<?= base_url(); ?>/dashboard">
          <i class="app-menu__icon fas fa-tachometer-alt"></i>
          <span class="app-menu__label">Dashboard</span>
        </a>
      </li>
    <?php endif; ?>

    <!-- Gestión de Seguridad -->
    <?php if(!empty($_SESSION['permisos'][2]['r']) || !empty($_SESSION['permisos'][3]['r'])): ?>
      <li class="treeview">
        <a class="app-menu__item" href="#" data-toggle="treeview" aria-expanded="false" aria-label="Gestión de Seguridad">
          <i class="app-menu__icon fas fa-users-cog"></i>
          <span class="app-menu__label">Gestión de Seguridad</span>
          <i class="treeview-indicator fa fa-angle-right"></i>
        </a>
        <ul class="treeview-menu">
          <?php if(!empty($_SESSION['permisos'][2]['r'])): ?>
            <li><a class="treeview-item" href="<?= base_url(); ?>/usuarios"><i class="icon fa fa-circle"></i> Usuarios</a></li>
          <?php endif; ?>
          <?php if(!empty($_SESSION['permisos'][3]['r'])): ?>
            <li><a class="treeview-item" href="<?= base_url(); ?>/roles"><i class="icon fa fa-circle"></i> Roles y Permisos</a></li>
          <?php endif; ?>
        </ul>
      </li>
    <?php endif; ?>

    <!-- Departamentos -->
    <?php if(!empty($_SESSION['permisos'][4]['r'])): ?>
      <li>
        <a class="app-menu__item" href="<?= base_url(); ?>/departamentos">
          <i class="app-menu__icon fas fa-building"></i>
          <span class="app-menu__label">Departamentos</span>
        </a>
      </li>
    <?php endif; ?>

    <!-- Recursos Humanos -->
    <?php if(!empty($_SESSION['permisos'][5]['r'])): ?>
      <li>
        <a class="app-menu__item" href="<?= base_url(); ?>/Trabajador">
          <i class="app-menu__icon fas fa-id-badge"></i>
          <span class="app-menu__label">Recursos Humanos</span>
        </a>
      </li>
    <?php endif; ?>

    <!-- Tareas y Carga Laboral -->
    <?php if(!empty($_SESSION['permisos'][12]['r'])): ?>
      <li>
        <a class="app-menu__item" href="<?= base_url(); ?>/tareas">
          <i class="app-menu__icon fas fa-tasks"></i>
          <span class="app-menu__label">Tareas y Carga Laboral</span>
        </a>
      </li>
    <?php endif; ?>

    <!-- Indicadores de Estrés -->
    <?php if(!empty($_SESSION['permisos'][13]['r'])): ?>
      <li>
        <a class="app-menu__item" href="<?= base_url(); ?>/indicadores">
          <i class="app-menu__icon fas fa-chart-line"></i>
          <span class="app-menu__label">Indicadores de Estrés</span>
        </a>
      </li>
    <?php endif; ?>

    <!-- Bitácora Emocional -->
    <?php if(!empty($_SESSION['permisos'][14]['r'])): ?>
      <li>
        <a class="app-menu__item" href="<?= base_url(); ?>/bitacora">
          <i class="app-menu__icon fas fa-book"></i>
          <span class="app-menu__label">Bitácora Emocional</span>
        </a>
      </li>
    <?php endif; ?>

    <!-- Logout -->
    <li>
      <a class="app-menu__item" href="<?= base_url(); ?>/logout">
        <i class="app-menu__icon fas fa-sign-out-alt"></i>
        <span class="app-menu__label">Cerrar sesión</span>
      </a>
    </li>

  </ul>
</aside>
