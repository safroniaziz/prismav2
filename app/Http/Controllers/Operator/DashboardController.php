<?php

namespace App\Http\Controllers\Operator;

use App\BidangPenelitian;
use App\Formulir;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LaporanAkhir;
use App\Skim;
use DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $usulan1 = LaporanAkhir::join('usulans','usulans.id','laporan_akhirs.usulan_id')
                                    ->select('ketua_peneliti_fakultas_nama',DB::raw('COUNT(usulans.id) as jumlah_usulan'))
                                    ->groupBy('usulans.ketua_peneliti_fakultas_id')
                                    ->orderBy('usulans.ketua_peneliti_fakultas_id','asc')
                                    ->get();
        $usulan2 = LaporanAkhir::join('usulans','usulans.id','laporan_akhirs.usulan_id')
                                    ->join('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                                    ->select('anggota_fakultas_nama',DB::raw('COUNT(usulans.id) as jumlah_usulan'))
                                    ->groupBy('anggota_usulans.anggota_fakultas_id')
                                    ->orderBy('anggota_usulans.anggota_fakultas_id','asc')
                                    ->get();
        $data = [
            'usulan1'    =>  $usulan1,
            'usulan2'    =>  $usulan2,
        ];
        // return $data;
        $len = Count($data['usulan1']);
        $totals = [];
        for ($i=0; $i <$len ; $i++) {
            $totals[] = [
                'fakultas'  => $data['usulan1'][$i]->ketua_peneliti_fakultas_nama,
                'total' => $data['usulan1'][$i]->jumlah_usulan + $data['usulan2'][$i]->jumlah_usulan
            ];
        }
        $skim = Count(Skim::all());
        $bidang = Count(BidangPenelitian::all());
        $kriteria = Count(Formulir::all());
        $usulan = Count(LaporanAkhir::all());
        return view('operator/dashboard', compact('totals','skim','bidang','kriteria','usulan'));
    }
}
