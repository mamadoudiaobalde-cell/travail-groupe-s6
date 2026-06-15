-- =============================================
-- DONNÉES DE TEST
-- =============================================

USE gest_soutenance;

-- Mots de passe : password123
-- Hash bcrypt de "password123"

-- Utilisateurs
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, actif, doit_changer_mdp) VALUES
('Admin', 'Système', 'admin@univ.sn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'administrateur', 1, 0),
('Sow', 'Fatou', 'secretaire@univ.sn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'secretaire_pedagogique', 1, 0),
('Diop', 'Mamadou', 'prof@univ.sn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'enseignant', 1, 0),
('Ndiaye', 'Aïssatou', 'etudiant@univ.sn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'etudiant', 1, 0),
('Fall', 'Ousmane', 'responsable@univ.sn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'responsable_pedagogique', 1, 0),
('Kane', 'Awa', 'awa.kane@univ.sn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'etudiant', 1, 1),
('Diallo', 'Ibrahima', 'ibrahima.diallo@univ.sn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'enseignant', 1, 0),
('Balde', 'Mariana', 'mariana.balde@univ.sn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'etudiant', 1, 0),
('Thiaw', 'Boubacar', 'boubacar.thiaw@univ.sn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'enseignant', 1, 0);

-- Salles
INSERT INTO salles (nom, capacite, localisation, equipements, actif) VALUES
('Amphi A', 200, 'Bâtiment Principal - RDC', 'Vidéoprojecteur, Tableau blanc, Climatisation', 1),
('Salle 101', 50, 'Bâtiment A - 1er étage', 'Vidéoprojecteur, Tableau interactif', 1),
('Salle 102', 30, 'Bâtiment A - 1er étage', 'Tableau blanc', 1),
('Salle 201', 80, 'Bâtiment B - 2ème étage', 'Vidéoprojecteur, Climatisation', 1);

-- Soutenance exemple
INSERT INTO soutenances (etudiant_id, directeur_id, titre, filiere, type, date, heure, salle_id, statut) VALUES
(4, 3, 'Développement d\'une application web de gestion des soutenances', 'Master Informatique', 'master', DATE_ADD(CURDATE(), INTERVAL 7 DAY), '09:00:00', 1, 'confirmee');