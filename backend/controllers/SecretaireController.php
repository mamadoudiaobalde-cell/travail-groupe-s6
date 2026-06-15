<?php
// Contrôleur Secrétaire

require_once __DIR__ . '/../models/Soutenance.php';
require_once __DIR__ . '/../models/Salle.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../includes/audit.php';
require_once __DIR__ . '/../includes/fonctions.php';

class SecretaireController {
    private $soutenanceModel;
    private $salleModel;
    private $userModel;
    
    public function __construct() {
        $this->soutenanceModel = new Soutenance();
        $this->salleModel = new Salle();
        $this->userModel = new User();
    }
    
    /**
     * Statistiques du tableau de bord
     */
    public function getDashboardStats() {
        $pdo = Database::getConnection();
        
        // Soutenances aujourd'hui
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM soutenances WHERE date = CURDATE()");
        $stmt->execute();
        $aujourdhui = $stmt->fetchColumn();
        
        // Soutenances cette semaine
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM soutenances WHERE YEARWEEK(date) = YEARWEEK(CURDATE())");
        $stmt->execute();
        $semaine = $stmt->fetchColumn();
        
        // Soutenances à confirmer
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM soutenances WHERE statut = 'planifiee'");
        $stmt->execute();
        $aConfirmer = $stmt->fetchColumn();
        
        // Salles disponibles aujourd'hui
        $sallesDispo = count($this->salleModel->getDisponibles(date('Y-m-d'), '09:00:00'));
        
        // Prochaines soutenances
        $prochaines = $this->soutenanceModel->getAvenir(10);
        
        return [
            'aujourdhui' => $aujourdhui,
            'semaine' => $semaine,
            'a_confirmer' => $aConfirmer,
            'salles_dispo' => $sallesDispo,
            'prochaines' => $prochaines
        ];
    }
    
    /**
     * Liste des étudiants
     */
    public function getEtudiants() {
        return $this->userModel->getByRole('etudiant');
    }
    
    /**
     * Liste des enseignants
     */
    public function getEnseignants() {
        return $this->userModel->getByRole('enseignant');
    }
    
    /**
     * Liste des salles
     */
    public function getSalles() {
        return $this->salleModel->getActives();
    }
    
    /**
     * Planifie une soutenance
     */
    public function planifierSoutenance($data) {
        // Validation
        if (empty($data['etudiant_id']) || empty($data['directeur_id']) || empty($data['titre'])) {
            return ['success' => false, 'message' => 'Tous les champs obligatoires doivent être remplis'];
        }
        
        $result = $this->soutenanceModel->create($data);
        
        if ($result['success']) {
            logAudit($_SESSION['user_id'], 'planification_soutenance', "ID: {$result['id']}");
        }
        
        return $result;
    }
    
    /**
     * Met à jour une soutenance
     */
    public function updateSoutenance($id, $data) {
        $result = $this->soutenanceModel->update($id, $data);
        
        if ($result['success']) {
            logAudit($_SESSION['user_id'], 'modification_soutenance', "ID: $id");
        }
        
        return $result;
    }
    
    /**
     * Confirme une soutenance
     */
    public function confirmerSoutenance($id) {
        $result = $this->soutenanceModel->changeStatut($id, 'confirmee');
        
        if ($result) {
            logAudit($_SESSION['user_id'], 'confirmation_soutenance', "ID: $id");
            return ['success' => true];
        }
        
        return ['success' => false];
    }
    
    /**
     * Annule une soutenance
     */
    public function annulerSoutenance($id, $motif = null) {
        $result = $this->soutenanceModel->annuler($id, $motif);
        
        if ($result) {
            logAudit($_SESSION['user_id'], 'annulation_soutenance', "ID: $id, Motif: $motif");
            return ['success' => true];
        }
        
        return ['success' => false];
    }
    
    /**
     * Vérifie la disponibilité d'une salle
     */
    public function verifierDisponibiliteSalle($salleId, $date, $heure, $excludeId = null) {
        return isSalleDisponible($salleId, $date, $heure, $excludeId);
    }
    
    /**
     * Récupère les salles disponibles
     */
    public function getSallesDisponibles($date, $heure, $capaciteMin = 0) {
        return $this->salleModel->getDisponibles($date, $heure, $capaciteMin);
    }
}
?>