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
use PDF;

class LaporanKemajuanProsesReview extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $penelitians = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                            ->leftJoin('reviewer2s','reviewer2s.usulan_id','usulans.id')
                            ->leftJoin('laporan_kemajuans','laporan_kemajuans.usulan_id','usulans.id')
                            ->select('usulans.id','judul_kegiatan','file_kemajuan','jenis_kegiatan','tahun_usulan',
                                    'ketua_peneliti_nama as nm_ketua_peneliti',
                                    DB::raw('group_concat(distinct concat(anggota_usulans.anggota_nama) SEPARATOR "<br>") as "nm_anggota" '),
                                    DB::raw('group_concat(distinct concat(reviewer2s.reviewer_nama) SEPARATOR "&nbsp;|&nbsp;") as "nm_reviewer" ')
                                    )
                            ->where('usulans.status_usulan','3')
                            ->where('jenis_kegiatan','penelitian')
                            ->groupBy('usulans.id')
                            ->get();
        $pengabdians = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                            ->leftJoin('reviewer2s','reviewer2s.usulan_id','usulans.id')
                            ->leftJoin('laporan_kemajuans','laporan_kemajuans.usulan_id','usulans.id')
                            ->select('usulans.id','judul_kegiatan','file_kemajuan','jenis_kegiatan','tahun_usulan',
                                    'ketua_peneliti_nama as nm_ketua_peneliti',
                                    DB::raw('group_concat(distinct concat(anggota_usulans.anggota_nama) SEPARATOR "<br>") as "nm_anggota" '),
                                    DB::raw('group_concat(distinct concat(reviewer2s.reviewer_nama) SEPARATOR "&nbsp;|&nbsp;") as "nm_reviewer" ')
                                    )
                            ->where('usulans.status_usulan','3')
                            ->where('jenis_kegiatan','pengabdian')
                            ->groupBy('usulans.id')
                            ->get();
        $fakultas = Fakultas::select('fakultas_kode','nm_fakultas')->get();
        return view('operator/laporan_kemajuan/proses_review.index',compact('penelitians','pengabdians','fakultas'));
    }


    public function getReviewer($id){
        $reviewer = Reviewer1::select('reviewer_nip as nip','reviewer_nama as nm_lengkap','reviewer_prodi_nama as prodi','reviewer_fakultas_nama as fakultas')
                                ->where('usulan_id',$id)
                                ->groupBy('reviewer_nip')
                                ->get();
        $usulan = Usulan::select('judul_kegiatan')->where('id',$id)->first();
        $data = [
            'reviewers'    =>  $reviewer,
            'usulan'    =>  $usulan,
        ];
        Session::put('usulan_id',$id);
        return $data;
    }
}
