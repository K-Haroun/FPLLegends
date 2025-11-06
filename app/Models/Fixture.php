<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixture extends Model
{
    /** @use HasFactory<\Database\Factories\FixtureFactory> */
    use HasFactory;

    protected $fillable = [
        'fpl_id', 'team_h', 'team_a', 'event', 'kickoff_time', 'team_h_score', 'team_a_score', 'finished'
    ];

    public function homeTeam() { return $this->belongsTo(Team::class, 'team_h');}
    public function awayTeam() { return $this->belongsTo(Team::class, 'team_a');}
    public function gameweek() { return $this->belongsTo(Gameweek::class, 'event');}
    public function performances() { return $this->hasMany(PlayerPerformance::class); }
}