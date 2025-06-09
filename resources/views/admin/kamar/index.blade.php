@extends('admin.layouts.app', [
'activePage' => 'kamar',
])
@section('content')
<div class="row">
   <div class="col-md-12 col-sm-12">
      <div class="widget p-md clearfix">
         <div class="pull-left">
            <h1 class="widget-title" style="font-size: 30px; margin-bottom: 5px;">Data Kamar</h1>
            <small class="text-color">Data Master <span style="margin:0px 3px 0px 3px"> > </span> <a href="/admin/kamar">List Data Kamar</a></small>
         </div>
         <span class="pull-right fz-lg fw-500 counter"></span>
      </div>
      <!-- .widget -->
   </div>
</div>

<div class="row">
   <div class="col-md-12">
      <div class="widget">
         <header class="widget-header">
            <div class="pull-left">
               <h4 class="widget-title" style="font-size:24px;">
                  <i class="glyphicon glyphicon-list"></i> List Data Kamar
               </h4>
            </div>
            <div class="pull-right">
               <a href="/admin/kamar/add" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Tambah Data
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
            <div class="table-responsive">
               <table id="default-datatable" class="table table-striped table-hover table-bordered" cellspacing="0" width="100%">
                  <thead class="bg-primary">
                     <tr>
                        <th width="3%" class="text-center">#</th>
                        <th>Nomor Kamar</th>
                        <th>Ruangan</th> <!-- Tambahan -->
                        <th width="15%" class="text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $no = 1; ?>
                    @foreach($kamar as $data)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $data->nomor_kamar }}</td>
                        <td>{{ $data->ruangan->nama ?? '-' }}</td> <!-- Menampilkan nama ruangan -->
                        <td class="text-center">
                            <a href="/admin/kamar/edit/{{$data->id}}">
                                <button class="btn btn-success btn-xs">
                                <i class="fa fa-edit" data-toggle="tooltip" data-placement="top" title="Edit Data"></i>
                                </button>
                            </a>
                            <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#data-{{ $data->id }}">
                                <i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="Delete Data"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>

               </table>
            </div>
         </div>
         <!-- .widget-body -->
      </div>
      <!-- .widget -->
   </div>
   <!-- END column -->
</div>
@foreach($kamar as $data)
<div class="modal fade" id="data-{{$data->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-body">
            <h2 class="text-center">Apakah Anda Yakin Menghapus Data Ini?<h2>
            <hr>
            <div class="form-group" style="font-size: 17px;">
               <label>Nomor Kamar</label>
               <input type="text" class="form-control" readonly value="{{$data->nomor_kamar}}" style="background-color: white;">
            </div>
            <div class="row mt-1">
               <div class="col-md-6">
                  <a href="/admin/kamar/delete/{{$data->id}}" style="text-decoration: none;">
                  <button type="button" class="btn btn-primary btn-block">Ya</button>
                  </a>
               </div>
               <div class="col-md-6">
                  <button type="button" class="btn btn-danger btn-block" data-dismiss="modal" aria-label="Close">Tidak</button>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endforeach


@endsection
