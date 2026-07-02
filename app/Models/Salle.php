<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'capacite',
        'localisation',
        'equipements',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    // Relations
    public function soutenances()
    {
        return $this->hasMany(Soutenance::class);
    }

    // Scopes
    public function scopeActives($query)
    {
        return $query->where('actif', true);
    }
}
