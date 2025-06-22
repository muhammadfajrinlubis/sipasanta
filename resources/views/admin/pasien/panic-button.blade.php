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
                <div class="d-flex flex-wrap align-items-right">
                    <form method="GET" class="form-inline">
                        <label for="tanggal" class="mr-2">Filter Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control mr-2" value="{{ old('tanggal', $tanggal ?? '') }}">
                        <button type="submit" class="btn btn-primary mr-2">Filter</button>
                        <a href="/admin/pasien/panic-button" class="btn btn-secondary">Reset</a>
                    </form>
                </div>
            </header>
             <hr class="widget-separator">
            <div class="widget-body">
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
            <div class="widget-body">
                <div class="table-responsive">
                    <table id="default-datatable" class="table table-striped table-hover table-bordered" cellspacing="0" width="100%">
                        <thead class="bg-primary">
                            <tr>
                                <th>#</th>
                                <th>Nama Pasien</th>
                                <th>Ruangan</th>
                                <th>Kamar</th>
                                <th>Kendala</th>
                                <th>Belum Ditangani</th>
                                <th>Diproses</th>
                                <th>Selesai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($pasien as $index => $p)
                            @php
                                $panicLogs = $p->kamar->panicLogs ?? collect();
                                $grouped = $panicLogs->groupBy('status');
                            @endphp
                            <tr>
                                <td class="text-center align-middle-custom">{{ $index + 1 }}</td>
                                <td class="align-middle-custom">{{ $p->nama }}</td>
                                <td class="align-middle-custom">{{ $p->kamar->ruangan->nama ?? '-' }}</td>
                                <td class="align-middle-custom">{{ $p->kamar->nomor_kamar ?? '-' }}</td>
                                <td class="align-middle-custom">{{ $p->kendala ?? '-' }}</td>
                                <td>
                                    @if($grouped->has('belum_ditangani'))
                                        <ul class="list-unstyled mb-0">
                                            @foreach($grouped['belum_ditangani'] as $log)
                                                <li><span class="badge badge-danger">{{ $log->created_at->format('d-m-Y H:i:s') }}</span></li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td>
                                    @if($grouped->has('diproses'))
                                        <ul class="list-unstyled mb-0">
                                            @foreach($grouped['diproses'] as $log)
                                                <li><span class="badge badge-info">{{ $log->created_at->format('d-m-Y H:i:s') }}</span></li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td>
                                    @if($grouped->has('selesai'))
                                        <ul class="list-unstyled mb-0">
                                            @foreach($grouped['selesai'] as $log)
                                                <li><span class="badge badge-success">{{ $log->created_at->format('d-m-Y H:i:s') }}</span></li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td class="align-middle-custom">
                                    @php $lastLog = $panicLogs->last(); @endphp
                                    @if($lastLog)
                                    @if($lastLog && Auth::user()->level == '6')
                                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#statusModal{{ $lastLog->id }}">
                                        Ubah Status
                                    </button>

                                    <div class="modal fade" id="statusModal{{ $lastLog->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <form method="POST" action="{{ route('panic-logs.updateStatusPanicLog', $lastLog->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Ubah Status Panic Log</h5>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <select name="status" class="form-control">
                                                            <option value="belum_ditangani" {{ $lastLog->status == 'belum_ditangani' ? 'selected' : '' }}>Belum Ditangani</option>
                                                            <option value="diproses" {{ $lastLog->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                                            <option value="selesai" {{ $lastLog->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                        </select>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    @endif
                                </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    var table = $('#default-datatable').DataTable();

    window.Echo.channel('panic-logs')
        .listen('PanicLogCreated', (e) => {
            let panicLog = e;

            let countRows = table.rows().count() + 1;
            let createdAt = panicLog.created_at ? new Date(panicLog.created_at).toLocaleString() : '-';

            let newRow = `
                <tr>
                    <td class="text-center">${countRows}</td>
                    <td>${panicLog.pasien?.nama || '-'}</td>
                    <td>${panicLog.ruangan?.nama || '-'}</td>
                    <td>${panicLog.kamar?.nomor_kamar || '-'}</td>
                    <td>${panicLog.pasien?.kendala || '-'}</td>
                    <td><span class="text-muted">-</span></td>
                    <td><span class="text-muted">-</span></td>
                    <td><span class="text-muted">-</span></td>
                    <td><button class="btn btn-warning btn-sm">Ubah Status</button></td>
                </tr>
            `;
            table.row.add($(newRow)).draw(false);
        });
});
</script>
@endsection
