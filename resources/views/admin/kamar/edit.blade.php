@extends('admin.layouts.app', [
    'activePage' => 'kamar',
])

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="widget p-md clearfix">
            <div class="pull-left">
                <h1 class="widget-title" style="font-size: 30px; margin-bottom: 5px;">Edit Data Kamar</h1>
                <small class="text-color">
                    Data Master
                    <span style="margin:0px 3px;"> > </span>
                    <a href="{{ url('/admin/kamar') }}">Data Kamar</a>
                    <span style="margin:0px 3px;"> > </span>
                    Edit Data Kamar
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
                        <i class="glyphicon glyphicon-edit"></i> Edit Data Kamar
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

                <form action="{{ url('/admin/kamar/update/'.$kamar->id) }}" method="POST">
                    @csrf
                    @method('POST')

                   <div class="form-group">
                    <label for="ruangan_id">Pilih Ruangan <span class="text-danger">*</span></label>
                    <select name="ruangan_id" id="ruangan_id" class="form-control select2" required>
                        <option value="">-- Pilih Ruangan --</option>
                        @foreach ($ruangans as $ruangan)
                            <option value="{{ $ruangan->id }}" {{ $kamar->ruangan_id == $ruangan->id ? 'selected' : '' }}>
                                {{ $ruangan->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>


                    <div class="form-group">
                        <label for="nomor_kamar">Nomor Kamar <span class="text-danger">*</span></label>
                        <input type="text" name="nomor_kamar" class="form-control" value="{{ $kamar->nomor_kamar }}" required>
                    </div>

                      <button type="submit" class="btn btn-primary mt-1 mr-2"><span class="glyphicon glyphicon-floppy-save"></span> Edit Data</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
