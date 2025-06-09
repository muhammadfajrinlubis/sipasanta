@extends('admin.layouts.app', [
'activePage' => 'pengaduan',
])
@section('content')
<div class="row">
   <div class="col-md-12 col-sm-12">
      <div class="widget p-md clearfix">
         <div class="pull-left">
            <h1 class="widget-title" style="font-size: 30px; margin-bottom: 5px;">Edit Data Pengaduan</h1>
            <small class="text-color">Pengaduan <span style="margin:0px 3px 0px 3px"> > </span> <a href="/pegawai/pengaduan">List Data Pengaduan</a></small>
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
                  <i class="glyphicon glyphicon-edit"></i> Edit Data Pengaduan
               </h4>
            </div>
            <div class="pull-right">
               <a href="{{ url('/pegawai/pengaduan') }}" class="btn btn-primary btn-sm">
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
            <form action="/pegawai/pengaduan/update/{{ $pengaduan->id }}" method="POST" enctype="multipart/form-data">
               {{ csrf_field() }}
               {{ method_field('PUT') }}
               <div class="row">

                  <!-- Select Nama Ruangan -->
                  <div class="col-md-6">
                    <div class="form-group">
                       <label>Nama Ruangan<span class="text-danger">* </span></label>
                       <select class="form-control select2" name="id_ruangan">
                          <option value="">-- Pilih Ruangan --</option>
                          @foreach($ruangan as $data)
                          <option value="{{$data->id}}" {{ $data->id == $pengaduan->id_ruangan ? 'selected' : '' }}>{{$data->nama}}</option>
                          @endforeach
                       </select>
                    </div>
                  </div>

                  <!-- Select Jenis Sarana -->
                  <div class="col-md-6">
                    <div class="form-group">
                       <label>Jenis Sarana Prasarana<span class="text-danger">* </span></label>
                       <select class="form-control select2" name="id_sarana">
                          <option value="">-- Pilih Sarana --</option>
                          @foreach($sarana as $data)
                          <option value="{{$data->id}}" {{ $data->id == $pengaduan->id_sarana ? 'selected' : '' }}>{{$data->nama}}</option>
                          @endforeach
                       </select>
                    </div>
                  </div>

                  <!-- Input Tanggal Pengaduan -->
                  <div class="col-md-6">
                    <div class="form-group">
                    <label>Tanggal Pengaduan<span class="text-danger">* </span></label>
                    <input type="datetime-local" name="tgl_pengaduan" class="form-control"
                    value="{{ isset($pengaduan->tgl_pengaduan) ? $pengaduan->tgl_pengaduan : \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d\TH:i') }}">

                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                       <label>Foto<span class="text-danger">* </span></label>
                       @if($pengaduan->foto == "")
                       <input type="file" name="foto" autofocus class="form-control" placeholder="Masukkan Foto Pengaduan .....">
                       @else
                       <div class="row">
                          <div class="col-md-9">
                             <input type="file" name="foto" autofocus class="form-control" placeholder="Masukkan Foto Pengaduan .....">
                          </div>
                          <div class="col-md-3">
                             <a href="{{url('images/')}}/{{$pengaduan->foto}}" target="_blank" class="btn btn-primary btn-block"><i class="fa fa-image"></i> Lihat Foto</a>
                          </div>
                       </div>
                       @endif
                    </div>
                 </div>

                  <!-- Select Tipe Pengaduan -->
                  <div class="col-md-6">
                    <div class="form-group">
                       <label>Tipe Pengaduan<span class="text-danger">* </span></label>
                       <select class="form-control select2" name="tipe">
                          <option value="">-- Pilih Tipe --</option>
                          <option value="Urgent" {{ $pengaduan->tipe == 'Urgent' ? 'selected' : '' }}>Urgent</option>
                          <option value="Routine" {{ $pengaduan->tipe == 'Routine' ? 'selected' : '' }}>Routine</option>
                          <option value="Maintenance" {{ $pengaduan->tipe == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                          <option value="Repair" {{ $pengaduan->tipe == 'Repair' ? 'selected' : '' }}>Repair</option>
                       </select>
                    </div>
                  </div>

                  <!-- Input Deskripsi -->
                  <div class="col-md-12">
                     <div class="form-group">
                        <label>Deskripsi Pengaduan<span class="text-danger">* </span></label>
                        <textarea name="deskripsi" class="form-control" rows="4" placeholder="Masukkan deskripsi pengaduan">{{ $pengaduan->deskripsi }}</textarea>
                     </div>
                  </div>

               </div>
               <button type="submit" class="btn btn-primary mt-1 mr-2"><span class="glyphicon glyphicon-floppy-save"></span> Simpan Perubahan</button>
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
