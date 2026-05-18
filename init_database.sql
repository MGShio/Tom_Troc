-- =============================================================================
-- Fichier d'initialisation de la base de données TomTroc
-- Ce fichier permet de créer la structure de la base et d'insérer des données
-- d'exemple pour démarrer rapidement avec le projet.
-- =============================================================================

-- Création de la base de données (si elle n'existe pas)
CREATE DATABASE IF NOT EXISTS tom_troc
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE tom_troc;

-- =============================================================================
-- 1. TABLE UTILISATEURS
-- =============================================================================
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pseudo VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    avatar VARCHAR(100) DEFAULT 'Avatar_default.png',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================================
-- 2. TABLE LIVRES
-- =============================================================================
DROP TABLE IF EXISTS books;
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    author VARCHAR(100) NOT NULL,
    description TEXT,
    statut ENUM('disponible', 'echange', 'vendu', 'reserve') DEFAULT 'disponible',
    disponibilite ENUM('disponible', 'indisponible') DEFAULT 'disponible',
    user_id INT NOT NULL,
    image VARCHAR(100) DEFAULT 'Book_default.png',
    date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================================
-- 3. TABLE CONVERSATIONS
-- =============================================================================
DROP TABLE IF EXISTS conversations;
CREATE TABLE conversations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user1_id INT NOT NULL,
    user2_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user1_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (user2_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_conversation (user1_id, user2_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================================
-- 4. TABLE MESSAGES
-- =============================================================================
DROP TABLE IF EXISTS messages;
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conversation_id INT NOT NULL,
    sender_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_read TINYINT(1) DEFAULT 0,
    FOREIGN KEY (conversation_id) REFERENCES conversations(id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================================