@extends('admin.layouts.app', ['activePage' => 'users'])

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="widget p-md clearfix">
            <div class="pull-left">
                <h1 class="widget-title" style="font-size: 30px; margin-bottom: 5px;">Edit Foto Profil</h1>
                <small class="text-color">Profil <span style="margin:0px 3px 0px 3px"> > </span> <a href="/admin/profil">List Data Profil</a></small>
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
                   <i class="glyphicon glyphicon-edit"></i> Edit Data Petugas
                </h4>
             </div>
             <div class="pull-right">
                <a href="{{ url('/admin/petugas') }}" class="btn btn-primary btn-sm">
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
                <form action="/profil/update/{{ $users->id }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-4">
                            <!-- Gambar Profil -->
                            <label for="foto">Foto Profil</label><br>
                            @if($users->foto)
                            <a href="{{ asset('public/profil/' . $users->foto) }}" target="_blank">
                                <img src="{{ asset('public/profil/' . $users->foto) }}" class="rounded-circle mb-3" width="150px">
                            </a>
                            @else
                            <a href="{{ asset('assets-admin/assets/images/user.png') }}" target="_blank">
                                <img src="{{ asset('assets-admin/assets/images/user.png') }}" class="rounded-circle mb-3" width="150px">
                            </a>
                            @endif
                            <input type="file" class="form-control" name="foto">
                        </div>

                        <div class="col-md-8">
                            <!-- Menampilkan data lainnya, tetapi tidak dapat diedit -->
                            <p><strong>Nama:</strong> {{ $users->name }}</p>
                            <p><strong>NIP:</strong> {{ $users->username }}</p>
                            <p><strong>Jabatan:</strong>
                                {{ $users->jabatan ?? 'Tidak ada jabatan' }}
                            </p>
                            <p><strong>Level:</strong>
                                @if($users->level == 1)
                                    Administrator
                                @elseif($users->level == 2)
                                    Pegawai
                                @elseif($users->level == 3)
                                    Petugas
                                @else
                                    Pengguna
                                @endif
                            </p>
                            <p><strong>Tanggal Dibuat:</strong> {{ $users->created_at }}</p>
                            <p><strong>Tanggal Diperbarui:</strong> {{ $users->updated_at }}</p>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success">Simpan Perubahan Foto</button>
                    <a href="/profil/{{ Auth::user()->id }}" class="btn btn-primary btn-sm">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
