<?php
// Contrôleur Authentification

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/audit.php';
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    /**
     * Connecte un utilisateur
     */
    public function login($email, $password, $remember = false) {
        $user = $this->userModel->getByEmail($email);
        
        if (!$user) {
            logAudit(0, 'echec_connexion', "Email inexistant: $email");
            return ['success' => false, 'message' => 'Email ou mot de passe incorrect'];
        }
        
        if (!password_verify($password, $user['mot_de_passe'])) {
            logAudit($user['id'], 'echec_connexion', 'Mot de passe incorrect');
            return ['success' => false, 'message' => 'Email ou mot de passe incorrect'];
        }
        
        if ($user['actif'] == 0) {
            logAudit($user['id'], 'tentative_connexion_compte_inactif');
            return ['success' => false, 'message' => 'Votre compte a été désactivé. Contactez l\'administrateur.'];
        }
        
        // Créer la session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['doit_changer_mdp'] = (bool)$user['doit_changer_mdp'];
        $_SESSION['last_activity'] = time();
        
        // Cookie remember me
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $hashedToken = password_hash($token, PASSWORD_DEFAULT);
            
            setcookie('remember_token', $token, time() + 30 * 86400, '/');
            
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("UPDATE utilisateurs SET remember_token = :token WHERE id = :id");
            $stmt->execute([':token' => $hashedToken, ':id' => $user['id']]);
        }
        
        logAudit($user['id'], 'connexion_reussie');
        
        return [
            'success' => true,
            'doit_changer_mdp' => (bool)$user['doit_changer_mdp'],
            'role' => $user['role']
        ];
    }
    
    /**
     * Déconnecte un utilisateur
     */
    public function logout() {
        if (isset($_SESSION['user_id'])) {
            logAudit($_SESSION['user_id'], 'deconnexion');
        }
        
        // Supprimer le cookie remember_token
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/');
        }
        
        $_SESSION = array();
        session_destroy();
        
        return ['success' => true];
    }
    
    /**
     * Change le mot de passe
     */
    public function changePassword($userId, $currentPassword, $newPassword) {
        $user = $this->userModel->getById($userId);
        
        if (!$user) {
            return ['success' => false, 'message' => 'Utilisateur non trouvé'];
        }
        
        if (!password_verify($currentPassword, $user['mot_de_passe'])) {
            logAudit($userId, 'echec_changement_mdp', 'Mot de passe actuel incorrect');
            return ['success' => false, 'message' => 'Mot de passe actuel incorrect'];
        }
        
        // Validation du nouveau mot de passe
        if (strlen($newPassword) < 8) {
            return ['success' => false, 'message' => 'Le mot de passe doit contenir au moins 8 caractères'];
        }
        if (!preg_match('/[A-Z]/', $newPassword)) {
            return ['success' => false, 'message' => 'Le mot de passe doit contenir au moins une majuscule'];
        }
        if (!preg_match('/[a-z]/', $newPassword)) {
            return ['success' => false, 'message' => 'Le mot de passe doit contenir au moins une minuscule'];
        }
        if (!preg_match('/[0-9]/', $newPassword)) {
            return ['success' => false, 'message' => 'Le mot de passe doit contenir au moins un chiffre'];
        }
        
        $result = $this->userModel->changePassword($userId, $newPassword);
        
        if ($result) {
            logAudit($userId, 'changement_mdp_reussi');
            $_SESSION['doit_changer_mdp'] = false;
            return ['success' => true, 'message' => 'Mot de passe modifié avec succès'];
        }
        
        return ['success' => false, 'message' => 'Erreur lors du changement de mot de passe'];
    }
    
    /**
     * Vérifie le token remember me
     */
    public function checkRememberToken() {
        if (isset($_COOKIE['remember_token']) && !isset($_SESSION['user_id'])) {
            $token = $_COOKIE['remember_token'];
            
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE remember_token IS NOT NULL");
            $stmt->execute();
            $users = $stmt->fetchAll();
            
            foreach ($users as $user) {
                if (password_verify($token, $user['remember_token'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['nom'] = $user['nom'];
                    $_SESSION['prenom'] = $user['prenom'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['doit_changer_mdp'] = (bool)$user['doit_changer_mdp'];
                    $_SESSION['last_activity'] = time();
                    
                    return true;
                }
            }
        }
        return false;
    }
}
?>