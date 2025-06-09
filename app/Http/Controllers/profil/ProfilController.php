<?php

namespace App\Http\Controllers\profil;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ProfilController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function read($id)
    {
        // Dapatkan ID user yang sedang login
        $loggedInUserId = Auth::id();

        // Jika user yang diminta bukan user yang sedang login, blokir akses
        if ($id != $loggedInUserId) {
            return redirect()->back()->with('error', 'Anda tidak diizinkan melihat data pengguna lain.');
        }

        // Ambil data dari tabel users
        $users = DB::table('users')->where('id', $id)->first();

        // Ambil data jabatan dari tabel pegawai, petugas, atau admin
        $pegawai = DB::table('pegawai')->where('id_user', $id)->first();
        $petugas = DB::table('petugas')->where('id_user', $id)->first();
        $admin = DB::table('admin')->where('id_user', $id)->first();

        // Tentukan jabatan berdasarkan data yang ditemukan
        if ($pegawai) {
            $users->jabatan = 'Pegawai';
            $users->foto = $pegawai->foto;
        } elseif ($petugas) {
            $users->jabatan = 'Petugas';
            $users->foto = $petugas->foto;
        } elseif ($admin) {
            $users->jabatan = 'Administrator';
            $users->foto = $admin->foto;
        } else {
            $users->jabatan = null; // Jika tidak ada jabatan
            $users->foto = null; // Jika tidak ada foto
        }

        // Kirim data ke view
        return view('profil.index', ['users' => $users]);

    }

    public function edit($id)
{
    // Mendapatkan ID pengguna yang sedang login
    $loggedInUserId = Auth::id();

    // Jika pengguna yang login bukan pemilik data, alihkan ke halaman 403 atau tampilkan error
    if ($loggedInUserId != $id) {
        abort(403, 'Anda tidak diizinkan untuk mengakses halaman ini.');
    }

    // Ambil data pengguna berdasarkan ID
    $users = DB::table('users')->where('id', $id)->first();
    $pegawai = DB::table('pegawai')->where('id_user', $id)->first();
    $petugas = DB::table('petugas')->where('id_user', $id)->first();
    $admin = DB::table('admin')->where('id_user', $id)->first();

    // Tentukan jabatan dan foto berdasarkan data yang ditemukan
    if ($pegawai) {
        $users->jabatan = 'Pegawai';
        $users->foto = $pegawai->foto;
    } elseif ($petugas) {
        $users->jabatan = 'Petugas';
        $users->foto = $petugas->foto;
    } elseif ($admin) {
        $users->jabatan = 'Administrator';
        $users->foto = $admin->foto;
    } else {
        $users->jabatan = null; // Jika tidak ada jabatan
        $users->foto = null; // Jika tidak ada foto
    }

    // Tampilkan halaman edit profil dengan data pengguna
    return view('profil.edit', ['users' => $users]);
}

    public function update(Request $request, $id)
    {
        // Validasi input foto
        $request->validate([
            'foto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Ambil data pengguna dari tabel users
        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan.');
        }

        // Cek apakah ada file foto yang diupload
        if ($request->hasFile('foto')) {
            // Nama file baru
            $file = $request->file('foto');
            $fileName = time() . '.' . $file->getClientOriginalExtension();

            // Hapus foto lama jika ada
            if (!empty($user->foto) && file_exists(public_path('public/profil/' . $user->foto))) {
                unlink(public_path('public/profil/' . $user->foto));
            }

            // Pindahkan foto yang diupload ke folder 'public/profil'
            $file->move(public_path('public/profil'), $fileName);



            // Update foto di tabel pegawai, petugas, atau admin jika user tersebut ada di sana
            if (DB::table('pegawai')->where('id_user', $id)->exists()) {
                DB::table('pegawai')->where('id_user', $id)->update(['foto' => $fileName]);
            }
            if (DB::table('petugas')->where('id_user', $id)->exists()) {
                DB::table('petugas')->where('id_user', $id)->update(['foto' => $fileName]);
            }
            if (DB::table('admin')->where('id_user', $id)->exists()) {
                DB::table('admin')->where('id_user', $id)->update(['foto' => $fileName]);
            }

            // Redirect ke halaman profil dengan pesan sukses
            return redirect('/profil/edit')->with('success', 'Foto profil berhasil diperbarui!');
        } else {
            return redirect()->back()->with('error', 'Tidak ada foto yang diupload.');
        }
    }

}
