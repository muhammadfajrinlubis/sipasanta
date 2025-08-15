<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class KamarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function read()
    {
       $kamar = Kamar::with('ruangan')->get(); // join dengan tabel ruangan
        return view('admin.kamar.index', compact('kamar'));
    }
    public function add() {
    // Ambil semua data ruangan, supaya bisa dipilih di form kamar
    $ruangans = DB::table('ruangan')->orderBy('nama', 'ASC')->get();

    // Kirim data ruangan ke view tambah kamar
    return view('admin.kamar.tambah', ['ruangans' => $ruangans]);
    }

   // Proses simpan data kamar ke database
    public function create(Request $request)
    {
        // Validasi input
        $request->validate([
            'ruangan_id' => 'required|exists:ruangan,id',
            'nomor_kamar' => 'required|string|max:255|unique:kamar,nomor_kamar',
        ]);

        // Insert data kamar baru
        DB::table('kamar')->insert([
            'ruangan_id' => $request->ruangan_id,
            'nomor_kamar' => $request->nomor_kamar,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/admin/kamar')->with('success', 'Data kamar berhasil ditambahkan.');
    }


    public function edit($id)
    {
        $kamar = DB::table('kamar')->where('id', $id)->first();
        $ruangans = DB::table('ruangan')->orderBy('nama', 'ASC')->get();

        if (!$kamar) {
            return redirect('/admin/kamar')->with('error', 'Data kamar tidak ditemukan.');
        }

        return view('admin.kamar.edit', compact('kamar', 'ruangans'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ruangan_id' => 'required|exists:ruangan,id',
            'nomor_kamar' => 'required|string|max:255',
        ]);

        $kamar = DB::table('kamar')->where('id', $id)->first();

        if (!$kamar) {
            return redirect('/admin/kamar')->with('error', 'Data kamar tidak ditemukan.');
        }

        DB::table('kamar')->where('id', $id)->update([
            'ruangan_id' => $request->ruangan_id,
            'nomor_kamar' => $request->nomor_kamar,
            'updated_at' => now(),
        ]);

        return redirect('/admin/kamar')->with('success', 'Data kamar berhasil diperbarui.');
    }

    public function delete($id)
    {
        // Pastikan data kamar dengan id tersebut ada
        $kamar = DB::table('kamar')->where('id', $id)->first();

        if (!$kamar) {
            return redirect('/admin/kamar')->with('error', 'Data kamar tidak ditemukan!');
        }

        // Hanya hapus data dari tabel kamar, tanpa mempengaruhi tabel lain
        DB::table('kamar')->where('id', $id)->delete();

        return redirect('/admin/kamar')->with('error', 'Data kamar berhasil dihapus!');
    }





}
