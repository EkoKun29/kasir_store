<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetailAudit;
use Illuminate\Http\Request;
use App\Models\DetailPenjualanPiutang;

class ExportAuditController extends Controller
{

public function exportAuditMingguan($startDate, $endDate)
{

    $audit = DetailAudit::with('audit.user')
        ->whereHas('audit', function ($q) use ($startDate, $endDate) {

            $q->whereDate('created_at', '>=', $startDate)
              ->whereDate('created_at', '<=', $endDate);

        })->get();

    return response()->json($audit);
}
}
