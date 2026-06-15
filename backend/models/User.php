<?php
// Modèle Utilisateur

require_once __DIR__ . '/../config/database.php';

class User {
    private $pdo;
    
    public function __construct() {
        $this->pdo = Database::getConnection();
    }
    
    /**
     * Récupère tous les utilisateurs
     */
    public function getAll($limit = null, $offset = 0) {
        $sql = "SELECT * FROM utilisateurs ORDER BY created_at DESC";
        if ($limit) {
            $sql .= " LIMIT $offset, $limit";
        }
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Récupère un utilisateur par son ID
     */
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Récupère un utilisateur par son email
     */
    public function getByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }
    
    /**
     * Récupère les utilisateurs par rôle
     */
    public function getByRole($role) {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE role = :role AND actif = 1 ORDER BY nom");
        $stmt->execute([':role' => $role]);
        return $stmt->fetchAll();
    }
    
    /**
     * Crée un nouvel utilisateur
     */
    public function create($data) {
        $passwordHash = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, doit_changer_mdp) 
                VALUES (:nom, :prenom, :email, :mdp, :role, 1)";
        
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':email' => $data['email'],
            ':mdp' => $passwordHash,
            ':role' => $data['role']
        ]);
        
        return $result ? $this->pdo->lastInsertId() : false;
    }
    
    /**
     * Met à jour un utilisateur
     */
    public function update($id, $data) {
        $sql = "UPDATE utilisateurs SET 
                nom = :nom, 
                prenom = :prenom, 
                email = :email, 
                role = :role 
                WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':email' => $data['email'],
            ':role' => $data['role'],
            ':id' => $id
        ]);
    }
    
    /**
     * Change le mot de passe d'un utilisateur
     */
    public function changePassword($id, $newPassword) {
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $sql = "UPDATE utilisateurs SET mot_de_passe = :mdp, doit_changer_mdp = 0 WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':mdp' => $passwordHash, ':id' => $id]);
    }
    
    /**
     * Réinitialise le mot de passe (force le changement)
     */
    public function resetPassword($id) {
        $defaultPassword = 'password123';
        $passwordHash = password_hash($defaultPassword, PASSWORD_DEFAULT);
        
        $sql = "UPDATE utilisateurs SET mot_de_passe = :mdp, doit_changer_mdp = 1 WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':mdp' => $passwordHash, ':id' => $id]);
    }
    
    /**
     * Active ou désactive un utilisateur
     */
    public function toggleActive($id) {
        $sql = "UPDATE utilisateurs SET actif = NOT actif WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    /**
     * Supprime un utilisateur
     */
    public function delete($id) {
        $sql = "DELETE FROM utilisateurs WHERE id = :id AND role != 'administrateur'";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    /**
     * Compte le nombre d'utilisateurs
     */
    public function count($actif = null) {
        $sql = "SELECT COUNT(*) FROM utilisateurs";
        if ($actif !== null) {
            $sql .= " WHERE actif = " . ($actif ? 1 : 0);
        }
        return $this->pdo->query($sql)->fetchColumn();
    }
    
    /**
     * Statistiques par rôle
     */
    public function getStatsByRole() {
        $sql = "SELECT role, COUNT(*) as count, SUM(actif) as actifs 
                FROM utilisateurs 
                GROUP BY role";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}
?>