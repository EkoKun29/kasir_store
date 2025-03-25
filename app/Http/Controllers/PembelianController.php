<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\Barcode;
use Illuminate\Http\Request;

class PembelianController extends Controller
{
    // Menampilkan daftar pembelian
    public function index()
    {
        $pembelian = Pembelian::all();
        return view('admin.pembelian.index', compact('pembelian'));
    }

    // Menampilkan form pembelian
    public function create()
    {
        return view('admin.pembelian.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'tanggal_beli' => 'required|date',
            'supplier' => 'required|string|max:255',
        ]);

        // Simpan data pembelian terlebih dahulu
        $pembelian = Pembelian::create([
            'tanggal_beli' => $request->tanggal_beli,
            'supplier' => $request->supplier,
            'total_harga' => 0,
            'id_user' => auth()->id(),
        ]);

        // Hitung total harga baru
        $totalHarga = DetailPembelian::where('pembelian_id', $pembelian->id)->sum('subtotal');

        // Update total harga pada tabel pembelian
        $pembelian->update([
            'total_harga' => $totalHarga
        ]);

        return redirect()->route('pembelian.detail.create', $pembelian->id)
                        ->with('success', 'Data pembelian berhasil disimpan.');
    }

    // Menampilkan form detail pembelian
    public function createDetail($id)
    {
        $pembelian = Pembelian::findOrFail($id);
        $detailPembelians = DetailPembelian::where('pembelian_id', $id)->get();
        return view('admin.pembelian.create_detail', compact('pembelian', 'detailPembelians'));
    }

    // Proses penyimpanan data detail pembelian
    public function storeDetail(Request $request)
    {

        $pembelian = Pembelian::find($request->pembelian_id);

            if (!$pembelian) {
                return redirect()->back()->with('error', 'Data pembelian tidak ditemukan!');
            }

            // Simpan detail pembelian
            $detailPembelian = new DetailPembelian;
            $detailPembelian->pembelian_id = $request->pembelian_id;
            $detailPembelian->produk = $request->produk;
            // $detailPembelian->tanggal_beli = $pembelian->tanggal_beli; 
            $detailPembelian->harga = $request->harga;
            $detailPembelian->qty = $request->qty;
            $detailPembelian->subtotal = $request->harga * $request->qty;
            $detailPembelian->harga_jual = $request->harga_jual;
            $detailPembelian->save();

            $pembelian->total_harga +=$detailPembelian->subtotal;
            $pembelian->save();
            

            $dataBarcode = new Barcode;
            $dataBarcode->produk = $detailPembelian->produk;
            $dataBarcode->tanggal_beli = $pembelian->tanggal_beli;
            $dataBarcode->harga_beli = $detailPembelian->harga ;
            $dataBarcode->qty = $detailPembelian->qty;
            $dataBarcode->hpp = $dataBarcode->harga_beli / $dataBarcode->qty;
            $dataBarcode->harga_jual = $detailPembelian->harga_jual;
            $dataBarcode->save();

            $detailPembelian->barcode_id = $dataBarcode->id;
            $detailPembelian->save(); 


        return redirect()->route('pembelian.detail.create', $detailPembelian->pembelian->id)
            ->with('success', 'Detail pembelian berhasil ditambahkan dengan beberapa produk!');
    }

    public function show($id)
    {
        $pembelian = Pembelian::with('detailPembelian')->findOrFail($id);
    
        if ($pembelian->detailPembelian->isEmpty()) {
            return back()->with('error', 'Data detail pembelian tidak ditemukan');
        }
    
        return view('admin.pembelian.index_detail', compact('pembelian'));
    }    

     public function edit($id)
     {
         $pembelian = Pembelian::findOrFail($id);
         return view('admin.pembelian.edit', compact('pembelian'));
     }
 
     public function update(Request $request, $id)
     {
         $request->validate([
             'supplier' => 'required|string|max:255',
             'tanggal_beli' => 'required|date',
            // 'total_harga' => 'required|numeric',
         ]);
 
         $pembelian = Pembelian::findOrFail($id);
         $pembelian->update($request->all());

         return redirect()->route('pembelian.index')
                          ->with('success', 'Data pembelian berhasil diperbarui.');
     }

     public function editDetail ($id){
        $detailPembelian = DetailPembelian::findOrFail($id);
        return view('admin.pembelian.edit_detail',compact('detailPembelian'));
     }
     
     public function updateDetail(Request $request, $id){
        $detailPembelian = DetailPembelian::findOrFail($id);
        $pembelian = Pembelian::findOrFail($detailPembelian->pembelian_id);

        $subtotalLama = $detailPembelian->subtotal;

        $detailPembelian->produk = $request->produk;
        $detailPembelian->harga = $request->harga;
        $detailPembelian->qty = $request->qty;
        $detailPembelian->subtotal = $request->harga * $request->qty;
        $detailPembelian->harga_jual = $request->harga_jual;
        $detailPembelian->save();

        $pembelian->total_harga = ($pembelian->total_harga - $subtotalLama) + $detailPembelian->subtotal;
        $pembelian->save();
        
        return redirect()->route('pembelian.detail', $detailPembelian->pembelian_id)
                         ->with('success', 'Produk berhasil diperbarui.');

     }
 
     public function destroy($id)
     {
         $pembelian = Pembelian::findOrFail($id);
         $pembelian->delete();

         return redirect()->route('pembelian.index')
                          ->with('success', 'Data pembelian berhasil dihapus.');
     }


    public function destroyDetail($id)
    {
        $detailPembelian = DetailPembelian::find($id);

        $pembelian = Pembelian::find($detailPembelian->pembelian_id);
        $pembelian->total_harga -= $detailPembelian->subtotal;
        $pembelian->save();

        $dataBarcode = Barcode::find($detailPembelian->barcode_id);
        $dataBarcode->delete();
        $detailPembelian->delete();

        return redirect()->back()->with('success', 'Data pembelian berhasil dihapus.');
    }


}
