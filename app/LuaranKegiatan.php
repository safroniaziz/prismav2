<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LuaranKegiatan extends Model
{
    protected $fillable = [
        'judul_luaran','jenis_publikasi','jenis_kegiatan','usulan_id'
    ];
}
