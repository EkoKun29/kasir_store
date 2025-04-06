<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    
    public function index()
    {
        $penjualans = Penjualan::all();
        return view('kasir.penjualan.index', compact('penjualans'));
    }

  
    public function create()
    {
        // $latestPenjualan = Penjualan::latest()->first();
        return view('kasir.penjualan.create');
    }



    public function store(Request $request)
    {
        $request->validate([
            'id_kios' => 'required|integer',
            'potongan' => 'nullable|integer',
            'status_penjualan' => 'nullable|string|max:125',
        ]);

        // Buat nomor_surat secara otomatis
        $latestPenjualan = Penjualan::orderby('id', 'DESC')->first();
        if(!$latestPenjualan){
            $nomorSurat="NPJ-" . 1;
        }else {
            $nomorSurat = 'NPJ-' . $latestPenjualan->id + 1;
        }
    
        // $latestPenjualan = Penjualan::orderBy('id', 'DESC')->first();
        // $nextId = $latestPenjualan ? $latestPenjualan->id + 1 : 1;
        // $nomorSurat = 'NPJ-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $penjualan = Penjualan::create([
            'nomor_surat' => $nomorSurat,
            'id_user' => auth()->id(),
            'id_kios' => $request->id_kios,
            'potongan' => $request->potongan,
            'status_penjualan' => $request->status_penjualan,
        ]);
        
        return redirect()->route('detail_penjualan.create', ['penjualan_id' => $penjualan->id])
            ->with('success', 'Data penjualan berhasil disimpan! Silakan isi detail penjualan.');
        
        }
   
    public function edit($id)
    {
        $penjualan = Penjualan::findOrFail($id);
        return view('kasir.penjualan.edit', compact('penjualan'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'nomor_surat' => 'required|string|max:125',
            // 'id_user' => 'required|integer',
            'id_kios' => 'required|integer',
            'potongan' => 'nullable|integer',
            'status_penjualan' => 'nullable|string|max:125',
        ]);

        $penjualan = Penjualan::findOrFail($id);
        $penjualan->update($request->all());

        return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $penjualan = Penjualan::findOrFail($id);
        $penjualan->delete();

        return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil dihapus.');
    }

    public function nota($id)
    {
        $penjualan = Penjualan::with('detailPenjualans.barcode')->findOrFail($id);
        return view('kasir.penjualan.print_nota', compact('penjualan'));
    }
    
}
