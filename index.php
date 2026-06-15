<?php
/**
 * index.php
 * Point d'entrée principal du site
 * Redirige vers la page de connexion ou le dashboard approprié
 */

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Définition des routes par rôle
 * Associe chaque rôle à son dashboard
 */
$roleRoutes = [
    'administrateur' => 'frontend/pages/admin/dashboard.php',
    'secretaire_pedagogique' => 'frontend/pages/secretaire/dashboard.php',
    'enseignant' => 'frontend/pages/enseignant/dashboard.php',
    'etudiant' => 'frontend/pages/etudiant/dashboard.php',
    'responsable_pedagogique' => 'frontend/pages/responsable/dashboard.php'
];

/**
 * Cas 1 : Utilisateur déjà connecté
 * Redirection vers son dashboard selon son rôle
 */
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    $role = $_SESSION['role'];
    
    // Vérifier si le rôle existe dans les routes
    if (isset($roleRoutes[$role])) {
        $dashboard = $roleRoutes[$role];
        
        // Vérifier si le fichier dashboard existe
        $dashboardPath = __DIR__ . '/' . $dashboard;
        if (file_exists($dashboardPath)) {
            header("Location: $dashboard");
            exit();
        } else {
            // Si le dashboard n'existe pas, rediriger vers la page de connexion
            error_log("Dashboard non trouvé : $dashboardPath");
            header('Location: frontend/pages/auth/login.php');
            exit();
        }
    } else {
        // Rôle inconnu - déconnexion
        session_destroy();
        header('Location: frontend/pages/auth/login.php');
        exit();
    }
}

/**
 * Cas 2 : Cookie "Se souvenir de moi"
 * Tentative de reconnexion automatique
 */
if (isset($_COOKIE['remember_token'])) {
    require_once __DIR__ . '/backend/config/database.php';
    
    try {
        $pdo = Database::getConnection();
        $token = $_COOKIE['remember_token'];
        
        // Rechercher l'utilisateur avec ce token
        $stmt = $pdo->prepare("SELECT id, nom, prenom, email, role, doit_changer_mdp FROM utilisateurs WHERE remember_token IS NOT NULL");
        $stmt->execute();
        $users = $stmt->fetchAll();
        
        foreach ($users as $user) {
            if (password_verify($token, $user['remember_token'])) {
                // Recréer la session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nom'] = $user['nom'];
                $_SESSION['prenom'] = $user['prenom'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['doit_changer_mdp'] = (bool)$user['doit_changer_mdp'];
                $_SESSION['last_activity'] = time();
                
                // Rediriger
                if ($user['doit_changer_mdp']) {
                    header('Location: frontend/pages/auth/changer-mdp.php');
                } elseif (isset($roleRoutes[$user['role']])) {
                    header('Location: ' . $roleRoutes[$user['role']]);
                } else {
                    header('Location: frontend/pages/auth/login.php');
                }
                exit();
            }
        }
    } catch (Exception $e) {
        error_log("Erreur reconnexion automatique : " . $e->getMessage());
    }
}

/**
 * Cas 3 : Aucune session active
 * Redirection vers la page de connexion
 */
header('Location: frontend/pages/auth/login.php');
exit();
?>