@extends('admin.layouts.app', [
    'activePage' => 'pengaduan',
])
@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="widget p-md clearfix">
            <div class="pull-left">
                <h1 class="widget-title" style="font-size: 30px; margin-bottom: 5px;">Data Pengaduan</h1>
                <small class="text-color">Pengaduan <span style="margin:0px 3px 0px 3px"> > </span> <a href="/admin/pengaduan">List Data Pengaduan</a></small>
            </div>
            <span class="pull-right fz-lg fw-500 counter"></span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="widget">
            <header class="widget-header">
                <div class="pull-left">
                    <h4 class="widget-title" style="font-size:24px;">
                        <i class="glyphicon glyphicon-list"></i> List Data Pengaduan
                    </h4>
                </div>
            </header>
            <hr class="widget-separator">
            <div class="widget-body">
                @if (session('error'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span>{{ session('error') }}</span>
                </div>
                @endif
                @if (session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span>{{ session('success') }}</span>
                </div>
                @endif
                <div class="table-responsive">
                    <table id="default-datatable" class="table table-striped table-hover table-bordered" cellspacing="0" width="100%">
                        <thead class="bg-primary">
                            <tr>
                                <th width="3%" class="text-center">#</th>
                                <th width="10%" class="text-center">Nama Pengaduan</th>
                                <th width="10%" class="text-center">Ruangan</th>
                                <th width="10%" class="text-center">Sarana</th>
                                <th width="10%" class="text-center">Tanggal Pengaduan</th>
                                <th width="5%" class="text-center">Tipe</th>
                                <th width="5%" class="text-center">Deskripsi</th>
                                <th width="5%" class="text-center">Status</th>
                                <th width="10%" class="text-center">Tanggal Selesai</th>
                                <th width="30%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pengaduan as $data)
                            <tr>
                                <td class="text-center align-middle-custom">{{ $loop->iteration }}</td>
                                <td class="align-middle-custom">{{ $data->userPengadu->name }}</td>
                                <td class="align-middle-custom">
                                    @if($data->ruangan)
                                        {{ $data->ruangan->nama }}
                                    @else
                                        <span class="text-danger">Data ruangan telah dihapus</span>
                                    @endif
                                </td>
                                <td class="align-middle-custom">
                                    @if($data->sarana)
                                        {{ $data->sarana->nama }}
                                    @else
                                        <span class="text-danger">Data sarana telah dihapus</span>
                                    @endif
                                </td>

                                <td class="align-middle-custom">{{  date('d F Y H:i:s', strtotime($data->tgl_pengaduan)) }}</td>
                                <td class="align-middle-custom">{{ $data->tipe }}</td>

                                <td class="align-middle-custom">{{ $data->deskripsi }}</td>
                                <td class="text-center align-middle-custom">
                                    @if($data->status == 'Menunggu Persetujuan Oleh Admin')
                                        <span class="label label-danger">Menunggu Persetujuan</span>
                                    @elseif($data->status == 'Dikerjakan Oleh Petugas')
                                        <span class="label label-warning">Sedang Dikerjakan</span>
                                    @elseif($data->status == 'selesai')
                                        <span class="label label-success">Selesai</span>
                                    @elseif($data->status == 'tidak_dapat_dikerjakan')
                                        <span class="label label-default">Tidak Dapat Dikerjakan</span>
                                    @elseif ($data->status == 'Ditolak')
                                        <span class="label label-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td class="align-middle-custom">
                                    @if($data->tgl_pukul_selesai == "")
                                    -
                                    @else
                                    {{ date('d F Y H:i:s', strtotime($data->tgl_pukul_selesai)) }}
                                    @endif
                                </td>
                                <td class="text-center align-middle-custom">
                                    @if(Auth::user() && Auth::user()->level == 2) <!-- Cek apakah user memiliki level 2 -->
                                    @if($data->status == 'Menunggu Persetujuan Oleh Admin')
                                        <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#assignModal-{{ $data->id }}">
                                            <i class="fa fa-paper-plane" data-toggle="tooltip" data-placement="top" title="Kirim ke Petugas"></i>
                                        </button>
                                        <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#tolakModal-{{ $data->id }}">
                                            <i class="fa fa-times" data-toggle="tooltip" title="Tolak Pengaduan"></i>
                                        </button>

                                    @endif
                                @endif

                                <a href="/admin/pengaduan/detail/{{ $data->id }}" class="btn btn-info btn-xs">
                                    <i class="glyphicon glyphicon-list-alt" data-toggle="tooltip" data-placement="top" title="Detail Data"></i>
                                </a>

                                <!-- Tombol untuk membuka modal Lihat Riwayat -->
                                <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#historyModal-{{ $data->id }}">
                                    <i class="fa fa-history" data-toggle="tooltip" data-placement="top" title="Lihat Riwayat"></i>
                                </button>

                                @if($data->foto)
                                    <a href="{{ asset('images/' . $data->foto) }}" class="btn btn-warning btn-xs" target="_blank">
                                        <i class="fa fa-camera" data-toggle="tooltip" data-placement="top" title="Lihat Foto Pengaduan"></i>
                                    </a>
                                @else
                                    <p>Tidak ada foto</p>
                                @endif

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

@foreach($pengaduan as $data)
<div class="modal fade" id="assignModal-{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="assignModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignModalLabel">Pilih Petugas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h2 class="text-center mb-4">Pilih Petugas</h2>
                <form action="/admin/pengaduan/updatepengaduan/{{ $data->id }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="petugas_id">Petugas</label>
                        <div>
                            @foreach($petugas as $pt)
                                <div class="form-check mb-2">
                                    <input type="checkbox" name="petugas_id[]" value="{{ $pt->id_user }}" class="form-check-input" id="petugas-{{ $pt->id }}">
                                    <label class="form-check-label d-flex align-items-center" for="petugas-{{ $pt->id }}">
                                        <a href="{{ $pt->foto ? url('public/profil/'.$pt->foto) : url('assets-admin/assets/images/user.png') }}" target="_blank" class="mr-2">
                                            <img src="{{ $pt->foto ? url('public/profil/'.$pt->foto) : url('assets-admin/assets/images/user.png') }}" class="rounded-circle" alt="Foto Petugas" width="50px" height="50px">
                                        </a>
                                        <span>{{ $pt->nama }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary btn-block">Kirim</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-danger btn-block" data-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Tolak Pengaduan -->
<div class="modal fade" id="tolakModal-{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="tolakModalLabel-{{ $data->id }}" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="/admin/pengaduan/tolak/{{ $data->id }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Alasan Penolakan</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="alasan">Masukkan alasan kenapa pengaduan ditolak</label>
            <textarea name="alasan" class="form-control" required rows="4" placeholder="Contoh: Pengaduan tidak valid karena ..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">Tolak Pengaduan</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endforeach

@foreach($pengaduan as $data)
<?php
$riwayat = DB::table('riwayat')->where('id_pengaduan', $data->id)->first(); // Mengubah ke where untuk mengambil berdasarkan id_pengaduan
?>
<div class="modal fade" id="historyModal-{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel-{{ $data->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historyModalLabel-{{ $data->id }}">Riwayat Pengaduan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h2 class="text-center mb-4">Riwayat Pengaduan</h2>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ date('d F Y H:i:s', strtotime($data->tgl_pengaduan)) }}</td>
                            <td>Pengaduan Dibuat</td>
                        </tr>

                        <tr>
                            <td>
                                @if($riwayat && $riwayat->tanggal) <!-- Memastikan $riwayat tidak null -->
                                    {{ date('d F Y H:i:s', strtotime($riwayat->tanggal)) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($riwayat && $riwayat->tanggal)
                                    Disetujui oleh Admin
                                @else
                                    Belum disetujui oleh Admin
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td>
                                @if($data->tgl_pukul_selesai)
                                    {{ date('d F Y H:i:s', strtotime($data->tgl_pukul_selesai)) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($data->tgl_pukul_selesai)
                                    Selesai
                                @else
                                    Belum selesai
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach


@endsection
