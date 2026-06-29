<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
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
