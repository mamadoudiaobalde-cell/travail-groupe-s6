<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'capacite' => $this->capacite,
            'localisation' => $this->localisation,
            'equipements' => $this->equipements,
            'actif' => $this->actif,
        ];
    }
}
