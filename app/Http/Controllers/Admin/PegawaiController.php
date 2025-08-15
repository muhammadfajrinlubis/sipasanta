<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use App\Imports\PegawaiImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class PegawaiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function read(){
        $pegawai = DB::table('pegawai')->orderBy('nama','ASC')->get();
        return view('admin.pegawai.index',['pegawai'=>$pegawai]);
    }

    public function add(){
        $jabatan = DB::table('jabatan')->orderBy('id','DESC')->get();
        return view('admin.pegawai.tambah',['jabatan'=>$jabatan]);
    }

    public function create(Request $request){
        // Cek apakah NIP sudah ada di tabel users
        $existingUser = DB::table('users')->where('username', $request->nip)->first();

        if($existingUser){
            return redirect('/admin/pegawai')->with("error", "NIP $request->nip sudah terdaftar!");
        }

        // Jika belum ada, lanjut insert
        DB::table('users')->insert([
            'name' => $request->nama,
            'username' => $request->nip,
            'level' => '4',
            'password' => bcrypt('Admin2024')
        ]);

        $users = DB::table('users')->orderBy('id','DESC')->first();

        $dokumen = $request->file('foto');
        if ($request->hasFile('foto')) {
            $name = uniqid().".".$dokumen->getClientOriginalExtension();
            $dokumen->move(public_path() . "/public/profil",$name);

            DB::table('pegawai')->insert([
                'nama' => $request->nama,
                'nip' => $request->nip,
                'no_hp' => $request->no_hp,
                'id_jabatan' => $request->id_jabatan,
                'id_user' => $users->id,
                'foto' => $name
            ]);
        } else {
            DB::table('pegawai')->insert([
                'nama' => $request->nama,
                'nip' => $request->nip,
                'no_hp' => $request->no_hp,
                'id_jabatan' => $request->id_jabatan,
                'id_user' => $users->id
            ]);
        }

        return redirect('/admin/pegawai')->with("success","Data Berhasil Ditambah !");
    }


    public function edit($id){
        $pegawai= DB::table('pegawai')->where('id',$id)->first();
        $jabatanSelect = DB::table('jabatan')->find($pegawai->id_jabatan);
        if($jabatanSelect != ""){
            $jabatan = DB::table('jabatan')->where('id', '!=',$jabatanSelect->id)->orderBy('id','DESC')->get();
        } else {
            $jabatan = DB::table('jabatan')->orderBy('id','DESC')->get();
        }

        return view('admin.pegawai.edit',['pegawai'=>$pegawai,'jabatanSelect'=>$jabatanSelect,'jabatan'=>$jabatan]);
    }

    public function update(Request $request, $id) {
        // Ambil data pegawai berdasarkan ID
        $pegawai = DB::table('pegawai')->find($id);

        // Update data di tabel users
        DB::table('users')
            ->where('id', $pegawai->id_user)
            ->update([
                'name' => $request->nama,
                'username' => $request->nip,
            ]);

        // Cek jika ada file foto yang diunggah
        if ($request->hasFile('foto')) {
            // Hapus file lama jika ada
            if (!empty($pegawai->foto) && file_exists(public_path('public/profil/' . $pegawai->foto))) {
                unlink(public_path('public/profil/' . $pegawai->foto));
            }

            // Simpan file baru
            $foto = $request->file('foto');
            $name = uniqid() . "." . $foto->getClientOriginalExtension();  // Mengganti $dokumen dengan $foto
            $foto->move(public_path('public/profil'), $name);

            // Update data pegawai dengan foto baru
            DB::table('pegawai')
                ->where('id', $id)
                ->update([
                    'nama' => $request->nama,
                    'nip' => $request->nip,
                    'no_hp' => $request->no_hp,
                    'id_jabatan' => $request->id_jabatan,
                    'foto' => $name
                ]);
        } else {
            // Update data pegawai tanpa foto
            DB::table('pegawai')
                ->where('id', $id)
                ->update([
                    'nama' => $request->nama,
                    'nip' => $request->nip,
                    'no_hp' => $request->no_hp,
                    'id_jabatan' => $request->id_jabatan
                ]);
        }

        // Redirect ke halaman admin/pegawai dengan pesan sukses
        return redirect('/admin/pegawai')->with('success', 'Data Berhasil Diupdate!');
    }

    public function delete($id)
    {
        $pegawai= DB::table('pegawai')->where('id',$id)->first();
        DB::table('pegawai')->where('id',$id)->delete();
        DB::table('users')->where('id',$pegawai->id_user)->delete();

        return redirect('/admin/pegawai')->with("error","Data Berhasil Dihapus !");
    }

    public function on($id)
    {
        DB::table('pegawai')
            ->where('id', $id)
            ->update([
                'status_aktif' => 'Aktif'
        ]);

        return redirect('/admin/pegawai')->with("success","Data Berhasil Diaktifkan !");
    }

    public function off($id)
    {
        DB::table('pegawai')
            ->where('id', $id)
            ->update([
                'status_aktif' => 'Tidak Aktif'
        ]);

        return redirect('/admin/pegawai')->with("success","Data Berhasil Dinonaktifkan !");
    }

    public function reset($id)
    {
        $pegawai= DB::table('pegawai')->where('id',$id)->first();
        DB::table('users')
            ->where('id', $pegawai->id_user)
            ->update([
                'password'=> bcrypt('Admin2024')
        ]);

        return redirect('/admin/pegawai')->with("success","Data Berhasil Direset, Dengan Password Default : Admin2024 !");
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        Excel::import(new PegawaiImport, $request->file('file'));

        return redirect('/admin/pegawai')->with('success', 'Data Berhasil Diimport !');
    }

    public function cetak($id){
        $pegawai = DB::table('pegawai')->find($id);
        $jabatan = DB::table('jabatan')->find($pegawai->id_jabatan);

        $pdf = PDF::loadview('admin.pegawai.cetak',['pegawai'=>$pegawai,'jabatan'=>$jabatan]);

        return $pdf->stream('Cetak Data '.$pegawai->nama.'.pdf');
    }

    public function resetPassword($id)
    {
        // Ambil id_user dari tabel pegawai
        $pegawai = DB::table('pegawai')->where('id', $id)->first();

        if (!$pegawai) {
            return redirect()->back()->with('error', 'Data pegawai tidak ditemukan');
        }

        // Update password user yang terkait
        DB::table('users')->where('id', $pegawai->id_user)->update([
            'password' => Hash::make('12345678'),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Password berhasil di-reset ke 12345678');
    }

}
