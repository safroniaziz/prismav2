<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnggotaUsulan extends Model
{
    protected $fillable = [
        'usulan_id','anggota_nip','anggota_nama','anggota_prodi_id','anggota_prodi_nama','anggota_fakultas_id','anggota_fakultas_nama','anggota_jabatan_fungsional','anggota_jk','anggota_universitas'
    ];
}
