<?php

namespace App\Http\Controllers\Reviewer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use App\Dosen;

class DashboardController extends Controller
{
    public function index(){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 2){
                return view('reviewer/dashboard');
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
