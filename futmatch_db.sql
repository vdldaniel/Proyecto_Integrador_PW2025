-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2025 at 01:43 AM
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
  `telefono` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_canchas`
--

INSERT INTO `admin_canchas` (`id_admin_cancha`, `id_solicitud`, `telefono`) VALUES
(1, 1, '+541155555001'),
(2, 2, '+541155555002');

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
  `id_estado` int(11) NOT NULL DEFAULT 1,
  `foto` varchar(255) DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `id_superficie` int(11) NOT NULL,
  `politicas_reservas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `canchas`
--

INSERT INTO `canchas` (`id_cancha`, `id_admin_cancha`, `id_direccion`, `nombre`, `descripcion`, `id_estado`, `foto`, `banner`, `id_superficie`, `politicas_reservas`) VALUES
(1, 1, 1, 'Cancha Centro', 'Cancha de fútbol 5 en el centro de la ciudad', 3, NULL, NULL, 1, NULL),
(2, 2, 2, 'Cancha Norte', 'Complejo deportivo con múltiples canchas', 3, NULL, NULL, 1, NULL),
(3, 1, 4, 'Cancha Centro 2', 'Segunda sede del complejo centro', 3, NULL, NULL, 2, NULL);

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
(2, 2, 1, '2025-11-13 00:16:20'),
(3, 1, 1, '2025-11-13 00:16:20'),
(3, 4, 1, '2025-11-13 00:16:20');

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
(5, '9 de Julio 123, Mendoza', -32.88945800, -68.84583900, 'Argentina', 'Mendoza', 'Mendoza');

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
(4, 4, 'Domingueross', 'uploads/equipos/equipo_1763336255_691a603f90291.jpg', NULL, 1, 'Nos juntamos a jugar futbol los domingos en CABA', 6, '2025-11-16 20:52:27');

-- --------------------------------------------------------

--
-- Table structure for table `equipos_partidos`
--

