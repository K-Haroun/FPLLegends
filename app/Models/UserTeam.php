<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTeam extends Model
{
    /** @use HasFactory<\Database\Factories\UserTeamFactory> */
    use HasFactory;

    public function gameweek() {return $this->belongsTo(Gameweek::class);}
    public function user() {return $this->belongsTo(User::class);}
    public function transfers() {return $this->hasMany(Transfer::class);}
    public function leagues()
    {
        return $this->belongsToMany(League::class, 'league_user_team')
                    ->withTimestamps();
    }
    public function players()
    {
        return $this->belongsToMany(Player::class, 'player_user_team')
                    ->withTimestamps();
    }
}
