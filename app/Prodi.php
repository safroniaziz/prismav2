<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $fillable = [
        'prodi_kode','nm_prodi','fakultas_kode'
    ];
}
