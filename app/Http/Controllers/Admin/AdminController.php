<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function read(){
        $data['header_title'] = "Admin List";
        $admin = DB::table('admin')->orderBy('nama','ASC')->get();
        return view('admin.admin.index',['admin'=>$admin]);
    }

    public function add(){
        $jabatan = DB::table('jabatan')->orderBy('id','DESC')->get();
        return view('admin.admin.tambah',['jabatan'=>$jabatan]);
    }

    public function create(Request $request){
        DB::table('users')->insert([
            'name'=>$request->nama,
            'username'=>$request->nip,
            'level'=>'2',
            'password'=> bcrypt('Admin2024')
            ]);

        $users= DB::table('users')->orderBy('id','DESC')->first();

        $dokumen = $request->file('foto');
        if ($request->hasFile('foto')) {
            $name = uniqid().".".$dokumen->getClientOriginalExtension();
            $dokumen->move(public_path() . "/public/profil",$name);

            DB::table('admin')->insert([
                'nama' => $request->nama,
                'nip' => $request->nip,
                'id_jabatan' => $request->id_jabatan,
                'no_hp' => $request->no_hp,
                'id_user' => $users->id,
                'foto' => $name]);
        } else {
            DB::table('admin')->insert([
                'nama' => $request->nama,
                'nip' => $request->nip,
                'no_hp' => $request->no_hp,
                'id_jabatan' => $request->id_jabatan,
                'id_user' => $users->id]);
        }

        return redirect('/admin/admin')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $admin= DB::table('admin')->where('id',$id)->first();
        $jabatanSelect = DB::table('jabatan')->find($admin->id_jabatan);
        if($jabatanSelect != ""){
            $jabatan = DB::table('jabatan')->where('id', '!=',$jabatanSelect->id)->orderBy('id','DESC')->get();
        } else {
            $jabatan = DB::table('jabatan')->orderBy('id','DESC')->get();
        }

        return view('admin.admin.edit',['admin'=>$admin,'jabatanSelect'=>$jabatanSelect,'jabatan'=>$jabatan]);
    }

    public function update(Request $request, $id) {
        // Ambil data admin berdasarkan ID
        $admin = DB::table('admin')->find($id);

        // Update data di tabel users
        DB::table('users')
            ->where('id', $admin->id_user)
            ->update([
                'name' => $request->nama,
                'username' => $request->nip,
            ]);

        // Cek jika ada file foto yang diunggah
        if ($request->hasFile('foto')) {
            // Hapus file lama jika ada
            if (!empty($admin->foto) && file_exists(public_path('public/profil/' . $admin->foto))) {
                unlink(public_path('public/profil/' . $admin->foto));
            }

            // Simpan file baru
            $foto = $request->file('foto');
            $name = uniqid() . "." . $foto->getClientOriginalExtension();  // Mengganti $dokumen dengan $foto
            $foto->move(public_path('public/profil'), $name);

            // Update data admin dengan foto baru
            DB::table('admin')
                ->where('id', $id)
                ->update([
                    'nama' => $request->nama,
                    'nip' => $request->nip,
                    'no_hp' => $request->no_hp,
                    'id_jabatan' => $request->id_jabatan,
                    'foto' => $name
                ]);
        } else {
            // Update data admin tanpa foto
            DB::table('admin')
                ->where('id', $id)
                ->update([
                    'nama' => $request->nama,
                    'nip' => $request->nip,
                    'no_hp' => $request->no_hp,
                    'id_jabatan' => $request->id_jabatan
                ]);
        }

        // Redirect ke halaman admin/admin dengan pesan sukses
        return redirect('/admin/admin')->with('success', 'Data Berhasil Diupdate!');
    }

    public function delete($id)
    {
        $admin= DB::table('admin')->where('id',$id)->first();
        DB::table('admin')->where('id',$id)->delete();
        DB::table('users')->where('id',$admin->id_user)->delete();

        return redirect('/admin/admin')->with("success","Data Berhasil Dihapus !");
    }

    public function resetPassword($id)
    {
        // Ambil id_user dari tabel admin
        $admin = DB::table('admin')->where('id', $id)->first();

        if (!$admin) {
            return redirect()->back()->with('error', 'Data admin tidak ditemukan');
        }

        // Update password user yang terkait
        DB::table('users')->where('id', $admin->id_user)->update([
            'password' => Hash::make('12345678'),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Password berhasil di-reset ke 12345678');
    }


}
