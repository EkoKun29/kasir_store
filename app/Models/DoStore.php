<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoStore extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'no_do',
        'id_user',
        'lokasi',
        'penginput',
        'created_at',
        'status'
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    public function User(){
        return $this->belongsTo(User::class);
    }

    public function detailDos()
    {
        return $this->hasMany(DetailDoStore::class, 'id_do_store');
    }
}
