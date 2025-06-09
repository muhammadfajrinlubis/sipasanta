@extends('admin.layouts.app', [
    'activePage' => '',
])

@section('content')
<div class="col-md-12 col-sm-12">
    <div class="widget p-md clearfix">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8 text-center">
                <h1 style="font-size: 100px; font-weight: bold; color: #dc3545;">404</h1>
                <h2 style="font-size: 30px; font-weight: bold;">Halaman Tidak Ditemukan</h2>
                <p class="text-muted">
                    Maaf, halaman yang Anda cari tidak tersedia atau sudah dipindahkan.
                </p>
                <a href="{{ url('/admin/home') }}" class="btn btn-primary mt-3">
                    <i class="fa fa-home"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
