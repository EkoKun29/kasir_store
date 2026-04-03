<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_user',
        'toko',
        'kode'
    ];

    public function detail()
    {
        return $this->hasMany(DetailAudit::class,'audit_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }
}
