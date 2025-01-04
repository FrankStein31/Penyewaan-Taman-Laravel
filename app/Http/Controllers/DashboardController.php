<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Taman;
use App\Models\User;
use Illuminate\Http\Request;

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

            return view('dashboard', compact(
                'totalUser',
                'totalTaman',
                'totalPemesanan',
                'pemesananPending',
                'pemesananTerbaru'
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
            
            // Pemesanan terbaru untuk user
            $pemesananTerbaru = Pemesanan::with('taman')
                                        ->where('user_id', auth()->id())
                                        ->latest()
                                        ->take(5)
                                        ->get();

            return view('dashboard', compact(
                'totalPemesanan',
                'pemesananPending',
                'pemesananDisetujui',
                'pemesananTerbaru'
            ));
        }
    }
} 