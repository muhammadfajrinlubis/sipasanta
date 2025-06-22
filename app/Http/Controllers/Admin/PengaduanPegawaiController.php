<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Events\PengaduanDiperbarui;
use App\Http\Controllers\Controller;

class PengaduanPegawaiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function read(){
        // Mengambil data pengaduan beserta relasi 'ruangan', 'sarana', 'userPengadu', dan 'userPetugas', diurutkan berdasarkan 'created_at' terbaru
         $pengaduan = Pengaduan::with(['ruangan', 'sarana', 'riwayat', 'userPengadu', 'userPetugas'])
            ->orderBy('created_at', 'DESC') // Urutkan berdasarkan waktu pembuatan terbaru
             ->get();

            // Mengambil data petugas dan diurutkan berdasarkan 'id' terbaru
            $petugas = DB::table('petugas')->orderBy('id', 'DESC')->get();

            return view('admin.pengaduan.index', compact('pengaduan', 'petugas'));
        }


    public function detail($id) {
        $pengaduan = Pengaduan::findOrFail($id); // Use findOrFail to handle not found exception
        return view('admin.pengaduan.detail', compact('pengaduan'));
        }

    public function edit($id)
    {
        $pengaduan = Pengaduan::find($id);
        if (!$pengaduan) {
            return redirect('/admin/pengaduan')->with('error', 'Data tidak ditemukan!');
        }
        return view('admin.pengaduan.edit', compact('pengaduan'));
    }

  public function updatepetugas(Request $request, $id) {
    // Validasi input petugas yang dipilih, harus berupa array
    $request->validate([
        'petugas_id' => 'required|array|min:1', // Minimal harus ada satu petugas yang dipilih
    ]);

    // Ambil semua id_petugas yang dipilih dari form checkbox
    $id_petugas = $request->petugas_id;

    // Ambil objek pengaduan dari database
    $pengaduan = DB::table('pengaduan')->where('id', $id)->first();

    // Mengubah data pengaduan dan menghubungkannya dengan lebih dari satu petugas
    DB::table('pengaduan')->where('id', $id)->update([
        'id_petugas' => implode(',', $id_petugas), // Simpan id petugas dalam bentuk string yang dipisahkan dengan koma
        'status' => 'Dikerjakan Oleh Petugas', // Update status pengaduan
    ]);

    // Simpan status dan id_pengaduan ke tabel 'riwayat'
    DB::table('riwayat')->insert([
        'id_pengaduan' => $id, // ID pengaduan yang diupdate
        'tanggal' => Carbon::now('Asia/Jakarta'), // Tanggal saat status diubah
        'created_at' => Carbon::now('Asia/Jakarta'),
        'updated_at' => Carbon::now('Asia/Jakarta'),
    ]);

    // Kirim notifikasi real-time ke tiap petugas yang dipilih
    foreach ($id_petugas as $petugasId) {
        event(new PengaduanDiperbarui($petugasId, $pengaduan)); // Kirim objek pengaduan
    }

    return redirect('/admin/pengaduan')->with('success', 'Data pengaduan berhasil diperbarui dan status telah disimpan ke riwayat');
}


    public function tolak(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'required|string|max:500',
        ]);

        $pengaduan = Pengaduan::findOrFail($id);
        $pengaduan->status = 'Ditolak'; // Gunakan 'Ditolak' atau tetap 'tidak_dapat_dikerjakan' sesuai kebutuhanmu
        $pengaduan->alasan = $request->alasan;
        $pengaduan->save();

        return redirect('/admin/pengaduan')->with('success', 'Pengaduan berhasil ditolak dengan alasan.');
    }





}
