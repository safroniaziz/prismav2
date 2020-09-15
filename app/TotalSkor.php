<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TotalSkor extends Model
{
    protected $fillable = [
        'usulan_id','total_skor','reviewer_id'
    ];
}
