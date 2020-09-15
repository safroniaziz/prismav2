<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnggotaEksternal extends Model
{
    protected $fillable = [
        'usulan_id','anggota_nip','anggota_nama','anggota_nidn','anggota_jabatan_fungsional','anggota_jk','anggota_universitas'
    ];
}
