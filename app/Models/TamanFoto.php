<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TamanFoto extends Model
{
    protected $fillable = ['taman_id', 'foto'];

    public function taman()
    {
        return $this->belongsTo(Taman::class);
    }
}
