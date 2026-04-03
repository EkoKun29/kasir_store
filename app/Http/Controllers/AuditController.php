<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\DetailAudit;
use App\Models\Barcode;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index()
{
    if(auth()->user()->role != 'admin'){
        abort(403);
    }

    $data = Audit::with('detail','user')
            ->orderBy('created_at','desc')
            ->get();

    return view('audit.index',compact('data'));
}

public function create($kode)
{
    if(auth()->user()->role != 'audit'){
        abort(403);
    }

    $audit = Audit::where('id_user', auth()->id())
        ->where('kode', $kode)
        ->where('created_at','>=',now()->subHours(12))
        ->first();

    $detail = $audit 
        ? DetailAudit::where('audit_id', $audit->id)->get()
        : [];

    $barang = Barcode::pluck('produk');

    return view('audit.create', compact('audit','detail','kode','barang'));
}

public function store(Request $request)
{
    $request->validate([
        'kode' => 'required'
    ]);

    $audit = Audit::where('id_user', auth()->id())
        ->where('kode', $request->kode)
        ->where('created_at','>=',now()->subHours(12))
        ->first();

    if(!$audit){
        $audit = Audit::create([
            'id_user' => auth()->id(),
            'kode' => $request->kode,
            'toko' => 'MADINQU FASHION'
        ]);
    }

    return redirect()->route('audit.create', $request->kode);
}

public function storeDetail(Request $request)
{
    $request->validate([
        'barang' => 'required|string',
        'qty' => 'required|integer|min:1',
        'tgl_exp' => 'required|date'
    ]);

    try {

        $audit = Audit::where('id_user', auth()->id())
            ->where('kode', $request->kode)
            ->where('created_at','>=',now()->subHours(12))
            ->first();

        if(!$audit){
            return response()->json([
                'success' => false,
                'message' => 'Audit sudah expired, silakan mulai ulang'
            ], 400);
        }

        $detail = DetailAudit::create([
            'audit_id' => $audit->id,
            'produk' => $request->barang,
            'qty' => $request->qty,
            'tgl_exp' => $request->tgl_exp
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $detail->id,
                'produk' => $detail->produk,
                'qty' => $detail->qty,
                'tgl_exp' => $detail->tgl_exp
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
    $audit = Audit::with('detail','user')->findOrFail($id);

    return view('audit.show',compact('audit'));
}

public function deleteDetail($id)
{
    $detail = DetailAudit::find($id);

    if ($detail) {
        $detail->delete();
    }

    return response()->json(['success' => true]);
}

public function delete($id)
{
    $audit = Audit::with('detail')->findOrFail($id);

    $audit->detail()->delete();

    $audit->delete();

    return back()->with('success','Data dihapus');
}

// private function syncAuditToSheet($audit, $detail)
// {
//     $client = new GoogleClient();
//     $client->setAuthConfig(storage_path('app/google/credentials.json'));
//     $client->addScope(Sheets::SPREADSHEETS);

//     $service = new Sheets($client);

//     $spreadsheetId = '1I74czvLfIm-EHnvQVYvzJysIlUS8BSegwJhjSPqqOiw';
//     $sheetName = 'IMPORT';

//     // cek ID_DETAIL di kolom A
//     $response = $service->spreadsheets_values->get(
//         $spreadsheetId,
//         "{$sheetName}!A2:A"
//     );

//     $rows = $response->getValues() ?? [];

//     $rowNumber = null;

//     foreach ($rows as $index => $row) {

//         if (isset($row[0]) && intval($row[0]) === intval($detail->id)) {

//             $rowNumber = $index + 2;
//             break;

//         }

//     }

//     $values = [[
//         $detail->id,
//         $audit->created_at->format('Y-m-d'),
//         $audit->user->name,
//         $audit->toko,
//         $audit->kode,
//         $detail->produk,
//         $detail->qty,
//         $detail->tgl_exp,
//         $audit->created_at
//     ]];

//     if ($rowNumber) {

//         // UPDATE BARIS
//         $service->spreadsheets_values->update(
//             $spreadsheetId,
//             "{$sheetName}!A{$rowNumber}",
//             new ValueRange([
//                 'values' => $values
//             ]),
//             ['valueInputOption' => 'USER_ENTERED']
//         );

//     } else {

//         // TAMBAH BARIS BARU
//         $service->spreadsheets_values->append(
//             $spreadsheetId,
//             $sheetName,
//             new ValueRange([
//                 'values' => $values
//             ]),
//             ['valueInputOption' => 'USER_ENTERED']
//         );

//     }
// }



// private function deleteAuditFromSheet($detailId)
// {
//     $client = new GoogleClient();
//     $client->setAuthConfig(storage_path('app/google/credentials.json'));
//     $client->addScope(Sheets::SPREADSHEETS);

//     $service = new Sheets($client);

//     $spreadsheetId = '1I74czvLfIm-EHnvQVYvzJysIlUS8BSegwJhjSPqqOiw';
//     $sheetName = 'IMPORT';

//     $sheetId = $this->getSheetIdByName($service,$spreadsheetId,$sheetName);

//     $response = $service->spreadsheets_values->get(
//         $spreadsheetId,
//         "{$sheetName}!A2:A"
//     );

//     $rows = $response->getValues() ?? [];

//     foreach ($rows as $index => $row) {

//         if (isset($row[0]) && intval($row[0]) === intval($detailId)) {

//             $rowIndex = $index + 1;

//             $request = new BatchUpdateSpreadsheetRequest([
//                 'requests' => [[
//                     'deleteDimension' => [
//                         'range' => [
//                             'sheetId' => $sheetId,
//                             'dimension' => 'ROWS',
//                             'startIndex' => $rowIndex,
//                             'endIndex' => $rowIndex + 1,
//                         ]
//                     ]
//                 ]]
//             ]);

//             $service->spreadsheets->batchUpdate($spreadsheetId,$request);

//             break;
//         }
//     }
// }


// private function getSheetIdByName($service, $spreadsheetId, $sheetName)
// {
//     $spreadsheet = $service->spreadsheets->get($spreadsheetId);
//     foreach ($spreadsheet->getSheets() as $sheet) {
//         if ($sheet->getProperties()->getTitle() === $sheetName) {
//             return $sheet->getProperties()->getSheetId();
//         }
//     }
//     throw new \Exception("Sheet tidak ditemukan");
// }
}
