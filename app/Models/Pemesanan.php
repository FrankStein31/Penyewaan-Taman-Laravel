<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanan';
    
    protected $fillable = [
        'user_id',
        'taman_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'keperluan',
        'jumlah_orang',
        'status',
        'total_harga',
        'catatan_admin'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'total_harga' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function taman()
    {
        return $this->belongsTo(Taman::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }
} 