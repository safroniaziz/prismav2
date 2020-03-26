<?php

namespace App\Http\Controllers\Pengusul;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LaporanKemajuan;
use DB;
use Session;
use App\Usulan;

class LaporanKemajuanController extends Controller
{
    public function index(){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                $usulans = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                                    ->leftJoin('laporan_kemajuans','laporan_kemajuans.usulan_id','usulans.id')
                                    ->select('usulans.id','judul_kegiatan','file_kemajuan','file_perbaikan','jenis_kegiatan','ketua_peneliti_nama','tahun_usulan')
                                    ->where('usulans.ketua_peneliti_nip',Session::get('nip'))
                                    ->where('status_usulan','3')
                                    ->orWhere('status_usulan','6')
                                    ->groupBy('usulans.id')
                                    ->get();
                return view('pengusul/usulan/laporan_kemajuan.index',compact('usulans'));
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
                $model['laporan_kemajuan'] = null;
                if ($request->hasFile('laporan_kemajuan')) {
                    $model['laporan_kemajuan'] = Session::get('nip').'-'.date('now').$request->id_usulan.'-'.$request->id_usulan.uniqid().'.'.$request->laporan_kemajuan->getClientOriginalExtension();
                    $request->laporan_kemajuan->move(public_path('/upload/laporan_kemajuan'), $model['laporan_kemajuan']);
                }
                $laporan = LaporanKemajuan::where('usulan_id',$request->id_usulan)->update([
                    'file_kemajuan' =>  $model['laporan_kemajuan'],
                ]);
                return redirect()->route('pengusul.laporan_kemajuan')->with(['success'  =>  'File Laporan Kemajuan Berhasil Diupload !!']);
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

    public function uploadLaporanPerbaikan(Request $request){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                $model = $request->all();
                $model['laporan_perbaikan'] = null;
                if ($request->hasFile('laporan_perbaikan')) {
                    $model['laporan_perbaikan'] = Session::get('nip').'-'.date('now').$request->id_usulan.'-'.$request->id_usulan.uniqid().'.'.$request->laporan_perbaikan->getClientOriginalExtension();
                    $request->laporan_perbaikan->move(public_path('/upload/laporan_perbaikan'), $model['laporan_perbaikan']);
                }
                $laporan = new LaporanKemajuan;
                $laporan->usulan_id = $request->id_usulan;
                $laporan->file_perbaikan = $model['laporan_perbaikan'];
                $laporan->save();
                return redirect()->route('pengusul.laporan_kemajuan')->with(['success'  =>  'File Laporan Perbaikan Berhasil Diupload !!']);
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

    public function detailJudul($id){
        $judul = Usulan::find($id);
        return $judul;
    }
}
