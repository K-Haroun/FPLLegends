<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory;

    protected $fillable = ['fpl_id','name','short_name','code','crest'];
    
    public function players() { return $this->hasMany(Player::class); }
    public function performances()
    {
        return $this->hasManyThrough(PlayerPerformance::class, Player::class);
    }
}
