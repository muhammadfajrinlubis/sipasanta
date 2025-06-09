<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;



class SaranaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function read(){
        $sarana = DB::table('sarana')->orderBy('nama','ASC')->get();
        return view('admin.sarana.index',['sarana'=>$sarana]);
    }

    public function add(){
        $sarana = DB::table('sarana')->orderBy('id','DESC')->get();
        return view('admin.sarana.tambah',['sarana'=>$sarana]);
    }

    public function create(Request $request){
        DB::table('sarana')->insert([
            'nama' => $request->nama]);
        return redirect('/admin/sarana')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $sarana= DB::table('sarana')->where('id',$id)->first();
        return view('admin.sarana.edit',['sarana'=>$sarana]);
    }

    public function detail($id){
        $sarana= DB::table('sarana')->where('id',$id)->first();
        return view('admin.sarana.detail',['sarana'=>$sarana]);
    }

    public function update(Request $request, $id) {
        DB::table('sarana')
            ->where('id', $id)
            ->update([
            'nama' => $request->nama]);

        return redirect('/admin/sarana')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        $sarana= DB::table('sarana')->where('id',$id)->first();
        DB::table('sarana')->where('id',$id)->delete();
        //DB::table('pegawai')->where('id_sarana',$id)->delete();

        return redirect('/admin/sarana')->with("error","Data Berhasil Dihapus !");
    }

}
