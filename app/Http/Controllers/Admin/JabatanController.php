<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\JabatanImport;
use App\Exports\JabatanExport;
use Auth;
use PDF;

class JabatanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function read(){
        $jabatan = DB::table('jabatan')->orderBy('id','DESC')->get();
        return view('admin.jabatan.index',['jabatan'=>$jabatan]);
    }

    public function add(){
        return view('admin.jabatan.tambah');
    }

    public function create(Request $request){
        DB::table('jabatan')->insert([
            'nama' => $request->nama]);

        return redirect('/admin/jabatan')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $jabatan= DB::table('jabatan')->where('id',$id)->first();
        return view('admin.jabatan.edit',['jabatan'=>$jabatan]);
    }

    public function detail($id){
        $jabatan= DB::table('jabatan')->where('id',$id)->first();
        return view('admin.jabatan.detail',['jabatan'=>$jabatan]);
    }

    public function update(Request $request, $id) {
        DB::table('jabatan')
            ->where('id', $id)
            ->update([
            'nama' => $request->nama]);

        return redirect('/admin/jabatan')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        $jabatan= DB::table('jabatan')->where('id',$id)->first();
        DB::table('jabatan')->where('id',$id)->delete();
        //DB::table('pegawai')->where('id_jabatan',$id)->delete();

        return redirect('/admin/jabatan')->with("error","Data Berhasil Dihapus !");
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        Excel::import(new JabatanImport, $request->file('file'));

        return redirect('/admin/jabatan')->with('success', 'Data Berhasil Diimport !');
    }

    public function export()
    {
        return Excel::download(new JabatanExport, 'List Data Jabatan.xlsx');
    }

    public function cetak()
    {
        $jabatan = DB::table('jabatan')->orderBy('id','DESC')->get();

        $pdf = PDF::loadview('admin.jabatan.cetak',['jabatan'=>$jabatan]);

        return $pdf->stream('List Data Jabatan.pdf');
    }
}
