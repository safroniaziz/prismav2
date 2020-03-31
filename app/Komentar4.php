<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Komentar4 extends Model
{
    protected $fillable = [
        'usulan_id','reviewer_id','komentar'
    ];
}
