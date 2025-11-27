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

    <!-- DASHBOARD -->
    <?php if(!empty($_SESSION['permisos'][1]['r'])){ ?>
    <li>
      <a class="app-menu__item" href="<?= base_url(); ?>/dashboard">
        <i class="app-menu__icon fa fa-dashboard"></i>
        <span class="app-menu__label">Dashboard</span>
      </a>
    </li>
    <?php } ?>

    <!-- GESTIÓN DE SEGURIDAD -->
    <?php if(!empty($_SESSION['permisos'][2]['r']) || !empty($_SESSION['permisos'][3]['r'])){ ?>
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
    
    <!-- RECURSOS HUMANOS -->
    <?php if(!empty($_SESSION['permisos'][11]['r']) || !empty($_SESSION['permisos'][10]['r'])){ ?>
    <li class="treeview">
      <a class="app-menu__item" href="#" data-toggle="treeview">
        <i class="app-menu__icon fas fa-users-cog"></i>
        <span class="app-menu__label">Recursos Humanos</span>
        <i class="treeview-indicator fa fa-angle-right"></i>
      </a>
      <ul class="treeview-menu">
        <?php if(!empty($_SESSION['permisos'][11]['r'])){ ?>
        <li>
          <a class="treeview-item" href="<?= base_url(); ?>/trabajador">
            <i class="icon fas fa-id-badge"></i>
            Trabajadores
          </a>
        </li>
        <?php } ?>
        <?php if(!empty($_SESSION['permisos'][10]['r'])){ ?>
        <li>
          <a class="treeview-item" href="<?= base_url(); ?>/departamentos">
            <i class="icon fa fa-building"></i>
            Departamentos
          </a>
        </li>
        <?php } ?>
      </ul>
    </li>
    <?php } ?>

    <!-- GESTIÓN DE ENCUESTAS -->
    <?php if(!empty($_SESSION['permisos'][20]['r']) || !empty($_SESSION['permisos'][21]['r']) || !empty($_SESSION['permisos'][22]['r'])){ ?>
    <li class="treeview">
      <a class="app-menu__item" href="#" data-toggle="treeview">
        <i class="app-menu__icon fa fa-clipboard-list"></i>
        <span class="app-menu__label">Gestión de Encuestas</span>
        <i class="treeview-indicator fa fa-angle-right"></i>
      </a>
      <ul class="treeview-menu">
        <?php if(!empty($_SESSION['permisos'][20]['r'])){ ?>
        <li>
          <a class="treeview-item" href="<?= base_url(); ?>/encuestas">
            <i class="icon fa fa-poll"></i>
            Encuestas de Estrés
          </a>
        </li>
        <?php } ?>
        <?php if(!empty($_SESSION['permisos'][21]['r'])){ ?>
        <li>
          <a class="treeview-item" href="<?= base_url(); ?>/preguntas">
            <i class="icon fa fa-question-circle"></i>
            Banco de Preguntas
          </a>
        </li>
        <?php } ?>
        <?php if(!empty($_SESSION['permisos'][22]['r'])){ ?>
        <li>
          <a class="treeview-item" href="<?= base_url(); ?>/categorias">
            <i class="icon fa fa-tags"></i>
            Categorías de Indicadores
          </a>
        </li>
        <?php } ?>
      </ul>
    </li>
    <?php } ?>

    <!-- GESTIÓN OPERATIVA -->
    <?php if(!empty($_SESSION['permisos'][30]['r']) || !empty($_SESSION['permisos'][31]['r']) || !empty($_SESSION['permisos'][32]['r'])){ ?>
    <li class="treeview">
      <a class="app-menu__item" href="#" data-toggle="treeview">
        <i class="app-menu__icon fa fa-tasks"></i>
        <span class="app-menu__label">Gestión Operativa</span>
        <i class="treeview-indicator fa fa-angle-right"></i>
      </a>
      <ul class="treeview-menu">
        <?php if(!empty($_SESSION['permisos'][30]['r'])){ ?>
        <li>
          <a class="treeview-item" href="<?= base_url(); ?>/tareas">
            <i class="icon fa fa-tasks"></i>
            Tareas y Carga Laboral
          </a>
        </li>
        <?php } ?>
        <?php if(!empty($_SESSION['permisos'][31]['r'])){ ?>
        <li>
          <a class="treeview-item" href="<?= base_url(); ?>/tareas/mistareas">
            <i class="icon fa fa-list-alt"></i>
            Mis Tareas Asignadas
          </a>
        </li>
        <?php } ?>
        <?php if(!empty($_SESSION['permisos'][32]['r'])){ ?>
        <li>
          <a class="treeview-item" href="<?= base_url(); ?>/bitacora">
            <i class="icon fa fa-book"></i>
            Bitácora Emocional
          </a>
        </li>
        <?php } ?>
      </ul>
    </li>
    <?php } ?>

    <!-- ANÁLISIS Y MONITOREO -->
    <?php if(!empty($_SESSION['permisos'][40]['r']) || !empty($_SESSION['permisos'][41]['r']) || !empty($_SESSION['permisos'][42]['r'])){ ?>
    <li class="treeview">
      <a class="app-menu__item" href="#" data-toggle="treeview">
        <i class="app-menu__icon fa fa-chart-line"></i>
        <span class="app-menu__label">Análisis y Monitoreo</span>
        <i class="treeview-indicator fa fa-angle-right"></i>
      </a>
      <ul class="treeview-menu">
        <?php if(!empty($_SESSION['permisos'][40]['r'])){ ?>
        <li>
          <a class="treeview-item" href="<?= base_url(); ?>/indicadores">
            <i class="icon fa fa-heartbeat"></i>
            Indicadores de Estrés
          </a>
        </li>
        <?php } ?>
        <?php if(!empty($_SESSION['permisos'][41]['r'])){ ?>
        <li>
          <a class="treeview-item" href="<?= base_url(); ?>/intervenciones">
            <i class="icon fa fa-life-ring"></i>
            Intervenciones
          </a>
        </li>
        <?php } ?>
        <?php if(!empty($_SESSION['permisos'][42]['r'])){ ?>
        <li>
          <a class="treeview-item" href="<?= base_url(); ?>/analiticas">
            <i class="icon fa fa-chart-bar"></i>
            Analíticas y Reportes
          </a>
        </li>
        <?php } ?>
      </ul>
    </li>
    <?php } ?>

    <!-- LOGOUT -->
    <li>
      <a class="app-menu__item" href="<?= base_url(); ?>/logout">
        <i class="app-menu__icon fa fa-sign-out" aria-hidden="true"></i>
        <span class="app-menu__label">Cerrar Sesión</span>
      </a>
    </li>
  </ul>
</aside>