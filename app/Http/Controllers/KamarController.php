<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class KamarController extends Controller
{
    public function index()
    {
        $kamars = Kamar::with('ruangan')->get();
        return view('kamar.index', compact('kamars'));
    }

    public function create()
    {
        $ruangans = Ruangan::all();
        return view('kamar.create', compact('ruangans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ruangan_id' => 'required',
            'nomor_kamar' => 'required|unique:kamar,nomor_kamar',
        ]);

        Kamar::create($request->all());
        return redirect()->route('kamar.index')->with('success', 'Data kamar berhasil ditambahkan');
    }

    public function edit(Kamar $kamar)
    {
        $ruangans = Ruangan::all();
        return view('kamar.edit', compact('kamar', 'ruangans'));
    }

    public function update(Request $request, Kamar $kamar)
    {
        $request->validate([
            'ruangan_id' => 'required',
            'nomor_kamar' => 'required|unique:kamar,nomor_kamar,' . $kamar->id,
        ]);

        $kamar->update($request->all());
        return redirect()->route('kamar.index')->with('success', 'Data kamar berhasil diupdate');
    }

    public function destroy(Kamar $kamar)
    {
        $kamar->delete();
        return redirect()->route('kamar.index')->with('success', 'Data kamar berhasil dihapus');
    }
}
