<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerPerformance extends Model
{
    /** @use HasFactory<\Database\Factories\PlayerPerformanceFactory> */
    use HasFactory;

     protected $fillable = [
        'player_id',
        'fixture_id',
        'gameweek_id',
        'minutes',
        'goals_scored',
        'assists',
        'clean_sheets',
        'goals_conceded',
        'own_goals',
        'penalties_saved',
        'penalties_missed',
        'yellow_cards',
        'red_cards',
        'saves',
        'bonus',
        'bps',
        'total_points',
    ];

    public function gameweek() {return $this->belongsTo(Gameweek::class);}
    public function player() {return $this->belongsTo(Player::class);}
    public function fixture() {return $this->belongsTo(Fixture::class);}
    public function team()
    {
        return $this->hasOneThrough(Team::class, Player::class, 'id', 'id', 'player_id', 'team_id');
    }
}
