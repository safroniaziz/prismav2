<?php

namespace App\Http\Controllers\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Usulan;
use App\UsulanDisetujui;
use DB;

class HasilUsulanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function diterima(){
        $usulans = UsulanDisetujui::join('usulans','usulans.id','usulan_disetujuis.usulan_id')
                                    ->leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                                    ->select('usulans.id','judul_kegiatan',
                                            'ketua_peneliti_nama as nm_ketua_peneliti',
                                            DB::raw('group_concat(distinct concat(anggota_usulans.anggota_nama) SEPARATOR "<br>") as "nm_anggota" ')
                                            )
                                    ->where(function($query) {
                                        $query->where('status_usulan','3')
                                        ->orWhere('status_usulan','6');
                                    })
                                    ->groupBy('usulans.id')
                                    ->get();
        return view('operator/usulan/disetujui.index',compact('usulans'));
    }

    public function ditolak(){
        $usulans = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                                    ->select('usulans.id','judul_kegiatan',
                                            'ketua_peneliti_nama as nm_ketua_peneliti',
                                            DB::raw('group_concat(distinct concat(anggota_usulans.anggota_nama) SEPARATOR "<br>") as "nm_anggota" ')
                                            )
                                    ->where('usulans.status_usulan','4')
                                    ->groupBy('usulans.id')
                                    ->get();
        return view('operator/usulan/ditolak.index',compact('usulans'));
    }
}
