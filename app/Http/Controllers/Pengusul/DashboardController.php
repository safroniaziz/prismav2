<?php

namespace App\Http\Controllers\Pengusul;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use App\Dosen;

class DashboardController extends Controller
{
    public function index(){
        if(Session::get('login') && Session::get('login',1)){
            if(Session::get('akses','dosen')){
                $user = Dosen::where('nip',Session::get('nip'))->get();
                return view('pengusul/dashboard');
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
