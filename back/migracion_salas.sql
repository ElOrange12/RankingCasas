-- ================================================
-- MIGRACIÓN: Añadir id_sala a todas las tablas
-- Ejecutar en: rural_planner
-- ================================================

USE rural_planner;

-- Añadir id_sala a casas (si no existe la columna salas, crearla primero)
CREATE TABLE IF NOT EXISTS salas (
    id_sala INT AUTO_INCREMENT PRIMARY KEY,
    nombre_sala VARCHAR(100) NOT NULL,
    codigo_sala VARCHAR(20) NOT NULL UNIQUE,
    id_creador INT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_creador) REFERENCES usuarios(id_usuario) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS usuarios_salas (
    id_usuario INT,
    id_sala INT,
    PRIMARY KEY (id_usuario, id_sala),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_sala) REFERENCES salas(id_sala) ON DELETE CASCADE
);

-- Añadir id_sala a casas
ALTER TABLE casas ADD COLUMN id_sala INT NULL,
    ADD FOREIGN KEY (id_sala) REFERENCES salas(id_sala) ON DELETE CASCADE;

-- Añadir id_sala a actividades
ALTER TABLE actividades ADD COLUMN id_sala INT NULL,
    ADD FOREIGN KEY (id_sala) REFERENCES salas(id_sala) ON DELETE CASCADE;

-- Añadir id_sala a transporte
ALTER TABLE transporte ADD COLUMN id_sala INT NULL,
    ADD FOREIGN KEY (id_sala) REFERENCES salas(id_sala) ON DELETE CASCADE;

-- Añadir id_sala a votos_fechas
-- Primero eliminamos la PK actual y la recreamos con id_sala
ALTER TABLE votos_fechas 
    DROP PRIMARY KEY,
    ADD COLUMN id_sala INT NOT NULL DEFAULT 1,
    ADD PRIMARY KEY (id_usuario, fecha, id_sala),
    ADD FOREIGN KEY (id_sala) REFERENCES salas(id_sala) ON DELETE CASCADE;
