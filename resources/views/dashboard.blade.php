@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<section class="section">
    <div class="row">
        @if(auth()->user()->isAdmin())
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total User</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalUser }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-tree"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Taman</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalTaman }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="fas fa-calendar"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total Pemesanan</h4>
                    </div>
                    <div class="card-body">
                        {{ $totalPemesanan }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Pemesanan Pending</h4>
                    </div>
                    <div class="card-body">
                        {{ $pemesananPending }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->isAdmin())
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Taman Populer</h4>
                </div>
                <div class="card-body">
                    <canvas id="tamanChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Pendapatan Bulanan</h4>
                </div>
                <div class="card-body">
                    <canvas id="pendapatanChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Dashboard untuk user biasa -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Status Pemesanan</h4>
                </div>
                <div class="card-body">
                    <canvas id="statusPemesananChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Total Pengeluaran</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <span class="text-success display-4">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</span>
                    </div>
                    <p class="text-muted">Total pengeluaran dari pemesanan yang sudah dibayar dan selesai</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @if($pemesananAkanDatang)
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Penyewaan Akan Datang</h4>
                </div>
                <div class="card-body">
                    <div class="media">
                        @if($pemesananAkanDatang->taman->foto)
                        <img src="{{ asset('storage/' . $pemesananAkanDatang->taman->foto) }}" 
                             class="mr-3 rounded" alt="Taman" style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                        <div class="mr-3 rounded bg-light" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-tree fa-3x text-muted"></i>
                        </div>
                        @endif
                        <div class="media-body">
                            <h5 class="mt-0">{{ $pemesananAkanDatang->taman->nama }}</h5>
                            <p class="mb-1">
                                <i class="fas fa-calendar-day mr-1"></i> 
                                {{ Carbon\Carbon::parse($pemesananAkanDatang->waktu_mulai)->format('d M Y H:i') }} - 
                                {{ Carbon\Carbon::parse($pemesananAkanDatang->waktu_selesai)->format('d M Y H:i') }}
                            </p>
                            <p class="mb-1">
                                <i class="fas fa-tag mr-1"></i> 
                                Keperluan: {{ $pemesananAkanDatang->keperluan }}
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-users mr-1"></i>
                                {{ $pemesananAkanDatang->jumlah_orang }} orang
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-whitesmoke text-center">
                    <a href="{{ route('pemesanan.show', $pemesananAkanDatang->id) }}" class="btn btn-info">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        @endif

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Taman Favorit Anda</h4>
                </div>
                <div class="card-body">
                    @if(count($tamanFavorit) > 0)
                        <div class="list-group">
                            @foreach($tamanFavorit as $taman)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $taman->taman->nama }}</strong>
                                    <p class="mb-0 text-muted small">{{ Str::limit($taman->taman->alamat, 50) }}</p>
                                </div>
                                <span class="badge badge-primary badge-pill">{{ $taman->total }} kali</span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">Anda belum memiliki taman favorit</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</section>

@if(auth()->user()->isAdmin())
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Grafik Taman Populer
        const tamanCtx = document.getElementById('tamanChart').getContext('2d');
        new Chart(tamanCtx, {
            type: 'pie',
            data: {
                labels: [
                    @foreach($tamanPopuler as $item)
                        '{{ $item->taman->nama }}',
                    @endforeach
                ],
                datasets: [{
                    data: [
                        @foreach($tamanPopuler as $item)
                            {{ $item->total }},
                        @endforeach
                    ],
                    backgroundColor: [
                        '#fc544b',
                        '#6777ef',
                        '#ffa426',
                        '#3abaf4',
                        '#63ed7a'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                plugins: {
                    legend: {
                        position: 'bottom',
                        display: true
                    }
                }
            }
        });
        
        // Grafik Pendapatan Bulanan
        const pendapatanCtx = document.getElementById('pendapatanChart').getContext('2d');
        new Chart(pendapatanCtx, {
            type: 'line',
            data: {
                labels: [
                    @foreach($pendapatanBulanan as $item)
                        '{{ $item["bulan"] }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: [
                        @foreach($pendapatanBulanan as $item)
                            {{ $item["pendapatan"] }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(103, 119, 239, 0.2)',
                    borderColor: '#6777ef',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@else
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Grafik Status Pemesanan untuk user
        const statusCtx = document.getElementById('statusPemesananChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: [
                    @foreach($statusPemesanan as $item)
                        '{{ $item["status"] }}',
                    @endforeach
                ],
                datasets: [{
                    data: [
                        @foreach($statusPemesanan as $item)
                            {{ $item["jumlah"] }},
                        @endforeach
                    ],
                    backgroundColor: [
                        @foreach($statusPemesanan as $item)
                            '{{ $item["warna"] }}',
                        @endforeach
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                plugins: {
                    legend: {
                        position: 'bottom',
                        display: true
                    }
                }
            }
        });
    });
</script>
@endif
@endsection 