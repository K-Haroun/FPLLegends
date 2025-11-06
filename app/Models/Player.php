<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    /** @use HasFactory<\Database\Factories\PlayerFactory> */
    use HasFactory;

    protected $fillable = [
        'fpl_id','web_name','first_name','second_name','team_id','position','now_cost', 'birth_date','news','photo'
    ];

    public function team() { return $this->belongsTo(Team::class); }
    public function performances() { return $this->hasMany(PlayerPerformance::class); }
    public function transfersIn() {return $this->hasMany(Transfer::class, 'player_in_id');}
    public function transfersOut() {return $this->hasMany(Transfer::class, 'player_out_id');}
    public function userTeams()
    {
        return $this->belongsToMany(UserTeam::class, 'player_user_team')
                    ->withTimestamps();
    }

    // price in £ (FPL uses integer tenths)
    public function getPriceAttribute() {
        return $this->now_cost ? $this->now_cost / 10 : null;
    }

    // In app/Models/Player.php
    public function getPlPlayerIdAttribute()
    {
        if (!$this->photo) return null;
        return (int) str_replace('.jpg', '', $this->photo);
    }

    public function getPlayerSlugAttribute()
    {
        return strtolower(str_replace(' ', '-', $this->first_name . '-' . $this->second_name));
    }

}
