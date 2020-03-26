<?php

namespace App\Http\Controllers\Reviewer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;
use Carbon;
use App\Usulan;
use App\AnggotaUsulan;
use App\Reviewer2;
use App\Formulir;
use App\NilaiFormulir2;
use App\Komentar2;

class LaporanKemajuanController extends Controller
{
    public function index(){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',2)){
            if($sesi == 2){
                $usulans = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                                    ->join('laporan_kemajuans','laporan_kemajuans.usulan_id','usulans.id')
                                    ->leftJoin('skims','skims.id','usulans.skim_id')
                                    ->leftJoin('reviewer2s','reviewer2s.usulan_id','usulans.id')
                                    ->leftJoin('nilai_formulir2s','nilai_formulir2s.reviewer_id','reviewer2s.reviewer_nip')
                                    ->select('usulans.id','judul_kegiatan','file_kemajuan','jenis_kegiatan','tahun_usulan','skims.id as skim_id',
                                            'ketua_peneliti_nama as nm_ketua_peneliti',
                                            DB::raw('group_concat(distinct concat(anggota_nama) SEPARATOR "<br>") as "nm_anggota" '),
                                            DB::raw('group_concat(distinct concat(reviewer_nama) SEPARATOR "<br>") as "nm_reviewer" '),
                                            'nilai_formulir2s.reviewer_id'
                                            )
                                    ->where('nilai_formulir2s.reviewer_id', null)
                                    ->where('usulans.status_usulan','3')
                                    ->where('reviewer_nip',Session::get('nip'))
                                    ->groupBy('nilai_formulir2s.reviewer_id')
                                    ->get();
                return view('reviewer.usulan.laporan_kemajuan.index', compact('usulans'));
            }
            else{
                Session::flush();
                return redirect()->route('panda.reviewer_login.form')->with(['error' => 'Anda Tidak Terdaftar Sebagai Mahasiswa']);
            }
        }
        else{
            return redirect()->route('panda.reviewer_login.form')->with(['error' => 'Masukan Username dan Password Terlebih Dahulu !!']);
        }
    }

    public function detail($id){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',2)){
            if($sesi == 2){
                $usulan = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                                ->leftJoin('skims','skims.id','usulans.skim_id')
                                ->select('usulans.id','judul_kegiatan','jenis_kegiatan','ketua_peneliti_fakultas_nama','ketua_peneliti_prodi_nama',
                                        'ketua_peneliti_nama as nm_ketua_peneliti','ketua_peneliti_nip','kata_kunci','nm_skim','abstrak','kata_kunci','peta_jalan','biaya_diusulkan','tahun_usulan')
                                ->where('usulans.id',$id)
                                ->first();
                $anggotas = AnggotaUsulan::select('anggota_nama as nm_anggota','anggota_prodi_nama','anggota_fakultas_nama','anggota_nip')
                                            ->where('usulan_id',$id)
                                            ->get();
                $reviewers = Reviewer2::select('reviewer_nama as nm_anggota','reviewer_prodi_nama','reviewer_fakultas_nama','reviewer_nip')
                                            ->where('usulan_id',$id)
                                            ->get();
                $data = [
                    'usulan'        => $usulan,
                    'anggotas'      => $anggotas,
                    'reviewers'      => $reviewers,
                ];
                return $data;
            }
            else{
                Session::flush();
                return redirect()->route('panda.reviewer_login.form')->with(['error' => 'Anda Tidak Terdaftar Sebagai Mahasiswa']);
            }
        }
        else{
            return redirect()->route('panda.reviewer_login.form')->with(['error' => 'Masukan Username dan Password Terlebih Dahulu !!']);
        }
    }

    public function review($id, $skim_id){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',2)){
            if($sesi == 2){
                // $cek = NilaiFormulir::select('usulan_id')
                $judul_kegiatan = Usulan::select('judul_kegiatan')->where('id',$id)->first();
                $id_usulan = $id;
                $jumlah =  Count(Formulir::join('skims','skims.id','formulirs.skim_id')->where('skims.id',$skim_id)->get());
                $formulirs = Formulir::join('skims','skims.id','formulirs.skim_id')->select('formulirs.id','kriteria_penilaian','bobot')->where('skims.id',$skim_id)->get();
                return view('reviewer/usulan/laporan_kemajuan.review',compact('judul_kegiatan','id_usulan','jumlah','formulirs'));
            }
            else{
                Session::flush();
                return redirect()->route('panda.reviewer_login.form')->with(['error' => 'Anda Tidak Terdaftar Sebagai Mahasiswa']);
            }
        }
        else{
            return redirect()->route('panda.reviewer_login.form')->with(['error' => 'Masukan Username dan Password Terlebih Dahulu !!']);
        }
    }

    public function reviewPost(Request $request){
        $mytime = Carbon\Carbon::now();
        $time = $mytime->toDateTimeString();
        $jumlah = $request->jumlah;
        $formulir = array();
        for($i=1; $i <= $jumlah; $i++){
            $formulir[] = array(
                'usulan_id'     =>  $request->usulan_id,
                'formulir_id'   =>  $request->nilai.$i,
                'skor'          =>  $_POST['nilai'.$i],
                'reviewer_id'          =>  Session::get('nip'),
                'created_at'    =>  $time,
                'updated_at'    =>  $time,
            );
        }
        NilaiFormulir2::insert($formulir);

        if ($request->komentar != null || $request->komentar != "") {
            $komentar = new Komentar2;
            $komentar->usulan_id = $request->usulan_id;
            $komentar->reviewer_id = Session::get('nip');
            $komentar->komentar = $request->komentar;
            $komentar->save();
        }

        $sudah = Usulan::leftJoin('reviewer2s','reviewer2s.usulan_id','usulans.id')
                                ->rightJoin('nilai_formulir2s','nilai_formulir2s.reviewer_id','reviewer2s.reviewer_nip')
                                ->select('nilai_formulir2s.reviewer_id')
                                ->where('nilai_formulir2s.usulan_id',$request->usulan_id)
                                ->groupBy('nilai_formulir2s.reviewer_id')
                                ->get();
        if (count($sudah) == 2) {
            $status = '5';
            $usulan = Usulan::find($request->usulan_id);
            $usulan->status_usulan = $status;
            $usulan->update();
        }

        return redirect()->route('reviewer.laporan_kemajuan')->with(['success' => 'Usulan Penelitian sudah di review']);

    }
}
