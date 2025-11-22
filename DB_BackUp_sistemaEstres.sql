-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: gestion_estres
-- ------------------------------------------------------
-- Server version	9.1.0

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
-- Table structure for table `bitacora_emocional`
--

DROP TABLE IF EXISTS `bitacora_emocional`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitacora_emocional`
--

LOCK TABLES `bitacora_emocional` WRITE;
/*!40000 ALTER TABLE `bitacora_emocional` DISABLE KEYS */;
/*!40000 ALTER TABLE `bitacora_emocional` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departamentos`
--

DROP TABLE IF EXISTS `departamentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departamentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `umbral_alerta_stress` int DEFAULT '7',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departamentos`
--

LOCK TABLES `departamentos` WRITE;
/*!40000 ALTER TABLE `departamentos` DISABLE KEYS */;
INSERT INTO `departamentos` VALUES (1,'Gerencia de Sistemas','Departamento de TI/Administración del Sistema',8,'2025-11-21 21:18:36');
/*!40000 ALTER TABLE `departamentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dependencias_tareas`
--

DROP TABLE IF EXISTS `dependencias_tareas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dependencias_tareas` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `tarea_padre_id` bigint NOT NULL,
  `tarea_hija_id` bigint NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tarea_padre_id` (`tarea_padre_id`),
  KEY `tarea_hija_id` (`tarea_hija_id`),
  CONSTRAINT `dep_fk_hija` FOREIGN KEY (`tarea_hija_id`) REFERENCES `tareas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dep_fk_padre` FOREIGN KEY (`tarea_padre_id`) REFERENCES `tareas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dependencias_tareas`
--

LOCK TABLES `dependencias_tareas` WRITE;
/*!40000 ALTER TABLE `dependencias_tareas` DISABLE KEYS */;
/*!40000 ALTER TABLE `dependencias_tareas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `encuestas`
--

DROP TABLE IF EXISTS `encuestas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `encuestas`
--

LOCK TABLES `encuestas` WRITE;
/*!40000 ALTER TABLE `encuestas` DISABLE KEYS */;
/*!40000 ALTER TABLE `encuestas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `indicadores_estres`
--

DROP TABLE IF EXISTS `indicadores_estres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `indicadores_estres`
--

LOCK TABLES `indicadores_estres` WRITE;
/*!40000 ALTER TABLE `indicadores_estres` DISABLE KEYS */;
/*!40000 ALTER TABLE `indicadores_estres` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `intervenciones_sistema`
--

DROP TABLE IF EXISTS `intervenciones_sistema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `intervenciones_sistema`
--

LOCK TABLES `intervenciones_sistema` WRITE;
/*!40000 ALTER TABLE `intervenciones_sistema` DISABLE KEYS */;
/*!40000 ALTER TABLE `intervenciones_sistema` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modulo`
--

DROP TABLE IF EXISTS `modulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modulo` (
  `idmodulo` bigint NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text,
  `status` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`idmodulo`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modulo`
--

LOCK TABLES `modulo` WRITE;
/*!40000 ALTER TABLE `modulo` DISABLE KEYS */;
INSERT INTO `modulo` VALUES (1,'Dashboard','Vista general del sistema',1),(2,'Usuarios','Gestión de usuarios',1),(3,'Roles y Permisos','Gestión de encuestas',1),(4,'Departamentos','Gestión de tareas',1),(5,'Tareas y Carga Laboral','Indicadores y reportes',1),(6,'Indicadores de Estrés','Reportes y métricas de nivel de estrés',1),(7,'Bitácora Emocional','Visualización de la bitácora de trabajadores',1);
/*!40000 ALTER TABLE `modulo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opciones_pregunta`
--

DROP TABLE IF EXISTS `opciones_pregunta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `opciones_pregunta` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `pregunta_id` bigint NOT NULL,
  `texto` varchar(255) NOT NULL,
  `valor_int` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pregunta_id` (`pregunta_id`),
  CONSTRAINT `opciones_fk_pregunta` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opciones_pregunta`
--

LOCK TABLES `opciones_pregunta` WRITE;
/*!40000 ALTER TABLE `opciones_pregunta` DISABLE KEYS */;
/*!40000 ALTER TABLE `opciones_pregunta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permisos`
--

DROP TABLE IF EXISTS `permisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permisos`
--

LOCK TABLES `permisos` WRITE;
/*!40000 ALTER TABLE `permisos` DISABLE KEYS */;
INSERT INTO `permisos` VALUES (8,1,1,1,1,1,1),(9,1,2,1,1,1,1),(10,1,3,1,1,1,1),(11,1,4,1,1,1,1),(12,1,5,1,1,1,1),(13,1,6,1,1,1,1),(14,1,7,1,1,1,1),(15,2,1,0,0,0,0),(16,2,2,1,0,0,0),(17,2,3,0,0,0,0),(18,2,4,0,0,0,0),(19,2,5,0,0,0,0),(20,2,6,0,0,0,0),(21,2,7,0,0,0,0);
/*!40000 ALTER TABLE `permisos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `persona`
--

DROP TABLE IF EXISTS `persona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persona`
--

LOCK TABLES `persona` WRITE;
/*!40000 ALTER TABLE `persona` DISABLE KEYS */;
INSERT INTO `persona` VALUES (1,'7268984','Rene','Vasquez','67230415','rene@gmail.com','$2y$12$vFBpkuCp8651ZqusPFsdju71LSq6IZj0DMftmWHlasFxEH8XXOt0e','7268984','Rene','Av. Paragua Santa Cruz',NULL,1,'2025-11-21 17:18:16',1),(2,'199','Miriam','Montecinos','74939941','empleada1@gmail.com','$2y$12$rMGFae8lG98fJq5cbKgv9uAbzKiagDp2VL1gpTcRq1riRspCc5PFG','199','Nela','Oruro',NULL,2,'2025-11-21 17:56:34',1);
/*!40000 ALTER TABLE `persona` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `preguntas`
--

DROP TABLE IF EXISTS `preguntas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `preguntas` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `encuesta_id` bigint NOT NULL,
  `texto` text NOT NULL,
  `tipo` enum('ESCALA','OPCION','TEXTO') DEFAULT 'ESCALA',
  `orden` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `encuesta_id` (`encuesta_id`),
  CONSTRAINT `preguntas_fk_encuesta` FOREIGN KEY (`encuesta_id`) REFERENCES `encuestas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `preguntas`
--

LOCK TABLES `preguntas` WRITE;
/*!40000 ALTER TABLE `preguntas` DISABLE KEYS */;
/*!40000 ALTER TABLE `preguntas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reportes`
--

DROP TABLE IF EXISTS `reportes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reportes`
--

LOCK TABLES `reportes` WRITE;
/*!40000 ALTER TABLE `reportes` DISABLE KEYS */;
/*!40000 ALTER TABLE `reportes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `respuestas`
--

DROP TABLE IF EXISTS `respuestas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `respuestas`
--

LOCK TABLES `respuestas` WRITE;
/*!40000 ALTER TABLE `respuestas` DISABLE KEYS */;
/*!40000 ALTER TABLE `respuestas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rol`
--

DROP TABLE IF EXISTS `rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rol` (
  `idrol` bigint NOT NULL AUTO_INCREMENT,
  `nombrerol` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`idrol`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rol`
--

LOCK TABLES `rol` WRITE;
/*!40000 ALTER TABLE `rol` DISABLE KEYS */;
INSERT INTO `rol` VALUES (1,'Super Administrador','Acceso total al sistema',1),(2,'Supervisor','Supervisión de trabajadores',1),(3,'Empleado','Usuario operativo estándar',1);
/*!40000 ALTER TABLE `rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subtareas_checklist`
--

DROP TABLE IF EXISTS `subtareas_checklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subtareas_checklist` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `tarea_id` bigint NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `completado` tinyint(1) DEFAULT '0',
  `orden` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tarea_id` (`tarea_id`),
  CONSTRAINT `subtareas_fk_tarea` FOREIGN KEY (`tarea_id`) REFERENCES `tareas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subtareas_checklist`
--

LOCK TABLES `subtareas_checklist` WRITE;
/*!40000 ALTER TABLE `subtareas_checklist` DISABLE KEYS */;
/*!40000 ALTER TABLE `subtareas_checklist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tareas`
--

DROP TABLE IF EXISTS `tareas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tareas`
--

LOCK TABLES `tareas` WRITE;
/*!40000 ALTER TABLE `tareas` DISABLE KEYS */;
/*!40000 ALTER TABLE `tareas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipos_tarea`
--

DROP TABLE IF EXISTS `tipos_tarea`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipos_tarea` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `factor_carga_cognitiva` decimal(5,2) DEFAULT '1.00',
  `color_hex` varchar(7) DEFAULT '#CCCCCC',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipos_tarea`
--

LOCK TABLES `tipos_tarea` WRITE;
/*!40000 ALTER TABLE `tipos_tarea` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipos_tarea` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trabajador`
--

DROP TABLE IF EXISTS `trabajador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trabajador`
--

LOCK TABLES `trabajador` WRITE;
/*!40000 ALTER TABLE `trabajador` DISABLE KEYS */;
INSERT INTO `trabajador` VALUES (1,1,1,NULL,8,1,'1',0);
/*!40000 ALTER TABLE `trabajador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `v_estres_por_departamento`
--

DROP TABLE IF EXISTS `v_estres_por_departamento`;
/*!50001 DROP VIEW IF EXISTS `v_estres_por_departamento`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `v_estres_por_departamento` AS SELECT 
 1 AS `departamento_id`,
 1 AS `departamento`,
 1 AS `promedio_estres`,
 1 AS `muestras`*/;
SET character_set_client = @saved_cs_client;

--
-- Dumping routines for database 'gestion_estres'
--

--
-- Final view structure for view `v_estres_por_departamento`
--

/*!50001 DROP VIEW IF EXISTS `v_estres_por_departamento`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `v_estres_por_departamento` AS select `d`.`id` AS `departamento_id`,`d`.`nombre` AS `departamento`,avg(`i`.`nivel_estres`) AS `promedio_estres`,count(`i`.`id`) AS `muestras` from (`indicadores_estres` `i` join `departamentos` `d` on((`d`.`id` = `i`.`departamento_id`))) group by `d`.`id`,`d`.`nombre` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-21 22:25:22
