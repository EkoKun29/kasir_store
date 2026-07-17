<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoStore;
use App\Models\DetailDoStore;
use App\Models\Barcode;
use Illuminate\Support\Facades\Auth;
use Google\Client as GoogleClient;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;
use Google\Service\Sheets\BatchUpdateValuesRequest;
use Google\Service\Sheets\BatchUpdateSpreadsheetRequest;




class DoStoreController extends Controller
{
    public function index(Request $request)
{

    if ($request->mode == 'detail' && Auth::user()->role == 'admin') {

        $query = DetailDoStore::with('DoStore');
        $detailDos = $query->latest()
                           ->paginate(20)
                           ->withQueryString();

        return view('do.index-detail', compact('detailDos'));
    }


    if (Auth::user()->role == 'admin') {

            $doStore = DoStore::latest()
                ->paginate(20)
                ->withQueryString();

        } elseif (Auth::user()->role == 'do') {

            $doStore = DoStore::latest()
                ->paginate(20)
                ->withQueryString();

        } else {

            abort(403);

        }
    return view('do.index', compact('doStore'));
}

    public function create()
{
    $do = DoStore::where('id_user', auth()->id())
        ->where('status',0)
        ->latest()
        ->first();

    $detail = $do
        ? DetailDoStore::where('id_do_store', $do->id)
            ->latest()
            ->get()
        : collect();

    $db = Barcode::all();


    return view('do.create', compact(
        'do',
        'detail',
        'db'
    ));
}

public function store(Request $request)
{
    $request->validate([
        'nama_personil' => 'required'
    ]);

    $do = DoStore::where('id_user', auth()->id())
        ->where('status',0)
        ->latest()
        ->first();

    if (!$do) {

        $last = DoStore::where('id_user', auth()->id())
            ->latest()
            ->first();

        $urut = 1;

        if ($last) {
            $parts = explode('-', $last->no_do);

            if (count($parts) > 1) {
                $urut = (int)$parts[1] + 1;
            }
        }

        $do = DoStore::create([
            'id_user'   => auth()->id(),
            'no_do'     => 'DOMST' . '-' . $urut,
            'lokasi'    => 'MADINQU STORE',
            'penginput' => $request->nama_personil,
            'status'    => 0
        ]);
    }

    return redirect()->route('do.create');
}

public function storeDetail(Request $request)
{
    $request->validate([
        'barang'      => 'required',
        'qty'         => 'required|integer|min:1',
        'satuan'      => 'required',
        // 'harga'       => 'required|numeric|min:0',
    ]);

    $do = DoStore::where('id_user', auth()->id())
        ->where('status',0)
        ->firstOrFail();

    $detail = DetailDoStore::create([
        'id_do_store' => $do->id,
        'produk'      => $request->barang,
        'qty'         => $request->qty,
        'satuan'      => $request->satuan,
        'harga'       => 0,
    ]);

    $this->syncDoToSheet($do, $detail);

    return response()->json([
        'success' => true,
        'data' => $detail
    ]);
}

public function finish($id)
{
    $do = DoStore::where('id', $id)
            ->where('id_user', auth()->id())
            ->firstOrFail();

    $do->update([
        'status' => 1
    ]);

    return redirect()->route('do.index')
            ->with('success','DO berhasil diselesaikan.');
}


    public function show($id)
    {
        $do = DoStore::findOrFail($id);

        $detail = DetailDoStore::where('id_do_store', $id)
            ->orderBy('id', 'desc')
            ->get();

        return view('do.show', compact('do', 'detail'));
    }



    public function destroyDetail($id)
    {

    $detail = DetailDoStore::findOrFail($id);

    $this->deleteDoFromSheet($detail->id);

    $detail->delete();

    return back()->with('success','Detail dihapus');
    }

    public function destroy($id)
    {

        $do = DoStore::with('detailDos')->findOrFail($id);

        $detailIds = $do->detailDos->pluck('id')->toArray();

        // Hapus semua row spreadsheet sekaligus
        $this->deleteAllDataFromSheet($do->no_do);

        // Hapus database
        $do->detailDos()->delete();
        $do->delete();

    return back()->with('success', 'Data dihapus');
        
    }

