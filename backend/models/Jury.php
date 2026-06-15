<?php
// backend/models/Jury.php
// Modèle pour la gestion des membres du jury

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/fonctions.php';

class Jury {
    private $pdo;
    
    public function __construct() {
        $this->pdo = Database::getConnection();
    }
    
    /**
     * Récupère les membres du jury pour une soutenance
     * @param int $soutenanceId
     * @return array
     */
    public function getBySoutenance($soutenanceId) {
        $stmt = $this->pdo->prepare("
            SELECT j.*, u.nom, u.prenom, u.email 
            FROM jury_membres j
            JOIN utilisateurs u ON j.utilisateur_id = u.id
            WHERE j.soutenance_id = :soutenance_id
            ORDER BY FIELD(j.role, 'president', 'directeur', 'rapporteur', 'membre')
        ");
        $stmt->execute([':soutenance_id' => $soutenanceId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Récupère les participations d'un enseignant
     * @param int $enseignantId
     * @return array
     */
    public function getByEnseignant($enseignantId) {
        $stmt = $this->pdo->prepare("
            SELECT j.*, 
                   s.titre, s.date, s.heure, s.statut,
                   e.nom as etudiant_nom, e.prenom as etudiant_prenom,
                   salle.nom as salle_nom
            FROM jury_membres j
            JOIN soutenances s ON j.soutenance_id = s.id
            JOIN utilisateurs e ON s.etudiant_id = e.id
            LEFT JOIN salles salle ON s.salle_id = salle.id
            WHERE j.utilisateur_id = :enseignant_id
            ORDER BY s.date DESC
        ");
        $stmt->execute([':enseignant_id' => $enseignantId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Ajoute un membre au jury
     * @param int $soutenanceId
     * @param int $utilisateurId
     * @param string $role
     * @return array
     */
    public function addMembre($soutenanceId, $utilisateurId, $role) {
        // Vérifier si déjà présent
        $check = $this->pdo->prepare("
            SELECT id FROM jury_membres 
            WHERE soutenance_id = :soutenance_id AND utilisateur_id = :utilisateur_id
        ");
        $check->execute([
            ':soutenance_id' => $soutenanceId,
            ':utilisateur_id' => $utilisateurId
        ]);
        
        if ($check->fetch()) {
            return ['success' => false, 'message' => 'Ce membre est déjà dans le jury'];
        }
        
        $sql = "INSERT INTO jury_membres (soutenance_id, utilisateur_id, role, statut_confirmation) 
                VALUES (:soutenance_id, :utilisateur_id, :role, 'en_attente')";
        
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            ':soutenance_id' => $soutenanceId,
            ':utilisateur_id' => $utilisateurId,
            ':role' => $role
        ]);
        
        if ($result) {
            // Envoyer notification à l'enseignant
            sendNotification($utilisateurId, 'Invitation jury', "Vous avez été invité à faire partie du jury d'une soutenance");
            return ['success' => true, 'id' => $this->pdo->lastInsertId()];
        }
        
        return ['success' => false, 'message' => 'Erreur lors de l\'ajout'];
    }
    
    /**
     * Supprime un membre du jury
     * @param int $id
     * @return bool
     */
    public function removeMembre($id) {
        $stmt = $this->pdo->prepare("DELETE FROM jury_membres WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
    
    /**
     * Confirme ou refuse la participation
     * @param int $id
     * @param string $statut (confirme ou refuse)
     * @return bool
     */
    public function confirmParticipation($id, $statut) {
        if (!in_array($statut, ['confirme', 'refuse'])) {
            return false;
        }
        
        $sql = "UPDATE jury_membres SET statut_confirmation = :statut WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':statut' => $statut, ':id' => $id]);
    }
    
    /**
     * Vérifie si le jury est complet (minimum 3 membres)
     * @param int $soutenanceId
     * @return bool
     */
    public function isComplet($soutenanceId) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM jury_membres 
            WHERE soutenance_id = :soutenance_id
        ");
        $stmt->execute([':soutenance_id' => $soutenanceId]);
        return $stmt->fetchColumn() >= 3;
    }
    
    /**
     * Récupère le nombre de membres du jury
     * @param int $soutenanceId
     * @return int
     */
    public function countMembres($soutenanceId) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM jury_membres 
            WHERE soutenance_id = :soutenance_id
        ");
        $stmt->execute([':soutenance_id' => $soutenanceId]);
        return $stmt->fetchColumn();
    }
    
    /**
     * Récupère les membres confirmés
     * @param int $soutenanceId
     * @return array
     */
    public function getMembresConfirmes($soutenanceId) {
        $stmt = $this->pdo->prepare("
            SELECT j.*, u.nom, u.prenom, u.email 
            FROM jury_membres j
            JOIN utilisateurs u ON j.utilisateur_id = u.id
            WHERE j.soutenance_id = :soutenance_id AND j.statut_confirmation = 'confirme'
            ORDER BY FIELD(j.role, 'president', 'directeur', 'rapporteur', 'membre')
        ");
        $stmt->execute([':soutenance_id' => $soutenanceId]);
        return $stmt->fetchAll();
    }
}
?>