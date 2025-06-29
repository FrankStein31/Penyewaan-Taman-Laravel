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
        'lokasi',
        'kapasitas',
        'harga_per_hari',
        'fasilitas',
        'gambar',
        'status'
    ];

    protected $casts = [
        'fasilitas' => 'array',
        'status' => 'boolean',
        'harga_per_hari' => 'decimal:2'
    ];

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class);
    }

    public function fotos()
    {
        return $this->hasMany(TamanFoto::class);
    }
} 