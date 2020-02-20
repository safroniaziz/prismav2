<?php

namespace App\Http\Controllers\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Usulan;
use App\UsulanDisetujui2;
use DB;

class HasilLaporanKemajuanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function diterima(){
        $usulans = UsulanDisetujui2::join('usulans','usulans.id','usulan_disetujui2s.usulan_id')
                                    ->leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                                    ->select('usulans.id','judul_penelitian',
                                            'ketua_peneliti_nama as nm_ketua_peneliti',
                                            DB::raw('group_concat(distinct concat(anggota_usulans.anggota_nama) SEPARATOR "<br>") as "nm_anggota" ')
                                            )
                                    ->where('usulans.status_usulan','6')
                                    ->groupBy('usulans.id')
                                    ->get();
        return view('operator/usulan/disetujui.index',compact('usulans'));
    }

    public function ditolak(){
        $usulans = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                                    ->select('usulans.id','judul_penelitian',
                                            'ketua_peneliti_nama as nm_ketua_peneliti',
                                            DB::raw('group_concat(distinct concat(anggota_usulans.anggota_nama) SEPARATOR "<br>") as "nm_anggota" ')
                                            )
                                    ->where('usulans.status_usulan','7')
                                    ->groupBy('usulans.id')
                                    ->get();
        return view('operator/usulan/ditolak.index',compact('usulans'));
    }
}
