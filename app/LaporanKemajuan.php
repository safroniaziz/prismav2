<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LaporanKemajuan extends Model
{
    protected $fillable = [
        'usulan_id',
        'file_kemajuan'
    ];
}
