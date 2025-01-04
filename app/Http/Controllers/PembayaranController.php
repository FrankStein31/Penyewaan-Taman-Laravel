<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function index()
    {
        if (auth()->user()->isAdmin()) {
            $pembayaran = Pembayaran::with(['pemesanan.user', 'pemesanan.taman'])
                ->latest()
                ->paginate(10);
        } else {
            $pembayaran = Pembayaran::whereHas('pemesanan', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->with(['pemesanan.taman'])
            ->latest()
            ->paginate(10);
        }

        return view('pembayaran.index', compact('pembayaran'));
    }

    public function create(Pemesanan $pemesanan)
    {
        if ($pemesanan->user_id !== auth()->id()) {
            abort(403);
        }

        if ($pemesanan->status !== 'disetujui') {
            return back()->with('error', 'Pemesanan belum disetujui');
        }

        if ($pemesanan->pembayaran) {
            return redirect()->route('pembayaran.show', $pemesanan->pembayaran);
        }

        return view('pembayaran.create', compact('pemesanan'));
    }

    public function store(Request $request, Pemesanan $pemesanan)
    {
        if ($pemesanan->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('bukti_pembayaran')) {
            $bukti = $request->file('bukti_pembayaran');
            $path = $bukti->store('public/pembayaran');
            $filename = str_replace('public/', '', $path);
        }

        $pembayaran = Pembayaran::create([
            'pemesanan_id' => $pemesanan->id,
            'bukti_pembayaran' => $filename,
            'jumlah' => $pemesanan->total_harga,
            'status' => 'pending'
        ]);

        // Kirim notifikasi ke admin
        $this->sendNotification($pembayaran, 'new_payment');

        return redirect()->route('pembayaran.show', $pembayaran)
            ->with('success', 'Bukti pembayaran berhasil diupload');
    }

    public function show(Pembayaran $pembayaran)
    {
        if (!auth()->user()->isAdmin() && $pembayaran->pemesanan->user_id !== auth()->id()) {
            abort(403);
        }

        $pembayaran->load('pemesanan.user', 'pemesanan.taman');
        return view('pembayaran.show', compact('pembayaran'));
    }

    public function verifikasi(Request $request, Pembayaran $pembayaran)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:diverifikasi,ditolak',
            'catatan' => 'required_if:status,ditolak'
        ]);

        $pembayaran->update([
            'status' => $request->status,
            'catatan' => $request->catatan
        ]);

        // Update status pemesanan jika pembayaran diverifikasi
        if ($request->status === 'diverifikasi') {
            $pembayaran->pemesanan->update(['status' => 'dibayar']);
        }

        // Kirim notifikasi ke user
        $this->sendNotification($pembayaran, 'payment_verified');

        return redirect()->route('pembayaran.show', $pembayaran)
            ->with('success', 'Status pembayaran berhasil diupdate');
    }

    private function sendNotification($pembayaran, $type)
    {
        // Implementasi notifikasi email/WA akan ditambahkan nanti
        // Contoh logika:
        switch ($type) {
            case 'new_payment':
                // Kirim notifikasi ke admin bahwa ada pembayaran baru
                break;
            case 'payment_verified':
                // Kirim notifikasi ke user bahwa pembayaran sudah diverifikasi
                break;
        }
    }

    public function destroy(Pembayaran $pembayaran)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        // Hapus file bukti pembayaran
        if ($pembayaran->bukti_pembayaran) {
            Storage::delete('public/' . $pembayaran->bukti_pembayaran);
        }

        $pembayaran->delete();

        return redirect()->route('pembayaran.index')
            ->with('success', 'Data pembayaran berhasil dihapus');
    }
} 