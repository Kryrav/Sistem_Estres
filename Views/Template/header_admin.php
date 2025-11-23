<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="description" content="Sistema MSB - Gestión del Estrés Laboral en Oficinas">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Abel OSH">
    <meta name="theme-color" content="#009688">
    <link rel="shortcut icon" href="<?= media();?>/images/favicon.ico">
    <title><?= htmlspecialchars($data['page_tag']); ?></title>

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="<?= media(); ?>/css/main.css">
    <link rel="stylesheet" type="text/css" href="<?= media();?>/css/bootstrap-select.min.css"> 
    <link rel="stylesheet" type="text/css" href="<?= media(); ?>/css/style.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/r-2.2.3/datatables.min.css"/>
</head>
<body class="app sidebar-mini">

    <!-- Loader global -->
    <div id="divLoading">
        <img src="<?= media(); ?>/images/loading.svg" alt="Cargando...">
    </div>

    <!-- Navbar / Header -->
    <header class="app-header">
        <a class="app-header__logo" href="<?= base_url(); ?>/dashboard" title="Sistema MSB">
          <span class="logo-text">Sistema MSB</span>
        </a>


        <!-- Sidebar toggle button -->
        <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </a>

        <!-- User Menu -->
        <ul class="app-nav">
            <li class="dropdown">
                <a class="app-nav__item" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="Menú de usuario">
                    <i class="fa fa-user fa-lg"></i>
                </a>
                <ul class="dropdown-menu settings-menu dropdown-menu-right">
                    <li><a class="dropdown-item" href="<?= base_url(); ?>/opciones"><i class="fa fa-cog fa-lg"></i> Configuración</a></li>
                    <li><a class="dropdown-item" href="<?= base_url(); ?>/usuarios/perfil"><i class="fa fa-user fa-lg"></i> Perfil</a></li>
                    <li class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?= base_url(); ?>/logout"><i class="fa fa-sign-out-alt fa-lg"></i> Cerrar sesión</a></li>
                </ul>
            </li>
        </ul>
    </header>

    <!-- Sidebar -->
    <?php require_once("nav_admin.php"); ?> 
