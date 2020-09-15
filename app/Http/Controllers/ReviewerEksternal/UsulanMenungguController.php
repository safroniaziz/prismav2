<?php

namespace App\Http\Controllers\ReviewerEksternal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use DB;
use PDF;
use Carbon;
use App\Usulan;
use App\AnggotaUsulan;
use App\Formulir;
use App\Komentar1;
use App\NilaiFormulir;
use App\Reviewer1;
use App\RancanganAnggaran;
use Auth;
use App\JadwalReviewUsulan;
use App\TotalSkor;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\DB as FacadesDB;

class UsulanMenungguController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:reviewerusulan');
    }

    public function index(){
        $usulans = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                                    ->leftJoin('skims','skims.id','usulans.skim_id')
                                    ->leftJoin('reviewer1s','reviewer1s.usulan_id','usulans.id')
                                    ->select('usulans.id','judul_kegiatan','usulans.created_at','nm_skim','jenis_kegiatan','ketua_peneliti_universitas','skims.id as skim_id','ketua_peneliti_prodi_nama',
                                            'ketua_peneliti_nama as nm_ketua_peneliti','abstrak','tahun_usulan','kata_kunci','file_usulan','biaya_diusulkan',
                                            DB::raw('group_concat(distinct concat(anggota_nama) SEPARATOR "<br>") as "nm_anggota" '),
                                            DB::raw('group_concat(distinct concat(reviewer_nip) SEPARATOR "<br>") as "nip_reviewer" ')
                                            )
                            ->where('reviewer_nip',Auth::guard('reviewerusulan')->user()->nip)
                            ->groupBy('usulans.id')
                            ->get();
                            // return Auth::guard('reviewerusulan')->user()->nip;
        $jadwal = JadwalReviewUsulan::select('tanggal_awal','tanggal_akhir')->where('status','1')->get();
        $mytime = Carbon\Carbon::now();
        $now =  $mytime->toDateString();
        return view('reviewer_eksternal.reviewer_usulan.menunggu.index', compact('usulans','jadwal','now'));
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
        return view('reviewer_eksternal/reviewer_usulan/menunggu.detail_penelitian',compact('detail','anggota_internal','anggota_eksternal','anggota_mahasiswa','anggota_alumni'));
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
                $pdf = PDF::loadView('reviewer/usulan.menunggu.cetak',compact('outputs','habis_pakais','penunjangs','lainnya'));
                $pdf->setPaper('a4', 'portrait');
                return $pdf->stream();
    }

    public function review($id, $skim_id){
        // $cek = NilaiFormulir::select('usulan_id')
        $judul_kegiatan = Usulan::select('judul_kegiatan')->where('id',$id)->first();
        $id_usulan = $id;
        $jumlah =  Count(Formulir::join('skims','skims.id','formulirs.skim_id')->where('skims.id',$skim_id)->get());
        $formulirs = Formulir::join('skims','skims.id','formulirs.skim_id')->select('formulirs.id','kriteria_penilaian','bobot')->where('skims.id',$skim_id)->get();
        return view('reviewer_eksternal/reviewer_usulan/menunggu.review',compact('judul_kegiatan','id_usulan','jumlah','formulirs'));
    }

    public function reviewPost(Request $request){
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
                    'reviewer_id'          =>  FacadesAuth::guard('reviewerusulan')->user()->nip,
                    'created_at'    =>  $time,
                    'updated_at'    =>  $time,
                );
            }
            NilaiFormulir::insert($formulir);
            
            $komentar = new Komentar1;
            $komentar->usulan_id = $request->usulan_id;
            $komentar->reviewer_id = FacadesAuth::guard('reviewerusulan')->user()->nip;
            $komentar->komentar = $request->komentar;
            $komentar->komentar_anggaran = $request->komentar_anggaran;
            $komentar->save();

            TotalSkor::create([
                'usulan_id'     =>  $request->usulan_id,
                'total_skor'    =>  $request->total_nilai,
                'reviewer_id'   =>  FacadesAuth::guard('reviewerusulan')->user()->nip,
            ]);
            DB::commit();
            $sudah = Usulan::leftJoin('reviewer1s','reviewer1s.usulan_id','usulans.id')
                                ->rightJoin('total_skors','total_skors.reviewer_id','reviewer1s.reviewer_nip')
                                ->select('total_skors.reviewer_id')
                                ->where('total_skors.usulan_id',$request->usulan_id)
                                ->groupBy('total_skors.reviewer_id')
                                ->get();
            if (count($sudah) > 1) {
                Usulan::where('id',$request->usulan_id)->update([
                    'status_usulan' => '2',
                ]);
                return redirect()->route('reviewer_usulan.menunggu')->with(['success' => 'Usulan Penelitian sudah di review']);
            }
            // all good
            return redirect()->route('reviewer_usulan.menunggu')->with(['success'  =>  'Usulan kegiatan berhasil direview']);
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return redirect()->route('reviewer_usulan.menunggu')->with(['error'  =>  'Usulan kegiatan gagal direview']);
        }
    }

    public function riwayat(){
        $riwayats = Usulan::leftJoin('skims','skims.id','usulans.skim_id')
                            ->leftJoin('total_skors','total_skors.usulan_id','usulans.id')
                            ->where('total_skors.reviewer_id',FacadesAuth::guard('reviewerusulan')->user()->nip)
                            ->groupBy('usulans.id')
                            ->get();
        return view('reviewer_eksternal/reviewer_usulan.riwayat', compact('riwayats'));
    }
}
