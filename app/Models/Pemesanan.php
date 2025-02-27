<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanan';
    
    protected $fillable = [
        'kode',
        'user_id',
        'taman_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'waktu_mulai',
        'waktu_selesai',
        'keperluan',
        'jumlah_orang',
        'total_hari',
        'total_jam',
        'total_harga',
        'status',
        'catatan_admin'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
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
        return $this->hasMany(Pembayaran::class);
    }

    public const STATUS_PENDING = 'pending';
    public const STATUS_DISETUJUI = 'disetujui';
    public const STATUS_DITOLAK = 'ditolak';
    public const STATUS_SELESAI = 'selesai';
} 