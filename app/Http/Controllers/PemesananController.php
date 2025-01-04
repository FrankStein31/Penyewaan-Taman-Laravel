<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Taman;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PemesananController extends Controller
{
    public function index()
    {
        if (auth()->user()->isAdmin()) {
            $pemesanan = Pemesanan::with(['user', 'taman'])->latest()->paginate(10);
        } else {
            $pemesanan = Pemesanan::with(['taman'])
                ->where('user_id', auth()->id())
                ->latest()
                ->paginate(10);
        }

        return view('pemesanan.index', compact('pemesanan'));
    }

    public function create(Request $request)
    {
        $taman = Taman::findOrFail($request->taman);
        
        // Cek apakah taman tersedia
        if (!$taman->status) {
            return redirect()->route('taman.index')
                ->with('error', 'Maaf, taman ini sedang tidak tersedia');
        }

        return view('pemesanan.create', compact('taman'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'taman_id' => 'required|exists:taman,id',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keperluan' => 'required|string',
            'jumlah_orang' => 'required|integer|min:1'
        ]);

        $taman = Taman::findOrFail($request->taman_id);
        
        // Validasi jumlah orang tidak melebihi kapasitas
        if ($request->jumlah_orang > $taman->kapasitas) {
            return back()->withInput()
                ->withErrors(['jumlah_orang' => 'Jumlah orang melebihi kapasitas taman']);
        }
        
        // Hitung total hari dan total harga
        $tanggal_mulai = Carbon::parse($request->tanggal_mulai);
        $tanggal_selesai = Carbon::parse($request->tanggal_selesai);
        $total_hari = $tanggal_selesai->diffInDays($tanggal_mulai) + 1;
        
        // Pastikan total harga tidak negatif
        $total_harga = max($taman->harga_per_hari * $total_hari, 0);

        // Buat pemesanan
        $pemesanan = Pemesanan::create([
            'user_id' => auth()->id(),
            'taman_id' => $taman->id,
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai,
            'keperluan' => $request->keperluan,
            'jumlah_orang' => $request->jumlah_orang,
            'total_harga' => $total_harga,
            'status' => 'pending'
        ]);

        return redirect()->route('pemesanan.show', $pemesanan->id)
            ->with('success', 'Pemesanan berhasil dibuat');
    }

    public function show(Pemesanan $pemesanan)
    {
        // Pastikan user hanya bisa melihat pemesanannya sendiri
        if (!auth()->user()->isAdmin() && $pemesanan->user_id !== auth()->id()) {
            abort(403);
        }

        return view('pemesanan.show', compact('pemesanan'));
    }

    public function approve(Pemesanan $pemesanan)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $pemesanan->update([
            'status' => 'disetujui'
        ]);

        return redirect()->route('pemesanan.show', $pemesanan->id)
            ->with('success', 'Pemesanan berhasil disetujui');
    }

    public function reject(Request $request, Pemesanan $pemesanan)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'catatan_admin' => 'required|string'
        ]);

        $pemesanan->update([
            'status' => 'ditolak',
            'catatan_admin' => $request->catatan_admin
        ]);

        return redirect()->route('pemesanan.show', $pemesanan->id)
            ->with('success', 'Pemesanan berhasil ditolak');
    }
} 