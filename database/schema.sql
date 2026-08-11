-- =====================================================================
-- REUSE - Esquema de base de datos
-- =====================================================================

CREATE DATABASE IF NOT EXISTS reuse_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE reuse_db;

-- ---------------------------------------------------------------------
-- Tabla: usuarios
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS usuarios (
  id                INT AUTO_INCREMENT PRIMARY KEY,
  nombre            VARCHAR(80)  NOT NULL,
  apellidos         VARCHAR(120) NOT NULL,
  username          VARCHAR(50)  NOT NULL,
  email             VARCHAR(150) NOT NULL,
  password_hash     VARCHAR(255) NOT NULL,
  foto_perfil       VARCHAR(255) NOT NULL DEFAULT 'IMAGEN.PERFIL.png',
  puntos            INT NOT NULL DEFAULT 0,
  kg_reciclado      DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  nivel_huella      VARCHAR(20)  NOT NULL DEFAULT 'Sin calcular',
  activo            TINYINT(1)   NOT NULL DEFAULT 1,
  fecha_registro    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ultima_conexion   TIMESTAMP    NULL DEFAULT NULL,
  UNIQUE KEY uq_usuarios_email (email),
  UNIQUE KEY uq_usuarios_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Tabla: configuracion_usuario
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS configuracion_usuario (
  id                    INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id            INT NOT NULL,
  notificaciones_email  TINYINT(1) NOT NULL DEFAULT 1,
  notificaciones_push   TINYINT(1) NOT NULL DEFAULT 1,
  perfil_publico        TINYINT(1) NOT NULL DEFAULT 1,
  idioma                VARCHAR(10) NOT NULL DEFAULT 'es',
  CONSTRAINT fk_config_usuario
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    ON DELETE CASCADE,
  UNIQUE KEY uq_config_usuario (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Usuario de prueba
-- Contraseña en texto plano: Prueba123
-- ---------------------------------------------------------------------
INSERT INTO usuarios (nombre, apellidos, username, email, password_hash, puntos, kg_reciclado, nivel_huella)
VALUES (
  'Manuela',
  'Rojas Esquivel',
  'manuelaroes',
  'manuelaroes@gmail.com',
  '$2y$10$/Thbz.0VYSrp7VFFoqBQ6.V71WEPJb1DhFd6fbxXGoNhf0AgtBqRe', -- Prueba123
  1250,
  32.00,
  'Nivel Bajo'
)
ON DUPLICATE KEY UPDATE email = email;

INSERT INTO configuracion_usuario (usuario_id, notificaciones_email, notificaciones_push, perfil_publico, idioma)
SELECT id, 1, 1, 1, 'es' FROM usuarios WHERE username = 'manuelaroes'
ON DUPLICATE KEY UPDATE usuario_id = usuario_id;

-- ---------------------------------------------------------------------
-- Tabla: registro_reciclaje
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS registro_reciclaje (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id      INT NOT NULL,
  material        VARCHAR(60)  NOT NULL,
  kilos           DECIMAL(10,2) NOT NULL,
  centro_acopio   VARCHAR(120) NOT NULL,
  puntos_ganados  INT NOT NULL,
  fecha_registro  DATE NOT NULL,
  creado_en       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_reciclaje_usuario
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Tabla: huella_calculos
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS huella_calculos (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id      INT NOT NULL,
  pais            VARCHAR(60)  NOT NULL,
  provincia       VARCHAR(60)  NOT NULL,
  tipo_vivienda   VARCHAR(60)  NOT NULL,
  nivel_huella    VARCHAR(20)  NOT NULL,
  fecha_calculo   DATE NOT NULL,
  creado_en       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_huella_usuario
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Tabla: historial_canjes
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS historial_canjes (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id      INT NOT NULL,
  recompensa      VARCHAR(120) NOT NULL,
  puntos_usados   INT NOT NULL,
  codigo_cupon    VARCHAR(40)  NOT NULL,
  fecha_canje     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_canje_usuario
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

