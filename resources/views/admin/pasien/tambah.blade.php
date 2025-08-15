@extends('admin.layouts.app', ['activePage' => 'pasien'])

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="widget p-md clearfix">
            <div class="pull-left">
                <h1 class="widget-title" style="font-size: 30px; margin-bottom: 5px;">Tambah Data Pasien</h1>
                <small class="text-color">
                    Data Master <span style="margin:0px 3px;"> &gt; </span>
                    <a href="/admin/pasien">List Data Pasien</a> <span> &gt; </span> Tambah Data
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
                        <i class="glyphicon glyphicon-plus-sign"></i> Tambah Data Pasien
                    </h4>
                </div>
                <div class="pull-right">
                    <a href="{{ url('/admin/pasien') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>
            </header>
            <hr class="widget-separator">

            <div class="widget-body">

                {{-- Tampilkan error validasi --}}
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Tampilkan session error --}}
                @if (session('error'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <span>{{ session('error') }}</span>
                </div>
                @endif

                {{-- Tampilkan session success --}}
                @if (session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                <form action="/admin/pasien/create" method="POST">
                    @csrf

                   <div class="form-group">
                    <label for="no_rm">No RM (Otomatis)<span class="text-danger">* </span></label>
                    <input type="text" class="form-control" id="no_rm" name="no_rm"
                        value="{{ old('no_rm', 'Akan diisi otomatis') }}"
                        readonly disabled>
                </div>

                    <div class="form-group">
                        <label for="nama">Nama Pasien<span class="text-danger">* </span></label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>

                    <div class="form-group">
                        <label for="jenis_kelamin">Jenis Kelamin<span class="text-danger">* </span></label>
                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tanggal_lahir">Tanggal Lahir<span class="text-danger">* </span></label>
                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat<span class="text-danger">* </span></label>
                        <textarea class="form-control" id="alamat" name="alamat" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="no_telepon">No Telepon<span class="text-danger">* </span></label>
                        <input type="number" class="form-control" id="no_telepon" name="no_telepon" required>
                    </div>

                    <div class="form-group">
                        <label for="ruangan_id">Ruangan <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="ruangan_id" name="ruangan_id" required>
                            <option value="">-- Pilih Ruangan --</option>
                            @foreach($ruangan as $r)
                                <option value="{{ $r->id }}">{{ $r->nama }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="kamar_id">Kamar<span class="text-danger">* </span></label>
                        <select class="form-control" id="kamar_id" name="kamar_id" required>
                            <option value="">-- Pilih Kamar --</option>
                            {{-- Data kamar akan di-load dinamis --}}
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="kendala">Kendala<span class="text-danger">* </span></label>
                        <textarea class="form-control" id="kendala" name="kendala" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="status">Status<span class="text-danger">* </span></label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="rawat">Di Rawat</option>
                            <option value="pulang">Pulang</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="/admin/pasien" class="btn btn-default">Batal</a>
                </form>

            </div>
        </div>
    </div>
</div>

{{-- JQuery untuk load kamar dinamis --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#ruangan_id').on('change', function() {
        let ruanganId = $(this).val();

        // Reset kamar dropdown
        $('#kamar_id').empty().append('<option value="">-- Pilih Kamar --</option>');

        if (ruanganId) {
            $.ajax({
                url: `/admin/pasien/get-kamar/${ruanganId}`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.length > 0) {
                        $.each(response, function(index, kamar) {
                            $('#kamar_id').append(
                                `<option value="${kamar.id}">${kamar.nomor_kamar}</option>`
                            );
                        });
                    } else {
                        $('#kamar_id').append('<option value="">Tidak ada kamar tersedia</option>');
                    }
                },
                error: function(xhr) {
                    console.error("Gagal memuat data kamar:", xhr.responseText);
                    alert("Terjadi kesalahan saat mengambil data kamar.");
                }
            });
        }
    });
});
</script>
@endsection