    public function resyncSheet($id)
{
    set_time_limit(300);

    $do = DoStore::with('detailDos')->findOrFail($id);

    $client = new GoogleClient();
    $client->setAuthConfig(storage_path('app/google/credentials.json'));
    $client->addScope(Sheets::SPREADSHEETS);

    $service = new Sheets($client);

    $spreadsheetId = '1pjLZkSjToWfhtftPb8qRoKJG-tnPg1krLJ40E6LXP70';
    $sheetName = 'DO';

    // Ambil seluruh kolom A
    $response = $service->spreadsheets_values->get(
        $spreadsheetId,
        "{$sheetName}!A2:A"
    );

    $rows = $response->getValues() ?? [];

    $rowMap = [];

    foreach ($rows as $index => $row) {

        if (!empty($row[0])) {
            $rowMap[(string)$row[0]] = $index + 2;
        }

    }

    foreach ($do->detailDos as $detail) {

        $values = [[
            $detail->id,
            $do->created_at->format('Y-m-d'),
            $do->no_do,
            $do->penginput,
            $do->lokasi,
            $detail->produk,
            $detail->qty,
            $detail->satuan,
            $detail->harga,
            $do->created_at->format('H:i:s'),
            $detail->created_at
                ->timezone('Asia/Jakarta')
                ->format('Y-m-d H:i:s')
        ]];

        // Kalau sudah ada -> update
        if (isset($rowMap[(string)$detail->id])) {

            $service->spreadsheets_values->update(
                $spreadsheetId,
                "{$sheetName}!A".$rowMap[(string)$detail->id],
                new ValueRange([
                    'values' => $values
                ]),
                [
                    'valueInputOption' => 'USER_ENTERED'
                ]
            );

        } else {

            // Kalau belum ada -> append
            $service->spreadsheets_values->append(
                $spreadsheetId,
                $sheetName,
                new ValueRange([
                    'values' => $values
                ]),
                [
                    'valueInputOption' => 'USER_ENTERED'
                ]
            );

        }

    }

    return back()->with('success', 'Berhasil resync ke Google Sheets');
}


