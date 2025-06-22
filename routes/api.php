<?php

use App\Models\Ruangan;
use App\Models\PanicLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PanicButtonController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Route::post('/panic-button', function (Request $request) {
//     $validated = $request->validate([
//         'KodeRuangan' => 'required|string'
//     ]);

//     $ruangan = Ruangan::where('kode', $validated['KodeRuangan'])->first();

//     if (!$ruangan) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Kode ruangan tidak ditemukan'
//         ], 404);
//     }

//     $log = PanicLog::create([
//         'kode_ruangan' => $ruangan->kode,
//         'nama_ruangan' => $ruangan->nama,
//         'kamar' => $ruangan->kamar
//     ]);

//     return response()->json([
//         'success' => true,
//         'message' => 'Panic button berhasil dikirim',
//         'data' => $log
//     ]);
// });

Route::get('/panic-button', [PanicButtonController::class, 'store']);

Route::post('/panic-button', [PanicButtonController::class, 'store']);
Route::get('/panic-button/pending', [PanicButtonController::class, 'getPending']);
Route::post('/panic-button/{id}/selesai', [PanicButtonController::class, 'markAsSelesai']);
Route::post('/panic-button/update-status', [PanicButtonController::class, 'updateStatus']);

Route::get('/panic-logs/pending', [PanicButtonController::class, 'getPending']);
Route::put('/panic-logs/{kamar_id}/dismiss', [PanicButtonController::class, 'dismiss']);
