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

    public function edit($id){
        $admin_perawat = DB::table('admin_perawat')->where('id',$id)->first();
        $jabatanSelect = DB::table('jabatan')->find($admin_perawat->id_jabatan);
        if($jabatanSelect != ""){
            $jabatan = DB::table('jabatan')->where('id', '!=',$jabatanSelect->id)->orderBy('id','DESC')->get();
        } else {
            $jabatan = DB::table('jabatan')->orderBy('id','DESC')->get();
        }

        return view('admin.perawat.edit',['admin_perawat'=>$admin_perawat,'jabatanSelect'=>$jabatanSelect,'jabatan'=>$jabatan]);
    }

     public function update(Request $request, $id) {
        // Ambil data admin_perawat berdasarkan ID
        $admin_perawat = DB::table('admin_perawat')->find($id);

        // Update data di tabel users
        DB::table('users')
            ->where('id', $admin_perawat->id_user)
            ->update([
                'name' => $request->nama,
                'username' => $request->nip,
            ]);

        // Cek jika ada file foto yang diunggah
        if ($request->hasFile('foto')) {
            // Hapus file lama jika ada
            if (!empty($admin_perawat->foto) && file_exists(public_path('public/profil/' . $admin_perawat->foto))) {
                unlink(public_path('public/profil/' . $admin_perawat->foto));
            }

            // Simpan file baru
            $foto = $request->file('foto');
            $name = uniqid() . "." . $foto->getClientOriginalExtension();  // Mengganti $dokumen dengan $foto
            $foto->move(public_path('public/profil'), $name);

            // Update data admin_perawat dengan foto baru
            DB::table('admin_perawat')
                ->where('id', $id)
                ->update([
                    'nama' => $request->nama,
                    'nip' => $request->nip,
                    'no_hp' => $request->no_hp,
                    'id_jabatan' => $request->id_jabatan,
                    'foto' => $name
                ]);
        } else {
            // Update data admin_perawat tanpa foto
            DB::table('admin_perawat')
                ->where('id', $id)
                ->update([
                    'nama' => $request->nama,
                    'nip' => $request->nip,
                    'no_hp' => $request->no_hp,
                    'id_jabatan' => $request->id_jabatan
                ]);
        }

        // Redirect ke halaman admin/admin_perawat dengan pesan sukses
        return redirect('/admin/perawat')->with('success', 'Data Berhasil Diupdate!');
    }

    public function delete($id)
    {
        $admin_perawat= DB::table('admin_perawat')->where('id',$id)->first();
        DB::table('admin_perawat')->where('id',$id)->delete();
        DB::table('users')->where('id',$admin_perawat->id_user)->delete();

        return redirect('/admin/perawat')->with("error","Data Berhasil Dihapus !");
    }
}
