<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NilaiFormulir4 extends Model
{
    protected $fillable = [
        'usulan_id','formulir_id','reviewer_id','skor'
    ];
}
