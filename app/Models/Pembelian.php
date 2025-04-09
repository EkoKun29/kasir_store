<?php

namespace App\Models;


use App\Models\Barcode;
use App\Models\DetailPembelian;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembelian extends Model
{
    use HasFactory;
    protected $fillable = [
        'tanggal_beli',
        'supplier',
        'total_harga',
        'nomor_surat',
        'id_user',
        'status_pembelian'
    ];
    public function detailPembelian()
    {
        return $this->hasMany(DetailPembelian::class);
    }

    public function barcode()
    {
        return $this->hasOne(Barcode::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
