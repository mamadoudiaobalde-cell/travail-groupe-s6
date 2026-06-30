<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'utilisateur_id',
        'type',
        'titre',
        'message',
        'lien',
        'lue',
        'email_envoye',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'lue' => 'boolean',
        'email_envoye' => 'boolean',
    ];

    // ==========================================
    // RELATIONS
    // ==========================================

    /**
     * Relation avec l'utilisateur.
     */
    public function utilisateur()

    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Scope pour les notifications non lues.
     */
    public function scopeNonLues($query)
    {
        return $query->where('lue', false);
    }

    /**
     * Scope pour les notifications lues.
     */
    public function scopeLues($query)
    {
        return $query->where('lue', true);
    }

    /**
     * Scope pour les notifications d'un utilisateur.
     */
    public function scopeParUtilisateur($query, $utilisateurId)
    {
        return $query->where('utilisateur_id', $utilisateurId);
    }

    /**
     * Scope pour les notifications par type.
     */
    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    // ==========================================
    // MÉTHODES
    // ==========================================

    /**
     * Marquer la notification comme lue.
     */
    public function marquerCommeLue()
    {
        $this->update(['lue' => true]);
        return $this;
    }

    /**
     * Marquer la notification comme non lue.
     */
    public function marquerCommeNonLue()
    {
        $this->update(['lue' => false]);
        return $this;
    }

    /**
     * Vérifier si la notification est lue.
     */
    public function estLue()
    {
        return $this->lue === true;
    }

    /**
     * Vérifier si la notification est non lue.
     */
    public function estNonLue()
    {
        return $this->lue === false;
    }

    /**
     * Envoyer une notification à un utilisateur.
     */
    public static function envoyer($utilisateurId, $titre, $message, $type = 'info', $lien = null)
    {
        return self::create([
            'utilisateur_id' => $utilisateurId,
            'titre' => $titre,
            'message' => $message,
            'type' => $type,
            'lien' => $lien,
            'lue' => false,
            'email_envoye' => false,
        ]);
    }
}
