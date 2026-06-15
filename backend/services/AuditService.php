<?php
// backend/services/AuditService.php
// Service avancé pour la gestion des logs d'audit

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/audit.php';

class AuditService {
    private $pdo;
    
    public function __construct() {
        $this->pdo = Database::getConnection();
    }
    
    /**
     * Récupère tous les logs
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getAllLogs($limit = 100, $offset = 0) {
        $sql = "
            SELECT a.*, u.nom, u.prenom, u.email 
            FROM audit_log a 
            LEFT JOIN utilisateurs u ON a.utilisateur_id = u.id 
            ORDER BY a.created_at DESC 
            LIMIT :limit OFFSET :offset
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Récupère les logs par utilisateur
     * @param int $userId
     * @param int $limit
     * @return array
     */
    public function getLogsByUser($userId, $limit = 100) {
        $sql = "
            SELECT a.*, u.nom, u.prenom, u.email 
            FROM audit_log a 
            LEFT JOIN utilisateurs u ON a.utilisateur_id = u.id 
            WHERE a.utilisateur_id = :user_id 
            ORDER BY a.created_at DESC 
            LIMIT :limit
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Récupère les logs par action
     * @param string $action
     * @param int $limit
     * @return array
     */
    public function getLogsByAction($action, $limit = 100) {
        $sql = "
            SELECT a.*, u.nom, u.prenom, u.email 
            FROM audit_log a 
            LEFT JOIN utilisateurs u ON a.utilisateur_id = u.id 
            WHERE a.action LIKE :action 
            ORDER BY a.created_at DESC 
            LIMIT :limit
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':action', "%$action%", PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Récupère les logs par période
     * @param string $dateStart (YYYY-MM-DD)
     * @param string $dateEnd (YYYY-MM-DD)
     * @param int $limit
     * @return array
     */
    public function getLogsByPeriod($dateStart, $dateEnd, $limit = 100) {
        $sql = "
            SELECT a.*, u.nom, u.prenom, u.email 
            FROM audit_log a 
            LEFT JOIN utilisateurs u ON a.utilisateur_id = u.id 
            WHERE DATE(a.created_at) BETWEEN :start AND :end 
            ORDER BY a.created_at DESC 
            LIMIT :limit
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':start' => $dateStart,
            ':end' => $dateEnd,
            ':limit' => $limit
        ]);
        return $stmt->fetchAll();
    }
    
