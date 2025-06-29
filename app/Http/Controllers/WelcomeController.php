<?php

namespace App\Http\Controllers;

use App\Models\Taman;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index()
    {
        // Ambil taman yang paling sering dipesan
        $tamanPopuler = Taman::select(
                'taman.*', 
                DB::raw('COUNT(pemesanan.id) as total_pemesanan'),
                DB::raw('CASE 
                    WHEN taman.status = 1 THEN "Tersedia"
                    ELSE "Tidak Tersedia"
                END as status_text')
            )
            ->leftJoin('pemesanan', 'taman.id', '=', 'pemesanan.taman_id')
            ->groupBy('taman.id', 'taman.nama', 'taman.deskripsi', 'taman.lokasi', 
                     'taman.kapasitas', 'taman.harga_per_hari', 'taman.fasilitas', 
                     'taman.gambar', 'taman.status', 'taman.created_at', 'taman.updated_at')
            ->orderByDesc('total_pemesanan')
            ->orderByDesc('taman.created_at')
            ->take(6)
            ->get();

        // Jika belum ada pemesanan, ambil taman terbaru
        if ($tamanPopuler->isEmpty()) {
            $tamanPopuler = Taman::select(
                    '*',
                    DB::raw('CASE 
                        WHEN status = 1 THEN "Tersedia"
                        ELSE "Tidak Tersedia"
                    END as status_text')
                )
                ->latest()
                ->take(6)
                ->get();
        }

        return view('welcome', compact('tamanPopuler'));
    }
} 