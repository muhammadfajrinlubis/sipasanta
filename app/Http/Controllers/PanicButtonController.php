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



public function store(Request $request)
{
    $nomorKamar = $request->input('nomor_kamar');

    // Cari kamar
    $kamar = Kamar::with('ruangan')->where('nomor_kamar', $nomorKamar)->first();

    if (!$kamar) {
        return response()->json(['message' => 'Kamar tidak ditemukan'], 404);
    }

    // Cek apakah ada pasien dengan status 'rawat' di kamar tersebut
    $pasien = Pasien::with('kamar.ruangan')
        ->where('kamar_id', $kamar->id)
        ->where('status', 'rawat')
        ->first();

    if (!$pasien) {
        return response()->json(['message' => 'Tidak ada pasien dirawat di kamar ini'], 200);
        // 200 agar ESP tidak dianggap error, tapi tidak simpan data
    }

    // Simpan panic log baru karena ada pasien yang dirawat
    $panicLog = PanicLog::create([
        'kamar_id' => $kamar->id,
        'status' => 'alarm_aktif',
    ]);

    // Broadcast event
    broadcast(new PanicLogCreated($pasien))->toOthers();

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

    public function updateStatus(Request $request)
{
    $kamarId = $request->input('kamar_id');

    $kamar = Kamar::find($kamarId);
    if (!$kamar) {
        return response()->json(['message' => 'Kamar tidak ditemukan'], 404);
    }

    $latestLog = $kamar->panicLogs()->latest()->first();
    if (!$latestLog) {
        return response()->json(['message' => 'Panic log tidak ditemukan'], 404);
    }

    $latestLog->status = 0; // anggap 0 = belum_ditangani
    $latestLog->save();

    return response()->json(['message' => 'Status panic log berhasil diubah menjadi belum_ditangani']);
}




public function getPending()
    {
        $panicLogs = DB::table('panic_logs')
            ->join('kamar', 'panic_logs.kamar_id', '=', 'kamar.id')
            ->leftJoin('ruangan', 'kamar.ruangan_id', '=', 'ruangan.id')
            ->leftJoin('pasien', 'pasien.kamar_id', '=', 'kamar.id') // sesuaikan jika relasi pasien ≠ 1:1
            ->where('panic_logs.status', 'alarm_aktif')
            ->select(
                'panic_logs.*',
                'kamar.nomor_kamar',
                'kamar.id as kamar_id',
                'ruangan.nama as nama_ruangan',
                'pasien.nama as pasien_nama',
                'pasien.kendala as pasien_kendala'
            )
            ->get();

        return response()->json(['data' => $panicLogs]);
    }

public function dismiss($kamar_id)
    {
        $logs = PanicLog::where('kamar_id', $kamar_id)
            ->where('status', 'alarm_aktif')
            ->get();

        if ($logs->isEmpty()) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        foreach ($logs as $log) {
            $log->update(['status' => 'belum_ditangani']);
        }

        return response()->json(['message' => 'Semua alarm_aktif berhasil diubah menjadi belum_ditangani']);
    }




}