    /**
     * Récupère les logs par IP
     * @param string $ip
     * @param int $limit
     * @return array
     */
    public function getLogsByIp($ip, $limit = 100) {
        $sql = "
            SELECT a.*, u.nom, u.prenom, u.email 
            FROM audit_log a 
            LEFT JOIN utilisateurs u ON a.utilisateur_id = u.id 
            WHERE a.ip_address = :ip 
            ORDER BY a.created_at DESC 
            LIMIT :limit
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':ip' => $ip, ':limit' => $limit]);
        return $stmt->fetchAll();
    }
    
    /**
     * Récupère les logs d'aujourd'hui
     * @return array
     */
    public function getTodayLogs() {
        $sql = "
            SELECT a.*, u.nom, u.prenom, u.email 
            FROM audit_log a 
            LEFT JOIN utilisateurs u ON a.utilisateur_id = u.id 
            WHERE DATE(a.created_at) = CURDATE() 
            ORDER BY a.created_at DESC
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Compte le nombre total de logs
     * @param array $filters
     * @return int
     */
    public function countLogs($filters = []) {
        $sql = "SELECT COUNT(*) FROM audit_log WHERE 1=1";
        $params = [];
        
        if (isset($filters['user_id'])) {
            $sql .= " AND utilisateur_id = :user_id";
            $params[':user_id'] = $filters['user_id'];
        }
        
        if (isset($filters['action'])) {
            $sql .= " AND action LIKE :action";
            $params[':action'] = "%{$filters['action']}%";
        }
        
        if (isset($filters['date_start']) && isset($filters['date_end'])) {
            $sql .= " AND DATE(created_at) BETWEEN :start AND :end";
            $params[':start'] = $filters['date_start'];
            $params[':end'] = $filters['date_end'];
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
    
    /**
     * Récupère les statistiques d'audit
     * @return array
     */
    public function getStats() {
        $stats = [];
        
        // Nombre total
        $stats['total'] = $this->pdo->query("SELECT COUNT(*) FROM audit_log")->fetchColumn();
        
        // Nombre aujourd'hui
        $stats['today'] = $this->pdo->query("SELECT COUNT(*) FROM audit_log WHERE DATE(created_at) = CURDATE()")->fetchColumn();
        
        // Nombre cette semaine
        $stats['this_week'] = $this->pdo->query("SELECT COUNT(*) FROM audit_log WHERE YEARWEEK(created_at) = YEARWEEK(CURDATE())")->fetchColumn();
        
        // Nombre ce mois
        $stats['this_month'] = $this->pdo->query("SELECT COUNT(*) FROM audit_log WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())")->fetchColumn();
        
        // Top 10 actions
        $stmt = $this->pdo->query("
            SELECT action, COUNT(*) as count 
            FROM audit_log 
            GROUP BY action 
            ORDER BY count DESC 
            LIMIT 10
        ");
        $stats['top_actions'] = $stmt->fetchAll();
        
        // Top 10 utilisateurs
        $stmt = $this->pdo->query("
            SELECT u.nom, u.prenom, u.email, COUNT(*) as count 
            FROM audit_log a 
            JOIN utilisateurs u ON a.utilisateur_id = u.id 
            GROUP BY a.utilisateur_id 
            ORDER BY count DESC 
            LIMIT 10
        ");
        $stats['top_users'] = $stmt->fetchAll();
        
        // Top 10 IP
        $stmt = $this->pdo->query("
            SELECT ip_address, COUNT(*) as count 
            FROM audit_log 
            WHERE ip_address IS NOT NULL
            GROUP BY ip_address 
            ORDER BY count DESC 
            LIMIT 10
        ");
        $stats['top_ips'] = $stmt->fetchAll();
        
        // Activité par heure
        $stmt = $this->pdo->query("
            SELECT HOUR(created_at) as heure, COUNT(*) as count 
            FROM audit_log 
            GROUP BY HOUR(created_at) 
            ORDER BY heure ASC
        ");
        $stats['by_hour'] = $stmt->fetchAll();
        
        // Activité par jour (7 derniers jours)
        $stmt = $this->pdo->query("
            SELECT DATE(created_at) as jour, COUNT(*) as count 
            FROM audit_log 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY DATE(created_at)
            ORDER BY jour ASC
        ");
        $stats['last_7_days'] = $stmt->fetchAll();
        
        return $stats;
    }
    
    /**
     * Exporte les logs en CSV
     * @param array $logs
     * @return string
     */
    public function exportLogsToCSV($logs) {
        $csv = "Date et heure;Utilisateur;Action;Détails;IP;User Agent\n";
        
        foreach ($logs as $log) {
            $csv .= date('d/m/Y H:i:s', strtotime($log['created_at'])) . ";";
            $csv .= ($log['prenom'] ?? 'Système') . " " . ($log['nom'] ?? '') . ";";
            $csv .= $log['action'] . ";";
            $csv .= '"' . str_replace('"', '""', $log['details'] ?? '') . '";';
            $csv .= $log['ip_address'] . ";";
            $csv .= '"' . str_replace('"', '""', $log['user_agent'] ?? '') . '"' . "\n";
        }
        
        return $csv;
    }
    
    /**
     * Exporte les logs en JSON
     * @param array $logs
     * @return string
     */
    public function exportLogsToJSON($logs) {
        return json_encode($logs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * Nettoie les logs anciens
     * @param int $jours Nombre de jours à conserver (défaut: 90)
     * @return int Nombre de lignes supprimées
     */
    public function cleanOldLogs($jours = 90) {
        $sql = "DELETE FROM audit_log WHERE created_at < DATE_SUB(NOW(), INTERVAL :jours DAY)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':jours' => $jours]);
        return $stmt->rowCount();
    }
    
    /**
     * Supprime tous les logs d'un utilisateur
     * @param int $userId
     * @return int
     */
    public function deleteLogsByUser($userId) {
        $sql = "DELETE FROM audit_log WHERE utilisateur_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->rowCount();
    }
    
    /**
     * Supprime tous les logs d'une action
     * @param string $action
     * @return int
     */
    public function deleteLogsByAction($action) {
        $sql = "DELETE FROM audit_log WHERE action = :action";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':action' => $action]);
        return $stmt->rowCount();
    }
    
    /**
     * Supprime tous les logs d'une IP
     * @param string $ip
     * @return int
     */
    public function deleteLogsByIp($ip) {
        $sql = "DELETE FROM audit_log WHERE ip_address = :ip";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':ip' => $ip]);
        return $stmt->rowCount();
    }
    
    /**
     * Recherche dans les logs
     * @param string $search
     * @param int $limit
     * @return array
     */
    public function searchLogs($search, $limit = 100) {
        $sql = "
            SELECT a.*, u.nom, u.prenom, u.email 
            FROM audit_log a 
            LEFT JOIN utilisateurs u ON a.utilisateur_id = u.id 
            WHERE a.action LIKE :search 
               OR a.details LIKE :search 
               OR a.ip_address LIKE :search
               OR u.nom LIKE :search
               OR u.prenom LIKE :search
               OR u.email LIKE :search
            ORDER BY a.created_at DESC 
            LIMIT :limit
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Récupère les tentatives de connexion échouées
     * @param int $limit
     * @return array
     */
    public function getFailedLogins($limit = 50) {
        $sql = "
            SELECT a.*, a.details as email
            FROM audit_log a 
            WHERE a.action = 'echec_connexion' 
            ORDER BY a.created_at DESC 
            LIMIT :limit
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Récupère les accès non autorisés
     * @param int $limit
     * @return array
     */
    public function getUnauthorizedAccess($limit = 50) {
        $sql = "
            SELECT a.*, u.nom, u.prenom, u.email 
            FROM audit_log a 
            LEFT JOIN utilisateurs u ON a.utilisateur_id = u.id 
            WHERE a.action = 'acces_non_autorise' 
            ORDER BY a.created_at DESC 
            LIMIT :limit
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Récupère les actions sensibles
     * @param int $limit
     * @return array
     */
    public function getSensitiveActions($limit = 50) {
        $sensitiveActions = [
            'suppression_utilisateur',
            'suppression_salle',
            'annulation_soutenance',
            'reset_mdp',
            'toggle_utilisateur'
        ];
        
        $placeholders = implode(',', array_fill(0, count($sensitiveActions), '?'));
        
        $sql = "
            SELECT a.*, u.nom, u.prenom, u.email 
            FROM audit_log a 
            LEFT JOIN utilisateurs u ON a.utilisateur_id = u.id 
            WHERE a.action IN ($placeholders) 
            ORDER BY a.created_at DESC 
            LIMIT :limit
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $params = array_merge($sensitiveActions, [$limit]);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * Récupère le tableau de bord d'audit
     * @return array
     */
    public function getAuditDashboard() {
        return [
            'stats' => $this->getStats(),
            'recent_logs' => $this->getAllLogs(20),
            'failed_logins' => $this->getFailedLogins(10),
            'unauthorized_access' => $this->getUnauthorizedAccess(10),
            'sensitive_actions' => $this->getSensitiveActions(10)
        ];
    }
}
?>