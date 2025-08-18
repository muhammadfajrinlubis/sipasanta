@extends('admin.layouts.app', ['activePage' => 'pasien'])

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="widget p-md clearfix">
            <div class="pull-left">
                <h1 class="widget-title" style="font-size: 30px; margin-bottom: 5px;">Edit Data Pasien</h1>
                <small class="text-color">
                    Data Master <span style="margin:0px 3px;"> &gt; </span>
                    <a href="/admin/pasien">List Data Pasien</a> <span> &gt; </span> Edit Data
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
                        <i class="glyphicon glyphicon-edit"></i> Edit Data Pasien
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

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="/admin/pasien/update/{{$pasien->id}}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}

                    <div class="form-group">
                        <label for="no_rm">No RM</label>
                        <input type="text" class="form-control" name="no_rm" value="{{ $pasien->no_rm }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="nama">Nama Pasien</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ $pasien->nama }}" required>
                    </div>

                    <div class="form-group">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L" {{ $pasien->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ $pasien->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{ $pasien->tanggal_lahir }}" required>
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" required>{{ $pasien->alamat }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="no_telepon">No Telepon</label>
                        <input type="number" class="form-control" id="no_telepon" name="no_telepon" value="{{ $pasien->no_telepon }}" required>
                    </div>

                    <div class="form-group">
                    <label for="ruangan_id">Ruangan</label>
                    <select class="form-control" id="ruangan_id" name="ruangan_id" required>
                        <option value="">-- Pilih Ruangan --</option>
                        @foreach($ruangan as $r)
                            <option value="{{ $r->id }}"
                                {{ optional($pasien->kamar)->ruangan_id == $r->id ? 'selected' : '' }}>
                                {{ $r->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                    <div class="form-group">
                        <label for="kamar_id">Kamar</label>
                        <select class="form-control" id="kamar_id" name="kamar_id" required>
                            <option value="">-- Pilih Kamar --</option>
                            {{-- Akan diisi lewat JS --}}
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="kendala">Kendala</label>
                        <textarea class="form-control" id="kendala" name="kendala">{{ $pasien->kendala }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="rawat" {{ $pasien->status == 'rawat' ? 'selected' : '' }}>Di Rawat</option>
                            <option value="pulang" {{ $pasien->status == 'pulang' ? 'selected' : '' }}>Pulang</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary mt-1 mr-2"><span class="glyphicon glyphicon-floppy-save"></span> Edit Data</button>
                    <a href="/admin/pasien" class="btn btn-default">Batal</a>
                </form>

            </div>
        </div>
    </div>
</div>

{{-- Script load kamar --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    function loadKamar(ruanganId, selectedKamarId = null, pasienId = null) {
        $('#kamar_id').empty().append('<option value="">-- Pilih Kamar --</option>');

        if (ruanganId) {
            let url = `/admin/pasien/get-kamar/${ruanganId}`;
            if (pasienId) {
                url += `/${pasienId}`;
            }

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.length > 0) {
                        $.each(response, function(index, kamar) {
                            const selected = kamar.id == selectedKamarId ? 'selected' : '';
                            $('#kamar_id').append(
                                `<option value="${kamar.id}" ${selected}>${kamar.nomor_kamar}</option>`
                            );
                        });
                    } else {
                        $('#kamar_id').append('<option value="">Tidak ada kamar tersedia</option>');
                    }
                }
            });
        }
    }

    // Load kamar awal saat halaman dibuka (jika edit)
    loadKamar($('#ruangan_id').val(), "{{ $pasien->kamar_id }}", "{{ $pasien->id }}");

    // Reload saat ruangan diganti (tanpa pasienId karena bisa kosong saat create)
    $('#ruangan_id').on('change', function() {
        loadKamar($(this).val(), null, "{{ $pasien->id ?? '' }}");
    });
});
</script>
@endsection
