@extends('admin.layouts.app', ['activePage' => 'pasien'])

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="widget p-md clearfix">
            <div class="pull-left">
                <h1 class="widget-title" style="font-size: 30px; margin-bottom: 5px;">Data Pasien</h1>
                <small class="text-color">
                    Data Master <span style="margin:0 3px;"> > </span>
                    <a href="admin/pasien">List Data Pasien</a>
                </small>
            </div>
            <span class="pull-right fz-lg fw-500 counter"></span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="widget">
            <header class="widget-header">
                <div class="pull-left">
                    <h4 class="widget-title" style="font-size:24px;">
                        <i class="glyphicon glyphicon-list"></i> List Data Pasien
                    </h4>
                </div>
                <div class="pull-right">
                    <a href="/admin/pasien/add" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i> Tambah Data
                    </a>
                </div>
            </header>
            <hr class="widget-separator">
            <div class="widget-body">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <div class="table-responsive">
                    <table id="default-datatable" class="table table-striped table-hover table-bordered" cellspacing="0" width="100%">
                        <thead class="bg-primary">
                            <tr>
                                <th width="3%" class="text-center">#</th>
                                <th>NO RM</th>
                                <th>Nama Pasien</th>
                                <th>Jenis Kelamin</th>
                                <th>Tanggal Lahir</th>
                                <th>Alamat</th>
                                <th>No Telepon</th>
                                <th>Ruangan</th>
                                <th>Kamar</th>
                                <th>Status</th>
                                <th width="15%" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pasien as $index => $data)
                                <tr>
                                    <td class="text-center align-middle-custom">{{ $index + 1 }}</td>
                                    <td class="align-middle-custom">{{ $data->no_rm }}</td>
                                    <td class="align-middle-custom">{{ $data->nama }}</td>
                                    <td class="align-middle-custom">
                                        {{ $data->jenis_kelamin == 'L' ? 'Laki-laki' : ($data->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}
                                    </td>
                                    <td class="align-middle-custom">{{ $data->tanggal_lahir }}</td>
                                    <td class="align-middle-custom">{{ $data->alamat }}</td>
                                    <td class="align-middle-custom">{{ $data->no_telepon }}</td>
                                    <td class="align-middle-custom">{{ $data->ruangan->nama ?? '-' }}</td>
                                    <td class="align-middle-custom text-center">{{ $data->kamar->nomor_kamar ?? '-' }}</td>
                                    <td class="align-middle-custom">
                                        {{ $data->status == 'rawat' ? 'Di Rawat' : ($data->status == 'pulang' ? 'Di Pulangkan' : '-') }}
                                    </td>
                                    <td class="text-center align-middle-custom" width="15%">
                                        {{-- Tombol Edit --}}
                                        <a href="{{ url('admin/pasien/edit/' . $data->id) }}">
                                            <button class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Edit Data">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        </a>
                                        {{-- Tombol Hapus dengan Modal --}}
                                        <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#data-{{ $data->id }}" title="Delete Data">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                         {{-- Tombol QR --}}
                                        <button class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#qrModal-{{ $data->id }}" title="QR Cepat">
                                            <i class="fa fa-qrcode"></i>
                                        </button>
                                         {{-- Tombol Print QR --}}
                                        <button class="btn btn-info btn-xs"
                                            onclick="printQRCode('{{ route('pasien.showPublic', $data->id) }}', '{{ addslashes($data->nama) }}')"
                                            title="Print QR">
                                            <i class="fa fa-print"></i>
                                        </button>
                                        {{-- Tombol Ubah Status dengan Modal --}}
                                        @if($data->status == 'rawat')
                                            <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#statusModal-{{ $data->id }}" title="Pulangkan">
                                                <i class="fa fa-sign-out"></i>
                                            </button>
                                        @elseif($data->status == 'pulang')
                                            <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#statusModal-{{ $data->id }}" title="Rawat Kembali">
                                                <i class="fa fa-sign-in"></i>
                                            </button>
                                        @endif
                                        {{-- Modal Konfirmasi Ubah Status --}}
                                        <div class="modal fade" id="statusModal-{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel-{{ $data->id }}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body text-center">
                                                        @if($data->status == 'rawat')
                                                            <h4>Apakah Anda yakin ingin memulangkan pasien ini?</h4>
                                                        @elseif($data->status == 'pulang')
                                                            <h4>Apakah Anda yakin ingin merawatkan kembali pasien ini?</h4>
                                                        @endif
                                                        <hr>
                                                        <div class="form-group" style="font-size: 17px;">
                                                            <label>Nama Pasien</label>
                                                            <input type="text" class="form-control" readonly value="{{ $data->nama }}" style="background-color: white;">
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-6">
                                                                <form action="{{ url('admin/pasien/status/' . $data->id) }}" method="POST">
                                                                    @csrf
                                                                    @if($data->status == 'rawat')
                                                                        <input type="hidden" name="status" value="pulang">
                                                                        <button type="submit" class="btn btn-warning btn-block">Ya, Pulangkan</button>
                                                                    @elseif($data->status == 'pulang')
                                                                        <input type="hidden" name="status" value="rawat">
                                                                        <button type="submit" class="btn btn-info btn-block">Ya, Rawat Kembali</button>
                                                                    @endif
                                                                </form>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <button type="button" class="btn btn-danger btn-block" data-dismiss="modal" aria-label="Close">Tidak</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@foreach($pasien as $data)
<div class="modal fade" id="data-{{$data->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-body">
            <h2 class="text-center">Apakah Anda Yakin Menghapus Data Ini?<h2>
            <hr>
            <div class="form-group" style="font-size: 17px;">
               <label>Nama Pasien</label>
               <input type="text" class="form-control" readonly value="{{$data->nama}}" style="background-color: white;">
            </div>
            <div class="row mt-1">
               <div class="col-md-6">
                  <a href="/admin/pasien/delete/{{$data->id}}" style="text-decoration: none;">
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
@foreach($pasien as $data)
{{-- Modal QR --}}
<div class="modal fade" id="qrModal-{{ $data->id }}" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content text-center p-3">
      <h4 class="mb-2">QR Pasien</h4>
      <div class="qr-code">
        {!! QrCode::size(200)->generate(route('pasien.showPublic', $data->id)) !!}
    </div>
      <a href="/admin/pasien/detail/{{ $data->id }}" target="_blank" class="btn btn-primary btn-block">Lihat Detail</a>
    </div>
  </div>
</div>
@endforeach

<script>
    function printQRCode(url, namaPasien) {
    var printWindow = window.open('', '', 'height=600,width=900');

    printWindow.document.write(
        '<html lang="id">' +
        '<head>' +
        '<title>Print QR</title>' +
        '<meta charset="UTF-8">' +
        '<meta name="viewport" content="width=device-width, initial-scale=1">' +

        // Bootstrap dan FontAwesome
        '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">' +
        '<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">' +

        // QRCode JS
        '<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"><\/script>' +

        // CSS Styling
        '<style>' +
        'body { font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 40px; margin: 0; }' +
        '.card { max-width: 400px; margin: auto; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); background: white; }' +
        '.card-header { background-color: #0d6efd; color: white; text-align: center; padding: 1rem; border-radius: 10px 10px 0 0; }' +
        '.card-body { padding: 2rem; text-align: center; }' +
        '.qr-code { margin: 20px auto; width: 200px; height: 200px; }' +
        '.nama-pasien { font-weight: 600; font-size: 1.25rem; margin-bottom: 1rem; }' +
        '@media print { body { padding: 0; background: white; } .card { box-shadow: none; border: none; } }' +
        '</style>' +
        '</head>' +
        '<body>' +
        '<div class="card">' +
        '<div class="card-header"><h4>QR Code Pasien</h4></div>' +
        '<div class="card-body">' +
        `<div class="nama-pasien">Nama Pasien: ${namaPasien}</div>` +    // Nama pasien sekarang muncul di sini
        '<div id="qrcode" class="qr-code"></div>' +
        '</div>' +
        '</div>' +

        '<script>' +
        `new QRCode(document.getElementById("qrcode"), "${url}");` +
        'setTimeout(function() { window.print(); }, 1000);' +
        '<\/script>' +

        '</body></html>'
    );

    printWindow.document.close();
}


</script>




@endsection
