-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-05-2025 a las 03:45:40
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
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id_cita` int(11) UNSIGNED NOT NULL,
  `nombre_paciente` varchar(255) NOT NULL,
  `numero_contacto` varchar(20) NOT NULL,
  `id_medico` int(11) NOT NULL,
  `motivo_cita` text NOT NULL,
  `fecha_cita` date NOT NULL,
  `hora_cita` time NOT NULL,
  `estado_cita` enum('pendiente','confirmada','cancelada') NOT NULL DEFAULT 'pendiente',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `observaciones` text DEFAULT NULL,
  `id_solicitud` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id_cita`, `nombre_paciente`, `numero_contacto`, `id_medico`, `motivo_cita`, `fecha_cita`, `hora_cita`, `estado_cita`, `fecha_registro`, `observaciones`, `id_solicitud`) VALUES
(1, 'PEDRO', '0451851848', 28, 'Ortodoncia', '2025-05-31', '12:17:00', 'pendiente', '2025-05-27 02:55:20', 'dolor de muela', NULL);

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
-- Estructura de tabla para la tabla `consultas`
--

CREATE TABLE `consultas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `Apellido` varchar(50) NOT NULL,
  `tratamiento` varchar(200) NOT NULL,
  `telefono` text NOT NULL,
  `fechaconsulta` date NOT NULL,
  `cedula` int(20) NOT NULL,
  `doctor` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `consultas`
--

INSERT INTO `consultas` (`id`, `nombre`, `Apellido`, `tratamiento`, `telefono`, `fechaconsulta`, `cedula`, `doctor`) VALUES
(17, 'ppee', 'peres', '- PENAPICAL', '04253625412', '2025-05-27', 25403645, 'sofia perez'),
(18, 'carla', 'teta', '- TOPE 1', '04263521452', '2025-05-30', 24532102, 'sofia perez'),
(19, 'carla', 'teta', '- TUBOS', '04263521452', '2025-05-27', 24532102, 'sofia perez');

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
(1, 'Dueño', 'Efectivo', 20.01, '', '', 'USD', 1, '2025-05-23 01:15:34'),
(2, '100BANCO', 'bancaria', 301.00, '', '', 'USD', 1, '2025-05-25 17:32:00'),
(3, 'Mercantil', 'bancaria', 2311.00, '', '', 'EUR', 1, '2025-05-25 17:32:38'),
(4, 'BANESCO', 'bancaria', 21.00, '', '', 'USD', 1, '2025-05-25 17:59:10'),
(5, 'principal', 'bancaria', 0.00, '4000340000000504', 'BBVA', 'USD', 1, '2025-05-25 19:23:16'),
(7, 'Caja Principal', 'Efectivo', 5868.00, '', 'Clinica', 'USD', 1, '2025-05-01 04:00:00'),
(8, 'Cuenta Corriente', 'bancaria', 200.00, '1234567890', 'Banco Dental', 'USD', 1, '2025-05-01 04:00:00'),
(9, 'Tarjeta de Crédito', 'tarjeta', 19000.00, '9876543210', 'Visa', 'USD', 1, '2025-05-01 04:00:00'),
(10, 'Fondo de Emergencia', 'bancaria', 10000.00, '4567890123', 'Banco Dental', 'USD', 1, '2025-05-01 04:00:00');

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
(4, 'prueba de integridad ', 2.10, '2025-05-24', 'proveedor', 4),
(5, 'prueba de integridad ', 3.00, '2025-05-24', 'otro', 1),
(6, 'prueba de integridad ', 4.00, '2025-05-24', 'otro', 1),
(7, 'Compra material dental', 350.00, '2025-05-03', 'proveedor', 8),
(8, 'Pago alquiler local', 2000.00, '2025-05-05', 'otro', 8),
(9, 'Salario Dra. Pérez', 1800.00, '2025-05-05', 'otro', 8),
(10, 'Salario asistente', 1200.00, '2025-05-05', 'otro', 8),
(11, 'Compra anestésicos', 150.00, '2025-05-10', 'proveedor', 8),
(12, 'Pago servicios (luz, agua)', 300.00, '2025-05-12', 'otro', 8),
(13, 'Mantenimiento equipo rayos X', 450.00, '2025-05-15', 'proveedor', 8),
(14, 'Seguro médico equipo', 200.00, '2025-05-18', 'otro', 8),
(15, 'Material de oficina', 80.00, '2025-05-20', 'proveedor', 7),
(16, 'Publicidad en redes', 150.00, '2025-05-25', 'otro', 7),
(17, 'Compra instrumental', 420.00, '2025-04-02', 'proveedor', 8),
(18, 'Pago alquiler local', 2000.00, '2025-04-05', 'otro', 8),
(19, 'Salarios personal', 3200.00, '2025-04-05', 'otro', 8),
(20, 'Compra resinas dentales', 180.00, '2025-04-08', 'proveedor', 8),
(21, 'Pago servicios', 280.00, '2025-04-10', 'otro', 8),
(22, 'Suscripción software dental', 100.00, '2025-04-15', 'otro', 8),
(23, 'Uniforme personal', 120.00, '2025-04-18', 'proveedor', 7),
(24, 'Capacitación equipo', 300.00, '2025-04-22', 'otro', 8),
(25, 'Mantenimiento preventivo', 350.00, '2025-04-25', 'proveedor', 8),
(26, 'Publicidad local', 200.00, '2025-04-28', 'otro', 7),
(27, 'Compra material quirúrgico', 380.00, '2025-03-03', 'proveedor', 8),
(28, 'Pago alquiler local', 2000.00, '2025-03-05', 'otro', 8),
(29, 'Salarios personal', 3200.00, '2025-03-05', 'otro', 8),
(30, 'Compra consumibles', 220.00, '2025-03-08', 'proveedor', 8),
(31, 'Pago servicios', 310.00, '2025-03-10', 'otro', 8),
(32, 'Renovación certificaciones', 250.00, '2025-03-15', 'otro', 8),
(33, 'Equipo de protección', 180.00, '2025-03-18', 'proveedor', 7),
(34, 'Actualización software', 150.00, '2025-03-22', 'otro', 8),
(35, 'Reparación sillón dental', 400.00, '2025-03-25', 'proveedor', 8),
(36, 'Campaña marketing', 350.00, '2025-03-28', 'otro', 7),
(37, 'PAGO de agua', 19000.00, '2025-05-27', 'proveedor', 9);

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
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id_empleado` int(20) NOT NULL,
  `cedula` int(20) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `genero` enum('M','F','O') NOT NULL,
  `email` varchar(50) NOT NULL,
  `direccion` varchar(50) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `fecha_contratacion` date NOT NULL,
  `cargo` varchar(20) NOT NULL,
  `salario` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Estructura de tabla para la tabla `especialidades`
