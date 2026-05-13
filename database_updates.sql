-- =============================================
-- Mises à jour de la base de données pour TomTroc
-- Exécutez ces requêtes dans phpMyAdmin ou via la ligne de commande MySQL
-- =============================================

-- 1. Ajouter les colonnes manquantes à la table users
-- =============================================

-- Vérifier si la colonne pseudo existe
SELECT COLUMN_NAME 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'users' 
AND COLUMN_NAME = 'pseudo';

-- Si elle n'existe pas, l'ajouter (remplacez 'name' par 'pseudo')
-- Si la colonne 'name' existe déjà, vous pouvez la renommer ou ajouter pseudo
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS pseudo VARCHAR(255) NOT NULL DEFAULT '' AFTER id;

-- Si vous aviez déjà une colonne 'name', vous pouvez migrer les données
UPDATE users SET pseudo = name WHERE pseudo = '' AND name IS NOT NULL;

-- Ajouter avatar et created_at
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS avatar VARCHAR(255) NOT NULL DEFAULT 'Avatar_default.png' AFTER password;

ALTER TABLE users 
ADD COLUMN IF NOT EXISTS created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER avatar;

-- 2. Ajouter la colonne disponibilite à la table books si elle n'existe pas
-- =============================================
ALTER TABLE books 
ADD COLUMN IF NOT EXISTS disponibilite ENUM('disponible', 'non disponible') NOT NULL DEFAULT 'disponible' AFTER statut;

-- Mettre à jour les valeurs existantes
UPDATE books SET disponibilite = statut WHERE disponibilite = 'disponible' AND statut IN ('disponible', 'non disponible');

-- 3. Créer les tables manquantes pour la messagerie
-- =============================================

-- Table conversations
CREATE TABLE IF NOT EXISTS conversations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user1_id INT NOT NULL,
    user2_id INT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user1_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (user2_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user1 (user1_id),
    INDEX idx_user2 (user2_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table messages
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conversation_id INT NOT NULL,
    sender_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    FOREIGN KEY (conversation_id) REFERENCES conversations(id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_conversation (conversation_id),
    INDEX idx_sender (sender_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 4. Mettre à jour la table books pour inclure date_ajout si elle n'existe pas
-- =============================================
ALTER TABLE books 
ADD COLUMN IF NOT EXISTS date_ajout TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER disponibilite;

-- 5. Vérification finale
-- =============================================
-- Vérifier que toutes les tables existent
SHOW TABLES;

-- Vérifier la structure des tables
DESCRIBE users;
DESCRIBE books;
DESCRIBE conversations;
DESCRIBE messages;
