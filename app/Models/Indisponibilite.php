<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indisponibilite extends Model
{
    use HasFactory;

    protected $fillable = [
        'utilisateur_id',
        'date_debut',
        'date_fin',
        'motif',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    // Relations
    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    // Vérifications
    public function chevauche($date)
    {
        return $date >= $this->date_debut && $date <= $this->date_fin;
    }
}
