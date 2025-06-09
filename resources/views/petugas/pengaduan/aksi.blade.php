@extends('admin.layouts.app', ['activePage' => 'pengaduan'])

@section('content')
<div class="row">
   <div class="col-md-12 col-sm-12">
      <div class="widget p-md clearfix">
         <div class="pull-left">
            <h1 class="widget-title" style="font-size: 30px; margin-bottom: 5px;">Data Sarana Pengaduan</h1>
            <small class="text-color">Pengaduan <span style="margin:0px 3px 0px 3px"> > </span> <a href="/petugas/pengaduan/Aksi">Aksi Petugas</a></small>
         </div>
         <span class="pull-right fz-lg fw-500 counter"></span>
      </div>
   </div>
</div>

<!-- Row for form -->
<div class="row">
   <div class="col-md-12">
      <div class="widget">
         <header class="widget-header">
            <div class="pull-left">
               <h4 class="widget-title" style="font-size:24px;">
                  <i class="glyphicon glyphicon-plus-sign"></i> Aksi Petugas
               </h4>
            </div>
            <div class="pull-right">
               <a href="{{ url('/petugas/pengaduan') }}" class="btn btn-primary btn-sm">
               <i class="fa fa-arrow-left"></i> Back
               </a>
            </div>
         </header>
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

            <!-- Form for handling the report -->
            <form action="/petugas/pengaduan/aksipetugas/{{ $pengaduan->id }}" method="POST" enctype="multipart/form-data">
               {{ csrf_field() }}


                <!-- Action status options -->
                <div class="row">
                    <div class="col-md-12">
                       <div class="form-group">
                          <label>Status Penyelesaian</label>
                          <select name="status" class="form-control" required>
                             <option value="selesai">Selesai</option>
                             <option value="tidak_dapat_dikerjakan">Tidak Dapat Dikerjakan</option>
                          </select>
                       </div>
                    </div>
                 </div>

               <!-- Date of completion -->
               <div class="row" id="tglField">
                  <div class="col-md-12">
                     <div class="form-group">
                        <label>Tanggal Selesai</label>
                        <input type="datetime-local" name="tgl_pukul_selesai" class="form-control"
                            value="{{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d\TH:i') }}">
                    </div>
                  </div>
               </div>

               <!-- Upload proof of work -->
               <div class="row" id="fotoField">
                  <div class="col-md-12">
                     <div class="form-group">
                        <label>Upload Bukti Pekerjaan</label>
                        <input type="file" name="bukti_petugas" class="form-control">
                     </div>
                  </div>
               </div>



               <!-- Reason if not completed -->
               <div class="row" id="reasonField" style="display: none;">
                  <div class="col-md-12">
                     <div class="form-group">
                        <label>Alasan Tidak Dapat Dikerjakan</label>
                        <textarea name="alasan" class="form-control" placeholder="Jelaskan alasan mengapa tidak dapat dikerjakan..."></textarea>
                     </div>
                  </div>
               </div>

               <!-- Submit button -->
               <button type="submit" class="btn btn-primary mt-1 mr-2">
                  <span class="glyphicon glyphicon-floppy-save"></span> Tambah Data
               </button>
            </form>

         </div>
      </div>
   </div>
</div>

<!-- Script to show/hide fields based on status -->
<script>
    document.querySelector('select[name="status"]').addEventListener('change', function() {
       var reasonField = document.getElementById('reasonField');
       var tglField = document.getElementById('tglField');
       var fotoField = document.getElementById('fotoField');

       if (this.value === 'tidak_dapat_dikerjakan') {
          reasonField.style.display = 'block';  // Show alasan field
          tglField.style.display = 'none';      // Hide tgl selesai field
          fotoField.style.display = 'none';     // Hide foto field
       } else {
          reasonField.style.display = 'none';   // Hide alasan field
          tglField.style.display = 'block';     // Show tgl selesai field
          fotoField.style.display = 'block';    // Show foto field
       }
    });
</script>

@endsection
