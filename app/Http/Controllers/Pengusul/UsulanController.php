<?php

namespace App\Http\Controllers\Pengusul;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use App\Dosen;
use App\Usulan;

class UsulanController extends Controller
{
    public function index(){
        if(Session::get('login') && Session::get('login',1)){
            if(Session::get('akses','dosen')){
                $usulans = Usulan::join('dosens as ketua_peneliti','ketua_peneliti.id','usulans.ketua_peneliti_id')
                                    ->select('usulans.id','judul_penelitian','bidang_penelitian','perguruan_tinggi','program_study',
                                            'ketua_peneliti.nm_lengkap as nm_ketua_peneliti','abstrak','kata_kunci','peta_jalan','biaya_diusulkan')
                                    ->get();
                return view('pengusul/usulan.index',compact('usulans'));
            }
            else{
                return redirect()->route('panda.login.form')->with(['error' => 'Anda Tidak Terdaftar Sebagai Mahasiswa']);
            }
        }
        else{
            return redirect()->route('panda.login.form')->with(['error' => 'Masukan Username dan Password Terlebih Dahulu !!']);
        }
    }
}
