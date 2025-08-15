@extends('admin.layouts.app', ['activePage' => 'permintaan_laundry'])
@section('content')
<div class="row">
   <div class="col-md-12 col-sm-12">
      <div class="widget p-md clearfix">
         <div class="pull-left">
            <h1 class="widget-title" style="font-size: 30px; margin-bottom: 5px;">Data Laundry</h1>
            <small class="text-color">Permintaan Laundry<span style="margin:0px 3px 0px 3px"> > </span> <a href="/admin/laundry">List Data laundry</a></small>
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
                  <i class="glyphicon glyphicon-list"></i> List Data Laundry
               </h4>
            </div>
            {{-- FILTER TANGGAL DAN OPSI TAMPIL --}}
            <div class="row mb-3">
                <div class="col-md-12">
                    <form method="GET" action="/petugaslaundry/laundry" class="form-inline">
                        <label for="tanggal" class="mr-2">Filter Tanggal:</label>
                        <input type="date" id="tanggal" name="tanggal" class="form-control mr-2"
                            value="{{ old('tanggal', request('tanggal', $selectedDate ?? '') ) }}"
                            {{ request()->has('show_all') ? 'disabled' : '' }}>

                        <button type="submit" class="btn btn-primary btn-sm mr-2">Tampilkan</button>
                        <a href="/petugaslaundry/laundry" class="btn btn-secondary btn-sm mr-3">Reset</a>

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
                        <th>Tanggal</th>
                        <th>Nama Pasien</th>
                        <th>Nomor Mr</th>
                        <th>Ruangan</th>
                        <th>Kamar</th>
                        <th>Berat</th>
                        <th width="15%" class="text-center">Biaya</th>
                        <th>Keterangan</th>
                        <th>Siap Pada</th>
                        <th width="15%" class="text-center">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php $no = 1; ?>
                     @foreach($laundry as $data)
                     <tr>
                        <td class="text-center align-middle-custom">{{ $no++ }}</td>
                        <td class="align-middle-custom">{{ $data->tanggal }}</td>
                        <td class="align-middle-custom">{{ $data->pasien->nama }}</td>
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
                        <td class="align-middle-custom">Rp {{ number_format($data->biaya, 3, ',', '.') }}
                        </td>
                        <td class="text-center align-middle-custom">
                            @if($data->keterangan == '0')
                            <span class="label label-danger">Menunggu Persetujuan Oleh Admin</span>
                        @elseif($data->keterangan == '1')
                            <span class="label label-warning">Disetujui Oleh Admin</span>
                        @elseif($data->keterangan == '2')
                            <span class="label label-success">Laundry Telah Dijemput Oleh Petugas Laundry</span>
                        @elseif($data->keterangan == '3')
                            <span class="label label-info">Sedang Diproses Oleh Petugas Laundry</span>
                        @elseif($data->keterangan == '4')
                            <span class="label label-primary">Telah Selesai dan Akan Diantar Ke Ruangan</span>
                        @elseif($data->keterangan == '5')
                            <span class="label label-default">Telah Diantar Ke Ruangan Oleh Petugas</span>
                        @elseif($data->keterangan == 'tidak_dapat_dikerjakan')
                            <span class="label label-default alasan-toggle" data-id="{{ $data->id }}">Tidak Dapat Dikerjakan</span>
                        @endif

                        </td>
                        <td class="align-middle-custom">
                            {{ $data->siap_pada ? \Carbon\Carbon::parse($data->siap_pada)->locale('id')->timezone('Asia/Jakarta')->translatedFormat('l, d F Y H:i') : '-' }}
                        </td>
                        <td class="text-center align-middle-custom" width="15%">
                            @if($data->keterangan == '1')
                             <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#send-modal-{{ $data->id }}">
                            <i class="fa fa-truck "data-toggle="tooltip" data-placement="top" title="Jemput Laundry"></i>
                                </button>
                            @elseif ($data->keterangan == '2')
                                <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#data-{{ $data->id }}">
                                    <i class="fa fa-balance-scale" data-toggle="tooltip" data-placement="top" title="Input Berat"></i>
                                </button>

                            @elseif ($data->keterangan == '3')
                            <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#send-selesai-{{ $data->id }}">
                            <i class="fa fa-check" data-toggle="tooltip" data-placement="top" title="Selesai"></i>


                            @elseif ($data->keterangan == '4')
                        <!-- Tombol Diantar ke Ruangan -->
                            <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#send-diantar-{{ $data->id }}">
                            <i class="fa fa-truck" data-toggle="tooltip" data-placement="top" title="Diantar ke Ruangan"></i>
                            </button>
                            @else
                            -
                            @endif
                          {{-- Tombol Cetak Bon muncul jika keterangan 3 atau 4 --}}
                            @if(in_array($data->keterangan, ['3', '4','5']))
                                <button class="btn btn-success btn-xs" onclick="printBon('print-area-{{ $data->id }}')">
                                    <i class="fa fa-print" data-toggle="tooltip" data-placement="top" title="Cetak Bon"></i>
                                </button>
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
@foreach($laundry as $data)
<div class="modal fade" id="send-modal-{{ $data->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h2 class="text-center">Apakah Anda Telah Menjemput Laundry ?</h2>
                <hr>
                <div class="form-group">
                    <label>Nama Pasien</label>
                    <input type="text" class="form-control" readonly value="{{ $data->pasien->nama }}">
                </div>
                <div class="row mt-1">
                    <div class="col-md-6">
                        <a href="/petugaslaundry/laundry/jemput/{{$data->id}}" class="btn btn-primary btn-block">Ya</a>
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

