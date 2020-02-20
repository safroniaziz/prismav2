<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reviewer2 extends Model
{
    protected $fillable = [
        'usulan_id',
        'reviewer_id',
        'reviewer_nip',
        'reviewer_nama',
        'reviewer_prodi_id',
        'reviewer_prodi_nama',
        'reviewe_fakultas_id',
        'reviewe_fakultas_nama',
        'reviewer_jabatan_fungsional',
        'reviewer_jk',
        'reviewer_universitas',
    ];
}
