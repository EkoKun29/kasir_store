<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditVideo extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_user',
        'toko'
    ];

    public function detail()
    {
        return $this->hasMany(DetailAuditVideo::class,'audit_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }
}
