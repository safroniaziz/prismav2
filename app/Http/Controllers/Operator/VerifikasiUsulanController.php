<?php

namespace App\Http\Controllers\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;
use App\Reviewer1;
use App\Usulan;
use App\Fakultas;
use App\Prodi;
use App\RancanganAnggaran;
use App\AnggotaUsulan;
use PDF;

class VerifikasiUsulanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $usulans = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                            ->leftJoin('skims','skims.id','usulans.skim_id')
                            ->leftJoin('bidang_penelitians','bidang_penelitians.id','usulans.bidang_id')
                            ->leftJoin('reviewer1s','reviewer1s.usulan_id','usulans.id')
                            ->select('usulans.id','judul_penelitian','nm_bidang as bidang_penelitian',
                                    'abstrak','kata_kunci','peta_jalan','biaya_diusulkan','status_usulan','ketua_peneliti_prodi_nama','ketua_peneliti_nama as nm_ketua_peneliti',
                                    DB::raw('group_concat(distinct concat(anggota_usulans.anggota_nama) SEPARATOR "<br>") as "nm_anggota" '),
                                    DB::raw('SUM(reviewer_nip) as jumlah')
                                    )
                            ->where('usulans.status_usulan','2')
                            ->groupBy('usulans.id')
                            ->get();
        return view('operator/usulan/verifikasi.index',compact('usulans'));
    }

    public function detail($id){
        $usulan = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                        ->leftJoin('skims','skims.id','usulans.skim_id')
                        ->leftJoin('bidang_penelitians','bidang_penelitians.id','usulans.bidang_id')
                        ->select('usulans.id','judul_penelitian','nm_bidang as bidang_penelitian','ketua_peneliti_fakultas_nama','ketua_peneliti_prodi_nama',
                                'ketua_peneliti_nama as nm_ketua_peneliti','ketua_peneliti_nip as nip','kata_kunci','nm_skim','abstrak','kata_kunci','peta_jalan','biaya_diusulkan','tahun_usulan')
                        ->where('usulans.id',$id)
                        ->first();
        $anggotas = AnggotaUsulan::select('anggota_nama as nm_anggota','anggota_prodi_nama','anggota_fakultas_nama','anggota_nip')
                                    ->where('usulan_id',$id)
                                    ->get();
        $reviewers = Reviewer1::select('reviewer_nama as nm_anggota','reviewer_prodi_nama','reviewer_fakultas_nama','reviewer_nip')
                                    ->where('usulan_id',$id)
                                    ->get();
        $data = [
            'usulan'        => $usulan,
            'anggotas'      => $anggotas,
            'reviewers'      => $reviewers,
        ];
        return $data;
    }

    public function getReviewer($id){
        $reviewer = Reviewer1::select('reviewer_nip as nip','reviewer_nama as nm_lengkap','reviewer_prodi_nama as prodi','reviewer_fakultas_nama as fakultas')
                                ->where('usulan_id',$id)
                                ->groupBy('reviewer_nip')
                                ->get();
        $usulan = Usulan::select('judul_penelitian')->where('id',$id)->first();
        $data = [
            'reviewers'    =>  $reviewer,
            'usulan'    =>  $usulan,
        ];
        Session::put('usulan_id',$id);
        return $data;
    }

    public function anggaranCetak($id){
        $outputs = RancanganAnggaran::leftJoin('anggaran_honor_outputs','anggaran_honor_outputs.rancangan_anggaran_id','rancangan_anggarans.id')
                                    ->where('usulan_id',$id)
                                    ->get();
        $habis_pakais = RancanganAnggaran::leftJoin('anggaran_bahan_habis_pakais','anggaran_bahan_habis_pakais.rancangan_anggaran_id','rancangan_anggarans.id')
                                    ->where('usulan_id',$id)
                                    ->get();
        $penunjangs = RancanganAnggaran::leftJoin('anggaran_peralatan_penunjangs','anggaran_peralatan_penunjangs.rancangan_anggaran_id','rancangan_anggarans.id')
                                    ->where('usulan_id',$id)
                                    ->get();
        $lainnya = RancanganAnggaran::leftJoin('anggaran_perjalanan_lainnyas','anggaran_perjalanan_lainnyas.rancangan_anggaran_id','rancangan_anggarans.id')
                                    ->where('usulan_id',$id)
                                    ->get();
        $pdf = PDF::loadView('operator/usulan.menunggu_disetujui.cetak',compact('outputs','habis_pakais','penunjangs','lainnya'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream();
    }

    public function verifikasi(Request $request){
        $ids = $request->get('ids');
        $status = '3';
        $dbs = Usulan::whereIn('id', explode(',', $ids))->update([
            'status_usulan' => $status,
        ]);
        return $dbs;

        return redirect()->route('operator.verifikasi')->with(['success'    =>  'Usulan Penelitian berhasil diverifikasi !!']);
    }
}
