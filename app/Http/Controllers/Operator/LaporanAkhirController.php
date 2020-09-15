<?php

namespace App\Http\Controllers\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Usulan;
use DB;
use App\Fakultas;

class LaporanAkhirController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $usulans = Usulan::join('laporan_akhirs','laporan_akhirs.usulan_id','usulans.id')
                                ->leftJoin('skims','skims.id','usulans.skim_id')
                                ->join('laporan_kemajuans','laporan_kemajuans.usulan_id','usulans.id')
                                ->leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                                ->select('usulans.id','judul_kegiatan',
                                        'ketua_peneliti_nama as nm_ketua_peneliti','tahun_usulan','nm_skim','file_usulan','file_kemajuan','file_akhir','tahun_usulan','jenis_kegiatan',
                                        DB::raw('group_concat(distinct concat(anggota_usulans.anggota_nama) SEPARATOR "<br>") as "nm_anggota" '),
                                        )
                                ->whereNotNull('file_akhir')
                                ->groupBy('usulans.id')
                                ->get();
        return view('operator/laporan_akhir.index',compact('usulans'));
    }
}
