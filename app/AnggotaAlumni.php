<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnggotaAlumni extends Model
{
    protected $fillable = [
        'usulan_id','anggota_nama','jabatan'
    ];
}
