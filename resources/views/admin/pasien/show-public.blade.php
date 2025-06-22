<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pasien</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body { padding-top: 40px; background-color: #f8f9fa; }
        .card { border-radius: 10px; }
        .qr-code { margin-top: 20px; }
        .badge { font-size: 100%; }
        .timeline {
            display: flex; justify-content: space-between;
            position: relative; margin: 20px 0;
        }
        .timeline::before {
            content: ''; position: absolute; top: 15px; left: 0;
            width: 100%; height: 4px; background: #dee2e6; z-index: 0;
        }
        .timeline-step {
            flex: 1; text-align: center; position: relative; z-index: 1;
        }
        .timeline-step .circle {
            width: 30px; height: 30px; margin: 0 auto;
            border-radius: 50%; background-color: #ccc;
            color: white; line-height: 30px; font-size: 14px;
        }
        .timeline-step.active .circle { background-color: #0d6efd; }
        .timeline-step.done .circle { background-color: #28a745; }
        .timeline-step .label { margin-top: 8px; font-size: 12px; }
        .timeline {
    display: flex;
    justify-content: space-between;
    position: relative;
    margin: 20px 0;
    counter-reset: step;
}

.timeline::before {
    content: '';
    position: absolute;
    top: 15px;
    left: 0;
    width: 100%;
    height: 4px;
    background: #dee2e6;
    z-index: 0;
}

.timeline-step {
    flex: 1;
    text-align: center;
    position: relative;
    z-index: 1;
}

.timeline-step .circle {
    width: 30px;
    height: 30px;
    margin: 0 auto;
    border-radius: 50%;
    background-color: #ccc;
    color: white;
    line-height: 30px;
    font-size: 14px;
    position: relative;
    z-index: 2;
}

.timeline-step.done .circle {
    background-color: #28a745; /* Hijau untuk langkah selesai */
}

.timeline-step.active .circle {
    background-color: #0d6efd; /* Biru untuk langkah aktif */
}

.timeline-step .label {
    margin-top: 8px;
    font-size: 12px;
}

/* Garis sebelum langkah */
.timeline-step::before {
    content: '';
    position: absolute;
    top: 15px;
    left: 0;
    width: 100%;
    height: 4px;
    background-color: #dee2e6; /* Default abu-abu */
    z-index: 1;
}

/* Warna garis untuk langkah selesai */
.timeline-step.done::before {
    background-color: #28a745;
}

/* Warna garis untuk langkah aktif */
.timeline-step.active::before {
    background-color: #0d6efd;
}

    </style>

</head>
<body>
<div class="container">

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Card Detail Pasien --}}
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0">Detail Pasien</h4>
        </div>
        <div class="card-body">
            <div class="row">

                {{-- Kiri: Detail Pasien --}}
                <div class="col-md-6 mb-3">
                    <table class="table table-bordered">
                        <tr><th>No. RM</th><td>{{ $pasien->no_rm }}</td></tr>
                        <tr><th>Nama</th><td>{{ $pasien->nama }}</td></tr>
                        <tr><th>Jenis Kelamin</th><td>{{ $pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                        <tr><th>Tanggal Lahir</th><td>{{ $pasien->tanggal_lahir ? \Carbon\Carbon::parse($pasien->tanggal_lahir)->format('d M Y') : '-' }}</td></tr>
                        <tr><th>Alamat</th><td>{{ $pasien->alamat ?? '-' }}</td></tr>
                        <tr><th>No. Telepon</th><td>{{ $pasien->no_telepon ?? '-' }}</td></tr>
                        <tr><th>Ruangan</th><td>{{ $pasien->ruangan->nama ?? '-' }}</td></tr>
                        <tr><th>Kamar</th><td>{{ $pasien->kamar->nomor_kamar ?? '-' }}</td></tr>
                        <tr><th>Status</th><td>
                            @if ($pasien->status == 'rawat')
                                <span class="badge bg-warning text-dark">Dirawat</span>
                            @elseif ($pasien->status == 'pulang')
                                <span class="badge bg-success">Dipulangkan</span>
                            @else
                                <span class="badge bg-secondary">-</span>
                            @endif
                        </td></tr>
                        <tr><th>Tanggal Daftar</th><td>{{ $pasien->created_at->format('d M Y H:i') }}</td></tr>
                    </table>
                </div>

                {{-- Kanan: QR & Tombol --}}
                <div class="col-md-6 text-center">
                    <h5>QR Code Pasien</h5>
                    <div class="qr-code">
                        {!! QrCode::size(200)->generate(route('pasien.showPublic', $pasien->id)) !!}
                    </div>
                    <p class="text-muted mt-2">Scan QR untuk melihat halaman ini kembali</p>

                    <div class="d-flex gap-2 mt-4 justify-content-center">
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#laundryModal">
                            <i class="fas fa-tshirt"></i> Ajukan Permintaan Laundry
                        </button>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#riwayatLaundryModal">
                            <i class="fas fa-history"></i> Lihat Riwayat Laundry
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Permintaan Laundry --}}
<div class="modal fade" id="laundryModal" tabindex="-1" aria-labelledby="laundryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('pasien.laundryRequest') }}" method="POST">
            @csrf
            <input type="hidden" name="id_pasien" value="{{ $pasien->id }}">
            <input type="hidden" name="id_ruangan" value="{{ $pasien->ruangan->id ?? '' }}">
            <input type="hidden" name="nomr" value="{{ $pasien->no_rm }}">

            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Form Permintaan Laundry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        <li class="list-group-item"><strong>No. RM:</strong> {{ $pasien->no_rm }}</li>
                        <li class="list-group-item"><strong>Nama:</strong> {{ $pasien->nama }}</li>
                        <li class="list-group-item"><strong>Ruangan:</strong> {{ $pasien->ruangan->nama ?? '-' }}</li>
                        <li class="list-group-item"><strong>Kamar:</strong> {{ $pasien->kamar->nomor_kamar ?? '-' }}</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Kirim Permintaan</button>
                </div>
            </div>
        </form>
    </div>
</div>

@php
    $icons = [
        'Menunggu Persetujuan' => 'fas fa-hourglass-start',
        'Disetujui' => 'fas fa-check-circle',
        'Dijemput' => 'fas fa-truck',
        'Diproses' => 'fas fa-cogs',
        'Selesai' => 'fas fa-check-double',
        'Diantar' => 'fas fa-shipping-fast'
    ];
@endphp


{{-- Modal Riwayat Laundry --}}
<div class="modal fade" id="riwayatLaundryModal" tabindex="-1" aria-labelledby="riwayatLaundryLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Riwayat Laundry Pasien</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                @if($riwayatLaundry->isEmpty())
                    <div class="alert alert-info">Belum ada riwayat laundry.</div>
                @else
                    <div class="accordion" id="accordionLaundry">
                        @foreach ($riwayatLaundry as $index => $data)
                            @php
                                $statusMap = ['0' => 0, '1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5];
                                $currentStep = $statusMap[$data->keterangan] ?? 0;
                                $steps = array_keys($icons);
                            @endphp
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $index }}">
                                    <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $index }}"
                                            aria-expanded="{{ $index == 0 ? 'true' : 'false' }}"
                                            aria-controls="collapse{{ $index }}">
                                        {{ \Carbon\Carbon::parse($data->created_at)->format('d M Y H:i') }} - Petugas: {{ $data->petugasLaundry->nama ?? '-' }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" data-bs-parent="#accordionLaundry">
                                    <div class="accordion-body">
                                        <div class="timeline">
                                            @foreach ($steps as $stepIndex => $label)
                                                <div class="timeline-step {{ $currentStep == $stepIndex ? 'active' : ($currentStep > $stepIndex ? 'done' : '') }}">
                                                    <div class="circle"><i class="{{ $icons[$label] }}"></i></div>
                                                    <div class="label">{{ $label }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                         {{-- Tampilkan berat dan biaya jika status sudah diproses --}}
                                        @if ($currentStep >= 2)
                                            <hr>
                                            <p><strong>Selesai Pada:</strong>
                                                {{ $data->siap_pada ? \Carbon\Carbon::parse($data->siap_pada)->timezone('Asia/Jakarta')->translatedFormat('d F Y H:i') : '-' }}
                                            </p>
                                            <p><strong>Berat Laundry:</strong> {{ $data->berat ?? '-' }} kg</p>
                                            <p><strong>Biaya Laundry:</strong> Rp {{ number_format($data->biaya, 3 , ',', '.') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>


{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
