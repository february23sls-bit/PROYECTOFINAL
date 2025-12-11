
-- Crear base de datos (ejecuta primero si necesitas crearla)
-- CREATE DATABASE courier_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE courier_db;
SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS emprendedores (
  id_emprendedor INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  correo VARCHAR(160) NOT NULL UNIQUE,
  telefono VARCHAR(40),
  direccion VARCHAR(200),
  contrasena_hash VARCHAR(255) NOT NULL,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS administradores (
  id_administrador INT AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(60) NOT NULL UNIQUE,
  contrasena_hash VARCHAR(255) NOT NULL,
  nombre_completo VARCHAR(160)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS motorizados (
  id_motorizado INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  documento_identidad VARCHAR(40) UNIQUE,
  telefono VARCHAR(40),
  placa_moto VARCHAR(20),
  activo TINYINT DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS rutas (
  id_ruta INT AUTO_INCREMENT PRIMARY KEY,
  nombre_ruta VARCHAR(120) NOT NULL,
  distritos TEXT NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS motorizados_rutas (
  id_motorizado INT NOT NULL,
  id_ruta INT NOT NULL,
  PRIMARY KEY (id_motorizado, id_ruta),
  CONSTRAINT fk_mr_m FOREIGN KEY (id_motorizado) REFERENCES motorizados(id_motorizado) ON DELETE CASCADE,
  CONSTRAINT fk_mr_r FOREIGN KEY (id_ruta) REFERENCES rutas(id_ruta) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pedidos (
  id_pedido INT AUTO_INCREMENT PRIMARY KEY,
  id_emprendedor INT NOT NULL,
  descripcion TEXT NOT NULL,
  peso_kg DECIMAL(10,2),
  dimensiones VARCHAR(60),
  destino_nombre VARCHAR(120),
  destino_telefono VARCHAR(40),
  destino_direccion VARCHAR(200) NOT NULL,
  destino_distrito VARCHAR(120) NOT NULL,
  estado ENUM('pendiente','asignado','en_ruta','entregado','cancelado') DEFAULT 'pendiente',
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_p_e FOREIGN KEY (id_emprendedor) REFERENCES emprendedores(id_emprendedor) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS asignaciones (
  id_asignacion INT AUTO_INCREMENT PRIMARY KEY,
  id_pedido INT NOT NULL,
  id_motorizado INT NOT NULL,
  fecha_asignacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_a_p FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido) ON DELETE CASCADE,
  CONSTRAINT fk_a_m FOREIGN KEY (id_motorizado) REFERENCES motorizados(id_motorizado) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Admin por defecto: usuario admin / contraseña admin123 (se actualiza en login_procesar si no existe)
INSERT IGNORE INTO administradores (id_administrador, usuario, contrasena_hash, nombre_completo)
VALUES (1,'admin','$2y$10$placeholderhashplaceholderhashplaceho','Administrador');
