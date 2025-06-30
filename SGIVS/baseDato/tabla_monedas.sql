-- Tabla de monedas
CREATE TABLE `monedas` (
  `id` varchar(20) NOT NULL,
  `codigo` varchar(3) NOT NULL COMMENT 'Código ISO de la moneda (USD, VES, EUR, etc.)',
  `nombre` varchar(50) NOT NULL COMMENT 'Nombre completo de la moneda',
  `simbolo` varchar(5) DEFAULT NULL COMMENT 'Símbolo de la moneda ($, Bs, €)',
  `activa` tinyint(1) DEFAULT 1 COMMENT '1=Activa, 0=Inactiva',
  `es_principal` tinyint(1) DEFAULT 0 COMMENT '1=Moneda principal (USD), 0=Otra moneda',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla de tipos de cambio
CREATE TABLE `tipos_cambio` (
  `id` varchar(20) NOT NULL,
  `moneda_origen` varchar(20) NOT NULL COMMENT 'ID de la moneda origen',
  `moneda_destino` varchar(20) NOT NULL COMMENT 'ID de la moneda destino',
  `tipo_cambio` decimal(10,4) NOT NULL COMMENT 'Valor del tipo de cambio',
  `fecha` date NOT NULL COMMENT 'Fecha del tipo de cambio',
  `usuario_id` varchar(20) DEFAULT NULL COMMENT 'Usuario que registró el tipo de cambio',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `moneda_fecha` (`moneda_origen`, `moneda_destino`, `fecha`),
  KEY `fk_tc_moneda_origen` (`moneda_origen`),
  KEY `fk_tc_moneda_destino` (`moneda_destino`),
  CONSTRAINT `fk_tc_moneda_origen` FOREIGN KEY (`moneda_origen`) REFERENCES `monedas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tc_moneda_destino` FOREIGN KEY (`moneda_destino`) REFERENCES `monedas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insertar monedas básicas
INSERT INTO `monedas` (`id`, `codigo`, `nombre`, `simbolo`, `activa`, `es_principal`) VALUES
('MON001', 'USD', 'Dólar Estadounidense', '$', 1, 1),
('MON002', 'VES', 'Bolívar Soberano', 'Bs', 1, 0),
('MON003', 'EUR', 'Euro', '€', 1, 0),
('MON004', 'COP', 'Peso Colombiano', '$', 1, 0);

-- Insertar tipos de cambio iniciales (ejemplo)
INSERT INTO `tipos_cambio` (`id`, `moneda_origen`, `moneda_destino`, `tipo_cambio`, `fecha`) VALUES
('TC001', 'MON001', 'MON002', 35.50, CURDATE()),
('TC002', 'MON001', 'MON003', 0.85, CURDATE()),
('TC003', 'MON001', 'MON004', 3800.00, CURDATE());

-- Trigger para actualizar tipos de cambio inversos automáticamente
DELIMITER $$
CREATE TRIGGER `after_tipo_cambio_insert` AFTER INSERT ON `tipos_cambio` 
FOR EACH ROW 
BEGIN
    -- Insertar el tipo de cambio inverso si no existe
    INSERT IGNORE INTO tipos_cambio (id, moneda_origen, moneda_destino, tipo_cambio, fecha, usuario_id)
    VALUES (
        CONCAT('TC', UUID_SHORT()),
        NEW.moneda_destino,
        NEW.moneda_origen,
        1 / NEW.tipo_cambio,
        NEW.fecha,
        NEW.usuario_id
    );
END$$
DELIMITER ;

-- Vista para obtener el tipo de cambio más reciente
CREATE OR REPLACE VIEW `v_tipo_cambio_actual` AS
SELECT 
    tc.id,
    mo.codigo as moneda_origen_codigo,
    mo.nombre as moneda_origen_nombre,
    md.codigo as moneda_destino_codigo,
    md.nombre as moneda_destino_nombre,
    tc.tipo_cambio,
    tc.fecha,
    tc.fecha_registro
FROM tipos_cambio tc
INNER JOIN monedas mo ON tc.moneda_origen = mo.id
INNER JOIN monedas md ON tc.moneda_destino = md.id
WHERE tc.fecha = (
    SELECT MAX(fecha) 
    FROM tipos_cambio 
    WHERE moneda_origen = tc.moneda_origen 
    AND moneda_destino = tc.moneda_destino
);

-- Función para convertir monedas
DELIMITER $$
CREATE FUNCTION `convertir_moneda`(
    monto DECIMAL(10,2),
    moneda_origen VARCHAR(3),
    moneda_destino VARCHAR(3),
    fecha_cambio DATE
) RETURNS DECIMAL(10,2)
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE tipo_cambio_valor DECIMAL(10,4);
    
    -- Si es la misma moneda, retornar el mismo valor
    IF moneda_origen = moneda_destino THEN
        RETURN monto;
    END IF;
    
    -- Obtener el tipo de cambio
    SELECT tc.tipo_cambio INTO tipo_cambio_valor
    FROM tipos_cambio tc
    INNER JOIN monedas mo ON tc.moneda_origen = mo.id
    INNER JOIN monedas md ON tc.moneda_destino = md.id
    WHERE mo.codigo = moneda_origen 
    AND md.codigo = moneda_destino
    AND tc.fecha = fecha_cambio
    LIMIT 1;
    
    -- Si no hay tipo de cambio, retornar NULL
    IF tipo_cambio_valor IS NULL THEN
        RETURN NULL;
    END IF;
    
    RETURN monto * tipo_cambio_valor;
END$$
DELIMITER ; 