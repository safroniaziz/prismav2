<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LaporanAkhir extends Model
{
    protected $fillable = [
        'usulan_id',
        'file_akhir'
    ];
}
