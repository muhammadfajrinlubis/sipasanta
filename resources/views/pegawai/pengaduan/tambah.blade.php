@extends('admin.layouts.app', [
'activePage' => 'pengaduan',
])
@section('content')
<div class="row">
   <div class="col-md-12 col-sm-12">
      <div class="widget p-md clearfix">
         <div class="pull-left">
            <h1 class="widget-title" style="font-size: 30px; margin-bottom: 5px;">Data Sarana Pengaduan</h1>
            <small class="text-color">Pengaduan <span style="margin:0px 3px 0px 3px"> <span style="margin:0px 3px 0px 3px"> > </span> <a href="/pegawai/pengaduan/add">Tambah Data Pengaduan</a></small>
         </div>
         <span class="pull-right fz-lg fw-500 counter"></span>
      </div>
      <!-- .widget -->
   </div>
</div>
<!-- .row -->
<div class="row">
   <div class="col-md-12">
      <div class="widget">
         <header class="widget-header">
            <div class="pull-left">
               <h4 class="widget-title" style="font-size:24px;">
                  <i class="glyphicon glyphicon-plus-sign"></i> Tambah Data Pengaduan
               </h4>
            </div>
            <div class="pull-right">
               <a href="{{ url('/pegawai/pengaduan') }}" class="btn btn-primary btn-sm">
               <i class="fa fa-arrow-left"></i> Back
               </a>
            </div>
         </header>
         <!-- .widget-header -->
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
            <form action="/pegawai/pengaduan/create" method="POST" enctype="multipart/form-data">
               {{ csrf_field() }}
               
               <div class="row">

                  <!-- Select Nama Ruangan -->
                  <div class="col-md-6">
                    <div class="form-group">
                       <label>Nama Ruangan<span class="text-danger">* </span></label>
                       <select class="form-control select2" name="id_ruangan" required>
                          <option value="">-- Pilih Ruangan --</option>
                          @foreach($ruangan as $data)
                          <option value="{{$data->id}}">{{$data->nama}}</option>
                          @endforeach
                       </select>
                    </div>
                  </div>

                  <!-- Select Jenis Sarana -->
                  <div class="col-md-6">
                    <div class="form-group">
                       <label>Jenis Sarana Prasarana<span class="text-danger">* </span></label>
                       <select class="form-control select2" name="id_sarana" required>
                          <option value="">-- Pilih Sarana --</option>
                          @foreach($sarana as $data)
                          <option value="{{$data->id}}">{{$data->nama}}</option>
                          @endforeach
                       </select>
                    </div>
                  </div>

                  <!-- Input Tanggal Pengaduan -->
                <div class="col-md-6">
                    <div class="form-group">
                    <label>Tanggal Pengaduan<span class="text-danger">* </span></label>
                    <input type="datetime-local" name="tgl_pengaduan" class="form-control"
                    value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d\TH:i') }}" required>
                    </div>
                </div>

                  <!-- Input Foto -->
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Foto<span class="text-danger">* </span></label>
                        <input type="file" name="foto" class="form-control" required>
                     </div>
                  </div>

                  <!-- Select Tipe Pengaduan -->
                  <div class="col-md-6">
                    <div class="form-group">
                       <label>Tipe Pengaduan<span class="text-danger">* </span></label>
                       <select class="form-control select2" name="tipe">
                          <option value="">-- Pilih Tipe --</option>
                          <option value="Urgent">Urgent</option>
                          <option value="Routine">Routine</option>
                          <option value="Maintenance">Maintenance</option>
                          <option value="Repair">Repair</option>
                       </select>
                    </div>
                  </div>

                  <!-- Input Deskripsi -->
                  <div class="col-md-12">
                     <div class="form-group">
                        <label>Deskripsi Pengaduan<span class="text-danger">* </span></label>
                        <textarea name="deskripsi" class="form-control" rows="4" placeholder="Masukkan deskripsi pengaduan" required></textarea>
                     </div>
                  </div>

               </div>
               <button type="submit" class="btn btn-primary mt-1 mr-2"><span class="glyphicon glyphicon-floppy-save"></span> Tambah Data</button>
            </form>
         </div>
         <!-- .widget-body -->
      </div>
      <!-- .widget -->
   </div>
   <!-- END column -->
</div>
<!-- .row -->
@endsection
