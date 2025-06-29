@extends('layouts.app')

@section('title', 'Buat Pemesanan')

@section('content')
<section class="section">
    <!-- <div class="section-header">
        <h1>Buat Pemesanan</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item active"><a href="{{ route('pemesanan.index') }}">Pemesanan</a></div>
            <div class="breadcrumb-item">Buat Pemesanan</div>
        </div>
    </div> -->

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Form Pemesanan Taman</h4>
                    </div>
                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        @php
                            $jsonBookedDates = json_encode($bookedDates);
                        @endphp

                        <form action="{{ route('pemesanan.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="taman_id">Pilih Taman</label>
                                <select name="taman_id" id="taman_id" class="form-control select2 @error('taman_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Taman --</option>
                                    @foreach($taman as $t)
                                        <option value="{{ $t->id }}" 
                                                data-harga="{{ $t->harga_per_hari }}" 
                                                @if(isset($specificTaman) && $specificTaman->id == $t->id) selected 
                                                @elseif(old('taman_id') == $t->id) selected @endif>
                                            {{ $t->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('taman_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group" id="booked-dates-info" style="display:none;">
                                <label>Tanggal Sudah Dipesan:</label>
                                <ul id="booked-dates-list" style="font-size:0.95em;"></ul>
                            </div>

                            <div class="form-group">
                                <label>Durasi Pemesanan</label>
                                <!-- <div class="custom-control custom-radio">
                                    <input type="radio" id="durasi_satu_hari" name="durasi_tipe" value="satu_hari" class="custom-control-input" checked>
                                    <label class="custom-control-label" for="durasi_satu_hari">1 Hari</label>
                                </div> -->
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="durasi_beberapa_hari" name="durasi_tipe" value="beberapa_hari" class="custom-control-input" checked>
                                    <label class="custom-control-label" for="durasi_beberapa_hari">Lebih dari 1 Hari</label>
                                </div>
                            </div>

                            <!-- Form untuk 1 hari -->
                            <div id="form_satu_hari">
                                <div class="form-group">
                                    <label for="tanggal_mulai_satu">Tanggal Sewa</label>
                                    <input type="date" name="tanggal_mulai" id="tanggal_mulai_satu" class="form-control @error('tanggal_mulai') is-invalid @enderror" min="{{ date('Y-m-d') }}" value="{{ old('tanggal_mulai') }}">
                                    @error('tanggal_mulai')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Form untuk beberapa hari -->
                            <div id="form_beberapa_hari" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tanggal_mulai_range">Tanggal Mulai</label>
                                            <input type="date" name="tanggal_mulai" id="tanggal_mulai_range" class="form-control @error('tanggal_mulai') is-invalid @enderror" min="{{ date('Y-m-d') }}" value="{{ old('tanggal_mulai') }}">
                                            @error('tanggal_mulai')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tanggal_selesai_range">Tanggal Selesai</label>
                                            <input type="date" name="tanggal_selesai" id="tanggal_selesai_range" class="form-control @error('tanggal_selesai') is-invalid @enderror" min="{{ date('Y-m-d') }}" value="{{ old('tanggal_selesai') }}">
                                            @error('tanggal_selesai')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="keperluan">Keperluan</label>
                                <textarea name="keperluan" id="keperluan" class="form-control @error('keperluan') is-invalid @enderror" rows="3" required>{{ old('keperluan') }}</textarea>
                                @error('keperluan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="jumlah_orang">Jumlah Orang</label>
                                <input type="number" name="jumlah_orang" id="jumlah_orang" class="form-control @error('jumlah_orang') is-invalid @enderror" min="1" value="{{ old('jumlah_orang', 1) }}" required>
                                @error('jumlah_orang')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Harga per Hari</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text" class="form-control" id="harga_per_hari" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Total Hari</label>
                                        <input type="text" class="form-control" id="total_hari" value="1" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Total Pembayaran</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" class="form-control" id="total_pembayaran" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="{{ route('pemesanan.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    const bookedDates = {!! $jsonBookedDates !!};
    function formatDate(dateStr) {
        const d = new Date(dateStr);
        return d.toLocaleDateString('id-ID');
    }
    function showBookedDates(tamanId) {
        const list = document.getElementById('booked-dates-list');
        list.innerHTML = '';
        if (bookedDates[tamanId] && bookedDates[tamanId].length > 0) {
            bookedDates[tamanId].forEach(b => {
                if (b.tanggal_mulai === b.tanggal_selesai) {
                    list.innerHTML += `<li>${formatDate(b.tanggal_mulai)}</li>`;
                } else {
                    list.innerHTML += `<li>${formatDate(b.tanggal_mulai)} s/d ${formatDate(b.tanggal_selesai)}</li>`;
                }
            });
            document.getElementById('booked-dates-info').style.display = '';
        } else {
            list.innerHTML = '<li class="text-success">Belum ada yang dipesan</li>';
            document.getElementById('booked-dates-info').style.display = '';
        }
    }
    function isDateBooked(tamanId, tanggalMulai, tanggalSelesai) {
        if (!bookedDates[tamanId]) return false;
        const start = new Date(tanggalMulai);
        const end = new Date(tanggalSelesai);
        return bookedDates[tamanId].some(b => {
            const tglMulai = new Date(b.tanggal_mulai);
            const tglSelesai = new Date(b.tanggal_selesai);
            return (start <= tglSelesai && end >= tglMulai);
        });
    }
    function getTanggalMulai() {
        if ($('#durasi_satu_hari').is(':checked')) {
            return $('#tanggal_mulai_satu').val();
        } else {
            return $('#tanggal_mulai_range').val();
        }
    }
    function getTanggalSelesai() {
        if ($('#durasi_satu_hari').is(':checked')) {
            return $('#tanggal_mulai_satu').val();
        } else {
            return $('#tanggal_selesai_range').val();
        }
    }
    $(document).ready(function() {
        // Toggling durasi form
        $('input[name="durasi_tipe"]').change(function() {
            toggleDurasiForm();
            hitungTotalHari();
            hitungTotal();
        });

        function toggleDurasiForm() {
            if ($('#durasi_satu_hari').is(':checked')) {
                $('#form_satu_hari').show();
                $('#form_beberapa_hari').hide();
            } else {
                $('#form_satu_hari').hide();
                $('#form_beberapa_hari').show();
            }
            hitungTotalHari();
        }

        // Format number menjadi currency
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        // Update harga ketika pilihan taman berubah
        $('#taman_id').change(function() {
            showBookedDates(this.value);
            hitungTotal();
        });

        // Hitung total hari
        function hitungTotalHari() {
            let totalHari = 0;
            const mulai = getTanggalMulai();
            const selesai = getTanggalSelesai();
            if ($('#durasi_satu_hari').is(':checked')) {
                totalHari = mulai ? 1 : 0;
            } else {
                const tanggalMulai = new Date(mulai);
                const tanggalSelesai = new Date(selesai);
                if (!isNaN(tanggalMulai) && !isNaN(tanggalSelesai) && tanggalMulai <= tanggalSelesai) {
                    const diffTime = Math.abs(tanggalSelesai - tanggalMulai);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    totalHari = diffDays + 1;
                }
            }
            $('#total_hari').val(totalHari);
            hitungTotal();
            return totalHari;
        }

        // Hitung total pembayaran
        function hitungTotal() {
            const hargaPerHari = $('#taman_id option:selected').data('harga') || 0;
            $('#harga_per_hari').val(formatRupiah(hargaPerHari));
            
            const totalHari = parseInt($('#total_hari').val()) || 0;
            const jumlahOrang = parseInt($('#jumlah_orang').val()) || 1;
            const totalBayar = hargaPerHari * totalHari * jumlahOrang;
            
            $('#total_pembayaran').val(formatRupiah(totalBayar));
        }

        // Event listener untuk perubahan tanggal
        $('#tanggal_mulai_satu, #tanggal_mulai_range, #tanggal_selesai_range').change(function() {
            hitungTotalHari();
            hitungTotal();
        });

        // Event listener untuk perubahan jumlah orang
        $('#jumlah_orang').change(function() {
            hitungTotal();
        });

        // Validasi tanggal bentrok saat submit
        $('form').on('submit', function(e) {
            const tamanId = $('#taman_id').val();
            let mulai = getTanggalMulai();
            let selesai = getTanggalSelesai();
            if (tamanId && mulai && selesai && isDateBooked(tamanId, mulai, selesai)) {
                alert('Tanggal yang dipilih sudah dipesan. Silakan pilih tanggal lain!');
                e.preventDefault();
                return false;
            }
        });

        // Tampilkan tanggal booked saat load jika sudah ada pilihan
        if ($('#taman_id').val()) {
            showBookedDates($('#taman_id').val());
        }

        // Inisialisasi
        $('#taman_id').trigger('change');
        toggleDurasiForm();
    });
</script>
@endpush 