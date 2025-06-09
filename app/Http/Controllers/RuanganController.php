<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    /**
     * Tampilkan daftar semua ruangan.
     */
    public function index()
    {
        $ruangan = Ruangan::all();
        return view('ruangan.index', compact('ruangan'));
    }

    /**
     * Tampilkan form untuk menambah ruangan.
     */
    public function create()
    {
        return view('ruangan.create');
    }

  public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:ruangan,kode',
            'nama' => 'required',
        ]);

        Ruangan::create($request->all());
        return redirect()->route('ruangan.index')->with('success', 'Data ruangan berhasil ditambahkan.');
    }

    public function show(Ruangan $ruangan)
    {
        return view('ruangan.show', compact('ruangan'));
    }

    public function edit(Ruangan $ruangan)
    {
        return view('ruangan.edit', compact('ruangan'));
    }

    public function update(Request $request, Ruangan $ruangan)
    {
        $request->validate([
            'kode' => 'required|unique:ruangan,kode,' . $ruangan->id,
            'nama' => 'required',

        ]);

        $ruangan->update($request->all());
        return redirect()->route('ruangan.index')->with('success', 'Data ruangan berhasil diperbarui.');
    }

    public function destroy(Ruangan $ruangan)
    {
        $ruangan->delete();
        return redirect()->route('ruangan.index')->with('success', 'Data ruangan berhasil dihapus.');
    }
}
