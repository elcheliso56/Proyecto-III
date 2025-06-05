-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-06-2025 a las 03:35:12
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
-- Base de datos: `bd_usuarios`
--

create database bd_usuarios IF NOT EXISTS ecommerce
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

-- Usar la base de datos

use bd_usuarios;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `id` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `modulo` varchar(50) NOT NULL,
  `accion` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `detalles` text DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `bitacora`
--

INSERT INTO `bitacora` (`id`, `fecha_hora`, `modulo`, `accion`, `descripcion`, `detalles`, `usuario_id`) VALUES
(1, '2025-05-29 23:33:56', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(2, '2025-05-29 23:35:03', 'Insumos', 'Incluir', 'Insumo incluido exitosamente', 'Código: sadasd, Nombre: sdasdasd, Marca: asdasd', 1),
(3, '2025-05-29 23:35:09', 'Insumos', 'Modificar', 'Insumo modificado exitosamente', 'Código: sadasd, Nombre: sdasdasd, Marca: asdasd', 1),
(4, '2025-05-29 23:35:12', 'Insumos', 'Eliminar', 'Insumo eliminado exitosamente', 'Código: sadasd, Nombre: sdasdasd, Marca: asdasd', 1),
(5, '2025-05-29 23:35:24', 'Insumos', 'Entrada', 'Entrada de insumos registrada exitosamente', 'ID Entrada: 38\nInsumo: 1 - Esteeeee, Cantidad: 12, Precio: 10.00', 1),
(6, '2025-05-29 23:35:38', 'Equipos', 'Incluir', 'Equipo incluido exitosamente', 'Código: asdasdas, Nombre: sadasd, Marca: asdasd, Modelo: sadas', 1),
(7, '2025-05-29 23:35:45', 'Equipos', 'Modificar', 'Equipo modificado exitosamente', 'Código: asdasdas, Nombre: sadasd, Marca: asdasd, Modelo: sadas', 1),
(8, '2025-05-29 23:35:49', 'Equipos', 'Eliminar', 'Equipo eliminado exitosamente', 'Código: asdasdas, Nombre: sadasd, Marca: asdasd, Modelo: sadas', 1),
(9, '2025-05-29 23:35:58', 'Equipos', 'Entrada', 'Entrada de equipos registrada exitosamente', 'ID Entrada: 33\nEquipo: hola - hola, Cantidad: 10, Precio: 100.00', 1),
(10, '2025-05-29 23:36:26', 'Servicios', 'Incluir', 'Servicio incluido exitosamente', 'Servicio: sdaasd, Descripción: sadasdsad, Precio: 11\nInsumos:\nInsumo: 1 - Esteeeee, Cantidad: 1, Precio: 10.00', 1),
(11, '2025-05-29 23:36:33', 'Servicios', 'Modificar', 'Servicio modificado exitosamente', 'Servicio: sdaasd\nCambios en insumos:\nEliminado: Insumo 1 - Esteeeee\nCambios en equipos:\nAgregado: Equipo xxxx - xxx, Cantidad: 1, Precio: 200.00', 1),
(12, '2025-05-29 23:36:36', 'Servicios', 'Eliminar', 'Servicio eliminado exitosamente', 'Servicio: sdaasd, Descripción: sadasdsad, Precio: 11.00\nEquipos eliminados:\n- xxxx - xxx, Cantidad: 1, Precio: 200.00\n', 1),
(13, '2025-05-29 23:38:16', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 2),
(14, '2025-05-29 23:38:27', 'Servicios', 'Modificar', 'Servicio modificado exitosamente', 'Servicio: sadsadsadsadd\nCambios en insumos:\nEliminado: Insumo xxx - dwasd\nActualizado: Insumo 1 - Esteeeee, Cantidad: 1 -> 1, Precio: 100.00 -> 10.00', 2),
(15, '2025-05-29 23:38:35', 'Equipos', 'Modificar', 'Equipo modificado exitosamente', 'Código: xxx, Nombre: bbbb, Marca: xxxx, Modelo: xxxsad', 2),
(16, '2025-05-29 23:38:40', 'Insumos', 'Modificar', 'Insumo modificado exitosamente', 'Código: 01, Nombre: Harina, Marca: PAN', 2),
(17, '2025-06-02 09:45:22', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(18, '2025-06-02 09:49:39', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(19, '2025-06-02 09:55:26', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(20, '2025-06-02 13:34:38', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(21, '2025-06-02 13:37:30', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(22, '2025-06-02 14:14:20', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(23, '2025-06-02 14:14:30', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(24, '2025-06-02 14:18:29', 'Login', 'Intento Fallido', 'Intento de inicio de sesión fallido', 'Usuario: Administrador', NULL),
(25, '2025-06-02 14:18:36', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(26, '2025-06-02 17:38:50', 'Login', 'Intento Fallido', 'Intento de inicio de sesión fallido', 'Usuario: Administrador', NULL),
(27, '2025-06-02 17:39:06', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(28, '2025-06-02 17:41:39', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(29, '2025-06-02 18:18:43', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(30, '2025-06-02 20:38:01', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(31, '2025-06-03 13:14:36', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(32, '2025-06-03 13:16:46', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 3),
(33, '2025-06-03 13:17:17', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(34, '2025-06-03 13:24:11', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(35, '2025-06-03 14:37:55', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(36, '2025-06-03 15:06:17', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(37, '2025-06-03 15:31:03', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(38, '2025-06-04 15:13:28', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id_permiso` int(11) NOT NULL,
  `nombre_permiso` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id_permiso`, `nombre_permiso`) VALUES
(1, 'Principal'),
(2, 'Citas'),
(3, 'Consultas'),
(4, 'Empleados'),
(5, 'Historiales'),
(6, 'Servicios'),
(7, 'Gestionar Insumo'),
(8, 'Gestionar Equipo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `estado` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre_rol`, `descripcion`, `estado`) VALUES
(8, 'Administrador', 'Es el Super Usuario con acceso a todo', 'ACTIVO'),
(9, 'Recepcionista', 'es la encargada de recibir a los clientes', 'ACTIVO'),
(10, 'Asistente', 'asistente del doctor', 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_permiso`
--

CREATE TABLE `rol_permiso` (
  `id_rol` int(11) NOT NULL,
  `id_permiso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol_permiso`
--

INSERT INTO `rol_permiso` (`id_rol`, `id_permiso`) VALUES
(8, 1),
(8, 2),
(8, 3),
(8, 4),
(8, 5),
(8, 6),
(8, 7),
(8, 8),
(9, 1),
(9, 2),
(9, 3),
(9, 6),
(10, 3),
(10, 5),
(10, 6),
(10, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `usuario` int(11) NOT NULL,
  `contrasena` text NOT NULL,
  `imagen` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`usuario`, `contrasena`, `imagen`) VALUES
(25369854, '$2y$10$lTnj.JqyMl0lbLWZmD.qVut2QjDlnz1bYxYY8AvwSkuKP5e3ocNuq', 'otros/img/usuarios/6840ee5a118c2_only.jpg'),
(25961374, '$2y$10$KRvMwtBRtabXL69m5wX.n.n0FRZbC/1oWFOfo7mKm7kb9AHN9G0Ri', 'otros/img/usuarios/6840ee4aa9ce3_only.jpg');

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
(1, '', '', '', '', '', 'Administrador', '$2y$10$jsMp89hwkYf4f1C02XjqqegoRgT3UdXg13P8NKxq5AbVAtjQbVkia', 'administrador', 'otros/img/usuarios/default.png'),
(2, '29945305', 'Gabriel', 'Hernandez', '04128282822', 'Gabriel@correo.com', 'Gabriel2003', '$2y$10$79f3HPwAOdxUIPdY97o8TOKTuRf7zxYZxruWREESjbOMs2bRKgEke', 'administrador', 'otros/img/usuarios/6838bf67e7458_err2.jpeg'),
(3, '25961375', 'Dannibelk', 'Amaro', '04124309712', 'Dannibelk@gmail.com', 'Dann', '$2y$10$R/Fmykg3SO9F9vUVY7cxkOk0jcZodoCWtsiUZWKUUJ4mDKoKhtFXi', 'usuario', 'otros/img/usuarios/683f2ded3fc44_only.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_rol`
--

CREATE TABLE `usuario_rol` (
  `usuario` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `estado` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario_rol`
--

INSERT INTO `usuario_rol` (`usuario`, `id_rol`, `estado`) VALUES
(25369854, 10, 'INACTIVO'),
(25961374, 10, 'ACTIVO');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id_permiso`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `rol_permiso`
--
ALTER TABLE `rol_permiso`
  ADD PRIMARY KEY (`id_rol`,`id_permiso`),
  ADD KEY `id_permiso` (`id_permiso`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`);

--
-- Indices de la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  ADD PRIMARY KEY (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD CONSTRAINT `bitacora_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `rol_permiso`
--
ALTER TABLE `rol_permiso`
  ADD CONSTRAINT `rol_permiso_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rol_permiso_ibfk_2` FOREIGN KEY (`id_permiso`) REFERENCES `permisos` (`id_permiso`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
