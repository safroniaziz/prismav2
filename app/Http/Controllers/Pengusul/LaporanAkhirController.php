<?php

namespace App\Http\Controllers\Pengusul;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;
use App\Usulan;
use App\LaporanAkhir;

class LaporanAkhirController extends Controller
{
    public function index(){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                $usulans = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                                    ->leftJoin('laporan_akhirs','laporan_akhirs.usulan_id','usulans.id')
                                    ->select('usulans.id','judul_kegiatan','file_akhir')
                                    ->where('usulans.ketua_peneliti_nip',Session::get('nip'))
                                    ->where('status_usulan','6')
                                    ->groupBy('usulans.id')
                                    ->get();
                return view('pengusul/usulan/laporan_akhir.index',compact('usulans'));
            }
            else{
                Session::flush();
                return redirect()->route('panda.login.form')->with(['error' => 'Anda Tidak Memiliki Akses Login']);
            }
        }
        else{
            return redirect()->route('panda.login.form')->with(['error' => 'Masukan Username dan Password Terlebih Dahulu !!']);
        }
    }

    public function uploadLaporan(Request $request){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                $model = $request->all();
                $model['laporan_akhir'] = null;
                if ($request->hasFile('laporan_akhir')) {
                    $model['laporan_akhir'] = Session::get('nip').'-'.date('now').$request->id_usulan.'-'.$request->id_usulan.uniqid().'.'.$request->laporan_akhir->getClientOriginalExtension();
                    $request->laporan_akhir->move(public_path('/upload/laporan_akhir'), $model['laporan_akhir']);
                }
                $laporan = new LaporanAkhir;
                $laporan->usulan_id = $request->id_usulan;
                $laporan->file_akhir = $model['laporan_akhir'];
                $laporan->save();
                return redirect()->route('pengusul.laporan_akhir')->with(['success'  =>  'File Laporan Akhir Berhasil Diupload !!']);
            }
            else{
                Session::flush();
                return redirect()->route('panda.login.form')->with(['error' => 'Anda Tidak Memiliki Akses Login']);
            }
        }
        else{
            return redirect()->route('panda.login.form')->with(['error' => 'Masukan Username dan Password Terlebih Dahulu !!']);
        }
    }
}
