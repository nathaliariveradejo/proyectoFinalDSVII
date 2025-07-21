-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 21-07-2025 a las 10:04:25
-- Versión del servidor: 9.1.0
-- Versión de PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cmdb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`) VALUES
(1, 'Software'),
(2, 'Hardware'),
(3, 'Equipo de Red'),
(4, 'Equipo de Cómputo'),
(5, 'Equipo de Telefonía'),
(6, 'Licencia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colaboradores`
--

DROP TABLE IF EXISTS `colaboradores`;
CREATE TABLE IF NOT EXISTS `colaboradores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `identificacion` varchar(30) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `ubicacion` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  PRIMARY KEY (`id`),
  UNIQUE KEY `identificacion` (`identificacion`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `colaboradores`
--

INSERT INTO `colaboradores` (`id`, `nombre`, `apellido`, `identificacion`, `foto`, `ubicacion`, `telefono`, `correo`, `estado`) VALUES
(1, 'María', 'Ramírez', 'CC-12345678', '1753091827_mujer-joven-hermosa-sueter-rosa-calido-aspecto-natural-sonriente-retrato-aislado-cabello-largo_285396-896.avif', 'Edificio 303, Oficina 12', '5557896524', 'nath56@gmail.com', 'activo'),
(2, 'Luis', 'González', 'CC-87654321', '1753091818_LuisMi.jpeg', 'Casa 257', '555-9876', 'luis.gonzalez@empresa.com', 'activo'),
(3, 'Jhan', 'jaen', '111111', '', 'chepo', '6811-2567', 'jhan@gmail.com', 'inactivo'),
(6, 'German', 'Carreño', '1000--7', '1753091864_images__1_.jpeg', 'chepo', '5489101', 'dark.tsukuyomi19@gmail.com', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

DROP TABLE IF EXISTS `inventario`;
CREATE TABLE IF NOT EXISTS `inventario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombreEquipo` varchar(100) NOT NULL,
  `tipo_equipo` enum('computadora','telefono','red') NOT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `serie` varchar(50) DEFAULT NULL,
  `fechaIngreso` date NOT NULL,
  `costo` decimal(10,2) DEFAULT NULL,
  `estado` enum('disponible','asignado','descartado','donado') DEFAULT 'disponible',
  `depreciacionMeses` int DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `categoria_id` int DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `comentario` text,
  `id_colaborador` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_categoria` (`categoria_id`),
  KEY `fk_inventario_colaborador` (`id_colaborador`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`id`, `nombreEquipo`, `tipo_equipo`, `marca`, `serie`, `fechaIngreso`, `costo`, `estado`, `depreciacionMeses`, `imagen`, `thumbnail`, `categoria_id`, `categoria`, `comentario`, `id_colaborador`) VALUES
(26, 'Router Wi-Fi 6', 'red', 'TP-Link', 'TP-ARCHER-AX50', '2023-06-10', 159.00, 'asignado', 24, '1753091555_images__1_.jpeg', NULL, NULL, 'Equipo de Red', NULL, 1),
(27, 'Switch 24 Puertos', 'red', 'Cisco', 'C-SF200-24P-2023', '2023-01-05', 899.00, 'disponible', 48, '1753091586_switch-fast-ethernet-de-24-puertos.webp', NULL, NULL, 'Equipo de Red', NULL, NULL),
(24, 'ThinkPad X1 Carbon', 'computadora', 'Lenovo', '20XW-001US-LX1C', '2023-02-15', 1499.00, 'asignado', 30, '1753091490_lenovo-laptop-thinkpad-x1-carbon-gen-8-hero.avif', NULL, NULL, 'Equipo de Cómputo', NULL, 1),
(25, 'Inspiron 15 3000', 'computadora', 'Dell', 'D-INS15-3511-2023', '2023-04-05', 599.00, 'disponible', 24, '1753091523_61zRDADh_YS._AC_SL1500_.jpg', NULL, NULL, 'Equipo de Cómputo', NULL, NULL),
(22, 'Redmi Note 11', 'telefono', 'Xiaomi', 'XM-RN11-2023', '2023-07-01', 199.00, 'asignado', 18, '1753091406_MI-NOTE-11-6-128-23.jpg', NULL, NULL, 'Equipo de Telefonía', NULL, 1),
(23, 'MacBook Pro 14\"', 'computadora', 'Apple', 'M1Pro-MBP14-2023', '2023-01-20', 1999.00, 'disponible', 36, '1753091443_images.jpeg', NULL, NULL, 'Equipo de Cómputo', NULL, NULL),
(21, 'Galaxy S22', 'telefono', 'Samsung', 'SM-S901U-GS22', '2023-03-10', 799.00, 'disponible', 24, '1753091364_D_NQ_NP_651731-MLU75081570841_032024-O.webp', NULL, NULL, 'Equipo de Telefonía', NULL, NULL),
(20, 'iPhone 13 Pro', 'telefono', 'Apple', 'A2487-IP13P', '2023-05-15', 999.00, 'disponible', 24, '1753091347_6351fe656a64ca4db1565d53-apple-iphone-13-pro-max-128gb-gold.jpg', NULL, NULL, 'Equipo de Telefonía', NULL, NULL),
(28, 'Access Point Ubiquiti', 'red', 'Ubiquiti', 'UAP-AC-PRO-2023', '2023-03-22', 179.00, 'disponible', 36, '1753091629_ubiquiti-uap-ac-lr-package.jpg', NULL, NULL, 'Equipo de Red', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `necesidades_equipo`
--

DROP TABLE IF EXISTS `necesidades_equipo`;
CREATE TABLE IF NOT EXISTS `necesidades_equipo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `colaborador_id` int NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_solicitud` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('pendiente','completada','rechazada') NOT NULL DEFAULT 'pendiente',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `necesidades_equipo`
--

INSERT INTO `necesidades_equipo` (`id`, `colaborador_id`, `descripcion`, `fecha_solicitud`, `estado`) VALUES
(1, 6, 'Mousepad', '2025-07-20 20:38:57', 'completada'),
(2, 6, 'otro mousepad', '2025-07-20 20:39:14', 'rechazada'),
(3, 7, 'Quiero un mousepad hp', '2025-07-20 21:15:19', 'completada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `cedula` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `estado` tinyint DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `cedula`, `password`, `estado`) VALUES
(1, 'Admin', 'admin@cmdb.com', '1000-7', '866c0f59a5b21743b45e8849986e5c19c6e22ef778e56cdfee50792f55a4be21', 1),
(2, 'Jhansoni', 'jhan@gmail.com', '8-1013-1416', '123456789', 1),
(3, 'Germán', 'german@gmail.com', '8-1011-2284', 'cbf06754df2f70dd1f853bdccaec98cc6d8ba861a2a91d357540b9d561b6ceb7', 1),
(4, 'nathalia', 'nath@gmail.com', '333-333', 'cbf06754df2f70dd1f853bdccaec98cc6d8ba861a2a91d357540b9d561b6ceb7', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
