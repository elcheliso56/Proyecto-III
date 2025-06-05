-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-05-2025 a las 15:46:05
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
-- Base de datos: `sgivs`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id` int(11) NOT NULL,
  `cita_contacto_id` int(11) DEFAULT NULL,
  `cedula_cliente` varchar(20) DEFAULT NULL,
  `cedula_representante` varchar(20) DEFAULT NULL,
  `nombre_cliente` varchar(100) NOT NULL,
  `apellido_cliente` varchar(100) NOT NULL,
  `telefono_cliente` varchar(15) DEFAULT NULL,
  `motivo_cita` text NOT NULL,
  `doctor_atendera` varchar(100) NOT NULL,
  `fecha_cita` date NOT NULL,
  `hora_cita` time NOT NULL,
  `estado_cita` varchar(50) NOT NULL DEFAULT 'Pendiente',
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id`, `cita_contacto_id`, `cedula_cliente`, `cedula_representante`, `nombre_cliente`, `apellido_cliente`, `telefono_cliente`, `motivo_cita`, `doctor_atendera`, `fecha_cita`, `hora_cita`, `estado_cita`, `fecha_registro`) VALUES
(1, NULL, 'V-12412414', 'V-2849319', 'dan', 'cor', '5854545454', 'asdadasd', 'asdada', '2025-05-31', '11:03:00', 'Confirmada', '2025-05-30 08:03:09'),
(2, NULL, 'V-12412414', '', 'Gabriel', 'Hernandez', '04128287690', 'asdasdasd', 'sadasdasdas', '2025-05-31', '01:42:00', 'Confirmada', '2025-05-30 08:37:14'),
(3, NULL, '12412415', '', 'Gabriel', 'gonzalez', '+584122222222', 'azucar', 'azucar', '2025-07-19', '11:56:00', 'Cancelada', '2025-05-30 08:54:06'),
(4, 21, '29945305', '', 'Gabriel', 'Hernandez', '+584128287690', 'sadasdasdsdasdas', 'azucar', '2025-05-30', '09:11:00', 'Pendiente', '2025-05-30 09:06:16'),
(5, NULL, '29945305', '', 'Gabriel', 'dsadsad', '+584128277680', 'sadsadasdas', 'azucar', '2025-05-31', '09:15:00', 'Pendiente', '2025-05-30 09:09:33'),
(6, NULL, '12412415', '', 'Gabriel', 'dsadsad', '+584123333333', 'azucar', 'azucar', '2025-05-31', '09:20:00', 'Confirmada', '2025-05-30 09:16:50'),
(7, 22, '12412415', '', 'daniel', 'Hernandez', '+584128287690', 'azucar y sal', 'azucar', '2025-05-30', '09:24:00', 'Pendiente', '2025-05-30 09:18:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas_contacto`
--

CREATE TABLE `citas_contacto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `motivo` text NOT NULL,
  `fecha_envio` datetime DEFAULT current_timestamp(),
  `estado` varchar(50) NOT NULL DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `citas_contacto`
--

INSERT INTO `citas_contacto` (`id`, `nombre`, `apellido`, `telefono`, `email`, `motivo`, `fecha_envio`, `estado`) VALUES
(20, 'Gabriel', 'Hernandez', '+584128287690', NULL, 'dientesaaa', '2025-05-29 23:16:11', 'Borrada'),
(21, 'Gabriel', 'Hernandez', '+584128287690', NULL, 'sadasdasdsdasdas', '2025-05-30 09:03:31', 'Borrada'),
(22, 'daniel', 'Hernandez', '+584128287690', NULL, 'azucar y sal', '2025-05-30 09:17:44', 'Registrada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id_empleado` bigint(20) NOT NULL,
  `tipo_rif` enum('V','J') NOT NULL,
  `rif` int(11) NOT NULL,
  `tipo_documento` enum('V','E') NOT NULL,
  `cedula` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(30) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `genero` enum('M','F','O') NOT NULL,
  `email` varchar(100) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `fecha_contratacion` date NOT NULL,
  `cargo` varchar(20) NOT NULL,
  `salario` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id_empleado`, `tipo_rif`, `rif`, `tipo_documento`, `cedula`, `nombre`, `apellido`, `fecha_nacimiento`, `genero`, `email`, `direccion`, `telefono`, `fecha_contratacion`, `cargo`, `salario`) VALUES
(2, 'V', 259613740, 'V', 25961374, 'dannibelk', 'amaro', '1995-09-11', 'F', 'dannibelk@gmail.com', 'los valles', '04124309712', '2020-05-12', 'Doctor', 5500);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradas_equipos`
--

CREATE TABLE `entradas_equipos` (
  `id` int(10) NOT NULL,
  `fecha_entrada` datetime NOT NULL,
  `nota_entrega` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `entradas_equipos`
--

INSERT INTO `entradas_equipos` (`id`, `fecha_entrada`, `nota_entrega`) VALUES
(31, '2025-05-29 22:32:08', ''),
(32, '2025-05-29 22:46:40', 'otros/img/entradas/68391c10b7184_RESUMEN CRITICO FORO SO EQUIPO VITAL SONRISA.pdf'),
(33, '2025-05-29 23:35:58', ''),
(34, '2025-05-30 01:06:30', 'otros/img/entradas/68393cd6434f4_TRAMO 3 BOCETO.pptx'),
(35, '2025-05-30 08:10:58', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradas_equipos_detalles`
--

CREATE TABLE `entradas_equipos_detalles` (
  `id_entrada_equipo` int(11) NOT NULL,
  `id_equipo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `entradas_equipos_detalles`
--

INSERT INTO `entradas_equipos_detalles` (`id_entrada_equipo`, `id_equipo`, `cantidad`, `precio`) VALUES
(31, 2, 1, 100.00),
(32, 2, 10, 100.00),
(33, 2, 10, 100.00),
(34, 17, 1, 1000.32),
(35, 2, 1, 100.00),
(35, 4, 1, 200.00),
(35, 24, 1, 1.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradas_insumos`
--

CREATE TABLE `entradas_insumos` (
  `id` int(10) NOT NULL,
  `fecha_entrada` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `entradas_insumos`
--

INSERT INTO `entradas_insumos` (`id`, `fecha_entrada`) VALUES
(34, '2025-05-29 22:26:09'),
(35, '2025-05-29 22:33:11'),
(36, '2025-05-29 22:45:25'),
(37, '2025-05-29 22:50:34'),
(38, '2025-05-29 23:35:24'),
(39, '2025-05-30 01:08:29'),
(40, '2025-05-30 08:11:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipos`
--

CREATE TABLE `equipos` (
  `id` int(10) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `modelo` varchar(50) DEFAULT NULL,
  `cantidad` int(10) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `equipos`
--

INSERT INTO `equipos` (`id`, `codigo`, `nombre`, `marca`, `modelo`, `cantidad`, `precio`, `imagen`) VALUES
(2, 'hola', 'hola', 'hola', 'hola', 232, 100.00, 'otros/img/equipos/default.png'),
(4, 'xxxx', 'xxx', 'xxxx', 'xxx', 108, 200.00, 'otros/img/equipos/default.png'),
(6, 'ytuyt', 'aaaaaaaaaaaaaa', 'ytuyt', 'uytutyu', 695, 300.00, 'otros/img/equipos/default.png'),
(7, 'zxz<', 'xzxxzx', 'z<x<z', 'x<zxz<', 25, 100.00, 'otros/img/equipos/default.png'),
(9, 'sgrr', 'zxczxc', 'xzcxzc', 'xzcxzc', 34, 1.00, 'otros/img/equipos/default.png'),
(10, 'hhhh', 'hhhh', '', '', 10, 1.00, 'otros/img/equipos/default.png'),
(13, 'gola', 'sdasdasd', 'sadsad', 'asdasd', 2, 12.00, 'otros/img/equipos/default.png'),
(17, 'xxx', 'bbbb', 'xxxx', 'xxxsad', 5, 1000.32, 'otros/img/equipos/68393cc8385af_tramo A BOCETO.jpg'),
(23, 'asdasd', 'sadsad', 'sadsad', 'sadsad', 1212, 1212.00, 'otros/img/equipos/default.png'),
(24, 'sadasdasd', 'asdasd', 'asdasd', 'asdasd', 23, 1.00, 'otros/img/equipos/default.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `insumos`
--

CREATE TABLE `insumos` (
  `id` int(10) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `cantidad` int(10) NOT NULL,
  `cantidad_minima` int(10) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(150) NOT NULL,
  `id_presentacion` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `insumos`
--

INSERT INTO `insumos` (`id`, `codigo`, `nombre`, `marca`, `cantidad`, `cantidad_minima`, `precio`, `imagen`, `id_presentacion`) VALUES
(2, '1', 'Esteeeee', 'hola', 1197, 0, 10.00, 'otros/img/insumos/default.png', 2),
(7, 'xxx', 'dwasd', 'asdasd', 74, 1, 15.00, 'otros/img/insumos/68347732e55d7_default.png', 2),
(11, 'asdasd', 'sadas', 'dasdsad', 72, 1, 20.00, 'otros/img/insumos/6834772d3f627_default.png', 2),
(12, 'ghfghgf', 'hgfhgf', 'hgfhgfh', 24, 1, 10.00, 'otros/img/insumos/default.png', 2),
(15, 'XCVXCVCXV', 'CXVXC', 'VXCVX', 17, 1, 1.00, 'otros/img/insumos/default.png', 3),
(16, 'ttttttttt', 'tttttttt', 'tttttttttttt', 111, 1, 11.00, 'otros/img/insumos/683511c5c21ad_default.png', 3),
(17, 'ZXC', 'ZXCXZ', 'CXZCXZC', 10, 1, 3.00, 'otros/img/insumos/default.png', 2),
(18, 'dgfdgdfg', 'dfgfdgdf', 'dfgdfgdf', 11, 5, 1.00, 'otros/img/insumos/6837b9d20ae65_6836581a2374d_683657fa6bd2f_default.png', 2),
(23, 'sadsadsad', 'sadasdasd', '1111', 1111, 11, 1.00, 'otros/img/insumos/default.png', 3),
(24, 'jjjjjjjjjjj', 'sdsadsa', 'dsadasdd', 111, 1, 1.00, 'otros/img/insumos/6837d1b83201f_logo.png', 3),
(25, 'jlkjkljk', 'asdasd', 'asdasdasd', 322, 1, 1.00, 'otros/img/insumos/6837d2ab9d514_err2.jpeg', 3),
(26, 'asdas', 'dsadasd', 'sadasdsad', 333, 222, 111.00, 'otros/img/insumos/default.png', 3),
(27, '34234234', ' sfdsdfsdf', 'dfdsfds', 22, 2, 111.00, 'otros/img/insumos/6837d38da9557_logo.png', 3),
(28, 'ghjhgj', 'ghjghjgh', 'jghjghjghj', 6, 1, 3.00, 'otros/img/insumos/default.png', 2),
(29, 'ooo', 'dfgf', 'dgfdgdfg', 544, 4, 4.00, 'otros/img/insumos/6837d40d2c561_logo.png', 3),
(30, 'ghfghfg', 'hgfhgfh', 'gfhfghfgh', 66, 6, 6.00, 'otros/img/insumos/default.png', 2),
(31, 'xxxx', 'aaa', 'dasdsad', 12, 1, 300.00, 'otros/img/insumos/default.png', 2),
(33, 'jjj', 'cbdfg', 'fdgfdgfd', 445, 4, 12.26, 'otros/img/insumos/6838e66c80c40_6838e6629c23f_default.png', 2),
(36, '01', 'Harina', 'PAN', 40, 1, 2.00, 'otros/img/insumos/683928403206c_683917183a130_683917109a1e4_Captura2.PNG', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `insumos_entradas`
--

CREATE TABLE `insumos_entradas` (
  `id_entradas_insumos` int(10) NOT NULL,
  `id_insumos` int(10) NOT NULL,
  `cantidad` int(10) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `insumos_entradas`
--

INSERT INTO `insumos_entradas` (`id_entradas_insumos`, `id_insumos`, `cantidad`, `precio`) VALUES
(34, 36, 10, 2.00),
(35, 2, 1, 100.00),
(35, 7, 1, 15.00),
(36, 2, 1, 100.00),
(36, 7, 1, 15.00),
(37, 2, 1, 100.00),
(38, 2, 12, 10.00),
(39, 36, 10, 2.00),
(40, 2, 1, 10.00),
(40, 7, 1, 15.00),
(40, 11, 1, 20.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE `pacientes` (
  `id_paciente` int(20) NOT NULL,
  `tipo_documento` enum('V','E','NC') NOT NULL,
  `cedula` int(20) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `genero` enum('M','F','O') NOT NULL,
  `alergias` varchar(20) DEFAULT NULL,
  `antecedentes` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `telefono` varchar(15) NOT NULL,
  `fecha_registro` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pacientes`
--

INSERT INTO `pacientes` (`id_paciente`, `tipo_documento`, `cedula`, `nombre`, `apellido`, `fecha_nacimiento`, `genero`, `alergias`, `antecedentes`, `email`, `direccion`, `telefono`, `fecha_registro`) VALUES
(0, 'V', 28591973, 'Jesus', 'Regalado', '2002-06-18', 'M', 'Nada', 'ninguno', 'ejejmplo@gmail.com', 'calle 911 ', '04120000000', '2025-05-20'),
(11, 'V', 25961374, 'dannibelk', 'amaro', '1995-09-11', 'F', 'no', 'no', 'dannibelk@gmail.com', 'los vales', '04124309712', '2025-05-28'),
(12, 'NC', 202012121, 'DANNA', 'AMARO', '2020-12-12', 'F', 'NO', 'NO', 'DANNIBELK@GMAIL.COM', 'LOS VALLES', '04124309712', '2025-05-28'),
(13, 'E', 25964234, 'luigi', 'log', '1955-06-25', 'M', 'no', 'no', 'luigi@gmail.com', 'los roques', '02122589632', '2025-05-29'),
(14, 'V', 29945305, 'Gabriel', 'Hernandez', '2004-01-22', 'M', 'no', 'mo', 'gabo@gamil.com', 'el tostao calle 1', '04128287690', '2025-05-30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presentaciones`
--

CREATE TABLE `presentaciones` (
  `id` int(10) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `descripcion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `presentaciones`
--

INSERT INTO `presentaciones` (`id`, `nombre`, `descripcion`) VALUES
(2, 'aaa', 'aaa'),
(3, 'BBBB', 'ASDASDSA'),
(6, 'Kilogramo', 'Producto x kilo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int(10) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=activo, 0=eliminado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `estado`) VALUES
(172, 'asdas', 'asdasd', 1.00, 1),
(173, 'aaaa', 'aaaa', 2.00, 0),
(174, 'jljjlkljk', 'gfh', 121.00, 0),
(175, 'bbbb', 'bbbbb', 12.00, 0),
(176, 'asdsa', 'dsadsa', 12.00, 1),
(177, 'gfhfvg', 'gfhfg', 12.00, 0),
(178, 'sadsadsadsadd', '11', 1.00, 1),
(179, 'sdaasd', 'sadasdsad', 11.00, 0),
(180, 'SADASD', 'SADASD', 2121.00, 0),
(181, 'dsad', 'sadsadas', 222.00, 1),
(182, 'jkkjkjkj', 'jhkhjkh', 12.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios_equipos`
--

CREATE TABLE `servicios_equipos` (
  `id_servicio` int(10) NOT NULL,
  `id_equipo` int(10) NOT NULL,
  `cantidad` int(10) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=activo, 0=eliminado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios_equipos`
--

INSERT INTO `servicios_equipos` (`id_servicio`, `id_equipo`, `cantidad`, `precio`, `estado`) VALUES
(174, 2, 1, 100.00, 1),
(175, 2, 12, 100.00, 1),
(175, 4, 15, 200.00, 1),
(179, 4, 1, 200.00, 1),
(180, 2, 1, 100.00, 1),
(180, 4, 1, 200.00, 1),
(181, 2, 1, 100.00, 1),
(181, 4, 1, 200.00, 1),
(182, 2, 1, 100.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios_insumos`
--

CREATE TABLE `servicios_insumos` (
  `id_servicio` int(10) NOT NULL,
  `id_insumo` int(10) NOT NULL,
  `cantidad` int(10) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=activo, 0=eliminado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios_insumos`
--

INSERT INTO `servicios_insumos` (`id_servicio`, `id_insumo`, `cantidad`, `precio`, `estado`) VALUES
(172, 2, 1, 100.00, 1),
(173, 2, 1, 100.00, 1),
(174, 2, 1, 100.00, 1),
(175, 2, 5, 100.00, 1),
(176, 2, 1, 100.00, 1),
(177, 2, 1, 100.00, 1),
(178, 2, 1, 10.00, 1),
(182, 2, 1, 10.00, 1),
(182, 7, 1, 15.00, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cita_contacto_id` (`cita_contacto_id`);

--
-- Indices de la tabla `citas_contacto`
--
ALTER TABLE `citas_contacto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id_empleado`);

--
-- Indices de la tabla `entradas_equipos`
--
ALTER TABLE `entradas_equipos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `entradas_equipos_detalles`
--
ALTER TABLE `entradas_equipos_detalles`
  ADD KEY `id_entrada_equipo` (`id_entrada_equipo`),
  ADD KEY `id_equipo` (`id_equipo`);

--
-- Indices de la tabla `entradas_insumos`
--
ALTER TABLE `entradas_insumos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `insumos`
--
ALTER TABLE `insumos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_presentacion` (`id_presentacion`);

--
-- Indices de la tabla `insumos_entradas`
--
ALTER TABLE `insumos_entradas`
  ADD KEY `insumos_entradas_ibfk_1` (`id_entradas_insumos`),
  ADD KEY `insumos_entradas_ibfk_2` (`id_insumos`);

--
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id_paciente`);

--
-- Indices de la tabla `presentaciones`
--
ALTER TABLE `presentaciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `servicios_equipos`
--
ALTER TABLE `servicios_equipos`
  ADD PRIMARY KEY (`id_servicio`,`id_equipo`),
  ADD KEY `id_equipo` (`id_equipo`);

--
-- Indices de la tabla `servicios_insumos`
--
ALTER TABLE `servicios_insumos`
  ADD PRIMARY KEY (`id_servicio`,`id_insumo`),
  ADD KEY `id_insumo` (`id_insumo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `citas_contacto`
--
ALTER TABLE `citas_contacto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id_empleado` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `entradas_equipos`
--
ALTER TABLE `entradas_equipos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `entradas_insumos`
--
ALTER TABLE `entradas_insumos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `equipos`
--
ALTER TABLE `equipos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `insumos`
--
ALTER TABLE `insumos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id_paciente` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `presentaciones`
--
ALTER TABLE `presentaciones`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`cita_contacto_id`) REFERENCES `citas_contacto` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `entradas_equipos_detalles`
--
ALTER TABLE `entradas_equipos_detalles`
  ADD CONSTRAINT `entradas_equipos_ibfk_1` FOREIGN KEY (`id_entrada_equipo`) REFERENCES `entradas_equipos` (`id`),
  ADD CONSTRAINT `entradas_equipos_ibfk_2` FOREIGN KEY (`id_equipo`) REFERENCES `equipos` (`id`);

--
-- Filtros para la tabla `insumos`
--
ALTER TABLE `insumos`
  ADD CONSTRAINT `insumos_presentacion_fk` FOREIGN KEY (`id_presentacion`) REFERENCES `presentaciones` (`id`);

--
-- Filtros para la tabla `insumos_entradas`
--
ALTER TABLE `insumos_entradas`
  ADD CONSTRAINT `insumos_entradas_ibfk_1` FOREIGN KEY (`id_entradas_insumos`) REFERENCES `entradas_insumos` (`id`),
  ADD CONSTRAINT `insumos_entradas_ibfk_2` FOREIGN KEY (`id_insumos`) REFERENCES `insumos` (`id`);

--
-- Filtros para la tabla `servicios_equipos`
--
ALTER TABLE `servicios_equipos`
  ADD CONSTRAINT `servicios_equipos_ibfk_1` FOREIGN KEY (`id_servicio`) REFERENCES `servicios` (`id`),
  ADD CONSTRAINT `servicios_equipos_ibfk_2` FOREIGN KEY (`id_equipo`) REFERENCES `equipos` (`id`);

--
-- Filtros para la tabla `servicios_insumos`
--
ALTER TABLE `servicios_insumos`
  ADD CONSTRAINT `servicios_insumos_ibfk_1` FOREIGN KEY (`id_servicio`) REFERENCES `servicios` (`id`),
  ADD CONSTRAINT `servicios_insumos_ibfk_2` FOREIGN KEY (`id_insumo`) REFERENCES `insumos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
