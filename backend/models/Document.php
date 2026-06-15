<?php
// Modèle Document

require_once __DIR__ . '/../config/database.php';

class Document {
    private $pdo;
    
    public function __construct() {
        $this->pdo = Database::getConnection();
    }
    
    /**
     * Récupère les documents d'un étudiant
     */
    public function getByEtudiant($etudiantId) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM documents 
            WHERE etudiant_id = :etudiant_id 
            ORDER BY created_at DESC
        ");
        $stmt->execute([':etudiant_id' => $etudiantId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Récupère les documents d'une soutenance
     */
    public function getBySoutenance($soutenanceId) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM documents 
            WHERE soutenance_id = :soutenance_id 
            ORDER BY created_at DESC
        ");
        $stmt->execute([':soutenance_id' => $soutenanceId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Crée un document
     */
    public function create($data) {
        $sql = "INSERT INTO documents (soutenance_id, etudiant_id, type, nom_fichier, chemin_fichier, hash_sha256, taille) 
                VALUES (:soutenance_id, :etudiant_id, :type, :nom_fichier, :chemin_fichier, :hash, :taille)";
        
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            ':soutenance_id' => $data['soutenance_id'] ?? null,
            ':etudiant_id' => $data['etudiant_id'],
            ':type' => $data['type'],
            ':nom_fichier' => $data['nom_fichier'],
            ':chemin_fichier' => $data['chemin_fichier'],
            ':hash' => $data['hash_sha256'],
            ':taille' => $data['taille'] ?? null
        ]);
        
        return $result ? $this->pdo->lastInsertId() : false;
    }
    
    /**
     * Supprime un document
     */
    public function delete($id) {
        // Récupérer le chemin du fichier
        $stmt = $this->pdo->prepare("SELECT chemin_fichier FROM documents WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $doc = $stmt->fetch();
        
        if ($doc && file_exists($doc['chemin_fichier'])) {
            unlink($doc['chemin_fichier']);
        }
        
        $stmt = $this->pdo->prepare("DELETE FROM documents WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>