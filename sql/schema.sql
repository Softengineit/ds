-- SEED Digital School — schéma BDD
-- À exécuter une fois sur la base MariaDB c2586017c_ds (cPanel → phpMyAdmin)

CREATE TABLE IF NOT EXISTS users (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email           VARCHAR(190) NOT NULL UNIQUE,
    nom             VARCHAR(190) NOT NULL,
    password_hash   VARCHAR(255) NOT NULL,
    role            ENUM('admin', 'editor') NOT NULL DEFAULT 'editor',
    last_login_at   DATETIME NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS inscriptions (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom             VARCHAR(190) NOT NULL,
    email           VARCHAR(190) NOT NULL,
    telephone       VARCHAR(40) NOT NULL,
    formation       VARCHAR(190) NOT NULL,
    pack            VARCHAR(190) NULL,
    groupe          VARCHAR(190) NULL,
    message         TEXT NULL,
    ip              VARCHAR(45) NULL,
    user_agent      VARCHAR(255) NULL,
    statut          ENUM('nouveau','contacte','inscrit','abandonne') NOT NULL DEFAULT 'nouveau',
    notes_internes  TEXT NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_statut (statut),
    INDEX idx_email (email),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS audit_log (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         INT UNSIGNED NULL,
    user_email      VARCHAR(190) NULL,
    action          VARCHAR(100) NOT NULL,
    target          VARCHAR(190) NULL,
    details         TEXT NULL,
    ip              VARCHAR(45) NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_action (action),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
