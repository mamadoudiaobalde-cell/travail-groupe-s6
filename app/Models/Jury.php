<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jury extends Model
{
    use HasFactory;

    protected $table = 'jury_membres';

    protected $fillable = [
        'soutenance_id',
        'utilisateur_id',
        'role',
        'statut_confirmation',
    ];

    protected $casts = [
        'statut_confirmation' => 'string',
    ];

    // Relations
    public function soutenance()
    {
        return $this->belongsTo(Soutenance::class);
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    // Vérifications
    public function estConfirme()
    {
        return $this->statut_confirmation === 'confirme';
    }

    public function estRefuse()
    {
        return $this->statut_confirmation === 'refuse';
    }

    public function estEnAttente()
    {
        return $this->statut_confirmation === 'en_attente';
    }

    // Scopes
    public function scopeConfirmes($query)
    {
        return $query->where('statut_confirmation', 'confirme');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut_confirmation', 'en_attente');
    }
}