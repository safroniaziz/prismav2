<?php

namespace App\Http\Controllers\Reviewer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use DB;
use PDF;
use Carbon;
use App\Usulan;
use App\AnggotaUsulan;
use App\Formulir;
use App\JadwalReviewUsulan;
use App\Komentar1;
use App\NilaiFormulir;
use App\Reviewer1;
use App\RancanganAnggaran;

class UsulanMenungguController extends Controller
{
    public function index(){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',2)){
            if($sesi == 2){
                $usulans = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                                    ->leftJoin('skims','skims.id','usulans.skim_id')
                                    ->leftJoin('reviewer1s','reviewer1s.usulan_id','usulans.id')
                                    ->select('usulans.id','judul_kegiatan','jenis_kegiatan','ketua_peneliti_universitas','skims.id as skim_id','ketua_peneliti_prodi_nama',
                                            'ketua_peneliti_nama as nm_ketua_peneliti','abstrak','tahun_usulan','kata_kunci','peta_jalan','file_usulan','lembar_pengesahan','biaya_diusulkan','status_usulan',
                                            DB::raw('group_concat(distinct concat(anggota_nama) SEPARATOR "<br>") as "nm_anggota" '),
                                            DB::raw('group_concat(distinct concat(reviewer_nama) SEPARATOR "<br>") as "nm_reviewer" '),
                                            DB::raw('group_concat(distinct concat(reviewer_nip) SEPARATOR "<br>") as "nip_reviewer" ')
                                            )
                                    ->where('reviewer_nip',Session::get('nip'))
                                    ->groupBy('usulans.id')
                                    ->get();
                $jadwal = JadwalReviewUsulan::select('tanggal_awal','tanggal_akhir')->where('status','1')->first();
                $mytime = Carbon\Carbon::now();
                $now =  $mytime->toDateString();
                return view('reviewer.usulan.menunggu.index', compact('usulans','jadwal','now'));
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
                                ->select('usulans.id','judul_kegiatan','jenis_kegiatan','tujuan','luaran','ketua_peneliti_fakultas_nama','ketua_peneliti_prodi_nama',
                                        'ketua_peneliti_nama as nm_ketua_peneliti','ketua_peneliti_nip','kata_kunci','nm_skim','abstrak','kata_kunci','peta_jalan','biaya_diusulkan','tahun_usulan')
                                ->where('usulans.id',$id)
                                ->first();
                $anggotas = AnggotaUsulan::select('anggota_nama as nm_anggota','anggota_prodi_nama','anggota_fakultas_nama','anggota_nip')
                                            ->where('usulan_id',$id)
                                            ->get();
                $reviewers = Reviewer1::select('reviewer_nama as nm_anggota','reviewer_prodi_nama','reviewer_fakultas_nama','reviewer_nip')
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

    public function anggaranCetak($id){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',2)){
            if($sesi == 2){
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
                $pdf = PDF::loadView('reviewer/usulan.menunggu.cetak',compact('outputs','habis_pakais','penunjangs','lainnya'));
                $pdf->setPaper('a4', 'portrait');
                return $pdf->stream();
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
                return view('reviewer/usulan/menunggu.review',compact('judul_kegiatan','id_usulan','jumlah','formulirs'));
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
        NilaiFormulir::insert($formulir);

        if ($request->komentar != null || $request->komentar != "") {
            $komentar = new Komentar1;
            $komentar->usulan_id = $request->usulan_id;
            $komentar->reviewer_id = Session::get('nip');
            $komentar->komentar = $request->komentar;
            $komentar->save();
        }

        $sudah = Usulan::leftJoin('reviewer1s','reviewer1s.usulan_id','usulans.id')
                                ->rightJoin('nilai_formulirs','nilai_formulirs.reviewer_id','reviewer1s.reviewer_nip')
                                ->select('nilai_formulirs.reviewer_id')
                                ->where('nilai_formulirs.usulan_id',$request->usulan_id)
                                ->groupBy('nilai_formulirs.reviewer_id')
                                ->get();
        if (count($sudah) == 2) {
            $status = '2';
            $usulan = Usulan::find($request->usulan_id);
            $usulan->status_usulan = $status;
            $usulan->update();
        }

        return redirect()->route('reviewer.menunggu')->with(['success' => 'Usulan Penelitian sudah di review']);

    }

}
