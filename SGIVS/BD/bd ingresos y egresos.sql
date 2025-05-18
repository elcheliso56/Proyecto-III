-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-05-2025 a las 07:11:54
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `inventario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apartados`
--

CREATE TABLE `apartados` (
  `id` int(11) NOT NULL,
  `fecha_apartado` datetime NOT NULL,
  `cliente_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apartados_detalles`
--

CREATE TABLE `apartados_detalles` (
  `apartado_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT 'img/categorias/default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `imagen`) VALUES
(1, 'Herramientas Electricas', 'Herramientas que funcionan con electricidad', 'otros/img/categorias/default.png'),
(2, 'Plomeria', 'Articulos para instalaciones de agua', 'otros/img/categorias/default.png'),
(3, 'Iluminacion', 'Productos de iluminacion y accesorios', 'otros/img/categorias/default.png'),
(4, 'Pinturas', 'Pinturas y materiales de acabado', 'otros/img/categorias/default.png'),
(5, 'Cerrajeria', 'Productos de seguridad y cerraduras', 'otros/img/categorias/default.png'),
(6, 'Jardineria', 'Herramientas y productos para jardin', 'otros/img/categorias/default.png'),
(7, 'Construccion', 'Materiales de construccion basicos', 'otros/img/categorias/default.png'),
(8, 'Electricidad', 'Material electrico y componentes', 'otros/img/categorias/default.png'),
(9, 'Ferreteria General', 'Articulos diversos de ferreteria', 'otros/img/categorias/default.png'),
(10, 'Seguridad Industrial', 'Equipos de proteccion y seguridad', 'otros/img/categorias/default.png'),
(11, 'Herramientas Manuales', 'Herramientas que se utilizan manualmente', 'otros/img/categorias/default.png'),
(12, 'Accesorios', 'Accesorios para herramientas y maquinaria', 'otros/img/categorias/default.png'),
(13, 'Materiales de Construcción', 'Materiales para la construcción', 'otros/img/categorias/default.png'),
(14, 'Seguridad Personal', 'Equipos de protección personal', 'otros/img/categorias/default.png'),
(15, 'Ferretería para Jardín', 'Artículos para el cuidado del jardín', 'otros/img/categorias/default.png'),
(16, 'Suministros de Pintura', 'Todo lo necesario para pintar', 'otros/img/categorias/default.png'),
(17, 'Herramientas de Carpintería', 'Herramientas específicas para carpintería', 'otros/img/categorias/default.png'),
(18, 'Equipos de Limpieza', 'Productos y herramientas para limpieza', 'otros/img/categorias/default.png'),
(19, 'Ferretería Automotriz', 'Artículos para el mantenimiento de vehículos', 'otros/img/categorias/default.png'),
(20, 'Iluminación Exterior', 'Productos de iluminación para exteriores', 'otros/img/categorias/default.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `tipo_documento` varchar(10) NOT NULL,
  `numero_documento` varchar(15) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `correo` varchar(25) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `direccion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `tipo_documento`, `numero_documento`, `nombre`, `apellido`, `correo`, `telefono`, `direccion`) VALUES
(1, 'Cédula', '12345678', 'Juan', 'Perez', 'juan@email.com', '04141234567', 'Calle Principal 123'),
(2, 'Cédula', '98765432', 'Maria', 'Gonzalez', 'maria@email.com', '04242345678', 'Avenida Central 456'),
(3, 'Cédula', '45678912', 'Pedro', 'Ramirez', 'pedro@email.com', '04163456789', 'Carrera 7 #89'),
(4, 'Cédula', '78912345', 'Ana', 'Martinez', 'ana@email.com', '04124567890', 'Calle 15 #45-67'),
(5, 'Cédula', '32165498', 'Luis', 'Garcia', 'luis@email.com', '04145678901', 'Avenida 5 #12-34'),
(6, 'Cédula', '65498732', 'Carmen', 'Lopez', 'carmen@email.com', '04166789012', 'Carrera 23 #56'),
(7, 'Cédula', '95175384', 'Jose', 'Torres', 'jose@email.com', '04127890123', 'Calle 8 #90-12'),
(8, 'Cédula', '75395146', 'Rosa', 'Diaz', 'rosa@email.com', '04148901234', 'Avenida 12 #34-56'),
(9, 'Cédula', '85296374', 'Miguel', 'Sanchez', 'miguel@email.com', '04169012345', 'Carrera 45 #78'),
(10, 'Cédula', '14725836', 'Laura', 'Hernandez', 'laura@email.com', '04123456789', 'Calle 67 #89-12'),
(11, 'Cédula', '23456789', 'Carlos', 'Mendoza', 'carlos@email.com', '04141234568', 'Calle 10 #20-30'),
(12, 'Cédula', '34567890', 'Sofia', 'Martinez', 'sofia@email.com', '04242345679', 'Avenida 20 #40-50'),
(13, 'Cédula', '45678901', 'Andres', 'Gonzalez', 'andres@email.com', '04163456780', 'Carrera 30 #60-70'),
(14, 'Cédula', '56789012', 'Lucia', 'Hernandez', 'lucia@email.com', '04124567891', 'Calle 40 #80-90'),
(15, 'Cédula', '67890123', 'Fernando', 'Lopez', 'fernando@email.com', '04145678902', 'Avenida 50 #100-110'),
(16, 'Cédula', '78901234', 'Patricia', 'Diaz', 'patricia@email.com', '04166789013', 'Carrera 60 #120-130'),
(17, 'Cédula', '89012345', 'Javier', 'Torres', 'javier@email.com', '04127890124', 'Calle 70 #140-150'),
(18, 'Cédula', '90123456', 'Claudia', 'Sanchez', 'claudia@email.com', '04148901235', 'Avenida 80 #160-170'),
(19, 'Cédula', '01234567', 'Diego', 'Ramirez', 'diego@email.com', '04169012346', 'Carrera 90 #180-190'),
(20, 'Cédula', '1234567', 'Valeria', 'García', 'valeria@email.com', '04123456789', 'Calle 100 #200-210');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `egresos`
--

CREATE TABLE `egresos` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha` date DEFAULT NULL,
  `origen` enum('manual','consulta','servicio') DEFAULT 'manual',
  `id_referencia` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `egresos`
--

INSERT INTO `egresos` (`id`, `descripcion`, `monto`, `fecha`, `origen`, `id_referencia`) VALUES
(1, 'Pago de luz', 4000.00, '2025-05-16', 'servicio', NULL),
(2, 'pago de luz', 155555.00, '2025-05-16', 'consulta', NULL),
(3, 'pago cpu', 155555.00, '2025-06-20', '', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradas`
--

CREATE TABLE `entradas` (
  `id` int(11) NOT NULL,
  `fecha_entrada` datetime NOT NULL,
  `nota_entrega` varchar(255) DEFAULT NULL,
  `proveedor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradas_detalles`
--

CREATE TABLE `entradas_detalles` (
  `entrada_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `finanzas`
--

CREATE TABLE `finanzas` (
  `id` int(11) NOT NULL,
  `tipo` enum('ingreso','egreso') NOT NULL,
  `concepto` varchar(255) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `forma_pago` varchar(100) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `id_consulta` int(11) DEFAULT NULL,
  `id_servicio` int(11) DEFAULT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `observacion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresos`
--

CREATE TABLE `ingresos` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha` date DEFAULT NULL,
  `origen` enum('manual','consulta','servicio') DEFAULT 'manual',
  `id_referencia` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ingresos`
--

INSERT INTO `ingresos` (`id`, `descripcion`, `monto`, `fecha`, `origen`, `id_referencia`) VALUES
(8, 'paciente con lupus', 20000000.00, '2025-05-14', '', NULL),
(10, 'paciente con lupus', 20000.00, '2025-05-14', 'consulta', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `precio_compra` decimal(30,2) NOT NULL,
  `precio_venta` decimal(30,2) NOT NULL,
  `stock_total` int(25) NOT NULL,
  `stock_minimo` int(10) DEFAULT NULL,
  `marca` varchar(35) NOT NULL,
  `modelo` varchar(35) DEFAULT NULL,
  `tipo_unidad` varchar(20) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `categoria_id` int(11) NOT NULL,
  `ubicacion_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `codigo`, `nombre`, `precio_compra`, `precio_venta`, `stock_total`, `stock_minimo`, `marca`, `modelo`, `tipo_unidad`, `imagen`, `categoria_id`, `ubicacion_id`) VALUES
(21, '1', 'algo', 4.00, 8.00, 6, 2, 'algo', 'si', 'Kilogramo', 'otros/img/productos/default.png', 7, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL,
  `tipo_documento` varchar(10) NOT NULL,
  `numero_documento` varchar(15) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `correo` varchar(25) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `catalogo` varchar(255) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT 'img/proveedores/default.png',
  `catalogo_archivo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `tipo_documento`, `numero_documento`, `nombre`, `direccion`, `correo`, `telefono`, `catalogo`, `imagen`, `catalogo_archivo`) VALUES
(1, 'RIF', 'J-123456789', 'Ferreteria El Constructor', 'Zona Industrial 1', 'constructor@email.com', '02121234567', '', 'otros/img/proveedores/default.png', ''),
(2, 'RIF', 'J-987654321', 'Distribuidora Tools', 'Calle Comercio 234', 'tools@email.com', '02122345678', '', 'otros/img/proveedores/default.png', ''),
(3, 'RIF', 'J-456789123', 'Materiales Express', 'Avenida Principal 567', 'express@email.com', '02123456789', '', 'otros/img/proveedores/default.png', ''),
(4, 'RIF', 'J-789123456', 'Importadora Hardware', 'Calle 45 #12-34', 'hardware@email.com', '02124567890', '', 'otros/img/proveedores/default.png', ''),
(5, 'RIF', 'J-321654987', 'Suministros Pro', 'Carrera 78 #90', 'pro@email.com', '02125678901', '', 'otros/img/proveedores/default.png', ''),
(6, 'Cédula', '65498732', 'Herramientas Plus', 'Avenida 23 #45-67', 'plus@email.com', '02126789012', '', 'otros/img/proveedores/default.png', ''),
(7, 'Cédula', '95175384', 'Distribuidora Industrial', 'Calle 89 #12', 'industrial@email.com', '02127890123', '', 'otros/img/proveedores/default.png', ''),
(8, 'Cédula', '75395146', 'Materiales Unidos', 'Carrera 12 #34-56', 'unidos@email.com', '02128901234', '', 'otros/img/proveedores/default.png', ''),
(9, 'Cédula', '85296374', 'Ferreteria Total', 'Avenida 67 #89', 'total@email.com', '02129012345', '', 'otros/img/proveedores/default.png', ''),
(10, 'Cédula', '14725836', 'Importadora General', 'Calle 34 #56-78', 'general@email.com', '04120123456', '', 'otros/img/proveedores/default.png', ''),
(11, 'RIF', 'J-111111111', 'Ferretería La Nueva', 'Calle 1', 'nueva@email.com', '02121234568', '', 'otros/img/proveedores/default.png', ''),
(12, 'RIF', 'J-222222222', 'Distribuciones Rápidas', 'Calle 2', 'rapidas@email.com', '02122345679', '', 'otros/img/proveedores/default.png', ''),
(13, 'RIF', 'J-333333333', 'Materiales y Suministros', 'Calle 3', 'suministros@email.com', '02123456780', '', 'otros/img/proveedores/default.png', ''),
(14, 'RIF', 'J-444444444', 'Importaciones Globales', 'Calle 4', 'globales@email.com', '02124567891', '', 'otros/img/proveedores/default.png', ''),
(15, 'RIF', 'J-555555555', 'Suministros de Calidad', 'Calle 5', 'calidad@email.com', '02125678902', '', 'otros/img/proveedores/default.png', ''),
(16, 'Cédula', '65432100', 'Herramientas y Más', 'Calle 6', 'mas@email.com', '02126789013', '', 'otros/img/proveedores/default.png', ''),
(17, 'Cédula', '95175300', 'Distribuidora de Ferretería', 'Calle 7', 'distribuidora@email.com', '02127890124', '', 'otros/img/proveedores/default.png', ''),
(18, 'Cédula', '75395100', 'Materiales de Construcción', 'Calle 8', 'materiales@email.com', '02128901235', '', 'otros/img/proveedores/default.png', ''),
(19, 'Cédula', '85296300', 'Ferretería Total', 'Calle 9', 'total@email.com', '02129012346', '', 'otros/img/proveedores/default.png', ''),
(20, 'Cédula', '14725800', 'Importadora de Herramientas', 'Calle 10', 'importadora@email.com', '04120123457', '', 'otros/img/proveedores/default.png', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salidas`
--

CREATE TABLE `salidas` (
  `id` int(11) NOT NULL,
  `fecha_salida` datetime NOT NULL,
  `cliente_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `salidas`
--

INSERT INTO `salidas` (`id`, `fecha_salida`, `cliente_id`) VALUES
(1, '2024-03-11 10:00:00', 1),
(2, '2024-03-12 11:00:00', 2),
(3, '2024-03-13 12:00:00', 3),
(4, '2024-03-14 13:00:00', 4),
(5, '2024-03-15 14:00:00', 5),
(6, '2024-03-16 15:00:00', 6),
(7, '2024-03-17 16:00:00', 7),
(8, '2024-03-18 17:00:00', 8),
(9, '2024-03-19 18:00:00', 9),
(10, '2024-03-20 19:00:00', 10),
(11, '2024-04-11 10:00:00', 11),
(12, '2024-04-12 11:00:00', 12),
(13, '2024-04-13 12:00:00', 13),
(14, '2024-04-14 13:00:00', 14),
(15, '2024-04-15 14:00:00', 15),
(16, '2024-04-16 15:00:00', 16),
(17, '2024-04-17 16:00:00', 17),
(18, '2024-04-18 17:00:00', 18),
(19, '2024-04-19 18:00:00', 19),
(20, '2024-04-20 19:00:00', 20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salidas_detalles`
--

CREATE TABLE `salidas_detalles` (
  `salida_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicaciones`
--

CREATE TABLE `ubicaciones` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `ubicaciones`
--

INSERT INTO `ubicaciones` (`id`, `nombre`, `descripcion`, `imagen`) VALUES
(1, 'Almacen Principal', 'Area principal de almacenamiento', 'otros/img/ubicaciones/default.png'),
(2, 'Estanteria A1', 'Estanteria zona norte', 'otros/img/ubicaciones/default.png'),
(3, 'Estanteria B1', 'Estanteria zona sur', 'otros/img/ubicaciones/default.png'),
(4, 'Bodega Externa', 'Almacen secundario', 'otros/img/ubicaciones/default.png'),
(5, 'Mostrador', 'Area de exhibicion principal', 'otros/img/ubicaciones/default.png'),
(6, 'Vitrina 1', 'Vitrina de productos pequeños', 'otros/img/ubicaciones/default.png'),
(7, 'Deposito 1', 'Deposito de materiales pesados', 'otros/img/ubicaciones/default.png'),
(8, 'Zona Refrigerada', 'Area de productos especiales', 'otros/img/ubicaciones/default.png'),
(9, 'Estanteria C1', 'Estanteria zona este', 'otros/img/ubicaciones/default.png'),
(10, 'Area Seguridad', 'Zona de productos de seguridad', 'otros/img/ubicaciones/default.png'),
(11, 'Almacen Secundario', 'Almacen adicional para productos', 'otros/img/ubicaciones/default.png'),
(12, 'Estanteria D1', 'Estanteria zona oeste', 'otros/img/ubicaciones/default.png'),
(13, 'Estanteria E1', 'Estanteria zona norte', 'otros/img/ubicaciones/default.png'),
(14, 'Bodega Principal', 'Bodega principal de almacenamiento', 'otros/img/ubicaciones/default.png'),
(15, 'Mostrador Secundario', 'Area de exhibicion secundaria', 'otros/img/ubicaciones/default.png'),
(16, 'Vitrina 2', 'Vitrina de productos grandes', 'otros/img/ubicaciones/default.png'),
(17, 'Deposito 2', 'Deposito de productos livianos', 'otros/img/ubicaciones/default.png'),
(18, 'Zona de Herramientas', 'Area dedicada a herramientas', 'otros/img/ubicaciones/default.png'),
(19, 'Estanteria F1', 'Estanteria zona sur', 'otros/img/ubicaciones/default.png'),
(20, 'Area de Mantenimiento', 'Zona de productos de mantenimiento', 'otros/img/ubicaciones/default.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `cedula` varchar(15) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `correo` varchar(25) DEFAULT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `tipo_usuario` varchar(50) NOT NULL,
  `imagen` varchar(255) DEFAULT 'img/usuarios/default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `cedula`, `nombre`, `apellido`, `telefono`, `correo`, `nombre_usuario`, `contraseña`, `tipo_usuario`, `imagen`) VALUES
(1, '', '', '', '', '', 'Administrador', '$2y$10$Fj2EAzVDE82XiQBPiLHZy.iS8VIaRK1jOYQ7RyelpdYPrEVv.OXk2', 'administrador', 'otros/img/usuarios/default.jpg'),
(2, '21312312', 'jesus', 'regalado', '04526598956', 'correo@mail.com', 'admin', '$2y$10$xeLiosMt0wr7k.N3Re1BeuUFhO5jf778aNxSC9A5UZRzOs2sbNOjC', 'administrador', 'otros/img/usuarios/default.png');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `apartados`
--
ALTER TABLE `apartados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Indices de la tabla `apartados_detalles`
--
ALTER TABLE `apartados_detalles`
  ADD KEY `apartado_id` (`apartado_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_documento` (`numero_documento`);

--
-- Indices de la tabla `egresos`
--
ALTER TABLE `egresos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `entradas`
--
ALTER TABLE `entradas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proveedor_id` (`proveedor_id`);

--
-- Indices de la tabla `entradas_detalles`
--
ALTER TABLE `entradas_detalles`
  ADD KEY `entrada_id` (`entrada_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `finanzas`
--
ALTER TABLE `finanzas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `ubicacion_id` (`ubicacion_id`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_documento` (`numero_documento`);

--
-- Indices de la tabla `salidas`
--
ALTER TABLE `salidas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`) USING BTREE;

--
-- Indices de la tabla `salidas_detalles`
--
ALTER TABLE `salidas_detalles`
  ADD KEY `producto_id` (`producto_id`) USING BTREE,
  ADD KEY `salida_id` (`salida_id`) USING BTREE;

--
-- Indices de la tabla `ubicaciones`
--
ALTER TABLE `ubicaciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `apartados`
--
ALTER TABLE `apartados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `egresos`
--
ALTER TABLE `egresos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `entradas`
--
ALTER TABLE `entradas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `finanzas`
--
ALTER TABLE `finanzas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `salidas`
--
ALTER TABLE `salidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `ubicaciones`
--
ALTER TABLE `ubicaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `apartados`
--
ALTER TABLE `apartados`
  ADD CONSTRAINT `apartados_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);

--
-- Filtros para la tabla `apartados_detalles`
--
ALTER TABLE `apartados_detalles`
  ADD CONSTRAINT `apartados_productos_ibfk_1` FOREIGN KEY (`apartado_id`) REFERENCES `apartados` (`id`),
  ADD CONSTRAINT `apartados_productos_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `entradas`
--
ALTER TABLE `entradas`
  ADD CONSTRAINT `entradas_ibfk_1` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`);

--
-- Filtros para la tabla `entradas_detalles`
--
ALTER TABLE `entradas_detalles`
  ADD CONSTRAINT `entradas_productos_ibfk_1` FOREIGN KEY (`entrada_id`) REFERENCES `entradas` (`id`),
  ADD CONSTRAINT `entradas_productos_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_categoria_fk` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
  ADD CONSTRAINT `productos_ubicacion_fk` FOREIGN KEY (`ubicacion_id`) REFERENCES `ubicaciones` (`id`);

--
-- Filtros para la tabla `salidas`
--
ALTER TABLE `salidas`
  ADD CONSTRAINT `salidas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);

--
-- Filtros para la tabla `salidas_detalles`
--
ALTER TABLE `salidas_detalles`
  ADD CONSTRAINT `salidas_detalles_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `salidas_detalles_ibfk_2` FOREIGN KEY (`salida_id`) REFERENCES `salidas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
