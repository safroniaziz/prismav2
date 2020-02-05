<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnggaranPerjalananLainnya extends Model
{
    protected $fillable = [
        'rancangan_anggaran_id','material','justifikasi_pembelian','kuantitas','satuan','harga_satuan'
    ];
}
