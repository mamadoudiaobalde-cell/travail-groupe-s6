<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PvResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'soutenance_id' => $this->soutenance_id,
            'note' => $this->note,
            'mention' => $this->mention,
            'observations' => $this->observations,
            'status' => $this->status,
            'fichier_pdf' => $this->fichier_pdf,
            'signe_le' => $this->signe_le?->toDateString(),
            'soutenance' => new SoutenanceResource($this->whenLoaded('soutenance')),
        ];
    }
}