@foreach($laundry as $data)
<div class="modal fade" id="data-{{$data->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-body">
            <h2 class="text-center">Input Berat Cucian untuk Menentukan Biaya</h2>
            <hr>
            <div class="form-group" style="font-size: 17px;">
               <label>Nama Laundry</label>
               <input type="text" class="form-control" readonly value="{{ $data->pasien->nama }}" style="background-color: white;">
            </div>
            <form method="POST" action="/petugaslaundry/laundry/pickup/{{ $data->id }}">
                @csrf
                <div class="form-group" style="font-size: 17px;">
                    <label>Berat Cucian (kg)</label>
                    <input type="number" name="berat" class="form-control" id="weight-{{$data->id}}" placeholder="Masukkan berat cucian" oninput="calculateCost({{$data->id}})">
                </div>

                <div class="form-group" style="font-size: 17px;">
                    <label>Perkiraan Hari Selesai</label>
                    <select name="siap_pada" class="form-control" required>
                        <option value="" disabled selected>Pilih estimasi hari</option>
                        @for($i = 1; $i <= 7; $i++)
                            <option value="{{ $i }}">{{ $i }} hari</option>
                        @endfor
                    </select>
                </div>

                <div class="form-group" style="font-size: 17px;">
                    <label>Biaya (Rp)</label>
                    <input type="text" class="form-control" name="biaya" id="cost-{{$data->id}}" readonly style="background-color: white;">
                </div>
                <div class="row mt-1">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary btn-block">Simpan</button>
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
<script>
    function calculateCost(id) {
        const weightElement = document.getElementById(`weight-${id}`);
        const costElement = document.getElementById(`cost-${id}`);
        const ratePerKg = 5000; // Adjust this value based on your rate per kg

        // Calculate cost only if weight is a valid number
        const weight = parseFloat(weightElement.value) || 0;
        const cost = weight * ratePerKg;

        // Format the cost and display it in the input
        costElement.value = `${cost.toLocaleString('id-ID')}`;
    }
</script>

@endforeach

