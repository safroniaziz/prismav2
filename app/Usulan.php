<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usulan extends Model
{
    protected $fillable = [
        'judul_kegiatan',
        'skim_id',
        'jenis_kegiatan',
        'ketua_peneliti_nip',
        'ketua_peneliti_nama',
        'abstrak',
        'kata_kunci',
        'peta_jalan',
        'file_usulan',
        'biaya_diusulkan',
        'tahun_usulan',
    ];

    public function getShortJudulAttribute(){
        return substr($this->judul_kegiatan, 0, random_int(40,60)). '...';
    }
}
