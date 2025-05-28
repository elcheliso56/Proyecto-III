
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";




CREATE TABLE `entradas_equipos` (
  `id` int(10) NOT NULL,
  `fecha_entrada` datetime NOT NULL,
  `nota_entrega` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `entradas_equipos_detalles` (
  `id_entrada_equipo` int(11) NOT NULL,
  `id_equipo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `entradas_insumos` (
  `id` int(10) NOT NULL,
  `fecha_entrada` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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


CREATE TABLE `insumos_entradas` (
  `id_entradas_insumos` int(10) NOT NULL,
  `id_insumos` int(10) NOT NULL,
  `cantidad` int(10) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `insumos_entradas`


CREATE TABLE `presentaciones` (
  `id` int(10) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `descripcion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `presentaciones`
--

INSERT INTO `presentaciones` (`id`, `nombre`, `descripcion`) VALUES
(2, 'Kilogramo', 'Kilogramo'),




CREATE TABLE `servicios` (
  `id` int(10) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--


CREATE TABLE `servicios_equipos` (
  `id_servicio` int(10) NOT NULL,
  `id_equipo` int(10) NOT NULL,
  `cantidad` int(10) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `servicios_insumos` (
  `id_servicio` int(10) NOT NULL,
  `id_insumo` int(10) NOT NULL,
  `cantidad` int(10) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, '', '', '', '', '', 'Administrador', '$2y$10$Fj2EAzVDE82XiQBPiLHZy.iS8VIaRK1jOYQ7RyelpdYPrEVv.OXk2', 'administrador', 'otros/img/usuarios/default.png');




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
-- Indices de la tabla `presentaciones`
--
ALTER TABLE `presentaciones`
  ADD PRIMARY KEY (`id`);


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


--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`);

--

ALTER TABLE `entradas_equipos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `entradas_insumos`
--
ALTER TABLE `entradas_insumos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `equipos`
--
ALTER TABLE `equipos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `insumos`
--
ALTER TABLE `insumos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `presentaciones`
--
ALTER TABLE `presentaciones`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `productos`
--

-- AUTO_INCREMENT de la tabla `proveedores`


--
ALTER TABLE `servicios`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT de la tabla `ubicaciones`
--


--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;


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


