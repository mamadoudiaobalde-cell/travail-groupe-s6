<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JuryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'soutenance_id' => $this->soutenance_id,
            'role' => $this->role,
            'statut_confirmation' => $this->statut_confirmation,
            'utilisateur' => new UserResource($this->whenLoaded('utilisateur')),
            'soutenance' => new SoutenanceResource($this->whenLoaded('soutenance')),
        ];
    }
}
