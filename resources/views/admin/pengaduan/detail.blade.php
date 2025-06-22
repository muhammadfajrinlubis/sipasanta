@extends('admin.layouts.app', [
    'activePage' => 'pengaduan',
])

@section('content')
@if ($pengaduan)
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="widget p-md clearfix">
            <div class="pull-left">
                <h1 class="widget-title" style="font-size: 30px; margin-bottom: 5px;">Data Pengaduan</h1>
                <small class="text-color">
                    Pengaduan
                    <span style="margin:0px 3px;"> > </span>
                    <a href="/admin/pengaduan">Data Pengaduan</a>
                    <span style="margin:0px 3px;"> > </span>

                    @if(isset($pengaduan))
                        <a href="/admin/pengaduan/detail/{{ $pengaduan->id }}">Detail Data Pengaduan</a>
                    @else
                        <span style="color: gray;">Detail Data Pengaduan Tidak Tersedia</span>
                    @endif
                </small>

            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="widget">
            <header class="widget-header">
                <div class="pull-left">
                    <h4 class="widget-title" style="font-size:24px;">
                        <i class="glyphicon glyphicon-list-alt"></i> Detail Data Pengaduan
                    </h4>
                </div>
                <div class="pull-right">
                    <a href="{{ url('/admin/pengaduan') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>
            </header>
            <hr class="widget-separator">
            <div class="widget-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5>Informasi Pengaduan</h5>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label>Nama Pengadu</label>
                                    <input type="text" class="form-control" readonly value="{{ $pengaduan->userPengadu->name }}">
                                </div>
                                <div class="form-group">
                                    <label>Ruangan</label>
                                    <input type="text" class="form-control" readonly value="{{ $pengaduan->ruangan ? $pengaduan->ruangan->nama : 'Data ruangan telah dihapus' }}">
                                </div>
                                <div class="form-group">
                                    <label>Sarana</label>
                                    <input type="text" class="form-control" readonly value="{{ $pengaduan->sarana ? $pengaduan->sarana->nama : 'Data sarana telah dihapus' }}">
                                </div>
                                <div class="form-group">
                                    <label>Tanggal Pengaduan</label>
                                    <input type="text" class="form-control" readonly value="{{ date('d F Y H:i:s', strtotime($pengaduan->tgl_pengaduan)) }}">
                                </div>
                                <div class="form-group">
                                    <label>Tipe Pengaduan</label>
                                    <input type="text" class="form-control" readonly value="{{ $pengaduan->tipe }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5>Detail Pengaduan</h5>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label>Foto</label>
                                    @if($pengaduan->foto)
                                        <a href="{{ url('images/') }}/{{$pengaduan->foto}}" target="_blank">
                                            <img src="{{ asset('images/' . $pengaduan->foto) }}" alt="Foto" class="img-thumbnail" style="max-width: 100%;">
                                        </a>
                                    @else
                                        <p>Tidak ada foto</p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Deskripsi Pengaduan</label>
                                    <textarea class="form-control" rows="4" readonly>{{ $pengaduan->deskripsi }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5>Status Pengaduan</h5>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label>Status</label>
                                    <input type="text" class="form-control" readonly value="{{ $pengaduan->status }}">
                                </div>
                                <div class="form-group">
                                    <label>Tanggal & Waktu Selesai</label>
                                    <input type="text" class="form-control" readonly value="{{ $pengaduan->tgl_pukul_selesai ?? '-' }}">
                                </div>
                                <div class="form-group">
                                    <label>Nama Petugas</label>
                                    @php
                                        $id_petugas = explode(',', $pengaduan->id_petugas);
                                        $petugas = \App\Models\User::whereIn('id', $id_petugas)->get();
                                    @endphp
                                    <ul>
                                        @foreach($petugas as $p)
                                            <li>{{ $loop->iteration }}. {{ $p->name }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="form-group">
                                    <label>Bukti Petugas</label>
                                    @if($pengaduan->bukti_petugas)
                                        <a href="{{ url('images/') }}/{{$pengaduan->bukti_petugas}}" target="_blank">
                                            <img src="{{ asset('images/' . $pengaduan->bukti_petugas) }}" alt="Bukti Petugas" class="img-thumbnail" style="max-width: 100%;">
                                        </a>
                                    @else
                                        <p>Tidak ada bukti</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5>Penyelesaian Pengaduan</h5>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label>Alasan Jika Tidak Dapat Dikerjakan</label>
                                    <textarea class="form-control" rows="4" readonly>{{ $pengaduan->alasan ?? 'N/A' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="widget">
            <div class="widget-header text-center">
                <h4 class="widget-title">Rating Dari Pegawai</h4>
            </div>
            <hr class="widget-separator">
            <div class="widget-body text-center">
                @if ($pengaduan->rating)
                    <div class="star-display">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="star {{ $i <= $pengaduan->rating->nilai_rating ? 'filled' : '' }}">&#9733;</span>
                        @endfor
                    </div>
                    <p><strong>Komentar:</strong> {{ $pengaduan->rating->komentar }}</p>
                @else
                    <p>Belum ada rating untuk pengaduan ini.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@else
    {{-- Kalau data TIDAK ADA --}}
    <div class="row">
        <div class="col-md-12 text-center">
            <div class="widget p-md clearfix">
                <h1 style="font-size: 30px; color: red;">Data Pengaduan Tidak Ditemukan</h1>
                <p>Data yang Anda cari tidak tersedia atau telah dihapus.</p>
                <a href="{{ url('/admin/pengaduan') }}" class="btn btn-primary">
                    <i class="fa fa-arrow-left"></i> Kembali ke Data Pengaduan
                </a>
            </div>
        </div>
    </div>
@endif

<style>
.rating {
    direction: rtl;
    display: inline-flex;
}
.star-display {
    display: inline-flex;
    font-size: 2rem;
    color: #ccc;
}
.star.filled {
    color: #f39c12;
}
.rating input {
    display: none;
}
.rating label {
    font-size: 2rem;
    color: #ccc;
    cursor: pointer;
    transition: color 0.2s ease-in-out;
}
.rating input:checked ~ label,
.rating label:hover,
.rating label:hover ~ label {
    color: #f39c12;
}
</style>
@endsection
