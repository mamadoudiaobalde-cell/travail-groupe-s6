<?php
// Service d'envoi d'emails

require_once __DIR__ . '/../config/app.php';

class MailService {
    private $smtpHost;
    private $smtpPort;
    private $smtpUser;
    private $smtpPass;
    private $from;
    private $fromName;
    
    public function __construct() {
        $this->smtpHost = SMTP_HOST;
        $this->smtpPort = SMTP_PORT;
        $this->smtpUser = SMTP_USER;
        $this->smtpPass = SMTP_PASS;
        $this->from = SMTP_FROM;
        $this->fromName = SMTP_FROM_NAME;
    }
    
    /**
     * Envoie un email
     */
    public function send($to, $subject, $body, $isHtml = true) {
        // Si pas de configuration SMTP, on log uniquement
        if (empty($this->smtpUser)) {
            error_log("EMAIL (simulé) - À: $to, Sujet: $subject");
            return true;
        }
        
        // Avec PHPMailer (à installer via composer)
        try {
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = $this->smtpHost;
            $mail->SMTPAuth = true;
            $mail->Username = $this->smtpUser;
            $mail->Password = $this->smtpPass;
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $this->smtpPort;
            
            $mail->setFrom($this->from, $this->fromName);
            $mail->addAddress($to);
            
            $mail->isHTML($isHtml);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = strip_tags($body);
            
            return $mail->send();
        } catch (Exception $e) {
            error_log("Erreur envoi email: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Envoie une convocation
     */
    public function sendConvocation($to, $etudiantNom, $soutenance) {
        $subject = "Convocation à votre soutenance - Gestion Soutenances";
        
        $body = "
            <h2>Convocation à votre soutenance</h2>
            <p>Bonjour $etudiantNom,</p>
            <p>Votre soutenance a été planifiée :</p>
            <ul>
                <li><strong>Date :</strong> " . date('d/m/Y', strtotime($soutenance['date'])) . "</li>
                <li><strong>Heure :</strong> " . date('H:i', strtotime($soutenance['heure'])) . "</li>
                <li><strong>Salle :</strong> " . ($soutenance['salle_nom'] ?? 'À définir') . "</li>
                <li><strong>Titre :</strong> {$soutenance['titre']}</li>
            </ul>
            <p>Vous pouvez télécharger votre convocation depuis votre espace étudiant.</p>
            <hr>
            <p><small>Ceci est un message automatique, merci de ne pas y répondre.</small></p>
        ";
        
        return $this->send($to, $subject, $body);
    }
    
    /**
     * Envoie une invitation au jury
     */
    public function sendInvitationJury($to, $enseignantNom, $soutenance) {
        $subject = "Invitation à faire partie du jury - Gestion Soutenances";
        
        $body = "
            <h2>Invitation à participer au jury</h2>
            <p>Bonjour $enseignantNom,</p>
            <p>Vous avez été invité à faire partie du jury pour la soutenance suivante :</p>
            <ul>
                <li><strong>Étudiant :</strong> {$soutenance['etudiant_prenom']} {$soutenance['etudiant_nom']}</li>
                <li><strong>Date :</strong> " . date('d/m/Y', strtotime($soutenance['date'])) . "</li>
                <li><strong>Heure :</strong> " . date('H:i', strtotime($soutenance['heure'])) . "</li>
                <li><strong>Titre :</strong> {$soutenance['titre']}</li>
            </ul>
            <p>Veuillez confirmer votre participation depuis votre espace enseignant.</p>
            <hr>
            <p><small>Ceci est un message automatique, merci de ne pas y répondre.</small></p>
        ";
        
        return $this->send($to, $subject, $body);
    }
    
    /**
     * Envoie une notification de résultat
     */
    public function sendResultat($to, $etudiantNom, $soutenance, $pv) {
        $subject = "Résultat de votre soutenance - Gestion Soutenances";
        
        $body = "
            <h2>Résultat de votre soutenance</h2>
            <p>Bonjour $etudiantNom,</p>
            <p>Le résultat de votre soutenance est disponible :</p>
            <ul>
                <li><strong>Titre :</strong> {$soutenance['titre']}</li>
                <li><strong>Note :</strong> {$pv['note']}/20</li>
                <li><strong>Mention :</strong> {$pv['mention']}</li>
            </ul>
            <p>Vous pouvez consulter et télécharger votre PV depuis votre espace étudiant.</p>
            <hr>
            <p><small>Ceci est un message automatique, merci de ne pas y répondre.</small></p>
        ";
        
        return $this->send($to, $subject, $body);
    }
}
?>