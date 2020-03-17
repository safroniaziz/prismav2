<?php

namespace App\Http\Controllers\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LaporanAkhir;
use DB;
use App\Fakultas;

class LaporanAkhirController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $usulans = LaporanAkhir::join('usulans','usulans.id','laporan_akhirs.usulan_id')
                                ->leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                                ->leftJoin('reviewer2s','reviewer2s.usulan_id','usulans.id')
                                ->select('usulans.id','judul_kegiatan',
                                        'ketua_peneliti_nama as nm_ketua_peneliti',
                                        DB::raw('group_concat(distinct concat(anggota_usulans.anggota_nama) SEPARATOR "<br>") as "nm_anggota" '),
                                        DB::raw('group_concat(distinct concat(reviewer2s.reviewer_nama) SEPARATOR "<br>") as "nm_reviewer" ')
                                        )
                                ->groupBy('usulans.id')
                                ->get();
        return view('operator/laporan_akhir.index',compact('usulans'));
    }
}
