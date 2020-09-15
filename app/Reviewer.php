<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Reviewer extends Authenticatable
{
    use Notifiable;
    protected $guard = 'reviewer-usulan';
    protected $fillable = [
        'nip',
        'nama',
        'prodi_id',
        'prodi_nama',
        'fakultas_id',
        'fakultas_nama',
        'nidn',
        'jabatan_fungsional',
        'jenis_kelamin',
        'universitas',
        'jenis_reviewer',
        'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
