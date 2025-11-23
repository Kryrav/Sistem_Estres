<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Abel OSH">
    <meta name="theme-color" content="#009688">
    <link rel="shortcut icon" href="<?= media();?>/images/favicon.ico">
    
    <link rel="stylesheet" type="text/css" href="<?= media();?>/css/main.css">
    <link rel="stylesheet" type="text/css" href="<?= media();?>/css/style.css">
    
    <title><?= htmlspecialchars($data['page_tag']); ?></title>
</head>
<body>
    <!-- Fondo semitransparente -->
    <section class="material-half-bg">
        <div class="cover"></div>
    </section>

    <!-- Login -->
    <section class="login-content">
        <div class="logo">
            <h1><?= htmlspecialchars($data['page_title']); ?></h1>
        </div>

        <div class="login-box">
            <!-- Loader -->
            <div id="divLoading">
                <img src="<?= media(); ?>/images/loading.svg" alt="Cargando...">
            </div>

            <!-- Formulario de Login -->
            <form class="login-form" id="formLogin" action="" method="POST">
                <h3 class="login-head">
                    <i class="fa fa-lg fa-fw fa-user"></i>INICIAR SESIÓN
                </h3>

                <div class="form-group">
                    <label class="control-label" for="txtEmail">USUARIO</label>
                    <input id="txtEmail" name="txtEmail" type="email" class="form-control" placeholder="Email" required autofocus>
                </div>

                <div class="form-group">
                    <label class="control-label" for="txtPassword">CONTRASEÑA</label>
                    <input id="txtPassword" name="txtPassword" type="password" class="form-control" placeholder="Contraseña" required>
                </div>

                <div class="form-group utility">
                    <p class="semibold-text mb-2">
                        <a href="#" data-toggle="flip">¿Olvidaste tu contraseña?</a>
                    </p>
                </div>

                <div id="alertLogin" class="text-center"></div>

                <div class="form-group btn-container">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-sign-in-alt"></i> INICIAR SESIÓN
                    </button>
                </div>
            </form>

            <!-- Formulario de Recuperar Contraseña -->
            <form id="formRecetPass" class="forget-form" action="" method="POST">
                <h3 class="login-head">
                    <i class="fa fa-lg fa-fw fa-lock"></i>¿Olvidaste contraseña?
                </h3>

                <div class="form-group">
                    <label class="control-label" for="txtEmailReset">EMAIL</label>
                    <input id="txtEmailReset" name="txtEmailReset" type="email" class="form-control" placeholder="Email" required>
                </div>

                <div class="form-group btn-container">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fa fa-unlock fa-lg fa-fw"></i> REINICIAR
                    </button>
                </div>

                <div class="form-group mt-3">
                    <p class="semibold-text mb-0">
                        <a href="#" data-toggle="flip">
                            <i class="fa fa-angle-left fa-fw"></i> Iniciar sesión
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </section>

    <script>
        const base_url = "<?= base_url(); ?>";
    </script>

    <!-- JS esenciales -->
    <script src="<?= media(); ?>/js/jquery-3.3.1.min.js"></script>
    <script src="<?= media(); ?>/js/popper.min.js"></script>
    <script src="<?= media(); ?>/js/bootstrap.min.js"></script>
    <script src="<?= media(); ?>/js/fontawesome.js"></script>
    <script src="<?= media(); ?>/js/main.js"></script>
    <script src="<?= media(); ?>/js/plugins/pace.min.js"></script>
    <script src="<?= media();?>/js/plugins/sweetalert.min.js"></script>
    <script src="<?= media(); ?>/js/<?= $data['page_functions_js']; ?>"></script>
</body>
</html>
