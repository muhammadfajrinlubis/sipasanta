@extends('admin.layouts.app', ['activePage' => 'pasien'])

@section('content')

<div class="row">
   <div class="col-md-12 col-sm-12">
      <div class="widget p-md clearfix">
         <div class="pull-left">
            <h1 class="widget-title" style="font-size: 30px; margin-bottom: 5px;">Data Pasien</h1>
            <small class="text-color">Data Master <span style="margin:0px 3px 0px 3px"> > </span> <a href="/admin/Pasien">Data Pasien</a> <span style="margin:0px 3px 0px 3px"> > </span> <a href="/admin/pasien/detail/{{$pasien->id}}">Detail Data Pasien</a></small>
         </div>
         <span class="pull-right fz-lg fw-500 counter"></span>
      </div>
      <!-- .widget -->
   </div>
</div>
<!-- .row -->
<div class="row">
   <div class="col-md-12 col-sm-12">
      <div class="widget">
         <header class="widget-header">
            <div class="pull-left">
               <h4 class="widget-title" style="font-size:24px;">
                  <i class="glyphicon glyphicon-list-alt"></i> Detail Data Pasien
               </h4>
            </div>
            <div class="pull-right">
               <a href="{{ url('/admin/pasien') }}" class="btn btn-primary btn-sm">
               <i class="fa fa-arrow-left"></i> Back
               </a>
            </div>
         </header>

        <div class="card-body">
            <div class="row mb-6">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th>Nama</th>
                            <td>{{ $pasien->nama }}</td>
                        </tr>
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td>{{ $pasien->jenis_kelamin }}</td>
                        </tr>
                        <tr>
                            <th>Kendala</th>
                            <td>{{ $pasien->kendala }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $pasien->alamat }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if ($pasien->status == 'rawat')
                                    <span class="badge badge-warning">Dirawat</span>
                                @elseif ($pasien->status == 'pulang')
                                    <span class="badge badge-success">Dipulangkan</span>
                                @else
                                    <span class="badge badge-secondary">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Daftar</th>
                            <td>{{ $pasien->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Ruangan</th>
                            <td>{{ $pasien->ruangan->nama }}</td>
                        </tr>
                        <tr>
                            <th>Kamar</th>
                            <td>{{$pasien->kamar->nomor_kamar}}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6 text-center">
                    <h5>QR Code Pasien</h5>
                    <div class="mt-3">
                        {!! QrCode::size(200)->generate(route('pasien.showPublic', $pasien->id)) !!}
                    </div>
                    <p class="mt-2 text-muted">Scan untuk melihat detail pasien ini</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
