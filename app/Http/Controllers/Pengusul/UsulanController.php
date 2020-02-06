<?php

namespace App\Http\Controllers\Pengusul;

use App\BidangPenelitian;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Auth;
use App\Dosen;
use App\Skim;
use App\Usulan;

class UsulanController extends Controller
{
    public function index(){
        if(Session::get('login') && Session::get('login',1)){
            if(Session::get('akses','dosen')){
                $usulans = Usulan::join('dosens as ketua_peneliti','ketua_peneliti.nip','usulans.ketua_peneliti_id')
                                    ->leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                                    ->leftJoin('skims','skims.id','usulans.skim_id')
                                    ->leftJoin('bidang_penelitians','bidang_penelitians.id','usulans.bidang_id')
                                    ->select('usulans.id','judul_penelitian','nm_bidang as bidang_penelitian','anggota_id','perguruan_tinggi','prodi',
                                            'ketua_peneliti.nm_lengkap as nm_ketua_peneliti','abstrak','kata_kunci','peta_jalan','biaya_diusulkan')
                                    ->get();
                $skims  =   Skim::select('id','nm_skim')->where('tahun',date('Y'))->get();
                $bidangs  =   BidangPenelitian::select('id','nm_bidang')->get();
                $ketua = Dosen::select('nm_lengkap')->where('nip',Session::get('nip'))->first();
                return view('pengusul/usulan.index',compact('usulans','skims','bidangs','ketua'));
            }
            else{
                return redirect()->route('panda.login.form')->with(['error' => 'Anda Tidak Terdaftar Sebagai Mahasiswa']);
            }
        }
        else{
            return redirect()->route('panda.login.form')->with(['error' => 'Masukan Username dan Password Terlebih Dahulu !!']);
        }
    }

    public function post(Request $request){
        if(Session::get('login') && Session::get('login',1)){
            if(Session::get('akses','dosen')){

                $model = $request->all();
                $model['peta_jalan'] = null;
                if ($request->hasFile('peta_jalan')) {
                    $model['peta_jalan'] = Session::get('nip').'-'.date('now').$request->skim_id.'-'.$request->tahun_usulan.uniqid().'.'.$request->peta_jalan->getClientOriginalExtension();
                    $request->peta_jalan->move(public_path('/upload/peta_jalan'), $model['peta_jalan']);
                }
                $usulan = new Usulan;
                $usulan->judul_penelitian = $request->judul_penelitian;
                $usulan->skim_id = $request->skim_id;
                $usulan->bidang_id = $request->bidang_id;
                $usulan->ketua_peneliti_id = Session::get('nip');
                $usulan->abstrak    =   $request->abstrak;
                $usulan->kata_kunci =   implode(',',$request->kata_kunci);
                $usulan->peta_jalan    =   $model['peta_jalan'];
                $usulan->biaya_diusulkan    =   $request->biaya_diusulkan;
                $usulan->tahun_usulan    =   date('Y');
                $usulan->save();

                return redirect()->route('pengusul.usulan')->with(['success' =>  'Usulan baru berhasil ditambahkan !']);
            }
            else{
                return redirect()->route('panda.login.form')->with(['error' => 'Anda Tidak Terdaftar Sebagai Mahasiswa']);
            }
        }
        else{
            return redirect()->route('panda.login.form')->with(['error' => 'Masukan Username dan Password Terlebih Dahulu !!']);
        }
    }

    public function edit($id){
        if(Session::get('login') && Session::get('login',1)){
            if(Session::get('akses','dosen')){
                $usulan = Usulan::select('id','judul_penelitian','bidang_id','skim_id','ketua_peneliti_id','abstrak','peta_jalan','biaya_diusulkan','tahun_usulan')
                            ->where('id',$id)
                            ->first();
                return $usulan;
            }
            else{
                return redirect()->route('panda.login.form')->with(['error' => 'Anda Tidak Terdaftar Sebagai Mahasiswa']);
            }
        }
        else{
            return redirect()->route('panda.login.form')->with(['error' => 'Masukan Username dan Password Terlebih Dahulu !!']);
        }
    }

    public function update(Request $request){
        $usulan = Usulan::find($request->id);
        $input['peta_jalan'] = $usulan->peta_jalan;

        if ($request->hasFile('peta_jalan')) {
        	if ($usulan->peta_jalan != null) {
        		unlink(public_path('/upload/peta_jalan/'.$usulan->peta_jalan));
        	}
            $input['peta_jalan'] = Session::get('nip').'-'.date('now').$request->skim_id.'-'.$request->tahun_usulan.uniqid().'.'.$request->peta_jalan->getClientOriginalExtension();
        	$request->peta_jalan->move(public_path('/upload/peta_jalan'), $input['peta_jalan']);
        }
        $model = $request->all();
        $usulan->judul_penelitian = $request->judul_penelitian;
        $usulan->skim_id = $request->skim_id;
        $usulan->bidang_id = $request->bidang_id;
        $usulan->ketua_peneliti_id = Session::get('nip');
        $usulan->abstrak    =   $request->abstrak_edit;
        $usulan->kata_kunci =   implode(',',$request->kata_kunci);
        $usulan->peta_jalan    =   $model['peta_jalan'];
        $usulan->biaya_diusulkan    =   $request->biaya_diusulkan;
        $usulan->tahun_usulan    =   date('Y');
        $usulan->update();

        return redirect()->route('pengusul.usulan')->with(['success' =>  'Usulan berhasil diubah !']);
    }

    public function delete(Request $request){
        if(Session::get('login') && Session::get('login',1)){
            if(Session::get('akses','dosen')){
                $usulan = Usulan::find($request->id);
                $usulan->delete();

                return redirect()->route('pengusul.usulan')->with(['success' =>  'Usulan berhasil dihapus !']);
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
