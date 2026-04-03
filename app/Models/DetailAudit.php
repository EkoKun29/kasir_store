<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailAudit extends Model
{
    use HasFactory;
    protected $fillable = [
        'audit_id',
        'produk',
        'qty',
        'tgl_exp'
    ];

    public function audit()
    {
        return $this->belongsTo(Audit::class,'audit_id');
    }
}
