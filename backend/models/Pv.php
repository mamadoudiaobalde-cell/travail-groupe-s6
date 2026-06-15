<?php
// backend/models/Pv.php
// Modèle pour la gestion des procès-verbaux (PV)

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/fonctions.php';

class Pv {
    private $pdo;
    
    public function __construct() {
        $this->pdo = Database::getConnection();
    }
    
    /**
     * Récupère le PV d'une soutenance
     * @param int $soutenanceId
     * @return array|false
     */
    public function getBySoutenance($soutenanceId) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, s.titre, s.date, s.heure, s.filiere, s.type,
                   e.nom as etudiant_nom, e.prenom as etudiant_prenom,
                   d.nom as directeur_nom, d.prenom as directeur_prenom
            FROM pv p
            JOIN soutenances s ON p.soutenance_id = s.id
            JOIN utilisateurs e ON s.etudiant_id = e.id
            JOIN utilisateurs d ON s.directeur_id = d.id
            WHERE p.soutenance_id = :soutenance_id
        ");
        $stmt->execute([':soutenance_id' => $soutenanceId]);
        return $stmt->fetch();
    }
    
    /**
     * Récupère le PV par son ID
     * @param int $id
     * @return array|false
     */
    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, s.titre, s.date, s.heure
            FROM pv p
            JOIN soutenances s ON p.soutenance_id = s.id
            WHERE p.id = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Récupère tous les PV avec filtres
     * @param array $filters
     * @return array
     */
    public function getAll($filters = []) {
        $sql = "
            SELECT p.*, 
                   s.titre, s.date, s.filiere, s.type,
                   e.nom as etudiant_nom, e.prenom as etudiant_prenom
            FROM pv p
            JOIN soutenances s ON p.soutenance_id = s.id
            JOIN utilisateurs e ON s.etudiant_id = e.id
            WHERE 1=1
        ";
        $params = [];
        
        // Filtre par statut
        if (isset($filters['status'])) {
            $sql .= " AND p.status = :status";
            $params[':status'] = $filters['status'];
        }
        
        // Filtre par filière
        if (isset($filters['filiere'])) {
            $sql .= " AND s.filiere = :filiere";
            $params[':filiere'] = $filters['filiere'];
        }
        
        // Filtre par année
        if (isset($filters['annee'])) {
            $sql .= " AND YEAR(s.date) = :annee";
            $params[':annee'] = $filters['annee'];
        }
        
        $sql .= " ORDER BY s.date DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * Crée ou met à jour un PV
     * @param int $soutenanceId
     * @param array $data
     * @return array
     */
    public function save($soutenanceId, $data) {
        $existing = $this->getBySoutenance($soutenanceId);
        
        // Calcul de la mention en fonction de la note
        $mention = $this->calculerMention($data['note'] ?? null);
        
        if ($existing) {
            // Mise à jour
            $sql = "UPDATE pv SET 
                    note = :note, 
                    mention = :mention, 
                    observations = :observations, 
                    status = :status 
                    WHERE soutenance_id = :soutenance_id";
        } else {
            // Création
            $sql = "INSERT INTO pv (soutenance_id, note, mention, observations, status) 
                    VALUES (:soutenance_id, :note, :mention, :observations, :status)";
        }
        
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            ':soutenance_id' => $soutenanceId,
            ':note' => $data['note'] ?? null,
            ':mention' => $mention,
            ':observations' => $data['observations'] ?? null,
            ':status' => $data['status'] ?? 'brouillon'
        ]);
        
        if ($result) {
            // Envoyer notification à l'étudiant si note saisie
            if (isset($data['note']) && $data['note'] !== null) {
                $this->notifierEtudiant($soutenanceId, $data['note'], $mention);
            }
            return ['success' => true, 'message' => 'PV enregistré avec succès'];
        }
        
        return ['success' => false, 'message' => 'Erreur lors de l\'enregistrement'];
    }
    
    /**
     * Calcule la mention en fonction de la note
     * @param float|null $note
     * @return string|null
     */
    private function calculerMention($note) {
        if ($note === null) return null;
        
        if ($note >= 16) return 'Excellent';
        if ($note >= 14) return 'Tres bien';
        if ($note >= 12) return 'Bien';
        if ($note >= 10) return 'Assez bien';
        return 'Passable';
    }
    
    /**
     * Envoie une notification à l'étudiant
     * @param int $soutenanceId
     * @param float $note
     * @param string $mention
     */
    private function notifierEtudiant($soutenanceId, $note, $mention) {
        // Récupérer l'étudiant
        $stmt = $this->pdo->prepare("
            SELECT u.id, u.nom, u.prenom, u.email
            FROM soutenances s
            JOIN utilisateurs u ON s.etudiant_id = u.id
            WHERE s.id = :soutenance_id
        ");
        $stmt->execute([':soutenance_id' => $soutenanceId]);
        $etudiant = $stmt->fetch();
        
        if ($etudiant) {
            $message = "Votre résultat est disponible : Note: {$note}/20 - Mention: {$mention}";
            sendNotification($etudiant['id'], 'Résultat de soutenance', $message);
        }
    }
    
    /**
     * Change le statut du PV
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function changeStatus($id, $status) {
        $validStatus = ['brouillon', 'en_validation', 'valide', 'signe', 'archive'];
        if (!in_array($status, $validStatus)) {
            return false;
        }
        
        $sql = "UPDATE pv SET status = :status WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }
    
    /**
     * Valide le PV (le rend officiel)
     * @param int $id
     * @return bool
     */
    public function validate($id) {
        $sql = "UPDATE pv SET status = 'valide' WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    /**
     * Signe le PV
     * @param int $id
     * @return bool
     */
    public function sign($id) {
        $sql = "UPDATE pv SET status = 'signe', signe_le = CURDATE() WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    /**
     * Archive le PV (lecture seule)
     * @param int $id
     * @return bool
     */
    public function archive($id) {
        $sql = "UPDATE pv SET status = 'archive' WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    /**
     * Enregistre le chemin du fichier PDF
     * @param int $soutenanceId
     * @param string $cheminFichier
     * @return bool
     */
    public function savePdfPath($soutenanceId, $cheminFichier) {
        $sql = "UPDATE pv SET fichier_pdf = :fichier WHERE soutenance_id = :soutenance_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':fichier' => $cheminFichier,
            ':soutenance_id' => $soutenanceId
        ]);
    }
    
    /**
     * Vérifie si un PV existe pour une soutenance
     * @param int $soutenanceId
     * @return bool
     */
    public function exists($soutenanceId) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM pv WHERE soutenance_id = :soutenance_id");
        $stmt->execute([':soutenance_id' => $soutenanceId]);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Supprime un PV
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM pv WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
    
    /**
     * Statistiques des PV
     * @return array
     */
    public function getStats() {
        $stats = [];
        
        // Nombre total de PV
        $stats['total'] = $this->pdo->query("SELECT COUNT(*) FROM pv")->fetchColumn();
        
        // Par statut
        $stmt = $this->pdo->query("
            SELECT status, COUNT(*) as count 
            FROM pv 
            GROUP BY status
        ");
        $stats['par_status'] = $stmt->fetchAll();
        
        // Par mention
        $stmt = $this->pdo->query("
            SELECT mention, COUNT(*) as count 
            FROM pv 
            WHERE mention IS NOT NULL
            GROUP BY mention
            ORDER BY FIELD(mention, 'Excellent', 'Tres bien', 'Bien', 'Assez bien', 'Passable')
        ");
        $stats['par_mention'] = $stmt->fetchAll();
        
        // Note moyenne
        $stats['note_moyenne'] = $this->pdo->query("SELECT AVG(note) FROM pv WHERE note IS NOT NULL")->fetchColumn();
        
        // Taux de réussite
        $stmt = $this->pdo->query("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN note >= 10 THEN 1 ELSE 0 END) as reussi
            FROM pv
            WHERE note IS NOT NULL
        ");
        $reussite = $stmt->fetch();
        $stats['taux_reussite'] = $reussite['total'] > 0 
            ? round(($reussite['reussi'] / $reussite['total']) * 100, 1) 
            : 0;
        
        return $stats;
    }
    
    /**
     * Récupère les PV récents
     * @param int $limit
     * @return array
     */
    public function getRecents($limit = 10) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, 
                   s.titre, s.date,
                   e.nom as etudiant_nom, e.prenom as etudiant_prenom
            FROM pv p
            JOIN soutenances s ON p.soutenance_id = s.id
            JOIN utilisateurs e ON s.etudiant_id = e.id
            ORDER BY s.date DESC
            LIMIT :limit
        ");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Récupère les PV par filière
     * @param string $filiere
     * @return array
     */
    public function getByFiliere($filiere) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, 
                   s.titre, s.date,
                   e.nom as etudiant_nom, e.prenom as etudiant_prenom
            FROM pv p
            JOIN soutenances s ON p.soutenance_id = s.id
            JOIN utilisateurs e ON s.etudiant_id = e.id
            WHERE s.filiere = :filiere
            ORDER BY s.date DESC
        ");
        $stmt->execute([':filiere' => $filiere]);
        return $stmt->fetchAll();
    }
    
    /**
     * Récupère les PV par année
     * @param int $annee
     * @return array
     */
    public function getByAnnee($annee) {
        $stmt = $this->pdo->prepare("
            SELECT p.*, 
                   s.titre, s.date,
                   e.nom as etudiant_nom, e.prenom as etudiant_prenom
            FROM pv p
            JOIN soutenances s ON p.soutenance_id = s.id
            JOIN utilisateurs e ON s.etudiant_id = e.id
            WHERE YEAR(s.date) = :annee
            ORDER BY s.date DESC
        ");
        $stmt->execute([':annee' => $annee]);
        return $stmt->fetchAll();
    }
}
?>