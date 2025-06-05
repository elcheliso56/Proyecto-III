-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-05-2025 a las 09:14:38
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
-- Base de datos: `sgivs`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas_contacto`
--

CREATE TABLE `citas_contacto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `motivo` text NOT NULL,
  `fecha_envio` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas`
--

CREATE TABLE `cuentas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo` varchar(50) NOT NULL COMMENT 'Ej: Efectivo, Bancaria, Tarjeta',
  `saldo_actual` decimal(12,2) DEFAULT 0.00,
  `numero_cuenta` varchar(20) NOT NULL,
  `entidad_bancaria` varchar(80) NOT NULL,
  `moneda` varchar(3) DEFAULT 'VES',
  `activa` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cuentas`
--

INSERT INTO `cuentas` (`id`, `nombre`, `tipo`, `saldo_actual`, `numero_cuenta`, `entidad_bancaria`, `moneda`, `activa`, `fecha_creacion`) VALUES
(4, 'Principal', 'bancaria', 500.00, '01254656856', 'BBVA', 'Bs', 1, '2025-05-30 07:10:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas_por_cobrar`
--

CREATE TABLE `cuentas_por_cobrar` (
  `id` int(11) NOT NULL,
  `paciente_id` int(20) NOT NULL,
  `cuenta_id` int(11) NOT NULL,
  `fecha_emision` date NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `monto_total` decimal(10,2) NOT NULL,
  `monto_pendiente` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','parcial','pagado','vencido') NOT NULL DEFAULT 'pendiente',
  `descripcion` text DEFAULT NULL,
  `referencia` varchar(50) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `numero_cuotas` int(11) NOT NULL DEFAULT 1,
  `monto_cuota` decimal(10,2) NOT NULL,
  `frecuencia_pago` enum('semanal','quincenal','mensual') NOT NULL DEFAULT 'mensual'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cuentas_por_cobrar`
--

INSERT INTO `cuentas_por_cobrar` (`id`, `paciente_id`, `cuenta_id`, `fecha_emision`, `fecha_vencimiento`, `monto_total`, `monto_pendiente`, `estado`, `descripcion`, `referencia`, `fecha_creacion`, `numero_cuotas`, `monto_cuota`, `frecuencia_pago`) VALUES
(7, 2, 4, '2025-05-29', '2025-12-02', 4230.00, 4230.00, 'pendiente', 'pago moto', '0132', '2025-05-30 07:13:43', 4, 1057.50, 'quincenal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas_por_pagar`
--

CREATE TABLE `cuentas_por_pagar` (
  `id` int(11) NOT NULL,
  `proveedor_id` int(11) NOT NULL,
  `fecha_emision` date NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `monto_total` decimal(10,2) NOT NULL,
  `monto_pendiente` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','parcial','pagado','vencido') NOT NULL DEFAULT 'pendiente',
  `descripcion` text DEFAULT NULL,
  `referencia` varchar(50) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuotas_pago`
--

CREATE TABLE `cuotas_pago` (
  `id` int(11) NOT NULL,
  `cuenta_por_cobrar_id` int(11) NOT NULL,
  `numero_cuota` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `estado` enum('pendiente','pagado','vencido') NOT NULL DEFAULT 'pendiente',
  `fecha_pago` date DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cuotas_pago`
--

INSERT INTO `cuotas_pago` (`id`, `cuenta_por_cobrar_id`, `numero_cuota`, `monto`, `fecha_vencimiento`, `estado`, `fecha_pago`, `fecha_creacion`) VALUES
(6, 7, 1, 1057.50, '2025-06-13', 'pendiente', NULL, '2025-05-30 07:13:43'),
(7, 7, 2, 1057.50, '2025-06-28', 'pendiente', NULL, '2025-05-30 07:13:43'),
(8, 7, 3, 1057.50, '2025-07-13', 'pendiente', NULL, '2025-05-30 07:13:43'),
(9, 7, 4, 1057.50, '2025-07-28', 'pendiente', NULL, '2025-05-30 07:13:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `egresos`
--

CREATE TABLE `egresos` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha` date DEFAULT NULL,
  `origen` enum('servicio','proveedor','otro') DEFAULT 'servicio',
  `cuenta_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `egresos`
--

INSERT INTO `egresos` (`id`, `descripcion`, `monto`, `fecha`, `origen`, `cuenta_id`) VALUES
(0, 'egresos', 500.00, '2025-05-29', 'proveedor', 4);

--
-- Disparadores `egresos`
--
DELIMITER $$
CREATE TRIGGER `after_egreso_insert` AFTER INSERT ON `egresos` FOR EACH ROW BEGIN
    UPDATE cuentas 
    SET saldo_actual = saldo_actual - NEW.monto
    WHERE id = NEW.cuenta_id;
END
$$
DELIMITER ;

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
(33, '2025-05-29 23:35:58', '');

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
(33, 2, 10, 100.00);

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
(38, '2025-05-29 23:35:24');

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
(2, 'hola', 'hola', 'hola', 'hola', 231, 100.00, 'otros/img/equipos/default.png'),
(4, 'xxxx', 'xxx', 'xxxx', 'xxx', 107, 200.00, 'otros/img/equipos/default.png'),
(6, 'ytuyt', 'aaaaaaaaaaaaaa', 'ytuyt', 'uytutyu', 695, 300.00, 'otros/img/equipos/default.png'),
(7, 'zxz<', 'xzxxzx', 'z<x<z', 'x<zxz<', 25, 100.00, 'otros/img/equipos/default.png'),
(9, 'sgrr', 'zxczxc', 'xzcxzc', 'xzcxzc', 34, 1.00, 'otros/img/equipos/default.png'),
(10, 'hhhh', 'hhhh', '', '', 10, 1.00, 'otros/img/equipos/default.png'),
(13, 'gola', 'sdasdasd', 'sadsad', 'asdasd', 2, 12.00, 'otros/img/equipos/default.png'),
(17, 'xxx', 'bbbb', 'xxxx', 'xxxsad', 4, 1000.32, 'otros/img/equipos/6839283b6dcc3_68391bf5886d0_6838e64d1e16d_default.png');

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
  `cuenta_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ingresos`
--

INSERT INTO `ingresos` (`id`, `descripcion`, `monto`, `fecha`, `origen`, `cuenta_id`) VALUES
(0, 'pagos', 1000.00, '2025-05-29', 'consulta', 4);

--
-- Disparadores `ingresos`
--
DELIMITER $$
CREATE TRIGGER `after_ingreso_insert` AFTER INSERT ON `ingresos` FOR EACH ROW BEGIN
    UPDATE cuentas 
    SET saldo_actual = saldo_actual + NEW.monto
    WHERE id = NEW.cuenta_id;
END
$$
DELIMITER ;

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
(2, '1', 'Esteeeee', 'hola', 1196, 0, 10.00, 'otros/img/insumos/default.png', 2),
(7, 'xxx', 'dwasd', 'asdasd', 73, 1, 15.00, 'otros/img/insumos/68347732e55d7_default.png', 2),
(11, 'asdasd', 'sadas', 'dasdsad', 71, 1, 20.00, 'otros/img/insumos/6834772d3f627_default.png', 2),
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
(36, '01', 'Harina', 'PAN', 30, 1, 2.00, 'otros/img/insumos/683928403206c_683917183a130_683917109a1e4_Captura2.PNG', 6);

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
(38, 2, 12, 10.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE `pacientes` (
  `id_paciente` int(20) NOT NULL,
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

INSERT INTO `pacientes` (`id_paciente`, `cedula`, `nombre`, `apellido`, `fecha_nacimiento`, `genero`, `alergias`, `antecedentes`, `email`, `direccion`, `telefono`, `fecha_registro`) VALUES
(2, 25258456, 'javier', 'torres', '1985-09-17', 'M', 'nada', 'nada', 'ejemplo@hotmai.com', 'Lara', '01263666660', '2025-05-30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_cuentas_por_cobrar`
--

CREATE TABLE `pagos_cuentas_por_cobrar` (
  `id` int(11) NOT NULL,
  `cuenta_por_cobrar_id` int(11) NOT NULL,
  `fecha_pago` date NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `metodo_pago` varchar(50) NOT NULL,
  `referencia_pago` varchar(50) DEFAULT NULL,
  `cuenta_id` int(11) NOT NULL,
  `observaciones` text DEFAULT NULL,
  `cuota_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `pagos_cuentas_por_cobrar`
--
DELIMITER $$
CREATE TRIGGER `after_pago_cobrar_insert` AFTER INSERT ON `pagos_cuentas_por_cobrar` FOR EACH ROW BEGIN
    -- Actualizar la cuenta por cobrar
    UPDATE cuentas_por_cobrar 
    SET monto_pendiente = monto_pendiente - NEW.monto,
        estado = CASE 
            WHEN monto_pendiente - NEW.monto <= 0 THEN 'pagado'
            WHEN monto_pendiente - NEW.monto < monto_total THEN 'parcial'
            ELSE estado
        END
    WHERE id = NEW.cuenta_por_cobrar_id;
    
    -- Registrar el ingreso automáticamente
    INSERT INTO ingresos (
        descripcion,
        monto,
        fecha,
        origen,
        cuenta_id
    ) VALUES (
        CONCAT('Pago de cuenta por cobrar #', NEW.cuenta_por_cobrar_id),
        NEW.monto,
        NEW.fecha_pago,
        'servicio',
        NEW.cuenta_id
    );
END
$$
DELIMITER ;

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
(179, 'sdaasd', 'sadasdsad', 11.00, 0);

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
(179, 4, 1, 200.00, 1);

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
(178, 2, 1, 10.00, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `citas_contacto`
--
ALTER TABLE `citas_contacto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cuentas_por_cobrar`
--
ALTER TABLE `cuentas_por_cobrar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paciente_id` (`paciente_id`),
  ADD KEY `fk_cxc_cuenta` (`cuenta_id`);

--
-- Indices de la tabla `cuentas_por_pagar`
--
ALTER TABLE `cuentas_por_pagar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proveedor_id` (`proveedor_id`);

--
-- Indices de la tabla `cuotas_pago`
--
ALTER TABLE `cuotas_pago`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cuenta_por_cobrar_id` (`cuenta_por_cobrar_id`);

--
-- Indices de la tabla `egresos`
--
ALTER TABLE `egresos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_egreso_cuenta` (`cuenta_id`);

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
-- Indices de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ingreso_cuenta` (`cuenta_id`);

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
-- Indices de la tabla `pagos_cuentas_por_cobrar`
--
ALTER TABLE `pagos_cuentas_por_cobrar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pago_cxc` (`cuenta_por_cobrar_id`),
  ADD KEY `fk_pago_cuenta` (`cuenta_id`),
  ADD KEY `fk_pago_cuota` (`cuota_id`);

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
-- AUTO_INCREMENT de la tabla `citas_contacto`
--
ALTER TABLE `citas_contacto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `cuentas_por_cobrar`
--
ALTER TABLE `cuentas_por_cobrar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `cuotas_pago`
--
ALTER TABLE `cuotas_pago`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `entradas_equipos`
--
ALTER TABLE `entradas_equipos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `entradas_insumos`
--
ALTER TABLE `entradas_insumos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `equipos`
--
ALTER TABLE `equipos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `insumos`
--
ALTER TABLE `insumos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id_paciente` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pagos_cuentas_por_cobrar`
--
ALTER TABLE `pagos_cuentas_por_cobrar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `presentaciones`
--
ALTER TABLE `presentaciones`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=180;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cuentas_por_cobrar`
--
ALTER TABLE `cuentas_por_cobrar`
  ADD CONSTRAINT `fk_cxc_cuenta` FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cxc_paciente` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id_paciente`) ON UPDATE CASCADE;

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
-- Filtros para la tabla `pagos_cuentas_por_cobrar`
--
ALTER TABLE `pagos_cuentas_por_cobrar`
  ADD CONSTRAINT `fk_pago_cuenta` FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pago_cuota` FOREIGN KEY (`cuota_id`) REFERENCES `cuotas_pago` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pago_cxc` FOREIGN KEY (`cuenta_por_cobrar_id`) REFERENCES `cuentas_por_cobrar` (`id`) ON UPDATE CASCADE;

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
