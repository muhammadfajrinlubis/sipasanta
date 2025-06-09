@extends('admin.layouts.app', [
    'activePage' => 'kamar',
])

@section('content')
<div class="row">
   <div class="col-md-12 col-sm-12">
      <div class="widget p-md clearfix">
         <div class="pull-left">
            <h1 class="widget-title" style="font-size: 30px; margin-bottom: 5px;">Data Kamar</h1>
            <small class="text-color">
                Data Master
                <span style="margin:0px 3px;"> > </span>
                <a href="{{ url('/admin/kamar') }}">Data Kamar</a>
                <span style="margin:0px 3px;"> > </span>
                <a href="{{ url('/admin/kamar/add') }}">Tambah Data Kamar</a>
            </small>
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
                  <i class="glyphicon glyphicon-plus-sign"></i> Tambah Data Kamar
               </h4>
            </div>
            <div class="pull-right">
               <a href="{{ url('/admin/kamar') }}" class="btn btn-primary btn-sm">
                   <i class="fa fa-arrow-left"></i> Back
               </a>
            </div>
         </header>
         <hr class="widget-separator">
         <div class="widget-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                </div>
            @endif

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

            <form action="{{ url('/admin/kamar/create') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Pilih Ruangan -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="ruangan_id">Pilih Ruangan <span class="text-danger">*</span></label>
                            <select name="ruangan_id" id="ruangan_id" class="form-control" required>
                                <option value="">-- Pilih Ruangan --</option>
                                @foreach ($ruangans as $ruangan)
                                    <option value="{{ $ruangan->id }}" {{ old('ruangan_id') == $ruangan->id ? 'selected' : '' }}>
                                        {{ $ruangan->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Nomor Kamar -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="nomor_kamar">Nomor Kamar <span class="text-danger">*</span></label>
                            <input type="text" name="nomor_kamar" id="nomor_kamar" class="form-control" placeholder="Masukkan Nomor Kamar ....." value="{{ old('nomor_kamar') }}" required autofocus>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-1 mr-2">
                    <span class="glyphicon glyphicon-floppy-save"></span> Tambah Data
                </button>
            </form>
         </div>
      </div>
   </div>
</div>
@endsection
