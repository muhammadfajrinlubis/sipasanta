<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Laundry;
use Illuminate\Http\Request;
use App\Events\LaundryDikirim;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LaundryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function read(Request $request)
    {
        // Ambil tanggal dari request, default hari ini jika tidak ada
        $tanggal = $request->input('tanggal', Carbon::today()->toDateString());

        // Buat query awal dengan relasi pasien dan kamar -> ruangan
        $query = Laundry::with(['pasien.kamar.ruangan'])->orderByDesc('id');

        // Jika checkbox show_all TIDAK dicentang, filter berdasarkan tanggal
        if (!$request->has('show_all')) {
            $query->whereDate('tanggal', $tanggal);
        }

        // Eksekusi query
        $laundry = $query->get();

        // Kirim data ke view
        return view('admin.laundry.index', [
            'laundry' => $laundry,
            'selectedDate' => $tanggal,
            'showAll' => $request->has('show_all')
        ]);
    }

   public function kirim($id)
    {
        if (auth()->user()->level != 2) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengirim data ke petugas.');
        }

        // Eloquent: Ambil laundry beserta relasi ruangan
        $laundry = \App\Models\Laundry::with('ruangan')->find($id);

        if (!$laundry) {
            return redirect()->back()->with('error', 'Data Laundry tidak ditemukan.');
        }

        if ($laundry->keterangan != 0) {
            return redirect()->back()->with('error', 'Data laundry sudah dikirim atau sedang diproses.');
        }

        $laundry->keterangan = 1;
        $laundry->save();

        // Kirim notifikasi event realtime
        event(new LaundryDikirim($laundry));

        return redirect('/admin/laundry')->with('success', 'Data laundry berhasil dikirim.');
    }

}
