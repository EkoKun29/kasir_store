<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class DetailDoStore extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'id_do_store',
        'produk',
        'qty',
        'satuan',
        'harga',
        'created_at',
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    public function doStore()
    {
        return $this->belongsTo(DoStore::class, 'id_do_store');
    }
}
