<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soutenance extends Model
{
    protected $fillable = [
        'etudiant_id',
        'directeur_id',
        'titre',
        'filiere',
        'type',
        'date',
        'heure',
        'salle_id',
        'statut',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Relations
    public function etudiant()
    {
        return $this->belongsTo(User::class, 'etudiant_id');
    }

    public function directeur()
    {
        return $this->belongsTo(User::class, 'directeur_id');
    }

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    public function jury()
    {
        return $this->hasMany(Jury::class);
    }

    public function pv()
    {
        return $this->hasOne(Pv::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    // Vérifications
    public function estConfirmee()
    {
        return $this->statut === 'confirmee';
    }

    public function estAnnulee()
    {
        return $this->statut === 'annulee';
    }

    public function estRealisee()
    {
        return $this->statut === 'realisee';
    }

    // Scopes
    public function scopePlanifiees($query)
    {
        return $query->where('statut', 'planifiee');
    }

    public function scopeConfirmees($query)
    {
        return $query->where('statut', 'confirmee');
    }
}
