@extends('admin.layouts.app', ['activePage' => 'users'])

@section('content')

<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="widget p-md clearfix">
            <div class="pull-left">
                <h1 class="widget-title" style="font-size: 30px; margin-bottom: 5px;">Data Profil</h1>
                <small class="text-color">Profil <span style="margin:0px 3px 0px 3px"> > </span> <a href="/admin/profil">List Data Profil</a></small>
            </div>
            <span class="pull-right fz-lg fw-500 counter"></span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="widget">
            <header class="widget-header">
                <h4 class="widget-title" style="font-size:24px;">
                    <i class="glyphicon glyphicon-list"></i> List Data Profil
                </h4>
            </header>
            <!-- .widget-header -->
            <hr class="widget-separator">
            <div class="widget-body">
                @if (session('error'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span>{{ session('error') }}</span>
                </div>
                @endif
                @if (session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span>{{ session('success') }}</span>
                </div>
                @endif
    <div class="card">
        <div class="widget p-md clearfix">
            <div class="card-header">
                Detail Profil
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <!-- Menampilkan Foto -->
                        @if($users->foto)
                        <a href="{{ asset('public/profil/' . $users->foto) }}" target="_blank">
                            <img src="{{ asset('public/profil/' . $users->foto) }}" class="rounded-circle" width="150px">
                        </a>
                        @else
                        <a href="{{ asset('assets-admin/assets/images/user.png') }}" target="_blank">
                            <img src="{{ asset('assets-admin/assets/images/user.png') }}" class="rounded-circle" width="150px">
                        </a>
                        @endif
                    </div>

                    <div class="col-md-8">
                        <p><strong>Nama:</strong> {{ $users->name }}</p>
                        <p><strong>NIP:</strong> {{ $users->username }}</p>
                        <p><strong>Jabatan:</strong>
                            @if($users->jabatan)
                                {{ $users->jabatan }}
                            @else
                                <span class="text-muted">Tidak ada jabatan</span>
                            @endif
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
                <a href="/profil/edit/{{ $users->id }}" class="btn btn-warning btn-sm">Edit Profil</a>
                <a href="{{ route('change') }}" class="btn btn-danger btn-sm">Ganti Password</a>
            </div>
        </div>
    </div>
</div>

@endsection
