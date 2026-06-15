<?php
// Contrôleur Enseignant

require_once __DIR__ . '/../models/Soutenance.php';
require_once __DIR__ . '/../models/Jury.php';
require_once __DIR__ . '/../includes/audit.php';
require_once __DIR__ . '/../includes/fonctions.php';

class EnseignantController {
    private $soutenanceModel;
    private $juryModel;
    
    public function __construct() {
        $this->soutenanceModel = new Soutenance();
        $this->juryModel = new Jury();
    }
    
    /**
     * Récupère les soutenances où l'enseignant est directeur
     */
    public function getSoutenancesDirigees($enseignantId) {
        return $this->soutenanceModel->getByEnseignant($enseignantId);
    }
    
    /**
     * Récupère les participations jury
     */
    public function getParticipationsJury($enseignantId) {
        return $this->juryModel->getByEnseignant($enseignantId);
    }
    
    /**
     * Confirme la participation au jury
     */
    public function confirmerParticipation($juryId, $statut) {
        if (!in_array($statut, ['confirme', 'refuse'])) {
            return ['success' => false, 'message' => 'Statut invalide'];
        }
        
        $result = $this->juryModel->confirmParticipations($juryId, $statut);
        
        if ($result) {
            logAudit($_SESSION['user_id'], 'confirmation_jury', "ID: $juryId, Statut: $statut");
            return ['success' => true];
        }
        
        return ['success' => false];
    }
    
    /**
     * Déclare une indisponibilité
     */
    public function declareIndisponibilite($enseignantId, $date, $creneau, $motif = null) {
        $pdo = Database::getConnection();
        
        // Vérifier les doublons
        $check = $pdo->prepare("SELECT id FROM indisponibilites 
                                WHERE enseignant_id = :enseignant_id AND date = :date AND creneau = :creneau");
        $check->execute([
            ':enseignant_id' => $enseignantId,
            ':date' => $date,
            ':creneau' => $creneau
        ]);
        
        if ($check->fetch()) {
            return ['success' => false, 'message' => 'Cette indisponibilité existe déjà'];
        }
        
        $sql = "INSERT INTO indisponibilites (enseignant_id, date, creneau, motif) 
                VALUES (:enseignant_id, :date, :creneau, :motif)";
        
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ':enseignant_id' => $enseignantId,
            ':date' => $date,
            ':creneau' => $creneau,
            ':motif' => $motif
        ]);
        
        if ($result) {
            logAudit($enseignantId, 'declaration_indisponibilite', "Date: $date, Créneau: $creneau");
            return ['success' => true];
        }
        
        return ['success' => false];
    }
    
    /**
     * Récupère les indisponibilités d'un enseignant
     */
    public function getIndisponibilites($enseignantId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM indisponibilites WHERE enseignant_id = :id ORDER BY date DESC");
        $stmt->execute([':id' => $enseignantId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Supprime une indisponibilité
     */
    public function deleteIndisponibilite($id, $enseignantId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM indisponibilites WHERE id = :id AND enseignant_id = :enseignant_id");
        $result = $stmt->execute([':id' => $id, ':enseignant_id' => $enseignantId]);
        
        if ($result) {
            logAudit($enseignantId, 'suppression_indisponibilite', "ID: $id");
        }
        
        return $result;
    }
}
?>