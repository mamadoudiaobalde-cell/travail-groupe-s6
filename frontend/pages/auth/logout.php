<?php
// frontend/pages/auth/logout.php
// Déconnexion de l'utilisateur

// Démarrer la session si nécessaire
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclure les dépendances
require_once __DIR__ . '/../../../backend/includes/auth.php';
require_once __DIR__ . '/../../../backend/includes/audit.php';
require_once __DIR__ . '/../../../backend/includes/fonctions.php';

/**
 * Fonction de déconnexion
 */
function logout() {
    // Journaliser la déconnexion si utilisateur connecté
    if (isset($_SESSION['user_id'])) {
        logAudit($_SESSION['user_id'], 'deconnexion', 'Déconnexion volontaire');
    }
    
    // Supprimer le cookie "Se souvenir de moi"
    if (isset($_COOKIE['remember_token'])) {
        setcookie('remember_token', '', time() - 3600, '/');
        
        // Supprimer le token en base de données
        if (isset($_SESSION['user_id'])) {
            try {
                $pdo = Database::getConnection();
                $stmt = $pdo->prepare("UPDATE utilisateurs SET remember_token = NULL WHERE id = :id");
                $stmt->execute([':id' => $_SESSION['user_id']]);
            } catch (Exception $e) {
                // Ignorer l'erreur
            }
        }
    }
    
    // Vider toutes les variables de session
    $_SESSION = array();
    
    // Détruire la session
    session_destroy();
}

/**
 * Fonction pour ajouter un message flash
 */
function flash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Fonction de redirection
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

// ========== EXÉCUTION ==========

// Exécuter la déconnexion
logout();

// Ajouter un message de succès (optionnel)
flash('success', 'Vous avez été déconnecté avec succès.');

// Rediriger vers la page de connexion
redirect('/travail-groupe-s6/frontend/pages/auth/login.php');
?>