<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Pusher\Pusher;
use App\Models\Kamar;
use App\Models\Pasien;
use App\Models\Ruangan;
use App\Models\PanicLog;
use Illuminate\Http\Request;
use App\Events\PanicLogCreated;
use Illuminate\Support\Facades\DB;

class PanicButtonController extends Controller
{
    // public function store(Request $request)
    // {
    //     // Validate the incoming request to ensure the 'ruangan' field is present
    //     $request->validate([
    //         'ruangan' => 'required|string',
    //     ]);

    //     // Create a new PanicLog entry and save the data
    //     $panic = new PanicLog();
    //     $panic->ruangan = $request->ruangan;
    //     $panic->save();

    //     // Setup Pusher for broadcasting the event
    //     $pusher = new Pusher(
    //         env('PUSHER_APP_KEY'),
    //         env('PUSHER_APP_SECRET'),
    //         env('PUSHER_APP_ID'),
    //         [
    //             'cluster' => env('PUSHER_APP_CLUSTER'),
    //             'useTLS' => true
    //         ]
    //     );

    //     // Trigger the 'panic-event' on the 'panic-channel' for real-time updates
    //     $pusher->trigger('panic-channel', 'panic-event', [
    //         'ruangan' => $panic->ruangan, // Sending the room name as part of the event data
    //     ]);

    //     // Return a success response
    //     return response()->json(['message' => 'Panic button pressed!'], 200);
    // }

    public function show($kode)
    {
        $ruangan = Ruangan::where('kode', $kode)->firstOrFail();
        $pasiens = $ruangan->pasiens;

        // Ambil panic logs dari ruangan ini (misal 10 log terbaru)
        $panicLogs = $ruangan->panicLogs()->latest()->take(10)->get();

        return view('layouts.show', compact('ruangan', 'pasiens', 'panicLogs'));
    }



 // Simpan panic log
    public function store(Request $request)
    {
        $nomorKamar = $request->input('nomor_kamar');

        // Cari kamar
        $kamar = Kamar::with('ruangan')->where('nomor_kamar', $nomorKamar)->first();

        if (!$kamar) {
            return response()->json(['message' => 'Kamar tidak ditemukan'], 404);
        }

        // Simpan panic log baru
        $panicLog = PanicLog::create([
            'kamar_id' => $kamar->id,
            'status' => 'belum_ditangani',
        ]);

        // Ambil pasien yang aktif di kamar tersebut
        $pasien = Pasien::with('kamar.ruangan')
            ->where('kamar_id', $kamar->id)
            ->where('status', 'rawat')
            ->first();

        // Broadcast event jika dashboard terbuka
        if ($pasien) {
            broadcast(new PanicLogCreated($pasien))->toOthers();
        }

        return response()->json([
            'message' => 'Panic log berhasil disimpan',
            'data' => [
                'pasien' => $pasien,
                'kamar' => $kamar,
                'ruangan' => $kamar->ruangan,
                'status' => $panicLog->status,
                'created_at' => Carbon::parse($panicLog->created_at)
                    ->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            ]
        ]);
    }




// public function getPending()
// {
//     $panicLogs = DB::table('panic_logs')
//         ->join('kamar', 'panic_logs.kamar_id', '=', 'kamar.id')
//         ->leftJoin('ruangan', 'kamar.ruangan_id', '=', 'ruangan.id')
//         ->leftJoin('pasien', 'pasien.kamar_id', '=', 'kamar.id') // sesuaikan jika relasi pasien ≠ 1:1
//         ->where('panic_logs.status', 'belum_ditangani')
//         ->select(
//             'panic_logs.*',
//             'kamar.nomor_kamar',
//             'kamar.id as kamar_id',
//             'ruangan.nama as nama_ruangan',
//             'pasien.nama as pasien_nama',
//             'pasien.kendala as pasien_kendala'
//         )
//         ->get();

//     return response()->json(['data' => $panicLogs]);
// }





}
