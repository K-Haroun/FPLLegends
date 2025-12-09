<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $position = $this->getPlayerPosition();
        return [
            "id" => $this->id,
            "fpl_id" => $this->fpl_id,
            "name" => $this->web_name,
            "first_name" => $this->first_name,
            "second_name" => $this->second_name,
            "team" => $this->team->name ?? null,
            "team_id" => $this->team->id ?? null,
            "position" => $position,
            "now_cost" => $this->now_cost,
            "news" => $this->news,
            "birth_date" => $this->birth_date,
            "nationality" => $this->nationality,
            "squad_number" => $this->squad_number,
            "performances" => $this->whenLoaded('performances'),
        ];
    }
}
