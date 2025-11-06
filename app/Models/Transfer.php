<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    /** @use HasFactory<\Database\Factories\TransferFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_team_id',
        'gameweek_id',
        'player_in_id',
        'player_out_id',
        'cost',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userTeam()
    {
        return $this->belongsTo(UserTeam::class);
    }

    public function gameweek()
    {
        return $this->belongsTo(Gameweek::class);
    }

    public function playerIn()
    {
        return $this->belongsTo(Player::class, 'player_in_id');
    }

    public function playerOut()
    {
        return $this->belongsTo(Player::class, 'player_out_id');
    }
}
