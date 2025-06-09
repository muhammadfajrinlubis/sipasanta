@extends('admin.layouts.app', [
'activePage' => 'jabatan',
])
@section('content')
<div class="row">
   <div class="col-md-12 col-sm-12">
      <div class="widget p-md clearfix">
         <div class="pull-left">
            <h1 class="widget-title" style="font-size: 30px; margin-bottom: 5px;">Data Jabatan</h1>
            <small class="text-color">Data Master <span style="margin:0px 3px 0px 3px"> > </span> <a href="/admin/jabatan">Data Jabatan</a> <span style="margin:0px 3px 0px 3px"> > </span> <a href="/admin/jabatan/detail/{{$jabatan->id}}">Detail Data Jabatan</a></small>
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
                  <i class="glyphicon glyphicon-list-alt"></i> Detail Data Jabatan
               </h4>
            </div>
            <div class="pull-right">
               <a href="{{ url('/admin/jabatan') }}" class="btn btn-primary btn-sm">
               <i class="fa fa-arrow-left"></i> Back
               </a>
            </div>
         </header>
         <!-- .widget-header -->
         <hr class="widget-separator">
         <div class="widget-body">
            <div class="row">
               <div class="col-md-12">
                  <div class="form-group">
                     <label>Nama Jabatan</label>
                     <input type="text" name="nama" autofocus class="form-control" readonly placeholder="Masukkan Nama Jabatan ....." value="{{$jabatan->nama}}" style="background-color: white;">     
                  </div>
               </div>
            </div>
         </div>
         <!-- .widget-body -->
      </div>
      <!-- .widget -->
   </div>
   <!-- END column -->
</div>
<!-- .row -->
@endsection