<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerPerformance extends Model
{
    /** @use HasFactory<\Database\Factories\PlayerPerformanceFactory> */
    use HasFactory;

     protected $guarded = [];

    public function gameweek() {return $this->belongsTo(Gameweek::class);}
    public function player() {return $this->belongsTo(Player::class);}
    public function fixture() {return $this->belongsTo(Fixture::class);}
    public function team()
    {
        return $this->hasOneThrough(Team::class, Player::class, 'id', 'id', 'player_id', 'team_id');
    }
}
