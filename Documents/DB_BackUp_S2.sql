-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: gestion_estres_2
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
-- Table structure for table `asignaciones_tareas`
--

DROP TABLE IF EXISTS `asignaciones_tareas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `asignaciones_tareas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trabajador_id` bigint unsigned NOT NULL,
  `tarea_id` bigint unsigned NOT NULL,
  `fecha_asignacion` date NOT NULL DEFAULT (curdate()),
  `fecha_limite` date DEFAULT NULL,
  `estado` enum('pendiente','en_progreso','completada','cancelada') NOT NULL DEFAULT 'pendiente',
  `observaciones` text,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_asig_trabajador` (`trabajador_id`),
  KEY `fk_asig_tarea` (`tarea_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asignaciones_tareas`
--

LOCK TABLES `asignaciones_tareas` WRITE;
/*!40000 ALTER TABLE `asignaciones_tareas` DISABLE KEYS */;
INSERT INTO `asignaciones_tareas` VALUES (1,1,1,'2025-11-20','2025-12-01','completada','Prioridad alta - módulo crítico para el sistema','2025-11-26 20:53:31'),(2,1,2,'2025-11-24','2025-11-25','completada','Reunión obligatoria con todo el equipo','2025-11-26 20:53:31'),(3,4,3,'2025-11-23','2025-12-05','en_progreso','Documentación para nuevo desarrollador','2025-11-26 20:53:31'),(4,4,4,'2025-11-25','2025-11-30','pendiente','Análisis requerido para reporte gerencial','2025-11-26 20:53:31'),(5,4,5,'2025-11-24','2025-11-26','completada','Soporte urgente para usuarios críticos','2025-11-26 20:53:31'),(6,1,6,'2025-11-22','2025-12-10','en_progreso','Testing exhaustivo requerido','2025-11-26 20:53:31'),(7,4,7,'2025-11-26','2025-12-03','pendiente','Capacitación para 5 nuevos empleados','2025-11-26 20:53:31'),(8,1,8,'2025-11-25','2025-11-28','pendiente','Revisión de código por best practices','2025-11-26 20:53:31'),(9,1,9,'2025-11-27','2025-12-15','pendiente','Planificación estratégica importante','2025-11-26 20:53:31'),(10,4,10,'2025-11-28','2025-12-20','pendiente','Mantenimiento preventivo programado','2025-11-26 20:53:31');
/*!40000 ALTER TABLE `asignaciones_tareas` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitacora_emocional`
--

LOCK TABLES `bitacora_emocional` WRITE;
/*!40000 ALTER TABLE `bitacora_emocional` DISABLE KEYS */;
INSERT INTO `bitacora_emocional` VALUES (1,1,1,'2025-11-24','08:00:00','login_checkin',8,2,'motivado','Buen comienzo de día, listo para trabajar en el módulo de login'),(2,1,1,'2025-11-24','12:30:00','cierre_tarea',6,4,'satisfecho','Módulo de login completado con éxito, buen progreso'),(3,4,3,'2025-11-24','09:00:00','login_checkin',7,3,'motivado','Iniciando documentación de APIs, día productivo por delante'),(4,4,5,'2025-11-24','11:00:00','login_checkin',6,5,'ansioso','Varios tickets de soporte pendientes, día intenso'),(5,4,5,'2025-11-24','13:30:00','cierre_tarea',5,6,'frustrado','Algunos usuarios con problemas complejos, requieren más tiempo'),(6,1,2,'2025-11-25','10:00:00','login_checkin',9,1,'motivado','Reunión de planificación, buen ambiente de equipo'),(7,1,6,'2025-11-25','14:00:00','login_checkin',7,4,'satisfecho','Avanzando con testing, encontrando algunos bugs menores'),(8,4,4,'2025-11-26','08:30:00','login_checkin',6,5,'cansado','Análisis de datos complejo, requiere mucha concentración'),(9,1,8,'2025-11-26','15:00:00','login_checkin',8,3,'motivado','Revisión de código interesante, buen aprendizaje'),(10,4,7,'2025-11-27','09:00:00','login_checkin',7,2,'satisfecho','Preparando material de capacitación, buen flujo de trabajo'),(11,1,NULL,'2025-11-26','18:16:58','login_checkin',NULL,NULL,NULL,NULL),(12,1,NULL,'2025-11-26','18:17:16','logout',NULL,NULL,NULL,NULL),(13,1,NULL,'2025-11-26','18:17:22','login_checkin',NULL,NULL,NULL,NULL),(14,1,NULL,'2025-11-26','18:20:16','login_checkin',NULL,NULL,NULL,NULL),(15,1,NULL,'2025-11-26','18:59:25','login_checkin',NULL,NULL,NULL,NULL),(16,1,NULL,'2025-11-26','18:59:51','logout',NULL,NULL,NULL,NULL),(17,1,NULL,'2025-11-26','18:59:56','login_checkin',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `bitacora_emocional` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorias_indicadores`
--

DROP TABLE IF EXISTS `categorias_indicadores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias_indicadores` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_nombre_categoria` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias_indicadores`
--

LOCK TABLES `categorias_indicadores` WRITE;
/*!40000 ALTER TABLE `categorias_indicadores` DISABLE KEYS */;
INSERT INTO `categorias_indicadores` VALUES (1,'Carga Laboral','Mide la percepción del trabajador sobre la cantidad de tareas, la presión de tiempo y el esfuerzo mental requerido.',1),(2,'Ambiente Organizacional','Evalúa el clima laboral, la cultura de la empresa, la claridad de los roles y la equidad percibida.',1),(3,'Relación con el Supervisor','Mide la calidad de la comunicación, el apoyo recibido y la confianza en el liderazgo inmediato.',1),(4,'Autonomía y Control','Mide el grado de libertad del trabajador para tomar decisiones sobre su trabajo y la influencia que tiene en el proceso.',1),(5,'Recompensa y Reconocimiento','Evalúa la satisfacción con el salario, los beneficios, y el reconocimiento por el trabajo bien hecho.',1);
/*!40000 ALTER TABLE `categorias_indicadores` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departamentos`
--

LOCK TABLES `departamentos` WRITE;
/*!40000 ALTER TABLE `departamentos` DISABLE KEYS */;
INSERT INTO `departamentos` VALUES (1,'Gerencia de Sistemas','Departamento de TI/Administración del Sistema',8,'2025-11-22 01:18:36'),(2,'Recursos Humanos','Gestión de personal y bienestar laboral',6,'2025-11-22 01:18:36'),(3,'Ventas y Marketing','Equipo comercial y promociones',7,'2025-11-22 01:18:36');
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
-- Table structure for table `encuesta_pregunta`
--

DROP TABLE IF EXISTS `encuesta_pregunta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `encuesta_pregunta` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `encuesta_id` bigint NOT NULL,
  `pregunta_id` bigint NOT NULL,
  `orden` int NOT NULL DEFAULT '0' COMMENT 'Posición de la pregunta dentro de la encuesta',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_encuesta_pregunta` (`encuesta_id`,`pregunta_id`),
  KEY `fk_encuesta_pregunta_pregunta` (`pregunta_id`),
  CONSTRAINT `fk_encuesta_pregunta_encuesta` FOREIGN KEY (`encuesta_id`) REFERENCES `encuestas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_encuesta_pregunta_pregunta` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `encuesta_pregunta`
--

LOCK TABLES `encuesta_pregunta` WRITE;
/*!40000 ALTER TABLE `encuesta_pregunta` DISABLE KEYS */;
INSERT INTO `encuesta_pregunta` VALUES (1,50,50,1),(2,50,53,2),(3,50,55,3),(4,50,57,4),(5,50,58,5),(8,10,54,1),(9,10,55,2);
/*!40000 ALTER TABLE `encuesta_pregunta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `encuesta_respondida`
--

DROP TABLE IF EXISTS `encuesta_respondida`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `encuesta_respondida` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `encuesta_id` bigint NOT NULL,
  `trabajador_id` bigint NOT NULL,
  `fecha_completada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('PENDIENTE','COMPLETADA','VENCIDA') DEFAULT 'PENDIENTE',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_encuesta_trabajador` (`encuesta_id`,`trabajador_id`),
  KEY `fk_encuesta_respondida_trabajador` (`trabajador_id`),
  CONSTRAINT `fk_encuesta_respondida_encuesta` FOREIGN KEY (`encuesta_id`) REFERENCES `encuestas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_encuesta_respondida_trabajador` FOREIGN KEY (`trabajador_id`) REFERENCES `trabajador` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `encuesta_respondida`
--

LOCK TABLES `encuesta_respondida` WRITE;
/*!40000 ALTER TABLE `encuesta_respondida` DISABLE KEYS */;
INSERT INTO `encuesta_respondida` VALUES (1,1,1,'2025-11-24 10:30:00','COMPLETADA'),(2,1,4,'2025-11-24 11:15:00','COMPLETADA'),(3,10,1,'2025-11-25 09:45:00','COMPLETADA'),(4,10,4,'2025-11-25 14:20:00','COMPLETADA'),(5,50,1,'2025-11-26 08:30:00','COMPLETADA'),(6,50,4,'2025-11-26 16:45:00','COMPLETADA'),(7,1,3,'2025-11-27 10:00:00','COMPLETADA'),(8,10,3,'2025-11-27 11:30:00','COMPLETADA'),(9,50,3,'2025-11-28 09:15:00','COMPLETADA');
/*!40000 ALTER TABLE `encuesta_respondida` ENABLE KEYS */;
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
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `usuario_creador_persona_id` bigint DEFAULT NULL,
  `estado` enum('ACTIVA','INACTIVA','BORRADOR') DEFAULT 'ACTIVA',
  PRIMARY KEY (`id`),
  KEY `usuario_creador_persona_id` (`usuario_creador_persona_id`),
  CONSTRAINT `encuestas_fk_persona` FOREIGN KEY (`usuario_creador_persona_id`) REFERENCES `persona` (`idpersona`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `encuestas`
--

LOCK TABLES `encuestas` WRITE;
/*!40000 ALTER TABLE `encuestas` DISABLE KEYS */;
INSERT INTO `encuestas` VALUES (1,'Evaluación Psicosocial Inicial','Primera evaluación de factores psicosociales obligatoria.','2025-12-01','2025-12-15','2025-11-23 18:25:43',1,'ACTIVA'),(2,'Cuestionario Piloto - Nuevas Preguntas de Liderazgo','Encuesta de prueba interna para evaluar la efectividad y pertinencia de las nuevas preguntas añadidas al banco.','2025-05-01','2025-05-15','2025-11-23 17:50:23',1,'BORRADOR'),(3,'Evaluación de Factores Psicosociales Q4 2024','Resultados utilizados para el informe final de gestión de riesgos del trimestre anterior. Cerrada para nuevas respuestas.','2024-10-01','2024-10-31','2024-09-28 10:30:00',NULL,'INACTIVA'),(10,'Evaluación Psicosocial Inicial','Primera evaluación de factores psicosociales obligatoria.','2025-12-01','2025-12-15','2025-11-23 18:29:27',1,'ACTIVA'),(50,'Evaluación Psicosocial Inicial (Prueba 5)','Primera evaluación de factores psicosociales obligatoria.','2025-12-01','2025-12-15','2025-11-23 18:32:11',1,'ACTIVA');
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `indicadores_estres`
--

LOCK TABLES `indicadores_estres` WRITE;
/*!40000 ALTER TABLE `indicadores_estres` DISABLE KEYS */;
INSERT INTO `indicadores_estres` VALUES (1,1,1,3.20,'bajo','promedio_bitacora','2025-11-24 17:00:00'),(2,4,1,4.80,'medio','promedio_bitacora','2025-11-24 17:00:00'),(3,3,2,5.20,'medio','promedio_encuesta','2025-11-24 17:00:00'),(4,1,1,2.90,'bajo','promedio_bitacora','2025-11-25 17:00:00'),(5,4,1,5.60,'medio','promedio_bitacora','2025-11-25 17:00:00'),(6,1,1,3.50,'bajo','combinado','2025-11-26 17:00:00'),(7,4,1,4.20,'medio','combinado','2025-11-26 17:00:00'),(8,3,2,6.10,'alto','promedio_encuesta','2025-11-26 17:00:00'),(9,1,1,3.10,'bajo','combinado','2025-11-27 17:00:00'),(10,4,1,4.90,'medio','combinado','2025-11-27 17:00:00');
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `intervenciones_sistema`
--

LOCK TABLES `intervenciones_sistema` WRITE;
/*!40000 ALTER TABLE `intervenciones_sistema` DISABLE KEYS */;
INSERT INTO `intervenciones_sistema` VALUES (1,4,'2025-11-24 13:45:00','descanso_sugerido','Nivel de estrés elevado detectado. Se sugiere pausa activa de 15 minutos.','pendiente'),(2,3,'2025-11-26 10:30:00','alerta_burnout','Patrón de estrés creciente detectado. Recomendación: evaluar carga de trabajo.','leida'),(3,4,'2025-11-25 16:20:00','redistribucion_carga','Multiple registro de frustración. Considerar redistribución de tareas complejas.','pendiente'),(4,1,'2025-11-27 09:15:00','felicitacion','Excelente manejo del estrés y energía consistentemente alta. ¡Buen trabajo!','aplicada'),(5,4,'2025-11-26 14:30:00','descanso_sugerido','Baja energía detectada en horas de la tarde. Recomendación: pausa para recargar.','ignorada'),(6,3,'2025-11-28 11:00:00','alerta_burnout','Niveles de estrés persistentemente altos. Se sugiere intervención del supervisor.','pendiente'),(7,4,'2025-11-27 15:45:00','redistribucion_carga','Saturación de tareas complejas detectada. Evaluar priorización.','leida'),(8,1,'2025-11-28 08:30:00','felicitacion','Manejo ejemplar del estrés bajo presión. Reconocimiento por resiliencia.','aplicada'),(9,4,'2025-11-28 13:20:00','descanso_sugerido','Patrón de estrés post-almuerzo detectado. Pausa recomendada.','pendiente'),(10,3,'2025-11-29 10:00:00','alerta_burnout','Tendencia preocupante en niveles de estrés. Intervención requerida.','leida');
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
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modulo`
--

LOCK TABLES `modulo` WRITE;
/*!40000 ALTER TABLE `modulo` DISABLE KEYS */;
INSERT INTO `modulo` VALUES (1,'Dashboard','Vista general del sistema',1),(2,'Usuarios','Gestión de usuarios',1),(3,'Roles y Permisos','Gestión de encuestas',1),(4,'Departamentos','Gestión de tareas',1),(5,'Trabajadores','Visualización de trabajadores',1),(7,'Categorías ','Categorías de indicadores',1),(11,'Banco de Preguntas','Banco de Preguntas Disponibles ',1),(12,'Encuestas de Estrés','Gestión de encuestas y asignación de preguntas',1),(30,'Tareas y Carga Laboral','Indicadores y reportes',1),(31,'Indicadores de Estrés','Reportes y métricas de nivel de estrés',1),(32,'Bitácora Emocional','Visualización de la bitácora de trabajadores',1);
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
  `texto_opcion` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `valor_numerico` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pregunta_id` (`pregunta_id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opciones_pregunta`
--

LOCK TABLES `opciones_pregunta` WRITE;
/*!40000 ALTER TABLE `opciones_pregunta` DISABLE KEYS */;
INSERT INTO `opciones_pregunta` VALUES (22,1,'Totalmente de Acuerdo',5),(23,1,'De Acuerdo',4),(24,1,'Ni de Acuerdo ni en Desacuerdo',3),(25,1,'En Desacuerdo',2),(26,1,'Totalmente en Desacuerdo',1),(34,2,'Posiblemente',1),(35,57,'Sí',5),(36,57,'No',1),(39,58,'Sí',1),(40,58,'No',0),(41,59,'Bien',1),(42,59,'Mal',2);
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
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permisos`
--

LOCK TABLES `permisos` WRITE;
/*!40000 ALTER TABLE `permisos` DISABLE KEYS */;
INSERT INTO `permisos` VALUES (78,1,1,1,1,1,1),(79,1,2,1,1,1,1),(80,1,3,1,1,1,1),(81,1,4,1,1,1,1),(82,1,5,1,1,1,1),(83,1,7,1,1,1,1),(84,1,11,1,1,1,1),(85,1,12,1,1,1,1),(86,1,30,1,1,1,1),(87,1,31,1,1,1,1),(88,1,32,1,1,1,1),(89,1,12,1,1,1,1),(91,2,1,0,0,0,0),(92,2,2,1,0,0,0),(93,2,3,1,1,0,0),(94,2,4,1,0,0,0),(95,2,5,0,1,0,0),(96,2,7,1,0,0,0),(97,2,11,0,0,0,0),(98,2,12,0,0,0,0),(99,2,30,0,0,0,0),(100,2,31,0,0,0,0),(101,2,32,0,0,0,0);
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persona`
--

LOCK TABLES `persona` WRITE;
/*!40000 ALTER TABLE `persona` DISABLE KEYS */;
INSERT INTO `persona` VALUES (1,'7268984','Rene','Vasquez','67230415','rene@gmail.com','$2y$12$vFBpkuCp8651ZqusPFsdju71LSq6IZj0DMftmWHlasFxEH8XXOt0e','7268984','Rene','Av. Paragua Santa Cruz',NULL,1,'2025-11-21 17:18:16',1),(2,'199','Miriam','Montecinos','74939941','empleada1@gmail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','199','Nela','Oruro',NULL,2,'2025-11-21 17:56:34',1),(3,'123456','Carlos','Mendoza','77788899','carlos.mendoza@empresa.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','123456','Carlos','Calle Principal 123',NULL,3,'2025-11-21 17:56:34',1),(4,'789012','Ana','Garcia','66655544','ana.garcia@empresa.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','789012','Ana','Avenida Central 456',NULL,3,'2025-11-21 17:56:34',1),(5,'345678','Luis','Fernandez','33322211','luis.fernandez@empresa.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','345678','Luis','Plaza Mayor 789',NULL,3,'2025-11-21 17:56:34',1);
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
  `categoria_id` bigint NOT NULL,
  `texto_pregunta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tipo_pregunta` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'ESCALA, OPCION, TEXTO',
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_preguntas_categoria` (`categoria_id`),
  CONSTRAINT `fk_preguntas_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_indicadores` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `preguntas`
--

LOCK TABLES `preguntas` WRITE;
/*!40000 ALTER TABLE `preguntas` DISABLE KEYS */;
INSERT INTO `preguntas` VALUES (1,1,'¿Siento que el volumen de trabajo que se me asigna es excesivo para mi jornada laboral?','ESCALA',1),(2,1,'¿Soy desesperante?','OPCION',1),(3,1,'¿Que necesitas para tener un buen ambiente laboral?','TEXTO',1),(20,1,'¿Siento que el volumen de trabajo que se me asigna es excesivo para mi jornada laboral?','ESCALA',1),(22,1,'¿Necesito trabajar horas extra para cumplir consistentemente con los plazos?','ESCALA',1),(23,2,'¿La cultura organizacional promueve un equilibrio entre el trabajo y la vida personal?','ESCALA',1),(24,3,'Mi supervisor me ofrece apoyo y retroalimentación constructiva regularmente.','ESCALA',1),(25,4,'¿Tengo suficiente autonomía para decidir cómo realizar mi trabajo?','ESCALA',1),(26,5,'¿Siento que mi salario es justo en relación con mi carga de trabajo y responsabilidades?','ESCALA',1),(27,5,'¿Recibo reconocimiento formal o informal por mis logros?','ESCALA',1),(28,2,'¿Tu departamento se siente sobrecargado?','OPCION',1),(29,4,'¿Tienes una estación de trabajo ergonómica?','OPCION',1),(50,1,'¿Siento que el volumen de trabajo que se me asigna es excesivo para mi jornada laboral?','ESCALA',1),(51,1,'¿Necesito trabajar horas extra para cumplir consistentemente con los plazos?','ESCALA',1),(52,2,'¿La cultura organizacional promueve un equilibrio entre el trabajo y la vida personal?','ESCALA',1),(53,3,'Mi supervisor me ofrece apoyo y retroalimentación constructiva regularmente.','ESCALA',1),(54,4,'¿Tengo suficiente autonomía para decidir cómo realizar mi trabajo?','ESCALA',1),(55,5,'¿Siento que mi salario es justo en relación con mi carga de trabajo y responsabilidades?','ESCALA',1),(56,5,'¿Recibo reconocimiento formal o informal por mis logros?','ESCALA',1),(57,2,'¿Tu departamento se siente sobrecargado?','OPCION',1),(58,4,'¿Tienes una estación de trabajo ergonómica?','OPCION',1),(59,1,'¿Que te parece el trabajo y el puesto que ejerces en la empresa?','OPCION',1);
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
  `persona_id` bigint NOT NULL,
  `pregunta_id` bigint NOT NULL,
  `opcion_id` bigint DEFAULT NULL,
  `valor_respuesta` int NOT NULL DEFAULT '0',
  `texto_respuesta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `persona_id` (`persona_id`),
  KEY `pregunta_id` (`pregunta_id`),
  KEY `opcion_id` (`opcion_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `respuestas`
--

LOCK TABLES `respuestas` WRITE;
/*!40000 ALTER TABLE `respuestas` DISABLE KEYS */;
INSERT INTO `respuestas` VALUES (1,1,50,22,5,NULL,'2025-11-24 10:30:00'),(2,1,53,23,4,NULL,'2025-11-24 10:30:00'),(3,1,55,24,3,NULL,'2025-11-24 10:30:00'),(4,1,57,35,5,NULL,'2025-11-24 10:30:00'),(5,1,58,37,1,NULL,'2025-11-24 10:30:00'),(6,4,50,23,4,NULL,'2025-11-24 11:15:00'),(7,4,53,22,5,NULL,'2025-11-24 11:15:00'),(8,4,55,25,2,NULL,'2025-11-24 11:15:00'),(9,4,57,36,1,NULL,'2025-11-24 11:15:00'),(10,4,58,38,5,NULL,'2025-11-24 11:15:00');
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
  `tipo_tarea_id` bigint DEFAULT NULL,
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
  CONSTRAINT `tareas_fk_trabajador` FOREIGN KEY (`trabajador_id`) REFERENCES `trabajador` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tareas`
--

LOCK TABLES `tareas` WRITE;
/*!40000 ALTER TABLE `tareas` DISABLE KEYS */;
INSERT INTO `tareas` VALUES (1,'Implementar módulo de login','Desarrollar sistema de autenticación con roles y permisos para el sistema de gestión de estrés',1,1,480,360,'2025-11-26 16:53:21','2025-12-01 17:00:00','alta','alta','terminado',NULL),(2,'Reunión planificación semanal','Reunión con equipo para planificar actividades de la semana y revisar progreso',1,2,60,75,'2025-11-26 16:53:21','2025-11-25 11:00:00','media','alta','terminado',NULL),(3,'Documentar API REST','Crear documentación técnica para APIs del sistema de bitácora emocional',4,3,240,120,'2025-11-26 16:53:21','2025-12-05 18:00:00','media','media','en_progreso',NULL),(4,'Analizar métricas de estrés','Estadísticas de niveles de estrés por departamento último mes',4,4,180,0,'2025-11-26 16:53:21','2025-11-30 17:00:00','alta','media','backlog',NULL),(5,'Soporte técnico usuarios','Resolver incidencias reportadas por usuarios del sistema',4,5,120,90,'2025-11-26 16:53:21','2025-11-26 15:00:00','media','alta','terminado',NULL),(6,'Testing módulo encuestas','Pruebas de funcionalidad del nuevo módulo de encuestas',1,6,240,180,'2025-11-26 16:53:21','2025-12-10 17:00:00','media','baja','en_progreso',NULL),(7,'Capacitación nuevo personal','Inducción sobre uso del sistema de gestión de estrés',4,7,180,0,'2025-11-26 16:53:21','2025-12-03 13:00:00','alta','baja','listo',NULL),(8,'Revisión código bitácora','Code review del módulo de bitácora emocional',1,8,120,0,'2025-11-26 16:53:21','2025-11-28 16:00:00','media','media','listo',NULL),(9,'Planificación Q1 2025','Planificación de objetivos y proyectos para primer trimestre 2025',1,9,300,0,'2025-11-26 16:53:21','2025-12-15 17:00:00','alta','baja','backlog',NULL),(10,'Mantenimiento servidor','Actualización y mantenimiento preventivo del servidor',4,10,240,0,'2025-11-26 16:53:21','2025-12-20 17:00:00','alta','media','backlog',NULL);
/*!40000 ALTER TABLE `tareas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipos_tarea`
--

DROP TABLE IF EXISTS `tipos_tarea`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipos_tarea` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `factor_carga_cognitiva` decimal(5,2) DEFAULT '1.00',
  `color_hex` varchar(7) DEFAULT '#CCCCCC',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipos_tarea`
--

LOCK TABLES `tipos_tarea` WRITE;
/*!40000 ALTER TABLE `tipos_tarea` DISABLE KEYS */;
INSERT INTO `tipos_tarea` VALUES (1,'Desarrollo de Software',1.50,'#3498db'),(2,'Reunión de Equipo',0.80,'#9b59b6'),(3,'Documentación Técnica',1.20,'#f1c40f'),(4,'Análisis de Datos',1.80,'#e74c3c'),(5,'Soporte Técnico',1.30,'#2ecc71'),(6,'Testing QA',1.25,'#e67e22'),(7,'Capacitación',0.90,'#1abc9c'),(8,'Revisión de Código',1.15,'#34495e'),(9,'Planificación de Proyecto',1.10,'#8e44ad'),(10,'Mantenimiento Sistema',1.40,'#d35400');
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trabajador`
--

LOCK TABLES `trabajador` WRITE;
/*!40000 ALTER TABLE `trabajador` DISABLE KEYS */;
INSERT INTO `trabajador` VALUES (1,1,NULL,'2025-02-10',8,1,'Técnico de Sistemas',1),(2,2,2,'2023-03-20',8,1,'Supervisora de RH',1),(3,3,1,'2023-06-10',8,1,'Desarrollador Senior',1),(4,4,3,'2023-02-15',8,1,'Ejecutivo de Ventas',1),(5,5,2,'2023-08-05',8,1,'Analista de RH',2);
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
-- Dumping routines for database 'gestion_estres_2'
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

-- Dump completed on 2025-11-26 19:04:37
