-- Création de la base de données
CREATE DATABASE IF NOT EXISTS hergrowth CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hergrowth;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prenom VARCHAR(50) NOT NULL,
    nom VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'coach', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des coachs
CREATE TABLE IF NOT EXISTS coachs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    nom VARCHAR(100) NOT NULL,
    domaine VARCHAR(100) NOT NULL,
    specialite TEXT,
    bio TEXT,
    prix DECIMAL(10, 2) NOT NULL,
    note DECIMAL(2,1) DEFAULT 4.5,
    en_ligne TINYINT(1) DEFAULT 1,
    valide TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Table des réservations
CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    coach_id INT NOT NULL,
    date DATE NOT NULL,
    heure TIME NOT NULL,
    type VARCHAR(50) DEFAULT 'Individuelle',
    statut ENUM('attente', 'confirme', 'annule') DEFAULT 'attente',
    prix DECIMAL(10, 2) NOT NULL,
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (coach_id) REFERENCES coachs(id)
);

-- Table des avis
CREATE TABLE IF NOT EXISTS avis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    resa_id INT NOT NULL,
    coach_id INT NOT NULL,
    user_id INT NOT NULL,
    note INT CHECK (note BETWEEN 1 AND 5),
    commentaire TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (resa_id) REFERENCES reservations(id),
    FOREIGN KEY (coach_id) REFERENCES coachs(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Table des disponibilités
CREATE TABLE IF NOT EXISTS coach_dispos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    coach_id INT NOT NULL,
    jour_semaine INT, -- 0 (dimanche) à 6 (samedi)
    heure_debut TIME,
    heure_fin TIME,
    FOREIGN KEY (coach_id) REFERENCES coachs(id)
);

-- Table des certifications
CREATE TABLE IF NOT EXISTS coach_certifs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    coach_id INT NOT NULL,
    titre VARCHAR(255),
    annee INT,
    image_url VARCHAR(255),
    FOREIGN KEY (coach_id) REFERENCES coachs(id)
);

-- Table des messages
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    expediteur_id INT NOT NULL,
    destinataire_id INT NOT NULL,
    contenu TEXT NOT NULL,
    lu TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (expediteur_id) REFERENCES users(id),
    FOREIGN KEY (destinataire_id) REFERENCES users(id)
);

-- Table des programmes (Bootcamps)
CREATE TABLE IF NOT EXISTS programmes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    coach_id INT NOT NULL,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    prix DECIMAL(10, 2),
    duree_jours INT,
    image_url VARCHAR(255),
    FOREIGN KEY (coach_id) REFERENCES coachs(id)
);

-- Table des abonnements VIP
CREATE TABLE IF NOT EXISTS abonnements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type VARCHAR(50) DEFAULT 'VIP',
    statut VARCHAR(20) DEFAULT 'actif',
    date_expiration DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Insertion de l'admin par défaut (admin123)
INSERT INTO users (prenom, nom, email, password, role) 
VALUES ('Admin', 'HerGrowth', 'admin@hergrowth.com', '$2y$10$C8.rF6o6kR5eE7B7t9.I.OQ7A7e7O7E7A7e7O7E7A7e7O7E7A7e7O', 'admin')
ON DUPLICATE KEY UPDATE role='admin';
