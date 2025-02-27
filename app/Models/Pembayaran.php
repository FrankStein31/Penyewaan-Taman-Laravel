<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;
    
    protected $table = 'pembayaran';
    
    protected $fillable = [
        'pemesanan_id',
        'bukti_pembayaran',
        'jumlah',
        'status',
        'catatan',
        'transaction_id',
        'order_id',
        'payment_type',
        'payment_data'
    ];
    
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class);
    }
}