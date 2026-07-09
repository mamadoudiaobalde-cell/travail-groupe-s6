<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'action' => $this->action,
            'details' => $this->details,
            'ip_address' => $this->ip_address,
            'utilisateur' => new UserResource($this->whenLoaded('utilisateur')),
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
