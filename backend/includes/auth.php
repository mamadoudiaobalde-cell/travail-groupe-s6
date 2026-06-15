<?php
// Middleware d'authentification

session_start();

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/audit.php';

/**
 * Vérifie que l'utilisateur est authentifié
 */
function requireAuth() {
    if (!isset($_SESSION['user_id'])) {
        logAudit(0, 'tentative_acces_sans_session', $_SERVER['REQUEST_URI']);
        header('Location: /travail-groupe-s6/frontend/pages/auth/login.php');
        exit();
    }
    
    // Vérifier l'expiration de session
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
        session_destroy();
        header('Location: /travail-groupe-s6/frontend/pages/auth/login.php?expired=1');
        exit();
    }
    
    $_SESSION['last_activity'] = time();
}

/**
 * Vérifie que l'utilisateur a le bon rôle
 * @param string|array $roles Rôle(s) autorisé(s)
 */
function requireRole($roles) {
    requireAuth();
    
    if (!isset($_SESSION['role'])) {
        header('Location: /travail-groupe-s6/frontend/pages/auth/login.php');
        exit();
    }
    
    if (!in_array($_SESSION['role'], (array)$roles)) {
        logAudit($_SESSION['user_id'], 'acces_non_autorise', $_SERVER['REQUEST_URI']);
        header('HTTP/1.0 403 Forbidden');
        die('<div style="text-align:center; margin-top:50px; font-family:Arial">
             <h1>⛔ Accès interdit</h1>
             <p>Vous n\'avez pas les droits nécessaires pour accéder à cette page.</p>
             <a href="/travail-groupe-s6/frontend/pages/auth/logout.php">Retour à l\'accueil</a>
             </div>');
    }
}

/**
 * Redirige l'utilisateur vers son dashboard
 */
function redirectToDashboard() {
    $route = DASHBOARD_ROUTES[$_SESSION['role']] ?? '/frontend/pages/auth/login.php';
    header("Location: $route");
    exit();
}

/**
 * Vérifie si l'utilisateur est administrateur
 */
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'administrateur';
}

/**
 * Vérifie si l'utilisateur est secrétaire
 */
function isSecretaire() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'secretaire_pedagogique';
}

/**
 * Vérifie si l'utilisateur est enseignant
 */
function isEnseignant() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'enseignant';
}

/**
 * Vérifie si l'utilisateur est étudiant
 */
function isEtudiant() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'etudiant';
}

/**
 * Récupère les informations de l'utilisateur connecté
 */
function getCurrentUser() {
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'],
        'nom' => $_SESSION['nom'],
        'prenom' => $_SESSION['prenom'],
        'email' => $_SESSION['email'],
        'role' => $_SESSION['role']
    ];
}

/**
 * Vérifie si l'utilisateur doit changer son mot de passe
 */
function doitChangerMotDePasse() {
    return isset($_SESSION['doit_changer_mdp']) && $_SESSION['doit_changer_mdp'] === true;
}
?>