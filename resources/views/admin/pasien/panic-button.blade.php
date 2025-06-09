@extends('admin.layouts.app', ['activePage' => 'panic'])

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="widget p-md clearfix">
            <div class="pull-left">
                <h1 class="widget-title" style="font-size: 30px; margin-bottom: 5px;">Data Panic Button Pasien</h1>
                <small class="text-color">
                    Data Master <span style="margin:0 3px;"> > </span>
                    <a href="/admin/pasien/panic-button">List Data Panic Button Pasien</a>
                </small>
            </div>
            <span class="pull-right fz-lg fw-500 counter"></span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="widget">
            <header class="widget-header d-flex justify-content-between align-items-center flex-wrap">
                <h4 class="widget-title mb-2" style="font-size:24px;">
                    <i class="glyphicon glyphicon-list"></i> List Data Panic Button Pasien
                </h4>
                {{-- Filter Tanggal + Tanggal Hari Ini --}}
                <div class="d-flex flex-wrap align-items-right">
                    {{-- Form Filter --}}
                    <form method="GET" class="form-inline">
                        <label for="tanggal" class="mr-2">Filter Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control mr-2" value="{{ old('tanggal', $tanggal ?? '') }}" >
                        <button type="submit" class="btn btn-primary mr-2">Filter</button>
                        <a href="/admin/pasien/panic-button" class="btn btn-secondary">Reset</a>
                    </form>
                </div>
            </header>
            <hr class="widget-separator">
            <div class="widget-body">
                {{-- Alert Success/Error --}}
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <span>{{ session('error') }}</span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <span>{{ session('success') }}</span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="table-responsive">
                <table id="default-datatable" class="table table-striped table-hover table-bordered" cellspacing="0" width="100%">
                <thead class="bg-primary">
                <tr>
                    <th width="5%" class="text-center">#</th>
                    <th>Nama Pasien</th>
                    <th>Ruangan</th>
                    <th>Kamar</th>
                    <th>Kendala</th>
                    <th>Riwayat Panic Button</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pasien as $index => $p)
                <tr>
                    <td class="text-center align-middle-custom">{{ $index + 1 }}</td>
                    <td>{{ $p->nama }}</td>
                    <td>{{ $p->ruangan->nama ?? '-' }}</td>
                    <td>{{ $p->kamar->nomor_kamar ?? '-' }}</td>
                    <td>{{ $p->kendala ?? '-' }}</td>
                    <td>
                        @if($p->kamar && $p->kamar->panicLogs->count())
                            <ul class="list-unstyled mb-0">
                                @foreach($p->kamar->panicLogs as $log)
                                    <li>
                                        <span class="badge badge-danger">
                                            {{ $log->created_at->format('d-m-Y H:i:s') }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">Tidak ada riwayat</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data pasien dengan riwayat panic button.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
