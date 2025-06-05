-- Tabla de permisos (estructura simple)
CREATE TABLE permisos (
    id_permiso INT AUTO_INCREMENT PRIMARY KEY,
    nombre_permiso VARCHAR(100) NOT NULL
);

-- Tabla de relación roles-permisos (estructura simple)
CREATE TABLE rol_permiso (
    id_rol INT,
    id_permiso INT,
    PRIMARY KEY (id_rol, id_permiso),
    FOREIGN KEY (id_rol) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (id_permiso) REFERENCES permisos(id_permiso) ON DELETE CASCADE
);

-- Insertar permisos básicos
INSERT INTO permisos (nombre_permiso) VALUES
('Crear Usuarios'),
('Editar Usuarios'),
('Eliminar Usuarios'),
('Crear Roles'),
('Editar Roles'),
('Eliminar Roles'),
('Configuración del Sistema'),
('Generar Reportes'),
('Ver Auditoría');

-- Asignar todos los permisos al rol ADMINISTRADOR por defecto
INSERT INTO rol_permiso (id_rol, id_permiso)
SELECT 
    (SELECT id FROM roles WHERE nombre_rol = 'ADMINISTRADOR'),
    id_permiso
FROM permisos; 