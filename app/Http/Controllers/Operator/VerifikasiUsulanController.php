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
use App\UsulanDisetujui;
use PDF;

class VerifikasiUsulanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $usulans = Usulan::leftJoin('nilai_formulirs','nilai_formulirs.usulan_id','usulans.id')
                            ->leftJoin('formulirs','formulirs.id','nilai_formulirs.formulir_id')
                            ->select('usulans.id','judul_penelitian',DB::raw('SUM(skor * (bobot/100)/2) as totalskor'))
                            ->groupBy('usulans.id')
                            ->where('status_usulan','2')
                            ->get();
        return view('operator/usulan/verifikasi.index',compact('usulans'));
    }

    public function detail($id){
        $usulan = Usulan::leftJoin('nilai_formulirs','nilai_formulirs.usulan_id','usulans.id')
                            ->leftJoin('formulirs','formulirs.id','nilai_formulirs.formulir_id')
                            ->select('usulans.id','judul_penelitian',DB::raw('SUM(skor * (bobot/100)/2) as skor'),'kriteria_penilaian')
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
            return redirect()->route('operator.verifikasi')->with(['success'    =>  'Usulan Penelitian berhasil diverifikasi !!']);
        }
        else{
            return redirect()->route('operator.verifikasi')->with(['error'    =>  'Harap pilih usulan penelitian terlebih dahulu !!']);
        }

    }
}
