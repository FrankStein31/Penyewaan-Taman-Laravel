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

    public function __construct($userId = null)
    {
        $this->userId = $userId;
    }

    public function collection()
    {
        if ($this->userId) {
            return Pemesanan::with(['user', 'taman'])
                    ->where('user_id', $this->userId)
                    ->latest()
                    ->get();
        } else {
            return Pemesanan::with(['user', 'taman'])
                    ->latest()
                    ->get();
        }
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