<?php
// Contrôleur Étudiant

require_once __DIR__ . '/../models/Soutenance.php';
require_once __DIR__ . '/../models/Jury.php';
require_once __DIR__ . '/../models/Document.php';

class EtudiantController {
    private $soutenanceModel;
    private $juryModel;
    private $documentModel;
    
    public function __construct() {
        $this->soutenanceModel = new Soutenance();
        $this->juryModel = new Jury();
        $this->documentModel = new Document();
    }
    
    /**
     * Récupère les soutenances de l'étudiant
     */
    public function getMesSoutenances($etudiantId) {
        return $this->soutenanceModel->getByEtudiant($etudiantId);
    }
    
    /**
     * Récupère la prochaine soutenance
     */
    public function getProchaineSoutenance($etudiantId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT s.*, salle.nom as salle_nom, salle.localisation
            FROM soutenances s
            LEFT JOIN salles salle ON s.salle_id = salle.id
            WHERE s.etudiant_id = :id 
            AND s.date >= CURDATE()
            AND s.statut IN ('planifiee', 'confirmee')
            ORDER BY s.date ASC
            LIMIT 1
        ");
        $stmt->execute([':id' => $etudiantId]);
        return $stmt->fetch();
    }
    
    /**
     * Récupère les membres du jury pour une soutenance
     */
    public function getJurySoutenance($soutenanceId) {
        return $this->juryModel->getBySoutenance($soutenanceId);
    }
    
    /**
     * Récupère les documents de l'étudiant
     */
    public function getMesDocuments($etudiantId) {
        return $this->documentModel->getByEtudiant($etudiantId);
    }
    
    /**
     * Vérifie si l'étudiant a une soutenance planifiée
     */
    public function hasSoutenancePlanifiee($etudiantId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM soutenances 
            WHERE etudiant_id = :id AND statut IN ('planifiee', 'confirmee')
        ");
        $stmt->execute([':id' => $etudiantId]);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Récupère les résultats de l'étudiant
     */
    public function getResultats($etudiantId) {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT s.titre, s.date, pv.note, pv.mention, pv.observations
            FROM soutenances s
            JOIN pv ON pv.soutenance_id = s.id
            WHERE s.etudiant_id = :id AND s.statut = 'realisee'
            ORDER BY s.date DESC
        ");
        $stmt->execute([':id' => $etudiantId]);
        return $stmt->fetchAll();
    }
}
?>