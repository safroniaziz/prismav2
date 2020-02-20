<?php

namespace App\Http\Controllers\Pengusul;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use App\Dosen;
use App\Usulan;

class DashboardController extends Controller
{
    public function index(){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                $usulans = Count(Usulan::where('usulans.ketua_peneliti_nip',Session::get('nip'))
                                    ->get());
                $belum_diteruskan = Count(Usulan::where('status_usulan','0')->where('usulans.ketua_peneliti_nip',Session::get('nip'))
                                    ->get());
                $didanai = Count(Usulan::where('status_usulan','3')->orWhere('status_usulan','6')->where('usulans.ketua_peneliti_nip',Session::get('nip'))
                                    ->get());
                $tidak_didanai = Count(Usulan::where('status_usulan','4')->where('usulans.ketua_peneliti_nip',Session::get('nip'))
                                    ->get());
                return view('pengusul/dashboard', compact('usulans','belum_diteruskan','didanai','tidak_didanai'));
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
