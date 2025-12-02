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
        return [
            "id" => $this->id,
            "fpl_id" => $this->fpl_id,
            "name" => $this->web_name,
            "first_name" => $this->first_name,
            "second_name" => $this->second_name,
            "team" => $this->team->name ?? null,
            "team_id" => $this->team->id ?? null,
            "position" => $this->position,
            "now_cost" => $this->now_cost,
            "news" => $this->news,
            "birth_date" => $this->birth_date,
            "nationality" => $this->nationality,
            "squad_number" => $this->squad_number,
            "performances" => $this->whenLoaded('performances'),
            "fixtures" => $this->performances->map(function ($performance) {
                return [
                    'gameweek_id' => $performance->gameweek_id,
                    'total_points' => $performance->total_points,
                    'fixture' => [
                        'id' => $performance->fixture->id,
                        'home_team' => $performance->fixture->homeTeam,
                        'away_team' => $performance->fixture->awayTeam,
                        'home_score' => $performance->fixture->team_h_score,
                        'away_score' => $performance->fixture->team_a_score,
                        'kickoff' => $performance->fixture->kickoff_time,
                    ],
                ];
            }),
        ];
    }
}
