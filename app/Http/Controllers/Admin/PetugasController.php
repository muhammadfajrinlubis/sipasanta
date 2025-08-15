<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function read(){
        $petugas = DB::table('petugas')->orderBy('nama','ASC')->get();
        return view('admin.petugas.index',['petugas'=>$petugas]);
    }

    public function add(){
        $jabatan = DB::table('jabatan')->orderBy('id','DESC')->get();
        return view('admin.petugas.tambah',['jabatan'=>$jabatan]);
    }

    public function create(Request $request){
        // Cek apakah NIP sudah ada di tabel users
        $existingUser = DB::table('users')->where('username', $request->nip)->first();

        if($existingUser){
            return redirect('/admin/petugas')->with("error", "NIP $request->nip sudah terdaftar!");
        }

        // Jika belum ada, lanjut insert
        DB::table('users')->insert([
            'name' => $request->nama,
            'username' => $request->nip,
            'level' => '3',
            'password' => bcrypt('Admin2024')
        ]);

        $users = DB::table('users')->orderBy('id','DESC')->first();

        $dokumen = $request->file('foto');
        if ($request->hasFile('foto')) {
            $name = uniqid().".".$dokumen->getClientOriginalExtension();
            $dokumen->move(public_path() . "/public/profil",$name);

            DB::table('petugas')->insert([
                'nama' => $request->nama,
                'nip' => $request->nip,
                'id_jabatan' => $request->id_jabatan,
                'no_hp' => $request->no_hp,
                'id_user' => $users->id,
                'foto' => $name
            ]);
        } else {
            DB::table('petugas')->insert([
                'nama' => $request->nama,
                'nip' => $request->nip,
                'id_jabatan' => $request->id_jabatan,
                'no_hp' => $request->no_hp,
                'id_user' => $users->id
            ]);
        }

        return redirect('/admin/petugas')->with("success","Data Berhasil Ditambah !");
    }


    public function edit($id){
        $petugas= DB::table('petugas')->where('id',$id)->first();
        $jabatanSelect = DB::table('jabatan')->find($petugas->id_jabatan);
        if($jabatanSelect != ""){
            $jabatan = DB::table('jabatan')->where('id', '!=',$jabatanSelect->id)->orderBy('id','DESC')->get();
        } else {
            $jabatan = DB::table('jabatan')->orderBy('id','DESC')->get();
        }

        return view('admin.petugas.edit',['petugas'=>$petugas,'jabatanSelect'=>$jabatanSelect,'jabatan'=>$jabatan]);
    }

    public function update(Request $request, $id) {
        // Ambil data petugas berdasarkan ID
        $petugas = DB::table('petugas')->find($id);

        // Update data di tabel users
        DB::table('users')
            ->where('id', $petugas->id_user)
            ->update([
                'name' => $request->nama,
                'username' => $request->nip,
            ]);

        // Cek jika ada file foto yang diunggah
        if ($request->hasFile('foto')) {
            // Hapus file lama jika ada
            if (!empty($petugas->foto) && file_exists(public_path('public/profil/' . $petugas->foto))) {
                unlink(public_path('public/profil/' . $petugas->foto));
            }

            // Simpan file baru
            $foto = $request->file('foto');
            $name = uniqid() . "." . $foto->getClientOriginalExtension();  // Mengganti $dokumen dengan $foto
            $foto->move(public_path('public/profil'), $name);

            // Update data petugas dengan foto baru
            DB::table('petugas')
                ->where('id', $id)
                ->update([
                    'nama' => $request->nama,
                    'nip' => $request->nip,
                    'no_hp' => $request->no_hp,
                    'id_jabatan' => $request->id_jabatan,
                    'foto' => $name
                ]);
        } else {
            // Update data petugas tanpa foto
            DB::table('petugas')
                ->where('id', $id)
                ->update([
                    'nama' => $request->nama,
                    'nip' => $request->nip,
                    'no_hp' => $request->no_hp,
                    'id_jabatan' => $request->id_jabatan
                ]);
        }

        // Redirect ke halaman admin/petugas dengan pesan sukses
        return redirect('/admin/petugas')->with('success', 'Data Berhasil Diupdate!');
    }

    public function delete($id){
        $petugas = DB::table('petugas')->where('id', $id)->first();

        // Menghapus foto jika ada
        if ($petugas->foto) {
            $file_path = public_path() . "/public/profil/" . $petugas->foto;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        // Hapus data petugas dari database
        DB::table('petugas')->where('id', $id)->delete();

        // Hapus juga dari tabel users
        DB::table('users')->where('id', $petugas->id_user)->delete();

        return redirect('/admin/petugas')->with("success", "Data Berhasil Dihapus!");
    }
    public function resetPassword($id)
    {
        // Ambil id_user dari tabel petugas
        $petugas = DB::table('petugas')->where('id', $id)->first();

        if (!$petugas) {
            return redirect()->back()->with('error', 'Data petugas tidak ditemukan');
        }

        // Update password user yang terkait
        DB::table('users')->where('id', $petugas->id_user)->update([
            'password' => Hash::make('12345678'),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Password berhasil di-reset ke 12345678');
    }
}
