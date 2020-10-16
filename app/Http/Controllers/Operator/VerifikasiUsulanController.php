<?php

namespace App\Http\Controllers\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;
use Carbon;
use App\Reviewer1;
use App\Usulan;
use App\Fakultas;
use App\Prodi;
use App\RancanganAnggaran;
use App\AnggotaUsulan;
use App\Komentar1;
use App\UsulanDisetujui;
use App\Formulir;
use App\NilaiFormulir3;
use App\Komentar3;
use App\NilaiFormulir;
use App\TotalSkor;
use PDF;
use Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
if(version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}
class VerifikasiUsulanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $penelitians = Usulan::leftJoin('nilai_formulirs','nilai_formulirs.usulan_id','usulans.id')
                            ->leftJoin('skims','skims.id','usulans.skim_id')
                            ->select('usulans.id','jenis_kegiatan','ketua_peneliti_nama','biaya_diusulkan','nm_skim','skim_id','tahun_usulan','judul_kegiatan',DB::raw('SUM(total_skor)/2 as totalskor')
                            )
                            ->groupBy('usulans.id')
                            ->where('status_usulan','2')
                            ->where('jenis_kegiatan','penelitian')
                            ->orderBy('usulans.skim_id')
                            ->get();
        $pengabdians = Usulan::leftJoin('nilai_formulirs','nilai_formulirs.usulan_id','usulans.id')
                            ->leftJoin('skims','skims.id','usulans.skim_id')
                            ->select('usulans.id','jenis_kegiatan','ketua_peneliti_nama','nm_skim','tahun_usulan','judul_kegiatan',DB::raw('SUM(total_skor)/2 as totalskor')
                            )
                            ->groupBy('usulans.id')
                            ->where('status_usulan','2')
                            ->where('jenis_kegiatan','pengabdian')
                            ->get();
                            // return $pengabdians;
        return view('operator/usulan/verifikasi.index',compact('penelitians','pengabdians'));
    }

    public function detail($id){
        $usulan = Usulan::leftJoin('nilai_formulirs','nilai_formulirs.usulan_id','usulans.id')
                            ->leftJoin('formulirs','formulirs.id','nilai_formulirs.formulir_id')
                            ->select('usulans.id','judul_kegiatan',DB::raw('SUM(skor * (bobot/100)/2) as skor'),'kriteria_penilaian')
                            ->where('usulans.id',$id)
                            ->groupBy('formulirs.id')
                            ->get();
        return $usulan;
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

    public function verifikasi(Request $request){
        if (!empty($request->ids)) {
            if ($request->verifikasi == "Setujui") {
                for ($i=0; $i < count($request->ids); $i++) {
                    $ver = Usulan::find($request->ids[$i]);
                    $ver->status_usulan =   "3";
                    $ver->update();

                    $setuju = new UsulanDisetujui;
                    $setuju->usulan_id = $request->ids[$i];
                    $setuju->save();
                }
            }
            else{
                for ($i=0; $i < count($request->ids); $i++) {
                    $ver = Usulan::find($request->ids[$i]);
                    $ver->status_usulan =   "4";
                    $ver->update();
                }
            }
            return redirect()->route('operator.verifikasi')->with(['success'    =>  'Usulan kegiatan berhasil diverifikasi !!']);
        }
        else{
            return redirect()->route('operator.verifikasi')->with(['error'    =>  'Harap pilih usulan kegiatan terlebih dahulu !!']);
        }
    }

    public function detailJudul($id){
        $judul = Usulan::find($id);
        return $judul;
    }

    public function komentar($id){
        $komentar = Komentar1::leftJoin('usulans','usulans.id','komentar1s.usulan_id')
                                ->leftJoin('reviewer1s','reviewer1s.reviewer_nip','komentar1s.reviewer_id')
                                ->select('komentar1s.komentar','reviewer_nama','reviewer_nip')
                                ->where('komentar1s.usulan_id',$id)
                                ->get();
        return $komentar;
    }

    public function detailReviewer($id){
        $usulan = Usulan::leftJoin('nilai_formulirs','nilai_formulirs.usulan_id','usulans.id')
                            // ->leftJoin('formulirs','formulirs.id','nilai_formulirs.formulir_id')
                            ->leftJoin('reviewer1s','reviewer1s.reviewer_nip','nilai_formulirs.reviewer_id')
                            ->select('usulans.id','judul_kegiatan','reviewer_nama','total_skor','reviewer_nip','jenis_reviewer')
                            ->where('nilai_formulirs.usulan_id',$id)
                            ->orderBy('reviewer1s.reviewer_nip','desc')
                            // ->orderBy('formulirs.id','asc')
                            ->get();
        return $usulan;
    }

    public function reviewerTiga($id,$skim_id){
        $id_usulan = $id;
        $judul_kegiatan = Usulan::select('judul_kegiatan')->where('id',$id)->first();
        $jumlah =  Count(Formulir::join('skims','skims.id','formulirs.skim_id')->where('skims.id',$skim_id)->get());
        $formulirs = Formulir::join('skims','skims.id','formulirs.skim_id')->select('formulirs.id','kriteria_penilaian','bobot')->where('skims.id',$skim_id)->get();
        return view('operator.usulan.verifikasi.reviewer3',compact('id_usulan','judul_kegiatan','jumlah','formulirs'));
    }

    public function reviewerTigaPost(Request $request){
        $this->validate($request,[
            'total_nilai'    =>  'required',
        ]);
        $mytime = Carbon\Carbon::now();
        $time = $mytime->toDateTimeString();
        $jumlah = $request->jumlah;
        DB::beginTransaction();
        try {
            $formulir = array();
            for($i=1; $i <= $jumlah; $i++){
                $formulir[] = array(
                    'usulan_id'     =>  $request->usulan_id,
                    'formulir_id'   =>  $_POST['formulir_id'.$i],
                    'skor'          =>  $_POST['nilai'.$i],
                    'total_skor'          =>  floatval($_POST['total'.$i]),
                    'reviewer_id'          =>  FacadesAuth::user()->id,
                    'created_at'    =>  $time,
                    'updated_at'    =>  $time,
                );
            }
            NilaiFormulir::insert($formulir);
            
            $komentar = new Komentar1;
            $komentar->usulan_id = $request->usulan_id;
            $komentar->reviewer_id = FacadesAuth::user()->id;
            $komentar->komentar = $request->komentar;
            $komentar->komentar_anggaran = $request->komentar_anggaran;
            $komentar->save();

            TotalSkor::create([
                'usulan_id'     =>  $request->usulan_id,
                'total_skor'    =>  $request->total_nilai,
                'reviewer_id'   =>  FacadesAuth::user()->id,
            ]);
            
            DB::commit();
            // all good
            return redirect()->route('operator.verifikasi')->with(['success'  =>  'Usulan kegiatan berhasil direview']);
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return redirect()->route('operator.verifikasi')->with(['error'  =>  'Usulan kegiatan gagal direview']);
        }
    }

    public function cetak(){
        $penelitians = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                            ->leftJoin('skims','skims.id','usulans.skim_id')
                            ->select('usulans.id','judul_kegiatan','jenis_kegiatan',
                                    'abstrak','kata_kunci','peta_jalan','file_usulan','lembar_pengesahan','biaya_diusulkan','status_usulan','tahun_usulan','ketua_peneliti_prodi_nama','ketua_peneliti_nama as nm_ketua_peneliti',
                                    DB::raw('group_concat(distinct concat(anggota_usulans.anggota_nama) SEPARATOR "<br>") as "nm_anggota" ')
                                    )
                            ->where('usulans.status_usulan','2')
                            ->where('usulans.jenis_kegiatan','penelitian')
                            ->groupBy('usulans.id')
                            ->get();
                            // return $penelitians;
        return view('operator/usulan.verifikasi.detail',compact('penelitians'));
    }

    public function cetakPengabdian(){
        $pengabdians = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                            ->leftJoin('skims','skims.id','usulans.skim_id')
                            ->select('usulans.id','judul_kegiatan','jenis_kegiatan',
                                    'abstrak','kata_kunci','peta_jalan','file_usulan','lembar_pengesahan','biaya_diusulkan','status_usulan','tahun_usulan','ketua_peneliti_prodi_nama','ketua_peneliti_nama as nm_ketua_peneliti',
                                    DB::raw('group_concat(distinct concat(anggota_usulans.anggota_nama) SEPARATOR "<br>") as "nm_anggota" ')
                                    )
                            ->where('usulans.status_usulan','2')
                            ->where('usulans.jenis_kegiatan','pengabdian')
                            ->groupBy('usulans.id')
                            ->get();
        return view('operator/usulan.verifikasi.detail_pengabdian',compact('pengabdians'));
    }

    public function detailPenilaian($id){
        $per_dosen = Usulan::join('nilai_formulirs','nilai_formulirs.usulan_id','usulans.id')
                            ->join('reviewers','reviewers.nip','nilai_formulirs.reviewer_id')
                            ->join('skims','skims.id','usulans.skim_id')
                            ->select('nip','nama','total_skor','jenis_reviewer')
                            ->groupBy('nilai_formulirs.id')
                            ->get();
        $review3 = Usulan::join('nilai_formulirs','nilai_formulirs.usulan_id','usulans.id')
                            ->join('users','users.id','nilai_formulirs.reviewer_id')
                            ->join('skims','skims.id','usulans.skim_id')
                            ->select('nm_user','total_skor')
                            ->groupBy('nilai_formulirs.id')
                            ->get();
        $komentars = Usulan::leftJoin('komentar1s','komentar1s.usulan_id','usulans.id')
                            ->join('reviewers','reviewers.nip','komentar1s.reviewer_id')
                            ->select('komentar1s.komentar','nama','komentar_anggaran','nip')
                            ->get();
        $komentar_operator = Usulan::leftJoin('komentar1s','komentar1s.usulan_id','usulans.id')
                            ->join('users','users.id','komentar1s.reviewer_id')
                            ->select('komentar1s.komentar','nm_user','komentar_anggaran')
                            ->get();
        $total     = TotalSkor::join('usulans','usulans.id','total_skors.usulan_id')
                                ->select('total_skor')
                                ->get();
        $sub_total     = TotalSkor::join('usulans','usulans.id','total_skors.usulan_id')
                                ->select(DB::raw('sum(total_skor) as total_skor'))
                                ->first();
        $jumlah = count(TotalSkor::select('reviewer_id')->where('usulan_id',$id)->get());
        $total2     = TotalSkor::join('usulans','usulans.id','total_skors.usulan_id')
                                ->select(DB::raw('sum(total_skor) as total_skor'))
                                ->get();
        return view('operator/usulan.verifikasi.detail_penilaian',compact('per_dosen','review3','komentars','komentar_operator','total','sub_total','jumlah','total2'));
    }

    public function updateBiaya(Request $request, $id){
        Usulan::where('id',$id)->update([
            'biaya_diusulkan'   =>  $request->biaya_diusulkan,
        ]);

        return redirect()->route('operator.verifikasi')->with(['success'    =>  'Biaya berhasil diubah !!']);
    }
}
