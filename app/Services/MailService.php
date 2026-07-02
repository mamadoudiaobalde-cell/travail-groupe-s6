<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class MailService
{
    /**
     * Envoyer un email
     */
    public function send($to, $subject, $body, $type = 'html')
    {
        try {
            Mail::send([], [], function ($message) use ($to, $subject, $body) {
                $message->to($to)
                        ->subject($subject)
                        ->html($body);
            });
            return true;
        } catch (\Exception $e) {
            Log::error('Email failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Envoyer une convocation
     */
    public function sendConvocation($etudiant, $soutenance)
    {
        $subject = 'Convocation à votre soutenance - GestSoutenance';
        $body = "
            <h2>Convocation à la soutenance</h2>
            <p>Bonjour {$etudiant->name},</p>
            <p>Votre soutenance est prévue :</p>
            <ul>
                <li><strong>Date :</strong> {$soutenance->date}</li>
                <li><strong>Heure :</strong> {$soutenance->heure}</li>
                <li><strong>Salle :</strong> " . ($soutenance->salle->nom ?? 'À définir') . "</li>
                <li><strong>Titre :</strong> {$soutenance->titre}</li>
            </ul>
            <p>Cordialement,</p>
            <p>L'équipe GestSoutenance</p>
        ";
        return $this->send($etudiant->email, $subject, $body);
    }

    /**
     * Envoyer une invitation jury
     */
    public function sendInvitationJury($enseignant, $soutenance)
    {
        $subject = 'Invitation à faire partie du jury - GestSoutenance';
        $body = "
            <h2>Invitation au jury</h2>
            <p>Bonjour {$enseignant->name},</p>
            <p>Vous êtes invité à faire partie du jury pour la soutenance :</p>
            <ul>
                <li><strong>Étudiant :</strong> {$soutenance->etudiant->name}</li>
                <li><strong>Date :</strong> {$soutenance->date}</li>
                <li><strong>Heure :</strong> {$soutenance->heure}</li>
            </ul>
            <p>Veuillez confirmer votre participation.</p>
            <p>Cordialement,</p>
            <p>L'équipe GestSoutenance</p>
        ";
        return $this->send($enseignant->email, $subject, $body);
    }

    /**
     * Envoyer un résultat
     */
    public function sendResultat($etudiant, $soutenance, $pv)
    {
        $subject = 'Résultat de votre soutenance - GestSoutenance';
        $body = "
            <h2>Résultat de votre soutenance</h2>
            <p>Bonjour {$etudiant->name},</p>
            <p>Le résultat de votre soutenance est disponible :</p>
            <ul>
                <li><strong>Titre :</strong> {$soutenance->titre}</li>
                <li><strong>Note :</strong> {$pv->note}/20</li>
                <li><strong>Mention :</strong> {$pv->mention}</li>
            </ul>
            <p>Vous pouvez consulter votre PV depuis votre espace étudiant.</p>
            <p>Cordialement,</p>
            <p>L'équipe GestSoutenance</p>
        ";
        return $this->send($etudiant->email, $subject, $body);
    }
}