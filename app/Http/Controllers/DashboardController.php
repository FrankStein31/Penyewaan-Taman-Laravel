<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Taman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'admin') {
            // Data untuk admin
            $totalUser = User::where('role', 'user')->count();
            $totalTaman = Taman::count();
            $totalPemesanan = Pemesanan::count();
            $pemesananPending = Pemesanan::where('status', 'pending')->count();
            
            // Pemesanan terbaru untuk admin
            $pemesananTerbaru = Pemesanan::with(['user', 'taman'])
                                        ->latest()
                                        ->take(5)
                                        ->get();
                                        
            // Data taman populer untuk grafik
            $tamanPopuler = Pemesanan::select('taman_id', DB::raw('count(*) as total'))
                                    ->groupBy('taman_id')
                                    ->orderByDesc('total')
                                    ->limit(5)
                                    ->with('taman')
                                    ->get();
            
            // Data pendapatan untuk grafik
            $pendapatanBulanan = Pemesanan::select(
                                    DB::raw('MONTH(created_at) as bulan'),
                                    DB::raw('YEAR(created_at) as tahun'),
                                    DB::raw('SUM(total_harga) as total_pendapatan')
                                )
                                ->where('status', 'dibayar')
                                ->orWhere('status', 'selesai')
                                ->groupBy('tahun', 'bulan')
                                ->orderBy('tahun')
                                ->orderBy('bulan')
                                ->get()
                                ->map(function($item) {
                                    $bulan = Carbon::createFromDate($item->tahun, $item->bulan, 1);
                                    return [
                                        'bulan' => $bulan->format('M Y'),
                                        'pendapatan' => $item->total_pendapatan
                                    ];
                                });

            return view('dashboard', compact(
                'totalUser',
                'totalTaman',
                'totalPemesanan',
                'pemesananPending',
                'pemesananTerbaru',
                'tamanPopuler',
                'pendapatanBulanan'
            ));
        } else {
            // Data untuk user biasa
            $totalPemesanan = Pemesanan::where('user_id', auth()->id())->count();
            $pemesananPending = Pemesanan::where('user_id', auth()->id())
                                        ->where('status', 'pending')
                                        ->count();
            $pemesananDisetujui = Pemesanan::where('user_id', auth()->id())
                                          ->where('status', 'disetujui')
                                          ->count();
            $pemesananDibayar = Pemesanan::where('user_id', auth()->id())
                                       ->where('status', 'dibayar')
                                       ->count();
            $pemesananSelesai = Pemesanan::where('user_id', auth()->id())
                                       ->where('status', 'selesai')
                                       ->count();
            
            // Pemesanan akan datang
            $pemesananAkanDatang = Pemesanan::with('taman')
                                   ->where('user_id', auth()->id())
                                   ->whereIn('status', ['disetujui', 'dibayar'])
                                   ->where('waktu_mulai', '>', Carbon::now())
                                   ->orderBy('waktu_mulai')
                                   ->first();
            
            // Taman favorit user
            $tamanFavorit = Pemesanan::select('taman_id', DB::raw('count(*) as total'))
                                 ->where('user_id', auth()->id())
                                 ->groupBy('taman_id')
                                 ->orderByDesc('total')
                                 ->limit(3)
                                 ->with('taman')
                                 ->get();
                                 
            // Total pengeluaran
            $totalPengeluaran = Pemesanan::where('user_id', auth()->id())
                                      ->whereIn('status', ['dibayar', 'selesai'])
                                      ->sum('total_harga');
                                      
            // Dapatkan statistik status pemesanan untuk chart
            $statusPemesanan = [
                ['status' => 'Pending', 'jumlah' => $pemesananPending, 'warna' => '#ffa426'],
                ['status' => 'Disetujui', 'jumlah' => $pemesananDisetujui, 'warna' => '#6777ef'],
                ['status' => 'Dibayar', 'jumlah' => $pemesananDibayar, 'warna' => '#63ed7a'],
                ['status' => 'Selesai', 'jumlah' => $pemesananSelesai, 'warna' => '#3abaf4']
            ];

            return view('dashboard', compact(
                'totalPemesanan',
                'pemesananPending',
                'pemesananDisetujui',
                'pemesananDibayar',
                'pemesananSelesai',
                'pemesananAkanDatang',
                'tamanFavorit',
                'totalPengeluaran',
                'statusPemesanan'
            ));
        }
    }
} 
