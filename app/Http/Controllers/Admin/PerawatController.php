<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class PerawatController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }

    public function read(){
        $admin_perawat = DB::table('admin_perawat')->orderBy('nama','ASC')->get();
        return view('admin.perawat.index',['admin_perawat'=>$admin_perawat]);
    }

    public function add(){
        $jabatan = DB::table('jabatan')->orderBy('id','DESC')->get();
        return view('admin.perawat.tambah',['jabatan'=>$jabatan]);
    }
    public function create(Request $request){
        DB::table('users')->insert([
            'name'=>$request->nama,
            'username'=>$request->nip,

            'level'=>'6',
            'password'=> bcrypt('Admin2024')
            ]);

        $users= DB::table('users')->orderBy('id','DESC')->first();

        $dokumen = $request->file('foto');
        if ($request->hasFile('foto')) {
            $name = uniqid().".".$dokumen->getClientOriginalExtension();
            $dokumen->move(public_path() . "/public/profil",$name);

            DB::table('admin_perawat')->insert([
                'nama' => $request->nama,
                'nip' => $request->nip,
                'no_hp' => $request->no_hp,
                'id_jabatan' => $request->id_jabatan,
                'id_user' => $users->id,
                'foto' => $name]);
        } else {
            DB::table('admin_perawat')->insert([
                'nama' => $request->nama,
                'nip' => $request->nip,
                'no_hp' => $request->no_hp,
                'id_jabatan' => $request->id_jabatan,
                'id_user' => $users->id]);
        }

        return redirect('/admin/perawat')->with("success","Data Berhasil Ditambah !");
    }

    public function resetPassword($id)
    {
        // Ambil id_user dari tabel admin_perawat
        $admin_perawat = DB::table('admin_perawat')->where('id', $id)->first();

        if (!$admin_perawat) {
            return redirect()->back()->with('error', 'Data admin_perawat tidak ditemukan');
        }

        // Update password user yang terkait
        DB::table('users')->where('id', $admin_perawat->id_user)->update([
            'password' => Hash::make('12345678'),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Password berhasil di-reset ke 12345678');
    }
}