@foreach($laundry as $data)
<div class="modal fade" id="send-selesai-{{ $data->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h2 class="text-center">Apakah Anda Telah Menyelesaikan Laundry ?</h2>
                <hr>
                <div class="form-group">
                    <label>Nama Pasien</label>
                    <input type="text" class="form-control" readonly value="{{ $data->pasien->nama }}">
                </div>
                <div class="row mt-1">
                    <div class="col-md-6">
                        <a href="/petugaslaundry/laundry/selesai/{{$data->id}}" class="btn btn-primary btn-block">Ya</a>
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

@foreach($laundry as $data)
<div class="modal fade" id="send-diantar-{{ $data->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h2 class="text-center">Apakah Anda Telah Mengantar Laundry ke Ruangan ?</h2>
                <hr>
                <div class="form-group">
                    <label>Nama Pasien</label>
                    <input type="text" class="form-control" readonly value="{{ $data->pasien->nama }}">
                </div>
                <div class="row mt-1">
                    <div class="col-md-6">
                        <a href="/petugaslaundry/laundry/diantar/{{$data->id}}" class="btn btn-primary btn-block">Ya</a>
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
@foreach($laundry as $data)
<div id="print-area-{{ $data->id }}" style="display: none;">
  <div style="font-family: monospace; font-size: 12px; width: 58mm; text-align: left;">
    <center><strong>BON LAUNDRY</strong></center>
    <hr>
    <table style="width: 100%; font-size: 12px;">
      <tr><td>Nama Pasien</td><td>: {{ $data->pasien->nama }}</td></tr>
      <tr><td>Nomor MR</td><td>: {{ $data->nomr }}</td></tr>
      <tr><td>Ruangan</td><td>: {{ $data->ruangan->nama ?? '-' }}</td></tr>
      <tr><td>Kamar</td><td>: {{ $data->pasien->kamar->nomor_kamar ?? '-' }}</td></tr>
      <tr><td>Tanggal</td><td>: {{ $data->tanggal }}</td></tr>
      <tr><td>Berat</td><td>: {{ $data->berat }} kg</td></tr>
      <tr><td>Biaya</td><td>: Rp {{ number_format($data->biaya, 3, ',', '.') }}</td></tr>
    </table>
    <hr>
    <center>Terima kasih telah menggunakan<br>layanan laundry kami.</center>
  </div>
</div>

@endforeach



<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkbox = document.getElementById('show_all');
        const dateInput = document.getElementById('tanggal');

        checkbox.addEventListener('change', function () {
            if (this.checked) {
                dateInput.setAttribute('disabled', 'disabled');
            } else {
                dateInput.removeAttribute('disabled');
            }
        });
    });
</script>
<script>
    function printBon(divId) {
        const printContents = document.getElementById(divId).innerHTML;
        const printWindow = window.open('', '', 'height=400,width=300');

        printWindow.document.write('<html><head><title>Cetak Bon</title>');
        printWindow.document.write(`
            <style>
                @media print {
                     body {
                font-family: monospace;
                font-size: 12px;
                width: 58mm;
                margin: 0;
                padding: 0;
                text-align: center;
            }
            pre {
                white-space: pre-wrap;
                text-align: center;
                font-family: monospace;
            }
                    .bon-container {
                        padding: 10px;
                        width: 100%;
                    }
                    h4 {
                        text-align: center;
                        margin-bottom: 10px;
                        font-size: 14px;
                        font-weight: bold;
                    }
                    p {
                        margin: 0 0 4px 0;
                        line-height: 1.2;
                    }
                    hr {
                        border: none;
                        border-top: 1px dashed #000;
                        margin: 8px 0;
                    }
                }
            </style>
        `);
        printWindow.document.write('</head><body>');
        printWindow.document.write('<div class="bon-container">' + printContents + '</div>');
        printWindow.document.write('</body></html>');

        printWindow.document.close();
        printWindow.focus();

        printWindow.print();
        printWindow.close();
    }
</script>


@endsection
