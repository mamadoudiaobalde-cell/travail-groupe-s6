<?php
// Journalisation des actions

require_once __DIR__ . '/../config/database.php';

/**
 * Journalise une action
 * @param int $userId ID utilisateur (0 = non connecté)
 * @param string $action Action effectuée
 * @param string|null $details Détails
 * @return bool
 */
function logAudit($userId, $action, $details = null) {
    try {
        $pdo = Database::getConnection();
        
        $sql = "INSERT INTO audit_log (utilisateur_id, action, details, ip_address, user_agent) 
                VALUES (:user_id, :action, :details, :ip, :user_agent)";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':user_id' => $userId ?: null,
            ':action' => $action,
            ':details' => $details,
            ':ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
            ':user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
    } catch (Exception $e) {
        error_log("Erreur audit log: " . $e->getMessage());
        return false;
    }
}

/**
 * Récupère l'historique d'audit
 * @param int|null $userId Filtrer par utilisateur
 * @param int $limit Nombre maximum
 * @return array
 */
function getAuditLog($userId = null, $limit = 100) {
    try {
        $pdo = Database::getConnection();
        
        if ($userId) {
            $sql = "SELECT a.*, u.nom, u.prenom, u.email 
                    FROM audit_log a 
                    LEFT JOIN utilisateurs u ON a.utilisateur_id = u.id 
                    WHERE a.utilisateur_id = :id 
                    ORDER BY a.created_at DESC 
                    LIMIT :limit";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        } else {
            $sql = "SELECT a.*, u.nom, u.prenom, u.email 
                    FROM audit_log a 
                    LEFT JOIN utilisateurs u ON a.utilisateur_id = u.id 
                    ORDER BY a.created_at DESC 
                    LIMIT :limit";
            $stmt = $pdo->prepare($sql);
        }
        
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    } catch (Exception $e) {
        error_log($e->getMessage());
        return [];
    }
}

/**
 * Nettoie les logs de plus de X jours
 * @param int $jours Nombre de jours à conserver
 * @return int Nombre de lignes supprimées
 */
function cleanAuditLog($jours = 90) {
    try {
        $pdo = Database::getConnection();
        $sql = "DELETE FROM audit_log WHERE created_at < DATE_SUB(NOW(), INTERVAL :jours DAY)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':jours' => $jours]);
        return $stmt->rowCount();
    } catch (Exception $e) {
        error_log($e->getMessage());
        return 0;
    }
}
?>