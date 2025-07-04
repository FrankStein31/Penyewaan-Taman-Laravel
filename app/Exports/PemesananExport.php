<?php

namespace App\Exports;

use App\Models\Pemesanan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PemesananExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $userId;
    protected $filters = [];

    public function __construct($userId = null, $filters = [])
    {
        $this->userId = $userId;
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Pemesanan::with(['user', 'taman']);
        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }
        // Terapkan filter
        if (!empty($this->filters)) {
            if (!empty($this->filters['status'])) {
                $query->where('status', $this->filters['status']);
            }
            if (!empty($this->filters['pembayaran_status'])) {
                if ($this->filters['pembayaran_status'] == 'belum_bayar') {
                    $query->where('status', 'disetujui')
                          ->whereDoesntHave('pembayaran');
                } else {
                    $query->whereHas('pembayaran', function($q) {
                        $q->where('status', $this->filters['pembayaran_status']);
                    });
                }
            }
            if (!empty($this->filters['tanggal_mulai'])) {
                $query->whereDate('waktu_mulai', '>=', $this->filters['tanggal_mulai']);
            }
            if (!empty($this->filters['tanggal_selesai'])) {
                $query->whereDate('waktu_selesai', '<=', $this->filters['tanggal_selesai']);
            }
            if (!empty($this->filters['keyword'])) {
                $keyword = $this->filters['keyword'];
                $query->where(function($q) use ($keyword) {
                    $q->where('kode', 'like', "%{$keyword}%")
                      ->orWhereHas('taman', function($q2) use ($keyword) {
                          $q2->where('nama', 'like', "%{$keyword}%");
                      });
                });
            }
        }
        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Kode',
            'Nama Penyewa',
            'Taman',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Waktu Mulai',
            'Waktu Selesai',
            'Total Jam',
            'Total Hari',
            'Keperluan',
            'Jumlah Orang',
            'Total Harga',
            'Status',
            'Tanggal Pemesanan'
        ];
    }

    public function map($pemesanan): array
    {
        return [
            $pemesanan->kode,
            $pemesanan->user->name,
            $pemesanan->taman->nama,
            $pemesanan->tanggal_mulai,
            $pemesanan->tanggal_selesai,
            $pemesanan->waktu_mulai->format('H:i'),
            $pemesanan->waktu_selesai->format('H:i'),
            $pemesanan->total_jam,
            $pemesanan->total_hari,
            $pemesanan->keperluan,
            $pemesanan->jumlah_orang,
            'Rp ' . number_format($pemesanan->total_harga, 0, ',', '.'),
            ucfirst($pemesanan->status),
            $pemesanan->created_at->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}