<?php

namespace App\Models;

use App\Models\Barcode;
use App\Models\Penjualan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailPenjualan extends Model
{
    use HasFactory;
    protected $fillable = [
        'barcode_id',
        'pcs',
        'subtotal',
        'diskon',
        'penjualan_id',
    ];

    public function barcode()
    {
        return $this->belongsTo(Barcode::class, 'barcode_id');
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id', 'id');
    }
}
