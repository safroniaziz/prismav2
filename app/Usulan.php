<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usulan extends Model
{
    protected $fillable = [
        'judul_penelitian',
        'skim_id',
        'bidang_id',
        'ketua_peneliti_nip',
        'ketua_peneliti_nama',
        'abstrak',
        'kata_kunci',
        'peta_jalan',
        'biaya_diusulkan',
        'tahun_usulan',
    ];
}
