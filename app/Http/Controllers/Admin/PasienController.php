<?php

namespace App\Http\Controllers\Admin;


use App\Models\Kamar;
use App\Models\Pasien;
use App\Models\Laundry;
use App\Models\Ruangan;
use App\Models\PanicLog;
use Illuminate\Http\Request;
use App\Models\LaundryRequest;
use App\Events\LaundryRequested;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PasienController extends Controller
{

     public function read()
    {
        $pasien = Pasien::with(['kamar.ruangan'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pasien.index', compact('pasien'));
    }


    public function add()
    {
        $ruangan = Ruangan::all();
        $kamar = Kamar::all();
        return view('admin.pasien.tambah', compact('ruangan', 'kamar'));
    }

    public function getKamar($ruangan_id)
    {
        // Ambil semua kamar di ruangan tersebut
        $kamar = Kamar::where('ruangan_id', $ruangan_id)
            ->whereDoesntHave('pasien', function($query) {
                $query->where('status', 'rawat');
            })
            ->get();

        return response()->json($kamar);
    }

    public function getKamarEdit($ruangan_id, $pasien_id = null)
    {
        $kamar = Kamar::where('ruangan_id', $ruangan_id)
            ->where(function ($query) use ($pasien_id) {
                $query->whereDoesntHave('pasien', function ($subQuery) use ($pasien_id) {
                    $subQuery->where('status', 'rawat');

                    // Kecualikan kamar yang dipakai pasien saat ini
                    if ($pasien_id) {
                        $subQuery->where('id', '!=', $pasien_id);
                    }
                });
            })
            ->orWhere(function ($query) use ($ruangan_id, $pasien_id) {
                $query->where('ruangan_id', $ruangan_id)
                    ->whereHas('pasien', function ($subQuery) use ($pasien_id) {
                        if ($pasien_id) {
                            $subQuery->where('id', $pasien_id);
                        }
                    });
            })
            ->get();

        return response()->json($kamar);
    }

  public function create(Request $request)
{
    $request->validate([
        // 'no_rm' tidak perlu divalidasi karena akan dibuat otomatis
        'nama'          => 'required|string|max:255',
        'jenis_kelamin' => 'required|in:L,P',
        'tanggal_lahir' => 'required|date',
        'alamat'        => 'required|string',
        'no_telepon'    => 'required|string|max:15',
        'kamar_id'      => 'required|exists:kamar,id',
        'status'        => 'required|in:rawat,pulang',
    ]);

    // Cek apakah kamar tersebut sedang digunakan oleh pasien rawat
    $pasienRawat = Pasien::where('kamar_id', $request->kamar_id)
                        ->where('status', 'rawat')
                        ->first();

    if ($pasienRawat) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Tidak bisa menambahkan pasien ke kamar ini karena masih ada pasien yang dirawat di kamar tersebut.');
    }

    // Buat no_rm otomatis (misalnya: 20250001, 20250002, dst)
    $tahun = date('Y');
    $lastNoRm = Pasien::where('no_rm', 'like', $tahun . '%')->max('no_rm');

    if ($lastNoRm) {
        $lastNumber = (int)substr($lastNoRm, 4); // Ambil bagian belakang nomor
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }

    $no_rm = $tahun . str_pad($newNumber, 4, '0', STR_PAD_LEFT); // Format: 20250001

    // Simpan data pasien
    Pasien::create([
        'no_rm'         => $no_rm,
        'nama'          => $request->nama,
        'jenis_kelamin' => $request->jenis_kelamin,
        'tanggal_lahir' => $request->tanggal_lahir,
        'alamat'        => $request->alamat,
        'no_telepon'    => $request->no_telepon,
        'kamar_id'      => $request->kamar_id,
        'kendala'       => $request->kendala,
        'status'        => $request->status,
    ]);

    return redirect('/admin/pasien')->with('success', 'Data Pasien Berhasil Disimpan.');
}
    public function edit($id)
    {
        $pasien = Pasien::findOrFail($id);
        $ruangan = Ruangan::all();

        return view('admin.pasien.edit', compact('pasien', 'ruangan'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        // no_rm unik, tapi tidak usah dipaksa di update kalau memang otomatis
        'nama' => 'required|string|max:255',
        'jenis_kelamin' => 'required|in:L,P',
        'tanggal_lahir' => 'required|date',
        'alamat' => 'required|string',
        'no_telepon' => 'nullable|string|max:15',
        'kamar_id' => 'required|exists:kamar,id',
        'kendala' => 'nullable|string',
        'status' => 'required|in:rawat,pulang',
    ]);

    $pasien = Pasien::findOrFail($id);

    $pasien->update([
        'nama'          => $request->nama,
        'jenis_kelamin' => $request->jenis_kelamin,
        'tanggal_lahir' => $request->tanggal_lahir,
        'alamat'        => $request->alamat,
        'no_telepon'    => $request->no_telepon,
        'kamar_id'      => $request->kamar_id,
        'kendala'       => $request->kendala,
        'status'        => $request->status,
    ]);

    return redirect('/admin/pasien')->with('success', 'Data Pasien berhasil diupdate.');
}

        public function updateStatus(Request $request, $id)
    {
        $pasien = DB::table('pasien')->where('id', $id)->first();
        $statusBaru = $request->input('status');

        if (!in_array($statusBaru, ['rawat', 'pulang'])) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        if ($statusBaru === 'rawat') {
            $kamarTerisi = DB::table('pasien')
                ->where('kamar_id', $pasien->kamar_id)
                ->where('status', 'rawat')
                ->where('id', '!=', $pasien->id)
                ->exists();

            if ($kamarTerisi) {
                return redirect()->back()->with('error', 'Kamar Sudah Ditempati Pasien Lain Yang Sedang Dirawat. Silakan Pindahkan Pasien Ke Kamar Lain Terlebih Dahulu.');
            }
        }

        DB::table('pasien')
            ->where('id', $id)
            ->update(['status' => $statusBaru]);

        return redirect('/admin/pasien')->with('success', 'Status Pasien Berhasil Diubah Menjadi ' . $statusBaru . '.');
    }



    public function delete($id)
    {
        $pasien = Pasien::findOrFail($id); // otomatis 404 jika tidak ketemu
        $pasien->delete();

        return redirect('admin/pasien')->with("error", "Data Berhasil Dihapus!");
    }

    public function detail($id)
    {
        $pasien = Pasien::with(['ruangan', 'kamar'])->findOrFail($id);
        return view('admin.pasien.detail', compact('pasien'));
    }

    public function showPublic($id)
    {
        $pasien = Pasien::findOrFail($id);

        // Ambil riwayat laundry berdasarkan pasien_id
        $riwayatLaundry = Laundry::where('pasien_id', $id)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('admin.pasien.show-public', compact('pasien', 'riwayatLaundry'));
    }


    public function laundryRequest(Request $request)
{
    // Validasi input
    $request->validate([
        'pasien_id' => 'required|exists:pasien,id',

    ]);

    // Ambil pasien beserta kamar & ruangan
    $pasien = Pasien::with('kamar.ruangan')->findOrFail($request->pasien_id);

    // Buat permintaan laundry
    $laundry = Laundry::create([
        'tanggal'    => now()->toDateString(),
        'pasien_id'  => $pasien->id,        // HARUS ADA
        'nomr'       => $pasien->no_rm,
        'keterangan' => 0, // bisa kosong
    ]);

    // Load relasi pasien untuk event
    $laundry->load('pasien.kamar.ruangan');

    // Trigger event
    event(new LaundryRequested($laundry));

    return redirect()->back()->with('success', 'Permintaan laundry berhasil dikirim.');
}



    public function panicButton(Request $request)
    {
        // Ambil input dari form
        $tanggal = $request->input('tanggal');
        $showAll = $request->has('show_all'); // Cek apakah checkbox di-check

        // Jika tidak ada tanggal dan show_all tidak aktif, set default ke hari ini
        if (!$tanggal && !$showAll) {
            $tanggal = now()->toDateString();
        }

        // Query data pasien + relasi panicLogs yang difilter jika perlu
        $pasien = Pasien::with([
            'ruangan',
            'kamar.panicLogs' => function ($query) use ($tanggal, $showAll) {
                if (!$showAll && $tanggal) {
                    $query->whereDate('created_at', $tanggal);
                }
            }
        ])
        ->when(!$showAll && $tanggal, function ($query) use ($tanggal) {
            // Jika filter tanggal aktif
            return $query->whereHas('kamar.panicLogs', function ($q) use ($tanggal) {
                $q->whereDate('created_at', $tanggal);
            });
        }, function ($query) {
            // Kalau show_all aktif, ambil semua pasien yang punya panicLogs
            return $query->whereHas('kamar.panicLogs');
        })
        ->get();

        // Kirim juga nilai showAll ke view agar bisa digunakan kembali
        return view('admin.pasien.panic-button', compact('pasien', 'tanggal', 'showAll'));
    }




    public function getQrData($id)
    {
        $pasien = Pasien::findOrFail($id);

        $qrData = route('admin.pasien.index', $pasien->id);
        $qrImage = base64_encode(QrCode::format('png')->size(200)->generate($qrData));
        $qrImageSrc = 'data:image/png;base64,' . $qrImage;

        return response()->json([
            'nama' => $pasien->nama,
            'no_rm' => $pasien->no_rm,
            'qr' => $qrImageSrc,
        ]);
    }

    public function updateStatusPanicLog(Request $request, $id)
{
    // Validasi request
    $request->validate([
        'status' => 'required|in:belum_ditangani,diproses,selesai',
    ]);

    // Ambil data panic log berdasarkan ID
    $panicLog = PanicLog::findOrFail($id);

    $updated = 0;
    $newStatus = null;

    // Cek status saat ini, lalu update yang sesuai
    if ($panicLog->status === 'belum_ditangani') {
        $newStatus = $request->status;
        $updated = PanicLog::where('kamar_id', $panicLog->kamar_id)
            ->where('status', 'belum_ditangani')
            ->update(['status' => $newStatus]);
    } elseif ($panicLog->status === 'diproses') {
        $newStatus = 'selesai';
        $updated = PanicLog::where('kamar_id', $panicLog->kamar_id)
            ->where('status', 'diproses')
            ->update(['status' => $newStatus]);
    }

    if ($updated > 0 && $newStatus !== null) {
        // Simpan riwayat status ke panic_log_histories untuk semua panic_log yang diupdate
        $updatedPanicLogs = PanicLog::where('kamar_id', $panicLog->kamar_id)
            ->where('status', $newStatus)
            ->get();

        foreach ($updatedPanicLogs as $log) {
            DB::table('panic_log_histories')->insert([
                'panic_log_id' => $log->id,
                'status' => $newStatus,
                'changed_at' => now(),
                'changed_by' => auth()->id(), // bisa null jika guest
            ]);
        }

        return redirect()->back()->with('success', 'Status panic log berhasil diperbarui dan riwayat dicatat.');
    } else {
        return redirect()->back()->with('error', 'Tidak ada data yang berhasil diperbarui.');
    }
}



}
