<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penjualan extends Model
{
    use HasFactory;
    protected $fillable = [
        'nomor_surat',
        'id_user',
        'id_kios',
        'potongan',
        'status_penjualan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

