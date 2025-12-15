<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTeam extends Model
{
    /** @use HasFactory<\Database\Factories\UserTeamFactory> */
    use HasFactory;

    protected $guarded = [];

    public function gameweek()
    {
        return $this->belongsTo(Gameweek::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function transfers()
    {
        return $this->hasMany(Transfer::class);
    }
    public function leagues()
    {
        return $this->belongsToMany(League::class, 'league_user_team')
            ->withTimestamps();
    }
    public function players()
    {
        return $this->belongsToMany(Player::class, 'player_user_team')
            ->withPivot('position')
            ->withTimestamps();
    }
    public function performances()
    {
        return $this->hasManyThrough(PlayerPerformance::class, Player::class);
    }

    public function goalkeepers()
    {
        return $this->belongsToMany(Player::class, 'player_user_team')
            ->wherePivot('position', 'Goalkeeper');
    }

    public function defenders()
    {
        return $this->belongsToMany(Player::class, 'player_user_team')
            ->wherePivot('position', 'Defenders');
    }

    public function midfielders()
    {
        return $this->belongsToMany(Player::class, 'player_user_team')
            ->wherePivot('position', 'Midfielders');
    }

    public function forwards()
    {
        return $this->belongsToMany(Player::class, 'player_user_team')
            ->wherePivot('position', 'Forwards');
    }

}
