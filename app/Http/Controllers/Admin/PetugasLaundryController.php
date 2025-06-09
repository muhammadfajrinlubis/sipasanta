<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PetugasLaundryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function read(){
        $petugaslaundry = DB::table('petugaslaundry')->orderBy('nama','ASC')->get();
        return view('admin.petugaslaundry.index',['petugaslaundry'=>$petugaslaundry]);
    }
    public function add(){
        $jabatan = DB::table('jabatan')->orderBy('id','DESC')->get();
        return view('admin.petugaslaundry.tambah',['jabatan'=>$jabatan]);
    }

    public function create(Request $request){
        DB::table('users')->insert([
            'name'=>$request->nama,
            'username'=>$request->nip,

            'level'=>'5',
            'password'=> bcrypt('Admin2024')
            ]);

        $users= DB::table('users')->orderBy('id','DESC')->first();

        $dokumen = $request->file('foto');
        if ($request->hasFile('foto')) {
            $name = uniqid().".".$dokumen->getClientOriginalExtension();
            $dokumen->move(public_path() . "/public/profil",$name);

            DB::table('petugaslaundry')->insert([
                'nama' => $request->nama,
                'nip' => $request->nip,
                'no_hp' => $request->no_hp,
                'id_jabatan' => $request->id_jabatan,
                'id_user' => $users->id,
                'foto' => $name]);
        } else {
            DB::table('petugaslaundry')->insert([
                'nama' => $request->nama,
                'nip' => $request->nip,
                'no_hp' => $request->no_hp,
                'id_jabatan' => $request->id_jabatan,
                'id_user' => $users->id]);
        }

        return redirect('/admin/petugaslaundry')->with("success","Data Berhasil Ditambah !");
    }
    public function edit($id){
        $petugaslaundry= DB::table('petugaslaundry')->where('id',$id)->first();
        $jabatanSelect = DB::table('jabatan')->find($petugaslaundry->id_jabatan);
        if($jabatanSelect != ""){
            $jabatan = DB::table('jabatan')->where('id', '!=',$jabatanSelect->id)->orderBy('id','DESC')->get();
        } else {
            $jabatan = DB::table('jabatan')->orderBy('id','DESC')->get();
        }

        return view('admin.petugaslaundry.edit',['petugaslaundry'=>$petugaslaundry,'jabatanSelect'=>$jabatanSelect,'jabatan'=>$jabatan]);
    }

    public function update(Request $request, $id) {
        // Ambil data petugas berdasarkan ID
        $petugaslaundry = DB::table('petugaslaundry')->find($id);

        // Update data di tabel users
        DB::table('users')
            ->where('id', $petugaslaundry->id_user)
            ->update([
                'name' => $request->nama,
                'username' => $request->nip,
            ]);

        // Cek jika ada file foto yang diunggah
        if ($request->hasFile('foto')) {
            // Hapus file lama jika ada
            if (!empty($petugaslaundry->foto) && file_exists(public_path('public/profil/' . $petugaslaundry->foto))) {
                unlink(public_path('public/profil/' . $petugaslaundry->foto));
            }

            // Simpan file baru
            $foto = $request->file('foto');
            $name = uniqid() . "." . $foto->getClientOriginalExtension();  // Mengganti $dokumen dengan $foto
            $foto->move(public_path('public/profil'), $name);

            // Update data petugas dengan foto baru
            DB::table('petugaslaundry')
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
            DB::table('petugaslaundry')
                ->where('id', $id)
                ->update([
                    'nama' => $request->nama,
                    'nip' => $request->nip,
                    'no_hp' => $request->no_hp,
                    'id_jabatan' => $request->id_jabatan
                ]);
        }

        // Redirect ke halaman admin/petugas dengan pesan sukses
        return redirect('/admin/petugaslaundry')->with('success', 'Data Berhasil Diupdate!');
    }

    public function delete($id){
        $petugaslaundry = DB::table('petugaslaundry')->where('id', $id)->first();

        // Menghapus foto jika ada
        if ($petugaslaundry->foto) {
            $file_path = public_path() . "/public/profil/" . $petugaslaundry->foto;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        // Hapus data petugas dari database
        DB::table('petugaslaundry')->where('id', $id)->delete();

        // Hapus juga dari tabel users
        DB::table('users')->where('id', $petugaslaundry->id_user)->delete();

        return redirect('/admin/petugaslaundry')->with("success", "Data Berhasil Dihapus!");
    }

}
