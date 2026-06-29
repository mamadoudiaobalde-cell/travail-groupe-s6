<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public function __construct(protected MailService $mailService)
    {
    }

    /**
     * Créer une notification in-app pour un utilisateur.
     */
    public function notify(int $userId, string $type, string $titre, string $message): Notification
    {
        return Notification::create([
            'utilisateur_id' => $userId,
            'type' => $type,
            'titre' => $titre,
            'message' => $message,
        ]);
    }

    /**
     * Notifier un étudiant de sa convocation (in-app + email).
     */
    public function notifierConvocation(User $etudiant, $soutenance): Notification
    {
        $this->mailService->sendConvocation($etudiant, $soutenance);

        return $this->notify(
            $etudiant->id,
            'convocation',
            'Convocation à votre soutenance',
            "Votre soutenance \"{$soutenance->titre}\" est prévue le {$soutenance->date} à {$soutenance->heure}."
        );
    }

    /**
     * Notifier un enseignant de son invitation au jury (in-app + email).
     */
    public function notifierInvitationJury(User $enseignant, $soutenance): Notification
    {
        $this->mailService->sendInvitationJury($enseignant, $soutenance);

        return $this->notify(
            $enseignant->id,
            'invitation_jury',
            'Invitation à un jury',
            "Vous êtes invité à participer au jury de la soutenance \"{$soutenance->titre}\"."
        );
    }

    /**
     * Notifier un étudiant que ses résultats sont disponibles (in-app + email).
     */
    public function notifierResultat(User $etudiant, $soutenance, $pv): Notification
    {
        $this->mailService->sendResultat($etudiant, $soutenance, $pv);

        return $this->notify(
            $etudiant->id,
            'resultat',
            'Résultat disponible',
            "Le résultat de votre soutenance \"{$soutenance->titre}\" est disponible."
        );
    }

    /**
     * Notifier l'étudiant et les membres du jury de l'annulation d'une soutenance.
     */
    public function notifierAnnulation($soutenance): void
    {
        $destinataires = $soutenance->jury->pluck('utilisateur_id')->push($soutenance->etudiant_id)->unique();

        foreach ($destinataires as $utilisateurId) {
            $this->notify(
                $utilisateurId,
                'annulation',
                'Soutenance annulée',
                "La soutenance \"{$soutenance->titre}\" prévue le {$soutenance->date} a été annulée."
            );
        }
    }
}
