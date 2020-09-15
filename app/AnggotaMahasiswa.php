<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnggotaMahasiswa extends Model
{
    protected $fillable = [
        'usulan_id','anggota_npm','anggota_nama','anggota_jk','anggota_angkatan','anggota_prodi_nama','anggota_fakultas_nama'
    ];
}
