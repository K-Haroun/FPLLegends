<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gameweek extends Model
{
    /** @use HasFactory<\Database\Factories\GameweekFactory> */
    use HasFactory;

    protected $fillable = [
        'fpl_id', 'name', 'deadline_time', 'is_current', 'is_next', 'is_finished'
    ];

    public function fixtures() {return $this->hasMany(Fixture::class, 'event');}
    public function playerStats() {return $this->hasMany(PlayerPerformance::class);}
    public function userTeams(){return $this->hasMany(UserTeam::class);}
    public function performances() { return $this->hasMany(PlayerPerformance::class); }
    public function transfers() {return $this->hasMany(Transfer::class);}
}
