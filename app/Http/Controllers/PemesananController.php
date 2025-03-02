<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Pembayaran;
use App\Models\Taman;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Mail\PemesananMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PemesananController extends Controller
{
    public function index()
    {
        // Check dan update expired bookings
        $this->updateExpiredBookings();

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

    private function updateExpiredBookings()
    {
        $now = Carbon::now();

        Pemesanan::where('status', 'disetujui')
            ->where('waktu_selesai', '<', $now)
            ->whereHas('taman', function($query) {
                $query->where('status', false);
            })
            ->each(function($booking) {
                $booking->taman->update(['status' => true]);
                $booking->update(['status' => 'selesai']);
            });
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
        try {
            $request->validate([
                'taman_id' => 'required|exists:taman,id',
                'tanggal_mulai' => 'required|date|after_or_equal:today',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'waktu_mulai' => 'required',
                'waktu_selesai' => 'required',
                'keperluan' => 'required|string',
                'jumlah_orang' => 'required|integer|min:1'
            ]);

            $taman = Taman::findOrFail($request->taman_id);
            
            // Combine tanggal dan waktu
            $waktu_mulai = Carbon::parse($request->tanggal_mulai . ' ' . $request->waktu_mulai);
            $waktu_selesai = Carbon::parse($request->tanggal_selesai . ' ' . $request->waktu_selesai);
            
            // Hitung total jam
            $total_jam = $waktu_mulai->diffInHours($waktu_selesai);
            $total_hari = ceil($total_jam / 24); // Bulatkan ke atas untuk hari penuh
            
            // Hitung harga per jam (harga per hari dibagi 24)
            $harga_per_jam = $taman->harga_per_hari / 24;
            
            // Hitung total harga berdasarkan jam
            $total_harga = $harga_per_jam * $total_jam;
            
            // Debug log
            \Log::info('Pemesanan Calculation', [
                'taman' => $taman->nama,
                'waktu_mulai' => $waktu_mulai->format('Y-m-d H:i'),
                'waktu_selesai' => $waktu_selesai->format('Y-m-d H:i'),
                'total_jam' => $total_jam,
                'total_hari' => $total_hari,
                'harga_per_jam' => $harga_per_jam,
                'total_harga' => $total_harga
            ]);

            // Buat pemesanan
            $pemesanan = Pemesanan::create([
                'kode' => 'PSN-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
                'user_id' => auth()->id(),
                'taman_id' => $taman->id,
                'tanggal_mulai' => $waktu_mulai->toDateString(),
                'tanggal_selesai' => $waktu_selesai->toDateString(),
                'waktu_mulai' => $waktu_mulai,
                'waktu_selesai' => $waktu_selesai,
                'keperluan' => $request->keperluan,
                'jumlah_orang' => $request->jumlah_orang,
                'total_hari' => $total_hari,
                'total_jam' => $total_jam,
                'total_harga' => $total_harga,
                'status' => 'pending'
            ]);

            // Update status taman menjadi tidak tersedia
            $taman->update(['status' => false]);

            // Kirim email notifikasi
            Mail::to($pemesanan->user->email)->send(new PemesananMail($pemesanan, 'created'));

            return redirect()->route('pemesanan.show', $pemesanan->id)
                ->with('success', 'Pemesanan berhasil dibuat');

        } catch (\Exception $e) {
            \Log::error('Pemesanan Error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Pemesanan $pemesanan)
    {
        // Pastikan user hanya bisa melihat pemesanannya sendiri
        if (!auth()->user()->isAdmin() && $pemesanan->user_id !== auth()->id()) {
            abort(403);
        }
        
        // Ambil data pembayaran jika ada
        $pembayaran = $pemesanan->pembayaran()->latest()->first();

        return view('pemesanan.show', compact('pemesanan', 'pembayaran'));
    }

    public function approve(Pemesanan $pemesanan)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $pemesanan->update(['status' => 'disetujui']);

        // Kirim email notifikasi
        Mail::to($pemesanan->user->email)->send(new PemesananMail($pemesanan, 'approved'));

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

        // Update status taman menjadi tersedia kembali
        $pemesanan->taman->update(['status' => true]);

        // Kirim email notifikasi
        Mail::to($pemesanan->user->email)->send(new PemesananMail($pemesanan, 'rejected'));

        return redirect()->route('pemesanan.show', $pemesanan->id)
            ->with('success', 'Pemesanan berhasil ditolak');
    }

    public function destroy(Pemesanan $pemesanan)
    {
        try {
            if (auth()->user()->isAdmin() || auth()->id() === $pemesanan->user_id) {
                if ($pemesanan->status === 'pending') {
                    // Update status taman menjadi tersedia kembali sebelum menghapus
                    $pemesanan->taman->update(['status' => true]);
                    
                    // Hapus pemesanan
                    $pemesanan->delete();

                    // Kirim email notifikasi pembatalan
                    Mail::to($pemesanan->user->email)->send(new PemesananMail($pemesanan, 'cancelled'));

                    return redirect()
                        ->route('pemesanan.index')
                        ->with('success', 'Pemesanan berhasil dibatalkan');
                } else {
                    return back()
                        ->with('error', 'Pemesanan yang sudah disetujui atau ditolak tidak dapat dibatalkan');
                }
            } else {
                return back()
                    ->with('error', 'Anda tidak memiliki akses untuk membatalkan pemesanan ini');
            }
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Terjadi kesalahan saat membatalkan pemesanan');
        }
    }

    public function uploadBuktiPembayaran(Request $request, Pemesanan $pemesanan)
    {
        try {
            $request->validate([
                'bukti_pembayaran' => 'required|image|max:2048',
                'jumlah' => 'required|numeric|min:1'
            ]);

            // Pastikan pemesanan milik user yang login
            if ($pemesanan->user_id !== auth()->id()) {
                abort(403);
            }
            
            // Pastikan status pemesanan disetujui
            if ($pemesanan->status !== 'disetujui') {
                return back()->with('error', 'Pembayaran hanya bisa dilakukan untuk pemesanan yang disetujui');
            }

            // Upload bukti pembayaran
            $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

            // Buat data pembayaran
            $pembayaran = $pemesanan->pembayaran()->create([
                'bukti_pembayaran' => $path,
                'jumlah' => $request->jumlah,
                'status' => 'pending'
            ]);

            // Update status pemesanan
            $pemesanan->update(['status' => 'dibayar']);

            // Kirim email notifikasi pembayaran
            Mail::to($pemesanan->user->email)->send(new PemesananMail($pemesanan, 'payment_uploaded'));

            return redirect()->route('pemesanan.show', $pemesanan->id)
                ->with('success', 'Bukti pembayaran berhasil diunggah');
        } catch (\Exception $e) {
            \Log::error('Upload Pembayaran Error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function verifikasiPembayaran(Request $request, Pemesanan $pemesanan)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $pembayaran = $pemesanan->pembayaran()->latest()->first();
        
        if (!$pembayaran) {
            return back()->with('error', 'Pembayaran tidak ditemukan');
        }

        $status = $request->status;
        $catatan = $request->catatan;

        $pembayaran->update([
            'status' => $status,
            'catatan' => $catatan
        ]);

        // Jika ditolak, kembalikan status pemesanan ke disetujui
        if ($status == 'ditolak') {
            $pemesanan->update(['status' => 'disetujui']);
        }

        // Kirim email notifikasi
        Mail::to($pemesanan->user->email)->send(new PemesananMail($pemesanan, 'payment_' . $status));

        return redirect()->route('pemesanan.show', $pemesanan->id)
            ->with('success', 'Pembayaran berhasil diverifikasi');
    }

    public function selesai($id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        
        // Hanya admin yang dapat menyelesaikan pemesanan
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
        }
        
        // Pemesanan harus dalam status 'dibayar' untuk dapat diselesaikan
        if ($pemesanan->status !== 'dibayar') {
            return redirect()->back()->with('error', 'Hanya pemesanan dengan status dibayar yang dapat diselesaikan.');
        }
        
        // Update status pemesanan menjadi selesai
        $pemesanan->update(['status' => 'selesai']);
        
        // Update status taman menjadi tersedia
        if ($pemesanan->taman) {
            $pemesanan->taman->update(['status' => 1]);
        }
        
        // Kirim email notifikasi
        Mail::to($pemesanan->user->email)->send(new PemesananMail($pemesanan, 'completed'));
        
        return redirect()->back()->with('success', 'Pemesanan berhasil diselesaikan.');
    }
} 