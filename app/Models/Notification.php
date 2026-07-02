<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'utilisateur_id',
        'type',
        'titre',
        'message',
        'lu',
        'lu_le',
    ];

    protected $casts = [
        'lu' => 'boolean',
        'lu_le' => 'datetime',
    ];

    // Relations
    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    // Vérifications
    public function estLue()
    {
        return $this->lu === true;
    }

    // Scopes
    public function scopeNonLues($query)
    {
        return $query->where('lu', false);
    }
}
