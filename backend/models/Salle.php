<?php
// Modèle Salle

require_once __DIR__ . '/../config/database.php';

class Salle {
    private $pdo;
    
    public function __construct() {
        $this->pdo = Database::getConnection();
    }
    
    /**
     * Récupère toutes les salles
     */
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM salles ORDER BY nom");
        return $stmt->fetchAll();
    }
    
    /**
     * Récupère les salles actives
     */
    public function getActives() {
        $stmt = $this->pdo->query("SELECT * FROM salles WHERE actif = 1 ORDER BY nom");
        return $stmt->fetchAll();
    }
    
    /**
     * Récupère une salle par son ID
     */
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM salles WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Crée une nouvelle salle
     */
    public function create($data) {
        $sql = "INSERT INTO salles (nom, capacite, localisation, equipements, actif) 
                VALUES (:nom, :capacite, :localisation, :equipements, :actif)";
        
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            ':nom' => $data['nom'],
            ':capacite' => $data['capacite'],
            ':localisation' => $data['localisation'] ?? null,
            ':equipements' => $data['equipements'] ?? null,
            ':actif' => $data['actif'] ?? 1
        ]);
        
        return $result ? $this->pdo->lastInsertId() : false;
    }
    
    /**
     * Met à jour une salle
     */
    public function update($id, $data) {
        $sql = "UPDATE salles SET 
                nom = :nom, 
                capacite = :capacite, 
                localisation = :localisation, 
                equipements = :equipements, 
                actif = :actif 
                WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nom' => $data['nom'],
            ':capacite' => $data['capacite'],
            ':localisation' => $data['localisation'] ?? null,
            ':equipements' => $data['equipements'] ?? null,
            ':actif' => $data['actif'] ?? 1,
            ':id' => $id
        ]);
    }
    
    /**
     * Supprime une salle (si non utilisée)
     */
    public function delete($id) {
        // Vérifier si la salle est utilisée
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM soutenances WHERE salle_id = :id");
        $stmt->execute([':id' => $id]);
        $used = $stmt->fetchColumn();
        
        if ($used > 0) {
            return ['success' => false, 'message' => 'Cette salle est utilisée dans des soutenances'];
        }
        
        $stmt = $this->pdo->prepare("DELETE FROM salles WHERE id = :id");
        $result = $stmt->execute([':id' => $id]);
        
        return ['success' => $result, 'message' => $result ? 'Salle supprimée' : 'Erreur lors de la suppression'];
    }
    
    /**
     * Récupère les salles disponibles à une date et heure
     */
    public function getDisponibles($date, $heure, $capaciteMin = 0) {
        $sql = "SELECT s.* FROM salles s 
                WHERE s.actif = 1 
                AND s.capacite >= :capacite
                AND NOT EXISTS (
                    SELECT 1 FROM soutenances so 
                    WHERE so.salle_id = s.id 
                    AND so.date = :date 
                    AND so.heure = :heure
                )
                ORDER BY s.capacite ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':date' => $date,
            ':heure' => $heure,
            ':capacite' => $capaciteMin
        ]);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Compte le nombre de salles
     */
    public function count($actif = null) {
        $sql = "SELECT COUNT(*) FROM salles";
        if ($actif !== null) {
            $sql .= " WHERE actif = " . ($actif ? 1 : 0);
        }
        return $this->pdo->query($sql)->fetchColumn();
    }
}
?>