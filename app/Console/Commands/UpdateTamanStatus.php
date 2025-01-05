<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pemesanan;
use Carbon\Carbon;

class UpdateTamanStatus extends Command
{
    protected $signature = 'taman:update-status';
    protected $description = 'Update status taman berdasarkan waktu pemesanan yang telah berakhir';

    public function handle()
    {
        $now = Carbon::now();

        // Ambil semua pemesanan yang sudah berakhir tapi tamannya masih tidak tersedia
        $expiredBookings = Pemesanan::where('status', 'disetujui')
            ->where('waktu_selesai', '<', $now)
            ->whereHas('taman', function($query) {
                $query->where('status', false);
            })
            ->get();

        foreach ($expiredBookings as $booking) {
            // Update status taman menjadi tersedia
            $booking->taman->update(['status' => true]);
            
            // Opsional: Update status pemesanan menjadi 'selesai'
            $booking->update(['status' => 'selesai']);

            $this->info("Taman {$booking->taman->nama} telah diupdate menjadi tersedia.");
        }

        $this->info('Selesai mengupdate status taman.');
    }
} 