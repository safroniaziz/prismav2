<?php

namespace App\Http\Controllers\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserLoginController;
use DB;
use Session;
use App\Fakultas;
use App\Usulan;
use App\Reviewer2;
use App\Prodi;

class LaporanKemajuanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $usulans = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                                    ->leftJoin('reviewer2s','reviewer2s.usulan_id','usulans.id')
                                    ->join('laporan_kemajuans','laporan_kemajuans.usulan_id','usulans.id')
                                    ->select('usulans.id','judul_kegiatan','ketua_peneliti_nama','file_kemajuan',
                                    DB::raw('group_concat(distinct concat(anggota_usulans.anggota_nama) SEPARATOR "<br>") as "nm_anggota" '),
                                    DB::raw('group_concat(distinct concat(reviewer2s.reviewer_nama) SEPARATOR "&nbsp;|&nbsp;") as "nm_reviewer" ')
                                    )
                                    ->groupBy('usulans.id')
                                    ->get();
        $fakultas = Fakultas::select('fakultas_kode','nm_fakultas')->get();
        return view('operator/laporan_kemajuan/reviewer.index',compact('usulans','fakultas'));
    }

    public function getReviewer($id){
        $reviewer = Reviewer2::select('reviewer_nip as nip','reviewer_nama as nm_lengkap','reviewer_prodi_nama as prodi','reviewer_fakultas_nama as fakultas')
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

    public function cariProdi(Request $request){
        $fakultas_id = $request->fakultas_id;
        $prodi  =   Prodi::select('prodi_kode','nm_prodi')
                            ->where('fakultas_kode',$fakultas_id)
                            ->get();
        return $prodi;
    }

    public function cariReviewer(Request $request){
        $panda = new UserLoginController();
        $prodi_id = $request->prodi_id;
        $reviewers  =   '
            {prodi(prodiKode:'.$prodi_id.') {
                prodiKode
                dosen {
                dsnPegNip
                pegawai {
                    pegNama
                    pegIsAktif
                }
                }
            }}
        ';
        $reviewer = $panda->panda($reviewers);
        return $reviewer['prodi'][0]['dosen'];
    }

    public function reviewerPost(Request $request){
        $panda = new UserLoginController();
        $reviewers = '
            {dosen(dsnPegNip:"'.$request->reviewer_id.'"){
                dsnPegNip
                prodi {
                    prodiKode
                    prodiNamaResmi
                    fakultas {
                    fakKode
                    fakNamaResmi
                    }
                }
                pegawai {
                pegNama
                }
            }}
        ';
        $reviewer = $panda->panda($reviewers);
        $anggota = new Reviewer2;
        $anggota->usulan_id = $request->usulan_id;
        $anggota->reviewer_nip = $request->reviewer_id;
        $anggota->reviewer_nama = $reviewer['dosen'][0]['pegawai']['pegNama'];
        $anggota->reviewer_prodi_id = $reviewer['dosen'][0]['prodi']['prodiKode'];
        $anggota->reviewer_prodi_nama = $reviewer['dosen'][0]['prodi']['prodiNamaResmi'];
        $anggota->reviewer_fakultas_id = $reviewer['dosen'][0]['prodi']['fakultas']['fakKode'];
        $anggota->reviewer_fakultas_nama = $reviewer['dosen'][0]['prodi']['fakultas']['fakNamaResmi'];
        $anggota->save();

        return redirect()->route('operator.laporan_kemajuan')->with(['success' =>  'Reviewer berhasil ditambahkan !']);
    }
}
