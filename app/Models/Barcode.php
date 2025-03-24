<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barcode extends Model
{
    use HasFactory;
    protected $fillable = [
        'produk',
        'tanggal_beli',
        'harga_beli',
        'qty',
        'hpp',
    ];

    
}
