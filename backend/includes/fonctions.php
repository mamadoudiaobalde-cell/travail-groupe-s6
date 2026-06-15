<?php
// Fonctions utilitaires génériques

require_once __DIR__ . '/../config/database.php';

/**
 * Génère un slug à partir d'une chaîne
 */
function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return empty($text) ? 'n-a' : $text;
}

/**
 * Formate une date pour l'affichage
 */
function formatDate($date, $format = 'd/m/Y H:i') {
    if (!$date) return '-';
    $timestamp = is_string($date) ? strtotime($date) : $date;
    return date($format, $timestamp);
}

/**
 * Génère un token aléatoire sécurisé
 */
function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

/**
 * Vérifie si une salle est disponible à une date et heure données
 */
function isSalleDisponible($salleId, $date, $heure, $excludeSoutenanceId = null) {
    try {
        $pdo = Database::getConnection();
        
        $sql = "SELECT COUNT(*) FROM soutenances 
                WHERE salle_id = :salle_id 
                AND date = :date 
                AND heure = :heure";
        
        $params = [
            ':salle_id' => $salleId,
            ':date' => $date,
            ':heure' => $heure
        ];
        
        if ($excludeSoutenanceId) {
            $sql .= " AND id != :id";
            $params[':id'] = $excludeSoutenanceId;
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchColumn() == 0;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}

/**
 * Vérifie si un enseignant est disponible
 */
function isEnseignantDisponible($enseignantId, $date, $heure) {
    try {
        $pdo = Database::getConnection();
        
        // Vérifier les indisponibilités déclarées
        $sql = "SELECT COUNT(*) FROM indisponibilites 
                WHERE enseignant_id = :enseignant_id 
                AND date = :date 
                AND (creneau = 'journee' OR creneau = CASE 
                    WHEN HOUR(:heure) < 12 THEN 'matin' 
                    ELSE 'apres-midi' 
                END)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':enseignant_id' => $enseignantId,
            ':date' => $date,
            ':heure' => $heure
        ]);
        
        return $stmt->fetchColumn() == 0;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return true;
    }
}

/**
 * Envoie une notification push (simulée)
 */
function sendNotification($userId, $title, $message, $type = 'info') {
    try {
        $pdo = Database::getConnection();
        
        $sql = "INSERT INTO notifications (utilisateur_id, type, titre, message, lue) 
                VALUES (:user_id, :type, :titre, :message, 0)";
        
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':user_id' => $userId,
            ':type' => $type,
            ':titre' => $title,
            ':message' => $message
        ]);
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}

/**
 * Récupère les notifications non lues d'un utilisateur
 */
function getUnreadNotifications($userId) {
    try {
        $pdo = Database::getConnection();
        $sql = "SELECT * FROM notifications 
                WHERE utilisateur_id = :user_id AND lue = 0 
                ORDER BY created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        error_log($e->getMessage());
        return [];
    }
}

/**
 * Marque une notification comme lue
 */
function markNotificationAsRead($notificationId, $userId) {
    try {
        $pdo = Database::getConnection();
        $sql = "UPDATE notifications SET lue = 1 
                WHERE id = :id AND utilisateur_id = :user_id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $notificationId,
            ':user_id' => $userId
        ]);
    } catch (Exception $e) {
        error_log($e->getMessage());
        return false;
    }
}
?>