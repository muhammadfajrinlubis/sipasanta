@extends('admin.layouts.app', [
'activePage' => 'petugaslaundry',
])
@section('content')
<div class="row">
   <div class="col-md-12 col-sm-12">
      <div class="widget p-md clearfix">
         <div class="pull-left">
            <h1 class="widget-title" style="font-size: 30px; margin-bottom: 5px;">Data Petugas Laundry</h1>
            <small class="text-color">Data User <span style="margin:0px 3px 0px 3px"> > </span> <a href="/admin/petugaslaundry">List Data Petugas Laundry</a></small>
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
                  <i class="glyphicon glyphicon-list"></i> List Data Petugas Laundry
               </h4>
            </div>
            <div class="pull-right">
               <a href="/admin/petugaslaundry/add" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Tambah Data
               </a>
               {{-- <button class="btn btn-dark btn-sm" data-toggle="modal" data-target="#import">
                <i class="fa fa-upload"></i> Import Data
               </button> --}}
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
                        <th class="text-center" width="5%">Foto</th>
                        <th>NIP</th>
                        <th>Nama Petugas Laundry</th>
                        <th>No HP</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                        <th width="15%" class="text-center">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php $no = 1; ?>
                     @foreach($petugaslaundry as $data)
                    <?php
                     $jabatan= DB::table('jabatan')->find($data->id_jabatan);
                    ?>
                     <tr>
                         <td class="text-center align-middle-custom">{{ $no++ }}</td>
                         <td class="text-center align-middle-custom">
                             @if($data->foto == "")
                             <a href="{{url('assets-admin')}}/assets/images/user.png" target="_blank">
                                 <img src="{{url('assets-admin')}}/assets/images/user.png" class="rounded-circle" width="30px">
                             </a>
                             @else
                             <a href="{{url('public/profil')}}/{{$data->foto}}" target="_blank">
                                 <img src="{{url('public/profil')}}/{{$data->foto}}" class="rounded-circle" width="30px">
                             </a>
                             @endif
                         </td>
                         <td class="align-middle-custom">{{ $data->nip }}</td>
                         <td class="align-middle-custom">{{ $data->nama }}</td>
                         <td class="align-middle-custom">{{ $jabatan->nama }}</td>
                         <td class="align-middle-custom">{{ $data->no_hp }}</td>
                         <td class="align-middle-custom">{{ $data->status_aktif }}</td>
                         <td class="text-center align-middle-custom" width="15%">
                             <a href="/admin/petugaslaundry/edit/{{$data->id}}">
                                 <button class="btn btn-success btn-xs">
                                     <i class="fa fa-edit" data-toggle="tooltip" data-placement="top" title="Edit Data"></i>
                                 </button>
                             </a>
                             <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#data-{{ $data->id }}">
                                 <i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="Delete Data"></i>
                             </button>
                              <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#resetModal-{{ $data->id }}">
                                <i class="fa fa-key" data-toggle="tooltip" data-placement="top" title="Reset Password"></i>
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
<!-- .row -->
<div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-body">
            <h2 class="text-center">Import Data<h2>
            <hr>
            <form method="post" action="/admin/petugas/import" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <div class="form-group">
                     <label>Pilih File Excel</label>
                     <input type="file" name="file" required="required" class="form-control">
                  </div>
                   <div class="row mt-1">
                     <div class="col-md-6">
                       <a href="{{url('template/Template petugas.xlsx')}}">
                        <button type="button" class="btn btn-dark btn-block"> <span class="glyphicon glyphicon-download"></span> Download Template</button>
                       </a>
                     </div>
                     <div class="col-md-6">
                        <button type="submit" class="btn btn-primary btn-block"> <span class="glyphicon glyphicon-floppy-save"></span> Import Data</button>
                     </div>

                   </div>
            </form>
         </div>
      </div>
   </div>
</div>
@foreach($petugaslaundry as $data)
<div class="modal fade" id="data-{{$data->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-body">
            <h2 class="text-center">Apakah Anda Yakin Menghapus Data Ini?<h2>
            <hr>
            <div class="form-group" style="font-size: 17px;">
               <label>Nama petugas</label>
               <input type="text" class="form-control" readonly value="{{$data->nama}}" style="background-color: white;">
            </div>
            <div class="row mt-1">
               <div class="col-md-6">
                  <a href="/admin/petugaslaundry/delete/{{$data->id}}" style="text-decoration: none;">
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
<div class="modal fade" id="resetModal-{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="resetModalLabel-{{ $data->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="/admin/petugaslaundry/reset-password/{{ $data->id }}">
            @csrf
            <div class="modal-content">
                <div class="modal-body">
                    <h2 class="text-center">Reset Password Petugas Laundry</h2>
                    <hr>

                    <div class="form-group" style="font-size: 17px;">
                        <label>NIP</label>
                        <input type="text" class="form-control" value="{{ $data->nip }}" readonly style="background-color: white;">
                        <label>Nama Petugas Laundry</label>
                        <input type="text" class="form-control" value="{{ $data->nama }}" readonly style="background-color: white;">
                    </div>

                    <p class="text-center mt-3">
                        Password akan di-reset menjadi: <strong>12345678</strong>
                    </p>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-warning btn-block">Reset</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach
@endsection
