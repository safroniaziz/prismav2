<?php

namespace App\Http\Controllers\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;
use App\AnggotaUsulan;
use App\Reviewer1;
use App\Reviewer2;
use App\Usulan;
use App\Fakultas;
use App\Prodi;
use App\RancanganAnggaran;
use PDF;

if(version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}
class UsulanProsesReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $penelitians = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                            ->leftJoin('skims','skims.id','usulans.skim_id')
                            ->leftJoin('reviewer1s','reviewer1s.usulan_id','usulans.id')
                            ->leftJoin('reviewers','reviewers.nip','reviewer1s.reviewer_nip')
                            ->select('usulans.id','judul_kegiatan','jenis_kegiatan',
                                    'abstrak','kata_kunci','file_usulan','nm_skim','biaya_diusulkan','usulans.created_at','tahun_usulan','status_usulan','ketua_peneliti_prodi_nama','ketua_peneliti_nama as nm_ketua_peneliti',
                                    DB::raw('group_concat(distinct concat(anggota_usulans.anggota_nama) SEPARATOR "<br>") as "nm_anggota" '),
                                    DB::raw('group_concat(distinct concat(reviewers.nama) SEPARATOR "<br>") as "nm_reviewer" '),
                                    )
                            ->where('usulans.status_usulan','1')
                            ->where('usulans.jenis_kegiatan','penelitian')
                            ->groupBy('usulans.id')
                            ->get();
        $pengabdians = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                            ->leftJoin('skims','skims.id','usulans.skim_id')
                            ->leftJoin('reviewer1s','reviewer1s.usulan_id','usulans.id')
                            ->leftJoin('reviewers','reviewers.nip','reviewer1s.reviewer_nip')
                            ->select('usulans.id','judul_kegiatan','jenis_kegiatan',
                                    'abstrak','kata_kunci','file_usulan','nm_skim','biaya_diusulkan','usulans.created_at','tahun_usulan','status_usulan','ketua_peneliti_prodi_nama','ketua_peneliti_nama as nm_ketua_peneliti',
                                    DB::raw('group_concat(distinct concat(anggota_usulans.anggota_nama) SEPARATOR "<br>") as "nm_anggota" '),
                                    DB::raw('group_concat(distinct concat(reviewers.nama) SEPARATOR "<br>") as "nm_reviewer" '),
                                    )
                            ->where('usulans.status_usulan','1')
                            ->where('usulans.jenis_kegiatan','pengabdian')
                            ->groupBy('usulans.id')
                            ->get();
                            // return $pengabdians;
        $fakultas = Fakultas::select('fakultas_kode','nm_fakultas')->get();
        return view('operator/usulan/proses_review.index',compact('penelitians','pengabdians','fakultas'));
    }

    public function detail($id){
        $detail = Usulan::leftJoin('skims','skims.id','usulans.skim_id')
                        ->select('usulans.id','judul_kegiatan','jenis_kegiatan','nm_skim','usulans.created_at','tujuan','luaran',
                        'ketua_peneliti_nama','ketua_peneliti_nip','abstrak','file_usulan','kata_kunci','biaya_diusulkan','status_usulan','tahun_usulan')
                        ->where('usulans.id',$id)
                        ->first();
        $anggota_internal = Usulan::join('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                                    ->select('anggota_nip','anggota_nama','anggota_prodi_nama','anggota_fakultas_nama','anggota_universitas')
                                    ->groupBy('anggota_usulans.id')
                                    ->where('usulans.id',$id)
                                    ->get();
        $anggota_eksternal = Usulan::join('anggota_eksternals','anggota_eksternals.usulan_id','usulans.id')
                                    ->select('anggota_nip','anggota_nama','anggota_universitas')
                                    ->groupBy('anggota_eksternals.id')
                                    ->where('usulans.id',$id)
                                    ->get();
        $anggota_mahasiswa = Usulan::join('anggota_mahasiswas','anggota_mahasiswas.usulan_id','usulans.id')
                                    ->select('anggota_npm','anggota_nama','anggota_prodi_nama','anggota_fakultas_nama')
                                    ->groupBy('anggota_mahasiswas.id')
                                    ->where('usulans.id',$id)
                                    ->get();
        $anggota_alumni = Usulan::join('anggota_alumnis','anggota_alumnis.usulan_id','usulans.id')
                                    ->select('anggota_nama','jabatan')
                                    ->groupBy('anggota_alumnis.id')
                                    ->where('usulans.id',$id)
                                    ->get();
        return view('operator/usulan/proses_review.detail_penelitian',compact('detail','anggota_internal','anggota_eksternal','anggota_mahasiswa','anggota_alumni'));
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

    public function detailReviewer($id){
        $reviewers = Reviewer1::join('usulans','usulans.id','reviewer1s.usulan_id')
                                ->join('reviewers','reviewers.nip','reviewer1s.reviewer_nip')
                                ->select('reviewer1s.id','reviewer_nip','nama','prodi_nama','fakultas_nama','judul_kegiatan')
                                ->where('usulans.id',$id)
                                ->where('reviewer1s.jenis_reviewer','internal')
                                ->groupBy('reviewer1s.id')
                                ->get();
        $reviewer_eksternals = Reviewer1::join('usulans','usulans.id','reviewer1s.usulan_id')
                                ->join('reviewers','reviewers.nip','reviewer1s.reviewer_nip')
                                ->select('reviewer1s.id','reviewer_nip','nama','universitas','jabatan_fungsional')
                                ->where('usulans.id',$id)
                                ->where('reviewer1s.jenis_reviewer','eksternal')
                                ->get();
        $id_usulan = $id;
        $a = count($reviewers);
        $b = count($reviewer_eksternals);
        $jumlah = $a+$b;
        $judul_kegiatan = Usulan::select('judul_kegiatan')->where('id',$id)->first();
        return view('operator/usulan/proses_review.reviewer',compact('reviewers','reviewer_eksternals','id_usulan','judul_kegiatan','jumlah'));
    }

    public function cariReviewer(Request $request){
        $panda = new UserLoginController();
        $dosen = '
        {pegawai(pegNip:"'.$request->nip_reviewer.'") {
            pegNip
            pegIsAktif
            pegNama
            pegawai_simpeg {
                pegJenkel
                pegNmJabatan
            }
            dosen {
              prodi {
                prodiKode
                prodiNamaResmi
                fakultas {
                  fakKode
                  fakKodeUniv
                  fakNamaResmi
                }
              }
            }
          }}
        ';
        $dosens = $panda->panda($dosen);
        $datas = count($dosens['pegawai']);
        $data = [
            'jumlah'    =>  $datas,
            'detail'    =>  $dosens,
        ];
        if($data['jumlah'] == 1){
            return response()->json($data);
        }
    }

    public function reviewerPost(Request $request){
        $sudah = AnggotaUsulan::select('anggota_nip')->where('usulan_id',$request->usulan_id_reviewer)->where('anggota_nip',$request->nip_reviewer)->first();
        $sudah2 = Reviewer1::select('reviewer_nip')->where('usulan_id',$request->usulan_id_reviewer)->where('reviewer_nip',$request->nip_reviewer)->first();
        $ketua = Usulan::select('ketua_peneliti_nip')->where('id',$request->usulan_id_reviewer)->where('ketua_peneliti_nip',$request->nip_reviewer)->first();
        if (count($sudah) != 0) {
            return redirect()->route('operator.proses_review.detail_reviewer',[$request->usulan_id_reviewer])->with(['error' =>  'reviewer yang dipilih adalah anggota kegiatan, tidak dapat ditambahkan !']);
        }
        else{
            if(count($sudah2) != 0){
                return redirect()->route('operator.proses_review.detail_reviewer',[$request->usulan_id_reviewer])->with(['error' =>  'reviewer yang dipilih gagal ditambahkan !']);
            }
            if(count($ketua) != 0){
                return redirect()->route('operator.proses_review.detail_reviewer',[$request->usulan_id_reviewer])->with(['error' =>  'reviewer yang dipilih adalah ketua usulan kegiatan, tidak dapat ditambahkan !']);
            }
            else{
                $reviewer = new Reviewer1;
                $reviewer->usulan_id = $request->usulan_id_reviewer;
                $reviewer->reviewer_nip = $request->nip_reviewer;
                $reviewer->reviewer_nama = $request->nm_reviewer;
                $reviewer->reviewer_prodi_id = $request->prodi_kode_reviewer;
                $reviewer->reviewer_prodi_nama = $request->prodi_reviewer;
                $reviewer->reviewer_fakultas_id = $request->fakultas_kode_reviewer;
                $reviewer->reviewer_fakultas_nama = $request->fakultas_reviewer;
                $reviewer->reviewer_jabatan_fungsional = $request->jabatan_reviewer;
                $reviewer->reviewer_jk = $request->jk_reviewer;
                $reviewer->reviewer_universitas = "Universitas Bengkulu";
                $reviewer->jenis_reviewer = "internal";
                $reviewer->save();

                $reviewer = new Reviewer2;
                $reviewer->usulan_id = $request->usulan_id_reviewer;
                $reviewer->reviewer_nip = $request->nip_reviewer;
                $reviewer->reviewer_nama = $request->nm_reviewer;
                $reviewer->reviewer_prodi_id = $request->prodi_kode_reviewer;
                $reviewer->reviewer_prodi_nama = $request->prodi_reviewer;
                $reviewer->reviewer_fakultas_id = $request->fakultas_kode_reviewer;
                $reviewer->reviewer_fakultas_nama = $request->fakultas_reviewer;
                $reviewer->reviewer_jabatan_fungsional = $request->jabatan_reviewer;
                $reviewer->reviewer_jk = $request->jk_reviewer;
                $reviewer->reviewer_universitas = "Universitas Bengkulu";
                $reviewer->jenis_reviewer = "internal";
                $reviewer->save();
                return redirect()->route('operator.proses_review.detail_reviewer',[$request->usulan_id_reviewer])->with(['success' =>  'Reviewer berhasil ditambahkan !']);
            }
        }
    }

    public function reviewerEksternalPost(Request $request){
        $sudah = AnggotaUsulan::select('anggota_nip')->where('usulan_id',$request->usulan_id_reviewer_eksternal)->where('anggota_nip',$request->nip_reviewer)->first();
        $sudah2 = Reviewer1::select('reviewer_nip')->where('usulan_id',$request->usulan_id_reviewer_eksternal)->where('reviewer_nip',$request->nip_reviewer)->first();
        $ketua = Usulan::select('ketua_peneliti_nip')->where('id',$request->usulan_id_reviewer_eksternal)->where('ketua_peneliti_nip',$request->nip_reviewer)->first();
        if (count($sudah) != 0) {
            return redirect()->route('operator.proses_review.detail_reviewer',[$request->usulan_id_reviewer_eksternal])->with(['error' =>  'reviewer yang dipilih adalah anggota kegiatan, tidak dapat ditambahkan !']);
        }
        else{
            if(count($sudah2) != 0){
                return redirect()->route('operator.proses_review.detail_reviewer',[$request->usulan_id_reviewer_eksternal])->with(['error' =>  'reviewer yang dipilih gagal ditambahkan !']);
            }
            if(count($ketua) != 0){
                return redirect()->route('operator.proses_review.detail_reviewer',[$request->usulan_id_reviewer_eksternal])->with(['error' =>  'reviewer yang dipilih adalah ketua usulan kegiatan, tidak dapat ditambahkan !']);
            }
            else{
                $reviewer = new Reviewer1;
                $reviewer->usulan_id = $request->usulan_id_reviewer_eksternal;
                $reviewer->reviewer_nip = $request->nip_reviewer;
                $reviewer->reviewer_nama = $request->nm_reviewer;
                $reviewer->ketua_peneliti_nidn = $request->nidn_reviewer;
                // $reviewer->reviewer_prodi_id = $request->prodi_kode_reviewer;
                // $reviewer->reviewer_prodi_nama = $request->prodi_reviewer;
                // $reviewer->reviewer_fakultas_id = $request->fakultas_kode_reviewer;
                // $reviewer->reviewer_fakultas_nama = $request->fakultas_reviewer;
                // $reviewer->reviewer_jabatan_fungsional = $request->jabatan_reviewer;
                // $reviewer->reviewer_jk = $request->jk_reviewer;
                $reviewer->reviewer_universitas = $request->universitas;
                $reviewer->jenis_reviewer = "eksternal";
                $reviewer->password = bcrypt($request->password);
                $reviewer->save();

                $reviewer = new Reviewer2;
                $reviewer->usulan_id = $request->usulan_id_reviewer_eksternal;
                $reviewer->reviewer_nip = $request->nip_reviewer;
                $reviewer->reviewer_nama = $request->nm_reviewer;
                $reviewer->ketua_peneliti_nidn = $request->nidn_reviewer;
                // $reviewer->reviewer_prodi_id = $request->prodi_kode_reviewer;
                // $reviewer->reviewer_prodi_nama = $request->prodi_reviewer;
                // $reviewer->reviewer_fakultas_id = $request->fakultas_kode_reviewer;
                // $reviewer->reviewer_fakultas_nama = $request->fakultas_reviewer;
                // $reviewer->reviewer_jabatan_fungsional = $request->jabatan_reviewer;
                // $reviewer->reviewer_jk = $request->jk_reviewer;
                $reviewer->reviewer_universitas = $request->universitas;
                $reviewer->jenis_reviewer = "eksternal";
                $reviewer->save();
                return redirect()->route('operator.proses_review.detail_reviewer',[$request->usulan_id_reviewer_eksternal])->with(['success' =>  'Reviewer Eksternal berhasil ditambahkan !']);
            }
        }
    }

    public function hapusReviewer(Request $request){
        $anggota = Reviewer1::find($request->id);
        $anggota->delete();

        return redirect()->route('operator.proses_review.detail_reviewer',[$request->id_usulan])->with(['success' =>  'Reviewer usulan kegiatan berhasil dihapus !']);
    }

    public function hapusReviewerEksternal(Request $request){
        $anggota = Reviewer1::find($request->id);
        $anggota->delete();

        return redirect()->route('operator.proses_review.detail_reviewer',[$request->id_usulan])->with(['success' =>  'Reviewer eksternal usulan kegiatan berhasil dihapus !']);
    }

    public function cetak(){
        $penelitians = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                            ->leftJoin('skims','skims.id','usulans.skim_id')
                            ->select('usulans.id','judul_kegiatan','jenis_kegiatan',
                                    'abstrak','kata_kunci','nm_skim','peta_jalan','file_usulan','lembar_pengesahan','biaya_diusulkan','status_usulan','tahun_usulan','ketua_peneliti_prodi_nama','ketua_peneliti_nama as nm_ketua_peneliti',
                                    DB::raw('group_concat(distinct concat(anggota_usulans.anggota_nama) SEPARATOR "<br>") as "nm_anggota" ')
                                    )
                            ->where('usulans.status_usulan','1')
                            ->where('usulans.jenis_kegiatan','penelitian')
                            ->groupBy('usulans.id')
                            ->orderBy('skims.id')
                            ->get();
                            // return $penelitians;
        return view('operator/usulan.proses_review.detail',compact('penelitians'));
    }

    public function cetakPengabdian(){
        $pengabdians = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                            ->leftJoin('skims','skims.id','usulans.skim_id')
                            ->select('usulans.id','judul_kegiatan','jenis_kegiatan',
                                    'abstrak','kata_kunci','nm_skim','peta_jalan','file_usulan','lembar_pengesahan','biaya_diusulkan','status_usulan','tahun_usulan','ketua_peneliti_prodi_nama','ketua_peneliti_nama as nm_ketua_peneliti',
                                    DB::raw('group_concat(distinct concat(anggota_usulans.anggota_nama) SEPARATOR "<br>") as "nm_anggota" ')
                                    )
                            ->where('usulans.status_usulan','1')
                            ->where('usulans.jenis_kegiatan','pengabdian')
                            ->groupBy('usulans.id')
                            ->orderBy('skims.id')
                            ->get();
        return view('operator/usulan.proses_review.detail_pengabdian',compact('pengabdians'));
    }
}
