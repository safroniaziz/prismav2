<?php

namespace App\Http\Controllers\Reviewer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use DB;
use App\Usulan;
use App\AnggotaUsulan;
use App\Reviewer1;

class UsulanMenungguController extends Controller
{
    public function index(){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 2){
                $usulans = Usulan::join('dosens as ketua_peneliti','ketua_peneliti.nip','usulans.ketua_peneliti_id')
                                    ->leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                                    ->leftJoin('dosens as anggotas','anggotas.id','anggota_usulans.anggota_id')
                                    ->leftJoin('skims','skims.id','usulans.skim_id')
                                    ->leftJoin('bidang_penelitians','bidang_penelitians.id','usulans.bidang_id')
                                    ->leftJoin('reviewer1s','reviewer1s.usulan_id','usulans.id')
                                    ->leftJoin('dosens as reviewer','reviewer.id','reviewer1s.reviewer_id')
                                    ->select('usulans.id','judul_penelitian','nm_bidang as bidang_penelitian','anggota_id','ketua_peneliti.perguruan_tinggi','ketua_peneliti.prodi',
                                            'ketua_peneliti.nm_lengkap as nm_ketua_peneliti','abstrak','kata_kunci','peta_jalan','biaya_diusulkan','status_usulan',
                                            DB::raw('group_concat(distinct concat(anggotas.nm_lengkap) SEPARATOR "&nbsp;|&nbsp;") as "nm_anggota" '),
                                            DB::raw('group_concat(distinct concat(reviewer.nm_lengkap) SEPARATOR "&nbsp;|&nbsp;") as "nm_reviewer" ')
                                            )
                                    ->where('usulans.status_usulan','1')
                                    ->where('reviewer.nip',Session::get('nip'))
                                    ->groupBy('usulans.id')
                                    ->get();
                return view('reviewer.usulan.menunggu.index', compact('usulans'));
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
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 2){
                $usulan = Usulan::join('dosens as ketua_peneliti','ketua_peneliti.nip','usulans.ketua_peneliti_id')
                        ->leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                        ->leftJoin('dosens as anggotas','anggotas.id','anggota_usulans.anggota_id')
                        ->leftJoin('skims','skims.id','usulans.skim_id')
                        ->leftJoin('bidang_penelitians','bidang_penelitians.id','usulans.bidang_id')
                        ->select('usulans.id','judul_penelitian','nm_bidang as bidang_penelitian','anggota_id','ketua_peneliti.fakultas','ketua_peneliti.prodi',
                                'ketua_peneliti.nm_lengkap as nm_ketua_peneliti','ketua_peneliti.nip','kata_kunci','nm_skim','abstrak','kata_kunci','peta_jalan','biaya_diusulkan','tahun_usulan')
                        ->where('usulans.id',$id)
                        ->first();
                $anggotas = AnggotaUsulan::join('dosens','dosens.id','anggota_usulans.anggota_id')
                                            ->select('nm_lengkap as nm_anggota','prodi','fakultas','nip')
                                            ->where('usulan_id',$id)
                                            ->get();
                $reviewers = Reviewer1::join('dosens','dosens.id','reviewer1s.reviewer_id')
                                            ->select('nm_lengkap as nm_anggota','prodi','fakultas','nip')
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
}
