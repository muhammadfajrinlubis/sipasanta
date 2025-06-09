<?php

namespace App\Http\Controllers\Pegawai;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PermintaanLaundryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function read(){
        $laundry = DB::table('laundry')->where('id_user',Auth::User()->id)->orderBy('tanggal','DESC')->get();

        return view('pegawai.laundry.index',['laundry'=>$laundry]);
    }
    public function add(){
        $pasien = DB::table('pasien')->orderBy('id','DESC')->get();
        $ruangan = DB::table('ruangan')->orderBy('id','DESC')->get();
        return view('pegawai.laundry.tambah',['pasien'=>$pasien,'ruangan'=>$ruangan]);
    }

    public function create(Request $request){
        $request->validate([
            'nomr' => 'required',
            'id_ruangan' => 'required',
            'tanggal' => 'required|date',

        ]);

        DB::table('laundry')->insert([
            'nomr' => $request->nomr,
            'id_ruangan' => $request->id_ruangan,
            'tanggal' => $request->tanggal,
            'id_user' => auth()->user()->id,
            'keterangan' => '0',
        ]);

        return redirect('/pegawai/laundry')->with('success', 'Data pengaduan berhasil ditambahkan');

    }

    public function edit($id)
    {
        $laundry = DB::table('laundry')->where('id', $id)->first();
        $pasien = DB::table('pasien')->orderBy('id', 'DESC')->get();
        $ruangan = DB::table('ruangan')->orderBy('id', 'DESC')->get();

        return view('pegawai.laundry.edit', ['laundry' => $laundry, 'pasien' => $pasien, 'ruangan' => $ruangan]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nomr' => 'required',
            'id_ruangan' => 'required',
            'tanggal' => 'required|date',
        ]);

        DB::table('laundry')->where('id', $id)->update([
            'nomr' => $request->nomr,
            'id_ruangan' => $request->id_ruangan,
            'tanggal' => $request->tanggal,

        ]);

        return redirect('/pegawai/laundry')->with('success', 'Data laundry berhasil diupdate');
    }

    public function delete($id)
    {
        DB::table('laundry')->where('id', $id)->delete();
        return redirect('/pegawai/laundry')->with('success', 'Data laundry berhasil dihapus');
    }

}
