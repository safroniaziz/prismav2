<?php

namespace App\Http\Controllers\Reviewer;

use App\AnggotaUsulan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use App\Usulan;
use DB;

class RiwayatReviewController extends Controller
{
    public function index(){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',2)){
            if($sesi == 2){
                $riwayats = Usulan::leftJoin('skims','skims.id','usulans.skim_id')
                            ->leftJoin('total_skors','total_skors.usulan_id','usulans.id')
                            ->where('total_skors.reviewer_id',Session::get('nip'))
                            ->groupBy('usulans.id')
                            ->get();
                return view('reviewer.riwayat.index', compact('riwayats'));
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
                                ->select('usulans.id','judul_kegiatan','jenis_kegiatan','tujuan','abstrak','luaran','ketua_peneliti_fakultas_nama as fakultas','ketua_peneliti_prodi_nama as prodi',
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
            else{
                Session::flush();
                return redirect()->route('panda.reviewer_login.form')->with(['error' => 'Anda Tidak Terdaftar Sebagai Mahasiswa']);
            }
        }
        else{
            return redirect()->route('panda.reviewer_login.form')->with(['error' => 'Masukan Username dan Password Terlebih Dahulu !!']);
        }
    }
}
