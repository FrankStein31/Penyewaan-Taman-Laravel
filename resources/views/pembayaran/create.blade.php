@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="section-body">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0">
                        <i class="fas fa-money-bill-wave text-primary mr-2"></i>
                        Pembayaran Pemesanan
                    </h4>
                </div>
                
                <div class="card-body">
                    <!-- Informasi Pemesanan -->
                    <div class="mb-4">
                        <h5 class="text-dark">Detail Pemesanan</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Kode Pemesanan</th>
                                    <td>{{ $pemesanan->kode }}</td>
                                </tr>
                                <tr>
                                    <th>Taman</th>
                                    <td>{{ $pemesanan->taman->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Mulai</th>
                                    <td>{{ \Carbon\Carbon::parse($pemesanan->waktu_mulai)->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Selesai</th>
                                    <td>{{ \Carbon\Carbon::parse($pemesanan->waktu_selesai)->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Total Harga</th>
                                    <td class="font-weight-bold text-primary">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="midtrans-tab" data-toggle="tab" href="#midtrans" role="tab" aria-controls="midtrans" aria-selected="true">
                                <i class="fas fa-credit-card mr-1"></i> Pembayaran Online
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="manual-tab" data-toggle="tab" href="#manual" role="tab" aria-controls="manual" aria-selected="false">
                                <i class="fas fa-upload mr-1"></i> Upload Bukti Transfer
                            </a>
                        </li>
                    </ul>
                    
                    <div class="tab-content mt-4" id="myTabContent">
                        <!-- Pembayaran Midtrans -->
                        <div class="tab-pane fade show active" id="midtrans" role="tabpanel" aria-labelledby="midtrans-tab">
                            <div class="text-center mb-4">
                                <p class="mb-4">Klik tombol di bawah untuk membayar menggunakan berbagai metode pembayaran</p>
                                <button id="pay-button" class="btn btn-primary btn-lg">
                                    <i class="fas fa-credit-card mr-1"></i> Bayar Sekarang
                                </button>
                            </div>
                            
                            <form action="{{ route('pembayaran.store') }}" method="POST" id="midtrans-form">
                                @csrf
                                <input type="hidden" name="metode_pembayaran" value="midtrans">
                                <input type="hidden" name="pemesanan_id" value="{{ $pemesanan->id }}">
                                <input type="hidden" name="jumlah" value="{{ $pemesanan->total_harga }}">
                                <input type="hidden" name="json_result" id="json-result">
                            </form>
                        </div>
                        
                        <!-- Upload Bukti Transfer -->
                        <div class="tab-pane fade" id="manual" role="tabpanel" aria-labelledby="manual-tab">
                            <div class="alert alert-info">
                                <h6 class="alert-heading font-weight-bold"><i class="fas fa-info-circle mr-2"></i>Informasi Rekening</h6>
                                <p class="mb-0">Silakan transfer ke rekening berikut:</p>
                                <ul class="mb-0 mt-2">
                                    <li>Bank BCA: 1234567890 a.n. DLHKP Kota Kediri</li>
                                    <li>Bank BRI: 0987654321 a.n. DLHKP Kota Kediri</li>
                                </ul>
                            </div>
                            
                            <form action="{{ route('pembayaran.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="metode_pembayaran" value="manual">
                                <input type="hidden" name="pemesanan_id" value="{{ $pemesanan->id }}">
                                <input type="hidden" name="jumlah" value="{{ $pemesanan->total_harga }}">
                                
                                <div class="form-group">
                                    <label class="font-weight-bold">Upload Bukti Transfer <span class="text-danger">*</span></label>
                                    <div class="custom-file">
                                        <input type="file" name="bukti_pembayaran" class="custom-file-input @error('bukti_pembayaran') is-invalid @enderror" id="bukti_pembayaran" required>
                                        <label class="custom-file-label" for="bukti_pembayaran">Pilih file</label>
                                        <small class="form-text text-muted">Format: JPG, PNG, JPEG. Maks: 2MB</small>
                                        @error('bukti_pembayaran')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane mr-1"></i> Kirim Bukti Pembayaran
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script>
    let payButton = document.getElementById('pay-button');
    payButton.addEventListener('click', function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                document.getElementById('json-result').value = JSON.stringify(result);
                document.getElementById('midtrans-form').submit();
            },
            onPending: function(result) {
                document.getElementById('json-result').value = JSON.stringify(result);
                document.getElementById('midtrans-form').submit();
            },
            onError: function(result) {
                alert("Pembayaran gagal!");
                console.log(result);
            },
            onClose: function() {
                alert('Anda menutup popup tanpa menyelesaikan pembayaran');
            }
        });
    });
    
    // File input
    $(document).ready(function() {
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    });
</script>
@endpush