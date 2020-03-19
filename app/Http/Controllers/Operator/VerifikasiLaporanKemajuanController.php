<?php

namespace App\Http\Controllers\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;
use App\Reviewer2;
use App\Usulan;
use App\Fakultas;
use App\Prodi;
use App\RancanganAnggaran;
use App\AnggotaUsulan;
use App\UsulanDisetujui2;
use PDF;

class VerifikasiLaporanKemajuanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $usulans = Usulan::leftJoin('nilai_formulir2s','nilai_formulir2s.usulan_id','usulans.id')
                            ->leftJoin('formulirs','formulirs.id','nilai_formulir2s.formulir_id')
                            ->join('laporan_kemajuans','laporan_kemajuans.usulan_id','usulans.id')
                            ->select('usulans.id','jenis_kegiatan','file_kemajuan','ketua_peneliti_nama','tahun_usulan','judul_kegiatan',DB::raw('SUM(skor * (bobot/100)/2) as totalskor'))
                            ->groupBy('usulans.id')
                            ->where('status_usulan','5')
                            ->get();
        return view('operator/laporan_kemajuan/verifikasi.index',compact('usulans'));
    }

    public function detail($id){
        $usulan = Usulan::leftJoin('nilai_formulir2s','nilai_formulir2s.usulan_id','usulans.id')
                            ->leftJoin('formulirs','formulirs.id','nilai_formulir2s.formulir_id')
                            ->select('usulans.id','judul_kegiatan',DB::raw('SUM(skor * (bobot/100)/2) as skor'),'kriteria_penilaian')
                            ->where('usulans.id',$id)
                            ->groupBy('formulirs.id')
                            ->get();
        return $usulan;
    }

    public function verifikasi(Request $request){
        if (!empty($request->ids)) {
            if ($request->verifikasi == "Setujui") {
                for ($i=0; $i < count($request->ids); $i++) {
                    $ver = Usulan::find($request->ids[$i]);
                    $ver->status_usulan =   "6";
                    $ver->update();

                    $setuju = new UsulanDisetujui2;
                    $setuju->usulan_id = $request->ids[$i];
                    $setuju->save();
                }
            }
            else{
                for ($i=0; $i < count($request->ids); $i++) {
                    $ver = Usulan::find($request->ids[$i]);
                    $ver->status_usulan =   "7";
                    $ver->update();
                }
            }
            return redirect()->route('operator.laporan_kemajuan.verifikasi')->with(['success'    =>  'Usulan Penelitian berhasil diverifikasi !!']);
        }
        else{
            return redirect()->route('operator.laporan_kemajuan.verifikasi')->with(['error'    =>  'Harap pilih usulan penelitian terlebih dahulu !!']);
        }
    }

    public function detailJudul($id){
        $judul = Usulan::find($id);
        return $judul;
    }
}
