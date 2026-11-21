-- =============================================================================
-- BASE DE DATOS: bd_alquiler_trajes
-- Script generado con la estructura corregida y relaciones referenciales
-- =============================================================================

CREATE DATABASE IF NOT EXISTS `bd_alquiler_trajes` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `bd_alquiler_trajes`;

SET FOREIGN_KEY_CHECKS = 0;

-- 1. TABLA: rol
DROP TABLE IF EXISTS `rol`;
CREATE TABLE `rol` (
  `idRol` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`idRol`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. TABLA: usuario
DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `idUsuario` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_Rol` INT NULL DEFAULT 2,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`idUsuario`) USING BTREE,
  UNIQUE INDEX `email` (`email`) USING BTREE,
  KEY `fk_usuario_rol_idx` (`id_Rol`),
  CONSTRAINT `fk_usuario_rol` FOREIGN KEY (`id_Rol`) REFERENCES `rol` (`idRol`) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. TABLA: cliente
DROP TABLE IF EXISTS `cliente`;
CREATE TABLE `cliente` (
  `idCliente` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_cliente` ENUM('estudiante', 'fraternidad', 'publico general') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fraternidad` VARCHAR(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `colegio` VARCHAR(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `curso` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `lugar_baile` VARCHAR(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idCliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. TABLA: categoria
DROP TABLE IF EXISTS `categoria`;
CREATE TABLE `categoria` (
  `idCategoria` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`idCategoria`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. TABLA: traje
DROP TABLE IF EXISTS `traje`;
CREATE TABLE `traje` (
  `idTraje` INT NOT NULL AUTO_INCREMENT,
  `id_Categoria` INT NOT NULL,
  `nombre` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pieza` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cant_piezas` INT NOT NULL,
  `precio` DECIMAL(10,2) NOT NULL,
  `estado` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `imagen` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`idTraje`),
  KEY `fk_traje_categoria_idx` (`id_Categoria`),
  CONSTRAINT `fk_traje_categoria` FOREIGN KEY (`id_Categoria`) REFERENCES `categoria` (`idCategoria`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. TABLA: tallas_traje
DROP TABLE IF EXISTS `tallas_traje`;
CREATE TABLE `tallas_traje` (
  `idTalla` INT NOT NULL AUTO_INCREMENT,
  `id_traje` INT NOT NULL,
  `talla` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`idTalla`) USING BTREE,
  INDEX `id_traje` (`id_traje`) USING BTREE,
  CONSTRAINT `fk_tallas_traje` FOREIGN KEY (`id_traje`) REFERENCES `traje` (`idTraje`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. TABLA: garantia
DROP TABLE IF EXISTS `garantia`;
CREATE TABLE `garantia` (
  `idGarantia` INT NOT NULL AUTO_INCREMENT,
  `tipo` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `monto` INT NOT NULL,
  `descripcion` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`idGarantia`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. TABLA: alquiler
DROP TABLE IF EXISTS `alquiler`;
CREATE TABLE `alquiler` (
  `idAlquiler` INT NOT NULL AUTO_INCREMENT,
  `idCliente` INT NOT NULL,
  `idGarantia` INT NOT NULL,
  `idUsuario` INT NOT NULL,
  `f_recogida` DATETIME NOT NULL,
  `f_devolucion` DATETIME NOT NULL,
  `estado` ENUM('Reservado', 'Alquilado', 'Finalizado', 'Cancelado') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_operacion` ENUM('Alquiler', 'Reserva') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idAlquiler`),
  KEY `fk_alquiler_cliente_idx` (`idCliente`),
  KEY `fk_alquiler_garantia_idx` (`idGarantia`),
  KEY `fk_alquiler_usuario_idx` (`idUsuario`),
  CONSTRAINT `fk_alquiler_cliente` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`idCliente`) ON UPDATE CASCADE,
  CONSTRAINT `fk_alquiler_garantia` FOREIGN KEY (`idGarantia`) REFERENCES `garantia` (`idGarantia`) ON UPDATE CASCADE,
  CONSTRAINT `fk_alquiler_usuario` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. TABLA: detalle_alquiler
DROP TABLE IF EXISTS `detalle_alquiler`;
CREATE TABLE `detalle_alquiler` (
  `idDetalleAlquiler` INT NOT NULL AUTO_INCREMENT,
  `idAlquiler` INT NOT NULL,
  `idTraje` INT NOT NULL,
  `cantidad` INT NULL DEFAULT 1,
  `cantidadPiezas` INT NOT NULL,
  `precio` DECIMAL(10,2) NOT NULL,
  `subTotal` DECIMAL(10,2) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`idDetalleAlquiler`) USING BTREE,
  INDEX `idAlquiler` (`idAlquiler`) USING BTREE,
  INDEX `idTraje` (`idTraje`) USING BTREE,
  CONSTRAINT `fk_detalle_alquiler` FOREIGN KEY (`idAlquiler`) REFERENCES `alquiler` (`idAlquiler`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_detalle_traje` FOREIGN KEY (`idTraje`) REFERENCES `traje` (`idTraje`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. TABLA: devolucion
DROP TABLE IF EXISTS `devolucion`;
CREATE TABLE `devolucion` (
  `idDevolucion` INT NOT NULL AUTO_INCREMENT,
  `idAlquiler` INT NOT NULL,
  `f_devolucion` DATETIME NOT NULL,
  `f_real_devolucion` DATETIME NOT NULL,
  `estado` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cobroDano` DECIMAL(10,2) NULL DEFAULT 0.00,
  `monto_devuelto` DECIMAL(10,2) NULL DEFAULT 0.00,
  `observacion` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `idUsuario` INT NOT NULL,
  `f_ingreso_stock` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`idDevolucion`) USING BTREE,
  INDEX `idAlquiler` (`idAlquiler`) USING BTREE,
  INDEX `idUsuario` (`idUsuario`) USING BTREE,
  CONSTRAINT `fk_devolucion_alquiler` FOREIGN KEY (`idAlquiler`) REFERENCES `alquiler` (`idAlquiler`) ON UPDATE CASCADE,
  CONSTRAINT `fk_devolucion_usuario` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
