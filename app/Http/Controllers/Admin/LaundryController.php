<?php

namespace App\Http\Controllers\Admin;

use App\Models\Laundry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LaundryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function read()
    {
        $laundry = Laundry::with(['pasien.kamar', 'ruangan']) // tambahkan pasien.kamar
            ->orderBy('id', 'DESC')
            ->orderBy('tanggal', 'DESC')
            ->get();

        return view('admin.laundry.index', ['laundry' => $laundry]);
    }

    public function kirim($id)
{
    // Check if the user has admin level 2 access
    if (auth()->user()->level != 2) {
        return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengirim data ke petugas.');
    }

    // Retrieve the laundry data
    $laundry = DB::table('laundry')->where('id', $id)->first();

    // Check if the laundry data exists
    if (!$laundry) {
        return redirect()->back()->with('error', 'Data Laundry tidak ditemukan.');
    }

    // Check if 'keterangan' is not 0 (already sent or processed)
    if ($laundry->keterangan != 0) {
        return redirect()->back()->with('error', 'Data laundry sudah dikirim atau sedang diproses, tidak dapat diubah lagi.');
    }

    // Update the 'keterangan' status to 1, indicating it is being processed
    DB::table('laundry')->where('id', $id)->update(['keterangan' => '1']); // '1' = Sedang Dikerjakan Oleh Petugas

    return redirect('/admin/laundry')->with('success', 'Data laundry berhasil dikirim.');
}

}
