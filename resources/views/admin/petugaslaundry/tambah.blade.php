@extends('admin.layouts.app', [
'activePage' => 'petugas',
])
@section('content')
<div class="row">
   <div class="col-md-12 col-sm-12">
      <div class="widget p-md clearfix">
         <div class="pull-left">
            <h1 class="widget-title" style="font-size: 30px; margin-bottom: 5px;">Data Petugas Laundry</h1>
            <small class="text-color">Data User<span style="margin:0px 3px 0px 3px"> > </span> <a href="/admin/petugaslaundry">Data Petugas</a> <span style="margin:0px 3px 0px 3px"> > </span> <a href="/admin/petugas/add">Tambah Data Petugas</a></small>
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
                  <i class="glyphicon glyphicon-plus-sign"></i> Tambah Data Petugas Laundry
               </h4>
            </div>
            <div class="pull-right">
               <a href="{{ url('/admin/petugaslaundry') }}" class="btn btn-primary btn-sm">
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
            <form action="/admin/petugaslaundry/create" method="POST" enctype="multipart/form-data">
               {{ csrf_field() }}
               <div class="row">
                <div class="col-md-6">
                   <div class="form-group">
                      <label>NIP Petugas Laundry<span class="text-danger">* </span></label>
                      <input type="number" name="nip" autofocus class="form-control" placeholder="Masukkan NIP Petugas ....." required>
                   </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                       <label>No. HP<span class="text-danger">* </span></label>
                       <input type="number" name="no_hp" autofocus class="form-control" placeholder="Masukkan No. HP Petugas ....." required>
                    </div>
                 </div>
                <div class="col-md-6">
                    <div class="form-group">
                       <label>Nama Petugas Laundry<span class="text-danger">* </span></label>
                       <input type="text" name="nama" autofocus class="form-control" placeholder="Masukkan Nama Petugas ....." required>
                    </div>
                 </div>


                <div class="col-md-6">
                    <div class="form-group">
                       <label>Jabatan Petugas Laundry<span class="text-danger">* </span></label>
                       <select class="form-control select2" name="id_jabatan" required>
                          <option value="">-- Pilih Jabatan --</option>
                          @foreach($jabatan as $data)
                          <option value="{{$data->id}}">{{$data->nama}}</option>
                          @endforeach
                       </select>
                    </div>
                 </div>
                <div class="col-md-6">
                   <div class="form-group">
                      <label>Foto</label>
                      <input type="file" name="foto" autofocus class="form-control" placeholder="Masukkan Foto Pegawai .....">
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
