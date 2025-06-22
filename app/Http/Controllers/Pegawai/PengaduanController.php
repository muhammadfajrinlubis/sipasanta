<?php

namespace App\Http\Controllers\Pegawai;

use App\Models\Users;
use App\Models\Sarana;
use App\Models\Ruangan;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

use App\Events\PengaduanCreated;
use App\Events\NewPengaduanEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengaduanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function read()
    {
        // Ambil data pengaduan yang hanya dibuat oleh user yang sedang login
        $pengaduan = Pengaduan::with(['ruangan', 'sarana', 'userPengadu', 'userPetugas'])
            ->where('id_user', auth()->user()->id) // Hanya pengaduan dari user yang login
            ->orderBy('created_at', 'DESC') // Urutkan berdasarkan waktu pembuatan terbaru
                            ->get();
        return view('pegawai.pengaduan.index', compact('pengaduan'));
    }

    public function add(){
        $ruangan = DB::table('ruangan')->orderBy('id','DESC')->get();
        $sarana = DB::table('sarana')->orderBy('id','DESC')->get();
        return view('pegawai.pengaduan.tambah',['ruangan'=>$ruangan,'sarana'=>$sarana]);
    }

    public function create(Request $request)
    {
        // Validasi data input
        $request->validate([
            'id_ruangan' => 'required',
            'id_sarana' => 'required',
            'tgl_pengaduan' => 'required|date',
            'deskripsi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'tipe' => 'required',
        ]);

        // Simpan file foto jika ada
        if ($request->hasFile('foto')) {
            // Tentukan nama file dan simpan ke folder public/images
            $foto = time() . '_' . $request->file('foto')->getClientOriginalName();
            $request->file('foto')->move(public_path('images'), $foto);
        } else {
            $foto = null;
        }

        // Data yang akan disimpan
        $data = [
            'id_ruangan' => $request->id_ruangan,
            'id_sarana' => $request->id_sarana,
            'tgl_pengaduan' => $request->tgl_pengaduan,
            'deskripsi' => $request->deskripsi,
            'foto' => $foto,
            'id_petugas' => null, // Tidak menggunakan id_petugas
            'id_user' => auth()->user()->id, // User yang login
            'status' => 'Menunggu Persetujuan Oleh Admin', // Status awal
            'tipe' => $request->tipe,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Insert data ke database dan ambil ID-nya
        $id = DB::table('pengaduan')->insertGetId($data);

        // Ambil data lengkap yang baru disimpan
        $pengaduan = DB::table('pengaduan')->where('id', $id)->first();

        // Trigger event
        event(new PengaduanCreated($pengaduan));

        return redirect('/pegawai/pengaduan')->with('success', 'Data pengaduan berhasil ditambahkan');
    }

public function detail($id)
{
    $pengaduan = Pengaduan::with(['ruangan', 'sarana', 'userPengadu', 'userPetugas', 'rating'])->find($id);
    return view('pegawai.pengaduan.detail', compact('pengaduan'));
}



    public function delete($id)
    {
        $pengaduan = Pengaduan::findOrFail($id);

        // Cek jika status pengaduan sudah 'Selesai', 'Sedang Diproses', atau 'Tidak Dapat Dikerjakan'
        if (in_array($pengaduan->status, ['selesai', 'Dikerjakan Oleh Petugas', 'tidak_dapat_dikerjakan'])) {
            return redirect('/pegawai/pengaduan')->with('error', 'Data pengaduan tidak dapat dihapus karena sudah dalam status Selesai atau Sedang Diproses.');
        }

        $pengaduan->delete();
        return redirect('/pegawai/pengaduan')->with('error', 'Data pengaduan berhasil dihapus');
    }


    public function edit($id){
        $pengaduan = Pengaduan::findOrFail($id);

        // Cek jika status pengaduan sudah 'Selesai', 'Sedang Diproses', atau 'Tidak Dapat Dikerjakan'
        if (in_array($pengaduan->status, ['selesai', 'Dikerjakan Oleh Petugas', 'tidak_dapat_dikerjakan'])) {
            return redirect('/pegawai/pengaduan')->with('error', 'Data pengaduan tidak dapat diubah karena sudah dalam status Selesai atau Sedang Diproses.');
        }

        $ruangan = Ruangan::all();
        $sarana = Sarana::all();
        return view('pegawai.pengaduan.edit', compact('pengaduan', 'ruangan', 'sarana'));
    }

    public function update(Request $request, $id){
        $pengaduan = Pengaduan::findOrFail($id);

        // Cek jika status pengaduan sudah 'Selesai', 'Sedang Diproses', atau 'Tidak Dapat Dikerjakan'
        if (in_array($pengaduan->status, ['selesai', 'Dikerjakan Oleh Petugas', 'tidak_dapat_dikerjakan'])) {
            return redirect('/pegawai/pengaduan')->with('error', 'Data pengaduan tidak dapat diubah karena sudah dalam status Selesai atau Sedang Diproses.');
        }

        $pengaduan->id_ruangan = $request->id_ruangan;
        $pengaduan->id_sarana = $request->id_sarana;
        $pengaduan->tgl_pengaduan = $request->tgl_pengaduan;
        $pengaduan->tipe = $request->tipe;
        $pengaduan->deskripsi = $request->deskripsi;
        $pengaduan->status = 'Menunggu Persetujuan Oleh Admin';

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $fileName);
            $pengaduan->foto = $fileName;
        }

        $pengaduan->save();

        return redirect('/pegawai/pengaduan')->with('success', 'Data pengaduan berhasil diperbarui');
    }

    public function store(Request $request, $pengaduanId){
    // Validasi input dari request
    $validated = $request->validate([
        'nilai_rating' => 'required|integer|min:1|max:5',
        'komentar' => 'nullable|string|max:1000',
    ]);

    // Ambil data pengaduan berdasarkan ID pengaduan
    $pengaduan = DB::table('pengaduan')->where('id', $pengaduanId)->first();

    // Periksa apakah pengaduan ditemukan dan memiliki id_petugas
    if (!$pengaduan || !$pengaduan->id_petugas) {
        return redirect()->back()->with('error', 'Pengaduan tidak ditemukan atau belum memiliki petugas.');
    }

    // Ambil ID petugas dari tabel pengaduan
    $petugasId = $pengaduan->id_petugas;

    // Masukkan rating ke dalam tabel rating_petugas menggunakan DB facade
    DB::table('rating_petugas')->insert([
        'id_pengaduan' => $pengaduanId,
        'id_petugas' => $petugasId,
        'nilai_rating' => $validated['nilai_rating'],
        'komentar' => $validated['komentar'],
        'created_at' => now(), // Set timestamp otomatis
        'updated_at' => now(), // Set timestamp otomatis
    ]);

    // Redirect kembali dengan pesan sukses
    return redirect()->back()->with('success', 'Rating berhasil dikirim!');
    }
}
