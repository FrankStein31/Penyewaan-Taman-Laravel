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
        $tamanPopuler = Taman::select('taman.*', DB::raw('COUNT(pemesanan.id) as total_pemesanan'))
            ->leftJoin('pemesanan', 'taman.id', '=', 'pemesanan.taman_id')
            ->groupBy('taman.id')
            ->orderByDesc('total_pemesanan')
            ->orderByDesc('taman.created_at')
            ->take(6)
            ->get();

        // Jika belum ada pemesanan, ambil taman terbaru
        if ($tamanPopuler->isEmpty()) {
            $tamanPopuler = Taman::latest()
                ->take(6)
                ->get();
        }

        return view('welcome', compact('tamanPopuler'));
    }
} 