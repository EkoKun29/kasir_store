<?php

namespace App\Http\Controllers;

use App\Models\Kios;
use Illuminate\Http\Request;

class KiosController extends Controller
{
    public function index()
    {
        $kios = Kios::all();
        return view('admin.kios.index', compact('kios'));
    }

    public function create()
    {
        return view('admin.kios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kios' => 'required|string|max:255',
        ]);

        Kios::create([
            'kios' => $request->kios,
        ]);

        return redirect()->route('kios.index')->with('success', 'Data kios berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kios = Kios::findOrFail($id);
        return view('admin.kios.edit', compact('kios'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kios' => 'required|string|max:255',
        ]);

        $kios = Kios::findOrFail($id);
        $kios->update([
            'kios' => $request->kios,
        ]);

        return redirect()->route('kios.index')->with('success', 'Data kios berhasil diperbarui');
    }

    public function destroy($id)
    {
        $kios = Kios::findOrFail($id);
        $kios->delete();

        return redirect()->route('kios.index')->with('success', 'Data kios berhasil dihapus');
    }
}