    private function syncDoToSheet($do, $detail)
{
    $client = new GoogleClient();
    $client->setAuthConfig(storage_path('app/google/credentials.json'));
    $client->addScope(Sheets::SPREADSHEETS);

    $service = new Sheets($client);

    $spreadsheetId = '1pjLZkSjToWfhtftPb8qRoKJG-tnPg1krLJ40E6LXP70';
    $sheetName = 'DO';

    // cek ID_DETAIL di kolom A
    $response = $service->spreadsheets_values->get(
        $spreadsheetId,
        "{$sheetName}!A2:A"
    );

    $rows = $response->getValues() ?? [];

    $rowNumber = null;

    foreach ($rows as $index => $row) {

    if (
        isset($row[0]) &&
        trim((string)$row[0]) === trim((string)$detail->id)
    ) {
        $rowNumber = $index + 2;
        break;
    }

}   

    $values = [[
        $detail->id,
        $do->created_at->format('Y-m-d'),
        $do->no_do,
        $do->penginput,
        $do->lokasi,
        $detail->produk,
        $detail->qty,
        $detail->satuan,
        $detail->harga,
        $do->created_at->format('H:i:s'),
        $detail->created_at->timezone('Asia/Jakarta')
                        ->format('Y-m-d H:i:s')
    ]];

    if ($rowNumber) {

        // UPDATE BARIS
        $service->spreadsheets_values->update(
            $spreadsheetId,
            "{$sheetName}!A{$rowNumber}",
            new ValueRange([
                'values' => $values
            ]),
            ['valueInputOption' => 'USER_ENTERED']
        );

    } else {

        // TAMBAH BARIS BARU
        $service->spreadsheets_values->append(
            $spreadsheetId,
            $sheetName,
            new ValueRange([
                'values' => $values
            ]),
            ['valueInputOption' => 'USER_ENTERED']
        );

    }
}

private function batchSyncDoToSheet($do)
{
    $client = new GoogleClient();
    $client->setAuthConfig(storage_path('app/google/credentials.json'));
    $client->addScope(Sheets::SPREADSHEETS);

    $service = new Sheets($client);

    $spreadsheetId = '1pjLZkSjToWfhtftPb8qRoKJG-tnPg1krLJ40E6LXP70';
    $sheetName = 'DO';

    $response = $service->spreadsheets_values->get(
        $spreadsheetId,
        "{$sheetName}!A2:A"
    );

    $rows = $response->getValues() ?? [];

    $rowMap = [];

    foreach ($rows as $index => $row) {

        if (isset($row[0])) {
            $rowMap[(int)$row[0]] = $index + 2;
        }

    }

    $data = [];

    foreach ($do->detailDos as $detail) {

        if (!isset($rowMap[$detail->id])) {
            continue;
        }

        $rowNumber = $rowMap[$detail->id];

        $data[] = new ValueRange([
            'range' => "{$sheetName}!A{$rowNumber}",
            'values' => [[
                $detail->id,
                $do->created_at->format('Y-m-d'),
                $do->no_do,
                $do->penginput,
                $do->lokasi,
                $detail->produk,
                $detail->qty,
                $detail->satuan,
                $detail->harga,
                $do->created_at->format('H:i:s'),
                $detail->created_at
                        ->timezone('Asia/Jakarta')
                        ->format('Y-m-d H:i:s')
            ]]
        ]);

    }

    if (!empty($data)) {

        $body = new BatchUpdateValuesRequest([
            'valueInputOption' => 'USER_ENTERED',
            'data' => $data
        ]);

        $service->spreadsheets_values->batchUpdate(
            $spreadsheetId,
            $body
        );
    }
}

private function deleteDoFromSheet($detailId)
{
    $client = new GoogleClient();
    $client->setAuthConfig(storage_path('app/google/credentials.json'));
    $client->addScope(Sheets::SPREADSHEETS);

    $service = new Sheets($client);

    $spreadsheetId = '1pjLZkSjToWfhtftPb8qRoKJG-tnPg1krLJ40E6LXP70';
    $sheetName = 'DO';

    $sheetId = $this->getSheetIdByName($service, $spreadsheetId, $sheetName);

    $response = $service->spreadsheets_values->get(
        $spreadsheetId,
        "{$sheetName}!A2:A"
    );

    $rows = $response->getValues() ?? [];

    foreach ($rows as $index => $row) {

        if (isset($row[0]) && trim($row[0]) == trim($detailId)) {

            $rowIndex = $index + 1; // karena data mulai dari baris ke-2

            $request = new BatchUpdateSpreadsheetRequest([
                'requests' => [[
                    'deleteDimension' => [
                        'range' => [
                            'sheetId'    => $sheetId,
                            'dimension'  => 'ROWS',
                            'startIndex' => $rowIndex,
                            'endIndex'   => $rowIndex + 1,
                        ]
                    ]
                ]]
            ]);

            $service->spreadsheets->batchUpdate($spreadsheetId, $request);

            break;
        }
    }
}

private function deleteAllDataFromSheet($noDo)
{
    $client = new GoogleClient();
    $client->setAuthConfig(storage_path('app/google/credentials.json'));
    $client->addScope(Sheets::SPREADSHEETS);

    $service = new Sheets($client);

    $spreadsheetId = '1pjLZkSjToWfhtftPb8qRoKJG-tnPg1krLJ40E6LXP70';
    $sheetName = 'DO';

    $sheetId = $this->getSheetIdByName($service, $spreadsheetId, $sheetName);

    // Ambil kolom C (NO DO)
    $response = $service->spreadsheets_values->get(
        $spreadsheetId,
        "{$sheetName}!C2:C"
    );

    $rows = $response->getValues() ?? [];

    $rowIndexes = [];

    foreach ($rows as $index => $row) {

        if (isset($row[0]) && trim($row[0]) == trim($noDo)) {

            // karena mulai dari baris ke-2
            $rowIndexes[] = $index + 1;
        }
    }

    if (empty($rowIndexes)) {
        return;
    }

    // hapus dari bawah
    rsort($rowIndexes);

    $requests = [];

    foreach ($rowIndexes as $rowIndex) {

        $requests[] = [
            'deleteDimension' => [
                'range' => [
                    'sheetId'    => $sheetId,
                    'dimension'  => 'ROWS',
                    'startIndex' => $rowIndex,
                    'endIndex'   => $rowIndex + 1,
                ]
            ]
        ];
    }

    $body = new BatchUpdateSpreadsheetRequest([
        'requests' => $requests
    ]);

    $service->spreadsheets->batchUpdate($spreadsheetId, $body);
}

private function getSheetIdByName($service, $spreadsheetId, $sheetName)
{
    $spreadsheet = $service->spreadsheets->get($spreadsheetId);
    foreach ($spreadsheet->getSheets() as $sheet) {
        if ($sheet->getProperties()->getTitle() === $sheetName) {
            return $sheet->getProperties()->getSheetId();
        }
    }
    throw new \Exception("Sheet tidak ditemukan");
}
}
