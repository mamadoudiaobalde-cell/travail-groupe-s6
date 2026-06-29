<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'soutenance_id',
        'type',
        'chemin_fichier',
        'hash_fichier',
    ];

    // Relations
    public function soutenance()
    {
        return $this->belongsTo(Soutenance::class);
    }

    // Scopes
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }
}
