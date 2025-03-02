<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Midtrans\Config;
use Midtrans\Snap;
use App\Mail\PemesananMail;
use Illuminate\Support\Facades\Mail;

class PembayaranController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
        Config::$is3ds = env('MIDTRANS_IS_3DS', true);
    }
    
    public function index()
    {
        $pembayaran = Pembayaran::with(['pemesanan.user', 'pemesanan.taman'])
            ->when(!auth()->user()->isAdmin(), function ($query) {
                return $query->whereHas('pemesanan', function ($q) {
                    return $q->where('user_id', auth()->id());
                });
            })
            ->latest()
            ->paginate(10);
            
        return view('pembayaran.index', compact('pembayaran'));
    }
    
    public function create(Pemesanan $pemesanan)
    {
        if (auth()->id() !== $pemesanan->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }
        
        // Buat transaksi Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => 'SPT-' . $pemesanan->id . '-' . time(),
                'gross_amount' => (int) $pemesanan->total_harga,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => auth()->user()->phone ?? '',
            ],
            'item_details' => [
                [
                    'id' => 'TAMAN-' . $pemesanan->taman->id,
                    'price' => (int) $pemesanan->total_harga,
                    'quantity' => 1,
                    'name' => 'Sewa Taman: ' . $pemesanan->taman->nama,
                ]
            ],
        ];
        
        $snapToken = Snap::getSnapToken($params);
        
        return view('pembayaran.create', compact('pemesanan', 'snapToken'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'pemesanan_id' => 'required|exists:pemesanan,id',
            'metode_pembayaran' => 'required|in:midtrans,manual',
            'json_result' => 'nullable|required_if:metode_pembayaran,midtrans',
            'bukti_pembayaran' => 'nullable|required_if:metode_pembayaran,manual|image|max:2048',
            'jumlah' => 'required|numeric|min:1'
        ]);
        
        $pemesanan = Pemesanan::findOrFail($request->pemesanan_id);
        
        if (auth()->id() !== $pemesanan->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }
        
        $pembayaranData = [
            'pemesanan_id' => $request->pemesanan_id,
            'jumlah' => $request->jumlah,
            'status' => 'pending'
        ];
        
        if ($request->metode_pembayaran == 'midtrans') {
            $result = json_decode($request->json_result);
            
            $pembayaranData['transaction_id'] = $result->transaction_id ?? null;
            $pembayaranData['order_id'] = $result->order_id ?? null;
            $pembayaranData['payment_type'] = $result->payment_type ?? null;
            $pembayaranData['payment_data'] = $request->json_result;
            
            // Jika settlement langsung, update status
            if (isset($result->transaction_status) && 
                ($result->transaction_status == 'capture' || $result->transaction_status == 'settlement')) {
                $pembayaranData['status'] = 'diverifikasi';
                $pemesanan->update(['status' => 'dibayar']);
                
                Mail::to($pemesanan->user->email)->send(new PemesananMail($pemesanan, 'payment_diverifikasi'));
                
                Pembayaran::create($pembayaranData);
                
                return redirect()->route('pemesanan.show', $pemesanan->id)
                    ->with('success', 'Pembayaran berhasil');
            }
        } else {
            $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
            $pembayaranData['bukti_pembayaran'] = $path;
            
            // Kirim email notifikasi menunggu verifikasi (hanya untuk pembayaran manual)
            Mail::to($pemesanan->user->email)->send(new PemesananMail($pemesanan, 'payment_uploaded'));
        }
        
        Pembayaran::create($pembayaranData);
        
        return redirect()->route('pemesanan.show', $pemesanan->id)
            ->with('success', 'Pembayaran berhasil dibuat, silakan tunggu verifikasi admin');
    }
    
    public function show(Pembayaran $pembayaran)
    {
        if (auth()->id() !== $pembayaran->pemesanan->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }
        
        return view('pembayaran.show', compact('pembayaran'));
    }
    
    public function verifikasi(Request $request, Pembayaran $pembayaran)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
        
        $request->validate([
            'status' => 'required|in:diverifikasi,ditolak',
            'catatan' => 'nullable|required_if:status,ditolak|string'
        ]);
        
        $pembayaran->update([
            'status' => $request->status,
            'catatan' => $request->catatan
        ]);
        
        // Update status pemesanan jika pembayaran diverifikasi
        if ($request->status == 'diverifikasi') {
            $pembayaran->pemesanan->update(['status' => 'dibayar']);
        }
        
        // Kirim email notifikasi pembayaran
        Mail::to($pembayaran->pemesanan->user->email)->send(new PemesananMail($pembayaran->pemesanan, 'payment_' . $request->status));
        
        return redirect()->route('pemesanan.show', $pembayaran->pemesanan_id)
            ->with('success', 'Status pembayaran berhasil diperbarui');
    }
    
    public function callback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id.$request->status_code.$request->gross_amount.$serverKey);
        
        if($hashed == $request->signature_key) {
            $order_id = $request->order_id;
            
            $pembayaran = Pembayaran::where('order_id', $order_id)->first();
            
            if($pembayaran) {
                if($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                    $pembayaran->update(['status' => 'diverifikasi']);
                    
                    // Update status pemesanan juga
                    $pembayaran->pemesanan->update(['status' => 'dibayar']);
                }
                elseif($request->transaction_status == 'deny' || $request->transaction_status == 'expire' || $request->transaction_status == 'cancel') {
                    $pembayaran->update([
                        'status' => 'ditolak',
                        'catatan' => 'Pembayaran ' . $request->transaction_status
                    ]);
                }
            }
        }
        
        return response('OK', 200);
    }
}