--

CREATE TABLE `especialidades` (
  `id_especialidad` tinyint(4) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `especialidades`
--

INSERT INTO `especialidades` (`id_especialidad`, `nombre`) VALUES
(5, 'Cirugía Oral'),
(3, 'Endodoncia'),
(1, 'Odontología General'),
(2, 'Ortodoncia'),
(4, 'Periodoncia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_cita`
--

CREATE TABLE `estados_cita` (
  `id_estado` tinyint(4) NOT NULL,
  `nombre_estado` varchar(20) NOT NULL,
  `color` varchar(20) DEFAULT '#6c757d',
  `es_activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados_cita`
--

INSERT INTO `estados_cita` (`id_estado`, `nombre_estado`, `color`, `es_activo`) VALUES
(1, 'Pendiente', '#ffc107', 1),
(2, 'Confirmada', '#28a745', 1),
(3, 'Cancelada', '#dc3545', 1),
(4, 'Completada', '#17a2b8', 1),
(5, 'No Asistió', '#6c757d', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos_calendario`
--

CREATE TABLE `eventos_calendario` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `color` varchar(20) DEFAULT '#3788d8',
  `google_event_id` varchar(255) DEFAULT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial`
--

CREATE TABLE `historial` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `Apellido` varchar(100) NOT NULL,
  `Ocupacion` varchar(100) DEFAULT NULL,
  `Sexo` enum('Masculino','Femenino') DEFAULT NULL,
  `PersonaContacto` int(20) DEFAULT NULL,
  `telefono` int(20) DEFAULT NULL,
  `Edad` int(11) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `diagnostico` varchar(255) DEFAULT NULL,
  `tratamiento` varchar(255) DEFAULT NULL,
  `medicamentos` varchar(255) DEFAULT NULL,
  `dientesafectados` int(20) DEFAULT NULL,
  `antecedentes` text DEFAULT NULL,
  `fechaconsulta` date DEFAULT NULL,
  `proximacita` date DEFAULT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial`
--

INSERT INTO `historial` (`id`, `nombre`, `Apellido`, `Ocupacion`, `Sexo`, `PersonaContacto`, `telefono`, `Edad`, `correo`, `motivo`, `diagnostico`, `tratamiento`, `medicamentos`, `dientesafectados`, `antecedentes`, `fechaconsulta`, `proximacita`, `observaciones`) VALUES
(0, 'jesus', 'regalado', '', '', 0, 0, 0, '', NULL, '', '', '', 0, '', '0000-00-00', '0000-00-00', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios_medicos`
--

CREATE TABLE `horarios_medicos` (
  `id_horario` int(11) NOT NULL,
  `id_medico` int(11) NOT NULL,
  `dia_semana` tinyint(4) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `disponible` tinyint(1) DEFAULT 1,
  `fecha_especifica` date DEFAULT NULL
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
  `cuenta_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ingresos`
--

INSERT INTO `ingresos` (`id`, `descripcion`, `monto`, `fecha`, `origen`, `cuenta_id`) VALUES
(11, 'prueba integridad referencial ', 2.00, '2025-05-24', 'consulta', 2),
(13, 'Consulta Dra. Pérez', 80.00, '2025-05-05', 'consulta', 7),
(14, 'Limpieza dental Sr. Gómez', 120.00, '2025-05-07', 'servicio', 7),
(15, 'Ortodoncia inicial Sra. López', 500.00, '2025-05-10', 'servicio', 8),
(16, 'Blanqueamiento dental', 300.00, '2025-05-12', 'servicio', 7),
(17, 'Extracción muela Sr. Martínez', 150.00, '2025-05-15', 'servicio', 8),
(18, 'Control post-operatorio', 50.00, '2025-05-18', 'consulta', 7),
(19, 'Prótesis dental completa', 1200.00, '2025-05-20', 'servicio', 8),
(20, 'Radiografía panorámica', 90.00, '2025-05-22', 'servicio', 7),
(21, 'Sellantes dentales (2)', 80.00, '2025-05-25', 'servicio', 7),
(22, 'Reembolso seguro dental', 450.00, '2025-05-28', 'manual', 8),
(23, 'Consulta Dr. González', 80.00, '2025-04-03', 'consulta', 7),
(24, 'Empaste dental', 120.00, '2025-04-05', 'servicio', 7),
(25, 'Corona dental', 400.00, '2025-04-08', 'servicio', 8),
(26, 'Limpieza dental', 100.00, '2025-04-10', 'servicio', 7),
(27, 'Revisión ortodoncia', 60.00, '2025-04-15', 'consulta', 7),
(28, 'Implante dental', 800.00, '2025-04-18', 'servicio', 8),
(29, 'Reembolso seguro', 320.00, '2025-04-22', 'manual', 8),
(30, 'Consulta niño', 70.00, '2025-04-25', 'consulta', 7),
(31, 'Aparato ortopédico', 350.00, '2025-04-28', 'servicio', 8),
(32, 'Consulta Dra. Pérez', 80.00, '2025-03-02', 'consulta', 7),
(33, 'Extracción simple', 100.00, '2025-03-05', 'servicio', 7),
(34, 'Ortodoncia pago mensual', 200.00, '2025-03-08', 'servicio', 8),
(35, 'Blanqueamiento', 280.00, '2025-03-12', 'servicio', 7),
(36, 'Radiografía', 75.00, '2025-03-15', 'servicio', 7),
(37, 'Limpieza profunda', 150.00, '2025-03-18', 'servicio', 7),
(38, 'Reembolso seguro', 420.00, '2025-03-22', 'manual', 8),
(39, 'Consulta urgencia', 90.00, '2025-03-25', 'consulta', 7),
(40, 'Prótesis parcial', 600.00, '2025-03-28', 'servicio', 8),
(42, 'PAGO ANUAL', 40000.00, '2025-05-27', 'consulta', 9);

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
-- Estructura de tabla para la tabla `medicos`
--

CREATE TABLE `medicos` (
  `id_medico` int(11) NOT NULL,
  `nombre_medico` varchar(100) NOT NULL,
  `numero_licencia` varchar(50) DEFAULT NULL,
  `especialidad` tinyint(4) DEFAULT NULL,
  `numero_contacto` varchar(20) DEFAULT NULL,
  `correo_electronico` varchar(100) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `medicos`
--

INSERT INTO `medicos` (`id_medico`, `nombre_medico`, `numero_licencia`, `especialidad`, `numero_contacto`, `correo_electronico`, `activo`, `fecha_registro`) VALUES
(28, 'juen acosta', '156154894123', 2, '3151303256', 'dasjokdna@gasd.com', 1, '2025-05-26 22:53:51'),
(30, 'juen acosta', '15615494123', 2, '3151303256', 'dasjokdna@gasd.com', 1, '2025-05-26 22:54:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `motivos_cita`
--

CREATE TABLE `motivos_cita` (
  `id_motivo` smallint(6) NOT NULL,
  `nombre_motivo` varchar(50) NOT NULL,
  `duracion_estimada` time NOT NULL DEFAULT '00:30:00',
  `requiere_equipo` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `motivos_cita`
--

INSERT INTO `motivos_cita` (`id_motivo`, `nombre_motivo`, `duracion_estimada`, `requiere_equipo`) VALUES
(1, 'Consulta general', '00:30:00', 0),
(2, 'Limpieza dental', '00:45:00', 0),
(3, 'Extracción', '00:30:00', 0),
(4, 'Ortodoncia', '00:45:00', 0),
(5, 'Blanqueamiento', '01:00:00', 0),
(6, 'Emergencia dental', '00:45:00', 0);

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
(0, 28591973, 'Jesus', 'Regalado', '2002-06-18', 'M', 'Nada', 'ninguno', 'ejejmplo@gmail.com', 'calle 911 ', '04120000000', '2025-05-20');

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
-- Estructura de tabla para la tabla `recordatorios_citas`
--

CREATE TABLE `recordatorios_citas` (
  `id_recordatorio` int(11) NOT NULL,
  `id_cita` int(10) UNSIGNED NOT NULL,
  `fecha_envio` datetime DEFAULT NULL,
  `metodo` enum('sms','email','whatsapp') NOT NULL,
  `estado` enum('pendiente','enviado','fallido') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Estructura de tabla para la tabla `solicitud_citas`
--

CREATE TABLE `solicitud_citas` (
  `id_solicitud` int(11) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `numero_contacto` varchar(20) NOT NULL,
  `motivo` text NOT NULL,
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado_solicitud` enum('pendiente','procesada') NOT NULL DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tratamientos`
--

CREATE TABLE `tratamientos` (
  `id_tratamiento` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `costo_base` decimal(10,2) NOT NULL,
  `sesiones_estimadas` tinyint(4) DEFAULT 1,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id_cita`),
  ADD KEY `id_solicitud` (`id_solicitud`),
  ADD KEY `fecha_cita` (`fecha_cita`),
  ADD KEY `estado_cita` (`estado_cita`),
  ADD KEY `fecha_cita_2` (`fecha_cita`),
  ADD KEY `estado_cita_2` (`estado_cita`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_documento` (`numero_documento`);

--
-- Indices de la tabla `consultas`
--
ALTER TABLE `consultas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `egresos`
--
ALTER TABLE `egresos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_egreso_cuenta` (`cuenta_id`);

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
-- Indices de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`id_especialidad`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `estados_cita`
--
ALTER TABLE `estados_cita`
  ADD PRIMARY KEY (`id_estado`),
  ADD UNIQUE KEY `nombre_estado` (`nombre_estado`);

--
-- Indices de la tabla `eventos_calendario`
--
ALTER TABLE `eventos_calendario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `horarios_medicos`
--
ALTER TABLE `horarios_medicos`
  ADD PRIMARY KEY (`id_horario`),
  ADD KEY `id_medico` (`id_medico`);

--
-- Indices de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ingreso_cuenta` (`cuenta_id`);

--
-- Indices de la tabla `medicos`
--
ALTER TABLE `medicos`
  ADD PRIMARY KEY (`id_medico`),
  ADD UNIQUE KEY `numero_licencia` (`numero_licencia`),
  ADD KEY `fk_medico_especialidad` (`especialidad`);

--
-- Indices de la tabla `motivos_cita`
--
ALTER TABLE `motivos_cita`
  ADD PRIMARY KEY (`id_motivo`),
  ADD UNIQUE KEY `nombre_motivo` (`nombre_motivo`);

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
-- Indices de la tabla `recordatorios_citas`
--
ALTER TABLE `recordatorios_citas`
  ADD PRIMARY KEY (`id_recordatorio`),
  ADD KEY `id_cita` (`id_cita`);

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
-- Indices de la tabla `solicitud_citas`
--
ALTER TABLE `solicitud_citas`
  ADD PRIMARY KEY (`id_solicitud`),
  ADD KEY `fecha_solicitud` (`fecha_solicitud`),
  ADD KEY `estado_solicitud` (`estado_solicitud`),
  ADD KEY `fecha_solicitud_2` (`fecha_solicitud`),
  ADD KEY `estado_solicitud_2` (`estado_solicitud`),
  ADD KEY `fecha_solicitud_3` (`fecha_solicitud`),
  ADD KEY `estado_solicitud_3` (`estado_solicitud`);

--
-- Indices de la tabla `tratamientos`
--
ALTER TABLE `tratamientos`
  ADD PRIMARY KEY (`id_tratamiento`);

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
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id_cita` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `consultas`
--
ALTER TABLE `consultas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `egresos`
--
ALTER TABLE `egresos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `entradas`
--
ALTER TABLE `entradas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `id_especialidad` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `estados_cita`
--
ALTER TABLE `estados_cita`
  MODIFY `id_estado` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `eventos_calendario`
--
ALTER TABLE `eventos_calendario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `horarios_medicos`
--
ALTER TABLE `horarios_medicos`
  MODIFY `id_horario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `medicos`
--
ALTER TABLE `medicos`
  MODIFY `id_medico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `motivos_cita`
--
ALTER TABLE `motivos_cita`
  MODIFY `id_motivo` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
-- AUTO_INCREMENT de la tabla `recordatorios_citas`
--
ALTER TABLE `recordatorios_citas`
  MODIFY `id_recordatorio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `salidas`
--
ALTER TABLE `salidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `solicitud_citas`
--
ALTER TABLE `solicitud_citas`
  MODIFY `id_solicitud` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tratamientos`
--
ALTER TABLE `tratamientos`
  MODIFY `id_tratamiento` int(11) NOT NULL AUTO_INCREMENT;

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
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitud_citas` (`id_solicitud`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `egresos`
--
ALTER TABLE `egresos`
  ADD CONSTRAINT `fk_egreso_cuenta` FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas` (`id`);

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
-- Filtros para la tabla `eventos_calendario`
--
ALTER TABLE `eventos_calendario`
  ADD CONSTRAINT `eventos_calendario_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `horarios_medicos`
--
ALTER TABLE `horarios_medicos`
  ADD CONSTRAINT `horarios_medicos_ibfk_1` FOREIGN KEY (`id_medico`) REFERENCES `medicos` (`id_medico`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD CONSTRAINT `fk_ingreso_cuenta` FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas` (`id`);

--
-- Filtros para la tabla `medicos`
--
ALTER TABLE `medicos`
  ADD CONSTRAINT `fk_medico_especialidad` FOREIGN KEY (`especialidad`) REFERENCES `especialidades` (`id_especialidad`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_categoria_fk` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
  ADD CONSTRAINT `productos_ubicacion_fk` FOREIGN KEY (`ubicacion_id`) REFERENCES `ubicaciones` (`id`);

--
-- Filtros para la tabla `recordatorios_citas`
--
ALTER TABLE `recordatorios_citas`
  ADD CONSTRAINT `recordatorios_citas_ibfk_1` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`) ON DELETE CASCADE;

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
