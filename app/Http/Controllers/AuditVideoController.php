<?php

namespace App\Http\Controllers;

use App\Models\AuditVideo;
use App\Models\DetailAuditVideo;
use App\Models\Barcode;
use Illuminate\Http\Request;

class AuditVideoController extends Controller
{
    public function index()
{
    if(auth()->user()->role != 'admin'){
        abort(403);
    }

    $data = AuditVideo::with('detail','user')
            ->orderBy('created_at','desc')
            ->get();

    return view('audit_video.index',compact('data'));
}

public function create()
{
    if(auth()->user()->role != 'audit'){
        abort(403);
    }

    $audit = AuditVideo::where('id_user', auth()->id())
        ->where('created_at','>=',now()->subHours(12))
        ->first();

    $detail = $audit 
        ? DetailAuditVideo::where('audit_id', $audit->id)->get()
        : [];

    $barang = Barcode::pluck('produk');

    return view('audit_video.create', compact('audit','detail','barang'));
}

public function store(Request $request)
{

    $audit = AuditVideo::where('id_user', auth()->id())
        ->where('created_at','>=',now()->subHours(12))
        ->first();

    if(!$audit){
        $audit = AuditVideo::create([
            'id_user' => auth()->id(),
            'toko' => 'MADINQU FASHION'
        ]);
    }

    return redirect()->route('audit-video.create');
}

public function storeDetail(Request $request)
{
    $request->validate([
        'barang' => 'required|string',
        'qty' => 'required|integer|min:1'
    ]);

    try {

        $audit = AuditVideo::where('id_user', auth()->id())
            ->where('created_at','>=',now()->subHours(12))
            ->first();

        if(!$audit){
            return response()->json([
                'success' => false,
                'message' => 'Audit sudah expired, silakan mulai ulang'
            ], 400);
        }

        $detail = DetailAuditVideo::create([
            'audit_id' => $audit->id,
            'produk' => $request->barang,
            'qty' => $request->qty
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $detail->id,
                'produk' => $detail->produk,
                'qty' => $detail->qty
            ]
        ]);

    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

public function show($id)
{
    $audit = AuditVideo::with('detail','user')->findOrFail($id);

    return view('audit_video.show',compact('audit'));
}

public function deleteDetail($id)
{
    $detail = DetailAuditVideo::find($id);

    if ($detail) {
        $detail->delete();
    }

    return response()->json(['success' => true]);
}

public function delete($id)
{
    $audit = AuditVideo::with('detail')->findOrFail($id);

    $audit->detail()->delete();

    $audit->delete();

    return back()->with('success','Data dihapus');
}
}
