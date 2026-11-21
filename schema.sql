-- Dolphin CRM: database structure. Owner: Member 1.
-- Import this file in phpMyAdmin (Import tab) or paste it into the SQL tab.
-- It is safe to run more than once: it never drops anything.

CREATE DATABASE IF NOT EXISTS dolphin_crm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dolphin_crm;

CREATE TABLE IF NOT EXISTS users (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    firstname   VARCHAR(50)  NOT NULL,
    lastname    VARCHAR(50)  NOT NULL,
    password    VARCHAR(255) NOT NULL,            -- always a password_hash() value
    email       VARCHAR(100) NOT NULL UNIQUE,
    role        VARCHAR(10)  NOT NULL DEFAULT 'Member',   -- 'Admin' or 'Member'
    created_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS contacts (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(10)  NOT NULL,            -- Mr, Mrs, Ms, Dr, Prof
    firstname   VARCHAR(50)  NOT NULL,
    lastname    VARCHAR(50)  NOT NULL,
    email       VARCHAR(100) NOT NULL,
    telephone   VARCHAR(30)  NOT NULL,
    company     VARCHAR(100) NOT NULL,
    type        VARCHAR(20)  NOT NULL,            -- 'Sales Lead' or 'Support'
    assigned_to INT UNSIGNED NULL,                -- users.id
    created_by  INT UNSIGNED NOT NULL,            -- users.id
    created_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS notes (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    contact_id  INT UNSIGNED NOT NULL,            -- contacts.id
    comment     TEXT         NOT NULL,
    created_by  INT UNSIGNED NOT NULL,            -- users.id
    created_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- The required administrator: admin@project2.com / password123 (hashed with password_hash)
INSERT IGNORE INTO users (firstname, lastname, password, email, role)
VALUES ('Admin', 'User', '$2y$10$ZU5ugcSm1ljZrzkcZ9yAwO5X3vVsV.PLj3bNuUBK4CfJJTNPHV.SO', 'admin@project2.com', 'Admin');
