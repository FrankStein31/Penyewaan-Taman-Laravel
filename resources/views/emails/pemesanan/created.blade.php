<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pemesanan Baru</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #2d3748;">Pemesanan Baru</h2>
        
        <p>Halo {{ $pemesanan->user->name }},</p>
        
        <p>Terima kasih telah melakukan pemesanan di Sistem Penyewaan Taman. Berikut adalah detail pemesanan Anda:</p>
        
        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>Kode Pemesanan:</strong> {{ $pemesanan->kode }}</p>
            <p style="margin: 5px 0;"><strong>Status:</strong> {{ ucfirst($pemesanan->status) }}</p>
        </div>
        
        <h3 style="color: #2d3748;">Detail Taman:</h3>
        <p>
            <strong>Nama:</strong> {{ $pemesanan->taman->nama }}<br>
            <strong>Lokasi:</strong> {{ $pemesanan->taman->lokasi }}
        </p>
        
        <h3 style="color: #2d3748;">Detail Pemesanan:</h3>
        <p>
            <strong>Tanggal:</strong> {{ $pemesanan->tanggal_mulai->format('d/m/Y') }} - {{ $pemesanan->tanggal_selesai->format('d/m/Y') }}<br>
            <strong>Jumlah Orang:</strong> {{ number_format($pemesanan->jumlah_orang) }} orang<br>
            <strong>Total Bayar:</strong> Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}
        </p>
        
        <h3 style="color: #2d3748;">Keperluan:</h3>
        <p>{{ $pemesanan->keperluan }}</p>
        
        <div style="margin: 30px 0;">
            <a href="{{ route('pemesanan.show', $pemesanan->id) }}" 
               style="background: #4299e1; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px;">
                Lihat Detail Pemesanan
            </a>
        </div>
        
        <hr style="border: none; border-top: 1px solid #edf2f7; margin: 30px 0;">
        
        <p style="color: #718096; font-size: 14px;">
            Terima kasih,<br>
            Sistem Penyewaan Taman
        </p>
    </div>
</body>
</html> 