<?php
// Contrôleur Responsable Pédagogique

require_once __DIR__ . '/../models/Soutenance.php';
require_once __DIR__ . '/../models/User.php';

class ResponsableController {
    private $soutenanceModel;
    private $userModel;
    
    public function __construct() {
        $this->soutenanceModel = new Soutenance();
        $this->userModel = new User();
    }
    
    /**
     * Statistiques globales
     */
    public function getStatsGlobales() {
        $pdo = Database::getConnection();
        
        // Taux de réussite
        $stmt = $pdo->query("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN pv.note >= 10 THEN 1 ELSE 0 END) as reussi
            FROM soutenances s
            JOIN pv ON pv.soutenance_id = s.id
            WHERE s.statut = 'realisee'
        ");
        $reussite = $stmt->fetch();
        
        $tauxReussite = $reussite['total'] > 0 
            ? round(($reussite['reussi'] / $reussite['total']) * 100, 1) 
            : 0;
        
        // Par filière
        $stmt = $pdo->query("
            SELECT filiere, COUNT(*) as count 
            FROM soutenances 
            GROUP BY filiere
            ORDER BY count DESC
        ");
        $parFiliere = $stmt->fetchAll();
        
        // Par type
        $stmt = $pdo->query("
            SELECT type, COUNT(*) as count 
            FROM soutenances 
            GROUP BY type
        ");
        $parType = $stmt->fetchAll();
        
        // Alertes
        $alertes = $this->getAlertes();
        
        return [
            'taux_reussite' => $tauxReussite,
            'par_filiere' => $parFiliere,
            'par_type' => $parType,
            'alertes' => $alertes,
            'evolution' => $this->soutenanceModel->getStats()['par_mois']
        ];
    }
    
    /**
     * Récupère les alertes à traiter
     */
    public function getAlertes() {
        $pdo = Database::getConnection();
        $alertes = [];
        
        // Soutenances sans salle
        $stmt = $pdo->query("SELECT COUNT(*) FROM soutenances WHERE salle_id IS NULL AND date >= CURDATE()");
        $alertes['sans_salle'] = $stmt->fetchColumn();
        
        // Jurys incomplets
        $stmt = $pdo->query("
            SELECT COUNT(DISTINCT s.id) 
            FROM soutenances s
            LEFT JOIN jury_membres j ON s.id = j.soutenance_id
            WHERE s.date >= CURDATE() AND s.statut = 'planifiee'
            GROUP BY s.id
            HAVING COUNT(j.id) < 3
        ");
        $alertes['jury_incomplet'] = $stmt->rowCount();
        
        // Soutenances sans PV
        $stmt = $pdo->query("
            SELECT COUNT(*) 
            FROM soutenances s
            LEFT JOIN pv ON pv.soutenance_id = s.id
            WHERE s.statut = 'realisee' AND pv.id IS NULL
        ");
        $alertes['sans_pv'] = $stmt->fetchColumn();
        
        return $alertes;
    }
    
    /**
     * Export des résultats en CSV
     */
    public function exportResultatsCSV() {
        $pdo = Database::getConnection();
        
        $stmt = $pdo->query("
            SELECT 
                e.nom as etudiant_nom, e.prenom as etudiant_prenom,
                s.titre, s.filiere, s.type, s.date,
                pv.note, pv.mention, pv.observations
            FROM soutenances s
            JOIN utilisateurs e ON s.etudiant_id = e.id
            LEFT JOIN pv ON pv.soutenance_id = s.id
            WHERE s.statut = 'realisee'
            ORDER BY s.date DESC
        ");
        
        $data = $stmt->fetchAll();
        
        // En-têtes CSV
        $csv = "Étudiant;Titre;Filière;Type;Date;Note;Mention;Observations\n";
        
        foreach ($data as $row) {
            $csv .= "{$row['etudiant_prenom']} {$row['etudiant_nom']};";
            $csv .= "{$row['titre']};";
            $csv .= "{$row['filiere']};";
            $csv .= "{$row['type']};";
            $csv .= date('d/m/Y', strtotime($row['date'])). ";";
            $csv .= ($row['note'] ?? '-') . ";";
            $csv .= ($row['mention'] ?? '-') . ";";
            $csv .= str_replace(';', ',', $row['observations'] ?? '') . "\n";
        }
        
        return $csv;
    }
    
    /**
     * Statistiques par promotion
     */
    public function getStatsParPromotion() {
        $pdo = Database::getConnection();
        
        $stmt = $pdo->query("
            SELECT 
                YEAR(s.date) as annee,
                s.filiere,
                COUNT(*) as total,
                SUM(CASE WHEN pv.note >= 10 THEN 1 ELSE 0 END) as reussi,
                AVG(pv.note) as moyenne
            FROM soutenances s
            LEFT JOIN pv ON pv.soutenance_id = s.id
            WHERE s.statut = 'realisee'
            GROUP BY YEAR(s.date), s.filiere
            ORDER BY annee DESC, s.filiere
        ");
        
        return $stmt->fetchAll();
    }
}
?>