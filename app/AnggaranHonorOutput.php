<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnggaranHonorOutput extends Model
{
    protected $fillable = [
        'rancangan_anggaran_id','keterangan_honor','biaya','hari_per_minggu','jumlah_minggu'
    ];
}
