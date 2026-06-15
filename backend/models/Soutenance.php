<?php
// Modèle Soutenance

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/fonctions.php';

class Soutenance {
    private $pdo;
    
    public function __construct() {
        $this->pdo = Database::getConnection();
    }
    
    /**
     * Récupère toutes les soutenances
     */
    public function getAll($orderBy = 'date DESC') {
        $stmt = $this->pdo->query("
            SELECT s.*, 
                   e.nom as etudiant_nom, e.prenom as etudiant_prenom, e.email as etudiant_email,
                   d.nom as directeur_nom, d.prenom as directeur_prenom,
                   salle.nom as salle_nom, salle.localisation
            FROM soutenances s
            JOIN utilisateurs e ON s.etudiant_id = e.id
            JOIN utilisateurs d ON s.directeur_id = d.id
            LEFT JOIN salles salle ON s.salle_id = salle.id
            ORDER BY $orderBy
        ");
        return $stmt->fetchAll();
    }
    
    /**
     * Récupère une soutenance par son ID
     */
    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT s.*, 
                   e.nom as etudiant_nom, e.prenom as etudiant_prenom, e.email as etudiant_email,
                   d.nom as directeur_nom, d.prenom as directeur_prenom,
                   salle.nom as salle_nom, salle.localisation, salle.capacite, salle.equipements
            FROM soutenances s
            JOIN utilisateurs e ON s.etudiant_id = e.id
            JOIN utilisateurs d ON s.directeur_id = d.id
            LEFT JOIN salles salle ON s.salle_id = salle.id
            WHERE s.id = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Récupère les soutenances par étudiant
     */
    public function getByEtudiant($etudiantId) {
        $stmt = $this->pdo->prepare("
            SELECT s.*, 
                   d.nom as directeur_nom, d.prenom as directeur_prenom,
                   salle.nom as salle_nom, salle.localisation,
                   pv.note, pv.mention, pv.status as pv_status
            FROM soutenances s
            JOIN utilisateurs d ON s.directeur_id = d.id
            LEFT JOIN salles salle ON s.salle_id = salle.id
            LEFT JOIN pv ON pv.soutenance_id = s.id
            WHERE s.etudiant_id = :etudiant_id
            ORDER BY s.date DESC
        ");
        $stmt->execute([':etudiant_id' => $etudiantId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Récupère les soutenances par enseignant (directeur)
     */
    public function getByEnseignant($enseignantId) {
        $stmt = $this->pdo->prepare("
            SELECT s.*, 
                   e.nom as etudiant_nom, e.prenom as etudiant_prenom,
                   salle.nom as salle_nom
            FROM soutenances s
            JOIN utilisateurs e ON s.etudiant_id = e.id
            LEFT JOIN salles salle ON s.salle_id = salle.id
            WHERE s.directeur_id = :enseignant_id
            ORDER BY s.date DESC
        ");
        $stmt->execute([':enseignant_id' => $enseignantId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Récupère les soutenances à venir
     */
    public function getAvenir($limit = 10) {
        $stmt = $this->pdo->prepare("
            SELECT s.*, 
                   e.nom as etudiant_nom, e.prenom as etudiant_prenom,
                   salle.nom as salle_nom
            FROM soutenances s
            JOIN utilisateurs e ON s.etudiant_id = e.id
            LEFT JOIN salles salle ON s.salle_id = salle.id
            WHERE s.date >= CURDATE() AND s.statut IN ('planifiee', 'confirmee')
            ORDER BY s.date ASC, s.heure ASC
            LIMIT :limit
        ");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Récupère les soutenances du jour
     */
    public function getAujourdhui() {
        $stmt = $this->pdo->prepare("
            SELECT s.*, 
                   e.nom as etudiant_nom, e.prenom as etudiant_prenom,
                   salle.nom as salle_nom
            FROM soutenances s
            JOIN utilisateurs e ON s.etudiant_id = e.id
            LEFT JOIN salles salle ON s.salle_id = salle.id
            WHERE s.date = CURDATE()
            ORDER BY s.heure ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Crée une nouvelle soutenance
     */
    public function create($data) {
        // Vérifier les conflits
        if (!$this->verifierDisponibilite($data)) {
            return ['success' => false, 'message' => 'Conflit de planning détecté'];
        }
        
        $sql = "INSERT INTO soutenances (etudiant_id, directeur_id, titre, filiere, type, date, heure, salle_id, statut) 
                VALUES (:etudiant_id, :directeur_id, :titre, :filiere, :type, :date, :heure, :salle_id, :statut)";
        
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            ':etudiant_id' => $data['etudiant_id'],
            ':directeur_id' => $data['directeur_id'],
            ':titre' => $data['titre'],
            ':filiere' => $data['filiere'],
            ':type' => $data['type'],
            ':date' => $data['date'],
            ':heure' => $data['heure'],
            ':salle_id' => $data['salle_id'] ?? null,
            ':statut' => $data['statut'] ?? 'planifiee'
        ]);
        
        if ($result) {
            $id = $this->pdo->lastInsertId();
            // Envoyer notification à l'étudiant
            sendNotification($data['etudiant_id'], 'Soutenance planifiée', "Votre soutenance a été planifiée le " . formatDate($data['date']));
            return ['success' => true, 'id' => $id];
        }
        
        return ['success' => false, 'message' => 'Erreur lors de la création'];
    }
    
    /**
     * Met à jour une soutenance
     */
    public function update($id, $data) {
        $sql = "UPDATE soutenances SET 
                etudiant_id = :etudiant_id,
                directeur_id = :directeur_id,
                titre = :titre,
                filiere = :filiere,
                type = :type,
                date = :date,
                heure = :heure,
                salle_id = :salle_id,
                statut = :statut
                WHERE id = :id";
        
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            ':etudiant_id' => $data['etudiant_id'],
            ':directeur_id' => $data['directeur_id'],
            ':titre' => $data['titre'],
            ':filiere' => $data['filiere'],
            ':type' => $data['type'],
            ':date' => $data['date'],
            ':heure' => $data['heure'],
            ':salle_id' => $data['salle_id'] ?? null,
            ':statut' => $data['statut'],
            ':id' => $id
        ]);
        
        return ['success' => $result, 'message' => $result ? 'Soutenance mise à jour' : 'Erreur lors de la mise à jour'];
    }
    
    /**
     * Change le statut d'une soutenance
     */
    public function changeStatut($id, $statut) {
        $sql = "UPDATE soutenances SET statut = :statut WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([':statut' => $statut, ':id' => $id]);
        
        if ($result && $statut === 'confirmee') {
            // Récupérer les infos pour notification
            $soutenance = $this->getById($id);
            if ($soutenance) {
                sendNotification($soutenance['etudiant_id'], 'Soutenance confirmée', "Votre soutenance du " . formatDate($soutenance['date']) . " a été confirmée");
            }
        }
        
        return $result;
    }
    
    /**
     * Annule une soutenance
     */
    public function annuler($id, $motif = null) {
        $sql = "UPDATE soutenances SET statut = 'annulee' WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    /**
     * Vérifie les disponibilités (salle, enseignant)
     */
    private function verifierDisponibilite($data) {
        // Vérifier la salle
        if (isset($data['salle_id']) && $data['salle_id']) {
            if (!isSalleDisponible($data['salle_id'], $data['date'], $data['heure'])) {
                return false;
            }
        }
        
        // Vérifier le directeur
        if (!isEnseignantDisponible($data['directeur_id'], $data['date'], $data['heure'])) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Statistiques globales
     */
    public function getStats() {
        $stats = [];
        
        // Total
        $stats['total'] = $this->pdo->query("SELECT COUNT(*) FROM soutenances")->fetchColumn();
        
        // Par statut
        $stmt = $this->pdo->query("SELECT statut, COUNT(*) as count FROM soutenances GROUP BY statut");
        $stats['par_statut'] = $stmt->fetchAll();
        
        // Par type
        $stmt = $this->pdo->query("SELECT type, COUNT(*) as count FROM soutenances GROUP BY type");
        $stats['par_type'] = $stmt->fetchAll();
        
        // Par mois
        $stmt = $this->pdo->query("
            SELECT DATE_FORMAT(date, '%Y-%m') as mois, COUNT(*) as count 
            FROM soutenances 
            WHERE date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(date, '%Y-%m')
            ORDER BY mois DESC
        ");
        $stats['par_mois'] = $stmt->fetchAll();
        
        return $stats;
    }
}
?>