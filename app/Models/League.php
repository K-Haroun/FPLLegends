<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    /** @use HasFactory<\Database\Factories\LeagueFactory> */
    use HasFactory;

    protected $fillable = [
        'fpl_id', 'name', 'code', 'type'
    ];

    public function userTeams()
    {
        return $this->belongsToMany(UserTeam::class, 'league_user_team')
                    ->withTimestamps();
    }

    public function owner() {return $this->belongsTo(User::class, 'owner_id');}
}
