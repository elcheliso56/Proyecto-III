-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-06-2025 a las 07:09:04
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

create database bd_usuarios IF NOT EXISTS bd_usuarios
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

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
(39, '2025-06-05 22:11:51', 'Login', 'Intento Fallido', 'Intento de inicio de sesión fallido', 'Usuario: Administrador', 1),
(40, '2025-06-05 22:12:16', 'Login', 'Intento Fallido', 'Intento de inicio de sesión fallido', 'Usuario: 25961374', 1),
(44, '2025-06-05 22:15:51', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(45, '2025-06-05 22:41:07', 'Login', 'Intento Fallido', 'Intento de inicio de sesión fallido', 'Usuario: Administrador', 1),
(46, '2025-06-05 22:48:37', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(47, '2025-06-05 22:50:40', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(48, '2025-06-05 22:51:19', 'Login', 'Intento Fallido', 'Intento de inicio de sesión fallido', 'Usuario: ', 1),
(49, '2025-06-05 22:51:28', 'Login', 'Intento Fallido', 'Intento de inicio de sesión fallido', 'Usuario: ', 1),
(50, '2025-06-05 22:51:42', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(51, '2025-06-05 22:52:31', 'Login', 'Intento Fallido', 'Intento de inicio de sesión fallido', 'Usuario: ', 1),
(52, '2025-06-05 22:52:56', 'Login', 'Intento Fallido', 'Intento de inicio de sesión fallido', 'Usuario: Array', 1),
(53, '2025-06-05 22:53:07', 'Login', 'Intento Fallido', 'Intento de inicio de sesión fallido', 'Usuario: Array', 1),
(54, '2025-06-05 22:53:29', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(55, '2025-06-05 22:54:10', 'Login', 'Intento Fallido', 'Intento de inicio de sesión fallido', 'Usuario: ', 1),
(56, '2025-06-05 22:56:24', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(57, '2025-06-05 22:56:58', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(58, '2025-06-05 22:56:58', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(59, '2025-06-05 22:57:32', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(60, '2025-06-05 23:03:28', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(61, '2025-06-05 23:08:00', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(62, '2025-06-06 00:11:28', 'Login', 'Intento Fallido', 'Intento de inicio de sesión fallido', 'Usuario: ', 1),
(63, '2025-06-06 00:11:42', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(64, '2025-06-06 00:28:25', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(65, '2025-06-06 00:28:36', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(66, '2025-06-06 00:28:37', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(67, '2025-06-06 00:28:50', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(68, '2025-06-06 00:29:27', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(69, '2025-06-06 00:30:12', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(70, '2025-06-06 00:32:10', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(71, '2025-06-06 00:53:56', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(72, '2025-06-06 00:58:16', 'Login', 'Intento Fallido', 'Intento de inicio de sesión fallido', 'Usuario: Array', 1),
(73, '2025-06-06 00:58:34', 'Login', 'Intento Fallido', 'Intento de inicio de sesión fallido', 'Usuario: Array', 1),
(74, '2025-06-06 00:59:25', 'Login', 'Intento Fallido', 'Intento de inicio de sesión fallido', 'Usuario: Array', 1),
(75, '2025-06-06 01:01:28', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1),
(76, '2025-06-06 01:07:11', 'Login', 'Inicio de Sesión', 'Usuario inició sesión exitosamente', NULL, 1);

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
(1, 'ADMINISTRADOR', 'SUPER USUARIO', 'ACTIVO');

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
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `usuario` int(11) NOT NULL,
  `nombre_apellido` varchar(50) NOT NULL,
  `contrasena` text NOT NULL,
  `imagen` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`usuario`, `nombre_apellido`, `contrasena`, `imagen`) VALUES
(12345678, '', '$2y$10$E2B6J6Enuw8CTkaf3Bd5t.2N9JB9XFLp1hkoy7wDr2GUbD6tpsYW2', 'otros/img/usuarios/default.png');

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
(1, '', '', '', '', '', 'Administrador', '$2y$10$jsMp89hwkYf4f1C02XjqqegoRgT3UdXg13P8NKxq5AbVAtjQbVkia', 'administrador', 'otros/img/usuarios/default.png');

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
(12345678, 1, 'ACTIVO');

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
  ADD KEY `id_rol` (`id_rol`),
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
  ADD KEY `usuario` (`usuario`,`id_rol`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  ADD CONSTRAINT `rol_permiso_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rol_permiso_ibfk_2` FOREIGN KEY (`id_permiso`) REFERENCES `permisos` (`id_permiso`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  ADD CONSTRAINT `usuario_rol_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuario_rol_ibfk_2` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
