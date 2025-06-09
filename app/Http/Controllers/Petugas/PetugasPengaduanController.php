<?php

namespace App\Http\Controllers\Petugas;

use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PetugasPengaduanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Menampilkan list pengaduan oleh petugas yang login
    public function read()
    {
        // Ambil data pengaduan yang dibuat oleh user yang login dan memuat data petugas
        $pengaduan = Pengaduan::with(['ruangan', 'sarana', 'userPengadu', 'riwayat', 'userPetugas'])
            ->whereRaw("FIND_IN_SET(?, id_petugas)", [auth()->user()->id]) // Cari pengaduan di mana ID petugas login ada dalam id_petugas
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('petugas.pengaduan.index', compact('pengaduan'));
    }


    // Menampilkan detail pengaduan
    public function detail($id)
    {
        $pengaduan = Pengaduan::with(['ruangan', 'sarana', 'userPengadu', 'userPetugas'])
            ->findOrFail($id);

        return view('petugas.pengaduan.detail', compact('pengaduan'));
    }

    // Menampilkan form aksi pada pengaduan
    public function aksi($id)
    {
        $pengaduan = Pengaduan::with('userPetugas')->find($id);

        if (!$pengaduan) {
            return redirect('/petugas/pengaduan')->with('error', 'Data tidak ditemukan!');
        }

        return view('petugas.pengaduan.aksi', compact('pengaduan'));
    }

    public function aksiPetugas(Request $request, $id)
{
    // Validasi form
    $request->validate([
        'status' => 'required|string',  // Status harus ada dan berupa string
        'tgl_pukul_selesai' => 'required_if:status,selesai|nullable|date',  // Validasi jika status 'selesai'
        'alasan' => 'required_if:status,tidak_dapat_dikerjakan|nullable|string',  // Validasi jika status 'tidak_dapat_dikerjakan'
        'bukti_petugas' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',  // Validasi file bukti
    ]);

    try {
        // Cari pengaduan berdasarkan ID
        $pengaduan = Pengaduan::findOrFail($id);  // Menggunakan findOrFail untuk otomatis melempar error jika tidak ditemukan

        // Cek apakah pengaduan sudah selesai
        if ($pengaduan->status == 'selesai') {
            return redirect()->back()->with('error', 'Pengaduan ini sudah selesai dan tidak dapat diubah lagi.');
        }

        // Update data pengaduan berdasarkan status
        if ($request->status == 'selesai') {
            $pengaduan->tgl_pukul_selesai = $request->tgl_pukul_selesai;
        } elseif ($request->status == 'tidak_dapat_dikerjakan') {
            $pengaduan->alasan = $request->alasan;
        }

        // Simpan file bukti petugas jika ada
        if ($request->hasFile('bukti_petugas')) {
            $foto = time() . '_' . $request->file('bukti_petugas')->getClientOriginalName();
            $request->file('bukti_petugas')->move(public_path('images'), $foto);
            $pengaduan->bukti_petugas = $foto;
        }

        // Update status pengaduan
        $pengaduan->status = $request->status;

        // Simpan perubahan
        $pengaduan->save();

        // Redirect ke halaman pengaduan dengan pesan sukses
        return redirect('/petugas/pengaduan')->with('success', 'Data pengaduan berhasil diperbarui.');
    } catch (\Exception $e) {
        // Tangani jika terjadi error
        return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data pengaduan: ' . $e->getMessage());
    }
}


}
