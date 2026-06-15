-- =============================================
-- GESTION DES SOUTENANCES - SCHÉMA COMPLET
-- =============================================

DROP DATABASE IF EXISTS gest_soutenance;
CREATE DATABASE gest_soutenance CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gest_soutenance;

-- =============================================
-- TABLE UTILISATEURS
-- =============================================
CREATE TABLE utilisateurs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('administrateur', 'secretaire_pedagogique', 'enseignant', 'etudiant', 'responsable_pedagogique') NOT NULL,
    actif BOOLEAN DEFAULT TRUE,
    doit_changer_mdp BOOLEAN DEFAULT FALSE,
    remember_token VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
);

-- =============================================
-- TABLE SALLES
-- =============================================
CREATE TABLE salles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    capacite INT UNSIGNED NOT NULL,
    localisation VARCHAR(255),
    equipements TEXT,
    actif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_actif (actif)
);

-- =============================================
-- TABLE SOUTENANCES
-- =============================================
CREATE TABLE soutenances (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    etudiant_id INT UNSIGNED NOT NULL,
    directeur_id INT UNSIGNED NOT NULL,
    titre VARCHAR(255) NOT NULL,
    filiere VARCHAR(100) NOT NULL,
    type ENUM('licence', 'master', 'doctorat') NOT NULL,
    date DATE NOT NULL,
    heure TIME NOT NULL,
    salle_id INT UNSIGNED NULL,
    statut ENUM('brouillon', 'planifiee', 'confirmee', 'realisee', 'annulee') DEFAULT 'brouillon',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (etudiant_id) REFERENCES utilisateurs(id) ON DELETE RESTRICT,
    FOREIGN KEY (directeur_id) REFERENCES utilisateurs(id) ON DELETE RESTRICT,
    FOREIGN KEY (salle_id) REFERENCES salles(id) ON DELETE SET NULL,
    INDEX idx_date (date),
    INDEX idx_statut (statut),
    INDEX idx_etudiant (etudiant_id)
);

-- =============================================
-- TABLE JURY_MEMBRES
-- =============================================
CREATE TABLE jury_membres (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    soutenance_id INT UNSIGNED NOT NULL,
    utilisateur_id INT UNSIGNED NOT NULL,
    role ENUM('president', 'directeur', 'rapporteur', 'membre') NOT NULL,
    statut_confirmation ENUM('en_attente', 'confirme', 'refuse') DEFAULT 'en_attente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (soutenance_id) REFERENCES soutenances(id) ON DELETE CASCADE,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    UNIQUE KEY unique_membre_soutenance (soutenance_id, utilisateur_id),
    INDEX idx_soutenance (soutenance_id)
);

-- =============================================
-- TABLE PV (PROCÈS-VERBAUX)
-- =============================================
CREATE TABLE pv (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    soutenance_id INT UNSIGNED NOT NULL UNIQUE,
    note DECIMAL(4,2) CHECK (note >= 0 AND note <= 20),
    mention ENUM('Passable', 'Assez bien', 'Bien', 'Tres bien', 'Excellent') NULL,
    observations TEXT,
    status ENUM('brouillon', 'en_validation', 'valide', 'signe', 'archive') DEFAULT 'brouillon',
    fichier_pdf VARCHAR(255) NULL,
    signe_le DATE NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (soutenance_id) REFERENCES soutenances(id) ON DELETE CASCADE,
    INDEX idx_status (status)
);

-- =============================================
-- TABLE DOCUMENTS
-- =============================================
CREATE TABLE documents (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    soutenance_id INT UNSIGNED NULL,
    etudiant_id INT UNSIGNED NOT NULL,
    type ENUM('convocation', 'pv', 'attestation', 'autre') NOT NULL,
    nom_fichier VARCHAR(255) NOT NULL,
    chemin_fichier VARCHAR(500) NOT NULL,
    hash_sha256 VARCHAR(64) NOT NULL,
    taille INT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (soutenance_id) REFERENCES soutenances(id) ON DELETE SET NULL,
    FOREIGN KEY (etudiant_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    INDEX idx_type (type),
    INDEX idx_etudiant (etudiant_id)
);

-- =============================================
-- TABLE INDISPONIBILITES
-- =============================================
CREATE TABLE indisponibilites (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    enseignant_id INT UNSIGNED NOT NULL,
    date DATE NOT NULL,
    creneau ENUM('matin', 'apres-midi', 'journee') NOT NULL,
    motif TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (enseignant_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    UNIQUE KEY unique_indispo (enseignant_id, date, creneau),
    INDEX idx_date (date)
);

-- =============================================
-- TABLE AUDIT_LOG
-- =============================================
CREATE TABLE audit_log (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT UNSIGNED NULL,
    action VARCHAR(100) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_utilisateur (utilisateur_id),
    INDEX idx_action (action),
    INDEX idx_created (created_at)
);

-- =============================================
-- TABLE NOTIFICATIONS
-- =============================================
CREATE TABLE notifications (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT UNSIGNED NOT NULL,
    type VARCHAR(50) NOT NULL,
    titre VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    lien VARCHAR(500) NULL,
    lue BOOLEAN DEFAULT FALSE,
    email_envoye BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    INDEX idx_utilisateur_non_lu (utilisateur_id, lue),
    INDEX idx_created (created_at)
);