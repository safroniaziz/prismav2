<?php

namespace App\Http\Controllers\Operator;

use App\BidangPenelitian;
use App\Formulir;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LaporanAkhir;
use App\Skim;
use App\Usulan;
use DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        // $data = [
        //     'usulan1'    =>  $usulan1,
        //     'usulan2'    =>  $usulan2,
        // ];
        // return $data;
        // $len = Count($data['usulan1']);
        // $totals = [];
        // for ($i=0; $i <$len ; $i++) {
        //     $totals[] = [
        //         'fakultas'  => $data['usulan1'][$i]->ketua_peneliti_fakultas_nama,
        //         'total' => $data['usulan1'][$i]->jumlah_usulan + $data['usulan2'][$i]->jumlah_usulan
        //     ];
        // }
        $penelitians = Usulan::join('skims','skims.id','usulans.skim_id')->select('nm_skim',DB::raw('COUNT(jenis_kegiatan) as jumlah'))->where('jenis_kegiatan','penelitian')->groupBy('skim_id')->get();
        $pengabdians = Usulan::join('skims','skims.id','usulans.skim_id')->select('nm_skim',DB::raw('COUNT(jenis_kegiatan) as jumlah'))->where('jenis_kegiatan','pengabdian')->groupBy('skim_id')->get();
        $fa = Usulan::select('ketua_peneliti_fakultas_id as fakultas')->groupBy('ketua_peneliti_fakultas_id')->get();
        $array_penelitian = [];
        for ($i=0; $i <count($fa) ; $i++) {
            $unggulan = Usulan::select(DB::raw('COUNT(jenis_kegiatan) as jumlah'))->where('ketua_peneliti_fakultas_id',$fa[$i]->fakultas)->where('skim_id',10)->get();
            $pembinaan = Usulan::select(DB::raw('COUNT(jenis_kegiatan) as jumlah'))->where('ketua_peneliti_fakultas_id',$fa[$i]->fakultas)->where('skim_id',11)->get();
            $kolabnas = Usulan::select(DB::raw('COUNT(jenis_kegiatan) as jumlah'))->where('ketua_peneliti_fakultas_id',$fa[$i]->fakultas)->where('skim_id',12)->get();
            $kolabter = Usulan::select(DB::raw('COUNT(jenis_kegiatan) as jumlah'))->where('ketua_peneliti_fakultas_id',$fa[$i]->fakultas)->where('skim_id',13)->get();
            $pgb = Usulan::select(DB::raw('COUNT(jenis_kegiatan) as jumlah'))->where('ketua_peneliti_fakultas_id',$fa[$i]->fakultas)->where('skim_id',14)->get();
            if ($fa[$i]['fakultas'] == "EKONOMI DAN BISNIS") {
                $array_penelitian[] = [
                    'fakultas'  =>  "FEB",
                    'unggulan'  =>  $unggulan,
                    'pembinaan'  =>  $pembinaan,
                    'kolabnas'  =>  $kolabnas,
                    'kolabter'  =>  $kolabter,
                    'pgb'  =>  $pgb,
                ];
            }
            elseif ($fa[$i]['fakultas'] == "HUKUM") {
                $array_penelitian[] = [
                    'fakultas'  =>  "FH",
                    'unggulan'  =>  $unggulan,
                    'pembinaan'  =>  $pembinaan,
                    'kolabnas'  =>  $kolabnas,
                    'kolabter'  =>  $kolabter,
                    'pgb'  =>  $pgb,
                ];
            }
            elseif ($fa[$i]['fakultas'] == "ILMU SOSIAL DAN ILMU POLITIK") {
                $array_penelitian[] = [
                    'fakultas'  =>  "FISIP",
                    'unggulan'  =>  $unggulan,
                    'pembinaan'  =>  $pembinaan,
                    'kolabnas'  =>  $kolabnas,
                    'kolabter'  =>  $kolabter,
                    'pgb'  =>  $pgb,
                ];
            }
            elseif ($fa[$i]['fakultas'] == "KEDOKTERAN DAN ILMU KESEHATAN") {
                $array_penelitian[] = [
                    'fakultas'  =>  "FKIK",
                    'unggulan'  =>  $unggulan,
                    'pembinaan'  =>  $pembinaan,
                    'kolabnas'  =>  $kolabnas,
                    'kolabter'  =>  $kolabter,
                    'pgb'  =>  $pgb,
                ];
            }
            elseif ($fa[$i]['fakultas'] == "KEGURUAN DAN ILMU PENDIDIKAN") {
                $array_penelitian[] = [
                    'fakultas'  =>  "FKIP",
                    'unggulan'  =>  $unggulan,
                    'pembinaan'  =>  $pembinaan,
                    'kolabnas'  =>  $kolabnas,
                    'kolabter'  =>  $kolabter,
                    'pgb'  =>  $pgb,
                ];
            }
            elseif ($fa[$i]['fakultas'] == "MATEMATIKA DAN ILMU PENGETAHUAN ALAM") {
                $array_penelitian[] = [
                    'fakultas'  =>  "FMIPA",
                    'unggulan'  =>  $unggulan,
                    'pembinaan'  =>  $pembinaan,
                    'kolabnas'  =>  $kolabnas,
                    'kolabter'  =>  $kolabter,
                    'pgb'  =>  $pgb,
                ];
            }
            elseif ($fa[$i]['fakultas'] == "PERTANIAN") {
                $array_penelitian[] = [
                    'fakultas'  =>  "FP",
                    'unggulan'  =>  $unggulan,
                    'pembinaan'  =>  $pembinaan,
                    'kolabnas'  =>  $kolabnas,
                    'kolabter'  =>  $kolabter,
                    'pgb'  =>  $pgb,
                ];
            }
            elseif ($fa[$i]['fakultas'] == "TEKNIK") {
                $array_penelitian[] = [
                    'fakultas'  =>  "FT",
                    'unggulan'  =>  $unggulan,
                    'pembinaan'  =>  $pembinaan,
                    'kolabnas'  =>  $kolabnas,
                    'kolabter'  =>  $kolabter,
                    'pgb'  =>  $pgb,
                ];
            }
        }
        for ($i=0; $i <count($fa) ; $i++) {
            $ppm_pemb = Usulan::select(DB::raw('COUNT(jenis_kegiatan) as jumlah'))->where('ketua_peneliti_fakultas_id',$fa[$i]->fakultas)->where('skim_id',15)->get();
            $ppm_ipteks = Usulan::select(DB::raw('COUNT(jenis_kegiatan) as jumlah'))->where('ketua_peneliti_fakultas_id',$fa[$i]->fakultas)->where('skim_id',16)->get();
            $ppm_riset = Usulan::select(DB::raw('COUNT(jenis_kegiatan) as jumlah'))->where('ketua_peneliti_fakultas_id',$fa[$i]->fakultas)->where('skim_id',17)->get();
            if ($fa[$i]['fakultas'] == "EKONOMI DAN BISNIS") {
                $array_pembinaan[] = [
                    'fakultas'  =>  "FEB",
                    'ppm_pemb'  =>  $ppm_pemb,
                    'ppm_ipteks'  =>  $ppm_ipteks,
                    'ppm_riset'  =>  $ppm_riset,
                ];
            }
            elseif ($fa[$i]['fakultas'] == "HUKUM") {
                $array_pembinaan[] = [
                    'fakultas'  =>  "FH",
                    'ppm_pemb'  =>  $ppm_pemb,
                    'ppm_ipteks'  =>  $ppm_ipteks,
                    'ppm_riset'  =>  $ppm_riset,
                ];
            }
            elseif ($fa[$i]['fakultas'] == "ILMU SOSIAL DAN ILMU POLITIK") {
                $array_pembinaan[] = [
                    'fakultas'  =>  "FISIP",
                    'ppm_pemb'  =>  $ppm_pemb,
                    'ppm_ipteks'  =>  $ppm_ipteks,
                    'ppm_riset'  =>  $ppm_riset,
                ];
            }
            elseif ($fa[$i]['fakultas'] == "KEDOKTERAN DAN ILMU KESEHATAN") {
                $array_pembinaan[] = [
                    'fakultas'  =>  "FKIK",
                    'ppm_pemb'  =>  $ppm_pemb,
                    'ppm_ipteks'  =>  $ppm_ipteks,
                    'ppm_riset'  =>  $ppm_riset,
                ];
            }
            elseif ($fa[$i]['fakultas'] == "KEGURUAN DAN ILMU PENDIDIKAN") {
                $array_pembinaan[] = [
                    'fakultas'  =>  "FKIP",
                    'ppm_pemb'  =>  $ppm_pemb,
                    'ppm_ipteks'  =>  $ppm_ipteks,
                    'ppm_riset'  =>  $ppm_riset,
                ];
            }
            elseif ($fa[$i]['fakultas'] == "MATEMATIKA DAN ILMU PENGETAHUAN ALAM") {
                $array_pembinaan[] = [
                    'fakultas'  =>  "FMIPA",
                    'ppm_pemb'  =>  $ppm_pemb,
                    'ppm_ipteks'  =>  $ppm_ipteks,
                    'ppm_riset'  =>  $ppm_riset,
                ];
            }
            elseif ($fa[$i]['fakultas'] == "PERTANIAN") {
                $array_pembinaan[] = [
                    'fakultas'  =>  "FP",
                    'ppm_pemb'  =>  $ppm_pemb,
                    'ppm_ipteks'  =>  $ppm_ipteks,
                    'ppm_riset'  =>  $ppm_riset,
                ];
            }
            elseif ($fa[$i]['fakultas'] == "TEKNIK") {
                $array_pembinaan[] = [
                    'fakultas'  =>  "FT",
                    'ppm_pemb'  =>  $ppm_pemb,
                    'ppm_ipteks'  =>  $ppm_ipteks,
                    'ppm_riset'  =>  $ppm_riset,
                ];
            }
        }
        $skim = Count(Skim::all());
        $jumlah_usulan = Count(Usulan::all());
        $disetujui = Count(Usulan::where('status_usulan','3')->get());
        $kriteria = Count(Formulir::all());
        return view('operator/dashboard', compact('skim','jumlah_usulan','kriteria','disetujui','penelitians','pengabdians','array_penelitian','array_pembinaan'));
    }
}
