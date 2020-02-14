<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $fillable = [
        'nm_lengkap',
        'nip',
        'nidn',
        'pangkat',
        'golongan',
        'jabatan_fungsional',
        'fakultas_kode',
        'fakultas',
        'prodi_kode',
        'prodi',
        'keahlian',
        'perguruan_tinggi',
        'alamat_institusi',
        'telephone',
        'fax',
        'email',
    ];
}
