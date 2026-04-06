-- ============================================================
-- PROYECTO AGUA - Base de Datos
-- Motor: MariaDB
-- Fecha: 2026-04-03
-- ============================================================

-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS proyecto_agua
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE proyecto_agua;

-- ============================================================
-- 1. TABLA: users
-- Descripción: Usuarios del sistema (operadores/admins)
-- RF relacionado: RF-01, RF-05
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'operador') NOT NULL DEFAULT 'operador',
    email_verified_at TIMESTAMP NULL DEFAULT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_users_email (email),
    INDEX idx_users_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 2. TABLA: tarifas
-- Descripción: Tarifas mensuales del servicio
-- RF relacionado: RF-04, RF-11
-- ============================================================
CREATE TABLE IF NOT EXISTS tarifas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    monto DECIMAL(10, 2) NOT NULL,
    descripcion TEXT NULL,
    vigente_desde DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_tarifas_vigente (vigente_desde)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3. TABLA: padron_usuarios
-- Descripción: Padrón de vecinos con servicio de agua
-- RF relacionado: RF-02, RF-03, RF-04
-- ============================================================
CREATE TABLE IF NOT EXISTS padron_usuarios (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) NOT NULL UNIQUE,
    nombre VARCHAR(255) NOT NULL,
    apellido VARCHAR(255) NOT NULL,
    direccion VARCHAR(500) NOT NULL,
    estado ENUM('activo', 'cortado') NOT NULL DEFAULT 'activo',
    tarifa_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_padron_codigo (codigo),
    INDEX idx_padron_estado (estado),
    INDEX idx_padron_tarifa (tarifa_id),
    INDEX idx_padron_nombre (nombre, apellido),

    CONSTRAINT fk_padron_tarifa
        FOREIGN KEY (tarifa_id) REFERENCES tarifas(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 4. TABLA: multas
-- Descripción: Catálogo de multas aplicables
-- RF relacionado: RF-04, RF-11
-- ============================================================
CREATE TABLE IF NOT EXISTS multas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT NULL,
    monto DECIMAL(10, 2) NOT NULL,
    activa TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_multas_activa (activa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 5. TABLA: multas_usuario (pivot)
-- Descripción: Multas asignadas a cada usuario del padrón
-- RF relacionado: RF-04, RF-11
-- ============================================================
CREATE TABLE IF NOT EXISTS multas_usuario (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    padron_usuario_id BIGINT UNSIGNED NOT NULL,
    multa_id BIGINT UNSIGNED NOT NULL,
    mes VARCHAR(7) NOT NULL COMMENT 'Formato YYYY-MM',
    monto DECIMAL(10, 2) NOT NULL,
    pagada TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_multas_usuario_padron (padron_usuario_id),
    INDEX idx_multas_usuario_multa (multa_id),
    INDEX idx_multas_usuario_mes (mes),
    INDEX idx_multas_usuario_pagada (pagada),

    CONSTRAINT fk_multas_usuario_padron
        FOREIGN KEY (padron_usuario_id) REFERENCES padron_usuarios(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_multas_usuario_multa
        FOREIGN KEY (multa_id) REFERENCES multas(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 6. TABLA: pagos
-- Descripción: Registro de cobros realizados
-- RF relacionado: RF-05, RF-06, RF-07
-- ============================================================
CREATE TABLE IF NOT EXISTS pagos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    padron_usuario_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL COMMENT 'Operador que registra el pago',
    periodo VARCHAR(7) NOT NULL COMMENT 'Formato YYYY-MM',
    monto_cuota DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    monto_deuda DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    monto_multas DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    monto_total DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    numero_serie VARCHAR(100) NULL UNIQUE,
    estado ENUM('pagado', 'pendiente') NOT NULL DEFAULT 'pendiente',
    pdf_path VARCHAR(500) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_pagos_padron (padron_usuario_id),
    INDEX idx_pagos_user (user_id),
    INDEX idx_pagos_periodo (periodo),
    INDEX idx_pagos_estado (estado),
    INDEX idx_pagos_serie (numero_serie),

    CONSTRAINT fk_pagos_padron
        FOREIGN KEY (padron_usuario_id) REFERENCES padron_usuarios(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_pagos_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 7. TABLA: egresos
-- Descripción: Gastos operativos de la junta
-- RF relacionado: RF-12, RF-09
-- ============================================================
CREATE TABLE IF NOT EXISTS egresos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(500) NOT NULL,
    categoria VARCHAR(255) NOT NULL,
    monto DECIMAL(10, 2) NOT NULL,
    fecha DATE NOT NULL,
    proveedor VARCHAR(255) NULL,
    periodo VARCHAR(7) NOT NULL COMMENT 'Formato YYYY-MM',
    user_id BIGINT UNSIGNED NOT NULL COMMENT 'Usuario que registra el egreso',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_egresos_categoria (categoria),
    INDEX idx_egresos_fecha (fecha),
    INDEX idx_egresos_periodo (periodo),
    INDEX idx_egresos_user (user_id),

    CONSTRAINT fk_egresos_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 8. TABLA: reportes_mensuales
-- Descripción: Consolidados mensuales aprobados
-- RF relacionado: RF-09, RF-10
-- ============================================================
CREATE TABLE IF NOT EXISTS reportes_mensuales (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    periodo VARCHAR(7) NOT NULL UNIQUE COMMENT 'Formato YYYY-MM',
    total_ingresos DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    total_egresos DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    total_pendientes DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    balance DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    estado ENUM('pendiente', 'aprobado') NOT NULL DEFAULT 'pendiente',
    aprobado_por BIGINT UNSIGNED NULL COMMENT 'Usuario admin que aprueba',
    pdf_path VARCHAR(500) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_reportes_periodo (periodo),
    INDEX idx_reportes_estado (estado),

    CONSTRAINT fk_reportes_aprobado_por
        FOREIGN KEY (aprobado_por) REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 9. TABLA: activity_logs
-- Descripción: Registro de acciones del sistema
-- RF relacionado: RF-01
-- ============================================================
CREATE TABLE IF NOT EXISTS activity_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    accion VARCHAR(255) NOT NULL,
    modulo VARCHAR(255) NOT NULL,
    ip VARCHAR(45) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_logs_user (user_id),
    INDEX idx_logs_modulo (modulo),
    INDEX idx_logs_created (created_at),

    CONSTRAINT fk_logs_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- DATOS SEMILLA (Seeds)
-- ============================================================

-- Usuario admin por defecto
INSERT INTO users (name, email, password, role) VALUES
('Administrador', 'admin@proyectoagua.com', '$2y$12$placeholder_hash_replace_me', 'admin');

-- Tarifas iniciales de ejemplo
INSERT INTO tarifas (nombre, monto, descripcion, vigente_desde) VALUES
('Tarifa Básica', 25.00, 'Tarifa estándar para uso doméstico', '2026-01-01'),
('Tarifa Comercial', 50.00, 'Tarifa para establecimientos comerciales', '2026-01-01');

-- Multas iniciales de ejemplo
INSERT INTO multas (nombre, descripcion, monto, activa) VALUES
('Mora por atraso', 'Multa por pago tardío del servicio', 10.00, 1),
('Reconexión', 'Multa por reconexión del servicio cortado', 30.00, 1);
