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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PemesananExport;

class PemesananController extends Controller
{
    public function index(Request $request)
    {
        // Check dan update expired bookings
        $this->updateExpiredBookings();

        // Dapatkan parameter filter
        $status = $request->status;
        $pembayaranStatus = $request->pembayaran_status;
        $keyword = $request->keyword;
        $tanggalMulai = $request->tanggal_mulai;
        $tanggalSelesai = $request->tanggal_selesai;

        // Buat query dasar
        $query = Pemesanan::query();

        // Filter berdasarkan peran user
        if (auth()->user()->isAdmin()) {
            $query->with(['user', 'taman']);
        } else {
            $query->with(['taman'])
                ->where('user_id', auth()->id());
        }

        // Filter berdasarkan status pemesanan
        if ($status) {
            $query->where('status', $status);
        }

        // Filter berdasarkan status pembayaran
        if ($pembayaranStatus) {
            if ($pembayaranStatus == 'belum_bayar') {
                $query->where('status', 'disetujui')
                      ->whereDoesntHave('pembayaran');
            } else {
                $query->whereHas('pembayaran', function($q) use ($pembayaranStatus) {
                    $q->where('status', $pembayaranStatus);
                });
            }
        }

        // Filter berdasarkan tanggal mulai
        if ($tanggalMulai) {
            $query->whereDate('waktu_mulai', '>=', $tanggalMulai);
        }

        // Filter berdasarkan tanggal selesai
        if ($tanggalSelesai) {
            $query->whereDate('waktu_selesai', '<=', $tanggalSelesai);
        }

        // Filter berdasarkan kata kunci
        if ($keyword) {
            $query->where(function($q) use ($keyword) {
                $q->where('kode', 'like', "%{$keyword}%")
                  ->orWhereHas('taman', function($q2) use ($keyword) {
                      $q2->where('nama', 'like', "%{$keyword}%");
                  });
            });
        }

        // Ambil data dengan urutan terbaru
        $pemesanan = $query->latest()->paginate(10);

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
        if ($request->has('taman')) {
            // Jika ada parameter taman di URL, cari taman tersebut
            $tamanId = $request->taman;
            $specificTaman = Taman::findOrFail($tamanId);
            
            // Cek apakah taman tersedia
            if (!$specificTaman->status) {
                return redirect()->route('taman.index')
                    ->with('error', 'Maaf, taman ini sedang tidak tersedia');
            }
            
            // Ambil semua taman tersedia untuk dropdown
            $taman = Taman::where('status', true)->get();
            
            return view('pemesanan.create', compact('taman', 'specificTaman'));
        } else {
            // Tidak ada parameter taman, ambil semua taman yang tersedia
            $taman = Taman::where('status', true)->get();
            
            return view('pemesanan.create', compact('taman'));
        }
    }

    public function store(Request $request)
    {
        try {
            // Validasi sesuai dengan tipe durasi
            if ($request->durasi_tipe === 'satu_hari') {
                $request->validate([
                    'taman_id' => 'required|exists:taman,id',
                    'tanggal_sewa' => 'required|date|after_or_equal:today',
                    'keperluan' => 'required|string',
                    'jumlah_orang' => 'required|integer|min:1'
                ]);

                // Set tanggal mulai dan selesai sama
                $tanggal_mulai = Carbon::parse($request->tanggal_sewa);
                $tanggal_selesai = Carbon::parse($request->tanggal_sewa);
                $total_hari = 1;
            } else {
                $request->validate([
                    'taman_id' => 'required|exists:taman,id',
                    'tanggal_mulai' => 'required|date|after_or_equal:today',
                    'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                    'keperluan' => 'required|string',
                    'jumlah_orang' => 'required|integer|min:1'
                ]);

                // Parse tanggal
                $tanggal_mulai = Carbon::parse($request->tanggal_mulai);
                $tanggal_selesai = Carbon::parse($request->tanggal_selesai);
                
                // Hitung total hari (inklusif)
                $total_hari = $tanggal_mulai->diffInDays($tanggal_selesai) + 1;
            }

            $taman = Taman::findOrFail($request->taman_id);
            
            // Set waktu default (00:00)
            $waktu_mulai = $tanggal_mulai->copy()->startOfDay();
            $waktu_selesai = $tanggal_selesai->copy()->endOfDay();
            
            // Hitung total harga berdasarkan hari
            $total_harga = $taman->harga_per_hari * $total_hari;
            
            // Debug log
            \Log::info('Pemesanan Calculation', [
                'taman' => $taman->nama,
                'tanggal_mulai' => $tanggal_mulai->format('Y-m-d'),
                'tanggal_selesai' => $tanggal_selesai->format('Y-m-d'),
                'total_hari' => $total_hari,
                'total_harga' => $total_harga
            ]);

            // Buat pemesanan
            $pemesanan = Pemesanan::create([
                'kode' => 'PSN-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
                'user_id' => auth()->id(),
                'taman_id' => $taman->id,
                'tanggal_mulai' => $tanggal_mulai->toDateString(),
                'tanggal_selesai' => $tanggal_selesai->toDateString(),
                'waktu_mulai' => $waktu_mulai,
                'waktu_selesai' => $waktu_selesai,
                'keperluan' => $request->keperluan,
                'jumlah_orang' => $request->jumlah_orang,
                'total_hari' => $total_hari,
                'total_jam' => 0, // Set total jam menjadi 0, karena tidak lagi menggunakan jam
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

    public function export() 
    {
        if (auth()->user()->isAdmin()) {
            return Excel::download(new PemesananExport, 'laporan-pemesanan-' . date('Y-m-d') . '.xlsx');
        } else {
            return Excel::download(new PemesananExport(auth()->id()), 'pemesanan-saya-' . date('Y-m-d') . '.xlsx');
        }
    }
} 