DROP TABLE IF EXISTS `equipos_partidos`;
CREATE TABLE `equipos_partidos` (
  `id_equipo` int(11) NOT NULL,
  `id_partido` int(11) NOT NULL,
  `goles_anotados` int(11) DEFAULT 0,
  `es_ganador` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `equipos_partidos`
--

INSERT INTO `equipos_partidos` (`id_equipo`, `id_partido`, `goles_anotados`, `es_ganador`) VALUES
(1, 4, 0, 0),
(2, 4, 0, 0);

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
(5, 'Cancelada');

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
  `hora_apertura` time NOT NULL,
  `hora_cierre` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `horarios_cancha`
--

INSERT INTO `horarios_cancha` (`id_horario`, `id_cancha`, `id_dia`, `hora_apertura`, `hora_cierre`) VALUES
(1, 1, 1, '08:00:00', '22:00:00'),
(2, 1, 2, '08:00:00', '22:00:00'),
(3, 1, 3, '08:00:00', '22:00:00'),
(4, 1, 4, '08:00:00', '22:00:00'),
(5, 1, 5, '08:00:00', '22:00:00'),
(6, 1, 6, '09:00:00', '24:00:00'),
(7, 1, 7, '09:00:00', '24:00:00'),
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
(19, 3, 5, '09:00:00', '21:00:00');

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
  `reputacion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jugadores`
--

INSERT INTO `jugadores` (`id_jugador`, `username`, `telefono`, `foto_perfil`, `banner`, `fecha_nacimiento`, `id_sexo`, `id_posicion`, `reputacion`) VALUES
(1, 'juanpe', '+541123456789', NULL, NULL, '1995-03-15', 2, 3, NULL),
(2, 'mariag', '+541123456790', NULL, NULL, '1992-07-20', 1, 2, NULL),
(3, 'carlosl', '+541123456791', NULL, NULL, '1988-11-05', 2, 1, NULL),
(4, 'anam', '+541123456792', NULL, NULL, '1996-09-12', 1, 4, NULL),
(5, 'diegor', '+541123456793', NULL, NULL, '1990-01-25', 2, 2, NULL),
(6, 'lauraf', '+541123456794', NULL, NULL, '1994-05-30', 1, 3, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jugadores_equipos`
--

DROP TABLE IF EXISTS `jugadores_equipos`;
CREATE TABLE `jugadores_equipos` (
  `id_jugador` int(11) NOT NULL,
  `id_equipo` int(11) NOT NULL,
  `estado_solicitud` int(11) NOT NULL,
  `invitado_por` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jugadores_equipos`
--

INSERT INTO `jugadores_equipos` (`id_jugador`, `id_equipo`, `estado_solicitud`, `invitado_por`) VALUES
(1, 1, 3, 1),
(1, 3, 3, 4),
(1, 4, 3, 4),
(2, 1, 3, 1),
(3, 2, 3, 2),
(4, 1, 3, 2),
(4, 4, 3, 6),
(5, 2, 3, 3),
(5, 3, 3, 2),
(6, 3, 3, 1),
(6, 4, 1, 4);

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
  `equipo` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `participantes_partidos`
--

INSERT INTO `participantes_partidos` (`id_participante`, `id_partido`, `id_jugador`, `nombre_invitado`, `id_rol`, `id_estado`, `equipo`) VALUES
(1, 1, 1, NULL, 1, 3, 1),
(2, 1, 2, NULL, 2, 3, 2),
(3, 1, 4, NULL, 3, 1, 2),
(4, 2, 3, NULL, 1, 3, 1),
(5, 2, 5, NULL, 2, 3, 1),
(6, 3, 5, NULL, 1, 3, 1),
(7, 4, 1, NULL, 1, 3, 1),
(8, 4, 3, NULL, 2, 3, 2),
(9, 4, 5, NULL, 2, 3, 2),
(10, 4, 2, NULL, 2, 3, 1),
(11, 4, 4, NULL, 2, 3, 1),
(12, 2, 1, NULL, 3, 1, NULL),
(13, 2, 4, NULL, 3, 1, NULL),
(14, 2, 6, NULL, 3, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `partidos`
--

DROP TABLE IF EXISTS `partidos`;
CREATE TABLE `partidos` (
  `id_partido` int(11) NOT NULL,
  `id_anfitrion` int(11) NOT NULL,
  `id_tipo_partido` int(11) NOT NULL DEFAULT 1,
  `abierto` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partidos`
--

INSERT INTO `partidos` (`id_partido`, `id_anfitrion`, `id_tipo_partido`, `abierto`) VALUES
(1, 1, 1, 0),
(2, 3, 1, 1),
(3, 5, 2, 1),
(4, 1, 1, 0);

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
(4, 5);

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
  `orden_en_fase` int(11) DEFAULT NULL
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
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `titulo` varchar(50) DEFAULT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `id_estado` int(11) NOT NULL DEFAULT 1,
  `fecha_solicitud` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reservas`
--

INSERT INTO `reservas` (`id_reserva`, `id_cancha`, `id_tipo_reserva`, `fecha`, `hora_inicio`, `hora_fin`, `titulo`, `descripcion`, `id_estado`, `fecha_solicitud`) VALUES
(1, 1, 1, '2025-11-15', '18:00:00', '19:00:00', NULL, '', 3, '2025-11-13 00:16:20'),
(2, 1, 1, '2025-11-16', '20:00:00', '21:00:00', 'Partido Casual', '', 1, '2025-11-13 00:16:20'),
(3, 2, 1, '2025-11-17', '19:00:00', '20:00:00', 'Fútbol Femenino', 'Partido amistoso para pasar un buen rato. Todos los niveles son bienvenidos. Se juega con reglas estándar de fútbol 5.', 3, '2025-11-13 00:16:20'),
(4, 2, 1, '2025-11-18', '21:00:00', '22:00:00', NULL, '', 1, '2025-11-13 00:16:20'),
(5, 2, 1, '2025-11-14', '11:00:00', '12:00:00', 'Partido entre 2 equipos', '', 3, '2025-11-13 11:18:11');

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

--
-- Dumping data for table `reservas_usuarios`
--

INSERT INTO `reservas_usuarios` (`id_reserva`, `id_usuario`, `id_rol`) VALUES
(1, 1, 1),
(2, 3, 1),
(3, 5, 1),
(4, 2, 1);

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
(3, 'admin_sistema_viewer'),
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
(3, 'Miguel', 'Torres', 'miguel.torres@email.com', '+541155555003', 'Complejo Sur', 3, '2025-11-13 00:16:20', 1, NULL, NULL, NULL);

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
(1, 'jugador', 'Reserva realizada por un jugador desde la app'),
(2, 'torneo', 'Reserva del admin para partido de torneo'),
(3, 'mantenimiento', 'Reserva del admin para mantenimiento/limpieza'),
(4, 'evento', 'Reserva del admin para evento especial');

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
(9, 'Patricia', 'Morales', 'cancha.norte@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, '2025-11-13 00:16:20');

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
(7, 7, 4),
(8, 8, 2),
(9, 9, 2);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vista_bracket_torneos`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vista_bracket_torneos`;
CREATE TABLE `vista_bracket_torneos` (
`id_torneo` int(11)
,`torneo_nombre` varchar(100)
,`id_fase` int(11)
,`fase_nombre` varchar(50)
,`fase_numero` int(11)
,`orden_en_fase` int(11)
,`id_partido` int(11)
,`equipos_resultado` mediumtext
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vista_equipos_jugador`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vista_equipos_jugador`;
CREATE TABLE `vista_equipos_jugador` (
`id_equipo` int(11)
,`id_lider` int(11)
,`nombre_equipo` varchar(50)
,`foto_equipo` varchar(250)
,`abierto` tinyint(1)
,`clave` varchar(10)
,`descripcion` varchar(200)
,`nombre_lider` varchar(100)
,`apellido_lider` varchar(100)
,`cantidad_integrantes` bigint(21)
,`id_jugador` int(11)
,`torneos_participados` bigint(21)
,`partidos_jugados` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vista_estadisticas_equipos_torneo`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vista_estadisticas_equipos_torneo`;
CREATE TABLE `vista_estadisticas_equipos_torneo` (
`id_torneo` int(11)
,`id_equipo` int(11)
,`equipo_nombre` varchar(50)
,`partidos_jugados` bigint(21)
,`goles_a_favor` decimal(32,0)
,`victorias` decimal(22,0)
,`derrotas` decimal(23,0)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vista_explorar_canchas`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vista_explorar_canchas`;
CREATE TABLE `vista_explorar_canchas` (
`id_cancha` int(11)
,`nombre_cancha` varchar(100)
,`foto_cancha` varchar(255)
,`descripcion_cancha` varchar(250)
,`direccion_completa` varchar(500)
,`latitud` decimal(10,8)
,`longitud` decimal(11,8)
,`tipo_superficie` varchar(100)
,`tipo_partido_max` varchar(50)
,`calificacion_promedio` decimal(14,4)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vista_explorar_partidos`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vista_explorar_partidos`;
CREATE TABLE `vista_explorar_partidos` (
`id_partido` int(11)
,`id_anfitrion` int(11)
,`abierto` tinyint(1)
,`id_reserva` int(11)
,`fecha_partido` date
,`fecha_partido_formato` varchar(10)
,`dia_semana` varchar(9)
,`hora_inicio` varchar(10)
,`hora_fin` varchar(10)
,`hora_inicio_raw` time
,`titulo` varchar(50)
,`descripcion` varchar(200)
,`id_tipo_reserva` int(11)
,`id_cancha` int(11)
,`nombre_cancha` varchar(100)
,`foto_cancha` varchar(255)
,`direccion_completa` varchar(500)
,`latitud` decimal(10,8)
,`longitud` decimal(11,8)
,`id_tipo_partido` int(11)
,`tipo_partido` varchar(50)
,`min_participantes` int(11)
,`max_participantes` int(11)
,`tipo_superficie` varchar(100)
,`id_jugador_anfitrion` int(11)
,`username_anfitrion` varchar(20)
,`participantes_actuales` bigint(21)
,`cant_participantes_equipo_A` bigint(21)
,`cant_participantes_equipo_B` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vista_partidos_jugador`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vista_partidos_jugador`;
CREATE TABLE `vista_partidos_jugador` (
`id_jugador` int(11)
,`mi_username` varchar(20)
,`id_partido` int(11)
,`id_anfitrion` int(11)
,`abierto` tinyint(1)
,`id_reserva` int(11)
,`fecha_partido` varchar(10)
,`dia_semana` varchar(9)
,`hora_partido` varchar(10)
,`hora_fin` varchar(10)
,`id_tipo_reserva` int(11)
,`id_cancha` int(11)
,`nombre_cancha` varchar(100)
,`direccion_cancha` varchar(500)
,`latitud_cancha` decimal(10,8)
,`longitud_cancha` decimal(11,8)
,`id_estado` int(11)
,`estado_solicitud` varchar(50)
,`id_rol` int(11)
,`rol_usuario` varchar(50)
,`id_tipo_partido` int(11)
,`tipo_partido` varchar(50)
,`min_participantes` int(11)
,`max_participantes` int(11)
,`equipo_asignado` smallint(6)
,`cant_participantes_equipo_a` bigint(21)
,`cant_participantes_equipo_b` bigint(21)
,`id_equipo_del_jugador` int(11)
,`nombre_equipo_del_jugador` varchar(50)
,`foto_equipo_del_jugador` varchar(250)
,`goles_mi_equipo` int(11)
,`mi_equipo_gano` tinyint(1)
,`id_equipo_rival` int(11)
,`nombre_equipo_rival` varchar(50)
,`foto_equipo_rival` varchar(250)
,`goles_equipo_rival` int(11)
,`equipo_rival_gano` tinyint(1)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vista_usuarios`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vista_usuarios`;
CREATE TABLE `vista_usuarios` (
`id_usuario` int(11)
,`nombre` varchar(100)
,`apellido` varchar(100)
,`email` varchar(150)
,`roles` mediumtext
,`estado` varchar(50)
,`fecha_registro` datetime
);

-- --------------------------------------------------------

--
-- Structure for view `vista_bracket_torneos`
--
DROP TABLE IF EXISTS `vista_bracket_torneos`;

DROP VIEW IF EXISTS `vista_bracket_torneos`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_bracket_torneos`  AS SELECT `t`.`id_torneo` AS `id_torneo`, `t`.`nombre` AS `torneo_nombre`, `pt`.`id_fase` AS `id_fase`, `f`.`nombre` AS `fase_nombre`, `f`.`n` AS `fase_numero`, `pt`.`orden_en_fase` AS `orden_en_fase`, `p`.`id_partido` AS `id_partido`, group_concat(concat(`eq`.`nombre`,':',`ep`.`goles_anotados`,if(`ep`.`es_ganador`,' (G)','')) order by `ep`.`es_ganador` DESC separator ' vs ') AS `equipos_resultado` FROM (((((`torneos` `t` join `partidos_torneos` `pt` on(`t`.`id_torneo` = `pt`.`id_torneo`)) join `fases_torneo` `f` on(`pt`.`id_fase` = `f`.`id_fase`)) join `partidos` `p` on(`pt`.`id_partido` = `p`.`id_partido`)) join `equipos_partidos` `ep` on(`p`.`id_partido` = `ep`.`id_partido`)) join `equipos` `eq` on(`ep`.`id_equipo` = `eq`.`id_equipo`)) GROUP BY `t`.`id_torneo`, `pt`.`id_fase`, `p`.`id_partido` ORDER BY `t`.`id_torneo` ASC, `f`.`n` ASC, `pt`.`orden_en_fase` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `vista_equipos_jugador`
--
DROP TABLE IF EXISTS `vista_equipos_jugador`;

DROP VIEW IF EXISTS `vista_equipos_jugador`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_equipos_jugador`  AS SELECT `e`.`id_equipo` AS `id_equipo`, `e`.`id_lider` AS `id_lider`, `e`.`nombre` AS `nombre_equipo`, `e`.`foto` AS `foto_equipo`, `e`.`abierto` AS `abierto`, `e`.`clave` AS `clave`, `e`.`descripcion` AS `descripcion`, `u`.`nombre` AS `nombre_lider`, `u`.`apellido` AS `apellido_lider`, (select count(0) from `jugadores_equipos` `je` where `je`.`id_equipo` = `e`.`id_equipo`) AS `cantidad_integrantes`, `je`.`id_jugador` AS `id_jugador`, (select count(distinct `t`.`id_torneo`) from (`torneos` `t` join `equipos_torneos` `et` on(`t`.`id_torneo` = `et`.`id_torneo`)) where `et`.`id_equipo` = `e`.`id_equipo`) AS `torneos_participados`, (select count(0) from `equipos_partidos` `ep` where `ep`.`id_equipo` = `e`.`id_equipo`) AS `partidos_jugados` FROM ((`equipos` `e` join `usuarios` `u` on(`e`.`id_lider` = `u`.`id_usuario`)) join `jugadores_equipos` `je` on(`e`.`id_equipo` = `je`.`id_equipo`)) ;

-- --------------------------------------------------------

--
-- Structure for view `vista_estadisticas_equipos_torneo`
--
DROP TABLE IF EXISTS `vista_estadisticas_equipos_torneo`;

DROP VIEW IF EXISTS `vista_estadisticas_equipos_torneo`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_estadisticas_equipos_torneo`  AS SELECT `et`.`id_torneo` AS `id_torneo`, `et`.`id_equipo` AS `id_equipo`, `eq`.`nombre` AS `equipo_nombre`, count(`ep`.`id_partido`) AS `partidos_jugados`, sum(`ep`.`goles_anotados`) AS `goles_a_favor`, sum(case when `ep`.`es_ganador` then 1 else 0 end) AS `victorias`, count(`ep`.`id_partido`) - sum(case when `ep`.`es_ganador` then 1 else 0 end) AS `derrotas` FROM (((`equipos_torneos` `et` join `equipos` `eq` on(`et`.`id_equipo` = `eq`.`id_equipo`)) left join `equipos_partidos` `ep` on(`et`.`id_equipo` = `ep`.`id_equipo`)) left join `partidos_torneos` `pt` on(`ep`.`id_partido` = `pt`.`id_partido` and `pt`.`id_torneo` = `et`.`id_torneo`)) WHERE `et`.`id_estado` = 3 GROUP BY `et`.`id_torneo`, `et`.`id_equipo`, `eq`.`nombre` ;

-- --------------------------------------------------------

--
-- Structure for view `vista_explorar_canchas`
--
DROP TABLE IF EXISTS `vista_explorar_canchas`;

DROP VIEW IF EXISTS `vista_explorar_canchas`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_explorar_canchas`  AS SELECT `c`.`id_cancha` AS `id_cancha`, `c`.`nombre` AS `nombre_cancha`, `c`.`foto` AS `foto_cancha`, `c`.`descripcion` AS `descripcion_cancha`, `d`.`direccion_completa` AS `direccion_completa`, `d`.`latitud` AS `latitud`, `d`.`longitud` AS `longitud`, `s`.`nombre` AS `tipo_superficie`, (select `tp_max`.`nombre` from (`canchas_tipos_partido` `ctp` join `tipos_partido` `tp_max` on(`ctp`.`id_tipo_partido` = `tp_max`.`id_tipo_partido`)) where `ctp`.`id_cancha` = `c`.`id_cancha` order by `tp_max`.`max_participantes` desc limit 1) AS `tipo_partido_max`, (select avg(`r`.`calificacion`) from `resenias_canchas` `r` where `r`.`id_cancha` = `c`.`id_cancha`) AS `calificacion_promedio` FROM ((`canchas` `c` join `direcciones` `d` on(`c`.`id_direccion` = `d`.`id_direccion`)) join `superficies_canchas` `s` on(`c`.`id_superficie` = `s`.`id_superficie`)) ;

-- --------------------------------------------------------

--
-- Structure for view `vista_explorar_partidos`
--
DROP TABLE IF EXISTS `vista_explorar_partidos`;

DROP VIEW IF EXISTS `vista_explorar_partidos`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_explorar_partidos`  AS SELECT `p`.`id_partido` AS `id_partido`, `p`.`id_anfitrion` AS `id_anfitrion`, `p`.`abierto` AS `abierto`, `r`.`id_reserva` AS `id_reserva`, `r`.`fecha` AS `fecha_partido`, date_format(`r`.`fecha`,'%d/%m/%Y') AS `fecha_partido_formato`, CASE ENDselect count(0) END FROM `participantes_partidos` AS `pp` WHERE `pp`.`id_partido` = `p`.`id_partido`select count(0) from `participantes_partidos` `pp` where `pp`.`id_partido` = `p`.`id_partido` and `pp`.`equipo` = 1) AS `cant_participantes_equipo_A`,(select count(0) from `participantes_partidos` `pp` where `pp`.`id_partido` = `p`.`id_partido` and `pp`.`equipo` = 2) AS `cant_participantes_equipo_B` from (((((((`partidos` `p` join `partidos_reservas` `pr` on(`p`.`id_partido` = `pr`.`id_partido`)) join `reservas` `r` on(`pr`.`id_reserva` = `r`.`id_reserva`)) join `canchas` `c` on(`r`.`id_cancha` = `c`.`id_cancha`)) join `direcciones` `d` on(`c`.`id_direccion` = `d`.`id_direccion`)) join `tipos_partido` `tp` on(`p`.`id_tipo_partido` = `tp`.`id_tipo_partido`)) join `superficies_canchas` `s` on(`c`.`id_superficie` = `s`.`id_superficie`)) join `jugadores` `j_anfitrion` on(`p`.`id_anfitrion` = `j_anfitrion`.`id_jugador`)) where `p`.`abierto` = 1 and `r`.`fecha` >= curdate() and `r`.`id_tipo_reserva` = 1 order by `r`.`fecha`,`r`.`hora_inicio`  ;

-- --------------------------------------------------------

--
-- Structure for view `vista_partidos_jugador`
--
DROP TABLE IF EXISTS `vista_partidos_jugador`;

DROP VIEW IF EXISTS `vista_partidos_jugador`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_partidos_jugador`  AS SELECT `pp`.`id_jugador` AS `id_jugador`, `j`.`username` AS `mi_username`, `p`.`id_partido` AS `id_partido`, `p`.`id_anfitrion` AS `id_anfitrion`, `p`.`abierto` AS `abierto`, `r`.`id_reserva` AS `id_reserva`, date_format(`r`.`fecha`,'%d/%m/%Y') AS `fecha_partido`, CASE ENDselect count(0) END FROM `participantes_partidos` AS `pp_a` WHERE `pp_a`.`id_partido` = `p`.`id_partido` AND `pp_a`.`equipo` = 1select count(0) from `participantes_partidos` `pp_b` where `pp_b`.`id_partido` = `p`.`id_partido` and `pp_b`.`equipo` = 2) AS `cant_participantes_equipo_b`,`e_propio`.`id_equipo` AS `id_equipo_del_jugador`,`e_propio`.`nombre` AS `nombre_equipo_del_jugador`,`e_propio`.`foto` AS `foto_equipo_del_jugador`,`ep_propio`.`goles_anotados` AS `goles_mi_equipo`,`ep_propio`.`es_ganador` AS `mi_equipo_gano`,`e_rival`.`id_equipo` AS `id_equipo_rival`,`e_rival`.`nombre` AS `nombre_equipo_rival`,`e_rival`.`foto` AS `foto_equipo_rival`,`ep_rival`.`goles_anotados` AS `goles_equipo_rival`,`ep_rival`.`es_ganador` AS `equipo_rival_gano` from (((((((((((((`participantes_partidos` `pp` join `jugadores` `j` on(`pp`.`id_jugador` = `j`.`id_jugador`)) join `partidos` `p` on(`pp`.`id_partido` = `p`.`id_partido`)) join `tipos_partido` `tp` on(`p`.`id_tipo_partido` = `tp`.`id_tipo_partido`)) join `estados_solicitudes` `es` on(`pp`.`id_estado` = `es`.`id_estado`)) join `roles_partidos` `rp` on(`pp`.`id_rol` = `rp`.`id_rol`)) left join `partidos_reservas` `pr` on(`p`.`id_partido` = `pr`.`id_partido`)) left join `reservas` `r` on(`pr`.`id_reserva` = `r`.`id_reserva`)) left join `canchas` `c` on(`r`.`id_cancha` = `c`.`id_cancha`)) left join `direcciones` `d` on(`c`.`id_direccion` = `d`.`id_direccion`)) left join `equipos_partidos` `ep_propio` on(`p`.`id_partido` = `ep_propio`.`id_partido` and `ep_propio`.`id_equipo` in (select `je`.`id_equipo` from `jugadores_equipos` `je` where `je`.`id_jugador` = `pp`.`id_jugador`))) left join `equipos` `e_propio` on(`ep_propio`.`id_equipo` = `e_propio`.`id_equipo`)) left join `equipos_partidos` `ep_rival` on(`p`.`id_partido` = `ep_rival`.`id_partido` and `ep_rival`.`id_equipo` <> `ep_propio`.`id_equipo`)) left join `equipos` `e_rival` on(`ep_rival`.`id_equipo` = `e_rival`.`id_equipo`))  ;

-- --------------------------------------------------------

--
-- Structure for view `vista_usuarios`
--
DROP TABLE IF EXISTS `vista_usuarios`;

DROP VIEW IF EXISTS `vista_usuarios`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_usuarios`  AS SELECT `u`.`id_usuario` AS `id_usuario`, `u`.`nombre` AS `nombre`, `u`.`apellido` AS `apellido`, `u`.`email` AS `email`, group_concat(`r`.`nombre` separator ', ') AS `roles`, `e`.`nombre` AS `estado`, `u`.`fecha_registro` AS `fecha_registro` FROM (((`usuarios` `u` left join `usuarios_roles` `ur` on(`u`.`id_usuario` = `ur`.`id_usuario`)) left join `roles` `r` on(`ur`.`id_rol` = `r`.`id_rol`)) join `estados_usuarios` `e` on(`u`.`id_estado` = `e`.`id_estado`)) GROUP BY `u`.`id_usuario`, `u`.`nombre`, `u`.`apellido`, `u`.`email`, `e`.`nombre`, `u`.`fecha_registro` ;

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
  ADD KEY `idx_torneo_fase` (`id_torneo`,`id_fase`);

--
-- Indexes for table `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id_permiso`);

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
  ADD KEY `idx_reservas_fecha_cancha` (`fecha`,`id_cancha`);

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
  MODIFY `id_admin_cancha` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admin_sistema`
--
ALTER TABLE `admin_sistema`
  MODIFY `id_admin_sistema` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `calificaciones_jugadores`
--
ALTER TABLE `calificaciones_jugadores`
  MODIFY `id_calificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `canchas`
--
ALTER TABLE `canchas`
  MODIFY `id_cancha` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dias_semana`
--
ALTER TABLE `dias_semana`
  MODIFY `id_dia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `direcciones`
--
ALTER TABLE `direcciones`
  MODIFY `id_direccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `equipos`
--
ALTER TABLE `equipos`
  MODIFY `id_equipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `estadisticas_partido`
--
ALTER TABLE `estadisticas_partido`
  MODIFY `id_estadistica` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `estados_canchas`
--
ALTER TABLE `estados_canchas`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `estados_solicitudes`
--
ALTER TABLE `estados_solicitudes`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id_horario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `jugadores`
--
ALTER TABLE `jugadores`
  MODIFY `id_jugador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `observaciones_canchas`
--
ALTER TABLE `observaciones_canchas`
  MODIFY `id_observacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `participantes_partidos`
--
ALTER TABLE `participantes_partidos`
  MODIFY `id_participante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `partidos`
--
ALTER TABLE `partidos`
  MODIFY `id_partido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id_solicitud` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id_tipo_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `torneos`
--
ALTER TABLE `torneos`
  MODIFY `id_torneo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
