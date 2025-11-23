CREATE DATABASE IF NOT EXISTS `gestion_estres`;
USE `gestion_estres`;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `departamentos`
--

DROP TABLE IF EXISTS `departamentos`;
CREATE TABLE `departamentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `umbral_alerta_stress` int DEFAULT '7',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departamentos`
--

LOCK TABLES `departamentos` WRITE;
INSERT INTO `departamentos` VALUES 
(1,'Gerencia de Sistemas','Departamento de TI/Administración del Sistema',8,'2025-11-21 21:18:36'),
(2,'Recursos Humanos','Gestión de personal y bienestar laboral',6,'2025-11-21 21:18:36'),
(3,'Ventas y Marketing','Equipo comercial y promociones',7,'2025-11-21 21:18:36');
UNLOCK TABLES;

--
-- Table structure for table `rol`
--

DROP TABLE IF EXISTS `rol`;
CREATE TABLE `rol` (
  `idrol` bigint NOT NULL AUTO_INCREMENT,
  `nombrerol` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`idrol`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rol`
--

LOCK TABLES `rol` WRITE;
INSERT INTO `rol` VALUES 
(1,'Super Administrador','Acceso total al sistema',1),
(2,'Supervisor','Supervisión de trabajadores',1),
(3,'Empleado','Usuario operativo estándar',1);
UNLOCK TABLES;

--
-- Table structure for table `persona`
--

DROP TABLE IF EXISTS `persona`;
CREATE TABLE `persona` (
  `idpersona` bigint NOT NULL AUTO_INCREMENT,
  `identificacion` varchar(30) NOT NULL,
  `nombres` varchar(80) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email_user` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `nit` varchar(50) DEFAULT NULL,
  `nombrefiscal` varchar(100) DEFAULT NULL,
  `direccionfiscal` varchar(150) DEFAULT NULL,
  `token` varchar(150) DEFAULT NULL,
  `rolid` bigint NOT NULL,
  `datecreated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`idpersona`),
  UNIQUE KEY `persona_email_uq` (`email_user`),
  KEY `rolid` (`rolid`),
  CONSTRAINT `persona_fk_rol` FOREIGN KEY (`rolid`) REFERENCES `rol` (`idrol`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `persona`
--

LOCK TABLES `persona` WRITE;
INSERT INTO `persona` VALUES 
(1,'7268984','Rene','Vasquez','67230415','rene@gmail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','7268984','Rene','Av. Paragua Santa Cruz',NULL,1,'2025-11-21 17:18:16',1),
(2,'199','Miriam','Montecinos','74939941','empleada1@gmail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','199','Nela','Oruro',NULL,2,'2025-11-21 17:56:34',1),
(3,'123456','Carlos','Mendoza','77788899','carlos.mendoza@empresa.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','123456','Carlos','Calle Principal 123',NULL,3,'2025-11-21 17:56:34',1),
(4,'789012','Ana','Garcia','66655544','ana.garcia@empresa.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','789012','Ana','Avenida Central 456',NULL,3,'2025-11-21 17:56:34',1),
(5,'345678','Luis','Fernandez','33322211','luis.fernandez@empresa.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','345678','Luis','Plaza Mayor 789',NULL,3,'2025-11-21 17:56:34',1);
UNLOCK TABLES;

--
-- Table structure for table `trabajador`
--

DROP TABLE IF EXISTS `trabajador`;
CREATE TABLE `trabajador` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `idpersona` bigint NOT NULL,
  `departamento_id` int DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `horas_trabajo_diarias` int DEFAULT '8',
  `activo` tinyint(1) DEFAULT '1',
  `cargo` varchar(100) DEFAULT NULL,
  `supervisor_id` bigint DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trabajador_persona_uq` (`idpersona`),
  KEY `departamento_id` (`departamento_id`),
  KEY `idx_trabajador_departamento` (`departamento_id`),
  CONSTRAINT `trabajador_fk_departamento` FOREIGN KEY (`departamento_id`) REFERENCES `departamentos` (`id`) ON DELETE SET NULL,
  CONSTRAINT `trabajador_fk_persona` FOREIGN KEY (`idpersona`) REFERENCES `persona` (`idpersona`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trabajador`
--

LOCK TABLES `trabajador` WRITE;
INSERT INTO `trabajador` VALUES 
(1,1,1,'2023-01-15',8,1,'Gerente de Sistemas',NULL),
(2,2,2,'2023-03-20',8,1,'Supervisora de RH',1),
(3,3,1,'2023-06-10',8,1,'Desarrollador Senior',1),
(4,4,3,'2023-02-15',8,1,'Ejecutivo de Ventas',1),
(5,5,2,'2023-08-05',8,1,'Analista de RH',2);
UNLOCK TABLES;

--
-- Table structure for table `tipos_tarea`
--

DROP TABLE IF EXISTS `tipos_tarea`;
CREATE TABLE `tipos_tarea` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `factor_carga_cognitiva` decimal(5,2) DEFAULT '1.00',
  `color_hex` varchar(7) DEFAULT '#CCCCCC',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tipos_tarea`
--

LOCK TABLES `tipos_tarea` WRITE;
INSERT INTO `tipos_tarea` VALUES 
(1,'Desarrollo de Software',1.50,'#3498db'),
(2,'Reunión',0.80,'#9b59b6'),
(3,'Documentación',1.20,'#f1c40f'),
(4,'Análisis de Datos',1.80,'#e74c3c'),
(5,'Soporte Técnico',1.30,'#2ecc71');
UNLOCK TABLES;

--
-- Table structure for table `tareas`
--

DROP TABLE IF EXISTS `tareas`;
CREATE TABLE `tareas` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) NOT NULL,
  `descripcion_detallada` text,
  `trabajador_id` bigint DEFAULT NULL,
  `tipo_tarea_id` int DEFAULT NULL,
  `minutos_estimados` int DEFAULT '0',
  `minutos_reales_invertidos` int DEFAULT '0',
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_vencimiento` datetime DEFAULT NULL,
  `importancia` enum('baja','media','alta') DEFAULT 'media',
  `urgencia` enum('baja','media','alta') DEFAULT 'media',
  `estado` enum('backlog','listo','en_progreso','bloqueado','revision','terminado') DEFAULT 'backlog',
  `motivo_bloqueo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trabajador_id` (`trabajador_id`),
  KEY `tipo_tarea_id` (`tipo_tarea_id`),
  CONSTRAINT `tareas_fk_tipo` FOREIGN KEY (`tipo_tarea_id`) REFERENCES `tipos_tarea` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tareas_fk_trabajador` FOREIGN KEY (`trabajador_id`) REFERENCES `trabajador` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tareas`
--

LOCK TABLES `tareas` WRITE;
INSERT INTO `tareas` VALUES 
(1,'Implementar módulo de login','Desarrollar sistema de autenticación con roles y permisos',3,1,480,360,'2025-11-01 09:00:00','2025-11-15 17:00:00','alta','alta','terminado',NULL),
(2,'Reunión de planificación semanal','Reunión con equipo para planificar actividades de la semana',1,2,60,75,'2025-11-20 10:00:00','2025-11-20 11:00:00','media','alta','terminado',NULL),
(3,'Documentar API REST','Crear documentación técnica para APIs del sistema',3,3,240,180,'2025-11-18 14:00:00','2025-11-25 18:00:00','media','media','en_progreso',NULL),
(4,'Análisis de métricas de estrés','Analizar datos de bitácora emocional del último mes',5,4,180,0,'2025-11-21 08:00:00','2025-11-28 17:00:00','alta','media','backlog',NULL),
(5,'Soporte a usuario final','Resolver incidencias reportadas por usuarios',3,5,120,90,'2025-11-21 13:00:00','2025-11-21 15:00:00','media','alta','terminado',NULL),
(6,'Capacitación nuevo personal','Inducción para nuevos empleados del departamento',2,2,240,0,'2025-11-22 09:00:00','2025-11-22 13:00:00','alta','baja','listo',NULL),
(7,'Optimizar base de datos','Revisar y optimizar consultas SQL',3,1,360,0,'2025-11-23 10:00:00','2025-11-30 17:00:00','alta','baja','backlog',NULL),
(8,'Revisión de desempeño','Evaluación trimestral de desempeño',2,2,120,0,'2025-11-24 14:00:00','2025-11-24 16:00:00','alta','media','listo',NULL),
(9,'Desarrollo de reportes','Crear reportes gerenciales del sistema',4,1,480,0,'2025-11-25 08:00:00','2025-12-05 17:00:00','media','baja','backlog',NULL),
(10,'Análisis de mercado','Investigación de tendencias del mercado',4,4,300,0,'2025-11-26 09:00:00','2025-12-10 17:00:00','baja','baja','backlog',NULL);
UNLOCK TABLES;

--
-- Table structure for table `bitacora_emocional`
--

DROP TABLE IF EXISTS `bitacora_emocional`;
CREATE TABLE `bitacora_emocional` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `trabajador_id` bigint NOT NULL,
  `tarea_relacionada_id` bigint DEFAULT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `tipo_registro` enum('login_checkin','cierre_tarea','boton_panico','logout','auto') NOT NULL,
  `nivel_energia` tinyint DEFAULT NULL,
  `nivel_stress_percibido` tinyint DEFAULT NULL,
  `sentimiento_predominante` enum('motivado','cansado','frustrado','ansioso','satisfecho','otro') DEFAULT NULL,
  `comentario_libre` text,
  PRIMARY KEY (`id`),
  KEY `trabajador_id` (`trabajador_id`),
  KEY `tarea_relacionada_id` (`tarea_relacionada_id`),
  KEY `idx_bitacora_trabajador_fecha` (`trabajador_id`,`fecha`),
  CONSTRAINT `bitacora_fk_tarea` FOREIGN KEY (`tarea_relacionada_id`) REFERENCES `tareas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bitacora_fk_trabajador` FOREIGN KEY (`trabajador_id`) REFERENCES `trabajador` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bitacora_emocional`
--

LOCK TABLES `bitacora_emocional` WRITE;
INSERT INTO `bitacora_emocional` VALUES 
(1,3,1,'2025-11-21','08:00:00','login_checkin',8,2,'motivado','Buen comienzo de día, listo para trabajar'),
(2,3,1,'2025-11-21','12:30:00','cierre_tarea',6,4,'satisfecho','Módulo de login completado con éxito'),
(3,3,5,'2025-11-21','13:00:00','login_checkin',7,3,'motivado','Iniciando soporte técnico'),
(4,3,5,'2025-11-21','14:30:00','cierre_tarea',5,6,'frustrado','Algunos usuarios reportaron problemas complejos'),
(5,1,2,'2025-11-20','10:00:00','login_checkin',9,1,'motivado','Reunión de planificación con el equipo'),
(6,2,6,'2025-11-22','08:30:00','login_checkin',7,3,'ansioso','Preparando capacitación para nuevo personal'),
(7,5,4,'2025-11-21','09:00:00','login_checkin',6,5,'cansado','Muchos datos para analizar, día pesado');
UNLOCK TABLES;

--
-- Table structure for table `modulo`
--

DROP TABLE IF EXISTS `modulo`;
CREATE TABLE `modulo` (
  `idmodulo` bigint NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text,
  `status` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`idmodulo`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modulo`
--

LOCK TABLES `modulo` WRITE;
INSERT INTO `modulo` VALUES 
(1,'Dashboard','Vista general del sistema',1),
(2,'Usuarios','Gestión de usuarios',1),
(3,'Roles y Permisos','Gestión de encuestas',1),
(4,'Departamentos','Gestión de tareas',1),
(5,'Tareas y Carga Laboral','Indicadores y reportes',1),
(6,'Indicadores de Estrés','Reportes y métricas de nivel de estrés',1),
(7,'Bitácora Emocional','Visualización de la bitácora de trabajadores',1);
UNLOCK TABLES;

--
-- Table structure for table `permisos`
--

DROP TABLE IF EXISTS `permisos`;
CREATE TABLE `permisos` (
  `idpermiso` bigint NOT NULL AUTO_INCREMENT,
  `rolid` bigint NOT NULL,
  `moduloid` bigint NOT NULL,
  `r` tinyint NOT NULL DEFAULT '0',
  `w` tinyint NOT NULL DEFAULT '0',
  `u` tinyint NOT NULL DEFAULT '0',
  `d` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`idpermiso`),
  KEY `rolid` (`rolid`),
  KEY `moduloid` (`moduloid`),
  CONSTRAINT `permisos_fk_modulo` FOREIGN KEY (`moduloid`) REFERENCES `modulo` (`idmodulo`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `permisos_fk_rol` FOREIGN KEY (`rolid`) REFERENCES `rol` (`idrol`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permisos`
--

LOCK TABLES `permisos` WRITE;
INSERT INTO `permisos` VALUES 
(8,1,1,1,1,1,1),
(9,1,2,1,1,1,1),
(10,1,3,1,1,1,1),
(11,1,4,1,1,1,1),
(12,1,5,1,1,1,1),
(13,1,6,1,1,1,1),
(14,1,7,1,1,1,1),
(15,2,1,0,0,0,0),
(16,2,2,1,0,0,0),
(17,2,3,0,0,0,0),
(18,2,4,0,0,0,0),
(19,2,5,0,0,0,0),
(20,2,6,0,0,0,0),
(21,2,7,0,0,0,0);
UNLOCK TABLES;

-- Continuación con las demás tablas (sin datos adicionales por brevedad)

DROP TABLE IF EXISTS `dependencias_tareas`;
CREATE TABLE `dependencias_tareas` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `tarea_padre_id` bigint NOT NULL,
  `tarea_hija_id` bigint NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tarea_padre_id` (`tarea_padre_id`),
  KEY `tarea_hija_id` (`tarea_hija_id`),
  CONSTRAINT `dep_fk_hija` FOREIGN KEY (`tarea_hija_id`) REFERENCES `tareas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dep_fk_padre` FOREIGN KEY (`tarea_padre_id`) REFERENCES `tareas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `subtareas_checklist`;
CREATE TABLE `subtareas_checklist` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `tarea_id` bigint NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `completado` tinyint(1) DEFAULT '0',
  `orden` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tarea_id` (`tarea_id`),
  CONSTRAINT `subtareas_fk_tarea` FOREIGN KEY (`tarea_id`) REFERENCES `tareas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `encuestas`;
CREATE TABLE `encuestas` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text,
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `usuario_creador_persona_id` bigint DEFAULT NULL,
  `estado` enum('ACTIVA','INACTIVA','BORRADOR') DEFAULT 'ACTIVA',
  PRIMARY KEY (`id`),
  KEY `usuario_creador_persona_id` (`usuario_creador_persona_id`),
  CONSTRAINT `encuestas_fk_persona` FOREIGN KEY (`usuario_creador_persona_id`) REFERENCES `persona` (`idpersona`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `preguntas`;
CREATE TABLE `preguntas` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `encuesta_id` bigint NOT NULL,
  `texto` text NOT NULL,
  `tipo` enum('ESCALA','OPCION','TEXTO') DEFAULT 'ESCALA',
  `orden` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `encuesta_id` (`encuesta_id`),
  CONSTRAINT `preguntas_fk_encuesta` FOREIGN KEY (`encuesta_id`) REFERENCES `encuestas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `opciones_pregunta`;
CREATE TABLE `opciones_pregunta` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `pregunta_id` bigint NOT NULL,
  `texto` varchar(255) NOT NULL,
  `valor_int` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pregunta_id` (`pregunta_id`),
  CONSTRAINT `opciones_fk_pregunta` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `respuestas`;
CREATE TABLE `respuestas` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `encuesta_id` bigint NOT NULL,
  `pregunta_id` bigint NOT NULL,
  `trabajador_id` bigint NOT NULL,
  `respuesta_texto` text,
  `respuesta_valor` int DEFAULT NULL,
  `opcion_id` bigint DEFAULT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `encuesta_id` (`encuesta_id`,`trabajador_id`),
  KEY `respuestas_fk_pregunta` (`pregunta_id`),
  KEY `respuestas_fk_trabajador` (`trabajador_id`),
  KEY `respuestas_fk_opcion` (`opcion_id`),
  CONSTRAINT `respuestas_fk_encuesta` FOREIGN KEY (`encuesta_id`) REFERENCES `encuestas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `respuestas_fk_opcion` FOREIGN KEY (`opcion_id`) REFERENCES `opciones_pregunta` (`id`) ON DELETE SET NULL,
  CONSTRAINT `respuestas_fk_pregunta` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `respuestas_fk_trabajador` FOREIGN KEY (`trabajador_id`) REFERENCES `trabajador` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `indicadores_estres`;
CREATE TABLE `indicadores_estres` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `trabajador_id` bigint DEFAULT NULL,
  `departamento_id` int DEFAULT NULL,
  `nivel_estres` decimal(5,2) NOT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `metodo_calculo` varchar(100) DEFAULT 'promedio_encuesta_y_bitacora',
  `fecha_calculo` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `trabajador_id` (`trabajador_id`),
  KEY `departamento_id` (`departamento_id`),
  CONSTRAINT `indicadores_fk_departamento` FOREIGN KEY (`departamento_id`) REFERENCES `departamentos` (`id`) ON DELETE SET NULL,
  CONSTRAINT `indicadores_fk_trabajador` FOREIGN KEY (`trabajador_id`) REFERENCES `trabajador` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `intervenciones_sistema`;
CREATE TABLE `intervenciones_sistema` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `trabajador_id` bigint NOT NULL,
  `fecha_generada` datetime DEFAULT CURRENT_TIMESTAMP,
  `tipo_alerta` enum('descanso_sugerido','redistribucion_carga','alerta_burnout','felicitacion') DEFAULT NULL,
  `mensaje` text NOT NULL,
  `estado` enum('pendiente','leida','aplicada','ignorada') DEFAULT 'pendiente',
  PRIMARY KEY (`id`),
  KEY `trabajador_id` (`trabajador_id`),
  CONSTRAINT `interv_fk_trabajador` FOREIGN KEY (`trabajador_id`) REFERENCES `trabajador` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `reportes`;
CREATE TABLE `reportes` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `departamento_id` int DEFAULT NULL,
  `nivel_general_estres` decimal(5,2) DEFAULT NULL,
  `observaciones` text,
  `fecha_reporte` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `generado_por_persona_id` bigint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `departamento_id` (`departamento_id`),
  KEY `reportes_fk_persona` (`generado_por_persona_id`),
  CONSTRAINT `reportes_fk_departamento` FOREIGN KEY (`departamento_id`) REFERENCES `departamentos` (`id`) ON DELETE SET NULL,
  CONSTRAINT `reportes_fk_persona` FOREIGN KEY (`generado_por_persona_id`) REFERENCES `persona` (`idpersona`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- View structure for view `v_estres_por_departamento`
--

CREATE VIEW `v_estres_por_departamento` AS 
select `d`.`id` AS `departamento_id`,`d`.`nombre` AS `departamento`,avg(`i`.`nivel_estres`) AS `promedio_estres`,count(`i`.`id`) AS `muestras` 
from (`indicadores_estres` `i` join `departamentos` `d` on((`d`.`id` = `i`.`departamento_id`))) 
group by `d`.`id`,`d`.`nombre`;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;