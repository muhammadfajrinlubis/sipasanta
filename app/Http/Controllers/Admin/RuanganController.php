<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Auth;


class RuanganController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function read(){
        $ruangan = DB::table('ruangan')->orderBy('nama','ASC')->get();
        return view('admin.ruangan.index',['ruangan'=>$ruangan]);
    }

    public function add(){
        $ruangan = DB::table('ruangan')->orderBy('id','DESC')->get();
        return view('admin.ruangan.tambah',['ruangan'=>$ruangan]);
    }

    public function create(Request $request)
    {
        $exists = DB::table('ruangan')->where('kode_ruangan', $request->kode_ruangan)->exists();

        if ($exists) {
            return redirect('/admin/ruangan')->with('error', 'Kode Ruangan sudah digunakan!');
        }

        DB::table('ruangan')->insert([
            'nama' => $request->nama,
            'kode_ruangan' => $request->kode_ruangan,
        ]);

        return redirect('/admin/ruangan')->with('success', 'Data Berhasil Ditambah!');
    }



    public function edit($id){
        $ruangan= DB::table('ruangan')->where('id',$id)->first();
        return view('admin.ruangan.edit',['ruangan'=>$ruangan]);
    }

    public function detail($id){
        $ruangan= DB::table('ruangan')->where('id',$id)->first();
        return view('admin.ruangan.detail',['ruangan'=>$ruangan]);
    }

    public function update(Request $request, $id) {
        DB::table('ruangan')
            ->where('id', $id)
            ->update([
            'nama' => $request->nama,
            'kode_ruangan' => $request->kode_ruangan]);

        return redirect('/admin/ruangan')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        $ruangan= DB::table('ruangan')->where('id',$id)->first();
        DB::table('ruangan')->where('id',$id)->delete();
        //DB::table('pegawai')->where('id_ruangan',$id)->delete();

        return redirect('/admin/ruangan')->with("error","Data Berhasil Dihapus !");
    }

}
