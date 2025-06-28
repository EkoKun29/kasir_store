<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\Barcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

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
            'status_pembelian' => 'required|string'
        ]);

        // Generate nomor_surat otomatis
        $lastPembelian = Pembelian::orderBy('id', 'desc')->first();
        $lastNumber = $lastPembelian ? intval(substr($lastPembelian->nomor_surat, 4)) : 0;
        $newNumber = $lastNumber + 1;
        $nomorSurat = 'NPB-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        // Simpan data pembelian
        $pembelian = Pembelian::create([
            'tanggal_beli' => $request->tanggal_beli,
            'supplier' => $request->supplier,
            'total_harga' => 0,
            'nomor_surat' => $nomorSurat,
            'id_user' => auth()->id(),
            'status' => $request->status_pembelian
        ]);

        // Hitung total harga jika ada detail pembelian (jika pakai AJAX atau tambah setelah ini)
        $totalHarga = DetailPembelian::where('pembelian_id', $pembelian->id)->sum('subtotal');

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
        $client = new Client();

        $api = 'https://script.googleusercontent.com/a/macros/aliansyah.com/echo?user_content_key=AehSKLiY8drJsyq0_3Ewt3UV-sFflnxzrZ6IuZnn95flfwjRc-jIsjnC6HUsyUDcTbwumcawuyTNw1pmjWgLsLDhMEMeq-1N_46B8xtJhyKk-Rkx2heCJxZFjxmH2J80FAK752rIXgjTVjytCso_nfnW7PYp00ky7weUm9mS4kqa1AjHfS8TbIHCnr5Py6J-EprBVE0NsgzHsnCpxUgvw6u2Qwp64F-rheQU05BKPo93vjMH0umi7e9opnYIhKr1yfvGQIBa7S7Lugk1S1ke_0zUyTJeTXL7aWpCuQ9DGcHWrfw7wfsNF34_NT_QLwhPaQ&lib=MIv9TbkUYrXGiFbr4eFOEokMJMEEkocmk';

        $response = $client->request('GET', $api, [
            'verify'  => false,
        ]);

        $data = json_decode($response->getBody());
        $produkKoperasi = collect($data);
        return view('admin.pembelian.create_detail', compact('pembelian', 'detailPembelians', 'produkKoperasi'));
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
            $detailPembelian->produk = strtoupper($request->produk);
            // $detailPembelian->tanggal_beli = $pembelian->tanggal_beli; 
            $detailPembelian->harga = $request->harga;
            $detailPembelian->qty = $request->qty;
            $detailPembelian->subtotal = $request->harga * $request->qty;
            $detailPembelian->harga_jual = $request->harga_jual;
            $detailPembelian->save();

            $pembelian->total_harga +=$detailPembelian->subtotal;
            $pembelian->save();
            

            $dataBarcode = new Barcode;
            $dataBarcode->produk = strtoupper($detailPembelian->produk);
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
             'status_pembelian' => 'required|string'
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

        $detailPembelian->produk = strtoupper($request->produk);
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
        $pembelian = Pembelian::find($id);

        foreach($pembelian->detailPembelian as $detail){
            $detail->barcode->delete();
            $detail->delete();
        }
        $pembelian->delete();
        
         return redirect()->route('pembelian.index')->with('success', 'Data pembelian berhasil dihapus.');
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

    public function nota($id)
    {
        $pembelian = Pembelian::with('detailPembelian.barcode')->findOrFail($id);
        return view('admin.pembelian.nota_pembelian', compact('pembelian'));        
    }   
    
    //API
    public function apiIndex()
    {
        $pembelian = Pembelian::all();

        return response()->json([
            'success' => true,
            'message' => 'Data Pembelian',
            'data' => $pembelian
        ]);
    }
    

}
