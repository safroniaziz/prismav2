<?php

namespace App\Http\Controllers\Pengusul;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use App\Usulan;

class AnggotaController extends Controller
{
    public function index(){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                $usulans = Usulan::join('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                                    ->select('ketua_peneliti_nama','judul_kegiatan','jenis_kegiatan','tahun_usulan')
                                    ->where('anggota_usulans.anggota_nip',Session::get('nip'))
                                    ->get();
                return view('pengusul/usulan/anggota.index', compact('usulans'));
            }
            else{
                Session::flush();
                return redirect()->route('panda.login.form')->with(['error' => 'Anda Tidak Terdaftar Di Aplikasi']);
            }
        }
        else{
            return redirect()->route('panda.login.form')->with(['error' => 'Masukan Username dan Password Terlebih Dahulu !!']);
        }
    }
}
