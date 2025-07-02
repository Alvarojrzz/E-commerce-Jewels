-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-06-2025 a las 22:04:11
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `web`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_Categoria` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_Categoria`, `nombre`, `slug`, `descripcion`, `imagen`) VALUES
(1, 'Anillos', 'anillos', 'Variedad de anillos para todas las ocasiones.', 'anillos.jpg'),
(2, 'Collares', 'collares', 'Elegantes collares de oro y plata.', 'collares.jpg'),
(3, 'Pendientes', 'pendientes', 'Pendientes de diseño único.', 'pendientes.jpg'),
(4, 'Pulseras', 'pulseras', 'Pulseras de oro y plata para hombre y mujer.', 'pulseras.jpg'),
(5, 'Compromiso', 'anillos-compromiso', 'Anillos de compromiso para sellar tu amor.', 'compromiso.jpg'),
(6, 'Bodas', 'alianzas-boda', 'Alianzas de boda en diferentes materiales.', 'bodas.jpg'),
(7, 'Relojes', 'relojes', 'Relojes de lujo para complementar tu estilo.', 'relojes.jpg'),
(8, 'Joyería Fina', 'joyeria-fina', 'Piezas de joyería de alta gama con diamantes y piedras preciosas.', 'joyeria-fina.jpg'),
(9, 'Joyería Hombre', 'joyeria-hombre', 'Colección de joyería diseñada para hombres.', 'joyeria-hombre.jpg'),
(10, 'Outlet', 'outlet', 'Joyería a precios reducidos.', 'outlet.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direcciones`
--

CREATE TABLE `direcciones` (
  `id_Direccion` int(11) NOT NULL,
  `calle` varchar(100) NOT NULL,
  `numero` varchar(20) NOT NULL,
  `piso` varchar(20) DEFAULT NULL,
  `cod_postal` varchar(10) NOT NULL,
  `id_Usuario` int(11) NOT NULL,
  `ciudad` varchar(50) NOT NULL,
  `provincia` varchar(50) NOT NULL,
  `pais` varchar(50) NOT NULL,
  `direccion_principal` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Volcado de datos para la tabla `direcciones`
--

INSERT INTO `direcciones` (`id_Direccion`, `calle`, `numero`, `piso`, `cod_postal`, `id_Usuario`, `ciudad`, `provincia`, `pais`, `direccion_principal`) VALUES
(1, 'fasdf', '', '', '0', 5, 'fasdf', 'fasdf', 'España', 0),
(9, 'fads', 'fasdf', 'asdf', 'asdf', 5, 'asdf', 'asdf', 'Italia', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE `eventos` (
  `id` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `hora` varchar(20) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Volcado de datos para la tabla `eventos`
--

INSERT INTO `eventos` (`id`, `titulo`, `fecha_inicio`, `fecha_fin`, `hora`, `descripcion`, `created_at`) VALUES
(1, 'Lanzamiento Colección Primavera Esplendor', '2025-03-15', '2025-03-22', '18:00', 'Descubre nuestra exclusiva nueva colección de joyas inspiradas en la frescura y el color de la primavera. ¡Edición limitada!', '2025-05-29 14:23:33'),
(2, 'Venta Flash: Piezas Únicas con Descuento', '2025-06-01', '2025-06-03', '10:00', 'Aprovecha descuentos increíbles en una selección de nuestras joyas más deslumbrantes. ¡Solo por tiempo limitado!', '2025-05-29 14:23:33'),
(4, 'Celebra el día de la Madre con un Brillo Especial', '2025-04-20', '2025-05-05', NULL, 'Sorprende a mamá con el regalo perfecto. Descuentos exclusivos y envoltorio especial para el Día de la Madre.', '2025-05-29 14:23:33'),
(5, 'Presentación Exclusiva: Diamante Centenario', '2025-09-10', '2025-09-10', '19:00', 'Invitación especial para admirar nuestra última obra maestra de alta joyería: el Diamante Centenario.', '2025-05-29 14:23:33'),
(6, 'Nuestro Aniversario: ¡Celebramos Contigo!', '2025-11-01', '2025-11-15', 'Toda la semana', 'Gracias por estos años de confianza. Disfruta de ofertas especiales en toda nuestra tienda por nuestro aniversario.', '2025-05-29 14:23:33'),
(7, 'Personaliza tu Joya: Servicio de Grabado Gratuito', '2025-10-05', '2025-10-12', '11:00', 'Haz tu joya aún más única con nuestro servicio de grabado gratuito por tiempo limitado.', '2025-05-29 14:23:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `id_Factura` int(11) NOT NULL,
  `id_Pedido` int(11) NOT NULL,
  `numero_factura` varchar(50) NOT NULL,
  `fecha_emision` datetime DEFAULT current_timestamp(),
  `iva` decimal(4,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `nombre_cliente` varchar(100) NOT NULL,
  `telefono_cliente` varchar(20) NOT NULL,
  `email_cliente` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`id_Factura`, `id_Pedido`, `numero_factura`, `fecha_emision`, `iva`, `total`, `nombre_cliente`, `telefono_cliente`, `email_cliente`) VALUES
(19, 23, 'F-000023', '2025-06-05 09:47:43', 13.65, 65.00, '', '654635321', 'admin@gmail.com'),
(20, 24, 'F-000024', '2025-06-05 09:53:27', 13.65, 65.00, 'Alvaro', '654040202', 'admin@gmail.com'),
(21, 25, 'F-000025', '2025-06-05 10:08:15', 13.65, 65.00, 'Alvaro', '654236245', 'admin@gmail.com'),
(22, 26, 'F-000026', '2025-06-05 11:03:04', 13.65, 65.00, 'Alvaro', '626105691', 'admin@gmail.com'),
(23, 27, 'F-000027', '2025-06-05 11:09:49', 13.65, 65.00, 'David vivancos', '666666666666', 'admin@gmail.com'),
(24, 28, 'F-000028', '2025-06-05 11:20:40', 99.99, 500.00, 'Alvaro', '625632562', 'admin@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes_contacto`
--

CREATE TABLE `mensajes_contacto` (
  `id_Mensaje` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_envio` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Volcado de datos para la tabla `mensajes_contacto`
--

INSERT INTO `mensajes_contacto` (`id_Mensaje`, `nombre`, `email`, `mensaje`, `fecha_envio`) VALUES
(12, 'afd', 'alvaro1@gmail.es', '54545', '2025-05-10 18:53:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_Pedido` int(11) NOT NULL,
  `id_Usuario` int(11) NOT NULL,
  `fecha_pedido` datetime DEFAULT current_timestamp(),
  `estado` enum('pendiente','procesando','enviado','entregado','cancelado') DEFAULT 'pendiente',
  `precio_total` decimal(10,2) NOT NULL,
  `id_Direccion` int(11) NOT NULL,
  `metodo_pago` enum('tarjeta','paypal','transferencia') NOT NULL,
  `numero_seguimiento` varchar(100) DEFAULT NULL,
  `notas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id_Pedido`, `id_Usuario`, `fecha_pedido`, `estado`, `precio_total`, `id_Direccion`, `metodo_pago`, `numero_seguimiento`, `notas`) VALUES
(1, 5, '2025-06-03 16:42:25', 'pendiente', 2250.00, 1, 'tarjeta', NULL, NULL),
(2, 5, '2025-06-03 17:35:21', 'pendiente', 750.00, 2, 'tarjeta', NULL, NULL),
(3, 5, '2025-06-03 17:44:40', 'pendiente', 15.00, 3, 'tarjeta', NULL, NULL),
(4, 5, '2025-06-03 17:45:50', 'pendiente', 750.00, 4, 'tarjeta', NULL, NULL),
(5, 5, '2025-06-03 17:47:43', 'pendiente', 500.00, 5, 'tarjeta', NULL, NULL),
(6, 5, '2025-06-04 10:47:44', 'pendiente', 1500.00, 6, 'tarjeta', NULL, NULL),
(7, 5, '2025-06-04 10:50:08', 'pendiente', 750.00, 7, 'tarjeta', NULL, NULL),
(8, 5, '2025-06-04 13:12:56', 'pendiente', 0.00, 1, 'tarjeta', NULL, NULL),
(9, 5, '2025-06-04 13:13:21', 'pendiente', 0.00, 1, 'tarjeta', NULL, NULL),
(10, 5, '2025-06-04 13:19:53', 'pendiente', 65.00, 1, 'tarjeta', NULL, NULL),
(11, 5, '2025-06-04 23:28:03', 'pendiente', 1545.00, 1, 'tarjeta', NULL, NULL),
(12, 5, '2025-06-04 23:28:37', 'pendiente', 65.00, 1, 'tarjeta', NULL, NULL),
(13, 5, '2025-06-04 23:33:18', 'pendiente', 65.00, 1, 'tarjeta', NULL, NULL),
(14, 5, '2025-06-05 00:56:17', 'pendiente', 65.00, 1, 'tarjeta', NULL, NULL),
(15, 5, '2025-06-05 01:20:13', 'pendiente', 65.00, 1, 'tarjeta', NULL, NULL),
(16, 5, '2025-06-05 01:32:05', 'pendiente', 500.00, 1, 'tarjeta', NULL, NULL),
(17, 5, '2025-06-05 01:33:12', 'pendiente', 500.00, 1, 'tarjeta', NULL, NULL),
(18, 5, '2025-06-05 09:29:48', 'pendiente', 65.00, 1, 'tarjeta', NULL, NULL),
(19, 5, '2025-06-05 09:37:43', 'pendiente', 65.00, 1, 'tarjeta', NULL, NULL),
(20, 5, '2025-06-05 09:42:04', 'pendiente', 65.00, 1, 'tarjeta', NULL, NULL),
(21, 5, '2025-06-05 09:44:15', 'pendiente', 65.00, 1, 'tarjeta', NULL, NULL),
(22, 5, '2025-06-05 09:46:27', 'pendiente', 65.00, 1, 'tarjeta', NULL, NULL),
(23, 5, '2025-06-05 09:47:43', 'pendiente', 65.00, 1, 'tarjeta', NULL, NULL),
(24, 5, '2025-06-05 09:53:27', 'pendiente', 65.00, 1, 'tarjeta', NULL, NULL),
(25, 5, '2025-06-05 10:08:15', 'pendiente', 65.00, 1, 'tarjeta', NULL, NULL),
(26, 5, '2025-06-05 11:03:04', 'pendiente', 65.00, 1, 'tarjeta', NULL, NULL),
(27, 5, '2025-06-05 11:09:49', 'pendiente', 65.00, 9, 'tarjeta', NULL, NULL),
(28, 5, '2025-06-05 11:20:40', 'pendiente', 500.00, 1, 'tarjeta', NULL, NULL),
(29, 5, '2025-06-05 13:00:57', 'pendiente', 39095.00, 1, 'tarjeta', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_productos`
--

CREATE TABLE `pedido_productos` (
  `id_Pedido` int(11) NOT NULL,
  `id_Producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Volcado de datos para la tabla `pedido_productos`
--

INSERT INTO `pedido_productos` (`id_Pedido`, `id_Producto`, `cantidad`, `precio_unitario`) VALUES
(1, 11, 3, 750.00),
(2, 11, 1, 750.00),
(3, 49, 1, 15.00),
(4, 11, 1, 750.00),
(5, 1, 1, 500.00),
(6, 11, 2, 750.00),
(7, 11, 1, 750.00),
(8, 11, 2, 0.00),
(8, 12, 1, 0.00),
(8, 13, 1, 0.00),
(9, 11, 1, 0.00),
(10, 12, 1, 65.00),
(11, 1, 1, 500.00),
(11, 12, 1, 65.00),
(11, 13, 1, 980.00),
(12, 12, 1, 65.00),
(13, 12, 1, 65.00),
(14, 12, 1, 65.00),
(15, 12, 1, 65.00),
(16, 1, 1, 500.00),
(17, 1, 1, 500.00),
(18, 12, 1, 65.00),
(19, 12, 1, 65.00),
(20, 12, 1, 65.00),
(21, 12, 1, 65.00),
(22, 12, 1, 65.00),
(23, 12, 1, 65.00),
(24, 12, 1, 65.00),
(25, 12, 1, 65.00),
(26, 12, 1, 65.00),
(27, 12, 1, 65.00),
(28, 1, 1, 500.00),
(29, 12, 13, 65.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_Producto` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `id_Categoria` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `quilates` decimal(3,1) NOT NULL,
  `material` enum('Oro','Plata','Platino','Otros') NOT NULL,
  `peso_gramos` decimal(6,2) DEFAULT NULL,
  `dimensiones` varchar(50) DEFAULT NULL,
  `sku` varchar(50) NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `disponible` tinyint(1) DEFAULT 1,
  `imagen_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_Producto`, `nombre`, `descripcion`, `id_Categoria`, `precio`, `stock`, `quilates`, `material`, `peso_gramos`, `dimensiones`, `sku`, `fecha_creacion`, `fecha_actualizacion`, `disponible`, `imagen_url`) VALUES
(1, 'Anillo de Oro Clásico', 'Elegante anillo de oro de 18 quilates, perfecto para cualquier ocasión.', 1, 500.00, 5, 18.0, 'Oro', 5.50, '20x20x5 mm', 'AN-ORO-18K-001', '2025-05-08 17:37:56', '2025-06-05 11:20:40', 1, 'assets/img/productos/anillo-oro.webp'),
(2, 'Collar de Plata Luna', 'Delicado collar de plata de ley con un colgante de luna.', 2, 150.00, 25, 9.3, 'Plata', 10.20, '45 cm', 'CO-PLATA-LUNA-02', '2025-05-08 17:37:56', '2025-05-31 17:55:53', 1, 'assets/img/productos/collar-plata-luna-1.webp'),
(3, 'Pendientes Diamante Solitario', 'Deslumbrantes pendientes de oro blanco con diamantes solitarios de 0.5 quilates cada uno.', 3, 1200.00, 5, 0.5, 'Oro', 2.10, '7 mm', 'PE-ORO-DIA-003', '2025-05-08 17:37:56', '2025-05-31 17:55:53', 1, 'assets/img/productos/pendientes-diamante-1.webp'),
(4, 'Pulsera de Cuero y Plata Trenzada', 'Moderna pulsera de cuero trenzado con detalles en plata de ley.', 4, 80.00, 30, 9.3, 'Plata', 15.80, '20 cm', 'PU-CUERO-PLATA-04', '2025-05-08 17:37:56', '2025-05-31 18:03:34', 1, 'assets/img/productos/pulsera-cuero-plata-1.webp'),
(5, 'Anillo de Compromiso Diamante Corte Pera', 'Elegante anillo de compromiso de platino con un diamante corte pera de 1 quilate.', 5, 3500.00, 2, 1.0, 'Platino', 7.30, '6 mm', 'AC-PLAT-PERA-05', '2025-05-08 17:37:56', '2025-05-31 18:03:34', 1, 'assets/img/productos/anillo-compromiso-pera-1.webp'),
(6, 'Alianzas de Boda Oro Amarillo', 'Clásicas alianzas de boda de oro amarillo de 18 quilates, diseño tradicional.', 6, 1000.00, 8, 18.0, 'Oro', 9.00, '4 mm', 'AB-ORO-AMAR-06', '2025-05-08 17:37:56', '2025-05-31 18:03:34', 1, 'assets/img/productos/alianzas-oro-amarillo-1.jpg'),
(7, 'Reloj de Oro Rosa con Diamantes', 'Sofisticado reloj de oro rosa de 18 quilates con incrustaciones de diamantes.', 7, 15000.00, 3, 2.0, 'Oro', 85.50, '40 mm', 'RE-ORO-ROSA-07', '2025-05-08 17:37:56', '2025-05-31 18:03:34', 1, 'assets/img/productos/reloj-oro-rosa-1.jpg'),
(8, 'Collar de Diamantes y Zafiros', 'Espectacular collar de oro blanco con diamantes y zafiros azules.', 8, 25000.00, 1, 5.0, 'Oro', 120.00, '42 cm', 'CF-ORO-DIA-ZAF-08', '2025-05-08 17:37:56', '2025-05-31 18:03:34', 1, 'assets/img/productos/collar-diamantes-zafiros-1.webp'),
(9, 'Pulsera de Plata y Onix para Hombre', 'Robusta pulsera de plata de ley con incrustaciones de ónix negro, diseño masculino.', 9, 200.00, 15, 9.3, 'Plata', 25.00, '22 cm', 'PH-PLATA-ONIX-09', '2025-05-08 17:37:56', '2025-05-31 18:03:34', 1, 'assets/img/productos/pulsera-plata-onix-1.webp'),
(10, 'Anillo de Plata', 'Anillo de plata de ley con un diseño moderno, disponible a precio de outlet.', 10, 50.00, 50, 9.3, 'Plata', 8.00, '18 mm', 'AO-PLATA-DESC-10', '2025-05-08 17:37:56', '2025-05-31 18:03:34', 1, 'assets/img/productos/anillo-plata.webp'),
(11, 'Anillo Solitario Oro Blanco con Zafiro', 'Elegante anillo solitario de oro blanco 14k con un zafiro central ovalado y detalles de pequeños diamantes.', 1, 750.00, 0, 14.0, 'Oro', 4.50, 'Talla 7, Zafiro 6x4mm', 'AN-ORO-ZAF-011', '0000-00-00 00:00:00', '2025-06-04 13:13:21', 1, 'assets/img/productos/AnilloSolitarioOroBlancoconZafiro.webp'),
(12, 'Anillo de Plata Minimalista Ola', 'Anillo de plata de ley con un diseño de ola sutil, perfecto para uso diario y combinable.', 1, 65.00, 100, 9.3, 'Plata', 3.00, 'Talla ajustable', 'AN-PLATA-OLA-012', '2025-05-17 17:15:23', '2025-06-05 13:02:19', 1, 'assets/img/productos/AnillodePlataMinimalistaOla.webp'),
(13, 'Anillo Tres Oros Entrelazados', 'Sofisticado anillo con bandas entrelazadas de oro amarillo, blanco y rosa de 18k, simbolizando unión.', 1, 980.00, 3, 18.0, 'Oro', 6.80, 'Ancho 8mm, Talla 8', 'AN-ORO-TRES-013', '2025-05-17 17:15:23', '2025-06-04 23:28:03', 1, 'assets/img/productos/AnilloTresOrosEntrelazados.webp'),
(14, 'Anillo Sello de Plata Personalizable', 'Anillo tipo sello en plata de ley, con superficie lisa ideal para grabado personalizado de iniciales o escudo.', 1, 120.00, 12, 9.3, 'Plata', 10.50, 'Cara 15x15mm, Talla 10', 'AN-PLATA-SELLO-014', '2025-05-17 17:15:23', '2025-05-31 18:10:18', 1, 'assets/img/productos/anillo-oro.webp'),
(15, 'Collar de Oro Amarillo Corazón con Diamantes', 'Fino collar de oro amarillo de 18k con un colgante en forma de corazón y pequeños diamantes incrustados.', 2, 850.00, 10, 0.2, 'Oro', 7.20, 'Cadena 45cm, Colgante 1.5cm', 'CO-ORO-COR-DIA-015', '2025-05-17 17:15:23', '2025-05-31 18:41:18', 1, 'assets/img/productos/collar-plata-luna-2.jpg'),
(16, 'Gargantilla de Plata de Ley con Estrellas', 'Moderna gargantilla de plata de ley con múltiples colgantes de estrellas, ideal para un look juvenil.', 2, 95.00, 18, 9.3, 'Plata', 8.50, '38cm + 5cm extensión', 'CO-PLATA-EST-016', '2025-05-17 17:15:23', '2025-05-31 18:41:32', 1, 'assets/img/productos/collar-plata-luna-1.webp'),
(17, 'Collar Clásico de Perlas Cultivadas Blancas', 'Elegante collar de una hilera de perlas cultivadas blancas de agua dulce con cierre de seguridad en oro.', 2, 600.00, 7, 7.0, 'Otros', 30.00, 'Perlas 7-8mm, Longitud 42cm', 'CO-PERLA-CLA-017', '2025-05-17 17:15:23', '2025-05-31 18:41:18', 1, 'assets/img/productos/collar-plata-luna-2.jpg'),
(18, 'Colgante de Platino con Letra Inicial y Diamante', 'Fino colgante de platino con letra inicial personalizable y un pequeño diamante de acento. Cadena no incluida.', 2, 1100.00, 5, 0.1, 'Platino', 4.80, 'Letra 1cm, Diamante 0.05ct', 'CO-PLAT-LETRA-018', '2025-05-17 17:15:23', '2025-05-31 18:26:28', 1, 'assets/img/productos/collar-plata-luna-1.webp'),
(19, 'Pendientes de Aro Oro Amarillo Gruesos', 'Clásicos pendientes de aro gruesos en oro amarillo de 18k, un básico imprescindible.', 3, 450.00, 15, 18.0, 'Oro', 6.00, 'Diámetro 25mm, Ancho 4mm', 'PE-ORO-ARO-019', '2025-05-17 17:15:23', '2025-05-31 18:10:18', 1, 'assets/img/productos/anillo-oro.webp'),
(20, 'Pendientes Largos Plata con Piedras Azules', 'Elegantes pendientes largos de plata de ley con cascada de piedras semipreciosas azules (topacio).', 3, 180.00, 10, 9.3, 'Plata', 9.50, 'Longitud 5cm', 'PE-PLATA-PIEDRA-020', '2025-05-17 17:15:23', '2025-05-31 18:10:18', 1, 'assets/img/productos/anillo-oro.webp'),
(21, 'Dormilonas de Oro Blanco con Esmeralda', 'Delicadas dormilonas de oro blanco de 14k con esmeraldas redondas engastadas.', 3, 950.00, 6, 0.6, 'Oro', 1.80, 'Esmeralda 4mm', 'PE-ORO-ESM-021', '2025-05-17 17:15:23', '2025-05-31 18:10:18', 1, 'assets/img/productos/anillo-oro.webp'),
(22, 'Pendientes Trepadores Plata Circonitas', 'Modernos pendientes trepadores (ear climbers) de plata de ley con circonitas brillantes.', 3, 75.00, 22, 9.3, 'Plata', 3.20, 'Longitud 2.5cm', 'PE-PLATA-TREP-022', '2025-05-17 17:15:23', '2025-05-31 18:10:18', 1, 'assets/img/productos/anillo-oro.webp'),
(23, 'Pulsera Rígida de Oro Amarillo Martillado', 'Elegante esclava rígida de oro amarillo de 18k con acabado martillado artesanal.', 4, 1200.00, 7, 18.0, 'Oro', 15.50, 'Diámetro 6cm, Ancho 5mm', 'PU-ORO-MART-023', '2025-05-17 17:15:23', '2025-05-31 18:40:00', 1, 'assets/img/productos/pulsera-cuero-plata-1.webp'),
(24, 'Pulsera de Plata con Charms Marinos', 'Divertida pulsera de plata de ley con varios charms colgantes de temática marina (concha, estrella de mar, ancla).', 4, 110.00, 18, 9.3, 'Plata', 12.30, 'Longitud 19cm ajustable', 'PU-PLATA-CHARM-024', '2025-05-17 17:15:23', '2025-05-31 18:40:00', 1, 'assets/img/productos/pulsera-cuero-plata-1.webp'),
(25, 'Pulsera de Cuentas de Ónix y Acero para Hombre', 'Pulsera elástica con cuentas de ónix mate y detalles en acero inoxidable, estilo moderno.', 4, 60.00, 25, 0.0, 'Otros', 22.00, 'Cuentas 8mm, Longitud 20cm', 'PU-ONIX-ACERO-025', '2025-05-17 17:15:23', '2025-05-31 18:40:00', 1, 'assets/img/productos/pulsera-cuero-plata-1.webp'),
(26, 'Tobillera de Plata con Pequeñas Perlas', 'Delicada tobillera de plata de ley con pequeñas perlas de río intercaladas, perfecta para el verano.', 4, 85.00, 15, 9.3, 'Plata', 7.80, 'Longitud 23cm + 3cm extensión', 'PU-PLATA-TOBI-026', '2025-05-17 17:15:23', '2025-05-31 18:10:18', 1, 'assets/img/productos/anillo-oro.webp'),
(27, 'Anillo de Compromiso Solitario Clásico Oro Amarillo', 'Atrapante anillo de compromiso en oro amarillo 18k con diamante solitario corte brillante de 0.75 quilates.', 5, 2800.00, 4, 0.8, 'Oro', 3.80, 'Diamante 0.75ct, Talla 6', 'AC-ORO-SOL-027', '2025-05-17 17:15:23', '2025-05-31 18:10:18', 1, 'assets/img/productos/anillo-oro.webp'),
(28, 'Anillo de Compromiso Platino Halo Esmeralda', 'Lujoso anillo de compromiso en platino con esmeralda central corte princesa rodeada por un halo de diamantes.', 5, 4500.00, 3, 1.2, 'Platino', 6.50, 'Esmeralda 1ct, Diamantes 0.2ct', 'AC-PLAT-ESMHALO-028', '2025-05-17 17:15:23', '2025-05-31 18:10:18', 1, 'assets/img/productos/anillo-oro.webp'),
(29, 'Anillo de Compromiso Oro Rosa Vintage con Morganita', 'Romántico anillo de compromiso estilo vintage en oro rosa 14k con morganita ovalada y detalles milgrain.', 5, 1800.00, 6, 1.5, 'Oro', 5.20, 'Morganita 1.5ct, Talla 7', 'AC-ORO-MORGAN-029', '2025-05-17 17:15:23', '2025-05-31 18:10:18', 1, 'assets/img/productos/anillo-oro.webp'),
(30, 'Anillo de Compromiso Media Alianza Diamantes', 'Sutil y elegante anillo de compromiso tipo media alianza en oro blanco 18k con hilera de diamantes.', 5, 1500.00, 8, 0.5, 'Oro', 3.00, 'Diamantes total 0.5ct, Talla 5', 'AC-ORO-MEDIA-030', '2025-05-17 17:15:23', '2025-05-31 18:10:18', 1, 'assets/img/productos/anillo-oro.webp'),
(31, 'Alianzas de Boda Platino Planas', 'Modernas alianzas de boda de platino PT950 con perfil plano y acabado mate. Precio por par.', 6, 2200.00, 5, 0.0, 'Platino', 14.00, 'Ancho 3mm y 5mm', 'AB-PLAT-PLANAS-031', '2025-05-17 17:15:23', '2025-05-31 18:10:18', 1, 'assets/img/productos/anillo-oro.webp'),
(32, 'Alianzas de Boda Oro Blanco con Diamante (Mujer)', 'Alianza de boda para mujer en oro blanco 18k con un pequeño diamante engastado. Diseño confort.', 6, 700.00, 10, 0.0, 'Oro', 4.50, 'Ancho 3mm, Diamante 0.03ct', 'AB-ORO-DIAMUJ-032', '2025-05-17 17:15:23', '2025-05-31 18:10:18', 1, 'assets/img/productos/anillo-oro.webp'),
(33, 'Alianzas de Boda Oro Rosa Texturizadas', 'Originales alianzas de boda en oro rosa 18k con una sutil textura rayada. Precio por par.', 6, 1300.00, 6, 18.0, 'Oro', 10.00, 'Ancho 4mm', 'AB-ORO-TEXT-033', '2025-05-17 17:15:23', '2025-05-31 18:10:18', 1, 'assets/img/productos/anillo-oro.webp'),
(34, 'Alianzas de Boda Bicolor Oro Amarillo y Blanco', 'Elegantes alianzas de boda bicolor, combinando oro amarillo y oro blanco de 18k en un diseño entrelazado. Precio por par.', 6, 1650.00, 4, 18.0, 'Oro', 11.50, 'Ancho 5mm', 'AB-ORO-BICOLOR-034', '2025-05-17 17:15:23', '2025-05-31 18:10:18', 1, 'assets/img/productos/anillo-oro.webp'),
(35, 'Reloj Suizo Automático Acero Inoxidable', 'Reloj suizo para caballero con movimiento automático, caja y correa de acero inoxidable, esfera azul.', 7, 2500.00, 5, 0.0, 'Otros', 150.00, 'Caja 42mm, Resist. Agua 100m', 'RE-ACERO-AUTO-035', '2025-05-17 17:15:23', '2025-05-31 18:10:18', 1, 'assets/img/productos/anillo-oro.webp'),
(36, 'Reloj Mujer Oro Amarillo Esfera Nácar', 'Elegante reloj para mujer en oro amarillo de 18k con esfera de nácar y pequeños índices de diamante.', 7, 8500.00, 3, 0.1, 'Oro', 70.00, 'Caja 30mm', 'RE-ORO-NACAR-036', '2025-05-17 17:15:23', '2025-05-31 18:10:18', 1, 'assets/img/productos/anillo-oro.webp'),
(37, 'Reloj Deportivo Cronógrafo Titanio', 'Reloj deportivo cronógrafo para hombre, caja de titanio ligera y resistente, correa de caucho negra.', 7, 1200.00, 8, 0.0, 'Otros', 95.00, 'Caja 44mm, Resist. Agua 200m', 'RE-TITAN-CRONO-037', '2025-05-17 17:15:23', '2025-05-31 18:10:18', 1, 'assets/img/productos/anillo-oro.webp'),
(38, 'Reloj Vintage Plata Chapado Oro Rosa', 'Reloj de estilo vintage con caja de plata chapada en oro rosa, correa de cuero marrón y esfera minimalista.', 7, 350.00, 12, 9.3, 'Plata', 60.00, 'Caja 38mm', 'RE-PLATA-VINT-038', '2025-05-17 17:15:23', '2025-05-31 18:10:18', 1, 'assets/img/productos/anillo-oro.webp'),
(39, 'Gargantilla Rivière de Diamantes Oro Blanco', 'Impresionante gargantilla rivière de diamantes talla brillante engastados en oro blanco 18k, total 10 quilates.', 8, 45000.00, 1, 10.0, 'Oro', 25.00, 'Longitud 40cm, Diamantes 10ct TW', 'CF-ORO-RIVDIAM-039', '2025-05-17 17:15:23', '2025-05-31 18:10:18', 1, 'assets/img/productos/anillo-oro.webp'),
(40, 'Anillo Cóctel Rubí y Diamantes Platino', 'Espectacular anillo de cóctel en platino con un rubí central de talla oval rodeado de diamantes marquesa y brillante.', 8, 32000.00, 1, 3.5, 'Platino', 12.50, 'Rubí 3ct, Diamantes 1.5ct TW', 'CF-PLAT-RUBIDIAM-040', '2025-05-17 17:15:23', '2025-05-31 18:10:18', 1, 'assets/img/productos/anillo-oro.webp'),
(41, 'Pendientes Colgantes Zafiro Ceilán y Diamantes', 'Exquisitos pendientes colgantes de oro blanco 18k con zafiros de Ceilán talla pera y orla de diamantes.', 8, 18000.00, 2, 4.0, 'Oro', 8.00, 'Zafiros 2ct c/u, Longitud 3cm', 'CF-ORO-ZAFCEIL-041', '2025-05-17 17:15:23', '2025-05-31 18:10:18', 1, 'assets/img/productos/anillo-oro.webp'),
(42, 'Pulsera Brazalete Oro Amarillo con Esmeraldas Colombianas', 'Opulento brazalete de oro amarillo 22k con esmeraldas colombianas talla rectangular engastadas en canal.', 8, 55000.00, 1, 22.0, 'Oro', 65.00, 'Esmeraldas 15ct TW, Ancho 2cm', 'CF-ORO-ESMCOL-042', '2025-05-17 17:15:23', '2025-05-31 18:10:18', 1, 'assets/img/productos/anillo-oro.webp'),
(43, 'Gemelos de Oro Blanco y Ónix', 'Elegantes gemelos cuadrados de oro blanco 18k con incrustación central de ónix negro pulido.', 9, 950.00, 8, 18.0, 'Oro', 12.00, '15x15 mm', 'PH-ORO-ONIXGEM-043', '2025-05-17 17:15:23', '2025-05-31 18:10:18', 1, 'assets/img/productos/anillo-oro.webp'),
(44, 'Cadena Barbada de Plata Maciza', 'Robusta cadena barbada de plata de ley maciza para hombre, acabado brillante y cierre de mosquetón seguro.', 9, 320.00, 10, 9.3, 'Plata', 85.00, 'Longitud 60cm, Ancho 8mm', 'PH-PLATA-BARB-044', '2025-05-17 17:15:23', '2025-05-31 18:10:18', 1, 'assets/img/productos/anillo-oro.webp'),
(45, 'Anillo de Titanio y Fibra de Carbono', 'Moderno anillo para hombre fabricado en titanio ligero con incrustación central de fibra de carbono negra.', 9, 150.00, 15, 0.0, 'Otros', 7.00, 'Ancho 8mm, Talla 11', 'PH-TITAN-CARBON-045', '2025-05-17 17:15:23', '2025-05-31 18:10:18', 1, 'assets/img/productos/anillo-oro.webp'),
(46, 'Colgante Placa Militar Acero Grabable', 'Colgante tipo placa militar (dog tag) en acero inoxidable pulido, ideal para grabado personalizado. Incluye cadena de bolas.', 9, 70.00, 20, 0.0, 'Otros', 25.00, 'Placa 5x3cm, Cadena 60cm', 'PH-ACERO-PLACA-046', '2025-05-17 17:15:23', '2025-05-31 18:10:18', 1, 'assets/img/productos/anillo-oro.webp'),
(47, 'Pendientes de Plata con Circonita', 'Sencillos pendientes de plata de ley con una circonita central, precio especial por liquidación.', 10, 25.00, 40, 9.3, 'Plata', 2.50, 'Circonita 5mm', 'AO-PLATA-CIRC-047', '2025-05-17 17:15:23', '2025-05-31 18:10:18', 1, 'assets/img/productos/anillo-oro.webp'),
(48, 'Collar de Acero Dorado con Dije Corazón', 'Collar de acero inoxidable con baño dorado y dije de corazón. Pequeños defectos de exposición.', 10, 19.99, 30, 0.0, 'Otros', 15.00, 'Cadena 45cm', 'AO-ACERO-CORAZ-048', '2025-05-17 17:15:23', '2025-05-31 18:10:18', 1, 'assets/img/productos/anillo-oro.webp'),
(49, 'Pulsera de Cuero Sintético con Cierre Magnético ', 'Pulsera unisex de cuero sintético trenzado negro con cierre magnético de acero. Stock limitado.', 10, 15.00, 49, 0.0, 'Otros', 18.00, 'Longitud 21cm', 'AO-CUERO-MAGN-049', '2025-05-17 17:15:23', '2025-06-03 17:44:40', 1, 'assets/img/productos/anillo-oro.webp'),
(50, 'Anillo de Acero Inoxidable', 'Anillo básico de acero inoxidable, disponible en tallas sueltas a precio de remate.', 10, 9.90, 60, 0.0, 'Otros', 10.00, 'Varias tallas, Ancho 6mm', 'AO-ACERO-ANILLO-050', '2025-05-17 17:15:23', '2025-05-31 18:10:18', 1, 'assets/img/productos/anillo-oro.webp');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reseñas`
--

CREATE TABLE `reseñas` (
  `id_Reseña` int(11) NOT NULL,
  `id_Usuario` int(11) NOT NULL,
  `id_Producto` int(11) NOT NULL,
  `puntuacion` tinyint(4) NOT NULL CHECK (`puntuacion` between 1 and 5),
  `comentario` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Volcado de datos para la tabla `reseñas`
--

INSERT INTO `reseñas` (`id_Reseña`, `id_Usuario`, `id_Producto`, `puntuacion`, `comentario`, `fecha`) VALUES
(131, 1, 1, 5, '¡Absolutamente maravilloso! Superó mis expectativas. El oro brilla muchísimo.', '2024-01-15 09:30:00'),
(132, 1, 1, 4, 'Muy buen anillo, elegante y clásico. El envío fue rápido.', '2024-01-18 13:22:00'),
(133, 3, 2, 5, 'El collar de luna es precioso y delicado. Justo lo que buscaba.', '2024-02-01 08:00:00'),
(134, 1, 3, 5, 'Pendientes deslumbrantes, los diamantes tienen un brillo increíble. Perfectos para una ocasión especial.', '2024-02-05 17:05:00'),
(135, 3, 4, 3, 'La pulsera es bonita, pero el cuero parece un poco más fino de lo esperado. Cumple su función.', '2024-02-10 10:45:00'),
(136, 1, 5, 5, '¡Dijo que sí! El anillo de compromiso es espectacular, el diamante es impresionante.', '2024-03-01 19:00:00'),
(137, 1, 5, 5, 'Calidad excepcional, el platino se siente muy robusto y el diamante es de una claridad asombrosa.', '2024-03-03 11:12:12'),
(138, 1, 6, 4, 'Alianzas clásicas y hermosas. El oro amarillo es de buena calidad. Estamos muy contentos.', '2024-03-10 15:30:00'),
(139, 1, 7, 5, 'El reloj es una obra de arte. Lujoso y con un acabado impecable. Vale cada céntimo.', '2024-03-15 07:55:00'),
(140, 1, 8, 5, 'Un collar verdaderamente fino. Los zafiros son de un azul profundo y los diamantes complementan perfectamente.', '2024-04-02 15:00:00'),
(141, 1, 9, 4, 'Pulsera masculina con mucho estilo. El ónix le da un toque especial. Buena compra.', '2024-04-05 11:20:00'),
(142, 1, 10, 2, 'El anillo de outlet está bien por el precio, pero tenía un pequeño rasguño. Se nota que es de liquidación.', '2024-04-10 08:10:00'),
(143, 3, 10, 3, 'Para ser de outlet, la calidad es aceptable. Un diseño moderno a buen precio.', '2024-04-11 09:11:11'),
(144, 3, 11, 5, 'El zafiro de este anillo es espectacular, y el oro blanco lo hace muy elegante. ¡Me encanta!', '2024-05-01 17:00:00'),
(145, 3, 12, 4, 'Anillo minimalista muy bonito, ideal para el día a día. La plata es de buena calidad.', '2024-05-03 12:30:00'),
(146, 3, 13, 5, 'Los tres oros entrelazados quedan preciosos. Un diseño original y de gran calidad.', '2024-05-05 08:00:00'),
(147, 3, 14, 4, 'Perfecto para grabar mis iniciales. El anillo sello es robusto y bien acabado.', '2024-05-08 14:20:00'),
(148, 3, 15, 5, 'El collar de corazón con diamantes es un sueño. Un regalo perfecto.', '2024-06-01 09:50:00'),
(149, 3, 16, 3, 'La gargantilla de estrellas es juvenil, quizás un poco frágil para el uso diario, pero bonita.', '2024-06-04 07:15:00'),
(150, 3, 17, 5, 'Un collar de perlas clásico que nunca falla. Las perlas son uniformes y el cierre es seguro.', '2024-06-07 16:00:00'),
(151, 3, 18, 4, 'El colgante de platino con la inicial es muy fino. Un detalle que el diamante sea pequeño.', '2024-06-10 10:30:00'),
(152, 1, 19, 5, 'Aros de oro gruesos y de calidad. Justo lo que buscaba, un básico imprescindible.', '2024-07-02 13:00:00'),
(153, 1, 20, 4, 'Los pendientes largos con piedras azules son muy llamativos. Ideales para una fiesta.', '2024-07-05 15:45:00'),
(154, 1, 21, 5, 'Las dormilonas con esmeralda son preciosas, el color de la piedra es intenso.', '2024-07-08 08:10:00'),
(155, 1, 22, 3, 'Los pendientes trepadores son modernos, aunque cuesta un poco acostumbrarse a llevarlos.', '2024-07-11 06:00:00'),
(156, 1, 23, 5, 'La pulsera rígida martillada es una joya. Tiene un brillo especial y se ve muy artesanal.', '2024-08-01 12:00:00'),
(157, 1, 24, 4, 'Pulsera de charms muy divertida y veraniega. La plata parece de buena calidad.', '2024-08-04 09:20:00'),
(158, 1, 25, 4, 'La pulsera de ónix para hombre es elegante y moderna. Buen ajuste.', '2024-08-07 17:30:00'),
(159, 1, 26, 5, 'Tobillera muy fina y delicada, las perlitas le dan un toque especial. Perfecta para el verano.', '2024-08-10 14:00:00'),
(160, 3, 27, 5, 'El solitario clásico de oro amarillo es un acierto seguro. El diamante brilla muchísimo.', '2024-09-01 10:00:00'),
(161, 1, 28, 5, 'Un anillo de compromiso de ensueño. La esmeralda con el halo de diamantes es espectacular.', '2024-09-05 07:30:00'),
(162, 1, 29, 4, 'El anillo vintage con morganita es muy romántico. El oro rosa le va perfecto.', '2024-09-08 15:15:00'),
(163, 1, 30, 5, 'Media alianza muy elegante y cómoda de llevar. Los diamantes son pequeños pero brillantes.', '2024-09-11 12:45:00'),
(164, 1, 31, 5, 'Alianzas de platino modernas y con un acabado mate muy bonito. Nos encantaron.', '2024-10-03 08:00:00'),
(165, 3, 32, 4, 'La alianza para mujer con el diamante es fina y elegante. Buen detalle.', '2024-10-06 14:20:00'),
(166, 1, 33, 5, 'Las alianzas texturizadas en oro rosa son originales y preciosas. Muy contentos con la elección.', '2024-10-09 09:50:00'),
(167, 1, 1, 4, 'gsdfgsdfg', '2025-05-18 16:30:45'),
(168, 1, 18, 4, 'fasdfs', '2025-05-18 16:37:46'),
(169, 1, 18, 2, 'fsdf', '2025-05-18 16:38:17'),
(170, 5, 12, 5, 'fasdf', '2025-06-05 10:41:24'),
(171, 5, 12, 5, 'Muy bueno', '2025-06-05 10:41:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_Usuario` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_Usuario`, `nombre`, `email`, `password`, `fecha_registro`) VALUES
(1, 'afd', 'alvaro1@gmail.es', '$2y$10$1f1thpO7CKqPu4TAtmH.IuFdpS2Gzj1zUY2S5DmkLxhsYsFKKB.Du', '2025-05-10 20:07:30'),
(3, 'hola', 'hola@gmail.com', '$2y$10$o8Q9lG.CLh21Cz/LEnGSyOVoV71B1YlD4ZUis2UbCIO0aEBfWAC16', '2025-05-11 18:16:40'),
(4, 'osama bin laden', 'obinlanden@alu.ucam.edu', '$2y$10$qJfwoUf849Zptmt/1dFC8.kBjXYUrRwtX2RMNZqdkjCVvZwlpSATa', '2025-05-21 16:08:08'),
(5, 'admin', 'admin@gmail.com', '$2y$10$4sWitdu/AnbTdYdordm/R.Z.PIYqAZLeLmuWkCve1ZibjDAGzcDXO', '2025-05-30 19:18:37'),
(6, 'amine', 'amine@hola.es', '$2y$10$V4.7caB24Ge55.Uoy9Y7ZOF8qbERou2YbpfI8W73WiS2N3Mth4r2q', '2025-05-30 20:06:19');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_Categoria`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indices de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  ADD PRIMARY KEY (`id_Direccion`),
  ADD KEY `id_Usuario` (`id_Usuario`);

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id_Factura`),
  ADD UNIQUE KEY `id_Pedido` (`id_Pedido`),
  ADD UNIQUE KEY `numero_factura` (`numero_factura`);

--
-- Indices de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  ADD PRIMARY KEY (`id_Mensaje`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_Pedido`),
  ADD KEY `id_Usuario` (`id_Usuario`),
  ADD KEY `id_Direccion` (`id_Direccion`);

--
-- Indices de la tabla `pedido_productos`
--
ALTER TABLE `pedido_productos`
  ADD PRIMARY KEY (`id_Pedido`,`id_Producto`),
  ADD KEY `id_Producto` (`id_Producto`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_Producto`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `id_Categoria` (`id_Categoria`);

--
-- Indices de la tabla `reseñas`
--
ALTER TABLE `reseñas`
  ADD PRIMARY KEY (`id_Reseña`),
  ADD KEY `id_Usuario` (`id_Usuario`),
  ADD KEY `id_Producto` (`id_Producto`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_Usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_Categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  MODIFY `id_Direccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id_Factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  MODIFY `id_Mensaje` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_Pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_Producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `reseñas`
--
ALTER TABLE `reseñas`
  MODIFY `id_Reseña` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_Usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `direcciones`
--
ALTER TABLE `direcciones`
  ADD CONSTRAINT `direcciones_ibfk_1` FOREIGN KEY (`id_Usuario`) REFERENCES `usuarios` (`id_Usuario`);

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`id_Pedido`) REFERENCES `pedidos` (`id_Pedido`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_Usuario`) REFERENCES `usuarios` (`id_Usuario`),
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`id_Direccion`) REFERENCES `direcciones` (`id_Direccion`);

--
-- Filtros para la tabla `pedido_productos`
--
ALTER TABLE `pedido_productos`
  ADD CONSTRAINT `pedido_productos_ibfk_1` FOREIGN KEY (`id_Pedido`) REFERENCES `pedidos` (`id_Pedido`),
  ADD CONSTRAINT `pedido_productos_ibfk_2` FOREIGN KEY (`id_Producto`) REFERENCES `productos` (`id_Producto`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_Categoria`) REFERENCES `categorias` (`id_Categoria`);

--
-- Filtros para la tabla `reseñas`
--
ALTER TABLE `reseñas`
  ADD CONSTRAINT `reseñas_ibfk_1` FOREIGN KEY (`id_Usuario`) REFERENCES `usuarios` (`id_Usuario`),
  ADD CONSTRAINT `reseñas_ibfk_2` FOREIGN KEY (`id_Producto`) REFERENCES `productos` (`id_Producto`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
