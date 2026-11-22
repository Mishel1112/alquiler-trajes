-- Creaciones y Fantasías Erlinda - Base de Datos Actualizada
-- Generado para presentación profesional del sistema
-- Servidor: 127.0.0.1 (MySQL 8.4)
-- Base de datos: bd_alquiler_trajes

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "-04:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bd_alquiler_trajes`
--
CREATE DATABASE IF NOT EXISTS `bd_alquiler_trajes` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `bd_alquiler_trajes`;

-- --------------------------------------------------------
-- TABLA: rol
-- --------------------------------------------------------
DROP TABLE IF EXISTS `rol`;
CREATE TABLE IF NOT EXISTS `rol` (
  `idRol` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idRol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `rol` (`idRol`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'Acceso total a todo el sistema, inventario, usuarios y reportes', '2026-10-01 08:00:00', '2026-10-01 08:00:00'),
(2, 'Empleado', 'Acceso operativo para registro de alquileres, devoluciones y stock', '2026-10-01 08:00:00', '2026-10-01 08:00:00');

-- --------------------------------------------------------
-- TABLA: usuario
-- --------------------------------------------------------
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `usuario` (`idUsuario`, `nombre`, `apellido`, `telefono`, `email`, `password`, `idRol`, `created_at`, `updated_at`) VALUES
(1, 'Erlinda', 'Pedrazas', '67793706', 'erlinda@gmail.com', '$2y$12$zzA3aXRztvE7846qMrR9o.Fr1X25Bp/.VnzaMup9O3FPbimFnzh5S', 1, '2026-10-01 08:00:00', '2026-10-01 08:00:00'),
(2, 'Mishel', 'Pedrazas', '69225856', 'mishel@gmail.com', '$2y$12$/.otucGf1Ur3njuD/A2FCOjL4Xbx5EtYdWmliKAa3RpuIo3D.m0/2', 2, '2026-10-01 08:00:00', '2026-10-01 08:00:00'),
(3, 'Vicente', 'Serrudo Heredia', '78461849', 'vicente@gmail.com', '$2y$12$Lt3ousALhSQVefjVV6s.sea95eB0wh9OkqA4LEMkzHkW2wwibKLhK', 2, '2026-10-15 09:30:00', '2026-10-15 09:30:00'),
(4, 'Emanuel', 'Serrudo Heredia', '70786687', 'emanuel@gmail.com', '$2y$12$fmxnHQg8dWVagBnqIyQNKeSBXYrivdTRSlhhb.XW1P1g1KRH1.mXy', 2, '2026-10-20 14:15:00', '2026-10-20 14:15:00');

-- --------------------------------------------------------
-- TABLA: categoria
-- --------------------------------------------------------
DROP TABLE IF EXISTS `categoria`;
CREATE TABLE IF NOT EXISTS `categoria` (
  `idCategoria` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idCategoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categoria` (`idCategoria`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'Trajes típicos', 'Trajes tradicionales y folclóricos de Bolivia.', '2026-10-01 08:00:00', '2026-10-01 08:00:00'),
(2, 'Disfraces', 'Trajes temáticos de fantasía, superhéroes y personajes.', '2026-10-01 08:00:00', '2026-10-01 08:00:00'),
(3, 'Trajes internacionales', 'Vestimenta típica y representativa de otros países.', '2026-10-01 08:00:00', '2026-10-01 08:00:00'),
(4, 'Trajes de gala', 'Ropa formal, vestidos de noche y trajes para ocasiones de gala.', '2026-10-01 08:00:00', '2026-10-01 08:00:00');

-- --------------------------------------------------------
-- TABLA: cliente
-- --------------------------------------------------------
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `cliente` (`idCliente`, `nombre`, `telefono`, `tipo_cliente`, `fraternidad`, `colegio`, `curso`, `lugar_baile`, `created_at`, `updated_at`) VALUES
(1, 'Franco López', '78764576', 'estudiante', NULL, 'Colegio José Mariano Serrano', '1° Secundaria B', 'Teatro Municipal', '2026-10-15 10:00:00', '2026-10-15 10:00:00'),
(2, 'Camila Rodríguez Morales', '72034912', 'estudiante', NULL, 'Colegio San Calixto', '5° Secundaria A', 'Coliseo San Calixto', '2026-10-20 11:30:00', '2026-10-20 11:30:00'),
(3, 'Alejandro Mendoza Flores', '71589301', 'fraternidad', 'Fraternidad Morenada Central Oruro', NULL, NULL, 'Entrada Folclórica 16 de Julio', '2026-10-25 14:00:00', '2026-10-25 14:00:00'),
(4, 'Valeria Quiroga Paz', '68124578', 'publico general', NULL, NULL, NULL, 'Boda Salón Real Miraflores', '2026-10-28 16:20:00', '2026-10-28 16:20:00'),
(5, 'Prof. Rodrigo Arce Gutiérrez', '76543210', 'coreografo', NULL, 'Colegio Don Bosco', NULL, 'Festival Departamental de Danzas', '2026-11-01 09:00:00', '2026-11-01 09:00:00'),
(6, 'Natalia Siles Choque', '73214569', 'estudiante', NULL, 'Colegio Alemán', '6° Primaria B', 'Feria de Culturas', '2026-11-05 10:15:00', '2026-11-05 10:15:00'),
(7, 'Mateo Fernández Rojas', '70654321', 'publico general', NULL, NULL, NULL, 'Graduación Hotel Radisson', '2026-11-08 15:40:00', '2026-11-08 15:40:00'),
(8, 'Luciana Torrico Salinas', '77894512', 'fraternidad', 'Caporales San Simón Sucre', NULL, NULL, 'Entrada Folclórica Universitaria', '2026-11-10 17:10:00', '2026-11-10 17:10:00'),
(9, 'Diego Vargas Claros', '69874123', 'publico general', NULL, NULL, NULL, 'Fiesta de Disfraces Eventos Moon', '2026-11-12 11:00:00', '2026-11-12 11:00:00'),
(10, 'Sofía Benítez Mercado', '71239845', 'estudiante', NULL, 'Colegio San Ignacio', '4° Secundaria', 'Velada Folclórica Anual', '2026-11-14 12:25:00', '2026-11-14 12:25:00'),
(11, 'Javier Mamani Quispe', '74125896', 'fraternidad', 'Tinkus Tolkas La Paz', NULL, NULL, 'Festividad del Gran Poder', '2026-11-16 16:50:00', '2026-11-16 16:50:00'),
(12, 'Gabriela Ortiz Suárez', '78965412', 'publico general', NULL, NULL, NULL, 'Cena de Gala Aniversario Club La Paz', '2026-11-18 14:10:00', '2026-11-18 14:10:00'),
(13, 'Sebastián Paredes Luna', '70124589', 'estudiante', NULL, 'Colegio Sagrados Corazones', '3° Secundaria', 'Festival Primavera y Tradición', '2026-11-19 09:45:00', '2026-11-19 09:45:00'),
(14, 'Daniela Guzmán Ríos', '67451239', 'publico general', NULL, NULL, NULL, 'Sesión Fotográfica Quince Años', '2026-11-20 15:30:00', '2026-11-20 15:30:00'),
(15, 'Carlos Enrique Montaño', '73098124', 'publico general', NULL, NULL, NULL, 'Presentación Teatral Municipal', '2026-11-21 10:00:00', '2026-11-21 10:00:00');

-- --------------------------------------------------------
-- TABLA: traje
-- --------------------------------------------------------
DROP TABLE IF EXISTS `traje`;
CREATE TABLE IF NOT EXISTS `traje` (
  `idTraje` int NOT NULL AUTO_INCREMENT,
  `idCategoria` int NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pieza` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cant_piezas` int NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `estado` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`idTraje`),
  KEY `id_Categoria` (`idCategoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `traje` (`idTraje`, `idCategoria`, `nombre`, `pieza`, `cant_piezas`, `precio`, `estado`, `descripcion`, `created_at`, `updated_at`, `imagen`) VALUES
-- 1. Trajes Típicos (idCategoria = 1)
(17, 1, 'Cueca Tarijeña Tradicional', 'Sombrero, camisa, pañoleta, chaleco, faja, pantalón, abarcas', 7, 70.00, 'Disponible', 'Traje folclórico tradicional chapaco con detalles bordados a mano.', '2026-10-01 08:00:00', '2026-11-20 10:00:00', '1793288800_cuecatarij.jpeg'),
(21, 1, 'Tinku Verde Limón', 'Montera de cuero, chaquetilla bordada, pantalón, faja tejida, chuspa', 5, 50.00, 'Disponible', 'Atuendo festivo norte potosino con bordados vivos y aplicaciones en chaquira.', '2026-10-01 08:00:00', '2026-11-20 10:00:00', '1793289524_tinkuverde.jpeg'),
(25, 1, 'Cueca Cochabambina', 'Sombrero blanco de copa alta, blusa bordada, pollera plisada, enagua, faja', 5, 45.00, 'Disponible', 'Vestimenta tradicional cochabambina con pollera amplia y finos encajes.', '2026-10-05 08:00:00', '2026-11-20 10:00:00', '1793476480_cochala.jpeg'),
(26, 1, 'Cueca Chapaca Femenina', 'Sombrero, manta bordada, tulmas, blusa, centro, pollera, abarcas', 7, 40.00, 'Disponible', 'Traje típico tarijeño femenino con flores bordadas y pañuelo tradicional.', '2026-10-05 08:00:00', '2026-11-20 10:00:00', '1793476546_tarji.jpeg'),
(27, 1, 'Cueca Chapaca de Gala', 'Sombrero fino, manta de seda, tulmas, blusa con encaje, centro, pollera plisada, pañuelo', 7, 50.00, 'Disponible', 'Vestido de gala tarijeño especial para festivales y concursos folclóricos.', '2026-10-05 08:00:00', '2026-11-20 10:00:00', '1793476615_tarijeña1.jpeg'),
(32, 1, 'Rosita Pochi Oriental', 'Tipoy tradicional cruceño, flor para el cabello, collar artesanal', 3, 30.00, 'Disponible', 'Atuendo tradicional de la región oriental en tela fresca y colores vibrantes.', '2026-10-10 08:00:00', '2026-11-20 10:00:00', '1793477234_jijiji.jpeg'),
(33, 1, 'Taquirari Beniano Varón', 'Camisa blanca bordada, pantalón blanco, pañoleta roja, sombrero de saó', 4, 35.00, 'Disponible', 'Traje típico de baile de taquirari para varón confeccionado en lino blanco.', '2026-10-10 08:00:00', '2026-11-20 10:00:00', '1793477289_j.jpeg'),
(36, 1, 'Morenada Central Gran Poder', 'Casco con plumas, máscara de moreno, pollerín bordado, pechera metálica, matracón', 5, 90.00, 'Disponible', 'Traje de moreno mayor con bordados pesados en hilos dorados y perlas.', '2026-10-15 08:00:00', '2026-11-20 10:00:00', 'bolivia.jpeg'),
(37, 1, 'Caporal Macho Bordado', 'Sombrero, chaquetilla con dragón bordado, faja de monedas, pantalón abombado, botas con cascabeles', 5, 80.00, 'Disponible', 'Traje completo de caporal con mangas anchas y cascabeles metálicos sonoros.', '2026-10-15 08:00:00', '2026-11-20 10:00:00', '1789091262_cuecatarij.jpeg'),

-- 2. Disfraces (idCategoria = 2)
(18, 2, 'Disfraz de Bruja Mística', 'Sombrero puntiagudo, vestido terciopelo, capa, varita mágica', 4, 40.00, 'Disponible', 'Vestido de fantasía con acabados en tul negro y capa satinada.', '2026-10-01 08:00:00', '2026-11-20 10:00:00', '1793289275_BRUJA.jpg'),
(19, 2, 'Disfraz de Unicornio Fantasía', 'Enterizo afelpado con capucha, cuerno brillante, alas desmontables', 3, 45.00, 'Disponible', 'Disfraz enterizo suave y abrigado con detalles tornasolados.', '2026-10-01 08:00:00', '2026-11-20 10:00:00', '1793474293_uniii.jpeg'),
(28, 2, 'Disfraz de Pirata Infantil', 'Sombrero pirata, camisa con chaleco, cinturón, pantalón a rayas, parche', 5, 45.00, 'Disponible', 'Disfraz infantil de corsario para eventos escolares y fiestas de disfraces.', '2026-10-05 08:00:00', '2026-11-20 10:00:00', '1793476741_pngtree-pirate-costume-boy-isolated-on-transparent-background-png-image_16558439.png'),
(29, 2, 'Disfraz de Pirata Juvenil', 'Sombrero con calavera, casaca pirata, camisa encaje, cinturón doble, cubrebotas', 5, 50.00, 'Disponible', 'Disfraz completo juvenil con casaca elegante y espada de utilería.', '2026-10-05 08:00:00', '2026-11-20 10:00:00', '1793476776_pngtree-pirate-costume-boy-isolated-on-transparent-background-png-image_16558439.png'),
(30, 2, 'Disfraz de Payaso Multicolor', 'Peluca rizada, enterizo a cuadros, cuello volante, nariz roja', 4, 40.00, 'Disponible', 'Disfraz alegre y colorido ideal para shows, teatro y animaciones infantiles.', '2026-10-10 08:00:00', '2026-11-20 10:00:00', '1793476829_PAYASO.jpeg'),
(31, 2, 'Disfraz de Batman Armadura', 'Máscara con capucha, pechera termoformada, capa negra, cinturón utilitario, pantalón', 5, 50.00, 'Disponible', 'Traje de superhéroe con relieve muscular termoformado y capa amplia.', '2026-10-10 08:00:00', '2026-11-20 10:00:00', '1793476928_disfraz-batman-armadura-infantil_6763_1.jpg'),
(35, 2, 'Disfraz de Arlequín Elegante', 'Gorro veneciano, antifaz, casaca a rombos, pantalón bombacho, cascabeles', 5, 55.00, 'Disponible', 'Atuendo veneciano clásico en blanco y negro con campanas y antifaz.', '2026-10-15 08:00:00', '2026-11-20 10:00:00', 'disfraces.jpg'),

-- 3. Trajes Internacionales (idCategoria = 3)
(22, 3, 'Traje Mariachi Charro Jalisco', 'Sombrero charro bordado, chaqueta con botonadura, corbatón de seda, chaleco, pantalón', 5, 80.00, 'Disponible', 'Traje charro mexicano de alta gala con botonadura plateada y bordado en hilo blanco.', '2026-10-01 08:00:00', '2026-11-20 10:00:00', 'internacionales.jpg'),
(38, 3, 'Traje Flamenco Español Sevillana', 'Vestido con volantes multicapa, mantón de manila bordado, flor para el cabello, peineta', 4, 75.00, 'Disponible', 'Vestido flamenco tradicional con volantes en falda y mangas, mantón y castañuelas.', '2026-10-05 08:00:00', '2026-11-20 10:00:00', '1789091338_tarijeña2.jpeg'),
(39, 3, 'Kimono Tradicional Japonés', 'Kimono de satén estampado, faja Obi ancha con lazo, abanico de bambú, sandalias geta', 4, 70.00, 'Disponible', 'Vestimenta oriental clásica con motivos florales de cerezo y cinto Obi tradicional.', '2026-10-10 08:00:00', '2026-11-20 10:00:00', '1793289226_POLLERA.jpg'),

-- 4. Trajes de Gala (idCategoria = 4)
(20, 4, 'Vestido de Gala Corte Sirena Negro', 'Vestido largo con escote corazón y pedrería fina', 1, 85.00, 'Disponible', 'Vestido de noche elegante para recepciones de graduación y galas de honor.', '2026-10-01 08:00:00', '2026-11-20 10:00:00', '1793289393_vestidonegro.jfif'),
(23, 4, 'Vestido de Gala Rojo Pasión', 'Vestido largo satinado con abertura lateral, fajín drapeado', 2, 80.00, 'Disponible', 'Diseño de alta costura en satén rojo rubí ideal para desfiles y ceremonias.', '2026-10-05 08:00:00', '2026-11-20 10:00:00', '1793475179_rojo.jpeg'),
(24, 4, 'Traje Formal Ejecutivo Azul Marino', 'Saco corte slim fit, pantalón formal, camisa blanca de vestir, corbata de seda', 4, 120.00, 'Disponible', 'Terno masculino de corte moderno confeccionado en paño italiano de primera.', '2026-10-05 08:00:00', '2026-11-20 10:00:00', '1793475262_traje.jpeg'),
(40, 4, 'Smoking Etiqueta Negra Ceremonial', 'Saco solapa satín brillo, pantalón formal con franja, corbata humita, fajín negro, camisa piqué', 5, 130.00, 'Disponible', 'Smoking formal para padrinos, novios y eventos de estricta etiqueta ceremonial.', '2026-10-15 08:00:00', '2026-11-20 10:00:00', 'gala.jpg');

-- --------------------------------------------------------
-- TABLA: tallas_traje
-- --------------------------------------------------------
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tallas_traje` (`idTalla`, `idTraje`, `talla`, `stock`, `created_at`, `updated_at`) VALUES
-- 17 Cueca Tarijeña
(1, 17, 'S', 5, NOW(), NOW()),
(2, 17, 'M', 8, NOW(), NOW()),
(3, 17, 'L', 4, NOW(), NOW()),
-- 21 Tinku Verde
(4, 21, 'S', 6, NOW(), NOW()),
(5, 21, 'M', 8, NOW(), NOW()),
(6, 21, 'L', 4, NOW(), NOW()),
-- 25 Cueca Cochabambina
(7, 25, 'S', 6, NOW(), NOW()),
(8, 25, 'M', 10, NOW(), NOW()),
(9, 25, 'L', 5, NOW(), NOW()),
-- 26 Cueca Chapaca
(10, 26, 'S', 8, NOW(), NOW()),
(11, 26, 'M', 12, NOW(), NOW()),
(12, 26, 'L', 6, NOW(), NOW()),
-- 27 Cueca Chapaca de Gala
(13, 27, 'S', 8, NOW(), NOW()),
(14, 27, 'M', 10, NOW(), NOW()),
(15, 27, 'L', 5, NOW(), NOW()),
-- 32 Rosita Pochi
(16, 32, 'S', 6, NOW(), NOW()),
(17, 32, 'M', 8, NOW(), NOW()),
-- 33 Taquirari Beniano
(18, 33, 'S', 5, NOW(), NOW()),
(19, 33, 'M', 8, NOW(), NOW()),
(20, 33, 'L', 4, NOW(), NOW()),
-- 36 Morenada Central
(21, 36, 'M', 6, NOW(), NOW()),
(22, 36, 'L', 8, NOW(), NOW()),
-- 37 Caporal Macho
(23, 37, 'S', 6, NOW(), NOW()),
(24, 37, 'M', 10, NOW(), NOW()),
(25, 37, 'L', 6, NOW(), NOW()),
-- 18 Bruja Mística
(26, 18, 'S', 5, NOW(), NOW()),
(27, 18, 'M', 7, NOW(), NOW()),
-- 19 Unicornio Fantasía
(28, 19, 'S', 6, NOW(), NOW()),
(29, 19, 'M', 8, NOW(), NOW()),
-- 28 Pirata Infantil
(30, 28, 'P', 6, NOW(), NOW()),
(31, 28, 'S', 8, NOW(), NOW()),
(32, 28, 'M', 5, NOW(), NOW()),
-- 29 Pirata Juvenil
(33, 29, 'M', 8, NOW(), NOW()),
(34, 29, 'L', 6, NOW(), NOW()),
-- 30 Payaso Multicolor
(35, 30, 'P', 5, NOW(), NOW()),
(36, 30, 'S', 6, NOW(), NOW()),
(37, 30, 'M', 7, NOW(), NOW()),
-- 31 Batman Armadura
(38, 31, 'S', 6, NOW(), NOW()),
(39, 31, 'M', 9, NOW(), NOW()),
(40, 31, 'L', 5, NOW(), NOW()),
-- 35 Arlequín Elegante
(41, 35, 'S', 5, NOW(), NOW()),
(42, 35, 'M', 7, NOW(), NOW()),
-- 22 Mariachi Charro
(43, 22, 'M', 7, NOW(), NOW()),
(44, 22, 'L', 6, NOW(), NOW()),
-- 38 Flamenco Sevillana
(45, 38, 'S', 5, NOW(), NOW()),
(46, 38, 'M', 8, NOW(), NOW()),
-- 39 Kimono Japonés
(47, 39, 'M', 6, NOW(), NOW()),
(48, 39, 'Única', 5, NOW(), NOW()),
-- 20 Vestido Negro Sirena
(49, 20, 'S', 4, NOW(), NOW()),
(50, 20, 'M', 6, NOW(), NOW()),
-- 23 Vestido Rojo Pasión
(51, 23, 'S', 5, NOW(), NOW()),
(52, 23, 'M', 7, NOW(), NOW()),
-- 24 Traje Formal Azul Marino
(53, 24, 'S', 5, NOW(), NOW()),
(54, 24, 'M', 9, NOW(), NOW()),
(55, 24, 'L', 6, NOW(), NOW()),
-- 40 Smoking Etiqueta Negra
(56, 40, 'M', 6, NOW(), NOW()),
(57, 40, 'L', 5, NOW(), NOW());

-- --------------------------------------------------------
-- TABLA: garantia
-- --------------------------------------------------------
DROP TABLE IF EXISTS `garantia`;
CREATE TABLE IF NOT EXISTS `garantia` (
  `idGarantia` int NOT NULL AUTO_INCREMENT,
  `tipo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `monto` decimal(10,2) NOT NULL DEFAULT '0.00',
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`idGarantia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- TABLA: alquiler
-- --------------------------------------------------------
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
  PRIMARY KEY (`idAlquiler`),
  KEY `idCliente` (`idCliente`),
  KEY `idGarantia` (`idGarantia`),
  KEY `idUsuario` (`idUsuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- TABLA: detalle_alquiler
-- --------------------------------------------------------
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- TABLA: devolucion
-- --------------------------------------------------------
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcado de datos para la tabla `garantia`
INSERT INTO `garantia` (`idGarantia`, `tipo`, `monto`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'Dinero', 50.00, 'Carnet de Identidad', '2026-11-21 09:30:00', '2026-11-21 09:30:00'),
(2, 'Dinero', 100.00, 'Carnet de Identidad y credencial', '2026-11-21 10:45:00', '2026-11-21 10:45:00'),
(3, 'Objeto de Valor', 0.00, 'Celular Samsung Galaxy A54', '2026-11-20 15:30:00', '2026-11-20 15:30:00'),
(4, 'Dinero', 100.00, 'Licencia de Conducir', '2026-11-20 17:00:00', '2026-11-20 17:00:00'),
(5, 'Objeto de Valor', 0.00, 'Celular Xiaomi Redmi Note 12', '2026-11-18 10:00:00', '2026-11-18 10:00:00'),
(6, 'Dinero', 50.00, 'Carnet de Identidad', '2026-11-18 11:30:00', '2026-11-18 11:30:00'),
(7, 'Dinero', 60.00, 'Carnet de Identidad', '2026-11-18 14:00:00', '2026-11-18 14:00:00'),
(8, 'Dinero', 70.00, 'Carnet de Identidad', '2026-11-14 10:00:00', '2026-11-14 10:00:00'),
(9, 'Dinero', 100.00, 'Carnet de Identidad', '2026-11-12 15:00:00', '2026-11-12 15:00:00'),
(10, 'Seña / Anticipo', 150.00, 'Anticipo para apartar trajes de Caporal', '2026-11-20 16:00:00', '2026-11-20 16:00:00'),
(11, 'Seña / Anticipo', 200.00, 'Anticipo elenco de danzas', '2026-11-21 08:45:00', '2026-11-21 08:45:00'),
(12, 'Seña / Anticipo', 80.00, 'Seña reserva festividad', '2026-11-21 11:15:00', '2026-11-21 11:15:00'),
(13, 'Seña / Anticipo', 50.00, 'Seña apartado smoking', '2026-11-19 14:00:00', '2026-11-19 14:00:00'),
(14, 'Dinero', 30.00, 'Carnet de Identidad', '2026-11-21 08:30:00', '2026-11-21 08:30:00'),
(15, 'Dinero', 50.00, 'Carnet de Identidad', '2026-11-21 12:00:00', '2026-11-21 12:00:00'),
(16, 'Dinero', 50.00, 'CI', '2026-11-02 10:00:00', '2026-11-02 10:00:00'),
(17, 'Dinero', 50.00, 'CI', '2026-11-04 11:30:00', '2026-11-04 11:30:00'),
(18, 'Dinero', 100.00, 'CI', '2026-11-06 14:00:00', '2026-11-06 14:00:00'),
(19, 'Dinero', 70.00, 'CI', '2026-11-08 16:00:00', '2026-11-08 16:00:00'),
(20, 'Dinero', 150.00, 'CI', '2026-11-10 09:30:00', '2026-11-10 09:30:00'),
(21, 'Dinero', 50.00, 'CI', '2026-11-12 11:00:00', '2026-11-12 11:00:00'),
(22, 'Dinero', 100.00, 'CI', '2026-11-15 15:30:00', '2026-11-15 15:30:00'),
(23, 'Dinero', 100.00, 'CI', '2026-11-16 10:00:00', '2026-11-16 10:00:00'),
(24, 'Dinero', 80.00, 'CI', '2026-10-31 16:00:00', '2026-10-31 16:00:00'),
(25, 'Dinero', 60.00, 'CI', '2026-10-30 14:00:00', '2026-10-30 14:00:00'),
(26, 'Dinero', 150.00, 'CI', '2026-10-24 09:00:00', '2026-10-24 09:00:00'),
(27, 'Dinero', 100.00, 'CI', '2026-10-20 15:00:00', '2026-10-20 15:00:00'),
(28, 'Dinero', 60.00, 'CI', '2026-10-15 11:30:00', '2026-10-15 11:30:00'),
(29, 'Dinero', 200.00, 'CI y Garantía', '2026-08-05 08:30:00', '2026-08-05 08:30:00'),
(30, 'Dinero', 150.00, 'CI', '2026-09-20 10:00:00', '2026-09-20 10:00:00'),
(31, 'Dinero', 200.00, 'CI', '2025-08-05 10:00:00', '2025-08-05 10:00:00'),
(32, 'Dinero', 300.00, 'CI', '2025-10-25 09:00:00', '2025-10-25 09:00:00'),
(33, 'Dinero', 250.00, 'CI', '2025-11-15 15:00:00', '2025-11-15 15:00:00'),
(34, 'Dinero', 200.00, 'CI', '2024-08-05 10:00:00', '2024-08-05 10:00:00'),
(35, 'Dinero', 250.00, 'CI', '2024-11-10 14:00:00', '2024-11-10 14:00:00');

-- Volcado de datos para la tabla `alquiler`
INSERT INTO `alquiler` (`idAlquiler`, `idCliente`, `idGarantia`, `idUsuario`, `f_recogida`, `f_devolucion`, `estado`, `tipo_operacion`, `created_at`) VALUES
(1, 2, 1, 1, '2026-11-21 09:30:00', '2026-11-23 18:00:00', 'Alquilado', 'Alquiler', '2026-11-21 09:30:00'),
(2, 3, 2, 2, '2026-11-21 10:45:00', '2026-11-23 18:00:00', 'Alquilado', 'Alquiler', '2026-11-21 10:45:00'),
(3, 4, 3, 1, '2026-11-20 15:30:00', '2026-11-22 18:00:00', 'Alquilado', 'Alquiler', '2026-11-20 15:30:00'),
(4, 7, 4, 3, '2026-11-20 17:00:00', '2026-11-22 18:00:00', 'Alquilado', 'Alquiler', '2026-11-20 17:00:00'),
(5, 9, 5, 2, '2026-11-18 10:00:00', '2026-11-19 18:00:00', 'Alquilado', 'Alquiler', '2026-11-18 10:00:00'),
(6, 6, 6, 1, '2026-11-18 11:30:00', '2026-11-20 18:00:00', 'Finalizado', 'Alquiler', '2026-11-18 11:30:00'),
(7, 13, 7, 2, '2026-11-18 14:00:00', '2026-11-20 19:00:00', 'Finalizado', 'Alquiler', '2026-11-18 14:00:00'),
(8, 1, 8, 1, '2026-11-14 10:00:00', '2026-11-16 18:00:00', 'Finalizado', 'Alquiler', '2026-11-14 10:00:00'),
(9, 15, 9, 2, '2026-11-12 15:00:00', '2026-11-14 18:00:00', 'Finalizado', 'Alquiler', '2026-11-12 15:00:00'),
(10, 8, 10, 1, '2026-11-20 16:00:00', '2026-11-24 18:00:00', 'Reservado', 'Reserva', '2026-11-20 16:00:00'),
(11, 5, 11, 2, '2026-11-21 08:45:00', '2026-11-27 18:00:00', 'Reservado', 'Reserva', '2026-11-21 08:45:00'),
(12, 11, 12, 1, '2026-11-21 11:15:00', '2026-11-29 18:00:00', 'Reservado', 'Reserva', '2026-11-21 11:15:00'),
(13, 12, 13, 3, '2026-11-19 14:00:00', '2026-11-28 18:00:00', 'Reservado', 'Reserva', '2026-11-19 14:00:00'),
(14, 10, 14, 1, '2026-11-21 08:30:00', '2026-11-23 18:00:00', 'Alquilado', 'Alquiler', '2026-11-21 08:30:00'),
(15, 14, 15, 2, '2026-11-21 12:00:00', '2026-11-23 18:00:00', 'Alquilado', 'Alquiler', '2026-11-21 12:00:00'),
(16, 1, 16, 1, '2026-11-02 10:00:00', '2026-11-04 18:00:00', 'Finalizado', 'Alquiler', '2026-11-02 10:00:00'),
(17, 2, 17, 2, '2026-11-04 11:30:00', '2026-11-06 18:00:00', 'Finalizado', 'Alquiler', '2026-11-04 11:30:00'),
(18, 3, 18, 1, '2026-11-06 14:00:00', '2026-11-08 18:00:00', 'Finalizado', 'Alquiler', '2026-11-06 14:00:00'),
(19, 4, 19, 3, '2026-11-08 16:00:00', '2026-11-10 18:00:00', 'Finalizado', 'Alquiler', '2026-11-08 16:00:00'),
(20, 5, 20, 1, '2026-11-10 09:30:00', '2026-11-12 18:00:00', 'Finalizado', 'Alquiler', '2026-11-10 09:30:00'),
(21, 6, 21, 2, '2026-11-12 11:00:00', '2026-11-14 18:00:00', 'Finalizado', 'Alquiler', '2026-11-12 11:00:00'),
(22, 7, 22, 1, '2026-11-15 15:30:00', '2026-11-17 18:00:00', 'Finalizado', 'Alquiler', '2026-11-15 15:30:00'),
(23, 8, 23, 3, '2026-11-16 10:00:00', '2026-11-18 18:00:00', 'Finalizado', 'Alquiler', '2026-11-16 10:00:00'),
(24, 9, 24, 1, '2026-10-31 16:00:00', '2026-11-02 18:00:00', 'Finalizado', 'Alquiler', '2026-10-31 16:00:00'),
(25, 10, 25, 2, '2026-10-30 14:00:00', '2026-11-01 18:00:00', 'Finalizado', 'Alquiler', '2026-10-30 14:00:00'),
(26, 11, 26, 1, '2026-10-24 09:00:00', '2026-10-26 18:00:00', 'Finalizado', 'Alquiler', '2026-10-24 09:00:00'),
(27, 12, 27, 2, '2026-10-20 15:00:00', '2026-10-22 18:00:00', 'Finalizado', 'Alquiler', '2026-10-20 15:00:00'),
(28, 13, 28, 1, '2026-10-15 11:30:00', '2026-10-17 18:00:00', 'Finalizado', 'Alquiler', '2026-10-15 11:30:00'),
(29, 14, 29, 1, '2026-08-05 08:30:00', '2026-08-08 18:00:00', 'Finalizado', 'Alquiler', '2026-08-05 08:30:00'),
(30, 15, 30, 2, '2026-09-20 10:00:00', '2026-09-22 18:00:00', 'Finalizado', 'Alquiler', '2026-09-20 10:00:00'),
(31, 1, 31, 1, '2025-08-05 10:00:00', '2025-08-08 18:00:00', 'Finalizado', 'Alquiler', '2025-08-05 10:00:00'),
(32, 3, 32, 1, '2025-10-25 09:00:00', '2025-10-28 18:00:00', 'Finalizado', 'Alquiler', '2025-10-25 09:00:00'),
(33, 4, 33, 2, '2025-11-15 15:00:00', '2025-11-18 18:00:00', 'Finalizado', 'Alquiler', '2025-11-15 15:00:00'),
(34, 2, 34, 1, '2024-08-05 10:00:00', '2024-08-08 18:00:00', 'Finalizado', 'Alquiler', '2024-08-05 10:00:00'),
(35, 5, 35, 1, '2024-11-10 14:00:00', '2024-11-13 18:00:00', 'Finalizado', 'Alquiler', '2024-11-10 14:00:00');

-- Volcado de datos para la tabla `detalle_alquiler`
INSERT INTO `detalle_alquiler` (`idDetalleAlquiler`, `idAlquiler`, `idTraje`, `cantidad`, `cantidadPiezas`, `precio`, `subTotal`, `created_at`, `updated_at`) VALUES
(1, 1, 27, 1, 7, 50.00, 50.00, '2026-11-21 09:30:00', '2026-11-21 09:30:00'),
(2, 2, 21, 2, 5, 50.00, 100.00, '2026-11-21 10:45:00', '2026-11-21 10:45:00'),
(3, 3, 23, 1, 2, 80.00, 80.00, '2026-11-20 15:30:00', '2026-11-20 15:30:00'),
(4, 4, 24, 1, 4, 120.00, 120.00, '2026-11-20 17:00:00', '2026-11-20 17:00:00'),
(5, 5, 31, 1, 5, 50.00, 50.00, '2026-11-18 10:00:00', '2026-11-18 10:00:00'),
(6, 6, 18, 1, 4, 40.00, 40.00, '2026-11-18 11:30:00', '2026-11-18 11:30:00'),
(7, 7, 33, 2, 4, 35.00, 70.00, '2026-11-18 14:00:00', '2026-11-18 14:00:00'),
(8, 8, 17, 1, 7, 70.00, 70.00, '2026-11-14 10:00:00', '2026-11-14 10:00:00'),
(9, 9, 22, 1, 5, 80.00, 80.00, '2026-11-12 15:00:00', '2026-11-12 15:00:00'),
(10, 10, 37, 4, 5, 80.00, 320.00, '2026-11-20 16:00:00', '2026-11-20 16:00:00'),
(11, 11, 26, 5, 7, 40.00, 200.00, '2026-11-21 08:45:00', '2026-11-21 08:45:00'),
(12, 11, 25, 3, 5, 45.00, 135.00, '2026-11-21 08:45:00', '2026-11-21 08:45:00'),
(13, 12, 21, 3, 5, 50.00, 150.00, '2026-11-21 11:15:00', '2026-11-21 11:15:00'),
(14, 13, 40, 1, 5, 130.00, 130.00, '2026-11-19 14:00:00', '2026-11-19 14:00:00'),
(15, 14, 32, 1, 3, 30.00, 30.00, '2026-11-21 08:30:00', '2026-11-21 08:30:00'),
(16, 15, 30, 1, 4, 40.00, 40.00, '2026-11-21 12:00:00', '2026-11-21 12:00:00'),
(17, 16, 25, 2, 5, 45.00, 90.00, '2026-11-02 10:00:00', '2026-11-02 10:00:00'),
(18, 17, 26, 3, 7, 40.00, 120.00, '2026-11-04 11:30:00', '2026-11-04 11:30:00'),
(19, 18, 36, 2, 5, 90.00, 180.00, '2026-11-06 14:00:00', '2026-11-06 14:00:00'),
(20, 19, 20, 1, 1, 85.00, 85.00, '2026-11-08 16:00:00', '2026-11-08 16:00:00'),
(21, 20, 37, 3, 5, 80.00, 240.00, '2026-11-10 09:30:00', '2026-11-10 09:30:00'),
(22, 20, 21, 2, 5, 50.00, 100.00, '2026-11-10 09:30:00', '2026-11-10 09:30:00'),
(23, 21, 28, 2, 5, 45.00, 90.00, '2026-11-12 11:00:00', '2026-11-12 11:00:00'),
(24, 22, 24, 2, 4, 120.00, 240.00, '2026-11-15 15:30:00', '2026-11-15 15:30:00'),
(25, 23, 37, 2, 5, 80.00, 160.00, '2026-11-16 10:00:00', '2026-11-16 10:00:00'),
(26, 23, 17, 1, 7, 70.00, 70.00, '2026-11-16 10:00:00', '2026-11-16 10:00:00'),
(27, 24, 18, 2, 4, 40.00, 80.00, '2026-10-31 16:00:00', '2026-10-31 16:00:00'),
(28, 24, 31, 2, 5, 50.00, 100.00, '2026-10-31 16:00:00', '2026-10-31 16:00:00'),
(29, 25, 19, 2, 3, 45.00, 90.00, '2026-10-30 14:00:00', '2026-10-30 14:00:00'),
(30, 25, 35, 1, 5, 55.00, 55.00, '2026-10-30 14:00:00', '2026-10-30 14:00:00'),
(31, 26, 21, 4, 5, 50.00, 200.00, '2026-10-24 09:00:00', '2026-10-24 09:00:00'),
(32, 26, 36, 2, 5, 90.00, 180.00, '2026-10-24 09:00:00', '2026-10-24 09:00:00'),
(33, 27, 40, 1, 5, 130.00, 130.00, '2026-10-20 15:00:00', '2026-10-20 15:00:00'),
(34, 27, 23, 1, 2, 80.00, 80.00, '2026-10-20 15:00:00', '2026-10-20 15:00:00'),
(35, 28, 26, 4, 7, 40.00, 160.00, '2026-10-15 11:30:00', '2026-10-15 11:30:00'),
(36, 29, 17, 4, 7, 70.00, 280.00, '2026-08-05 08:30:00', '2026-08-05 08:30:00'),
(37, 29, 25, 4, 5, 45.00, 180.00, '2026-08-05 08:30:00', '2026-08-05 08:30:00'),
(38, 29, 27, 3, 7, 50.00, 150.00, '2026-08-05 08:30:00', '2026-08-05 08:30:00'),
(39, 30, 32, 3, 3, 30.00, 90.00, '2026-09-20 10:00:00', '2026-09-20 10:00:00'),
(40, 30, 33, 3, 4, 35.00, 105.00, '2026-09-20 10:00:00', '2026-09-20 10:00:00'),
(41, 31, 17, 6, 7, 65.00, 390.00, '2025-08-05 10:00:00', '2025-08-05 10:00:00'),
(42, 31, 26, 8, 7, 35.00, 280.00, '2025-08-05 10:00:00', '2025-08-05 10:00:00'),
(43, 32, 36, 5, 5, 85.00, 425.00, '2025-10-25 09:00:00', '2025-10-25 09:00:00'),
(44, 32, 37, 6, 5, 75.00, 450.00, '2025-10-25 09:00:00', '2025-10-25 09:00:00'),
(45, 33, 24, 4, 4, 110.00, 440.00, '2025-11-15 15:00:00', '2025-11-15 15:00:00'),
(46, 33, 20, 3, 1, 80.00, 240.00, '2025-11-15 15:00:00', '2025-11-15 15:00:00'),
(47, 34, 21, 6, 5, 45.00, 270.00, '2024-08-05 10:00:00', '2024-08-05 10:00:00'),
(48, 34, 25, 6, 5, 40.00, 240.00, '2024-08-05 10:00:00', '2024-08-05 10:00:00'),
(49, 35, 37, 5, 5, 70.00, 350.00, '2024-11-10 14:00:00', '2024-11-10 14:00:00'),
(50, 35, 17, 4, 7, 60.00, 240.00, '2024-11-10 14:00:00', '2024-11-10 14:00:00');

-- Volcado de datos para la tabla `devolucion`
INSERT INTO `devolucion` (`idDevolucion`, `idAlquiler`, `f_devolucion`, `f_real_devolucion`, `estado`, `cobroDano`, `monto_devuelto`, `observacion`, `idUsuario`, `f_ingreso_stock`) VALUES
(1, 6, '2026-11-20 18:00:00', '2026-11-20 18:25:00', 'lavado', 0.00, 50.00, 'Prenda devuelta completa, en proceso de lavado y planchado', 1, NULL),
(2, 7, '2026-11-20 19:00:00', '2026-11-20 19:15:00', 'lavado', 0.00, 60.00, 'Enviado a lavandería sin daños', 2, NULL),
(3, 8, '2026-11-16 18:00:00', '2026-11-16 17:40:00', 'ingresado_stock', 0.00, 70.00, 'Devuelto en perfecto estado. Stock reingresado.', 1, '2026-11-17 11:30:00'),
(4, 9, '2026-11-14 18:00:00', '2026-11-14 18:30:00', 'ingresado_stock', 20.00, 80.00, 'Descostura en botón de plata de manga izquierda. Cobro de Bs. 20 por reparación.', 2, '2026-11-15 16:00:00');

-- --------------------------------------------------------
-- TABLA: sessions (Estructura estándar de Laravel)
-- --------------------------------------------------------
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- TABLA: cache
-- --------------------------------------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- TABLA: migrations
-- --------------------------------------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_08_16_165720_create_clientes_table', 2),
(5, '2026_10_27_135006_add_fechas_devolucion_to_alquiler_table', 3),
(6, '2026_10_27_154425_add_reserva_fields_to_alquiler_table', 4);

-- --------------------------------------------------------
-- Restricciones y Llaves Foráneas
-- --------------------------------------------------------
ALTER TABLE `tallas_traje`
  ADD CONSTRAINT `fk_traje_tallas` FOREIGN KEY (`idTraje`) REFERENCES `traje` (`idTraje`) ON DELETE CASCADE;

ALTER TABLE `traje`
  ADD CONSTRAINT `fk_traje_categoria` FOREIGN KEY (`idCategoria`) REFERENCES `categoria` (`idCategoria`) ON DELETE CASCADE;

ALTER TABLE `alquiler`
  ADD CONSTRAINT `fk_alquiler_cliente` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`idCliente`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_alquiler_garantia` FOREIGN KEY (`idGarantia`) REFERENCES `garantia` (`idGarantia`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_alquiler_usuario` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE;

ALTER TABLE `detalle_alquiler`
  ADD CONSTRAINT `fk_detalle_alquiler` FOREIGN KEY (`idAlquiler`) REFERENCES `alquiler` (`idAlquiler`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_detalle_traje` FOREIGN KEY (`idTraje`) REFERENCES `traje` (`idTraje`) ON DELETE CASCADE;

ALTER TABLE `devolucion`
  ADD CONSTRAINT `fk_devolucion_alquiler` FOREIGN KEY (`idAlquiler`) REFERENCES `alquiler` (`idAlquiler`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_devolucion_usuario` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;