-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-05-2025 a las 05:44:34
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
(16, '2025-05-29 23:38:40', 'Insumos', 'Modificar', 'Insumo modificado exitosamente', 'Código: 01, Nombre: Harina, Marca: PAN', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `nombre_usuario` varchar(50) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `tipo_usuario` varchar(50) NOT NULL DEFAULT 'regular',
  `imagen` varchar(255) DEFAULT 'otros/img/usuarios/default.png',
  PRIMARY KEY (`nombre_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`nombre_usuario`, `contraseña`, `tipo_usuario`, `imagen`) VALUES
('Administrador', '$2y$10$Fj2EAzVDE82XiQBPiLHZy.iS8VIaRK1jOYQ7RyelpdYPrEVv.OXk2', 'administrador', 'otros/img/usuarios/default.png'),
('admin', '$2y$10$xeLiosMt0wr7k.N3Re1BeuUFhO5jf778aNxSC9A5UZRzOs2sbNOjC', 'administrador', 'otros/img/usuarios/default.png');

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
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`nombre_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD CONSTRAINT `bitacora_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_rol` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` enum('ACTIVO','INACTIVO') DEFAULT 'ACTIVO',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_rol` (`nombre_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre_rol`, `descripcion`, `estado`) VALUES
(1, 'ADMINISTRADOR', 'Rol con acceso total al sistema', 'ACTIVO'),
(2, 'USUARIO', 'Rol con acceso limitado', 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_rol`
--

CREATE TABLE `usuario_rol` (
  `usuario` varchar(50) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `estado` enum('ACTIVO','INACTIVO') DEFAULT 'ACTIVO',
  PRIMARY KEY (`usuario`,`id_rol`),
  KEY `id_rol` (`id_rol`),
  CONSTRAINT `usuario_rol_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`nombre_usuario`) ON DELETE CASCADE,
  CONSTRAINT `usuario_rol_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuario_rol`
--

INSERT INTO `usuario_rol` (`usuario`, `id_rol`, `estado`) VALUES
('Administrador', 1, 'ACTIVO'),
('admin', 1, 'ACTIVO');

-- --------------------------------------------------------
