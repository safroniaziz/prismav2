<?php

namespace App\Http\Controllers\Operator;

use App\AnggotaUsulan;
use App\Dosen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserLoginController;
use DB;
use Session;
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
class UsulanMenungguController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $panda = new UserLoginController();
        $penelitians = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                            ->leftJoin('skims','skims.id','usulans.skim_id')
                            ->leftJoin('reviewer1s','reviewer1s.usulan_id','usulans.id')
                            ->select('usulans.id','judul_kegiatan','jenis_kegiatan',
                                    'abstrak','kata_kunci','peta_jalan','file_usulan','lembar_pengesahan','biaya_diusulkan','status_usulan','tahun_usulan','ketua_peneliti_prodi_nama','ketua_peneliti_nama as nm_ketua_peneliti',
                                    DB::raw('group_concat(distinct concat(anggota_usulans.anggota_nama) SEPARATOR "<br>") as "nm_anggota" '),
                                    DB::raw('group_concat(distinct concat(reviewer1s.reviewer_nama) SEPARATOR "&nbsp;|&nbsp;") as "nm_reviewer" '),
                                    DB::raw('SUM(reviewer_nip) as jumlah')
                                    )
                            ->where('usulans.status_usulan','1')
                            ->where('usulans.jenis_kegiatan','penelitian')
                            ->groupBy('usulans.id')
                            ->get();
        $pengabdians = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                            ->leftJoin('skims','skims.id','usulans.skim_id')
                            ->leftJoin('reviewer1s','reviewer1s.usulan_id','usulans.id')
                            ->select('usulans.id','judul_kegiatan','jenis_kegiatan',
                                    'abstrak','kata_kunci','peta_jalan','file_usulan','lembar_pengesahan','biaya_diusulkan','status_usulan','tahun_usulan','ketua_peneliti_prodi_nama','ketua_peneliti_nama as nm_ketua_peneliti',
                                    DB::raw('group_concat(distinct concat(anggota_usulans.anggota_nama) SEPARATOR "<br>") as "nm_anggota" '),
                                    DB::raw('group_concat(distinct concat(reviewer1s.reviewer_nama) SEPARATOR "&nbsp;|&nbsp;") as "nm_reviewer" '),
                                    DB::raw('SUM(reviewer_nip) as jumlah')
                                    )
                            ->where('usulans.status_usulan','1')
                            ->where('usulans.jenis_kegiatan','pengabdian')
                            ->groupBy('usulans.id')
                            ->get();
        return view('operator/usulan/menunggu_disetujui.index',compact('penelitians','pengabdians'));
    }

    public function detail($id){
        $usulan = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                        ->leftJoin('skims','skims.id','usulans.skim_id')
                        ->select('usulans.id','judul_kegiatan','tujuan','luaran','abstrak','jenis_kegiatan','ketua_peneliti_fakultas_nama as fakultas','ketua_peneliti_prodi_nama as prodi',
                                'ketua_peneliti_nama as nm_ketua_peneliti','ketua_peneliti_nip as nip','kata_kunci','nm_skim','abstrak','kata_kunci')
                        ->where('usulans.id',$id)
                        ->first();
        $anggotas = AnggotaUsulan::select('anggota_nama as nm_anggota','anggota_prodi_nama as prodi','anggota_fakultas_nama as fakultas','anggota_nip as nip')
                                    ->where('usulan_id',$id)
                                    ->get();
        $data = [
            'usulan'        => $usulan,
            'anggotas'      => $anggotas,
        ];
        return $data;
    }

    public function reviewerPost(Request $request){
        $sudah = AnggotaUsulan::select('anggota_nip')->where('usulan_id',$request->usulan_id_reviewer)->where('anggota_nip',$request->nip_reviewer)->first();
        $sudah2 = Reviewer1::select('reviewer_nip')->where('usulan_id',$request->usulan_id_reviewer)->where('reviewer_nip',$request->nip_reviewer)->first();
        $ketua = Usulan::select('ketua_peneliti_nip')->where('id',$request->usulan_id_reviewer)->where('ketua_peneliti_nip',$request->nip_reviewer)->first();
        if (count($sudah) != 0) {
            return redirect()->route('operator.menunggu.detail_reviewer',[$request->usulan_id_reviewer])->with(['error' =>  'reviewer yang dipilih adalah anggota kegiatan, tidak dapat ditambahkan !']);
        }
        else{
            if(count($sudah2) != 0){
                return redirect()->route('operator.menunggu.detail_reviewer',[$request->usulan_id_reviewer])->with(['error' =>  'reviewer yang dipilih gagal ditambahkan !']);
            }
            if(count($ketua) != 0){
                return redirect()->route('operator.menunggu.detail_reviewer',[$request->usulan_id_reviewer])->with(['error' =>  'reviewer yang dipilih adalah ketua usulan kegiatan, tidak dapat ditambahkan !']);
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
                return redirect()->route('operator.menunggu.detail_reviewer',[$request->usulan_id_reviewer])->with(['success' =>  'Reviewer berhasil ditambahkan !']);
            }
        }
    }

    public function reviewerEksternalPost(Request $request){
        $sudah = AnggotaUsulan::select('anggota_nip')->where('usulan_id',$request->usulan_id_reviewer_eksternal)->where('anggota_nip',$request->nip_reviewer)->first();
        $sudah2 = Reviewer1::select('reviewer_nip')->where('usulan_id',$request->usulan_id_reviewer_eksternal)->where('reviewer_nip',$request->nip_reviewer)->first();
        $ketua = Usulan::select('ketua_peneliti_nip')->where('id',$request->usulan_id_reviewer_eksternal)->where('ketua_peneliti_nip',$request->nip_reviewer)->first();
        if (count($sudah) != 0) {
            return redirect()->route('operator.menunggu.detail_reviewer',[$request->usulan_id_reviewer_eksternal])->with(['error' =>  'reviewer yang dipilih adalah anggota kegiatan, tidak dapat ditambahkan !']);
        }
        else{
            if(count($sudah2) != 0){
                return redirect()->route('operator.menunggu.detail_reviewer',[$request->usulan_id_reviewer_eksternal])->with(['error' =>  'reviewer yang dipilih gagal ditambahkan !']);
            }
            if(count($ketua) != 0){
                return redirect()->route('operator.menunggu.detail_reviewer',[$request->usulan_id_reviewer_eksternal])->with(['error' =>  'reviewer yang dipilih adalah ketua usulan kegiatan, tidak dapat ditambahkan !']);
            }
            else{
                $reviewer = new Reviewer1;
                $reviewer->usulan_id = $request->usulan_id_reviewer_eksternal;
                $reviewer->reviewer_nip = $request->nip_reviewer;
                $reviewer->reviewer_nama = $request->nm_reviewer;
                $reviewer->reviewer_prodi_id = $request->prodi_kode_reviewer;
                $reviewer->reviewer_prodi_nama = $request->prodi_reviewer;
                $reviewer->reviewer_fakultas_id = $request->fakultas_kode_reviewer;
                $reviewer->reviewer_fakultas_nama = $request->fakultas_reviewer;
                $reviewer->reviewer_jabatan_fungsional = $request->jabatan_reviewer;
                $reviewer->reviewer_jk = $request->jk_reviewer;
                $reviewer->reviewer_universitas = $request->universitas;
                $reviewer->jenis_reviewer = "eksternal";
                $reviewer->password = bcrypt($request->password);
                $reviewer->save();

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
                $reviewer->reviewer_universitas = $request->universitas;
                $reviewer->jenis_reviewer = "eksternal";
                $reviewer->save();
                return redirect()->route('operator.menunggu.detail_reviewer',[$request->usulan_id_reviewer_eksternal])->with(['success' =>  'Reviewer Eksternal berhasil ditambahkan !']);
            }
        }
    }

    public function hapusReviewer(Request $request){
        $anggota = Reviewer1::find($request->id);
        $anggota->delete();

        return redirect()->route('operator.menunggu.detail_reviewer',[$request->id_usulan])->with(['success' =>  'Reviewer usulan kegiatan berhasil dihapus !']);
    }

    public function hapusReviewerEksternal(Request $request){
        $anggota = Reviewer1::find($request->id);
        $anggota->delete();

        return redirect()->route('operator.menunggu.detail_reviewer',[$request->id_usulan])->with(['success' =>  'Reviewer eksternal usulan kegiatan berhasil dihapus !']);
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
                                    ->select('reviewer1s.id','reviewer_nip','reviewer_nama','reviewer_prodi_nama','reviewer_fakultas_nama','judul_kegiatan')
                                    ->where('usulans.id',$id)
                                    ->where('jenis_reviewer','internal')
                                    ->get();
        $reviewer_eksternals = Reviewer1::join('usulans','usulans.id','reviewer1s.usulan_id')
                                    ->select('reviewer1s.id','reviewer_nip','reviewer_nama','reviewer_prodi_nama','reviewer_fakultas_nama','judul_kegiatan')
                                    ->where('usulans.id',$id)
                                    ->where('jenis_reviewer','eksternal')
                                    ->get();
        $id_usulan = $id;
        $a = count($reviewers);
        $b = count($reviewer_eksternals);
        $jumlah = $a+$b;
        $judul_kegiatan = Usulan::select('judul_kegiatan')->where('id',$id)->first();
        return view('operator/usulan/menunggu_disetujui.reviewer',compact('reviewers','reviewer_eksternals','id_usulan','judul_kegiatan','jumlah'));
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

    public function batalkan($id){
        $batalkan = Usulan::where('id',$id)->update([
            'status_usulan' =>  '0',
        ]);

        return redirect()->route('operator.menunggu')->with(['success' =>  'Usulan kegiatan sudah dibatalkan!! !']);
    }
}
