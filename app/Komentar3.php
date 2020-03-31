<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Komentar3 extends Model
{
    protected $fillable = [
        'usulan_id','reviewer_id','komentar'
    ];
}
