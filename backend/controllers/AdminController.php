<?php
// Contrôleur Administrateur

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Salle.php';
require_once __DIR__ . '/../includes/audit.php';

class AdminController {
    private $userModel;
    private $salleModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->salleModel = new Salle();
    }
    
    /**
     * Statistiques du tableau de bord
     */
    public function getDashboardStats() {
        return [
            'total_users' => $this->userModel->count(true),
            'total_salles' => $this->salleModel->count(true),
            'users_by_role' => $this->userModel->getStatsByRole(),
            'recent_audits' => getAuditLog(null, 10)
        ];
    }
    
    /**
     * Liste des utilisateurs
     */
    public function getUtilisateurs() {
        return $this->userModel->getAll();
    }
    
    /**
     * Crée un utilisateur
     */
    public function createUtilisateur($data) {
        // Vérifier si l'email existe déjà
        $existing = $this->userModel->getByEmail($data['email']);
        if ($existing) {
            return ['success' => false, 'message' => 'Cet email est déjà utilisé'];
        }
        
        $data['mot_de_passe'] = 'password123'; // Mot de passe par défaut
        
        $id = $this->userModel->create($data);
        
        if ($id) {
            logAudit($_SESSION['user_id'], 'creation_utilisateur', "Email: {$data['email']}, Rôle: {$data['role']}");
            return ['success' => true, 'id' => $id, 'message' => 'Utilisateur créé avec succès. Mot de passe temporaire: password123'];
        }
        
        return ['success' => false, 'message' => 'Erreur lors de la création'];
    }
    
    /**
     * Met à jour un utilisateur
     */
    public function updateUtilisateur($id, $data) {
        $result = $this->userModel->update($id, $data);
        
        if ($result) {
            logAudit($_SESSION['user_id'], 'modification_utilisateur', "ID: $id");
            return ['success' => true, 'message' => 'Utilisateur modifié'];
        }
        
        return ['success' => false, 'message' => 'Erreur lors de la modification'];
    }
    
    /**
     * Active/Désactive un utilisateur
     */
    public function toggleUtilisateur($id) {
        $result = $this->userModel->toggleActive($id);
        
        if ($result) {
            logAudit($_SESSION['user_id'], 'toggle_utilisateur', "ID: $id");
            return ['success' => true];
        }
        
        return ['success' => false];
    }
    
    /**
     * Réinitialise le mot de passe
     */
    public function resetPassword($id) {
        $result = $this->userModel->resetPassword($id);
        
        if ($result) {
            logAudit($_SESSION['user_id'], 'reset_mdp', "ID: $id");
            return ['success' => true, 'message' => 'Mot de passe réinitialisé à "password123"'];
        }
        
        return ['success' => false];
    }
    
    /**
     * Supprime un utilisateur
     */
    public function deleteUtilisateur($id) {
        $user = $this->userModel->getById($id);
        if ($user && $user['role'] === 'administrateur') {
            return ['success' => false, 'message' => 'Impossible de supprimer un administrateur'];
        }
        
        $result = $this->userModel->delete($id);
        
        if ($result) {
            logAudit($_SESSION['user_id'], 'suppression_utilisateur', "ID: $id");
            return ['success' => true];
        }
        
        return ['success' => false];
    }
    
    /**
     * Liste des salles
     */
    public function getSalles() {
        return $this->salleModel->getAll();
    }
    
    /**
     * Crée une salle
     */
    public function createSalle($data) {
        $id = $this->salleModel->create($data);
        
        if ($id) {
            logAudit($_SESSION['user_id'], 'creation_salle', "Nom: {$data['nom']}");
            return ['success' => true, 'id' => $id];
        }
        
        return ['success' => false];
    }
    
    /**
     * Met à jour une salle
     */
    public function updateSalle($id, $data) {
        $result = $this->salleModel->update($id, $data);
        
        if ($result) {
            logAudit($_SESSION['user_id'], 'modification_salle', "ID: $id");
            return ['success' => true];
        }
        
        return ['success' => false];
    }
    
    /**
     * Supprime une salle
     */
    public function deleteSalle($id) {
        $result = $this->salleModel->delete($id);
        return $result;
    }
}
?>