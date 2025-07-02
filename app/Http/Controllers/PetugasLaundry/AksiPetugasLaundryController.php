<?php

namespace App\Http\Controllers\PetugasLaundry;

use Carbon\Carbon;
use App\Models\Laundry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AksiPetugasLaundryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

   public function read(Request $request)
    {
        // Ambil tanggal dari request, default hari ini jika tidak ada
        $tanggal = $request->input('tanggal', Carbon::today()->toDateString());

        // Buat query awal
        $query = Laundry::with(['pasien.kamar', 'ruangan'])->orderByDesc('id');

        // Jika checkbox show_all TIDAK dicentang, filter berdasarkan tanggal
        if (!$request->has('show_all')) {
            $query->whereDate('tanggal', $tanggal);
        }

        // Eksekusi query
        $laundry = $query->get();

        // Kirim data ke view
        return view('petugaslaundry.laundry.index', [
            'laundry' => $laundry,
            'selectedDate' => $tanggal,
            'showAll' => $request->has('show_all')
        ]);
    }

    public function jemput($id)
    {
        // Check if the user has admin level 5 access
        if (auth()->user()->level != 5) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengirim data ke petugas.');
        }

        // Retrieve the laundry data
        $laundry = DB::table('laundry')->where('id', $id)->first();

        // Check if the laundry data exists
        if (!$laundry) {
            return redirect()->back()->with('error', 'Data Laundry tidak ditemukan.');
        }

        // Check if 'keterangan' is not 0 (already sent or processed)
        if ($laundry->keterangan >= 3) {
            return redirect()->back()->with('error', 'Data laundry sudah dikirim atau sedang diproses, tidak dapat diubah lagi.');
        }

        // Update the 'keterangan' status to 2, indicating it is being processed
        DB::table('laundry')->where('id', $id)->update(['keterangan' => 2]);

        return redirect('/petugaslaundry/laundry')->with('success', 'Data laundry berhasil dijemput.');
    }

    public function pickup(Request $request, $id)
    {
        $request->validate([
            'berat' => 'required|numeric|min:0',
            'biaya' => 'required|numeric|min:0',
            'siap_pada' => 'required|integer|min:1|max:30', // jumlah hari
        ]);

        // Ambil data laundry berdasarkan ID
        $laundry = DB::table('laundry')->where('id', $id)->first();

        // Cek apakah keterangan lebih dari 3
        if ($laundry->keterangan > 3) {
            return redirect('/petugaslaundry/laundry')->with('error', 'Data tidak dapat diubah karena status telah melewati batas.');
        }

        // Hitung waktu selesai dari sekarang + jumlah hari
        $tanggalSelesai = now()->addDays($request->siap_pada);

        // Update data laundry
        DB::table('laundry')
            ->where('id', $id)
            ->update([
                'berat' => $request->berat,
                'biaya' => $request->biaya,
                'siap_pada' => $tanggalSelesai,
                'keterangan' => '3',
            ]);

        return redirect('/petugaslaundry/laundry')->with('success', 'Data laundry berhasil diimputkan');
    }



    public function selesai($id)
    {
        // Check if the user has admin level 5 access
        if (auth()->user()->level != 5) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengirim data ke petugas.');
        }

        // Retrieve the laundry data
        $laundry = DB::table('laundry')->where('id', $id)->first();

        // Check if the laundry data exists
        if (!$laundry) {
            return redirect()->back()->with('error', 'Data Laundry tidak ditemukan.');
        }

        // Check if 'keterangan' is not 0 (already sent or processed)
        if ($laundry->keterangan >= 4) {
            return redirect()->back()->with('error', 'Data laundry sudah dikirim atau sedang diproses, tidak dapat diubah lagi.');
        }

        // Update the 'keterangan' status to 2, indicating it is being processed
        DB::table('laundry')->where('id', $id)->update(['keterangan' => 4]);

        return redirect('/petugaslaundry/laundry')->with('success', 'Data laundry berhasil diselesaikan.');
    }

    public function diantar($id)
    {
        // Check if the user has admin level 5 access
        if (auth()->user()->level != 5) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengirim data ke petugas.');
        }

        // Retrieve the laundry data
        $laundry = DB::table('laundry')->where('id', $id)->first();

        // Check if the laundry data exists
        if (!$laundry) {
            return redirect()->back()->with('error', 'Data Laundry tidak ditemukan.');
        }

        // Check if 'keterangan' is not 0 (already sent or processed)
        if ($laundry->keterangan >= 5) {
            return redirect()->back()->with('error', 'Data laundry sudah dikirim atau sedang diproses, tidak dapat diubah lagi.');
        }

        // Update the 'keterangan' status to 2, indicating it is being processed
        DB::table('laundry')->where('id', $id)->update(['keterangan' => 5]);

        return redirect('/petugaslaundry/laundry')->with('success', 'Data laundry berhasil diantar.');
    }
}
