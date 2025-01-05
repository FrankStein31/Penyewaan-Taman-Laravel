<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #6777ef; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .footer { text-align: center; padding: 20px; color: #666; }
        .status { display: inline-block; padding: 5px 10px; border-radius: 4px; }
        .pending { background: #ffc107; color: #000; }
        .approved { background: #28a745; color: #fff; }
        .rejected { background: #dc3545; color: #fff; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>{{ config('app.name') }}</h2>
        </div>
        
        <div class="content">
            <p>Yth. {{ $pemesanan->user->name }},</p>

            @if($type === 'created')
                <p>Terima kasih telah melakukan pemesanan. Berikut detail pemesanan Anda:</p>
            @elseif($type === 'approved')
                <p>Pemesanan Anda telah disetujui. Berikut detail pemesanan:</p>
            @elseif($type === 'rejected')
                <p>Mohon maaf, pemesanan Anda ditolak dengan catatan: {{ $pemesanan->catatan_admin }}</p>
                <p>Silahkan melakukan pemesanan kembali.</p>
            @endif

            <table style="width: 100%; margin: 20px 0;">
                <tr>
                    <td style="padding: 8px;"><strong>Kode Pemesanan:</strong></td>
                    <td style="padding: 8px;">{{ $pemesanan->kode }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px;"><strong>Taman:</strong></td>
                    <td style="padding: 8px;">{{ $pemesanan->taman->nama }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px;"><strong>Waktu:</strong></td>
                    <td style="padding: 8px;">
                        {{ $pemesanan->waktu_mulai->format('d/m/Y H:i') }} - 
                        {{ $pemesanan->waktu_selesai->format('d/m/Y H:i') }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px;"><strong>Total Waktu:</strong></td>
                    <td style="padding: 8px;">
                        @if($pemesanan->total_jam >= 24)
                            {{ $pemesanan->total_hari }} hari
                        @else
                            {{ $pemesanan->total_jam }} jam
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px;"><strong>Harga per Hari:</strong></td>
                    <td style="padding: 8px;">Rp {{ number_format($pemesanan->taman->harga_per_hari, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px;"><strong>Harga per Jam:</strong></td>
                    <td style="padding: 8px;">Rp {{ number_format($pemesanan->taman->harga_per_hari / 24, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px;"><strong>Total Bayar:</strong></td>
                    <td style="padding: 8px;">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px;"><strong>Status:</strong></td>
                    <td style="padding: 8px;">
                        <span class="status {{ strtolower($pemesanan->status) }}">
                            {{ ucfirst($pemesanan->status) }}
                        </span>
                    </td>
                </tr>
            </table>

            <p>Untuk melihat detail pemesanan, silakan klik tombol di bawah ini:</p>
            <p style="text-align: center;">
                <a href="{{ route('pemesanan.show', $pemesanan->id) }}" 
                   style="background: #6777ef; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
                    Lihat Detail Pemesanan
                </a>
            </p>
        </div>

        <div class="footer">
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html> 