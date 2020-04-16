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
use PDF;
use Auth;

class VerifikasiUsulanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $penelitians = Usulan::leftJoin('nilai_formulirs','nilai_formulirs.usulan_id','usulans.id')
                            ->leftJoin('formulirs','formulirs.id','nilai_formulirs.formulir_id')
                            ->leftJoin('skims','skims.id','formulirs.skim_id')
                            ->select('usulans.id','jenis_kegiatan','ketua_peneliti_nama','bobot','nm_skim','tahun_usulan','formulirs.skim_id','judul_kegiatan',DB::raw('SUM(skor * (bobot/100)) as totalskor')
                            )
                            ->groupBy('usulans.id')
                            ->where('status_usulan','2')
                            ->where('jenis_kegiatan','penelitian')
                            ->orderBy('usulans.skim_id')
                            ->get();
        $pengabdians = Usulan::leftJoin('nilai_formulirs','nilai_formulirs.usulan_id','usulans.id')
                            ->leftJoin('formulirs','formulirs.id','nilai_formulirs.formulir_id')
                            ->select('usulans.id','jenis_kegiatan','ketua_peneliti_nama','tahun_usulan','formulirs.skim_id','judul_kegiatan',DB::raw('SUM(skor * (bobot/100)) as totalskor')
                            )
                            ->groupBy('usulans.id')
                            ->where('status_usulan','2')
                            ->where('jenis_kegiatan','pengabdian')
                            ->get();
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
                            ->leftJoin('formulirs','formulirs.id','nilai_formulirs.formulir_id')
                            ->leftJoin('reviewer1s','reviewer1s.reviewer_nip','nilai_formulirs.reviewer_id')
                            ->select('usulans.id','judul_kegiatan','reviewer_nama','kriteria_penilaian','skor')
                            ->where('nilai_formulirs.usulan_id',$id)
                            ->orderBy('reviewer1s.reviewer_nip','desc')
                            ->orderBy('formulirs.id','asc')
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
        $mytime = Carbon\Carbon::now();
        $time = $mytime->toDateTimeString();
        $jumlah = $request->jumlah;
        $formulir = array();
        for($i=1; $i <= $jumlah; $i++){
            $formulir[] = array(
                'usulan_id'     =>  $request->usulan_id,
                'formulir_id'   =>  $request->nilai.$i,
                'skor'          =>  $_POST['nilai'.$i],
                'reviewer_id'          =>  Auth::user()->id,
                'created_at'    =>  $time,
                'updated_at'    =>  $time,
            );
        }
        NilaiFormulir3::insert($formulir);

        if ($request->komentar != null || $request->komentar != "") {
            $komentar = new Komentar3;
            $komentar->usulan_id = $request->usulan_id;
            $komentar->reviewer_id = Auth::user()->id;
            $komentar->komentar = $request->komentar;
            $komentar->save();
        }
        return redirect()->route('operator.verifikasi')->with(['success' => 'Reviewer Ketiga sudah ditambahkan !!']);

    }
}
