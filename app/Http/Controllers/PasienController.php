<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Pasien;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class PasienController extends Controller
{
   public function index()
{
    $pasien = Pasien::with(['ruangan', 'kamar'])->get(); // eager loading
    return view('pasien.index', compact('pasien'));
}

   public function create()
    {
        $ruangans = Ruangan::all();
        return view('pasien.create', compact('ruangans'));

    }

public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'ruangan_id' => 'required|exists:ruangan,id',
            'kamar_id' => 'required|exists:kamar,id',
            'kendala' => 'required|string',
            'status' => 'required|in:rawat,pulang',
        ]);

        Pasien::create([
            'nama'       => $request->nama,
            'ruangan_id' => $request->ruangan_id,
            'kamar_id'   => $request->kamar_id,
            'kendala'    => $request->kendala,
            'status'     => $request->status,
        ]);

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil ditambahkan.');
    }


    public function edit(Pasien $pasien)
    {
        $ruangans = Ruangan::all();
        return view('pasiens.edit', compact('pasien', 'ruangans'));
    }

    public function update(Request $request, Pasien $pasien)
    {
        $request->validate([
            'nama' => 'required',
            'ruangan_id' => 'required|exists:ruangan,id',
            'kendala' => 'required',
            'status' => 'required|in:rawat,pulang',
        ]);

        $pasien->update($request->all());
        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil diperbarui.');
    }

    public function destroy(Pasien $pasien)
    {
        $pasien->delete();
        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil dihapus.');
    }

    public function updateStatus($id)
{
    $pasien = Pasien::findOrFail($id);

    // Toggle status: rawat ↔ pulang
    $pasien->status = $pasien->status === 'rawat' ? 'pulang' : 'rawat';
    $pasien->save();

    return redirect()->route('pasien.index')->with('success', 'Status pasien berhasil diubah.');
}
}
