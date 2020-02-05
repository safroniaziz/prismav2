<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnggotaUsulan extends Model
{
    protected $fillable = [
        'usulan_id','anggota_id'
    ];
}
