<?php

namespace App\Http\Controllers\Admin;


use App\Models\Kamar;
use App\Models\Pasien;
use App\Models\Laundry;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use App\Models\LaundryRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PasienController extends Controller
{

     public function read()
    {
        $pasien = Pasien::with(['ruangan', 'kamar'])
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

   public function create(Request $request)
   {
        $request->validate([
            'no_rm'         => 'required|unique:pasien,no_rm',
            'nama'          => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'alamat'        => 'required|string',
            'no_telepon'    => 'required|string|max:15',
            'ruangan_id'    => 'required|exists:ruangan,id',
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

        Pasien::create([
            'no_rm'         => $request->no_rm,
            'nama'          => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat'        => $request->alamat,
            'no_telepon'    => $request->no_telepon,
            'ruangan_id'    => $request->ruangan_id,
            'kamar_id'      => $request->kamar_id,
            'kendala'       => $request->kendala,
            'status'        => $request->status,
        ]);

        return redirect('/admin/pasien')->with('success', 'Data pasien berhasil disimpan.');
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
            'no_rm' => 'required|numeric',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'no_telepon' => 'required|numeric',
            'ruangan_id' => 'required|exists:ruangan,id',
            'kamar_id' => 'required|exists:kamar,id',
            'status' => 'required|in:rawat,pulang',
        ]);

        $pasien = Pasien::findOrFail($id);
        $pasien->update($request->all());

        return redirect('/admin/pasien')->with('success', 'Data pasien berhasil diupdate.');
    }

        public function updateStatus(Request $request, $id)
    {
        $pasien = Pasien::findOrFail($id);
        $statusBaru = $request->input('status');

        if (!in_array($statusBaru, ['rawat', 'pulang'])) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $pasien->status = $statusBaru;
        $pasien->save();

       return redirect('/admin/pasien')->with('success', 'Status pasien berhasil diubah menjadi ' . $statusBaru . '.');
    }


    public function delete($id){
        $pasien = DB::table('pasien')->where('id',$id)->first();
        DB::table('pasien')->where('id',$id)->delete();

        return redirect('admin/pasien')->with("error","Data Berhasil Hapus !");
    }

    public function detail($id)
    {
        $pasien = Pasien::with(['ruangan', 'kamar'])->findOrFail($id);
        return view('admin.pasien.detail', compact('pasien'));
    }
        public function showPublic($id)
        {
            $pasien = Pasien::findOrFail($id);

            // Ambil riwayat laundry berdasarkan id pasien
            $riwayatLaundry = Laundry::where('id_pasien', $id)
                ->orderBy('tanggal', 'DESC')
                ->get();

            return view('admin.pasien.show-public', compact('pasien', 'riwayatLaundry'));
        }

   public function laundryRequest(Request $request)
    {
        $request->validate([
            'id_pasien'    => 'required|exists:pasien,id', // Ganti dari id_user
            'id_ruangan'   => 'required|exists:ruangan,id',
            'nomr'         => 'required|string',

        ]);

        Laundry::create([
            'tanggal'      => now()->toDateString(),
            'id_pasien'    => $request->id_pasien, // Ganti dari id_user
            'id_ruangan'   => $request->id_ruangan,
            'nomr'         => $request->nomr,
            'keterangan'   => 0, // Set langsung ke 0
        ]);

        return redirect()->back()->with('success', 'Permintaan laundry berhasil dikirim.');
    }
    public function panicButton(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->toDateString());

        $pasien = Pasien::with(['ruangan', 'kamar.panicLogs' => function ($query) use ($tanggal) {
            if ($tanggal) {
                $query->whereDate('created_at', $tanggal);
            }
        }])
        ->where('status', 'rawat')
        ->whereHas('kamar.panicLogs', function ($query) use ($tanggal) {
            if ($tanggal) {
                $query->whereDate('created_at', $tanggal);
            }
        })
        ->get();

        return view('admin.pasien.panic-button', compact('pasien', 'tanggal'));
    }
}
