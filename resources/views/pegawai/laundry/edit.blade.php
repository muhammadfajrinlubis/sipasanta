@extends('admin.layouts.app',
['activePage' => 'permintaan_laundry'])

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="widget p-md clearfix">
            <div class="pull-left">
                <h1 class="widget-title" style="font-size: 30px; margin-bottom: 5px;">Edit Permintaan Laundry</h1>
                <small class="text-color">Permintaan Laundry
                    <span style="margin:0px 3px 0px 3px"> > </span>
                    <a href="/pegawai/laundry">Permintaan Laundry</a>
                    <span style="margin:0px 3px 0px 3px"> > </span>
                    <a href="/pegawai/laundry/edit/{{ $laundry->id }}">Edit Data Permintaan Laundry</a>
                </small>
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
                        <i class="glyphicon glyphicon-edit"></i> Edit Permintaan Laundry
                    </h4>
                </div>
                <div class="pull-right">
                    <a href="{{ url('/pegawai/laundry') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>
            </header>
            <hr class="widget-separator">
            <div class="widget-body">
                <!-- Display Messages -->
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Form for Editing Laundry Request -->
                <form action="/pegawai/laundry/update/{{ $laundry->id }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}


                    <div class="row">
                        <!-- Nama Pasien -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Pasien</label>
                                <select class="form-control select2" name="nomr" required>
                                    <option value="">-- Pilih Nama Pasien --</option>
                                    @foreach($pasien as $data)
                                        <option value="{{ $data->id }}" {{ $data->id == $laundry->nomr ? 'selected' : '' }}>
                                            {{ $data->no_mr }} | {{ $data->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Ruangan -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Ruangan</label>
                                <select class="form-control select2" name="id_ruangan" required>
                                    <option value="">-- Pilih Ruangan --</option>
                                    @foreach($ruangan as $data)
                                        <option value="{{ $data->id }}" {{ $data->id == $laundry->id_ruangan ? 'selected' : '' }}>
                                            {{ $data->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Tanggal Pengaduan -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Pengaduan</label>
                                <input type="date" name="tanggal" class="form-control" value="{{ $laundry->tanggal }}" required>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-1 mr-2">
                        <span class="glyphicon glyphicon-floppy-save"></span> Update Data
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
