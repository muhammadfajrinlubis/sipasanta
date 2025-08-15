@extends('admin.layouts.app', [
    'activePage' => 'permintaan_laundry',
])

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="widget p-md clearfix">
            <div class="pull-left">
                <h1 class="widget-title" style="font-size: 30px; margin-bottom: 5px;">Data Laundry</h1>
                <small class="text-color">Permintaan Laundry<span style="margin:0px 3px 0px 3px"> > </span> <a href="/admin/laundry">List Data laundry</a></small>
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
                        <i class="glyphicon glyphicon-list"></i> List Data Laundry
                    </h4>
                </div>
                <div class="pull-right">
                    {{-- <button class="btn btn-dark btn-sm" data-toggle="modal" data-target="#import">
                        <i class="fa fa-upload"></i> Import Data
                    </button> --}}
                </div>
                        {{-- FILTER TANGGAL DAN OPSI TAMPIL --}}
                <div class="row mb-3">
                    <div class="col-md-12">
                        <form method="GET" action="/admin/laundry" class="form-inline">
                            <label for="tanggal" class="mr-2">Filter Tanggal:</label>
                            <input type="date" id="tanggal" name="tanggal" class="form-control mr-2"
                                value="{{ old('tanggal', request('tanggal', $selectedDate ?? '') ) }}"
                                {{ request()->has('show_all') ? 'disabled' : '' }}>

                            <button type="submit" class="btn btn-primary btn-sm mr-2">Tampilkan</button>
                            <a href="/admin/laundry" class="btn btn-secondary btn-sm mr-3">Reset</a>

                            {{-- Opsi tampil semua --}}
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="show_all" id="show_all" value="1"
                                    {{ request()->has('show_all') ? 'checked' : '' }}
                                    onchange="this.form.submit()">
                                <label class="form-check-label" for="show_all">Tampilkan Semua Data</label>
                            </div>
                        </form>
                    </div>
                </div>
            </header>
            <hr class="widget-separator">
            <div class="widget-body">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <span>{{ session('error')}}</span>
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <span>{{ session('success')}}</span>
                    </div>
                @endif
                <div class="table-responsive">
                    <table id="default-datatable" class="table table-striped table-hover table-bordered" cellspacing="0" width="100%">
                        <thead class="bg-primary">
                            <tr>
                               <th width="3%" class="text-center">#</th>
                                <th width="10%" class="text-center">Tanggal</th>
                                <th width="10%" class="text-center">Nama Pasien</th>
                                <th width="10%" class="text-center">Nomor Mr</th>
                                <th width="10%" class="text-center">Ruangan</th>
                                <th width="10%" class="text-center">Kamar</th>
                                <th width="10%" class="text-center">Berat</th>
                                 <th width="10%" class="text-center">Biaya</th>
                                 <th width="10%" class="text-center">Keterangan</th>
                                <th width="15%" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                           @foreach($laundry as $key => $data)
                        <tr>
                            <td class="text-center align-middle-custom">{{ $key + 1 }}</td>
                            <td class="align-middle-custom">{{ $data->tanggal }}</td>
                            <td class="align-middle-custom">{{ $data->pasien->nama ?? 'Pasien telah dihapus' }}</td>
                            <td class="align-middle-custom">{{ $data->nomr }}</td>
                            <td class="align-middle-custom">
                                @if($data->ruangan && $data->ruangan->nama)
                                    {{ $data->ruangan->nama }}
                                @else
                                    <span class="text-danger">Data ruangan telah dihapus</span>
                                @endif
                            </td>
                            <td class="align-middle-custom">{{ $data->pasien->kamar->nomor_kamar ?? '-' }}</td>

                            <td class="align-middle-custom">{{ $data->berat }} kg</td>
                            <td class="align-middle-custom">Rp {{ number_format($data->biaya, 3, ',', '.') }}</td>
                            <td class="text-center align-middle-custom">
                                @switch($data->keterangan)
                                    @case('0')
                                        <span class="label label-danger">Menunggu Persetujuan Oleh Admin</span>
                                        @break
                                    @case('1')
                                        <span class="label label-warning">Disetujui Oleh Admin</span>
                                        @break
                                    @case('2')
                                        <span class="label label-success">Laundry Telah Dijemput Oleh Petugas Laundry</span>
                                        @break
                                    @case('3')
                                        <span class="label label-info">Sedang Diproses Oleh Petugas Laundry</span>
                                        @break
                                    @case('4')
                                        <span class="label label-primary">Telah Selesai dan Akan Diantar Ke Ruangan</span>
                                        @break
                                    @case('5')
                                        <span class="label label-default">Telah Diantar Ke Ruangan Oleh Petugas</span>
                                        @break
                                    @case('tidak_dapat_dikerjakan')
                                        <span class="label label-default alasan-toggle" data-id="{{ $data->id }}">Tidak Dapat Dikerjakan</span>
                                        @break
                                @endswitch
                            </td>
                            <td class="text-center align-middle-custom">
                                @if(auth()->user()->level == 2 && $data->keterangan <= 0)
                                    <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#send-modal-{{ $data->id }}">
                                        <i class="fa fa-paper-plane" title="Kirim ke Petugas"></i>
                                    </button>
                                @else
                                    <button class="btn btn-secondary btn-xs" disabled title="Hanya Level 2 yang bisa kirim ke petugas">
                                        <i class="fa fa-paper-plane"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>

                        <!-- Modal -->
                        <div class="modal fade" id="send-modal-{{ $data->id }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <h2 class="text-center">Apakah Anda Yakin Untuk Mengirim Data ini?</h2>
                                        <hr>
                                        <div class="form-group">
                                            <label>Nama Pasien</label>
                                            <input type="text" class="form-control" readonly value="{{ $data->pasien->nama ?? 'Pasien telah dihapus' }}">
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <a href="{{ url('/admin/laundry/kirim/'.$data->id) }}" class="btn btn-primary btn-block">Ya</a>
                                            </div>
                                            <div class="col-md-6">
                                                <button type="button" class="btn btn-danger btn-block" data-dismiss="modal">Tidak</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
