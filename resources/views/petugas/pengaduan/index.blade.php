@extends('admin.layouts.app', ['activePage' => 'pengaduan',])
@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="widget p-md clearfix">
            <div class="pull-left">
                <h1 class="widget-title" style="font-size: 30px; margin-bottom: 5px;">Data Pengaduan</h1>
                <small class="text-color">Pengaduan <span style="margin:0px 3px 0px 3px"> > </span> <a href="/pegawai/pengaduan">List Data Pengaduan</a></small>
            </div>
        </div>
        <!-- .widget -->
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="widget">
            <header class="widget-header">
                <h4 class="widget-title" style="font-size:24px;">
                    <i class="glyphicon glyphicon-list"></i> List Data Pengaduan
                </h4>
            </header>
            <!-- .widget-header -->
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
                                <th width="20%" class="text-center">Nama Pengadu</th>
                                <th width="20%" class="text-center">Tanggal Pengaduan</th>
                                <th width="10%" class="text-center">Status</th>

                                <th width="10%" class="text-center">Aksi</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pengaduan as $data)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $data->userPengadu->name }}</td> <!-- Tampilkan nama pengadu -->
                                <td>{{  date('d F Y', strtotime($data->tgl_pengaduan)) }}</td>
                                <td class="text-center">
                                    @if($data->status == 'Menunggu Persetujuan Oleh Admin')
                                        <span class="label label-danger">Menunggu Persetujuan</span>
                                    @elseif($data->status == 'Dikerjakan Oleh Petugas')
                                        <span class="label label-warning">Sedang Dikerjakan Oleh Petugas</span>
                                    @elseif($data->status == 'selesai')
                                        <span class="label label-success">Selesai</span>
                                    @elseif($data->status == 'tidak_dapat_dikerjakan')
                                        <span class="label label-default">Tidak Dapat Dikerjakan</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($data->status != 'selesai')
                                    <a href="/petugas/pengaduan/aksi/{{ $data->id }}" class="btn btn-success btn-xs">
                                        <i class="fa fa-play" data-toggle="tooltip" data-placement="top" title="Kerjakan"></i>
                                    </a>
                                    @endif
                                    <a href="/petugas/pengaduan/detail/{{ $data->id }}" class="btn btn-info btn-xs">
                                        <i class="glyphicon glyphicon-list-alt" data-toggle="tooltip" data-placement="top" title="Detail Data"></i>
                                    </a>

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
            <!-- .widget-body -->
        </div>
        <!-- .widget -->
    </div>
    <!-- END column -->
</div><!-- .row -->
@endsection
