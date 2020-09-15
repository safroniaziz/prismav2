<?php

namespace App\Http\Controllers\Operator;

use App\AnggotaUsulan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserLoginController;
use DB;
use Session;
use App\Usulan;
use App\RancanganAnggaran;
use Illuminate\Support\Facades\DB as FacadesDB;
use PDF;

class UsulanPendingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $panda = new UserLoginController();
        $penelitians = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                            ->leftJoin('skims','skims.id','usulans.skim_id')
                            ->select('usulans.id','judul_kegiatan','jenis_kegiatan',
                                    'abstrak','kata_kunci','file_usulan','nm_skim','usulans.created_at','biaya_diusulkan','status_usulan','tahun_usulan','ketua_peneliti_prodi_nama','ketua_peneliti_nama as nm_ketua_peneliti',
                                    DB::raw('group_concat(distinct concat(anggota_usulans.anggota_nama) SEPARATOR "<br>") as "nm_anggota" ')
                                    )
                            ->where('usulans.status_usulan','0')
                            ->where('usulans.jenis_kegiatan','penelitian')
                            ->groupBy('usulans.id')
                            ->get();
        $pengabdians = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                            ->leftJoin('skims','skims.id','usulans.skim_id')
                            ->select('usulans.id','judul_kegiatan','jenis_kegiatan',
                                    'abstrak','kata_kunci','file_usulan','nm_skim','biaya_diusulkan','status_usulan','tahun_usulan','ketua_peneliti_prodi_nama','ketua_peneliti_nama as nm_ketua_peneliti',
                                    DB::raw('group_concat(distinct concat(anggota_usulans.anggota_nama) SEPARATOR "<br>") as "nm_anggota" ')
                                    )
                            ->where('usulans.status_usulan','0')
                            ->where('usulans.jenis_kegiatan','pengabdian')
                            ->groupBy('usulans.id')
                            ->get();
        return view('operator/usulan/pending.index',compact('penelitians','pengabdians'));
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
        return view('operator/usulan/pending.detail_penelitian',compact('detail','anggota_internal','anggota_eksternal','anggota_mahasiswa','anggota_alumni'));
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
        $pdf = PDF::loadView('operator/usulan.pending.cetak',compact('outputs','habis_pakais','penunjangs','lainnya'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream();
    }

    public function cetak(){
        $penelitians = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                            ->leftJoin('skims','skims.id','usulans.skim_id')
                            ->select('usulans.id','judul_kegiatan','jenis_kegiatan',
                                    'abstrak','kata_kunci','peta_jalan','nm_skim','file_usulan','lembar_pengesahan','biaya_diusulkan','status_usulan','tahun_usulan','ketua_peneliti_prodi_nama','ketua_peneliti_nama as nm_ketua_peneliti',
                                    FacadesDB::raw('group_concat(distinct concat(anggota_usulans.anggota_nama) SEPARATOR "<br>") as "nm_anggota" ')
                                    )
                            ->where('usulans.status_usulan','0')
                            ->where('usulans.jenis_kegiatan','penelitian')
                            ->groupBy('usulans.id')
                            ->orderBy('skims.id')
                            ->get();
                            // return $penelitians;
        return view('operator/usulan.pending.detail',compact('penelitians'));
    }

    public function cetakPengabdian(){
        $pengabdians = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                            ->leftJoin('skims','skims.id','usulans.skim_id')
                            ->select('usulans.id','judul_kegiatan','jenis_kegiatan',
                                    'abstrak','kata_kunci','peta_jalan','nm_skim','file_usulan','lembar_pengesahan','biaya_diusulkan','status_usulan','tahun_usulan','ketua_peneliti_prodi_nama','ketua_peneliti_nama as nm_ketua_peneliti',
                                    FacadesDB::raw('group_concat(distinct concat(anggota_usulans.anggota_nama) SEPARATOR "<br>") as "nm_anggota" ')
                                    )
                            ->where('usulans.status_usulan','0')
                            ->where('usulans.jenis_kegiatan','pengabdian')
                            ->groupBy('usulans.id')
                            ->orderBy('skims.id')
                            ->get();
        return view('operator/usulan.pending.detail_pengabdian',compact('pengabdians'));
    }
}
