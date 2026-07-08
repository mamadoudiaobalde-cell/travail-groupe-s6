<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SoutenanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'filiere' => $this->filiere,
            'type' => $this->type,
            'date' => $this->date?->toDateString(),
            'heure' => $this->heure,
            'statut' => $this->statut,
            'etudiant' => new UserResource($this->whenLoaded('etudiant')),
            'directeur' => new UserResource($this->whenLoaded('directeur')),
            'salle' => new SalleResource($this->whenLoaded('salle')),
            'jury' => JuryResource::collection($this->whenLoaded('jury')),
            'pv' => $this->whenLoaded('pv', fn () => $this->pv ? new PvResource($this->pv) : null),
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
