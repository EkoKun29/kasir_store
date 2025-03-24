<?php

namespace App\Models;


use App\Models\Barcode;
use App\Models\Pembelian;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailPembelian extends Model
{
    use HasFactory;
    protected $fillable = [
        'pembelian_id',
        'produk',
        'harga',
        'qty',
        'subtotal',
        'barcode_id'
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }

    public function barcode()
    {
        return $this->belongsTo(Barcode::class);
    }
}
