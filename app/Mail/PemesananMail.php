<?php

namespace App\Mail;

use App\Models\Pemesanan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PemesananMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pemesanan;
    public $type;

    public function __construct(Pemesanan $pemesanan, $type)
    {
        $this->pemesanan = $pemesanan;
        $this->type = $type;
    }

    public function build()
    {
        $subject = match($this->type) {
            'created' => 'Pemesanan Baru - Menunggu Persetujuan',
            'approved' => 'Pemesanan Disetujui - Silahkan Lakukan Pembayaran',
            'rejected' => 'Pemesanan Ditolak',
            'payment_uploaded' => 'Pembayaran Diterima - Menunggu Verifikasi',
            'payment_diverifikasi' => 'Pembayaran Berhasil Diverifikasi',
            'payment_ditolak' => 'Pembayaran Ditolak - Mohon Upload Ulang',
            'completed' => 'Pemesanan Telah Selesai',
            default => 'Update Status Pemesanan'
        };

        return $this->subject($subject)
                   ->view('emails.pemesanan');
    }
} 