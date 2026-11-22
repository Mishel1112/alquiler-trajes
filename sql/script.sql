-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 20-11-2026 a las 23:43:05
-- Versión del servidor: 8.4.7
-- Versión de PHP: 8.4.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bd_alquiler_trajes`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alquiler`
--

DROP TABLE IF EXISTS `alquiler`;
CREATE TABLE IF NOT EXISTS `alquiler` (
  `idAlquiler` int NOT NULL AUTO_INCREMENT,
  `idCliente` int NOT NULL,
  `idGarantia` int NOT NULL,
  `idUsuario` int NOT NULL,
  `f_recogida` datetime NOT NULL,
  `f_devolucion` datetime NOT NULL,
  `estado` enum('Reservado','Alquilado','Finalizado','Cancelado') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_operacion` enum('Alquiler','Reserva') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idAlquiler`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `alquiler`
--

INSERT INTO `alquiler` (`idAlquiler`, `idCliente`, `idGarantia`, `idUsuario`, `f_recogida`, `f_devolucion`, `estado`, `tipo_operacion`, `created_at`) VALUES
(1, 1, 113, 1, '2026-11-20 18:51:37', '2026-11-21 00:00:00', 'Alquilado', 'Alquiler', '2026-11-20 22:51:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

DROP TABLE IF EXISTS `categoria`;
CREATE TABLE IF NOT EXISTS `categoria` (
  `idCategoria` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idCategoria`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`idCategoria`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'Trajes típicos', 'Trajes tradicionales y folclóricos de la región.', NULL, NULL),
(2, 'Trajes internacionales', 'Vestimenta representativa de otros países.', NULL, NULL),
(3, 'Disfraces', 'Trajes temáticos para fiestas y eventos especiales.', NULL, NULL),
(4, 'Trajes de gala', 'Ropa formal y elegante para ocasiones de gala.', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

DROP TABLE IF EXISTS `cliente`;
CREATE TABLE IF NOT EXISTS `cliente` (
  `idCliente` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_cliente` enum('estudiante','fraternidad','publico general','coreografo') COLLATE utf8mb4_unicode_ci NOT NULL,
  `fraternidad` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `colegio` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `curso` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lugar_baile` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idCliente`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`idCliente`, `nombre`, `telefono`, `tipo_cliente`, `fraternidad`, `colegio`, `curso`, `lugar_baile`, `created_at`, `updated_at`) VALUES
(1, 'Franco Lopez', '78764576', 'estudiante', NULL, 'Jose Mariano Serrano', '1° Secundaria B', NULL, '2026-11-20 22:51:37', '2026-11-20 22:51:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_alquiler`
--

DROP TABLE IF EXISTS `detalle_alquiler`;
CREATE TABLE IF NOT EXISTS `detalle_alquiler` (
  `idDetalleAlquiler` int NOT NULL AUTO_INCREMENT,
  `idAlquiler` int NOT NULL,
  `idTraje` int NOT NULL,
  `cantidad` int DEFAULT '1',
  `cantidadPiezas` int NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `subTotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idDetalleAlquiler`),
  KEY `idAlquiler` (`idAlquiler`),
  KEY `idTraje` (`idTraje`)
) ENGINE=MyISAM AUTO_INCREMENT=122 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_alquiler`
--

INSERT INTO `detalle_alquiler` (`idDetalleAlquiler`, `idAlquiler`, `idTraje`, `cantidad`, `cantidadPiezas`, `precio`, `subTotal`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 30.00, 30.00, '2026-10-22 00:08:54', '2026-10-22 00:08:54'),
(2, 2, 2, 1, 5, 30.00, 30.00, '2026-10-23 15:43:11', '2026-10-23 15:43:11'),
(3, 3, 1, 1, 1, 30.00, 30.00, '2026-10-23 16:02:05', '2026-10-23 16:02:05'),
(4, 4, 3, 1, 1, 45.00, 45.00, '2026-10-23 16:46:55', '2026-10-23 16:46:55'),
(5, 5, 1, 1, 1, 30.00, 30.00, '2026-10-23 19:48:19', '2026-10-23 19:48:19'),
(6, 6, 1, 1, 1, 30.00, 30.00, '2026-10-23 19:48:19', '2026-10-23 19:48:19'),
(7, 7, 3, 1, 1, 45.00, 45.00, '2026-10-23 22:26:44', '2026-10-23 22:26:44'),
(8, 8, 3, 1, 1, 45.00, 45.00, '2026-10-23 23:56:33', '2026-10-23 23:56:33'),
(9, 9, 3, 1, 1, 45.00, 45.00, '2026-10-24 00:15:35', '2026-10-24 00:15:35'),
(10, 10, 1, 1, 1, 30.00, 30.00, '2026-10-24 00:28:18', '2026-10-24 00:28:18'),
(11, 11, 3, 1, 1, 45.00, 45.00, '2026-10-24 01:22:32', '2026-10-24 01:22:32'),
(12, 12, 3, 1, 1, 45.00, 45.00, '2026-10-24 04:17:53', '2026-10-24 04:17:53'),
(13, 13, 6, 1, 1, 35.00, 35.00, '2026-10-25 00:06:34', '2026-10-25 00:06:34'),
(14, 14, 2, 1, 5, 30.00, 30.00, '2026-10-25 04:23:10', '2026-10-25 04:23:10'),
(15, 15, 2, 1, 5, 30.00, 30.00, '2026-10-25 04:28:51', '2026-10-25 04:28:51'),
(16, 16, 2, 1, 5, 30.00, 30.00, '2026-10-25 04:29:23', '2026-10-25 04:29:23'),
(17, 17, 2, 1, 5, 30.00, 30.00, '2026-10-25 04:51:57', '2026-10-25 04:51:57'),
(18, 18, 14, 1, 1, 35.00, 35.00, '2026-10-25 05:21:43', '2026-10-25 05:21:43'),
(19, 19, 3, 1, 1, 45.00, 45.00, '2026-10-25 05:35:15', '2026-10-25 05:35:15'),
(20, 20, 2, 1, 5, 30.00, 60.00, '2026-10-25 20:03:31', '2026-10-25 20:03:31'),
(21, 20, 3, 1, 1, 45.00, 45.00, '2026-10-25 20:03:31', '2026-10-25 20:03:31'),
(22, 21, 2, 1, 5, 30.00, 60.00, '2026-10-25 21:44:13', '2026-10-25 21:44:13'),
(23, 21, 14, 1, 1, 35.00, 35.00, '2026-10-25 21:44:13', '2026-10-25 21:44:13'),
(24, 21, 16, 1, 1, 70.00, 70.00, '2026-10-25 21:44:13', '2026-10-25 21:44:13'),
(25, 22, 14, 1, 1, 35.00, 35.00, '2026-10-25 22:03:17', '2026-10-25 22:03:17'),
(26, 23, 3, 1, 1, 45.00, 45.00, '2026-10-25 22:05:12', '2026-10-25 22:05:12'),
(27, 23, 5, 1, 1, 35.00, 35.00, '2026-10-25 22:05:12', '2026-10-25 22:05:12'),
(28, 23, 14, 1, 1, 35.00, 35.00, '2026-10-25 22:05:12', '2026-10-25 22:05:12'),
(29, 24, 5, 1, 1, 35.00, 105.00, '2026-10-26 02:21:49', '2026-10-26 02:21:49'),
(30, 24, 6, 1, 1, 35.00, 35.00, '2026-10-26 02:21:49', '2026-10-26 02:21:49'),
(31, 24, 2, 1, 5, 30.00, 210.00, '2026-10-26 02:21:49', '2026-10-26 02:21:49'),
(32, 25, 5, 1, 1, 35.00, 105.00, '2026-10-26 02:26:02', '2026-10-26 02:26:02'),
(33, 25, 6, 1, 1, 35.00, 35.00, '2026-10-26 02:26:02', '2026-10-26 02:26:02'),
(34, 25, 2, 1, 5, 30.00, 210.00, '2026-10-26 02:26:02', '2026-10-26 02:26:02'),
(35, 26, 5, 1, 1, 35.00, 105.00, '2026-10-26 02:34:03', '2026-10-26 02:34:03'),
(36, 26, 6, 1, 1, 35.00, 35.00, '2026-10-26 02:34:03', '2026-10-26 02:34:03'),
(37, 26, 2, 1, 5, 30.00, 210.00, '2026-10-26 02:34:03', '2026-10-26 02:34:03'),
(38, 27, 5, 1, 1, 35.00, 105.00, '2026-10-26 02:38:21', '2026-10-26 02:38:21'),
(39, 27, 6, 1, 1, 35.00, 35.00, '2026-10-26 02:38:21', '2026-10-26 02:38:21'),
(40, 27, 2, 1, 5, 30.00, 210.00, '2026-10-26 02:38:21', '2026-10-26 02:38:21'),
(41, 28, 5, 1, 1, 35.00, 105.00, '2026-10-26 02:38:50', '2026-10-26 02:38:50'),
(42, 28, 6, 1, 1, 35.00, 35.00, '2026-10-26 02:38:50', '2026-10-26 02:38:50'),
(43, 28, 2, 1, 5, 30.00, 210.00, '2026-10-26 02:38:50', '2026-10-26 02:38:50'),
(44, 29, 2, 1, 5, 30.00, 30.00, '2026-10-26 02:40:30', '2026-10-26 02:40:30'),
(45, 30, 4, 1, 1, 25.00, 25.00, '2026-10-26 02:43:01', '2026-10-26 02:43:01'),
(46, 33, 1, 7, 3, 30.00, 210.00, '2026-10-26 04:16:08', '2026-10-26 04:16:08'),
(47, 34, 4, 1, 1, 25.00, 25.00, '2026-10-26 04:16:50', '2026-10-26 04:16:50'),
(48, 35, 4, 1, 1, 25.00, 25.00, '2026-10-26 04:21:30', '2026-10-26 04:21:30'),
(49, 36, 4, 1, 1, 25.00, 25.00, '2026-10-26 04:23:30', '2026-10-26 04:23:30'),
(50, 37, 4, 1, 1, 25.00, 25.00, '2026-10-26 04:23:31', '2026-10-26 04:23:31'),
(51, 38, 12, 1, 1, 40.00, 40.00, '2026-10-26 04:32:35', '2026-10-26 04:32:35'),
(52, 39, 12, 1, 1, 40.00, 40.00, '2026-10-26 04:32:41', '2026-10-26 04:32:41'),
(53, 40, 12, 1, 1, 40.00, 40.00, '2026-10-26 04:36:47', '2026-10-26 04:36:47'),
(54, 41, 2, 3, 5, 30.00, 90.00, '2026-10-26 04:37:59', '2026-10-26 04:37:59'),
(55, 42, 5, 1, 1, 35.00, 35.00, '2026-10-26 04:39:04', '2026-10-26 04:39:04'),
(56, 43, 2, 6, 5, 30.00, 180.00, '2026-10-26 04:50:57', '2026-10-26 04:50:57'),
(57, 45, 2, 1, 8, 30.00, 30.00, '2026-10-26 05:54:52', '2026-10-26 05:54:52'),
(58, 46, 2, 2, 8, 30.00, 60.00, '2026-10-26 06:12:32', '2026-10-26 06:12:32'),
(59, 47, 2, 2, 8, 30.00, 60.00, '2026-10-26 06:17:20', '2026-10-26 06:17:20'),
(60, 48, 2, 1, 8, 30.00, 30.00, '2026-10-26 16:46:42', '2026-10-26 16:46:42'),
(61, 48, 4, 1, 1, 25.00, 25.00, '2026-10-26 16:46:42', '2026-10-26 16:46:42'),
(62, 49, 6, 1, 1, 35.00, 35.00, '2026-10-26 16:49:44', '2026-10-26 16:49:44'),
(63, 51, 5, 1, 7, 35.00, 35.00, '2026-10-26 17:07:36', '2026-10-26 17:07:36'),
(64, 52, 2, 1, 8, 30.00, 30.00, '2026-10-26 17:08:40', '2026-10-26 17:08:40'),
(65, 53, 5, 1, 7, 35.00, 35.00, '2026-10-26 17:09:30', '2026-10-26 17:09:30'),
(66, 54, 2, 2, 8, 30.00, 60.00, '2026-10-26 17:23:58', '2026-10-26 17:23:58'),
(67, 55, 2, 2, 8, 30.00, 60.00, '2026-10-26 17:24:25', '2026-10-26 17:24:25'),
(68, 56, 1, 1, 3, 30.00, 30.00, '2026-10-26 17:26:53', '2026-10-26 17:26:53'),
(69, 58, 2, 5, 8, 30.00, 150.00, '2026-10-26 17:41:33', '2026-10-26 17:41:33'),
(70, 59, 3, 1, 8, 45.00, 45.00, '2026-10-26 17:43:21', '2026-10-26 17:43:21'),
(71, 60, 2, 5, 8, 30.00, 150.00, '2026-10-26 17:49:46', '2026-10-26 17:49:46'),
(72, 61, 2, 1, 8, 30.00, 30.00, '2026-10-26 17:54:01', '2026-10-26 17:54:01'),
(73, 62, 2, 1, 8, 30.00, 30.00, '2026-10-26 18:02:36', '2026-10-26 18:02:36'),
(74, 63, 5, 1, 7, 35.00, 35.00, '2026-10-26 18:56:24', '2026-10-26 18:56:24'),
(75, 63, 13, 1, 2, 35.00, 35.00, '2026-10-26 18:56:24', '2026-10-26 18:56:24'),
(76, 64, 3, 5, 8, 45.00, 225.00, '2026-10-26 23:49:20', '2026-10-26 23:49:20'),
(77, 65, 5, 3, 7, 35.00, 105.00, '2026-10-26 23:51:12', '2026-10-26 23:51:12'),
(78, 66, 2, 2, 8, 30.00, 60.00, '2026-10-27 03:29:31', '2026-10-27 03:29:31'),
(79, 67, 2, 1, 8, 30.00, 30.00, '2026-10-27 03:34:19', '2026-10-27 03:34:19'),
(80, 69, 2, 13, 8, 30.00, 390.00, '2026-10-27 19:49:44', '2026-10-27 19:49:44'),
(81, 70, 7, 2, 1, 40.00, 80.00, '2026-10-27 20:14:23', '2026-10-27 20:14:23'),
(82, 70, 14, 2, 1, 35.00, 70.00, '2026-10-27 20:14:23', '2026-10-27 20:14:23'),
(83, 71, 2, 4, 8, 30.00, 120.00, '2026-10-27 20:37:45', '2026-10-27 20:37:45'),
(84, 72, 16, 2, 1, 70.00, 140.00, '2026-10-27 20:40:16', '2026-10-27 20:40:16'),
(85, 73, 3, 2, 8, 45.00, 90.00, '2026-10-28 00:38:37', '2026-10-28 00:38:37'),
(86, 73, 6, 1, 1, 35.00, 35.00, '2026-10-28 00:38:37', '2026-10-28 00:38:37'),
(87, 74, 14, 4, 1, 35.00, 140.00, '2026-10-28 00:49:27', '2026-10-28 00:49:27'),
(88, 75, 14, 4, 1, 35.00, 140.00, '2026-10-28 00:51:28', '2026-10-28 00:51:28'),
(89, 76, 16, 2, 1, 70.00, 140.00, '2026-10-28 00:56:25', '2026-10-28 00:56:25'),
(90, 77, 6, 1, 1, 35.00, 35.00, '2026-10-28 01:04:28', '2026-10-28 01:04:28'),
(91, 78, 6, 1, 1, 35.00, 35.00, '2026-10-28 01:08:22', '2026-10-28 01:08:22'),
(92, 79, 1, 8, 3, 30.00, 240.00, '2026-10-28 01:11:27', '2026-10-28 01:11:27'),
(93, 80, 1, 8, 3, 30.00, 240.00, '2026-10-28 01:21:57', '2026-10-28 01:21:57'),
(94, 81, 1, 8, 3, 30.00, 240.00, '2026-10-28 01:22:41', '2026-10-28 01:22:41'),
(95, 82, 2, 2, 8, 30.00, 60.00, '2026-10-28 02:04:50', '2026-10-28 02:04:50'),
(96, 83, 3, 2, 8, 45.00, 90.00, '2026-10-28 02:06:19', '2026-10-28 02:06:19'),
(97, 84, 9, 2, 1, 30.00, 60.00, '2026-10-28 02:10:57', '2026-10-28 02:10:57'),
(101, 86, 11, 1, 1, 30.00, 30.00, '2026-10-28 16:09:57', '2026-10-28 16:09:57'),
(102, 87, 2, 2, 8, 30.00, 60.00, '2026-10-29 03:16:50', '2026-10-29 03:16:50'),
(104, 88, 5, 4, 7, 35.00, 140.00, '2026-10-29 03:27:31', '2026-10-29 03:27:31'),
(105, 89, 3, 1, 8, 45.00, 45.00, '2026-10-29 17:53:37', '2026-10-29 17:53:37'),
(106, 96, 17, 4, 7, 70.00, 280.00, '2026-10-30 19:14:10', '2026-10-30 19:14:10'),
(107, 97, 21, 5, 4, 50.00, 250.00, '2026-10-30 19:47:20', '2026-10-30 19:47:20'),
(108, 98, 20, 1, 1, 70.00, 70.00, '2026-10-30 19:51:24', '2026-10-30 19:51:24'),
(109, 99, 17, 1, 7, 70.00, 70.00, '2026-10-31 16:58:25', '2026-10-31 16:58:25'),
(120, 100, 18, 1, 4, 35.00, 35.00, '2026-11-14 19:03:33', '2026-11-14 19:03:33'),
(116, 101, 19, 1, 3, 40.00, 40.00, '2026-10-31 23:24:53', '2026-10-31 23:24:53'),
(112, 102, 18, 1, 4, 35.00, 35.00, '2026-10-31 17:48:24', '2026-10-31 17:48:24'),
(113, 102, 19, 1, 3, 40.00, 40.00, '2026-10-31 17:48:24', '2026-10-31 17:48:24'),
(114, 103, 17, 1, 7, 70.00, 70.00, '2026-10-31 23:14:43', '2026-10-31 23:14:43'),
(115, 104, 22, 2, 5, 60.00, 120.00, '2026-10-31 23:23:07', '2026-10-31 23:23:07'),
(119, 105, 24, 1, 10, 120.00, 120.00, '2026-11-01 00:50:55', '2026-11-01 00:50:55'),
(118, 106, 25, 1, 1, 35.00, 35.00, '2026-11-01 00:48:04', '2026-11-01 00:48:04'),
(121, 1, 17, 1, 7, 70.00, 70.00, '2026-11-20 22:51:37', '2026-11-20 22:51:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devolucion`
--

DROP TABLE IF EXISTS `devolucion`;
CREATE TABLE IF NOT EXISTS `devolucion` (
  `idDevolucion` int NOT NULL AUTO_INCREMENT,
  `idAlquiler` int NOT NULL,
  `f_devolucion` datetime NOT NULL,
  `f_real_devolucion` datetime NOT NULL,
  `estado` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cobroDano` decimal(10,2) DEFAULT '0.00',
  `monto_devuelto` decimal(10,2) DEFAULT '0.00',
  `observacion` text COLLATE utf8mb4_unicode_ci,
  `idUsuario` int NOT NULL,
  `f_ingreso_stock` datetime DEFAULT NULL,
  PRIMARY KEY (`idDevolucion`),
  KEY `idAlquiler` (`idAlquiler`),
  KEY `idUsuario` (`idUsuario`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `garantia`
--

DROP TABLE IF EXISTS `garantia`;
CREATE TABLE IF NOT EXISTS `garantia` (
  `idGarantia` int NOT NULL AUTO_INCREMENT,
  `tipo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `monto` int NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idGarantia`)
) ENGINE=MyISAM AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `garantia`
--

INSERT INTO `garantia` (`idGarantia`, `tipo`, `monto`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'Dinero', 50, 'CI', '2026-10-22 00:08:54', '2026-10-22 00:08:54'),
(2, 'Objeto de Valor', 0, 'Celular', '2026-10-23 15:43:11', '2026-10-23 15:43:11'),
(3, 'Objeto de Valor', 0, '1 Celular', '2026-10-23 16:02:05', '2026-10-23 16:02:05'),
(4, 'Objeto de Valor', 0, 'Celular', '2026-10-23 16:46:55', '2026-10-23 16:46:55'),
(5, 'Objeto de Valor', 0, 'Celular', '2026-10-23 19:48:19', '2026-10-23 19:48:19'),
(6, 'Objeto de Valor', 0, 'Celular', '2026-10-23 19:48:19', '2026-10-23 19:48:19'),
(7, 'Dinero', 50, 'Carnet de identidad', '2026-10-23 22:26:44', '2026-10-23 22:26:44'),
(8, 'Objeto de Valor', 0, '1 Celular', '2026-10-23 23:56:33', '2026-10-23 23:56:33'),
(9, 'Objeto de Valor', 0, 'Celular', '2026-10-24 00:15:35', '2026-10-24 00:15:35'),
(10, 'Objeto de Valor', 0, '1 Celular', '2026-10-24 00:28:18', '2026-10-24 00:28:18'),
(11, 'Objeto de Valor', 0, '1 Celular', '2026-10-24 01:22:32', '2026-10-24 01:22:32'),
(12, 'Dinero', 50, 'Carnet de identidad', '2026-10-24 04:17:53', '2026-10-24 04:17:53'),
(13, 'Dinero', 50, 'CI', '2026-10-25 00:06:34', '2026-10-25 00:06:34'),
(14, 'Objeto de Valor', 0, 'Carnet de identidad', '2026-10-25 04:23:09', '2026-10-25 04:23:09'),
(15, 'Dinero', 50, 'CI', '2026-10-25 04:28:51', '2026-10-25 04:28:51'),
(16, 'Dinero', 50, 'CI', '2026-10-25 04:29:23', '2026-10-25 04:29:23'),
(17, 'Dinero', 50, 'CI', '2026-10-25 04:51:57', '2026-10-25 04:51:57'),
(18, 'Dinero', 50, 'Carnet de identidad', '2026-10-25 05:21:43', '2026-10-25 05:21:43'),
(19, 'Objeto de Valor', 0, 'Carnet de identidad', '2026-10-25 05:35:15', '2026-10-25 05:35:15'),
(20, 'Dinero', 70, 'Carnet de identidad', '2026-10-25 20:03:31', '2026-10-25 20:03:31'),
(21, 'Objeto de Valor', 0, 'Celular', '2026-10-25 21:44:13', '2026-10-25 21:44:13'),
(22, 'Dinero', 100, NULL, '2026-10-25 22:03:17', '2026-10-25 22:03:17'),
(23, 'Objeto de Valor', 0, 'Celular', '2026-10-25 22:05:12', '2026-10-25 22:05:12'),
(26, 'Seña / Anticipo', 200, 'Seña para reserva de trajes', '2026-10-26 02:34:03', '2026-10-26 02:34:03'),
(27, 'Seña / Anticipo', 200, 'Seña para reserva de trajes', '2026-10-26 02:38:21', '2026-10-26 02:38:21'),
(28, 'Seña / Anticipo', 2000, 'Seña para reserva de trajes', '2026-10-26 02:38:50', '2026-10-26 02:38:50'),
(29, 'Seña / Anticipo', 200, 'Seña para reserva de trajes', '2026-10-26 02:40:30', '2026-10-26 02:40:30'),
(30, 'Seña / Anticipo', 20, 'Seña para reserva de trajes', '2026-10-26 02:43:01', '2026-10-26 02:43:01'),
(31, 'Seña / Anticipo', 0, 'Celular', '2026-10-26 04:11:12', '2026-10-26 04:11:12'),
(32, 'Seña / Anticipo', 0, 'Celular', '2026-10-26 04:13:19', '2026-10-26 04:13:19'),
(33, 'Seña / Anticipo', 0, 'Celular', '2026-10-26 04:16:08', '2026-10-26 04:16:08'),
(34, 'Seña / Anticipo', 0, '1 Celular', '2026-10-26 04:16:50', '2026-10-26 04:16:50'),
(35, 'Seña / Anticipo', 0, '1 Celular', '2026-10-26 04:21:30', '2026-10-26 04:21:30'),
(36, 'Objeto de Valor', 0, '1 Celular', '2026-10-26 04:23:30', '2026-10-26 04:23:30'),
(37, 'Objeto de Valor', 0, '1 Celular', '2026-10-26 04:23:31', '2026-10-26 04:23:31'),
(38, 'Objeto de Valor', 0, 'Celular', '2026-10-26 04:32:35', '2026-10-26 04:32:35'),
(39, 'Objeto de Valor', 0, 'Celular', '2026-10-26 04:32:41', '2026-10-26 04:32:41'),
(40, 'Seña / Anticipo', 33, 'Seña para reserva de trajes', '2026-10-26 04:36:47', '2026-10-26 04:36:47'),
(41, 'Objeto de Valor', 0, 'Celular', '2026-10-26 04:37:59', '2026-10-26 04:37:59'),
(42, 'Seña / Anticipo', 33, 'Seña para reserva de trajes', '2026-10-26 04:39:04', '2026-10-26 04:39:04'),
(43, 'Seña / Anticipo', 100, 'Seña para reserva de trajes', '2026-10-26 04:50:57', '2026-10-26 04:50:57'),
(44, 'Objeto de Valor', 0, 'Celular', '2026-10-26 05:51:27', '2026-10-26 05:51:27'),
(45, 'Objeto de Valor', 0, 'Celular', '2026-10-26 05:54:52', '2026-10-26 05:54:52'),
(46, 'Objeto de Valor', 0, 'Celular', '2026-10-26 06:12:32', '2026-10-26 06:12:32'),
(47, 'Objeto de Valor', 0, 'Celular', '2026-10-26 06:17:20', '2026-10-26 06:17:20'),
(48, 'Seña / Anticipo', 100, 'Seña para reserva de trajes', '2026-10-26 16:46:42', '2026-10-26 16:46:42'),
(49, 'Seña / Anticipo', 100, 'Seña para reserva de trajes', '2026-10-26 16:49:44', '2026-10-26 16:49:44'),
(50, 'Seña / Anticipo', 100, 'Seña para reserva de trajes', '2026-10-26 17:05:23', '2026-10-26 17:05:23'),
(51, 'Seña / Anticipo', 100, 'Seña para reserva de trajes', '2026-10-26 17:07:36', '2026-10-26 17:07:36'),
(52, 'Objeto de Valor', 0, 'Celular', '2026-10-26 17:08:40', '2026-10-26 17:08:40'),
(53, 'Objeto de Valor', 0, 'Celular', '2026-10-26 17:09:30', '2026-10-26 17:09:30'),
(54, 'Objeto de Valor', 0, '1 Celular', '2026-10-26 17:23:58', '2026-10-26 17:23:58'),
(55, 'Objeto de Valor', 0, '1 Celular', '2026-10-26 17:24:25', '2026-10-26 17:24:25'),
(56, 'Seña / Anticipo', 10, 'Seña para reserva de trajes', '2026-10-26 17:26:53', '2026-10-26 17:26:53'),
(57, 'Seña / Anticipo', 100, 'Seña para reserva de trajes', '2026-10-26 17:33:55', '2026-10-26 17:33:55'),
(58, 'Seña / Anticipo', 120, 'Seña para reserva de trajes', '2026-10-26 17:41:33', '2026-10-26 17:41:33'),
(59, 'Objeto de Valor', 0, '1 Celular', '2026-10-26 17:43:21', '2026-10-26 17:43:21'),
(60, 'Seña / Anticipo', 120, 'Seña para reserva de trajes', '2026-10-26 17:49:46', '2026-10-26 17:49:46'),
(61, 'Objeto de Valor', 0, '1 Celular', '2026-10-26 17:54:01', '2026-10-26 17:54:01'),
(62, 'Seña / Anticipo', 120, 'Seña para reserva de trajes', '2026-10-26 18:02:36', '2026-10-26 18:02:36'),
(63, 'Seña / Anticipo', 50, 'Seña para reserva de trajes', '2026-10-26 18:56:24', '2026-10-26 18:56:24'),
(64, 'Objeto de Valor', 0, 'sedf', '2026-10-26 23:49:20', '2026-10-26 23:49:20'),
(65, 'Seña / Anticipo', 50, 'Seña para reserva de trajes', '2026-10-26 23:51:12', '2026-10-26 23:51:12'),
(66, 'Objeto de Valor', 0, 'Celular', '2026-10-27 03:29:31', '2026-10-27 03:29:31'),
(67, 'Seña / Anticipo', 50, 'Seña para reserva de trajes', '2026-10-27 03:34:19', '2026-10-27 03:34:19'),
(68, 'Objeto de Valor', 0, 'Celular', '2026-10-27 19:49:44', '2026-10-27 19:49:44'),
(69, 'Seña / Anticipo', 100, 'Seña para reserva de trajes', '2026-10-27 20:14:23', '2026-10-27 20:14:23'),
(70, 'Objeto de Valor', 0, 'Celular', '2026-10-27 20:37:45', '2026-10-27 20:37:45'),
(71, 'Seña / Anticipo', 100, 'Seña para reserva de trajes', '2026-10-27 20:40:16', '2026-10-27 20:40:16'),
(72, 'Objeto de Valor', 0, 'Celular', '2026-10-28 00:38:37', '2026-10-28 00:38:37'),
(73, 'Objeto de Valor', 0, '2 Celulares', '2026-10-28 00:49:27', '2026-10-28 00:49:27'),
(74, 'Objeto de Valor', 0, '2 Celulares', '2026-10-28 00:51:28', '2026-10-28 00:51:28'),
(75, 'Seña / Anticipo', 100, 'Seña para reserva de trajes', '2026-10-28 00:56:25', '2026-10-28 00:56:25'),
(76, 'Seña / Anticipo', 20, 'Seña para reserva de trajes', '2026-10-28 01:04:28', '2026-10-28 01:04:28'),
(77, 'Seña / Anticipo', 20, 'Seña para reserva de trajes', '2026-10-28 01:08:22', '2026-10-28 01:08:22'),
(78, 'Seña / Anticipo', 100, 'Seña para reserva de trajes', '2026-10-28 01:11:27', '2026-10-28 01:11:27'),
(79, 'Seña / Anticipo', 100, 'Seña para reserva de trajes', '2026-10-28 01:21:57', '2026-10-28 01:21:57'),
(80, 'Seña / Anticipo', 120, 'Seña para reserva de trajes', '2026-10-28 01:22:41', '2026-10-28 01:22:41'),
(81, 'Objeto de Valor', 0, '1 Celular', '2026-10-28 02:04:50', '2026-10-28 02:04:50'),
(82, 'Objeto de Valor', 0, 'Celular', '2026-10-28 02:06:19', '2026-10-28 02:06:19'),
(83, 'Seña / Anticipo', 30, 'Seña para reserva de trajes', '2026-10-28 02:10:57', '2026-10-28 02:10:57'),
(86, 'Seña / Anticipo', 100, 'Seña para reserva de trajes', '2026-10-28 02:53:32', '2026-10-28 02:53:32'),
(87, 'Dinero', 150, 'Carnet de identidad', '2026-10-28 16:09:57', '2026-10-28 16:09:57'),
(88, 'Objeto de Valor', 0, '2 Celulares', '2026-10-29 03:16:50', '2026-10-29 03:16:50'),
(89, 'Seña / Anticipo', 100, 'Seña para reserva de trajes', '2026-10-29 03:18:42', '2026-10-29 03:18:42'),
(90, 'Dinero', 200, '2 CI', '2026-10-29 03:27:31', '2026-10-29 03:27:31'),
(91, 'Objeto de Valor', 0, '2 Celulares', '2026-10-29 17:53:37', '2026-10-29 17:53:37'),
(92, 'Objeto de Valor', 0, '2 Celulares', '2026-10-30 18:10:23', '2026-10-30 18:10:23'),
(93, 'Dinero', 110, 'CI', '2026-10-30 18:11:06', '2026-10-30 18:11:06'),
(94, 'Objeto de Valor', 0, '2 Celulares', '2026-10-30 18:53:30', '2026-10-30 18:53:30'),
(95, 'Objeto de Valor', 0, '2 Celulares', '2026-10-30 19:00:05', '2026-10-30 19:00:05'),
(96, 'Dinero', 110, 'CI', '2026-10-30 19:03:04', '2026-10-30 19:03:04'),
(97, 'Dinero', 110, 'CI', '2026-10-30 19:04:54', '2026-10-30 19:04:54'),
(98, 'Dinero', 110, 'CI', '2026-10-30 19:14:10', '2026-10-30 19:14:10'),
(99, 'Seña / Anticipo', 100, 'Seña para reserva de trajes', '2026-10-30 19:47:20', '2026-10-30 19:47:20'),
(100, 'Dinero', 100, 'CI', '2026-10-30 19:51:24', '2026-10-30 19:51:24'),
(101, 'Seña / Anticipo', 50, 'Seña para reserva de trajes', '2026-10-31 16:58:25', '2026-10-31 16:58:25'),
(102, 'Seña / Anticipo', 20, 'Seña para reserva de trajes', '2026-10-31 17:41:35', '2026-10-31 17:41:35'),
(103, 'Seña / Anticipo', 20, 'Seña para reserva de trajes', '2026-10-31 17:46:28', '2026-10-31 17:46:28'),
(104, 'Dinero', 110, 'CI', '2026-10-31 17:48:24', '2026-10-31 17:48:24'),
(105, 'Dinero', 50, 'Carnet de identidad', '2026-10-31 23:14:43', '2026-10-31 23:14:43'),
(106, 'Dinero', 100, 'CI', '2026-10-31 23:23:07', '2026-10-31 23:23:07'),
(107, 'Dinero', 100, 'Carnet de identidad', '2026-10-31 23:24:53', '2026-10-31 23:24:53'),
(108, 'Seña / Anticipo', 70, 'Seña para reserva de trajes', '2026-10-31 23:38:32', '2026-10-31 23:38:32'),
(109, 'Dinero', 50, 'Carnet de identidad', '2026-11-01 00:48:03', '2026-11-01 00:48:03'),
(110, 'Dinero', 100, NULL, '2026-11-01 00:50:55', '2026-11-01 00:50:55'),
(111, 'Dinero', 100, 'Carnet de identidad', '2026-11-14 23:03:33', '2026-11-14 23:03:33'),
(112, 'Objeto de Valor', 0, 'Celular', '2026-11-19 20:43:54', '2026-11-19 20:43:54'),
(113, 'Objeto de Valor', 0, '1', '2026-11-20 22:51:37', '2026-11-20 22:51:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(4, '0001_01_01_000000_create_users_table', 1),
(5, '0001_01_01_000001_create_cache_table', 1),
(6, '0001_01_01_000002_create_jobs_table', 1),
(7, '2026_08_16_165720_create_clientes_table', 2),
(8, '2026_10_27_135006_add_fechas_devolucion_to_alquiler_table', 3),
(9, '2026_10_27_154425_add_reserva_fields_to_alquiler_table', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

DROP TABLE IF EXISTS `rol`;
CREATE TABLE IF NOT EXISTS `rol` (
  `idRol` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idRol`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`idRol`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'Acceso total a todo el sistema', '2026-10-21 05:16:24', '2026-10-21 05:16:24'),
(2, 'Empleado', 'Acceso restringido a operaciones de alquiler', '2026-10-21 05:16:24', '2026-10-21 05:16:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('kUyBxB6tmnOQKUnrtFHSNTZ8HNGAK55EJhV0Qxxq', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36 Edg/157.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTDE3NUU2b2dUYkFGdGdWVXdqVndLMG1pckhqS1VlVWF2ank3WmJZbiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1795132961);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tallas_traje`
--

DROP TABLE IF EXISTS `tallas_traje`;
CREATE TABLE IF NOT EXISTS `tallas_traje` (
  `idTalla` int NOT NULL AUTO_INCREMENT,
  `idTraje` int NOT NULL,
  `talla` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idTalla`),
  KEY `id_traje` (`idTraje`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tallas_traje`
--

INSERT INTO `tallas_traje` (`idTalla`, `idTraje`, `talla`, `stock`, `created_at`, `updated_at`) VALUES
(3, 18, 'M', 5, NULL, NULL),
(5, 20, 'P', 0, NULL, NULL),
(6, 21, 'M', 0, NULL, NULL),
(7, 21, 'S', 3, NULL, NULL),
(9, 22, 'M', 3, NULL, NULL),
(10, 19, 'M', 7, NULL, NULL),
(11, 23, 'M', 2, NULL, NULL),
(12, 23, 'S', 3, NULL, NULL),
(14, 24, 'M', 10, NULL, NULL),
(19, 27, 'M', 10, NULL, NULL),
(20, 27, 'S', 10, NULL, NULL),
(21, 28, 'P', 6, NULL, NULL),
(22, 29, 'P', 5, NULL, NULL),
(23, 30, 'P', 1, NULL, NULL),
(24, 30, 'S', 1, NULL, NULL),
(25, 31, 'M', 10, NULL, NULL),
(26, 32, 'M', 10, NULL, NULL),
(32, 35, 'M', 1, NULL, NULL),
(35, 17, 'M', 4, NULL, NULL),
(36, 17, 'P', 5, NULL, NULL),
(47, 26, 'M', 13, NULL, NULL),
(48, 26, 'S', 4, NULL, NULL),
(51, 25, 'M', 10, NULL, NULL),
(52, 25, 'P', 8, NULL, NULL),
(55, 33, 'M', 5, NULL, NULL),
(56, 33, 'S', 4, NULL, NULL),
(60, 36, 'M', 4, NULL, NULL),
(61, 36, 'P', 5, NULL, NULL),
(62, 37, 'M', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `traje`
--

DROP TABLE IF EXISTS `traje`;
CREATE TABLE IF NOT EXISTS `traje` (
  `idTraje` int NOT NULL AUTO_INCREMENT,
  `idCategoria` int NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pieza` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cant_piezas` int NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `estado` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`idTraje`),
  KEY `id_Categoria` (`idCategoria`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `traje`
--

INSERT INTO `traje` (`idTraje`, `idCategoria`, `nombre`, `pieza`, `cant_piezas`, `precio`, `estado`, `descripcion`, `created_at`, `updated_at`, `imagen`) VALUES
(17, 1, 'Cueca Tarijeña', 'Sombrero,camisa,pañoleta,chaleco,faja,pantalon,abarcas', 7, 70.00, 'Disponible', NULL, NULL, '2026-11-19 20:45:20', '1793288800_cuecatarij.jpeg'),
(18, 3, 'Pollera colorada', 'Sombrero,falfa,vestido,pañoleta', 4, 35.00, 'Disponible', NULL, NULL, NULL, '1793289226_POLLERA.jpg'),
(19, 2, 'Bruja', 'Sombrero,manta,vestido', 3, 40.00, 'Disponible', NULL, NULL, '2026-10-31 23:18:27', '1793289275_BRUJA.jpg'),
(20, 4, 'Vestido negro', 'Vestido', 1, 70.00, 'Disponible', NULL, NULL, NULL, '1793289393_vestidonegro.jfif'),
(21, 1, 'Tinku Verde Limon', 'Sombrero,Cabezal,Vestido,faja,', 4, 50.00, 'Disponible', NULL, NULL, NULL, '1793289524_tinkuverde.jpeg'),
(22, 2, 'Unicornio', NULL, 1, 60.00, 'Disponible', NULL, '2026-10-31 23:18:13', '2026-10-31 23:18:20', '1793474293_uniii.jpeg'),
(23, 4, 'Vestido rojo', 'Vestido,collar', 2, 65.00, 'Disponible', NULL, '2026-10-31 23:32:59', '2026-10-31 23:32:59', '1793475179_rojo.jpeg'),
(24, 4, 'Traje', 'Saco, pantalon,camisa,corbata', 4, 120.00, 'Disponible', NULL, '2026-10-31 23:34:22', '2026-10-31 23:34:39', '1793475262_traje.jpeg'),
(25, 1, 'Cueca cochabambina', 'Saco, pantalon,camisa,corbata,pluma', 5, 35.00, 'Disponible', NULL, '2026-10-31 23:54:40', '2026-11-20 01:54:06', '1793476480_cochala.jpeg'),
(26, 1, 'Cueca Chapaca', 'Sombrero, manta,tulmas,blusa,centro,pollera,pañuelo,abarca', 8, 35.00, 'Disponible', NULL, '2026-10-31 23:55:46', '2026-11-20 01:40:06', '1793476546_tarji.jpeg'),
(27, 1, 'Cueca Chapaca', 'Sombrero, manta,tulmas,blusa,centro,pollera,pañuelo,abarca', 8, 45.00, 'Disponible', NULL, '2026-10-31 23:56:55', '2026-10-31 23:56:55', '1793476615_tarijeña1.jpeg'),
(28, 3, 'Pirata', 'Sombrero, espada, botas,pantalon', 4, 50.00, 'Disponible', NULL, '2026-10-31 23:59:01', '2026-10-31 23:59:01', '1793476741_pngtree-pirate-costume-boy-isolated-on-transparent-background-png-image_16558439.png'),
(29, 2, 'Pirata', 'Sombrero, espada, botas,pantalon', 4, 50.00, 'Disponible', NULL, '2026-10-31 23:59:36', '2026-10-31 23:59:36', '1793476776_pngtree-pirate-costume-boy-isolated-on-transparent-background-png-image_16558439.png'),
(30, 2, 'Payaso', 'Sombrero,peluca,enterizo', 3, 45.00, 'Disponible', NULL, '2026-11-01 00:00:29', '2026-11-01 00:00:29', '1793476829_PAYASO.jpeg'),
(31, 2, 'Batman', 'Mascara,capa,mangalarga,pantalon', 4, 40.00, 'Disponible', NULL, '2026-11-01 00:02:08', '2026-11-01 00:02:08', '1793476928_disfraz-batman-armadura-infantil_6763_1.jpg'),
(32, 1, 'Rosita Pochi', 'Vestido,collar,collar', 3, 25.00, 'Disponible', NULL, '2026-11-01 00:07:14', '2026-11-01 00:07:14', '1793477234_jijiji.jpeg'),
(33, 1, 'Taquirari', 'Vestido,collar,collar', 3, 35.00, 'Disponible', NULL, '2026-11-01 00:08:09', '2026-11-20 01:56:12', '1793477289_j.jpeg'),
(35, 3, 'gtfh', 'Sombrero,sombrero,corset,centros,tulmas,polera,botas', 7, 77.00, 'Disponible', NULL, '2026-11-01 00:17:55', '2026-11-01 00:24:28', '1793478268_PAYASO.jpeg'),
(36, 3, 'muuu', 'kkk,jjj,jj,jj', 4, 34.00, 'Disponible', NULL, '2026-11-20 02:07:34', '2026-11-20 02:15:09', '1795140454_cochala.jpeg'),
(37, 3, 'muuu', 'nkk.,kk,.mmm,h', 4, 34.00, 'Disponible', NULL, '2026-11-20 02:15:50', '2026-11-20 02:15:50', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `idUsuario` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `idRol` int DEFAULT '2',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idUsuario`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idUsuario`, `nombre`, `apellido`, `telefono`, `email`, `password`, `idRol`, `created_at`, `updated_at`) VALUES
(1, 'Erlinda', 'Pedrazas', '67793706', 'erlinda@gmail.com', '$2y$12$zzA3aXRztvE7846qMrR9o.Fr1X25Bp/.VnzaMup9O3FPbimFnzh5S', 1, '2026-10-21 05:17:57', '2026-10-21 05:17:57'),
(2, 'Mishel', 'Pedrazas', '69225856', 'mishel@gmail.com', '$2y$12$/.otucGf1Ur3njuD/A2FCOjL4Xbx5EtYdWmliKAa3RpuIo3D.m0/2', 2, '2026-10-21 05:17:57', '2026-10-21 05:17:57'),
(4, 'Vicente', 'Serrudo Heredia', '78461849', 'vicente@gmail.com', '$2y$12$Lt3ousALhSQVefjVV6s.sea95eB0wh9OkqA4LEMkzHkW2wwibKLhK', 2, '2026-11-14 19:38:45', '2026-11-14 19:38:45'),
(6, 'Emanuel', 'Serrudo Heredia', '70786687', 'emanuel@gmail.com', '$2y$12$fmxnHQg8dWVagBnqIyQNKeSBXYrivdTRSlhhb.XW1P1g1KRH1.mXy', 2, '2026-11-15 14:44:29', '2026-11-15 14:44:29');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tallas_traje`
--
ALTER TABLE `tallas_traje`
  ADD CONSTRAINT `fk_traje_tallas` FOREIGN KEY (`idTraje`) REFERENCES `traje` (`idTraje`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
