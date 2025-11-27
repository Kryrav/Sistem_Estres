<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mini Framework - Rene Vasquez</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StressGuard - Sistema de Gestión del Estrés Laboral</title>
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --accent: #e74c3c;
            --light: #ecf0f1;
            --dark: #2c3e50;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: #333;
        }
        
        .navbar {
            background-color: var(--primary);
            padding: 15px 0;
            transition: all 0.3s;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 120px 0 100px;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .hero-section p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        
        .btn-primary {
            background-color: var(--accent);
            border-color: var(--accent);
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 30px;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background-color: #c0392b;
            border-color: #c0392b;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 60px;
            position: relative;
        }
        
        .section-title:after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: var(--secondary);
            margin: 15px auto;
            border-radius: 2px;
        }
        
        .feature-box {
            text-align: center;
            padding: 30px 20px;
            border-radius: 10px;
            transition: all 0.3s;
            margin-bottom: 30px;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .feature-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            font-size: 3rem;
            color: var(--secondary);
            margin-bottom: 20px;
        }
        
        .benefits-section {
            background-color: var(--light);
            padding: 100px 0;
        }
        
        .benefit-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 30px;
        }
        
        .benefit-icon {
            font-size: 1.5rem;
            color: var(--secondary);
            margin-right: 15px;
            margin-top: 5px;
        }
        
        .pricing-card {
            border: none;
            border-radius: 10px;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        
        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        
        .pricing-header {
            background: var(--primary);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        
        .pricing-body {
            padding: 30px;
        }
        
        .price {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        .contact-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 100px 0;
        }
        
        .contact-form {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        
        .contact-form .form-control {
            border-radius: 5px;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid #e1e5eb;
        }
        
        footer {
            background-color: var(--dark);
            color: white;
            padding: 60px 0 30px;
        }
        
        .social-links a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            margin-right: 10px;
            color: white;
            transition: all 0.3s;
        }
        
        .social-links a:hover {
            background: var(--secondary);
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">MAIN Stress System</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#inicio">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#caracteristicas">Características</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#beneficios">Beneficios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#precios">Planes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contacto">Contacto</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="inicio">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1>Gestiona el Estrés Laboral de Manera Eficiente</h1>
                    <p>Nuestro sistema integral monitorea, analiza y ayuda a reducir los niveles de estrés en tu organización, mejorando el bienestar y la productividad de tus colaboradores.</p>
                    <a href="<?= base_url(); ?>/login" class="btn btn-primary">Ver Demo </a>
                </div>
                <div class="col-lg-6">
                    <img src="<?= media(); ?>/images/Dashboard.png" alt="Dashboard StressGuard" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5" id="caracteristicas" >
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="section-title">Características Principales</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <h4>Encuestas de Estrés</h4>
                        <p>Diseña y administra encuestas personalizadas para evaluar los niveles de estrés en tu organización.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4>Analíticas en Tiempo Real</h4>
                        <p>Monitorea indicadores de estrés y genera reportes detallados para la toma de decisiones.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <h4>Gestión de Carga Laboral</h4>
                        <p>Asigna y monitorea tareas, identificando posibles fuentes de estrés por sobrecarga de trabajo.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <h4>Bitácora Emocional</h4>
                        <p>Permite a los colaboradores registrar su estado emocional y factores que influyen en su bienestar.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-life-ring"></i>
                        </div>
                        <h4>Intervenciones Proactivas</h4>
                        <p>Sistema de alertas y recomendaciones para intervenir ante situaciones de alto estrés.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4>Gestión de Usuarios</h4>
                        <p>Administra roles, permisos y departamentos con un sistema de seguridad robusto.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="benefits-section" id="beneficios">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="section-title">Beneficios para tu Organización</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h4>Reducción del Ausentismo</h4>
                            <p>Disminuye las ausencias laborales relacionadas con problemas de salud mental y estrés.</p>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h4>Mejora la Productividad</h4>
                            <p>Colaboradores más saludables y menos estresados son significativamente más productivos.</p>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h4>Retención de Talento</h4>
                            <p>Mejora la satisfacción laboral y reduce la rotación de personal.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h4>Cumplimiento Normativo</h4>
                            <p>Ayuda a cumplir con las regulaciones sobre salud mental en el entorno laboral.</p>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h4>Ambiente Laboral Saludable</h4>
                            <p>Fomenta una cultura organizacional que valora el bienestar de sus colaboradores.</p>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h4>Decisiones Basadas en Datos</h4>
                            <p>Proporciona información valiosa para la toma de decisiones estratégicas en RRHH.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="py-5" id="precios">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="section-title">Planes y Precios</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6">
                    <div class="pricing-card">
                        <div class="pricing-header">
                            <h3>Básico</h3>
                            <p>Para pequeñas empresas</p>
                        </div>
                        <div class="pricing-body text-center">
                            <div class="price">$99<span>/mes</span></div>
                            <ul class="list-unstyled my-4">
                                <li class="py-2"><i class="fas fa-check text-success mr-2"></i> Hasta 50 usuarios</li>
                                <li class="py-2"><i class="fas fa-check text-success mr-2"></i> Encuestas básicas</li>
                                <li class="py-2"><i class="fas fa-check text-success mr-2"></i> Reportes estándar</li>
                                <li class="py-2"><i class="fas fa-times text-muted mr-2"></i> Bitácora emocional</li>
                                <li class="py-2"><i class="fas fa-times text-muted mr-2"></i> Intervenciones</li>
                            </ul>
                            <a href="#contacto" class="btn btn-outline-primary btn-block">Seleccionar</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="pricing-card">
                        <div class="pricing-header bg-secondary">
                            <h3>Profesional</h3>
                            <p>Para medianas empresas</p>
                        </div>
                        <div class="pricing-body text-center">
                            <div class="price">$199<span>/mes</span></div>
                            <ul class="list-unstyled my-4">
                                <li class="py-2"><i class="fas fa-check text-success mr-2"></i> Hasta 200 usuarios</li>
                                <li class="py-2"><i class="fas fa-check text-success mr-2"></i> Encuestas avanzadas</li>
                                <li class="py-2"><i class="fas fa-check text-success mr-2"></i> Reportes detallados</li>
                                <li class="py-2"><i class="fas fa-check text-success mr-2"></i> Bitácora emocional</li>
                                <li class="py-2"><i class="fas fa-times text-muted mr-2"></i> Intervenciones limitadas</li>
                            </ul>
                            <a href="#contacto" class="btn btn-primary btn-block">Seleccionar</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="pricing-card">
                        <div class="pricing-header">
                            <h3>Empresarial</h3>
                            <p>Para grandes organizaciones</p>
                        </div>
                        <div class="pricing-body text-center">
                            <div class="price">$399<span>/mes</span></div>
                            <ul class="list-unstyled my-4">
                                <li class="py-2"><i class="fas fa-check text-success mr-2"></i> Usuarios ilimitados</li>
                                <li class="py-2"><i class="fas fa-check text-success mr-2"></i> Encuestas personalizadas</li>
                                <li class="py-2"><i class="fas fa-check text-success mr-2"></i> Analíticas avanzadas</li>
                                <li class="py-2"><i class="fas fa-check text-success mr-2"></i> Bitácora emocional</li>
                                <li class="py-2"><i class="fas fa-check text-success mr-2"></i> Intervenciones completas</li>
                            </ul>
                            <a href="#contacto" class="btn btn-outline-primary btn-block">Seleccionar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section" id="contacto">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="section-title text-white">Contáctanos</h2>
                    <p class="text-white">¿Listo para mejorar el bienestar en tu organización? Solicita una demostración gratuita.</p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="contact-form">
                        <form id="contactForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Nombre completo" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="email" class="form-control" placeholder="Correo electrónico" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Empresa">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="tel" class="form-control" placeholder="Teléfono">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <select class="form-control" required>
                                    <option value="" disabled selected>Selecciona un plan de interés</option>
                                    <option value="basico">Plan Básico</option>
                                    <option value="profesional">Plan Profesional</option>
                                    <option value="empresarial">Plan Empresarial</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" rows="5" placeholder="Mensaje"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Enviar Solicitud</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h4>StressGuard</h4>
                    <p>Sistema integral de gestión del estrés laboral para organizaciones modernas.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                    <h5>Enlaces</h5>
                    <ul class="list-unstyled">
                        <li><a href="#inicio" class="text-light">Inicio</a></li>
                        <li><a href="#caracteristicas" class="text-light">Características</a></li>
                        <li><a href="#beneficios" class="text-light">Beneficios</a></li>
                        <li><a href="#precios" class="text-light">Planes</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5>Contacto</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-map-marker-alt mr-2"></i> Av. Principal 123, Ciudad</li>
                        <li class="mb-2"><i class="fas fa-phone mr-2"></i> +1 234 567 890</li>
                        <li class="mb-2"><i class="fas fa-envelope mr-2"></i> info@stressguard.com</li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h5>Newsletter</h5>
                    <p>Suscríbete para recibir actualizaciones y noticias.</p>
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Tu correo electrónico">
                        <div class="input-group-append">
                            <button class="btn btn-secondary" type="button">Suscribir</button>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="bg-light my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; 2025 StressGuard. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-md-right">
                    <a href="#" class="text-light mr-3">Política de Privacidad</a>
                    <a href="#" class="text-light">Términos de Servicio</a>
                </div>
            </div>
        </div>
    </footer>

<!-- jQuery FIRST - Versión completa -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Luego Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Custom JS -->
<script>
// Versión corregida - sin conflicto de jQuery
$(document).ready(function() {
    console.log("jQuery cargado correctamente"); // Para verificar
    
    // Smooth scrolling
    $('a.nav-link[href*="#"]').on('click', function(e) {
        e.preventDefault();
        
        const targetId = $(this).attr('href');
        const targetElement = $(targetId);
        
        if (targetElement.length) {
            $('html, body').animate({
                scrollTop: targetElement.offset().top - 70
            }, 800);
        }
    });
    
    // Para botones también
    $('a.btn[href*="#"]').on('click', function(e) {
        e.preventDefault();
        
        const targetId = $(this).attr('href');
        const targetElement = $(targetId);
        
        if (targetElement.length) {
            $('html, body').animate({
                scrollTop: targetElement.offset().top - 70
            }, 800);
        }
    });
    
    // Navbar background on scroll
    $(window).scroll(function() {
        if ($(window).scrollTop() > 50) {
            $('.navbar').addClass('bg-dark');
        } else {
            $('.navbar').removeClass('bg-dark');
        }
    });
    
    // Form submission
    $('#contactForm').on('submit', function(e) {
        e.preventDefault();
        alert('¡Gracias por tu interés! Nos pondremos en contacto contigo pronto.');
        this.reset();
    });
});
</script>
</body>
</html>