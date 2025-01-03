<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taman extends Model
{
    use HasFactory;

    protected $table = 'taman';
    
    protected $fillable = [
        'nama',
        'deskripsi',
        'harga',
        'gambar',
        'status'
    ];
} 