<?php
// backend/services/ExportService.php
// Service pour l'export des données (CSV, Excel, PDF, iCal)

require_once __DIR__ . '/../config/database.php';

class ExportService {
    private $pdo;
    
    public function __construct() {
        $this->pdo = Database::getConnection();
    }
    
    /**
     * Export CSV générique
     * @param array $data Données à exporter
     * @param string $filename Nom du fichier
     * @param array $headers En-têtes personnalisés (optionnel)
     */
    public function exportCSV($data, $filename, $headers = []) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Ajouter BOM pour UTF-8 (compatibilité Excel)
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
        
        // Ajouter les en-têtes
        if (!empty($headers)) {
            fputcsv($output, $headers, ';');
        } elseif (!empty($data)) {
            fputcsv($output, array_keys($data[0]), ';');
        }
        
        // Ajouter les données
        foreach ($data as $row) {
            fputcsv($output, $row, ';');
        }
        
        fclose($output);
        exit();
    }
    
    /**
     * Export Excel (XLS) via CSV
     * @param array $data Données à exporter
     * @param string $filename Nom du fichier
     */
    public function exportExcel($data, $filename) {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '_' . date('Y-m-d') . '.xls"');
        
        $output = fopen('php://output', 'w');
        
        if (!empty($data)) {
            // En-têtes
            fputcsv($output, array_keys($data[0]), "\t");
            // Données
            foreach ($data as $row) {
                fputcsv($output, $row, "\t");
            }
        }
        
        fclose($output);
        exit();
    }
    
    /**
     * Export PDF (template HTML)
     * @param string $html Contenu HTML
     * @param string $filename Nom du fichier
     */
    public function exportPDF($html, $filename) {
        // Si dompdf est installé via composer
        if (class_exists('Dompdf\Dompdf')) {
            $dompdf = new Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream($filename . '.pdf');
        } else {
            // Fallback : afficher le HTML
            header('Content-Type: text/html');
            echo $html;
        }
        exit();
    }
    
    /**
     * Export iCal (calendrier)
     * @param array $events Événements
     * @param string $filename Nom du fichier
     */
    public function exportICal($events, $filename) {
        $ical = "BEGIN:VCALENDAR\r\n";
        $ical .= "VERSION:2.0\r\n";
        $ical .= "PRODID:-//GestionSoutenances//FR\r\n";
        $ical .= "CALSCALE:GREGORIAN\r\n";
        $ical .= "METHOD:PUBLISH\r\n";
        
        foreach ($events as $event) {
            $ical .= "BEGIN:VEVENT\r\n";
            $ical .= "UID:" . uniqid() . "@gestion-soutenances\r\n";
            $ical .= "DTSTAMP:" . date('Ymd\THis\Z') . "\r\n";
            $ical .= "DTSTART:" . date('Ymd\THis', strtotime($event['date'] . ' ' . ($event['heure'] ?? '09:00'))) . "\r\n";
            $ical .= "DTEND:" . date('Ymd\THis', strtotime($event['date'] . ' ' . ($event['heure'] ?? '09:00') . ' +2 hours')) . "\r\n";
            $ical .= "SUMMARY:" . $this->escapeICal($event['titre']) . "\r\n";
            $ical .= "DESCRIPTION:" . $this->escapeICal($event['description'] ?? 'Soutenance') . "\r\n";
            $ical .= "LOCATION:" . $this->escapeICal($event['location'] ?? 'À définir') . "\r\n";
            $ical .= "STATUS:CONFIRMED\r\n";
            $ical .= "END:VEVENT\r\n";
        }
        
        $ical .= "END:VCALENDAR\r\n";
        
        header('Content-Type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '_' . date('Y-m-d') . '.ics"');
        echo $ical;
        exit();
    }
    
    /**
     * Échappe les caractères spéciaux pour iCal
     * @param string $string
     * @return string
     */
    private function escapeICal($string) {
        return str_replace(['\\', ',', ';', "\n"], ['\\\\', '\,', '\;', '\n'], $string);
    }
    
    // =============================================
    // EXPORTS SPÉCIFIQUES AU PROJET
    // =============================================
    
    /**
     * Export des résultats de soutenance
     * @param array $filters Filtres (filiere, annee, type)
     * @param string $format csv|excel
     */
    public function exportResultats($filters = [], $format = 'csv') {
        $sql = "
            SELECT 
                e.nom as etudiant_nom,
                e.prenom as etudiant_prenom,
                e.email as etudiant_email,
                s.titre,
                s.filiere,
                s.type,
                s.date,
                d.nom as directeur_nom,
                d.prenom as directeur_prenom,
                pv.note,
                pv.mention,
                pv.observations,
                CASE WHEN pv.note >= 10 THEN 'Admis' ELSE 'Non admis' END as decision
            FROM soutenances s
            JOIN utilisateurs e ON s.etudiant_id = e.id
            JOIN utilisateurs d ON s.directeur_id = d.id
            LEFT JOIN pv ON pv.soutenance_id = s.id
            WHERE s.statut = 'realisee'
        ";
        $params = [];
        
        if (isset($filters['filiere']) && $filters['filiere']) {
            $sql .= " AND s.filiere = :filiere";
            $params[':filiere'] = $filters['filiere'];
        }
        
        if (isset($filters['type']) && $filters['type']) {
            $sql .= " AND s.type = :type";
            $params[':type'] = $filters['type'];
        }
        
        if (isset($filters['annee']) && $filters['annee']) {
            $sql .= " AND YEAR(s.date) = :annee";
            $params[':annee'] = $filters['annee'];
        }
        
        $sql .= " ORDER BY s.date DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll();
        
        $headers = [
            'Étudiant', 'Email', 'Titre', 'Filière', 'Type', 
            'Date', 'Directeur', 'Note', 'Mention', 'Observations', 'Décision'
        ];
        
        $formattedData = [];
        foreach ($data as $row) {
            $formattedData[] = [
                $row['etudiant_prenom'] . ' ' . $row['etudiant_nom'],
                $row['etudiant_email'],
                $row['titre'],
                $row['filiere'],
                $row['type'],
                date('d/m/Y', strtotime($row['date'])),
                $row['directeur_prenom'] . ' ' . $row['directeur_nom'],
                $row['note'] ?? '-',
                $row['mention'] ?? '-',
                $row['observations'] ?? '-',
                $row['decision'] ?? '-'
            ];
        }
        
        if ($format === 'excel') {
            $this->exportExcel($formattedData, 'resultats_soutenances');
        } else {
            $this->exportCSV($formattedData, 'resultats_soutenances', $headers);
        }
    }
    
    /**
     * Export du planning des soutenances
     * @param array $filters Filtres (date_debut, date_fin, filiere)
     * @param string $format csv|excel|ical
     */
    public function exportPlanning($filters = [], $format = 'csv') {
        $sql = "
            SELECT 
                s.date,
                s.heure,
                e.nom as etudiant_nom,
                e.prenom as etudiant_prenom,
                s.titre,
                s.filiere,
                s.type,
                salle.nom as salle_nom,
                salle.localisation,
                d.nom as directeur_nom,
                d.prenom as directeur_prenom,
                s.statut
            FROM soutenances s
            JOIN utilisateurs e ON s.etudiant_id = e.id
            JOIN utilisateurs d ON s.directeur_id = d.id
            LEFT JOIN salles salle ON s.salle_id = salle.id
            WHERE s.statut IN ('planifiee', 'confirmee')
        ";
        $params = [];
        
        if (isset($filters['date_debut']) && $filters['date_debut']) {
            $sql .= " AND s.date >= :date_debut";
            $params[':date_debut'] = $filters['date_debut'];
        }
        
        if (isset($filters['date_fin']) && $filters['date_fin']) {
            $sql .= " AND s.date <= :date_fin";
            $params[':date_fin'] = $filters['date_fin'];
        }
        
        if (isset($filters['filiere']) && $filters['filiere']) {
            $sql .= " AND s.filiere = :filiere";
            $params[':filiere'] = $filters['filiere'];
        }
        
        $sql .= " ORDER BY s.date ASC, s.heure ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll();
        
        if ($format === 'ical') {
            $events = [];
            foreach ($data as $row) {
                $events[] = [
                    'date' => $row['date'],
                    'heure' => $row['heure'],
                    'titre' => 'Soutenance: ' . $row['etudiant_prenom'] . ' ' . $row['etudiant_nom'],
                    'description' => $row['titre'] . ' - ' . $row['filiere'],
                    'location' => $row['salle_nom'] ?? 'À définir'
                ];
            }
            $this->exportICal($events, 'planning_soutenances');
        } else {
            $headers = ['Date', 'Heure', 'Étudiant', 'Titre', 'Filière', 'Type', 'Salle', 'Directeur', 'Statut'];
            $formattedData = [];
            foreach ($data as $row) {
                $formattedData[] = [
                    date('d/m/Y', strtotime($row['date'])),
                    $row['heure'],
                    $row['etudiant_prenom'] . ' ' . $row['etudiant_nom'],
                    $row['titre'],
                    $row['filiere'],
                    $row['type'],
                    $row['salle_nom'] ?? '-',
                    $row['directeur_prenom'] . ' ' . $row['directeur_nom'],
                    $row['statut']
                ];
            }
            
            if ($format === 'excel') {
                $this->exportExcel($formattedData, 'planning_soutenances');
            } else {
                $this->exportCSV($formattedData, 'planning_soutenances', $headers);
            }
        }
    }
    
    /**
     * Export des statistiques
     * @param string $format csv|excel
     */
    public function exportStatistiques($format = 'csv') {
        // Statistiques par filière
        $stmt = $this->pdo->query("
            SELECT 
                s.filiere,
                COUNT(*) as total_soutenances,
                SUM(CASE WHEN s.statut = 'realisee' THEN 1 ELSE 0 END) as realisees,
                AVG(pv.note) as note_moyenne,
                SUM(CASE WHEN pv.note >= 10 THEN 1 ELSE 0 END) as admis
            FROM soutenances s
            LEFT JOIN pv ON pv.soutenance_id = s.id
            GROUP BY s.filiere
        ");
        $parFiliere = $stmt->fetchAll();
        
        // Statistiques par type
        $stmt = $this->pdo->query("
            SELECT 
                s.type,
                COUNT(*) as total,
                AVG(pv.note) as moyenne
            FROM soutenances s
            LEFT JOIN pv ON pv.soutenance_id = s.id
            GROUP BY s.type
        ");
        $parType = $stmt->fetchAll();
        
        // Statistiques par année
        $stmt = $this->pdo->query("
            SELECT 
                YEAR(s.date) as annee,
                COUNT(*) as total,
                AVG(pv.note) as moyenne
            FROM soutenances s
            LEFT JOIN pv ON pv.soutenance_id = s.id
            GROUP BY YEAR(s.date)
            ORDER BY annee DESC
        ");
        $parAnnee = $stmt->fetchAll();
        
        $data = [];
        foreach ($parFiliere as $row) {
            $data[] = [
                'Filière' => $row['filiere'],
                'Total soutenances' => $row['total_soutenances'],
                'Soutenances réalisées' => $row['realisees'],
                'Note moyenne' => round($row['note_moyenne'] ?? 0, 2),
                'Taux de réussite' => $row['total_soutenances'] > 0 ? round(($row['admis'] / $row['total_soutenances']) * 100, 1) . '%' : '0%'
            ];
        }
        
        $headers = ['Filière', 'Total soutenances', 'Soutenances réalisées', 'Note moyenne', 'Taux de réussite'];
        
        if ($format === 'excel') {
            $this->exportExcel($data, 'statistiques_soutenances');
        } else {
            $this->exportCSV($data, 'statistiques_soutenances', $headers);
        }
    }
    
    /**
     * Export des utilisateurs
     * @param array $filters Filtres (role, actif)
     * @param string $format csv|excel
     */
    public function exportUtilisateurs($filters = [], $format = 'csv') {
        $sql = "
            SELECT 
                nom,
                prenom,
                email,
                role,
                CASE WHEN actif = 1 THEN 'Actif' ELSE 'Inactif' END as statut,
                DATE_FORMAT(created_at, '%d/%m/%Y') as date_creation
            FROM utilisateurs
            WHERE 1=1
        ";
        $params = [];
        
        if (isset($filters['role']) && $filters['role']) {
            $sql .= " AND role = :role";
            $params[':role'] = $filters['role'];
        }
        
        if (isset($filters['actif']) && $filters['actif'] !== '') {
            $sql .= " AND actif = :actif";
            $params[':actif'] = $filters['actif'];
        }
        
        $sql .= " ORDER BY nom ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll();
        
        $headers = ['Nom', 'Prénom', 'Email', 'Rôle', 'Statut', 'Date création'];
        
        if ($format === 'excel') {
            $this->exportExcel($data, 'utilisateurs');
        } else {
            $this->exportCSV($data, 'utilisateurs', $headers);
        }
    }
    
    /**
     * Export des salles
     * @param string $format csv|excel
     */
    public function exportSalles($format = 'csv') {
        $stmt = $this->pdo->query("
            SELECT 
                nom,
                capacite,
                localisation,
                equipements,
                CASE WHEN actif = 1 THEN 'Active' ELSE 'Inactive' END as statut
            FROM salles
            ORDER BY nom ASC
        ");
        $data = $stmt->fetchAll();
        
        $headers = ['Nom', 'Capacité', 'Localisation', 'Équipements', 'Statut'];
        
        if ($format === 'excel') {
            $this->exportExcel($data, 'salles');
        } else {
            $this->exportCSV($data, 'salles', $headers);
        }
    }
    
    /**
     * Export des PV (procès-verbaux)
     * @param array $filters Filtres (status, mention)
     * @param string $format csv|excel
     */
    public function exportPV($filters = [], $format = 'csv') {
        $sql = "
            SELECT 
                e.nom as etudiant_nom,
                e.prenom as etudiant_prenom,
                s.titre,
                s.filiere,
                s.date,
                pv.note,
                pv.mention,
                pv.observations,
                pv.status
            FROM pv
            JOIN soutenances s ON pv.soutenance_id = s.id
            JOIN utilisateurs e ON s.etudiant_id = e.id
            WHERE 1=1
        ";
        $params = [];
        
        if (isset($filters['status']) && $filters['status']) {
            $sql .= " AND pv.status = :status";
            $params[':status'] = $filters['status'];
        }
        
        if (isset($filters['mention']) && $filters['mention']) {
            $sql .= " AND pv.mention = :mention";
            $params[':mention'] = $filters['mention'];
        }
        
        $sql .= " ORDER BY s.date DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll();
        
        $headers = ['Étudiant', 'Titre', 'Filière', 'Date', 'Note', 'Mention', 'Observations', 'Statut PV'];
        
        $formattedData = [];
        foreach ($data as $row) {
            $formattedData[] = [
                $row['etudiant_prenom'] . ' ' . $row['etudiant_nom'],
                $row['titre'],
                $row['filiere'],
                date('d/m/Y', strtotime($row['date'])),
                $row['note'] ?? '-',
                $row['mention'] ?? '-',
                $row['observations'] ?? '-',
                $row['status']
            ];
        }
        
        if ($format === 'excel') {
            $this->exportExcel($formattedData, 'proces_verbaux');
        } else {
            $this->exportCSV($formattedData, 'proces_verbaux', $headers);
        }
    }
    
    /**
     * Export complet (toutes les données)
     * @return array Données pour backup
     */
    public function exportFullBackup() {
        $backup = [];
        
        // Utilisateurs
        $backup['utilisateurs'] = $this->pdo->query("SELECT * FROM utilisateurs")->fetchAll();
        
        // Salles
        $backup['salles'] = $this->pdo->query("SELECT * FROM salles")->fetchAll();
        
        // Soutenances
        $backup['soutenances'] = $this->pdo->query("SELECT * FROM soutenances")->fetchAll();
        
        // PV
        $backup['pv'] = $this->pdo->query("SELECT * FROM pv")->fetchAll();
        
        // Jurys
        $backup['jury_membres'] = $this->pdo->query("SELECT * FROM jury_membres")->fetchAll();
        
        // Indisponibilités
        $backup['indisponibilites'] = $this->pdo->query("SELECT * FROM indisponibilites")->fetchAll();
        
        return $backup;
    }
    
    /**
     * Export JSON (pour API)
     * @param array $data
     * @param string $filename
     */
    public function exportJSON($data, $filename) {
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="' . $filename . '_' . date('Y-m-d') . '.json"');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit();
    }
}
?>