<?php

namespace App\Http\Controllers\Operator;

use App\AnggotaUsulan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserLoginController;
use DB;
use Session;
use App\Fakultas;
use App\Usulan;
use App\Reviewer2;
use App\Prodi;

if(version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}
class LaporanKemajuanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $panda = new UserLoginController();
        $penelitians = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                                    ->leftJoin('skims','skims.id','usulans.skim_id')
                                    ->join('laporan_kemajuans','laporan_kemajuans.usulan_id','usulans.id')
                                    ->select('usulans.id','judul_kegiatan','ketua_peneliti_nama','usulans.created_at','nm_skim','tahun_usulan','file_kemajuan','file_perbaikan','status_usulan','jenis_kegiatan','tahun_usulan',
                                    DB::raw('group_concat(distinct concat(anggota_usulans.anggota_nama) SEPARATOR "<br>") as "nm_anggota" ')
                                    )
                                    ->groupBy('usulans.id')
                                    ->whereNotNull('file_kemajuan')
                                    ->where('jenis_kegiatan','penelitian')
                                    ->get();
        $pengabdians = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                                    ->leftJoin('skims','skims.id','usulans.skim_id')
                                    ->join('laporan_kemajuans','laporan_kemajuans.usulan_id','usulans.id')
                                    ->select('usulans.id','judul_kegiatan','ketua_peneliti_nama','usulans.created_at','nm_skim','tahun_usulan','file_kemajuan','file_perbaikan','status_usulan','jenis_kegiatan','tahun_usulan',
                                    DB::raw('group_concat(distinct concat(anggota_usulans.anggota_nama) SEPARATOR "<br>") as "nm_anggota" ')
                                    )
                                    ->groupBy('usulans.id')
                                    ->whereNotNull('file_kemajuan')
                                    ->where('jenis_kegiatan','pengabdian')
                                    ->get();
        return view('operator/laporan_kemajuan/reviewer.index',compact('penelitians','pengabdians','fakultas','dosens'));
    }

    public function detailJudul($id){
        $judul = Usulan::find($id);
        return $judul;
    }

    public function reviewerPost(Request $request){
        $sudah = AnggotaUsulan::select('anggota_nip')->where('usulan_id',$request->usulan_id_reviewer_eksternal)->where('anggota_nip',$request->nip_reviewer)->first();
        $sudah2 = Reviewer2::select('reviewer_nip')->where('usulan_id',$request->usulan_id_reviewer_eksternal)->where('reviewer_nip',$request->nip_reviewer)->first();
        $ketua = Usulan::select('ketua_peneliti_nip')->where('id',$request->usulan_id_reviewer_eksternal)->where('ketua_peneliti_nip',$request->nip_reviewer)->first();
        if (count($sudah) != 0) {
            return redirect()->route('operator.laporan_kemajuan.detail_reviewer',[$request->usulan_id_reviewer_eksternal])->with(['error' =>  'reviewer yang dipilih adalah anggota kegiatan, tidak dapat ditambahkan !']);
        }
        else{
            if(count($sudah2) != 0){
                return redirect()->route('operator.laporan_kemajuan.detail_reviewer',[$request->usulan_id_reviewer_eksternal])->with(['error' =>  'reviewer yang dipilih sudah ditambahkan !']);
            }
            if(count($ketua) != 0){
                return redirect()->route('operator.laporan_kemajuan.detail_reviewer',[$request->usulan_id_reviewer_eksternal])->with(['error' =>  'reviewer yang dipilih adalah ketua usulan kegiatan, tidak dapat ditambahkan !']);
            }
            else{
                $reviewer = new Reviewer2;
                $reviewer->usulan_id = $request->usulan_id_reviewer_eksternal;
                $reviewer->reviewer_nip = $request->nip_reviewer;
                $reviewer->reviewer_nama = $request->nm_reviewer;
                $reviewer->reviewer_prodi_id = $request->prodi_kode_reviewer;
                $reviewer->reviewer_prodi_nama = $request->prodi_reviewer;
                $reviewer->reviewer_fakultas_id = $request->fakultas_kode_reviewer;
                $reviewer->reviewer_fakultas_nama = $request->fakultas_reviewer;
                $reviewer->reviewer_jabatan_fungsional = $request->jabatan_reviewer;
                $reviewer->reviewer_jk = $request->jk_reviewer;
                $reviewer->reviewer_universitas = "Universitas Bengkulu";
                $reviewer->save();
                return redirect()->route('operator.laporan_kemajuan.detail_reviewer',[$request->usulan_id_reviewer])->with(['success' =>  'Reviewer berhasil ditambahkan !']);
            }
        }
    }

    public function reviewerEksternalPost(Request $request){
        $sudah = AnggotaUsulan::select('anggota_nip')->where('anggota_nip',$request->nip_reviewer)->first();
        $sudah2 = Reviewer2::select('reviewer_nip')->where('reviewer_nip',$request->nip_reviewer)->first();
        $ketua = Usulan::select('ketua_peneliti_nip')->where('ketua_peneliti_nip',$request->nip_reviewer)->first();
        if (count($sudah) != 0) {
            return redirect()->route('operator.laporan_kemajuan.detail_reviewer',[$request->usulan_id_reviewer_eksternal])->with(['error' =>  'reviewer yang dipilih adalah anggota kegiatan, tidak dapat ditambahkan !']);
        }
        else{
            if(count($sudah2) != 0){
                return redirect()->route('operator.laporan_kemajuan.detail_reviewer',[$request->usulan_id_reviewer_eksternal])->with(['error' =>  'reviewer yang dipilih sudah ditambahkan !']);
            }
            if(count($ketua) != 0){
                return redirect()->route('operator.laporan_kemajuan.detail_reviewer',[$request->usulan_id_reviewer_eksternal])->with(['error' =>  'reviewer yang dipilih adalah ketua usulan kegiatan, tidak dapat ditambahkan !']);
            }
            else{
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
                $reviewer->password = bcrypt($request->password);
                $reviewer->save();
                return redirect()->route('operator.laporan_kemajuan.detail_reviewer',[$request->usulan_id_reviewer_eksternal])->with(['success' =>  'Reviewer eksternal berhasil ditambahkan !']);
            }
        }
    }

    public function hapusReviewer(Request $request){
        $anggota = Reviewer2::find($request->id);
        $anggota->delete();

        return redirect()->route('operator.laporan_kemajuan.detail_reviewer',[$request->id_usulan])->with(['success' =>  'Reviewer usulan kegiatan berhasil dihapus !']);
    }

    public function hapusReviewerEksternal(Request $request){
        $anggota = Reviewer2::find($request->id);
        $anggota->delete();

        return redirect()->route('operator.laporan_kemajuan.detail_reviewer',[$request->id_usulan])->with(['success' =>  'Reviewer eksternal usulan kegiatan berhasil dihapus !']);
    }

    public function detailReviewer($id){
        $reviewers = Reviewer2::join('usulans','usulans.id','reviewer2s.usulan_id')
                                    ->select('reviewer2s.id','reviewer_nip','reviewer_nama','reviewer_prodi_nama','reviewer_fakultas_nama','judul_kegiatan')
                                    ->where('usulans.id',$id)
                                    ->where('jenis_reviewer','internal')
                                    ->get();
        $reviewer_eksternals = Reviewer2::join('usulans','usulans.id','reviewer2s.usulan_id')
                                    ->select('reviewer2s.id','reviewer_nip','reviewer_nama','reviewer2s.ketua_peneliti_nidn','reviewer_universitas','judul_kegiatan')
                                    ->where('usulans.id',$id)
                                    ->where('jenis_reviewer','eksternal')
                                    ->get();
        $id_usulan = $id;
        $a = count($reviewers);
        $b = count($reviewer_eksternals);
        $jumlah = $a+$b;
        $judul_kegiatan = Usulan::select('judul_kegiatan')->where('id',$id)->first();
        return view('operator/laporan_kemajuan/reviewer.reviewer',compact('reviewers','reviewer_eksternals','id_usulan','judul_kegiatan','jumlah'));
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
}
