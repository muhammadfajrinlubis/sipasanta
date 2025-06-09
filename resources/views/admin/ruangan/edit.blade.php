@extends('admin.layouts.app', [
'activePage' => 'ruangan',
])
@section('content')
<div class="row">
   <div class="col-md-12 col-sm-12">
      <div class="widget p-md clearfix">
         <div class="pull-left">
            <h1 class="widget-title" style="font-size: 30px; margin-bottom: 5px;">Data Ruangan</h1>
            <small class="text-color">Data Master <span style="margin:0px 3px 0px 3px"> > </span> <a href="/admin/ruangan">Data ruangan</a> <span style="margin:0px 3px 0px 3px"> > </span> <a href="/admin/ruangan/edit/{{$ruangan->id}}">Edit Data ruangan</a></small>
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
                  <i class="glyphicon glyphicon-edit"></i> Edit Data Ruangan
               </h4>
            </div>
            <div class="pull-right">
               <a href="{{ url('/admin/ruangan') }}" class="btn btn-primary btn-sm">
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
            <form action="/admin/ruangan/update/{{$ruangan->id}}" method="POST" enctype="multipart/form-data">
               {{ csrf_field() }}
               <div class="row">
                <div class="col-md-12">
                     <div class="form-group">
                        <label>Kode Ruangan<span class="text-danger">* </span></label>
                        <input type="text" name="kode_ruangan" autofocus class="form-control" placeholder="Masukkan Nama ruangan ....." value="{{$ruangan->kode_ruangan}}">
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group">
                        <label>Nama Ruangan<span class="text-danger">* </span></label>
                        <input type="text" name="nama" autofocus class="form-control" placeholder="Masukkan Nama ruangan ....." value="{{$ruangan->nama}}">
                     </div>
                  </div>
               </div>
               <button type="submit" class="btn btn-primary mt-1 mr-2"><span class="glyphicon glyphicon-floppy-save"></span> Edit Data</button>
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
