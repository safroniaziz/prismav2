<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Reviewer1 extends Authenticatable
{
    use Notifiable;
    protected $guard = 'reviewer-usulan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'usulan_id',
        'reviewer_id',
        'reviewer_nip',
        'reviewer_nama',
        'reviewer_prodi_id',
        'reviewer_prodi_nama',
        'reviewe_fakultas_id',
        'reviewe_fakultas_nama',
        'reviewer_jabatan_fungsional',
        'reviewer_jk',
        'reviewer_universitas',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
