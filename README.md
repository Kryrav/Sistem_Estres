Sistema de Gestión del Estrés Laboral - Metodología de Sistemas Blandos (MSB)
📋 Descripción del Proyecto

Sistema web desarrollado para identificar, analizar y gestionar el estrés laboral en organizaciones mediante la aplicación de la Metodología de Sistemas Blandos (MSB). El sistema permite mapear percepciones, identificar causas no lineales y proponer intervenciones organizacionales viables para reducir la entropía organizacional.
🎯 Objetivo General

Aplicar la Metodología de Sistemas Blandos para identificar causas, percepciones y actividades clave que reduzcan el estrés organizacional en oficinas públicas y privadas.
🚀 Características Principales
🔍 Módulos Implementados
Módulo	Descripción	Estado
Dashboard	Panel de control con métricas y gráficos interactivos	✅ Completado
Gestión de Usuarios	Administración de usuarios y permisos	✅ Completado
Roles y Permisos	Sistema de roles y control de acceso	✅ Completado
Departamentos	Gestión de áreas organizacionales	✅ Completado
Trabajadores	Registro y seguimiento de empleados	✅ Completado
Encuestas de Estrés	Creación y gestión de encuestas psicosociales	✅ Completado
Banco de Preguntas	Catálogo de preguntas para encuestas	✅ Completado
Categorías de Indicadores	Clasificación de factores de estrés	✅ Completado
Tareas y Carga Laboral	Gestión de asignaciones y carga de trabajo	✅ Completado
Bitácora Emocional	Registro emocional en tiempo real	✅ Completado
Indicadores de Estrés	Métricas y análisis de niveles de estrés	✅ Completado
Intervenciones	Sistema de alertas y acciones correctivas	✅ Completado
Analíticas y Reportes	Dashboard ejecutivo y reportes avanzados	✅ Completado
🛠️ Tecnologías Utilizadas
Backend

    PHP 7.4+ - Lenguaje de programación

    MySQL 8.0+ - Base de datos

    Arquitectura MVC - Patrón de diseño

    PDO - Conexión segura a base de datos

Frontend

    HTML5 - Estructura web

    CSS3 - Estilos y diseño

    JavaScript (ES6+) - Interactividad

    Bootstrap 4 - Framework CSS

    Chart.js - Gráficos interactivos

    DataTables - Tablas dinámicas

    SweetAlert2 - Notificaciones

    Font Awesome - Iconografía

Seguridad

    Prepared Statements - Prevención de SQL Injection

    Validación de sesiones - Control de acceso

    Sanitización de datos - Limpieza de inputs

    Sistema de permisos - Control granular de acceso

📊 Enfoque Sistémico - MSB
Conceptos Implementados

    Problemas blandos: Múltiples actores con distintas percepciones

    Rich Picture: Mapeo de tensiones, roles y cargas laborales

    Modelo conceptual MSB: Actividades esenciales identificadas

    Entropía organizacional: Monitoreo del desorden por saturación laboral

    Retroalimentación: Sistema de comunicación interna y alertas

Métricas Clave

    Niveles de estrés por departamento

    Tendencias temporales del estrés organizacional

    Distribución de cargas laborales

    Efectividad de intervenciones

    Estados emocionales en tiempo real

🗂️ Estructura del Proyecto
text

gestion_estres/
├── Controllers/
│   ├── Dashboard.php
│   ├── Usuarios.php
│   ├── Roles.php
│   ├── Departamentos.php
│   ├── Trabajadores.php
│   ├── Encuestas.php
│   ├── Preguntas.php
│   ├── Tareas.php
│   ├── Bitacora.php
│   ├── Indicadores.php
│   ├── Intervenciones.php
│   └── Analiticas.php
├── Models/
│   ├── Mysql.php
│   ├── DashboardModel.php
│   ├── UsuariosModel.php
│   └── ... [otros modelos]
├── Views/
│   ├── dashboard.php
│   ├── usuarios.php
│   └── ... [otras vistas]
├── Assets/
│   ├── css/
│   ├── js/
│   ├── plugins/
│   └── images/
├── Helpers/
│   └── helpers.php
└── Config/
    └── config.php

🚀 Instalación
Requisitos del Sistema

    Servidor web (Apache/Nginx)

    PHP 7.4 o superior

    MySQL 8.0 o superior

    Extensiones PHP: PDO, MySQLi, JSON, MBString

Pasos de Instalación

    Clonar el repositorio
    bash

git clone https://github.com/tu-usuario/gestion-estres.git
cd gestion-estres

Configurar base de datos
sql

-- Importar el archivo SQL incluido en la carpeta database/
mysql -u usuario -p nombre_base_datos < database/gestion_estres.sql

Configurar conexión a BD
php

// Editar Config/config.php
define('DB_HOST', 'localhost');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_password');
define('DB_NAME', 'gestion_estres');

Configurar URL base
php

// En Config/config.php
define('BASE_URL', 'http://localhost/gestion-estres');

Permisos de carpetas
bash

chmod 755 Assets/
chmod 644 Config/config.php

Credenciales por Defecto

    Administrador: rene@gmail.com / password

    Supervisor: empleada1@gmail.com / password

📈 Funcionalidades Destacadas
🎛️ Dashboard Interactivo

    Métricas en tiempo real

    Gráficos de tendencias de estrés

    Alertas proactivas

    Distribución de carga laboral

    Bitácora emocional en vivo

📝 Sistema de Encuestas

    Banco de preguntas categorizadas

    Múltiples tipos de preguntas (escala, opción, texto)

    Programación de encuestas

    Análisis de respuestas automático

⚠️ Sistema de Intervenciones

    Alertas automáticas por patrones de estrés

    Tipos: descanso sugerido, redistribución carga, alerta burnout, felicitaciones

    Seguimiento de estado de intervenciones

    Reportes de efectividad

📊 Analíticas Avanzadas

    Reportes ejecutivos

    Análisis comparativos

    Tendencias históricas

    Indicadores por departamento

🔐 Sistema de Seguridad

    Autenticación por sesiones

    Control de permisos por módulo (lectura, escritura, actualización, eliminación)

    Sanitización de inputs

    Protección contra CSRF

    Logs de actividad

🧩 API Endpoints

El sistema expone endpoints JSON para integración:
javascript

// Obtener métricas del dashboard
GET /Dashboard/getMetricas

// Obtener intervenciones pendientes
GET /Intervenciones/getPendientes

// Registrar entrada en bitácora
POST /Bitacora/setRegistro

📝 Licencia

Este proyecto está bajo la Licencia MIT - ver el archivo LICENSE para más detalles.
👥 Contribución

    Fork el proyecto

    Crear una rama para tu feature (git checkout -b feature/AmazingFeature)

    Commit tus cambios (git commit -m 'Add some AmazingFeature')

    Push a la rama (git push origin feature/AmazingFeature)

    Abrir un Pull Request

📞 Soporte

Para soporte técnico o consultas sobre implementación:

    📧 Email: soporte@sistemaestres.com

    🐛 Issues: GitHub Issues

    📚 Documentación: Wiki del Proyecto

🎯 Roadmap

    App móvil para bitácora emocional

    Integración con sistemas de RH existentes

    Machine Learning para predicción de burnout

    API REST completa

    Módulo de capacitaciones en bienestar laboral

Desarrollado con ❤️ para mejorar el bienestar laboral organizacional