-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 01, 2025 at 05:05 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `futmatch_db`
--
CREATE DATABASE IF NOT EXISTS `futmatch_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `futmatch_db`;

-- --------------------------------------------------------

--
-- Table structure for table `admin_canchas`
--

DROP TABLE IF EXISTS `admin_canchas`;
CREATE TABLE `admin_canchas` (
  `id_admin_cancha` int(11) NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  `telefono` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_canchas`
--

INSERT INTO `admin_canchas` (`id_admin_cancha`, `id_solicitud`, `telefono`) VALUES
(1, 3, NULL),
(2, 4, NULL),
(8, 1, '1155669988'),
(9, 2, '1166554477');

-- --------------------------------------------------------

--
-- Table structure for table `admin_sistema`
--

DROP TABLE IF EXISTS `admin_sistema`;
CREATE TABLE `admin_sistema` (
  `id_admin_sistema` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_sistema`
--

INSERT INTO `admin_sistema` (`id_admin_sistema`, `id_usuario`) VALUES
(1, 7);

-- --------------------------------------------------------

--
-- Table structure for table `calificaciones_jugadores`
--

DROP TABLE IF EXISTS `calificaciones_jugadores`;
CREATE TABLE `calificaciones_jugadores` (
  `id_calificacion` int(11) NOT NULL,
  `id_partido` int(11) NOT NULL,
  `id_jugador_evaluador` int(11) NOT NULL,
  `id_jugador_evaluado` int(11) NOT NULL,
  `puntuacion` int(11) NOT NULL,
  `reportado` tinyint(1) NOT NULL,
  `comentario` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `calificaciones_jugadores`
--

INSERT INTO `calificaciones_jugadores` (`id_calificacion`, `id_partido`, `id_jugador_evaluador`, `id_jugador_evaluado`, `puntuacion`, `reportado`, `comentario`) VALUES
(1, 4, 3, 4, 4, 0, 'Excelente partido! Me encanta jugar con Ana. '),
(2, 1, 1, 4, 5, 0, 'Ana fue solicitante en mi partido. Desde un principio fue muy respetuosa y fue muy ameno jugar con ella. ');

--
-- Triggers `calificaciones_jugadores`
--
DROP TRIGGER IF EXISTS `actualizar_reputacion_jugador`;
DELIMITER $$
CREATE TRIGGER `actualizar_reputacion_jugador` AFTER INSERT ON `calificaciones_jugadores` FOR EACH ROW BEGIN
    UPDATE jugadores 
    SET reputacion = (
        SELECT AVG(puntuacion) 
        FROM calificaciones_jugadores 
        WHERE id_jugador_evaluado = NEW.id_jugador_evaluado
    )
    WHERE id_jugador = NEW.id_jugador_evaluado;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `canchas`
--

DROP TABLE IF EXISTS `canchas`;
CREATE TABLE `canchas` (
  `id_cancha` int(11) NOT NULL,
  `id_admin_cancha` int(11) NOT NULL,
  `id_direccion` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `id_estado` int(11) NOT NULL DEFAULT 1,
  `foto` varchar(255) DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `id_superficie` int(11) DEFAULT NULL,
  `politicas_reservas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `canchas`
--

INSERT INTO `canchas` (`id_cancha`, `id_admin_cancha`, `id_direccion`, `nombre`, `descripcion`, `telefono`, `id_estado`, `foto`, `banner`, `id_superficie`, `politicas_reservas`) VALUES
(1, 8, 1, 'Cancha Centro', 'Cancha de fútbol 5 en el centro de la ciudad', NULL, 3, NULL, NULL, 1, '- Reserva mínima con 24 horas de anticipacion\n- Cancelación gratuita hasta 12 horas antes\n- Depósito del 50% al confirmar la reserva (comunicarse con Wpp para hacer la misma)'),
(2, 9, 2, 'Cancha Norte', 'Complejo deportivo con múltiples canchas', NULL, 3, NULL, NULL, 1, NULL),
(3, 8, 4, 'Cancha Centro 2', 'Segunda sede del complejo centro', NULL, 3, NULL, NULL, 2, NULL),
(5, 9, 7, 'Cancha Sur', 'Test Descripcion', NULL, 1, NULL, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `canchas_tipos_partido`
--

DROP TABLE IF EXISTS `canchas_tipos_partido`;
CREATE TABLE `canchas_tipos_partido` (
  `id_cancha` int(11) NOT NULL,
  `id_tipo_partido` int(11) NOT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_habilitacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `canchas_tipos_partido`
--

INSERT INTO `canchas_tipos_partido` (`id_cancha`, `id_tipo_partido`, `activo`, `fecha_habilitacion`) VALUES
(1, 1, 1, '2025-11-13 00:16:20'),
(2, 1, 1, '2025-11-13 00:16:20'),
(3, 4, 1, '2025-11-13 00:16:20'),
(5, 1, 1, '2025-11-24 14:51:18');

-- --------------------------------------------------------

--
-- Table structure for table `dias_semana`
--

DROP TABLE IF EXISTS `dias_semana`;
CREATE TABLE `dias_semana` (
  `id_dia` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dias_semana`
--

INSERT INTO `dias_semana` (`id_dia`, `nombre`) VALUES
(1, 'lunes'),
(2, 'martes'),
(3, 'miercoles'),
(4, 'jueves'),
(5, 'viernes'),
(6, 'sabado'),
(7, 'domingo');

-- --------------------------------------------------------

--
-- Table structure for table `direcciones`
--

DROP TABLE IF EXISTS `direcciones`;
CREATE TABLE `direcciones` (
  `id_direccion` int(11) NOT NULL,
  `direccion_completa` varchar(500) NOT NULL,
  `latitud` decimal(10,8) NOT NULL,
  `longitud` decimal(11,8) NOT NULL,
  `pais` varchar(100) DEFAULT NULL,
  `provincia` varchar(100) DEFAULT NULL,
  `localidad` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `direcciones`
--

INSERT INTO `direcciones` (`id_direccion`, `direccion_completa`, `latitud`, `longitud`, `pais`, `provincia`, `localidad`) VALUES
(1, 'Av. Corrientes 1234, Buenos Aires', -34.60385100, -58.38177500, 'Argentina', 'Buenos Aires', 'CABA'),
(2, 'San Martín 567, La Plata', -34.92131200, -57.95456700, 'Argentina', 'Buenos Aires', 'La Plata'),
(3, 'Mitre 890, Rosario', -32.94432100, -60.65054300, 'Argentina', 'Santa Fe', 'Rosario'),
(4, 'Belgrano 445, Córdoba', -31.41677500, -64.18344100, 'Argentina', 'Córdoba', 'Córdoba'),
(5, '9 de Julio 123, Mendoza', -32.88945800, -68.84583900, 'Argentina', 'Mendoza', 'Mendoza'),
(6, 'Presbítero Juan González y Aragón, Barrio Uno, Aeropuerto Internacional Ezeiza, Partido de Ezeiza, Buenos Aires, B1802, Argentina', -34.78115850, -58.53894340, 'Argentina', 'Buenos Aires', 'Aeropuerto Internacional Ezeiza'),
(7, 'Avenida Independencia 750', 0.00000000, 0.00000000, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `equipos`
--

DROP TABLE IF EXISTS `equipos`;
CREATE TABLE `equipos` (
  `id_equipo` int(11) NOT NULL,
  `id_lider` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `foto` varchar(250) DEFAULT NULL,
  `clave` varchar(10) DEFAULT NULL,
  `abierto` tinyint(1) DEFAULT 0,
  `descripcion` varchar(200) DEFAULT NULL,
  `creado_por` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `equipos`
--

INSERT INTO `equipos` (`id_equipo`, `id_lider`, `nombre`, `foto`, `clave`, `abierto`, `descripcion`, `creado_por`, `fecha_creacion`) VALUES
(1, 1, 'Los Tigres FC', NULL, '1536', 1, NULL, 3, '2025-11-16 20:52:27'),
(2, 3, 'Águilas Rojas', NULL, '1598', 0, NULL, 2, '2025-11-16 20:52:27'),
(3, 5, 'Deportivo Unión', NULL, '6584', 1, NULL, 1, '2025-11-16 20:52:27'),
(4, 4, 'Domingueross', NULL, NULL, 1, 'Nos juntamos a jugar futbol los domingos en CABA', 6, '2025-11-16 20:52:27'),
(6, 4, 'Homero', NULL, NULL, 1, '', 4, '2025-11-17 00:49:58'),
(7, 10, 'Fulboleros', NULL, NULL, 1, 'Jugamos al futbol y cantamos boleros', 10, '2025-11-21 19:02:31');

-- --------------------------------------------------------

--
-- Table structure for table `equipos_partidos`
--

DROP TABLE IF EXISTS `equipos_partidos`;
CREATE TABLE `equipos_partidos` (
  `id_equipo` int(11) NOT NULL,
  `id_partido` int(11) NOT NULL,
  `es_ganador` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `equipos_partidos`
--

INSERT INTO `equipos_partidos` (`id_equipo`, `id_partido`, `es_ganador`) VALUES
(1, 4, 0),
(2, 4, 0);

--
-- Triggers `equipos_partidos`
--
DROP TRIGGER IF EXISTS `validate_equipos_partido`;
DELIMITER $$
CREATE TRIGGER `validate_equipos_partido` BEFORE INSERT ON `equipos_partidos` FOR EACH ROW BEGIN
    DECLARE equipos_count INT;
    SELECT COUNT(*) INTO equipos_count 
    FROM equipos_partidos 
    WHERE id_partido = NEW.id_partido;
    
    IF equipos_count >= 2 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Un partido no puede tener más de 2 equipos';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `equipos_torneos`
--

DROP TABLE IF EXISTS `equipos_torneos`;
CREATE TABLE `equipos_torneos` (
  `id_equipo` int(11) NOT NULL,
  `id_torneo` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `equipos_torneos`
--

INSERT INTO `equipos_torneos` (`id_equipo`, `id_torneo`, `id_estado`) VALUES
(3, 1, 1),
(1, 1, 3),
(1, 2, 3),
(2, 1, 3),
(3, 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `estadisticas_partido`
--

DROP TABLE IF EXISTS `estadisticas_partido`;
CREATE TABLE `estadisticas_partido` (
  `id_estadistica` int(11) NOT NULL,
  `id_partido` int(11) NOT NULL,
  `id_participante` int(11) NOT NULL,
  `goles` int(11) DEFAULT NULL,
  `asistencias` int(11) DEFAULT NULL,
  `faltas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `estadisticas_partido`
--

INSERT INTO `estadisticas_partido` (`id_estadistica`, `id_partido`, `id_participante`, `goles`, `asistencias`, `faltas`) VALUES
(1, 4, 3, 1, 0, 0),
(2, 1, 11, 0, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `estados_canchas`
--

DROP TABLE IF EXISTS `estados_canchas`;
CREATE TABLE `estados_canchas` (
  `id_estado` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `estados_canchas`
--

INSERT INTO `estados_canchas` (`id_estado`, `nombre`) VALUES
(1, 'Pendiente de verificación'),
(2, 'En revisión'),
(3, 'Habilitada'),
(4, 'Deshabilitada'),
(5, 'Suspendida');

-- --------------------------------------------------------

--
-- Table structure for table `estados_solicitudes`
--

DROP TABLE IF EXISTS `estados_solicitudes`;
CREATE TABLE `estados_solicitudes` (
  `id_estado` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `estados_solicitudes`
--

INSERT INTO `estados_solicitudes` (`id_estado`, `nombre`) VALUES
(1, 'Pendiente'),
(2, 'En revisión'),
(3, 'Aceptada'),
(4, 'Rechazada'),
(5, 'Cancelada'),
(6, 'Ausente');

-- --------------------------------------------------------

--
-- Table structure for table `estados_usuarios`
--

DROP TABLE IF EXISTS `estados_usuarios`;
CREATE TABLE `estados_usuarios` (
  `id_estado` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `estados_usuarios`
--

INSERT INTO `estados_usuarios` (`id_estado`, `nombre`) VALUES
(1, 'Activo'),
(2, 'Inactivo'),
(3, 'Suspendido');

-- --------------------------------------------------------

--
-- Table structure for table `etapas_torneo`
--

DROP TABLE IF EXISTS `etapas_torneo`;
CREATE TABLE `etapas_torneo` (
  `id_etapa` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `etapas_torneo`
--

INSERT INTO `etapas_torneo` (`id_etapa`, `nombre`) VALUES
(1, 'borrador'),
(2, 'inscripciones abiertas'),
(3, 'en curso'),
(4, 'finalizado');

-- --------------------------------------------------------

--
-- Table structure for table `fases_torneo`
--

DROP TABLE IF EXISTS `fases_torneo`;
CREATE TABLE `fases_torneo` (
  `id_fase` int(11) NOT NULL,
  `n` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fases_torneo`
--

INSERT INTO `fases_torneo` (`id_fase`, `n`, `nombre`, `descripcion`) VALUES
(1, 0, 'Ganador', 'Equipo campeón del torneo'),
(2, 1, 'Final', 'Partido final entre los 2 últimos equipos'),
(3, 2, 'Semifinal', 'Partidos entre los 4 últimos equipos'),
(4, 3, 'Cuartos de Final', 'Partidos entre los 8 últimos equipos'),
(5, 4, 'Octavos de Final', 'Partidos entre los 16 últimos equipos'),
(6, 5, 'Eliminatorias', 'Fase de eliminación directa inicial');

-- --------------------------------------------------------

--
-- Table structure for table `horarios_cancha`
--

DROP TABLE IF EXISTS `horarios_cancha`;
CREATE TABLE `horarios_cancha` (
  `id_horario` int(11) NOT NULL,
  `id_cancha` int(11) NOT NULL,
  `id_dia` int(11) NOT NULL,
  `hora_apertura` time DEFAULT NULL,
  `hora_cierre` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `horarios_cancha`
--

INSERT INTO `horarios_cancha` (`id_horario`, `id_cancha`, `id_dia`, `hora_apertura`, `hora_cierre`) VALUES
(8, 2, 1, '07:00:00', '23:00:00'),
(9, 2, 2, '07:00:00', '23:00:00'),
(10, 2, 3, '07:00:00', '23:00:00'),
(11, 2, 4, '07:00:00', '23:00:00'),
(12, 2, 5, '07:00:00', '23:00:00'),
(13, 2, 6, '08:00:00', '24:00:00'),
(14, 2, 7, '08:00:00', '24:00:00'),
(15, 3, 1, '09:00:00', '21:00:00'),
(16, 3, 2, '09:00:00', '21:00:00'),
(17, 3, 3, '09:00:00', '21:00:00'),
(18, 3, 4, '09:00:00', '21:00:00'),
(19, 3, 5, '09:00:00', '21:00:00'),
(34, 1, 1, '08:00:00', '22:00:00'),
(35, 1, 2, '08:00:00', '22:00:00'),
(36, 1, 3, NULL, NULL),
(37, 1, 4, '08:00:00', '22:00:00'),
(38, 1, 5, '08:00:00', '22:00:00'),
(39, 1, 6, '09:00:00', '13:00:00'),
(40, 1, 7, '09:00:00', '14:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `jugadores`
--

DROP TABLE IF EXISTS `jugadores`;
CREATE TABLE `jugadores` (
  `id_jugador` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `fecha_nacimiento` date NOT NULL,
  `id_sexo` int(11) NOT NULL,
  `id_posicion` int(11) DEFAULT NULL,
  `reputacion` float DEFAULT NULL,
  `descripcion` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jugadores`
--

INSERT INTO `jugadores` (`id_jugador`, `username`, `telefono`, `foto_perfil`, `banner`, `fecha_nacimiento`, `id_sexo`, `id_posicion`, `reputacion`, `descripcion`) VALUES
(1, 'juanpe', '+541123456789', NULL, NULL, '1995-03-15', 2, 3, NULL, NULL),
(2, 'mariag', '+541123456790', NULL, NULL, '1992-07-20', 1, 2, NULL, NULL),
(3, 'carlosl', '+541123456791', NULL, NULL, '1988-11-05', 2, 1, NULL, NULL),
(4, 'anam', '+541123456792', NULL, NULL, '1996-09-12', 1, 4, 4.5, NULL),
(5, 'diegor', '+541123456793', NULL, NULL, '1990-01-25', 2, 2, NULL, NULL),
(6, 'lauraf', '+541123456794', NULL, NULL, '1994-05-30', 1, 3, NULL, NULL),
(10, 'cnsanto', '1154876550', NULL, NULL, '1999-07-16', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jugadores_equipos`
--

DROP TABLE IF EXISTS `jugadores_equipos`;
CREATE TABLE `jugadores_equipos` (
  `id_jugador` int(11) NOT NULL,
  `id_equipo` int(11) NOT NULL,
  `estado_solicitud` int(11) NOT NULL,
  `invitado_por` int(11) DEFAULT NULL,
  `fecha_act` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jugadores_equipos`
--

INSERT INTO `jugadores_equipos` (`id_jugador`, `id_equipo`, `estado_solicitud`, `invitado_por`, `fecha_act`) VALUES
(1, 1, 3, 1, '2025-11-17 01:00:45'),
(1, 3, 3, 4, '2025-11-17 01:00:45'),
(1, 4, 1, 4, '2025-11-17 01:50:19'),
(1, 6, 3, 4, '2025-11-17 01:39:29'),
(2, 1, 3, 1, '2025-11-17 01:00:45'),
(2, 4, 4, 4, '2025-11-17 02:19:43'),
(2, 7, 3, 10, '2025-11-22 01:59:25'),
(3, 2, 3, 2, '2025-11-17 01:00:45'),
(4, 1, 3, 2, '2025-11-17 01:00:45'),
(4, 4, 3, 6, '2025-11-17 01:00:45'),
(4, 6, 3, NULL, '2025-11-17 01:00:45'),
(5, 2, 3, 3, '2025-11-17 01:00:45'),
(5, 3, 3, 2, '2025-11-17 01:00:45'),
(6, 3, 3, 1, '2025-11-17 01:00:45'),
(6, 4, 1, 4, '2025-11-17 01:00:45'),
(10, 7, 3, NULL, '2025-11-21 19:02:31');

-- --------------------------------------------------------

--
-- Table structure for table `observaciones_canchas`
--

DROP TABLE IF EXISTS `observaciones_canchas`;
CREATE TABLE `observaciones_canchas` (
  `id_observacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `observaciones` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `participantes_partidos`
--

DROP TABLE IF EXISTS `participantes_partidos`;
CREATE TABLE `participantes_partidos` (
  `id_participante` int(11) NOT NULL,
  `id_partido` int(11) NOT NULL,
  `id_jugador` int(11) DEFAULT NULL,
  `nombre_invitado` varchar(100) DEFAULT NULL,
  `id_rol` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL DEFAULT 1,
  `equipo` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `participantes_partidos`
--

INSERT INTO `participantes_partidos` (`id_participante`, `id_partido`, `id_jugador`, `nombre_invitado`, `id_rol`, `id_estado`, `equipo`) VALUES
(1, 1, 1, NULL, 1, 3, 1),
(2, 1, 2, NULL, 2, 3, 2),
(3, 1, 4, NULL, 3, 3, 2),
(4, 2, 3, NULL, 1, 3, 1),
(5, 2, 5, NULL, 2, 3, 1),
(6, 3, 5, NULL, 1, 3, 1),
(7, 4, 1, NULL, 1, 3, 1),
(8, 4, 3, NULL, 2, 3, 2),
(9, 4, 5, NULL, 2, 3, 2),
(10, 4, 2, NULL, 2, 3, 1),
(11, 4, 4, NULL, 2, 3, 1),
(12, 2, 1, NULL, 3, 1, 1),
(13, 2, 4, NULL, 3, 6, 2),
(14, 2, 6, NULL, 3, 1, 1),
(15, 7, 1, NULL, 1, 3, 1),
(16, 9, 6, NULL, 1, 3, 1),
(17, 8, 4, NULL, 1, 3, 2),
(18, 8, 1, NULL, 3, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `partidos`
--

DROP TABLE IF EXISTS `partidos`;
CREATE TABLE `partidos` (
  `id_partido` int(11) NOT NULL,
  `id_anfitrion` int(11) NOT NULL,
  `id_tipo_partido` int(11) NOT NULL DEFAULT 1,
  `abierto` tinyint(1) DEFAULT 0,
  `goles_equipo_A` int(10) DEFAULT NULL,
  `goles_equipo_B` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partidos`
--

INSERT INTO `partidos` (`id_partido`, `id_anfitrion`, `id_tipo_partido`, `abierto`, `goles_equipo_A`, `goles_equipo_B`) VALUES
(1, 1, 1, 0, 1, 1),
(2, 3, 1, 1, 3, 0),
(3, 5, 2, 1, 0, 0),
(4, 1, 1, 0, 1, 2),
(5, 1, 3, 0, 0, 0),
(7, 1, 1, 0, 0, 0),
(8, 4, 1, 1, NULL, NULL),
(9, 6, 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `partidos_reservas`
--

DROP TABLE IF EXISTS `partidos_reservas`;
CREATE TABLE `partidos_reservas` (
  `id_partido` int(11) NOT NULL,
  `id_reserva` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partidos_reservas`
--

INSERT INTO `partidos_reservas` (`id_partido`, `id_reserva`) VALUES
(1, 1),
(2, 3),
(7, 6),
(8, 8),
(9, 7);

-- --------------------------------------------------------

--
-- Table structure for table `partidos_torneos`
--

DROP TABLE IF EXISTS `partidos_torneos`;
CREATE TABLE `partidos_torneos` (
  `id_partido_torneo` int(11) NOT NULL,
  `id_partido` int(11) NOT NULL,
  `id_torneo` int(11) NOT NULL,
  `id_fase` int(11) NOT NULL,
  `orden_en_fase` int(11) DEFAULT NULL,
  `id_equipo_A` int(11) NOT NULL,
  `id_equipo_B` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permisos`
--

DROP TABLE IF EXISTS `permisos`;
CREATE TABLE `permisos` (
  `id_permiso` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permisos`
--

INSERT INTO `permisos` (`id_permiso`, `nombre`) VALUES
(1, 'Modificar detalles de cuenta'),
(2, 'Eliminar cuenta de usuario'),
(3, 'Suspender cuenta de usuario'),
(4, 'Solicitar reserva de cancha'),
(5, 'Solicitar participación en partido'),
(6, 'Editar perfil de Jugador'),
(7, 'Obtener listado de Equipos abiertos'),
(8, 'Solicitar unirse a un Equipo'),
(9, 'Crear un Equipo'),
(10, 'Ver perfil de Equipo'),
(11, 'Calificar jugador'),
(12, 'Cancelar participación en un partido'),
(13, 'Ver partidos vinculados a mi perfil'),
(14, 'Ver detalle de partido vinculado a mi perfil'),
(15, 'Ver historial de partidos'),
(16, 'Ver detalle post-partido'),
(17, 'Compartir partido jugado en redes sociales'),
(18, 'Calificar cancha'),
(19, 'Invitar jugador a tu Equipo'),
(20, 'Solicitar participación en un Torneo'),
(21, 'Aceptar o rechazar solicitante'),
(22, 'Modificar detalles del partido'),
(23, 'Agregar estadísticas del partido'),
(24, 'Eliminar solicitante previamente aceptado'),
(25, 'Solicitar modificación de reserva'),
(26, 'Obtener listado de canchas vinculadas a mi cuenta'),
(27, 'Crear cancha'),
(28, 'Modificar cancha'),
(29, 'Modificar perfil de cancha'),
(30, 'Eliminar cancha'),
(31, 'Crear un torneo'),
(32, 'Ver historial de torneos'),
(33, 'Modificar un torneo'),
(34, 'Cancelar un torneo'),
(35, 'Ver solicitudes de participación de Equipos en un torneo'),
(36, 'Aceptar o rechazar participación de Equipos en un torneo'),
(37, 'Abrir convocatorias al torneo'),
(38, 'Cerrar convocatorias al torneo'),
(39, 'Agendar partidos de un torneo'),
(40, 'Ver solicitudes de Reserva'),
(41, 'Aceptar o rechazar solicitudes de Reserva'),
(42, 'Ver detalle de reserva'),
(43, 'Cancelar reserva'),
(44, 'Modificar reserva'),
(45, 'Crear reserva'),
(46, 'Ver agenda'),
(47, 'Ver historial de reservas'),
(48, 'Configurar horario de cancha'),
(49, 'Obtener listado de solicitudes de admin de cancha'),
(50, 'Ver solicitud de admin de cancha'),
(51, 'Obtener listado de jugadores registrados'),
(52, 'Ver detalle de partidos de jugadores registrados'),
(53, 'Obtener reportes o denuncias de jugadores'),
(54, 'Obtener reportes de canchas'),
(55, 'Verificar solicitud de admin de cancha'),
(56, 'Aceptar o rechazar solicitud de admin de cancha'),
(57, 'Inhabilitar cancha'),
(58, 'Habilitar cancha'),
(59, 'Suspender cuenta de jugador'),
(60, 'Restablecer cuenta de jugador'),
(61, 'Suspender cuenta de admin de cancha'),
(62, 'Restablecer cuenta de admin de cancha');

-- --------------------------------------------------------

--
-- Table structure for table `personas_externas`
--

DROP TABLE IF EXISTS `personas_externas`;
CREATE TABLE `personas_externas` (
  `id_externo` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personas_externas`
--

INSERT INTO `personas_externas` (`id_externo`, `nombre`, `apellido`, `telefono`) VALUES
(3, 'Patricio', 'Conte', '+541166558855');

-- --------------------------------------------------------

--
-- Table structure for table `posiciones`
--

DROP TABLE IF EXISTS `posiciones`;
CREATE TABLE `posiciones` (
  `id_posicion` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posiciones`
--

INSERT INTO `posiciones` (`id_posicion`, `nombre`) VALUES
(1, 'arquero'),
(2, 'defensor'),
(3, 'mediocampista'),
(4, 'delantero');

-- --------------------------------------------------------

--
-- Table structure for table `resenias_canchas`
--

DROP TABLE IF EXISTS `resenias_canchas`;
CREATE TABLE `resenias_canchas` (
  `id_resenia` int(11) NOT NULL,
  `id_cancha` int(11) NOT NULL,
  `id_jugador` int(11) NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `texto` varchar(500) DEFAULT NULL,
  `calificacion` int(11) NOT NULL,
  `foto` varchar(250) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `resenias_canchas`
--

INSERT INTO `resenias_canchas` (`id_resenia`, `id_cancha`, `id_jugador`, `titulo`, `texto`, `calificacion`, `foto`, `fecha`) VALUES
(1, 1, 2, 'Muy buena cancha', 'Excelente estado del césped sintético', 5, NULL, '2025-11-13 00:16:20'),
(2, 1, 4, 'Recomendable', 'Buenos vestuarios y estacionamiento', 4, NULL, '2025-11-13 00:16:20'),
(3, 2, 1, 'Complejo completo', 'Tiene todo lo que necesitas', 5, NULL, '2025-11-13 00:16:20'),
(4, 2, 6, 'Buen lugar', 'Aunque un poco caro', 4, NULL, '2025-11-13 00:16:20');

-- --------------------------------------------------------

--
-- Table structure for table `reservas`
--

DROP TABLE IF EXISTS `reservas`;
CREATE TABLE `reservas` (
  `id_reserva` int(11) NOT NULL,
  `id_cancha` int(11) NOT NULL,
  `id_tipo_reserva` int(11) NOT NULL DEFAULT 1,
  `fecha` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `titulo` varchar(50) DEFAULT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `id_estado` int(11) NOT NULL DEFAULT 1,
  `fecha_solicitud` datetime DEFAULT current_timestamp(),
  `id_creador_usuario` int(11) NOT NULL,
  `id_titular_jugador` int(11) DEFAULT NULL,
  `id_titular_externo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reservas`
--

INSERT INTO `reservas` (`id_reserva`, `id_cancha`, `id_tipo_reserva`, `fecha`, `fecha_fin`, `hora_inicio`, `hora_fin`, `titulo`, `descripcion`, `id_estado`, `fecha_solicitud`, `id_creador_usuario`, `id_titular_jugador`, `id_titular_externo`) VALUES
(1, 1, 1, '2025-11-15', '2025-11-15', '18:00:00', '19:00:00', 'Test', '', 3, '2025-11-13 00:16:20', 1, 1, NULL),
(2, 1, 1, '2025-11-16', '2025-11-16', '20:00:00', '21:00:00', 'Partido Casual', '', 1, '2025-11-13 00:16:20', 3, 3, NULL),
(3, 2, 1, '2025-11-17', '2025-11-17', '19:00:00', '20:00:00', 'Fútbol Femenino', 'Partido amistoso para pasar un buen rato. Todos los niveles son bienvenidos. Se juega con reglas estándar de fútbol 5.', 3, '2025-11-13 00:16:20', 5, 5, NULL),
(4, 2, 1, '2025-11-18', '2025-11-18', '21:00:00', '22:00:00', NULL, '', 1, '2025-11-13 00:16:20', 2, 2, NULL),
(6, 1, 1, '2025-11-28', '2025-11-28', '15:00:00', '18:00:00', 'Partido fin de año', 'Nos juntamos a jugar un partidito y después para el que quiera unas birras en el bar de enfrente', 3, '2025-11-24 15:54:06', 1, 1, NULL),
(7, 3, 1, '2025-11-30', '2025-11-30', '11:00:00', '18:00:00', 'Partido Dominguero', 'El que quiera participar por favor contactarme al +541174589685', 3, '2025-11-24 16:08:17', 6, 6, NULL),
(8, 5, 1, '2025-12-08', '2025-12-08', '16:00:00', '19:00:00', 'Feriado', 'Festejamos el feriado jugando al futbol', 3, '2025-11-24 16:10:09', 4, 4, NULL),
(11, 1, 1, '2025-12-16', '2025-12-16', '12:00:00', '15:00:00', 'Partido Amistoso', 'Juan es cliente regular.', 3, '2025-11-30 22:55:16', 8, 1, NULL),
(12, 3, 1, '2025-12-20', '2025-12-20', '11:00:00', '15:00:00', 'Fiesta de cumpleaños', 'Es para el hijo de Juan', 3, '2025-11-30 23:37:45', 8, 1, NULL),
(13, 1, 4, '2025-12-20', '2025-12-20', '11:00:00', '16:00:00', 'Fiesta de cumpleaños', 'Es para el hijo de Juan y sus compañeros de colegio (aprox 16 años).', 3, '2025-11-30 23:39:15', 8, 1, NULL),
(14, 1, 4, '2025-01-20', '2025-01-20', '15:00:00', '18:00:00', 'Escuelita niños', 'Escuelita de futbol para niños de 11 años', 3, '2025-11-30 23:47:22', 8, NULL, 3),
(15, 1, 1, '2025-12-22', '2025-12-22', '10:00:00', '15:00:00', 'Partido Escuela Grilli', 'Reservaron para utilizar el predio el turno de Ed. Física', 3, '2025-12-01 00:02:07', 8, 3, NULL),
(17, 1, 4, '2025-12-19', '2025-12-19', '11:00:00', '18:00:00', 'Fiesta de cumpleaños', 'El cumpleañero es el hijo de Laura', 3, '2025-12-01 00:06:55', 8, 6, NULL),
(18, 1, 1, '2025-12-17', '2025-12-17', '19:00:00', '21:00:00', 'Futbol Femenino', 'Partido exclusivo femenino', 3, '2025-12-01 00:15:32', 8, 4, NULL),
(19, 1, 4, '2025-12-04', '2025-12-04', '12:00:00', '15:00:00', 'Combo Partido + Parrilla', 'Preparar la parrilla para la llegada de Diego', 3, '2025-12-01 00:23:55', 8, 5, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reservas_usuarios`
--

DROP TABLE IF EXISTS `reservas_usuarios`;
CREATE TABLE `reservas_usuarios` (
  `id_reserva` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre`) VALUES
(1, 'jugador'),
(2, 'admin_cancha'),
(3, 'admin_sistema'),
(4, 'admin_sistema_verificador'),
(5, 'admin_sistema_moderador'),
(6, 'admin_sistema_manager');

-- --------------------------------------------------------

--
-- Table structure for table `roles_partidos`
--

DROP TABLE IF EXISTS `roles_partidos`;
CREATE TABLE `roles_partidos` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles_partidos`
--

INSERT INTO `roles_partidos` (`id_rol`, `nombre`) VALUES
(1, 'Anfitrión'),
(2, 'Invitado'),
(3, 'Solicitante');

-- --------------------------------------------------------

--
-- Table structure for table `roles_permisos`
--

DROP TABLE IF EXISTS `roles_permisos`;
CREATE TABLE `roles_permisos` (
  `id` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `id_permiso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles_permisos`
--

INSERT INTO `roles_permisos` (`id`, `id_rol`, `id_permiso`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7),
(8, 1, 8),
(9, 1, 9),
(10, 1, 10),
(11, 1, 11),
(12, 1, 12),
(13, 1, 13),
(14, 1, 14),
(15, 1, 15),
(16, 1, 16),
(17, 1, 17),
(18, 1, 18),
(19, 1, 19),
(20, 1, 20),
(21, 1, 21),
(22, 1, 22),
(23, 1, 23),
(24, 1, 24),
(25, 1, 25),
(26, 2, 1),
(27, 2, 2),
(28, 2, 3),
(29, 2, 26),
(30, 2, 27),
(31, 2, 28),
(32, 2, 29),
(33, 2, 30),
(34, 2, 31),
(35, 2, 32),
(36, 2, 33),
(37, 2, 34),
(38, 2, 35),
(39, 2, 36),
(40, 2, 37),
(41, 2, 38),
(42, 2, 39),
(43, 2, 40),
(44, 2, 41),
(45, 2, 42),
(46, 2, 43),
(47, 2, 44),
(48, 2, 45),
(49, 2, 46),
(50, 2, 47),
(51, 2, 48),
(52, 3, 49),
(53, 3, 50),
(54, 3, 51),
(55, 3, 52),
(56, 3, 53),
(57, 3, 54),
(58, 4, 55),
(59, 4, 56),
(60, 4, 57),
(61, 4, 58),
(62, 5, 59),
(63, 5, 60),
(64, 5, 61),
(65, 5, 62),
(66, 6, 49),
(67, 6, 50),
(68, 6, 51),
(69, 6, 52),
(70, 6, 53),
(71, 6, 54),
(72, 6, 55),
(73, 6, 56),
(74, 6, 57),
(75, 6, 58),
(76, 6, 59),
(77, 6, 60),
(78, 6, 61),
(79, 6, 62);

-- --------------------------------------------------------

--
-- Table structure for table `servicios`
--

DROP TABLE IF EXISTS `servicios`;
CREATE TABLE `servicios` (
  `id_servicio` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `servicios`
--

INSERT INTO `servicios` (`id_servicio`, `nombre`) VALUES
(1, 'Vestuarios'),
(2, 'Estacionamiento'),
(3, 'Bar'),
(4, 'Duchas'),
(5, 'WIFI gratis');

-- --------------------------------------------------------

--
-- Table structure for table `servicios_canchas`
--

DROP TABLE IF EXISTS `servicios_canchas`;
CREATE TABLE `servicios_canchas` (
  `id_servicio` int(11) NOT NULL,
  `id_cancha` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `servicios_canchas`
--

INSERT INTO `servicios_canchas` (`id_servicio`, `id_cancha`) VALUES
(1, 1),
(2, 1),
(4, 1),
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(5, 2),
(1, 3),
(4, 3),
(5, 3);

-- --------------------------------------------------------

--
-- Table structure for table `sexo`
--

DROP TABLE IF EXISTS `sexo`;
CREATE TABLE `sexo` (
  `id_sexo` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sexo`
--

INSERT INTO `sexo` (`id_sexo`, `nombre`) VALUES
(1, 'femenino'),
(2, 'masculino'),
(3, 'otro');

-- --------------------------------------------------------

--
-- Table structure for table `solicitudes_admin_cancha`
--

DROP TABLE IF EXISTS `solicitudes_admin_cancha`;
CREATE TABLE `solicitudes_admin_cancha` (
  `id_solicitud` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `nombre_cancha` varchar(100) NOT NULL,
  `id_direccion` int(11) NOT NULL,
  `fecha_solicitud` datetime DEFAULT current_timestamp(),
  `id_estado` int(11) NOT NULL DEFAULT 1,
  `id_verificador` int(11) DEFAULT NULL,
  `fecha_resolucion` datetime DEFAULT NULL,
  `observaciones` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `solicitudes_admin_cancha`
--

INSERT INTO `solicitudes_admin_cancha` (`id_solicitud`, `nombre`, `apellido`, `email`, `telefono`, `nombre_cancha`, `id_direccion`, `fecha_solicitud`, `id_estado`, `id_verificador`, `fecha_resolucion`, `observaciones`) VALUES
(1, 'Roberto', 'Silva', 'cancha.centro@email.com', '+541155555001', 'Cancha Centro', 1, '2025-11-13 00:16:20', 3, 1, '2025-11-13 00:16:20', NULL),
(2, 'Patricia', 'Morales', 'cancha.norte@email.com', '+541155555002', 'Cancha Norte', 2, '2025-11-13 00:16:20', 3, 1, '2025-11-13 00:16:20', NULL),
(3, 'Miguel', 'Torres', 'miguel.torres@email.com', '+541155555003', 'Complejo Sur', 3, '2025-11-13 00:16:20', 1, NULL, NULL, NULL),
(4, 'Cristian', 'Santo', 'cristiansanto@gmail.com', '1166669999', 'Canchas Ezeiza', 6, '2025-11-24 12:26:22', 1, 1, NULL, 'Contactar por whatsapp en horario de manana');

-- --------------------------------------------------------

--
-- Table structure for table `superficies_canchas`
--

DROP TABLE IF EXISTS `superficies_canchas`;
CREATE TABLE `superficies_canchas` (
  `id_superficie` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `superficies_canchas`
--

INSERT INTO `superficies_canchas` (`id_superficie`, `nombre`) VALUES
(1, 'Sintético'),
(2, 'Cemento'),
(3, 'Parquet'),
(4, 'Césped natural');

-- --------------------------------------------------------

--
-- Table structure for table `tipos_partido`
--

DROP TABLE IF EXISTS `tipos_partido`;
CREATE TABLE `tipos_partido` (
  `id_tipo_partido` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `min_participantes` int(11) NOT NULL,
  `max_participantes` int(11) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tipos_partido`
--

INSERT INTO `tipos_partido` (`id_tipo_partido`, `nombre`, `min_participantes`, `max_participantes`, `descripcion`) VALUES
(1, 'Fútbol 5', 8, 10, 'Partido de fútbol con 5 jugadores por equipo'),
(2, 'Fútbol 7', 10, 14, 'Partido de fútbol con 7 jugadores por equipo'),
(3, 'Fútbol 11', 18, 22, 'Partido de fútbol con 11 jugadores por equipo'),
(4, 'Fútbol Sala', 8, 10, 'Partido de fútbol sala/futsal con 5 jugadores por equipo'),
(5, 'Fútbol Playa', 4, 10, 'Partido de fútbol playa con 5 jugadores por equipo'),
(6, 'Fútbol 8', 12, 16, NULL),
(7, 'Fútbol 3', 4, 6, NULL),
(8, 'Fútbol Libre', 2, 22, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tipos_reserva`
--

DROP TABLE IF EXISTS `tipos_reserva`;
CREATE TABLE `tipos_reserva` (
  `id_tipo_reserva` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tipos_reserva`
--

INSERT INTO `tipos_reserva` (`id_tipo_reserva`, `nombre`, `descripcion`) VALUES
(1, 'Partido', 'Reserva dedicada a un partido'),
(2, 'Torneo', 'Reserva del admin para partido de torneo'),
(3, 'Mantenimiento', 'Reserva del admin para mantenimiento/limpieza'),
(4, 'Evento', 'Reserva del admin para evento especial'),
(6, 'Otros', 'Tipo de reserva no clasificado');

-- --------------------------------------------------------

--
-- Table structure for table `torneos`
--

DROP TABLE IF EXISTS `torneos`;
CREATE TABLE `torneos` (
  `id_torneo` int(11) NOT NULL,
  `id_organizador` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `fin_estimativo` tinyint(1) DEFAULT NULL,
  `id_etapa` int(11) NOT NULL DEFAULT 1,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `torneos`
--

INSERT INTO `torneos` (`id_torneo`, `id_organizador`, `nombre`, `fecha_inicio`, `fecha_fin`, `fin_estimativo`, `id_etapa`, `descripcion`) VALUES
(1, 1, 'Copa Primavera 2025', '2025-12-01', '2025-12-15', NULL, 1, 'Torneo de fútbol 5 para equipos amateur'),
(2, 2, 'Torneo Relámpago', '2025-11-20', '2025-11-20', NULL, 1, 'Torneo de un día en múltiples canchas');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_estado` int(11) NOT NULL DEFAULT 1,
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellido`, `email`, `password`, `id_estado`, `fecha_registro`) VALUES
(1, 'Juan', 'Pérez', 'juan.perez@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, '2025-11-13 00:16:20'),
(2, 'María', 'González', 'maria.gonzalez@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, '2025-11-13 00:16:20'),
(3, 'Carlos', 'López', 'carlos.lopez@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, '2025-11-13 00:16:20'),
(4, 'Ana', 'Martínez', 'ana.martinez@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, '2025-11-13 00:16:20'),
(5, 'Diego', 'Rodríguez', 'diego.rodriguez@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, '2025-11-13 00:16:20'),
(6, 'Laura', 'Fernández', 'laura.fernandez@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, '2025-11-13 00:16:20'),
(7, 'Admin', 'Sistema', 'admin@futmatch.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, '2025-11-13 00:16:20'),
(8, 'Roberto', 'Silva', 'cancha.centro@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, '2025-11-13 00:16:20'),
(9, 'Patricia', 'Morales', 'cancha.norte@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, '2025-11-13 00:16:20'),
(10, 'Camila', 'Santo', 'cnsanto@gmail.com', '$2y$10$HP6SMxtT1bB4Pe.TS7Eck.sZJbgfU8HtZ5i5yu7.SgH06T45DI2Gi', 1, '2025-11-21 11:01:44');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios_roles`
--

DROP TABLE IF EXISTS `usuarios_roles`;
CREATE TABLE `usuarios_roles` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `usuarios_roles`
--

INSERT INTO `usuarios_roles` (`id`, `id_usuario`, `id_rol`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(4, 4, 1),
(5, 5, 1),
(6, 6, 1),
(7, 7, 3),
(8, 8, 2),
(9, 9, 2),
(10, 10, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_canchas`
--
ALTER TABLE `admin_canchas`
  ADD PRIMARY KEY (`id_admin_cancha`),
  ADD KEY `id_solicitud` (`id_solicitud`);

--
-- Indexes for table `admin_sistema`
--
ALTER TABLE `admin_sistema`
  ADD PRIMARY KEY (`id_admin_sistema`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `calificaciones_jugadores`
--
ALTER TABLE `calificaciones_jugadores`
  ADD PRIMARY KEY (`id_calificacion`),
  ADD KEY `id_partido` (`id_partido`),
  ADD KEY `id_jugador_evaluador` (`id_jugador_evaluador`),
  ADD KEY `id_jugador_evaluado` (`id_jugador_evaluado`);

--
-- Indexes for table `canchas`
--
ALTER TABLE `canchas`
  ADD PRIMARY KEY (`id_cancha`),
  ADD KEY `id_admin_cancha` (`id_admin_cancha`),
  ADD KEY `id_direccion` (`id_direccion`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `id_superficie` (`id_superficie`);

--
-- Indexes for table `canchas_tipos_partido`
--
ALTER TABLE `canchas_tipos_partido`
  ADD PRIMARY KEY (`id_cancha`,`id_tipo_partido`),
  ADD KEY `idx_cancha_activo` (`id_cancha`,`activo`),
  ADD KEY `idx_tipo_partido_activo` (`id_tipo_partido`,`activo`);

--
-- Indexes for table `dias_semana`
--
ALTER TABLE `dias_semana`
  ADD PRIMARY KEY (`id_dia`);

--
-- Indexes for table `direcciones`
--
ALTER TABLE `direcciones`
  ADD PRIMARY KEY (`id_direccion`);

--
-- Indexes for table `equipos`
--
ALTER TABLE `equipos`
  ADD PRIMARY KEY (`id_equipo`),
  ADD KEY `id_lider` (`id_lider`),
  ADD KEY `fk_creado_por` (`creado_por`);

--
-- Indexes for table `equipos_partidos`
--
ALTER TABLE `equipos_partidos`
  ADD PRIMARY KEY (`id_equipo`,`id_partido`),
  ADD KEY `idx_partido` (`id_partido`),
  ADD KEY `idx_equipo` (`id_equipo`);

--
-- Indexes for table `equipos_torneos`
--
ALTER TABLE `equipos_torneos`
  ADD PRIMARY KEY (`id_equipo`,`id_torneo`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `idx_equipos_torneos_estado` (`id_torneo`,`id_estado`);

--
-- Indexes for table `estadisticas_partido`
--
ALTER TABLE `estadisticas_partido`
  ADD PRIMARY KEY (`id_estadistica`),
  ADD KEY `id_partido` (`id_partido`),
  ADD KEY `id_participante` (`id_participante`);

--
-- Indexes for table `estados_canchas`
--
ALTER TABLE `estados_canchas`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indexes for table `estados_solicitudes`
--
ALTER TABLE `estados_solicitudes`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indexes for table `estados_usuarios`
--
ALTER TABLE `estados_usuarios`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indexes for table `etapas_torneo`
--
ALTER TABLE `etapas_torneo`
  ADD PRIMARY KEY (`id_etapa`);

--
-- Indexes for table `fases_torneo`
--
ALTER TABLE `fases_torneo`
  ADD PRIMARY KEY (`id_fase`),
  ADD KEY `idx_n` (`n`);

--
-- Indexes for table `horarios_cancha`
--
ALTER TABLE `horarios_cancha`
  ADD PRIMARY KEY (`id_horario`),
  ADD KEY `id_cancha` (`id_cancha`),
  ADD KEY `id_dia` (`id_dia`);

--
-- Indexes for table `jugadores`
--
ALTER TABLE `jugadores`
  ADD PRIMARY KEY (`id_jugador`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `id_sexo` (`id_sexo`),
  ADD KEY `id_posicion` (`id_posicion`);

--
-- Indexes for table `jugadores_equipos`
--
ALTER TABLE `jugadores_equipos`
  ADD PRIMARY KEY (`id_jugador`,`id_equipo`),
  ADD KEY `id_equipo` (`id_equipo`),
  ADD KEY `fk_estado_solicitud` (`estado_solicitud`),
  ADD KEY `fk_invitado_por` (`invitado_por`);

--
-- Indexes for table `observaciones_canchas`
--
ALTER TABLE `observaciones_canchas`
  ADD PRIMARY KEY (`id_observacion`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_solicitud` (`id_solicitud`);

--
-- Indexes for table `participantes_partidos`
--
ALTER TABLE `participantes_partidos`
  ADD PRIMARY KEY (`id_participante`),
  ADD KEY `id_jugador` (`id_jugador`),
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `idx_participantes_partido_estado` (`id_partido`,`id_estado`);

--
-- Indexes for table `partidos`
--
ALTER TABLE `partidos`
  ADD PRIMARY KEY (`id_partido`),
  ADD KEY `id_tipo_partido` (`id_tipo_partido`),
  ADD KEY `idx_partidos_anfitrion` (`id_anfitrion`);

--
-- Indexes for table `partidos_reservas`
--
ALTER TABLE `partidos_reservas`
  ADD PRIMARY KEY (`id_partido`,`id_reserva`),
  ADD KEY `id_reserva` (`id_reserva`);

--
-- Indexes for table `partidos_torneos`
--
ALTER TABLE `partidos_torneos`
  ADD PRIMARY KEY (`id_partido_torneo`),
  ADD UNIQUE KEY `unique_partido_torneo` (`id_partido`,`id_torneo`),
  ADD KEY `id_fase` (`id_fase`),
  ADD KEY `idx_torneo_fase` (`id_torneo`,`id_fase`),
  ADD KEY `fk_id_equipo_a` (`id_equipo_A`),
  ADD KEY `fk_id_equipo_b` (`id_equipo_B`);

--
-- Indexes for table `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id_permiso`);

--
-- Indexes for table `personas_externas`
--
ALTER TABLE `personas_externas`
  ADD PRIMARY KEY (`id_externo`);

--
-- Indexes for table `posiciones`
--
ALTER TABLE `posiciones`
  ADD PRIMARY KEY (`id_posicion`);

--
-- Indexes for table `resenias_canchas`
--
ALTER TABLE `resenias_canchas`
  ADD PRIMARY KEY (`id_resenia`),
  ADD KEY `id_cancha` (`id_cancha`),
  ADD KEY `id_jugador` (`id_jugador`);

--
-- Indexes for table `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `id_cancha` (`id_cancha`),
  ADD KEY `id_tipo_reserva` (`id_tipo_reserva`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `idx_reservas_fecha_cancha` (`fecha`,`id_cancha`),
  ADD KEY `fk_reservas_creador` (`id_creador_usuario`),
  ADD KEY `fk_reservas_titular_jugador` (`id_titular_jugador`),
  ADD KEY `fk_reservas_titular_externo` (`id_titular_externo`);

--
-- Indexes for table `reservas_usuarios`
--
ALTER TABLE `reservas_usuarios`
  ADD PRIMARY KEY (`id_reserva`,`id_usuario`),
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `idx_usuario_tipo` (`id_usuario`,`id_rol`),
  ADD KEY `idx_reserva` (`id_reserva`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indexes for table `roles_partidos`
--
ALTER TABLE `roles_partidos`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indexes for table `roles_permisos`
--
ALTER TABLE `roles_permisos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `id_permiso` (`id_permiso`);

--
-- Indexes for table `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id_servicio`);

--
-- Indexes for table `servicios_canchas`
--
ALTER TABLE `servicios_canchas`
  ADD KEY `id_servicio` (`id_servicio`),
  ADD KEY `id_cancha` (`id_cancha`);

--
-- Indexes for table `sexo`
--
ALTER TABLE `sexo`
  ADD PRIMARY KEY (`id_sexo`);

--
-- Indexes for table `solicitudes_admin_cancha`
--
ALTER TABLE `solicitudes_admin_cancha`
  ADD PRIMARY KEY (`id_solicitud`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_direccion` (`id_direccion`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `id_verificador` (`id_verificador`);

--
-- Indexes for table `superficies_canchas`
--
ALTER TABLE `superficies_canchas`
  ADD PRIMARY KEY (`id_superficie`);

--
-- Indexes for table `tipos_partido`
--
ALTER TABLE `tipos_partido`
  ADD PRIMARY KEY (`id_tipo_partido`);

--
-- Indexes for table `tipos_reserva`
--
ALTER TABLE `tipos_reserva`
  ADD PRIMARY KEY (`id_tipo_reserva`);

--
-- Indexes for table `torneos`
--
ALTER TABLE `torneos`
  ADD PRIMARY KEY (`id_torneo`),
  ADD KEY `id_etapa` (`id_etapa`),
  ADD KEY `idx_torneos_organizador_etapa` (`id_organizador`,`id_etapa`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_estado` (`id_estado`);

--
-- Indexes for table `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_canchas`
--
ALTER TABLE `admin_canchas`
  MODIFY `id_admin_cancha` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `admin_sistema`
--
ALTER TABLE `admin_sistema`
  MODIFY `id_admin_sistema` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `calificaciones_jugadores`
--
ALTER TABLE `calificaciones_jugadores`
  MODIFY `id_calificacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `canchas`
--
ALTER TABLE `canchas`
  MODIFY `id_cancha` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `dias_semana`
--
ALTER TABLE `dias_semana`
  MODIFY `id_dia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `direcciones`
--
ALTER TABLE `direcciones`
  MODIFY `id_direccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `equipos`
--
ALTER TABLE `equipos`
  MODIFY `id_equipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `estadisticas_partido`
--
ALTER TABLE `estadisticas_partido`
  MODIFY `id_estadistica` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `estados_canchas`
--
ALTER TABLE `estados_canchas`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `estados_solicitudes`
--
ALTER TABLE `estados_solicitudes`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `estados_usuarios`
--
ALTER TABLE `estados_usuarios`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `etapas_torneo`
--
ALTER TABLE `etapas_torneo`
  MODIFY `id_etapa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `fases_torneo`
--
ALTER TABLE `fases_torneo`
  MODIFY `id_fase` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `horarios_cancha`
--
ALTER TABLE `horarios_cancha`
  MODIFY `id_horario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `jugadores`
--
ALTER TABLE `jugadores`
  MODIFY `id_jugador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `observaciones_canchas`
--
ALTER TABLE `observaciones_canchas`
  MODIFY `id_observacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `participantes_partidos`
--
ALTER TABLE `participantes_partidos`
  MODIFY `id_participante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `partidos`
--
ALTER TABLE `partidos`
  MODIFY `id_partido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `partidos_torneos`
--
ALTER TABLE `partidos_torneos`
  MODIFY `id_partido_torneo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `personas_externas`
--
ALTER TABLE `personas_externas`
  MODIFY `id_externo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `posiciones`
--
ALTER TABLE `posiciones`
  MODIFY `id_posicion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `resenias_canchas`
--
ALTER TABLE `resenias_canchas`
  MODIFY `id_resenia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `roles_partidos`
--
ALTER TABLE `roles_partidos`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles_permisos`
--
ALTER TABLE `roles_permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sexo`
--
ALTER TABLE `sexo`
  MODIFY `id_sexo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `solicitudes_admin_cancha`
--
ALTER TABLE `solicitudes_admin_cancha`
  MODIFY `id_solicitud` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `superficies_canchas`
--
ALTER TABLE `superficies_canchas`
  MODIFY `id_superficie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tipos_partido`
--
ALTER TABLE `tipos_partido`
  MODIFY `id_tipo_partido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tipos_reserva`
--
ALTER TABLE `tipos_reserva`
  MODIFY `id_tipo_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `torneos`
--
ALTER TABLE `torneos`
  MODIFY `id_torneo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_canchas`
--
ALTER TABLE `admin_canchas`
  ADD CONSTRAINT `admin_canchas_ibfk_1` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitudes_admin_cancha` (`id_solicitud`);

--
-- Constraints for table `admin_sistema`
--
ALTER TABLE `admin_sistema`
  ADD CONSTRAINT `admin_sistema_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Constraints for table `calificaciones_jugadores`
--
ALTER TABLE `calificaciones_jugadores`
  ADD CONSTRAINT `calificaciones_jugadores_ibfk_1` FOREIGN KEY (`id_partido`) REFERENCES `partidos` (`id_partido`),
  ADD CONSTRAINT `calificaciones_jugadores_ibfk_2` FOREIGN KEY (`id_jugador_evaluador`) REFERENCES `jugadores` (`id_jugador`),
  ADD CONSTRAINT `calificaciones_jugadores_ibfk_3` FOREIGN KEY (`id_jugador_evaluado`) REFERENCES `jugadores` (`id_jugador`);

--
-- Constraints for table `canchas`
--
ALTER TABLE `canchas`
  ADD CONSTRAINT `canchas_ibfk_1` FOREIGN KEY (`id_admin_cancha`) REFERENCES `admin_canchas` (`id_admin_cancha`),
  ADD CONSTRAINT `canchas_ibfk_2` FOREIGN KEY (`id_direccion`) REFERENCES `direcciones` (`id_direccion`),
  ADD CONSTRAINT `canchas_ibfk_3` FOREIGN KEY (`id_estado`) REFERENCES `estados_canchas` (`id_estado`),
  ADD CONSTRAINT `canchas_ibfk_4` FOREIGN KEY (`id_superficie`) REFERENCES `superficies_canchas` (`id_superficie`);

--
-- Constraints for table `canchas_tipos_partido`
--
ALTER TABLE `canchas_tipos_partido`
  ADD CONSTRAINT `canchas_tipos_partido_ibfk_1` FOREIGN KEY (`id_cancha`) REFERENCES `canchas` (`id_cancha`) ON DELETE CASCADE,
  ADD CONSTRAINT `canchas_tipos_partido_ibfk_2` FOREIGN KEY (`id_tipo_partido`) REFERENCES `tipos_partido` (`id_tipo_partido`) ON DELETE CASCADE;

--
-- Constraints for table `equipos`
--
ALTER TABLE `equipos`
  ADD CONSTRAINT `equipos_ibfk_1` FOREIGN KEY (`id_lider`) REFERENCES `jugadores` (`id_jugador`),
  ADD CONSTRAINT `fk_creado_por` FOREIGN KEY (`creado_por`) REFERENCES `jugadores` (`id_jugador`);

--
-- Constraints for table `equipos_partidos`
--
ALTER TABLE `equipos_partidos`
  ADD CONSTRAINT `equipos_partidos_ibfk_1` FOREIGN KEY (`id_equipo`) REFERENCES `equipos` (`id_equipo`) ON DELETE CASCADE,
  ADD CONSTRAINT `equipos_partidos_ibfk_2` FOREIGN KEY (`id_partido`) REFERENCES `partidos` (`id_partido`) ON DELETE CASCADE;

--
-- Constraints for table `equipos_torneos`
--
ALTER TABLE `equipos_torneos`
  ADD CONSTRAINT `equipos_torneos_ibfk_1` FOREIGN KEY (`id_equipo`) REFERENCES `equipos` (`id_equipo`),
  ADD CONSTRAINT `equipos_torneos_ibfk_2` FOREIGN KEY (`id_torneo`) REFERENCES `torneos` (`id_torneo`),
  ADD CONSTRAINT `equipos_torneos_ibfk_3` FOREIGN KEY (`id_estado`) REFERENCES `estados_solicitudes` (`id_estado`);

--
-- Constraints for table `estadisticas_partido`
--
ALTER TABLE `estadisticas_partido`
  ADD CONSTRAINT `estadisticas_partido_ibfk_1` FOREIGN KEY (`id_partido`) REFERENCES `partidos` (`id_partido`),
  ADD CONSTRAINT `estadisticas_partido_ibfk_2` FOREIGN KEY (`id_participante`) REFERENCES `participantes_partidos` (`id_participante`);

--
-- Constraints for table `horarios_cancha`
--
ALTER TABLE `horarios_cancha`
  ADD CONSTRAINT `horarios_cancha_ibfk_1` FOREIGN KEY (`id_cancha`) REFERENCES `canchas` (`id_cancha`),
  ADD CONSTRAINT `horarios_cancha_ibfk_2` FOREIGN KEY (`id_dia`) REFERENCES `dias_semana` (`id_dia`);

--
-- Constraints for table `jugadores`
--
ALTER TABLE `jugadores`
  ADD CONSTRAINT `jugadores_ibfk_1` FOREIGN KEY (`id_jugador`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `jugadores_ibfk_2` FOREIGN KEY (`id_sexo`) REFERENCES `sexo` (`id_sexo`),
  ADD CONSTRAINT `jugadores_ibfk_3` FOREIGN KEY (`id_posicion`) REFERENCES `posiciones` (`id_posicion`);

--
-- Constraints for table `jugadores_equipos`
--
ALTER TABLE `jugadores_equipos`
  ADD CONSTRAINT `fk_estado_solicitud` FOREIGN KEY (`estado_solicitud`) REFERENCES `estados_solicitudes` (`id_estado`),
  ADD CONSTRAINT `fk_invitado_por` FOREIGN KEY (`invitado_por`) REFERENCES `jugadores` (`id_jugador`),
  ADD CONSTRAINT `jugadores_equipos_ibfk_1` FOREIGN KEY (`id_jugador`) REFERENCES `jugadores` (`id_jugador`),
  ADD CONSTRAINT `jugadores_equipos_ibfk_2` FOREIGN KEY (`id_equipo`) REFERENCES `equipos` (`id_equipo`);

--
-- Constraints for table `observaciones_canchas`
--
ALTER TABLE `observaciones_canchas`
  ADD CONSTRAINT `observaciones_canchas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `observaciones_canchas_ibfk_2` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitudes_admin_cancha` (`id_solicitud`);

--
-- Constraints for table `participantes_partidos`
--
ALTER TABLE `participantes_partidos`
  ADD CONSTRAINT `participantes_partidos_ibfk_1` FOREIGN KEY (`id_partido`) REFERENCES `partidos` (`id_partido`),
  ADD CONSTRAINT `participantes_partidos_ibfk_2` FOREIGN KEY (`id_jugador`) REFERENCES `jugadores` (`id_jugador`),
  ADD CONSTRAINT `participantes_partidos_ibfk_3` FOREIGN KEY (`id_rol`) REFERENCES `roles_partidos` (`id_rol`),
  ADD CONSTRAINT `participantes_partidos_ibfk_4` FOREIGN KEY (`id_estado`) REFERENCES `estados_solicitudes` (`id_estado`);

--
-- Constraints for table `partidos`
--
ALTER TABLE `partidos`
  ADD CONSTRAINT `partidos_ibfk_1` FOREIGN KEY (`id_anfitrion`) REFERENCES `jugadores` (`id_jugador`),
  ADD CONSTRAINT `partidos_ibfk_2` FOREIGN KEY (`id_tipo_partido`) REFERENCES `tipos_partido` (`id_tipo_partido`);

--
-- Constraints for table `partidos_reservas`
--
ALTER TABLE `partidos_reservas`
  ADD CONSTRAINT `partidos_reservas_ibfk_1` FOREIGN KEY (`id_reserva`) REFERENCES `reservas` (`id_reserva`),
  ADD CONSTRAINT `partidos_reservas_ibfk_2` FOREIGN KEY (`id_partido`) REFERENCES `partidos` (`id_partido`);

--
-- Constraints for table `partidos_torneos`
--
ALTER TABLE `partidos_torneos`
  ADD CONSTRAINT `fk_id_equipo_a` FOREIGN KEY (`id_equipo_A`) REFERENCES `equipos` (`id_equipo`),
  ADD CONSTRAINT `fk_id_equipo_b` FOREIGN KEY (`id_equipo_B`) REFERENCES `equipos` (`id_equipo`),
  ADD CONSTRAINT `partidos_torneos_ibfk_1` FOREIGN KEY (`id_partido`) REFERENCES `partidos` (`id_partido`) ON DELETE CASCADE,
  ADD CONSTRAINT `partidos_torneos_ibfk_2` FOREIGN KEY (`id_torneo`) REFERENCES `torneos` (`id_torneo`) ON DELETE CASCADE,
  ADD CONSTRAINT `partidos_torneos_ibfk_3` FOREIGN KEY (`id_fase`) REFERENCES `fases_torneo` (`id_fase`);

--
-- Constraints for table `resenias_canchas`
--
ALTER TABLE `resenias_canchas`
  ADD CONSTRAINT `resenias_canchas_ibfk_1` FOREIGN KEY (`id_cancha`) REFERENCES `canchas` (`id_cancha`),
  ADD CONSTRAINT `resenias_canchas_ibfk_2` FOREIGN KEY (`id_jugador`) REFERENCES `jugadores` (`id_jugador`);

--
-- Constraints for table `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `fk_reservas_creador` FOREIGN KEY (`id_creador_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `fk_reservas_titular_externo` FOREIGN KEY (`id_titular_externo`) REFERENCES `personas_externas` (`id_externo`),
  ADD CONSTRAINT `fk_reservas_titular_jugador` FOREIGN KEY (`id_titular_jugador`) REFERENCES `jugadores` (`id_jugador`),
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_cancha`) REFERENCES `canchas` (`id_cancha`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`id_tipo_reserva`) REFERENCES `tipos_reserva` (`id_tipo_reserva`),
  ADD CONSTRAINT `reservas_ibfk_3` FOREIGN KEY (`id_estado`) REFERENCES `estados_solicitudes` (`id_estado`);

--
-- Constraints for table `reservas_usuarios`
--
ALTER TABLE `reservas_usuarios`
  ADD CONSTRAINT `reservas_usuarios_ibfk_1` FOREIGN KEY (`id_reserva`) REFERENCES `reservas` (`id_reserva`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservas_usuarios_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservas_usuarios_ibfk_3` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);

--
-- Constraints for table `roles_permisos`
--
ALTER TABLE `roles_permisos`
  ADD CONSTRAINT `roles_permisos_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`),
  ADD CONSTRAINT `roles_permisos_ibfk_2` FOREIGN KEY (`id_permiso`) REFERENCES `permisos` (`id_permiso`);

--
-- Constraints for table `servicios_canchas`
--
ALTER TABLE `servicios_canchas`
  ADD CONSTRAINT `servicios_canchas_ibfk_1` FOREIGN KEY (`id_servicio`) REFERENCES `servicios` (`id_servicio`),
  ADD CONSTRAINT `servicios_canchas_ibfk_2` FOREIGN KEY (`id_cancha`) REFERENCES `canchas` (`id_cancha`);

--
-- Constraints for table `solicitudes_admin_cancha`
--
ALTER TABLE `solicitudes_admin_cancha`
  ADD CONSTRAINT `solicitudes_admin_cancha_ibfk_1` FOREIGN KEY (`id_direccion`) REFERENCES `direcciones` (`id_direccion`),
  ADD CONSTRAINT `solicitudes_admin_cancha_ibfk_2` FOREIGN KEY (`id_estado`) REFERENCES `estados_solicitudes` (`id_estado`),
  ADD CONSTRAINT `solicitudes_admin_cancha_ibfk_3` FOREIGN KEY (`id_verificador`) REFERENCES `admin_sistema` (`id_admin_sistema`);

--
-- Constraints for table `torneos`
--
ALTER TABLE `torneos`
  ADD CONSTRAINT `torneos_ibfk_1` FOREIGN KEY (`id_organizador`) REFERENCES `admin_canchas` (`id_admin_cancha`),
  ADD CONSTRAINT `torneos_ibfk_2` FOREIGN KEY (`id_etapa`) REFERENCES `etapas_torneo` (`id_etapa`);

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_estado`) REFERENCES `estados_usuarios` (`id_estado`);

--
-- Constraints for table `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  ADD CONSTRAINT `usuarios_roles_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`),
  ADD CONSTRAINT `usuarios_roles_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


-- =========================================================
-- NO BORRAR: SE AGREGAN VISTAS MANUALMENTE YA QUE PHPMYADMIN NO LAS EXPORTA BIEN
-- =========================================================

-- VISTA PARA VER INFORMACION DE EQUIPOS A LOS QUE PERTENECE UN JUGADOR
-- Muestra información relevante de los equipos a los que pertenece un jugador

DROP VIEW IF EXISTS vista_equipos_jugador;

CREATE OR REPLACE VIEW vista_equipos_jugador AS
SELECT 
    e.id_equipo,
    e.id_lider,
    -- agregar nombre del lider
    e.nombre AS nombre_equipo,
    e.foto AS foto_equipo,
    e.abierto,
    e.clave,
    e.descripcion,

    u.nombre AS nombre_lider,
    u.apellido AS apellido_lider,

    -- Cantidad de integrantes del equipo
    (SELECT COUNT(*) 
     FROM jugadores_equipos je 
     WHERE je.id_equipo = e.id_equipo
     AND je.estado_solicitud = 3) AS cantidad_integrantes,
     je.id_jugador,
     je.estado_solicitud,

    -- Cantidad de torneos participados por el equipo
    (SELECT COUNT(DISTINCT t.id_torneo)
     FROM torneos t
     INNER JOIN equipos_torneos et ON t.id_torneo = et.id_torneo
     WHERE et.id_equipo = e.id_equipo) AS torneos_participados,

    -- Cantidad de partidos jugados por el equipo
    (SELECT COUNT(*) 
     FROM equipos_partidos ep
     WHERE ep.id_equipo = e.id_equipo) AS partidos_jugados

FROM equipos e
INNER JOIN usuarios u ON e.id_lider = u.id_usuario
INNER JOIN jugadores_equipos je ON e.id_equipo = je.id_equipo;



-- VISTA PARA EXPLORAR CANCHAS DISPONIBLES PARA RESERVAR
-- Muestra información de las canchas disponibles para reservar por parte de un jugador

DROP VIEW IF EXISTS vista_explorar_canchas;

CREATE OR REPLACE VIEW vista_explorar_canchas AS
SELECT 
    c.id_cancha,
    c.nombre AS nombre_cancha,
    c.foto AS foto_cancha,
    c.descripcion AS descripcion_cancha,

    -- Direccion de la cancha
    d.direccion_completa,
    d.latitud,
    d.longitud,

    -- Tipo de superficie
    s.nombre AS tipo_superficie,

    -- Tipo de partido con máximo participantes disponible en esta cancha
    (SELECT tp_max.nombre 
     FROM canchas_tipos_partido ctp
     INNER JOIN tipos_partido tp_max ON ctp.id_tipo_partido = tp_max.id_tipo_partido
     WHERE ctp.id_cancha = c.id_cancha
     ORDER BY tp_max.max_participantes DESC
     LIMIT 1) AS tipo_partido_max,

    (SELECT AVG(r.calificacion) 
     FROM resenias_canchas r
     WHERE r.id_cancha = c.id_cancha) AS calificacion_promedio

FROM canchas c

INNER JOIN direcciones d ON c.id_direccion = d.id_direccion
INNER JOIN superficies_canchas s ON c.id_superficie = s.id_superficie;



-- =========================================================
-- VISTA: Explorar Partidos Disponibles
-- =========================================================
-- Muestra partidos abiertos disponibles para que los jugadores soliciten participar
-- Excluye partidos en los que el jugador ya participa (usar filtro en el WHERE del controller)
--
-- Ejemplo de uso:
-- SELECT * FROM vista_explorar_partidos WHERE fecha_partido >= CURDATE() ORDER BY fecha_partido ASC;
-- SELECT * FROM vista_explorar_partidos WHERE id_tipo_partido = 1;

DROP VIEW IF EXISTS vista_explorar_partidos;

CREATE OR REPLACE VIEW vista_explorar_partidos AS
SELECT 
    -- Información del partido
    p.id_partido,
    p.id_anfitrion,
    p.abierto,
    
    -- Información de la reserva
    r.id_reserva,
    r.fecha AS fecha_partido,
    DATE_FORMAT(r.fecha, '%d/%m/%Y') AS fecha_partido_formato,
    CASE DAYOFWEEK(r.fecha)
        WHEN 1 THEN 'Domingo'
        WHEN 2 THEN 'Lunes'
        WHEN 3 THEN 'Martes'
        WHEN 4 THEN 'Miércoles'
        WHEN 5 THEN 'Jueves'
        WHEN 6 THEN 'Viernes'
        WHEN 7 THEN 'Sábado'
    END as dia_semana,
    TIME_FORMAT(r.hora_inicio, '%H:%i') as hora_inicio,
    TIME_FORMAT(r.hora_fin, '%H:%i') as hora_fin,
    r.hora_inicio as hora_inicio_raw,
    r.titulo,
    r.descripcion,
    r.id_tipo_reserva,
    
    -- Información de la cancha
    c.id_cancha,
    c.nombre AS nombre_cancha,
    c.foto AS foto_cancha,
    
    -- Dirección de la cancha
    d.direccion_completa,
    d.latitud,
    d.longitud,
    
    -- Información del tipo de partido
    tp.id_tipo_partido,
    tp.nombre AS tipo_partido,
    tp.min_participantes,
    tp.max_participantes,
    
    -- Superficie de la cancha
    s.nombre AS tipo_superficie,
    
    -- Información del anfitrión
    j_anfitrion.id_jugador AS id_jugador_anfitrion,
    j_anfitrion.username AS username_anfitrion,
    
    -- Cantidad de participantes actual
    (SELECT COUNT(*) 
        FROM participantes_partidos pp 
        WHERE pp.id_partido = p.id_partido) AS participantes_actuales,

     -- Cantidad de participantes en pp.equipo=1
    (SELECT COUNT(*)
        FROM participantes_partidos pp 
        WHERE pp.id_partido = p.id_partido AND pp.equipo = 1) AS cant_participantes_equipo_A,

    -- Cantidad de participantes en pp.equipo=2
    (SELECT COUNT(*)
        FROM participantes_partidos pp 
        WHERE pp.id_partido = p.id_partido AND pp.equipo = 2) AS cant_participantes_equipo_B

FROM partidos p

-- Unir con partidos_reservas para obtener id_reserva
INNER JOIN partidos_reservas pr ON p.id_partido = pr.id_partido

-- Unir con reservas para obtener fecha, hora y cancha
INNER JOIN reservas r ON pr.id_reserva = r.id_reserva

-- Unir con canchas
INNER JOIN canchas c ON r.id_cancha = c.id_cancha

-- Unir con direcciones
INNER JOIN direcciones d ON c.id_direccion = d.id_direccion

-- Unir con tipo de partido
INNER JOIN tipos_partido tp ON p.id_tipo_partido = tp.id_tipo_partido

-- Unir con superficie de cancha
INNER JOIN superficies_canchas s ON c.id_superficie = s.id_superficie

-- Información del anfitrión
INNER JOIN jugadores j_anfitrion ON p.id_anfitrion = j_anfitrion.id_jugador

-- Condiciones: solo partidos abiertos y futuros
WHERE p.abierto = 1 
  AND r.fecha >= CURDATE()
  AND r.id_tipo_reserva = 1 -- Solo partidos (no torneos)

ORDER BY r.fecha ASC, r.hora_inicio ASC;

-- =========================================================
-- VISTA: Explorar Partidos Disponibles
-- =========================================================
-- Muestra partidos abiertos disponibles para que los jugadores soliciten participar
-- Excluye partidos en los que el jugador ya participa (usar filtro en el WHERE del controller)
--
-- Ejemplo de uso:
-- SELECT * FROM vista_explorar_partidos WHERE fecha_partido >= CURDATE() ORDER BY fecha_partido ASC;
-- SELECT * FROM vista_explorar_partidos WHERE id_tipo_partido = 1;

DROP VIEW IF EXISTS vista_explorar_partidos;

CREATE OR REPLACE VIEW vista_explorar_partidos AS
SELECT 
    -- Información del partido
    p.id_partido,
    p.id_anfitrion,
    p.abierto,
    
    -- Información de la reserva
    r.id_reserva,
    r.fecha AS fecha_partido,
    DATE_FORMAT(r.fecha, '%d/%m/%Y') AS fecha_partido_formato,
    CASE DAYOFWEEK(r.fecha)
        WHEN 1 THEN 'Domingo'
        WHEN 2 THEN 'Lunes'
        WHEN 3 THEN 'Martes'
        WHEN 4 THEN 'Miércoles'
        WHEN 5 THEN 'Jueves'
        WHEN 6 THEN 'Viernes'
        WHEN 7 THEN 'Sábado'
    END as dia_semana,
    TIME_FORMAT(r.hora_inicio, '%H:%i') as hora_inicio,
    TIME_FORMAT(r.hora_fin, '%H:%i') as hora_fin,
    r.hora_inicio as hora_inicio_raw,
    r.titulo,
    r.descripcion,
    r.id_tipo_reserva,
    
    -- Información de la cancha
    c.id_cancha,
    c.nombre AS nombre_cancha,
    c.foto AS foto_cancha,
    
    -- Dirección de la cancha
    d.direccion_completa,
    d.latitud,
    d.longitud,
    
    -- Información del tipo de partido
    tp.id_tipo_partido,
    tp.nombre AS tipo_partido,
    tp.min_participantes,
    tp.max_participantes,
    
    -- Superficie de la cancha
    s.nombre AS tipo_superficie,
    
    -- Información del anfitrión
    j_anfitrion.id_jugador AS id_jugador_anfitrion,
    j_anfitrion.username AS username_anfitrion,
    
    -- Cantidad de participantes actual
    (SELECT COUNT(*) 
        FROM participantes_partidos pp 
        WHERE pp.id_partido = p.id_partido) AS participantes_actuales,

     -- Cantidad de participantes en pp.equipo=1
    (SELECT COUNT(*)
        FROM participantes_partidos pp 
        WHERE pp.id_partido = p.id_partido AND pp.equipo = 1) AS cant_participantes_equipo_A,

    -- Cantidad de participantes en pp.equipo=2
    (SELECT COUNT(*)
        FROM participantes_partidos pp 
        WHERE pp.id_partido = p.id_partido AND pp.equipo = 2) AS cant_participantes_equipo_B

FROM partidos p

-- Unir con partidos_reservas para obtener id_reserva
INNER JOIN partidos_reservas pr ON p.id_partido = pr.id_partido

-- Unir con reservas para obtener fecha, hora y cancha
INNER JOIN reservas r ON pr.id_reserva = r.id_reserva

-- Unir con canchas
INNER JOIN canchas c ON r.id_cancha = c.id_cancha

-- Unir con direcciones
INNER JOIN direcciones d ON c.id_direccion = d.id_direccion

-- Unir con tipo de partido
INNER JOIN tipos_partido tp ON p.id_tipo_partido = tp.id_tipo_partido

-- Unir con superficie de cancha
INNER JOIN superficies_canchas s ON c.id_superficie = s.id_superficie

-- Información del anfitrión
INNER JOIN jugadores j_anfitrion ON p.id_anfitrion = j_anfitrion.id_jugador

-- Condiciones: solo partidos abiertos y futuros
WHERE p.abierto = 1 
  AND r.fecha >= CURDATE()
  AND r.id_tipo_reserva = 1 -- Solo partidos (no torneos)

ORDER BY r.fecha ASC, r.hora_inicio ASC;

-- =========================================================
-- VISTA: Partidos por Jugador
-- =========================================================
-- Esta vista permite consultar los partidos de cualquier jugador
-- simplemente filtrando por id_jugador en el WHERE
--
-- Ejemplo de uso:
-- SELECT * FROM vista_partidos_jugador WHERE id_jugador = 1;
-- SELECT * FROM vista_partidos_jugador WHERE id_jugador = 2 ORDER BY fecha_partido DESC;

DROP VIEW IF EXISTS vista_partidos_jugador;

CREATE VIEW vista_partidos_jugador AS
SELECT 
    -- Información del jugador
    pp.id_jugador,
    j.username as mi_username,
    
    -- Información del partido
    p.id_partido,
    p.id_anfitrion,
    p.abierto,
    p.goles_equipo_A,
    p.goles_equipo_B,
    
    -- Fecha y hora (de la reserva)
    r.id_reserva,
    DATE_FORMAT(r.fecha, '%d/%m/%Y') AS fecha_partido,
    CASE DAYOFWEEK(r.fecha)
        WHEN 1 THEN 'Domingo'
        WHEN 2 THEN 'Lunes'
        WHEN 3 THEN 'Martes'
        WHEN 4 THEN 'Miércoles'
        WHEN 5 THEN 'Jueves'
        WHEN 6 THEN 'Viernes'
        WHEN 7 THEN 'Sábado'
    END as dia_semana,
    TIME_FORMAT(r.hora_inicio, '%H:%i') as hora_partido,
    TIME_FORMAT(r.hora_fin, '%H:%i') as hora_fin,
    r.id_tipo_reserva,
    
    -- Información de la cancha
    c.id_cancha,
    c.nombre as nombre_cancha,
    
    -- Dirección de la cancha
    d.direccion_completa as direccion_cancha,
    d.latitud as latitud_cancha,
    d.longitud as longitud_cancha,
    
    -- Estado de la solicitud del usuario
    es.id_estado,
    es.nombre as estado_solicitud,
    
    -- Rol del usuario en el partido
    rp.id_rol,
    rp.nombre as rol_usuario,
    
    -- Información del tipo de partido
    tp.id_tipo_partido,
    tp.nombre as tipo_partido,
    tp.min_participantes as min_participantes,
    tp.max_participantes as max_participantes,
    
    CASE pp.equipo 
    WHEN 1 THEN 'Equipo A'
    WHEN 2 THEN 'Equipo B'
    ELSE 'Sin asignar'
    END as equipo_asignado,

    -- Cantidad de participantes por equipo
    (SELECT COUNT(*) FROM participantes_partidos pp_a 
     WHERE pp_a.id_partido = p.id_partido AND pp_a.equipo = 1) AS cant_participantes_equipo_a,
    (SELECT COUNT(*) FROM participantes_partidos pp_b 
     WHERE pp_b.id_partido = p.id_partido AND pp_b.equipo = 2) AS cant_participantes_equipo_b,

    
    -- NO SE USA
    -- Información del equipo del jugador (el equipo al que pertenece)
    -- e_propio.id_equipo AS id_equipo_del_jugador,
    -- e_propio.nombre AS nombre_equipo_del_jugador,
    -- e_propio.foto AS foto_equipo_del_jugador,
    
    -- Información del equipo rival
    -- e_rival.id_equipo AS id_equipo_rival,
    -- e_rival.nombre AS nombre_equipo_rival,
    -- e_rival.foto AS foto_equipo_rival,

    -- Información del torneo si la hubiere 
    
    -- tabla TORNEOS
    t.id_torneo,
    t.nombre AS nombre_torneo,
    -- t.id_etapa 
    
    -- tabla ETAPAS_TORNEOS
    et.nombre AS etapa_torneo,
    
    -- tabla PARTIDOS_TORNEOS
    -- acá es donde se puede linkear el id_partido con el id_torneo y su info
    pt.id_fase,
    pt.orden_en_fase,
    pt.id_equipo_A,
    pt.id_equipo_B,

    -- TABLA EQUIPOS
    eqa.nombre AS nombre_equipo_A,
    eqa.foto AS foto_equipo_A,
    eqa.descripcion AS descripcion_equipo_A,
    eqb.nombre AS nombre_equipo_B,
    eqb.foto AS foto_equipo_B,
    eqb.descripcion AS descripcion_equipo_B
    

FROM participantes_partidos pp

-- Join con jugadores para obtener info del usuario
INNER JOIN jugadores j ON pp.id_jugador = j.id_jugador

-- Join con partidos
INNER JOIN partidos p ON pp.id_partido = p.id_partido

-- Join con tipos de partido
INNER JOIN tipos_partido tp ON p.id_tipo_partido = tp.id_tipo_partido

-- Join con estados de solicitud
INNER JOIN estados_solicitudes es ON pp.id_estado = es.id_estado

-- Join con roles de partidos
INNER JOIN roles_partidos rp ON pp.id_rol = rp.id_rol

-- Join con partidos_reservas para obtener la reserva
LEFT JOIN partidos_reservas pr ON p.id_partido = pr.id_partido

-- Join con reservas para obtener fecha y hora
LEFT JOIN reservas r ON pr.id_reserva = r.id_reserva

-- Join con canchas para obtener información de la cancha
LEFT JOIN canchas c ON r.id_cancha = c.id_cancha

-- Join con direcciones para obtener la dirección de la cancha
LEFT JOIN direcciones d ON c.id_direccion = d.id_direccion

-- Join para obtener el equipo PROPIO del jugador
-- (el equipo al que el jugador pertenece en este partido)
-- LEFT JOIN equipos_partidos ep_propio ON p.id_partido = ep_propio.id_partido 
    -- AND ep_propio.id_equipo IN (
        -- SELECT je.id_equipo 
        -- FROM jugadores_equipos je 
        -- WHERE je.id_jugador = pp.id_jugador
    -- )
-- LEFT JOIN equipos e_propio ON ep_propio.id_equipo = e_propio.id_equipo

-- Join para obtener el equipo RIVAL
-- (el otro equipo que participa en el partido, diferente al del jugador)
-- LEFT JOIN equipos_partidos ep_rival ON p.id_partido = ep_rival.id_partido 
    -- AND ep_rival.id_equipo != ep_propio.id_equipo
-- LEFT JOIN equipos e_rival ON ep_rival.id_equipo = e_rival.id_equipo

-- Join con la tabla PARTIDOS_TORNEOS para obtener info del torneo
LEFT JOIN partidos_torneos pt ON p.id_partido = pt.id_partido

-- Join con la tabla TORNEOS para obtener info del torneo
LEFT JOIN torneos t ON pt.id_torneo = t.id_torneo

-- Join con la tabla ETAPAS_TORNEOS para obtener info de la etapa del torneo
LEFT JOIN etapas_torneo et ON t.id_etapa = et.id_etapa

LEFT JOIN equipos eqa ON pt.id_equipo_A = eqa.id_equipo
LEFT JOIN equipos eqb ON pt.id_equipo_B = eqb.id_equipo;

-- =========================================================


/*PERFIL CANCHA

BBDD: CANCHA
id_cancha, => sale del boton "ver perfil cancha"
id_admin_cancha => NO aparece en el perfil, pero sirve para contacto
nombre_cancha, => aparece en el perfil
id_direccion => se usa para buscar la direccion en tabla DIRECIONES
descripcion => aparece en el perfil
id_estado => aparece en el perfil (determina si mostrarse o no)
foto => aparece en el perfil (FOTO_PERFIL)
banner => aparece en el perfil
id_superficie => se usa para buscar el tipo de superficie en tabla superficies_canchas
politicas_reservas => NO aparece en el perfil, pero sirve para AGENDA.

BBDD: DIRECCIONES
id_direccion, = id_direccion de la cancha
direccion_completa => aparece en el perfil
latitud => NO aparece en el perfil, pero sirve para el mapa
longitud => NO aparece en el perfil, pero sirve para el mapa

BBDD: SUPERFICIES_CANCHAS
id_superficie, = id_superficie de la cancha
nombre => aparece en el perfil
*/

DROP VIEW IF EXISTS vista_perfil_cancha;
CREATE VIEW vista_perfil_cancha AS
SELECT
    c.id_cancha,
    c.nombre AS nombre_cancha,
    c.descripcion AS descripcion_cancha,
    c.foto AS foto_cancha,
    c.banner AS banner_cancha,
    
    -- Dirección de la cancha
    d.direccion_completa AS direccion_cancha,
    d.latitud AS latitud_cancha,
    d.longitud AS longitud_cancha,
    
    -- Tipo de superficie
    s.nombre AS tipo_superficie

FROM canchas c
INNER JOIN direcciones d ON c.id_direccion = d.id_direccion
INNER JOIN superficies_canchas s ON c.id_superficie = s.id_superficie;

/* PERFIL JUGADOR

BBDD: USUARIOS
id_usuario, =session ID_USER
email, => no aparece en el perfil, pero sirve para traer el contacto
nombre, => aparece en el perfil
apellido, => aparece en el perfil
id_estado, => aparece en el perfil
fecha_registro => aparece en el perfil (usuario desde...)

BBDD: JUGADOR:
id_jugador, = id_usuario
username, => aparece en el perfil
telefono => no aparece en el perfil, pero sirve para contacto
foto_perfil, => aparece en el perfil
banner => aparece en el perfil
fecha_nacimiento, => aparece en el perfil (edad)
id_sexo, => aparece en el perfil
id_posicion, => NO SE USA
reputación, => aparece en el perfil
*/

DROP VIEW IF EXISTS vista_perfil_jugador;
CREATE VIEW vista_perfil_jugador AS
SELECT 
    u.id_usuario,
    u.nombre,
    u.apellido,
    u.email,
    u.fecha_registro,
    
    j.id_jugador,
    j.username,
    j.telefono,
    j.foto_perfil,
    j.banner,
    j.fecha_nacimiento,
    j.id_sexo,
    j.reputacion,
    j.descripcion,

    -- Sexo
    s.nombre AS sexo,
    
    e.nombre AS estado_usuario

FROM usuarios u
INNER JOIN jugadores j ON u.id_usuario = j.id_jugador
INNER JOIN sexo s ON j.id_sexo = s.id_sexo
INNER JOIN estados_usuarios e ON u.id_estado = e.id_estado;

/* RESERVAS */
DROP VIEW IF EXISTS vista_reservas;
CREATE VIEW vista_reservas AS
SELECT 
    r.id_reserva,
    r.fecha,
    r.fecha_fin,
    r.hora_inicio,
    r.hora_fin,
    r.titulo,
    r.descripcion,
    
    r.id_tipo_reserva,
    tr.nombre AS tipo_reserva,

    r.id_estado,
    e.nombre AS estado_reserva,
    
    -- Creador de la reserva (admin que la creó)
    r.id_creador_usuario,
    u_creador.nombre AS nombre_creador,
    u_creador.apellido AS apellido_creador,
    
    -- Titular de la reserva (jugador o persona externa)
    r.id_titular_jugador,
    r.id_titular_externo,
    
    -- Datos del titular si es jugador
    j_titular.username AS username_titular,
    u_titular.nombre AS nombre_titular_jugador,
    u_titular.apellido AS apellido_titular_jugador,
    j_titular.telefono AS telefono_titular_jugador,
    
    -- Datos del titular si es persona externa
    pe.nombre AS nombre_titular_externo,
    pe.apellido AS apellido_titular_externo,
    pe.telefono AS telefono_titular_externo,
    
    -- Nombre completo del titular (jugador o externo)
    CASE
        WHEN r.id_titular_jugador IS NOT NULL THEN CONCAT(u_titular.nombre, ' ', u_titular.apellido)
        WHEN r.id_titular_externo IS NOT NULL THEN CONCAT(pe.nombre, ' ', pe.apellido)
        ELSE 'Sin titular'
    END AS titular_nombre_completo,
    
    -- Teléfono del titular (jugador o externo)
    COALESCE(j_titular.telefono, pe.telefono) AS titular_telefono,
    
    -- Indicador de tipo de reserva
    CASE
        WHEN r.id_titular_jugador IS NOT NULL THEN 'jugador'
        WHEN r.id_titular_externo IS NOT NULL THEN 'externo'
        ELSE NULL
    END AS tipo_titular,
    
    c.id_cancha,
    c.nombre AS nombre_cancha

FROM reservas r
INNER JOIN tipos_reserva tr ON r.id_tipo_reserva = tr.id_tipo_reserva
INNER JOIN estados_solicitudes e ON r.id_estado = e.id_estado
INNER JOIN usuarios u_creador ON r.id_creador_usuario = u_creador.id_usuario
INNER JOIN canchas c ON r.id_cancha = c.id_cancha
LEFT JOIN jugadores j_titular ON r.id_titular_jugador = j_titular.id_jugador
LEFT JOIN usuarios u_titular ON j_titular.id_jugador = u_titular.id_usuario
LEFT JOIN personas_externas pe ON r.id_titular_externo = pe.id_externo;



/* DETALLE RESERVA */
DROP VIEW IF EXISTS vista_reserva_detalle;
CREATE VIEW vista_reserva_detalle AS
    SELECT
        r.id_reserva,
        r.id_cancha,
        r.id_tipo_reserva,
        r.fecha,
        r.fecha_fin,
        r.hora_inicio,
        r.hora_fin,
        r.titulo,
        r.descripcion,
        r.id_estado,
        r.fecha_solicitud,

        -- Creador de la reserva (admin que la creó)
        r.id_creador_usuario,
        u_creador.nombre AS nombre_creador,
        u_creador.apellido AS apellido_creador,
        CONCAT(u_creador.nombre, ' ', u_creador.apellido) AS creador_nombre_completo,
        
        -- Titular de la reserva (jugador o persona externa)
        r.id_titular_jugador,
        r.id_titular_externo,
        
        -- Datos del titular si es jugador
        j_titular.username AS username_titular,
        u_titular.nombre AS nombre_titular_jugador,
        u_titular.apellido AS apellido_titular_jugador,
        j_titular.telefono AS telefono_titular_jugador,
        CONCAT(u_titular.nombre, ' ', u_titular.apellido) AS titular_jugador_nombre_completo,
        
        -- Datos del titular si es persona externa
        pe.nombre AS nombre_titular_externo,
        pe.apellido AS apellido_titular_externo,
        pe.telefono AS telefono_titular_externo,
        CONCAT(pe.nombre, ' ', pe.apellido) AS titular_externo_nombre_completo,
        
        -- Nombre completo del titular (jugador o externo)
        CASE
            WHEN r.id_titular_jugador IS NOT NULL THEN CONCAT(u_titular.nombre, ' ', u_titular.apellido)
            WHEN r.id_titular_externo IS NOT NULL THEN CONCAT(pe.nombre, ' ', pe.apellido)
            ELSE 'Sin titular'
        END AS titular_nombre_completo,
        
        -- Teléfono del titular (jugador o externo)
        COALESCE(j_titular.telefono, pe.telefono) AS titular_telefono,
        
        -- Indicador de tipo de reserva
        CASE
            WHEN r.id_titular_jugador IS NOT NULL THEN 'jugador'
            WHEN r.id_titular_externo IS NOT NULL THEN 'externo'
            ELSE NULL
        END AS tipo_titular,

        tr.nombre AS tipo_reserva,
        tr.descripcion AS descripcion_tipo_reserva,
        e.nombre AS estado_reserva,
        c.nombre AS nombre_cancha,
        c.descripcion AS descripcion_cancha
    
    FROM reservas r
    INNER JOIN tipos_reserva tr ON r.id_tipo_reserva = tr.id_tipo_reserva
    INNER JOIN estados_solicitudes e ON r.id_estado = e.id_estado
    INNER JOIN usuarios u_creador ON r.id_creador_usuario = u_creador.id_usuario
    INNER JOIN canchas c ON r.id_cancha = c.id_cancha
    LEFT JOIN jugadores j_titular ON r.id_titular_jugador = j_titular.id_jugador
    LEFT JOIN usuarios u_titular ON j_titular.id_jugador = u_titular.id_usuario
    LEFT JOIN personas_externas pe ON r.id_titular_externo = pe.id_externo;
    