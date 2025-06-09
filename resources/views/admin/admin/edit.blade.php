@extends('admin.layouts.app', [
'activePage' => 'admin',
])
@section('content')
<div class="row">
   <div class="col-md-12 col-sm-12">
      <div class="widget p-md clearfix">
         <div class="pull-left">
            <h1 class="widget-title" style="font-size: 30px; margin-bottom: 5px;">Data Admin</h1>
            <small class="text-color">Data User <span style="margin:0px 3px 0px 3px"> > </span> <a href="/admin/admin">Data Admin</a> <span style="margin:0px 3px 0px 3px"> > </span> <a href="/admin/admin/edit/{{$admin->id}}">Edit Data Admin</a></small>
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
                  <i class="glyphicon glyphicon-edit"></i> Edit Data Admin
               </h4>
            </div>
            <div class="pull-right">
               <a href="{{ url('/admin/admin') }}" class="btn btn-primary btn-sm">
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
            <form action="/admin/admin/update/{{$admin->id}}" method="POST" enctype="multipart/form-data">
               {{ csrf_field() }}
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>NIP Admin</label>
                        <input type="number" name="nip" autofocus class="form-control" placeholder="Masukkan NIP admin ....." value="{{$admin->nip}}">
                     </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                       <label>No. HP Admin</label>
                       <input type="number" name="no_hp" autofocus class="form-control" placeholder="Masukkan No. HP admin ....." value="{{$admin->no_hp}}">
                    </div>
                 </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Nama Admin</label>
                        <input type="text" name="nama" autofocus class="form-control" placeholder="Masukkan Nama admin ....." value="{{$admin->nama}}">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Jabatan Admin</label>
                        <select class="form-control select2" name="id_jabatan">
                           <option value="{{$jabatanSelect->id}}">{{$jabatanSelect->nama}}</option>
                           @foreach($jabatan as $data)
                           <option value="{{$data->id}}">{{$data->nama}}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Foto</label>
                        @if($admin->foto == "")
                        <input type="file" name="foto" autofocus class="form-control" placeholder="Masukkan Foto admin .....">
                        @else
                        <div class="row">
                           <div class="col-md-9">
                              <input type="file" name="foto" autofocus class="form-control" placeholder="Masukkan Foto admin .....">
                           </div>
                           <div class="col-md-3">
                              <a href="{{url('public/profil')}}/{{$admin->foto}}" target="_blank" class="btn btn-primary btn-block"><i class="fa fa-image"></i> Lihat Foto</a>
                           </div>
                        </div>
                        @endif
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
