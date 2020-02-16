<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Formulir extends Model
{
    protected $fillable = [
        'variabel','deskripsi','persentase'
    ];
}
