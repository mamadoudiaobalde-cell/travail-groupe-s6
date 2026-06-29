<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pv extends Model
{
    use HasFactory;

    protected $table = 'pvs';

    protected $fillable = [
        'soutenance_id',
        'note',
        'mention',
        'observations',
        'status',
        'fichier_pdf',
        'signe_le',
    ];

    protected $casts = [
        'note' => 'float',
        'signe_le' => 'date',
    ];

    // Relations
    public function soutenance()
    {
        return $this->belongsTo(Soutenance::class);
    }

    // Calculer la mention automatiquement
    public static function calculerMention($note)
    {
        if ($note === null) return null;
        
        if ($note >= 16) return 'Excellent';
        if ($note >= 14) return 'Tres bien';
        if ($note >= 12) return 'Bien';
        if ($note >= 10) return 'Assez bien';
        return 'Passable';
    }

    // Vérifications
    public function estValide()
    {
        return $this->status === 'valide';
    }

    public function estSigne()
    {
        return $this->status === 'signe';
    }

    public function estArchive()
    {
        return $this->status === 'archive';
    }

    // Scopes
    public function scopeValides($query)
    {
        return $query->where('status', 'valide');
    }

    public function scopeSignes($query)
    {
        return $query->where('status', 'signe');
    }
}