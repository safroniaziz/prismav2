<?php

namespace App\Http\Controllers\Pengusul;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LaporanKemajuan;
use App\TotalSkor;
use DB;
use App\Usulan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;


class LaporanKemajuanController extends Controller
{
    public function index(){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                $usulans = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                                    ->leftJoin('laporan_kemajuans','laporan_kemajuans.usulan_id','usulans.id')
                                    ->select('usulans.id','ketua_peneliti_nip','skim_id','judul_kegiatan','file_kemajuan','file_perbaikan','jenis_kegiatan','biaya_diusulkan','ketua_peneliti_nama','tahun_usulan')
                                    ->where('ketua_peneliti_nip',Session::get('nip'))
                                    ->where(function($query) {
                                        $query->where('status_usulan','3')
                                        ->orWhere('status_usulan','6');
                                    })
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
                $slug = Str::slug(Session::get('nm_dosen'));
                // $model['laporan_kemajuan'] = null;
                // if ($request->hasFile('laporan_kemajuan')) {
                //     $model['laporan_kemajuan'] = Session::get('nip').'-'.date('now').$request->id_usulan.'-'.$request->id_usulan.uniqid().'.'.$request->laporan_kemajuan->getClientOriginalExtension();
                //     $request->laporan_kemajuan->move(public_path('/upload/laporan_kemajuan'), $model['laporan_kemajuan']);
                // }
                $laporan_kemajuan = $request->file('laporan_kemajuan');
                $laporan_kemajuanUrl = $laporan_kemajuan->store('laporan_kemajuan/'.$slug.'-'.Session::get('nip'));
                DB::beginTransaction();
                try {
                    LaporanKemajuan::where('usulan_id',$request->id_usulan)->update([
                        'file_kemajuan' =>  $laporan_kemajuanUrl,
                    ]);

                    Usulan::where('id',$request->id_usulan)->update([
                        'status_usulan' =>  '4',
                    ]);
                    DB::commit();
                    return redirect()->route('pengusul.laporan_kemajuan')->with(['success'  =>  'File Laporan Kemajuan Berhasil Diupload !!']);
                } catch (Exception $e) {
                    DB::rollback();
                    return redirect()->route('pengusul.laporan_kemajuan')->with(['error'  =>  'File Laporan Kemajuan Gagal Diupload !!']);
                }
                // return redirect()->route('pengusul.laporan_kemajuan')->with(['success'  =>  'File Laporan Kemajuan Berhasil Diupload !!']);
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
                $slug = Str::slug(Session::get('nm_dosen'));
                // $model = $request->all();
                // $model['laporan_perbaikan'] = null;
                // if ($request->hasFile('laporan_perbaikan')) {
                //     $model['laporan_perbaikan'] = Session::get('nip').'-'.date('now').$request->id_usulan.'-'.$request->id_usulan.uniqid().'.'.$request->laporan_perbaikan->getClientOriginalExtension();
                //     $request->laporan_perbaikan->move(public_path('/upload/laporan_perbaikan'), $model['laporan_perbaikan']);
                // }

                $laporan_perbaikan = $request->file('laporan_perbaikan');
                $laporan_perbaikanUrl = $laporan_perbaikan->store('laporan_perbaikan/'.$slug.'-'.Session::get('nip'));
                
                LaporanKemajuan::create([
                    'usulan_id' =>  $request->id_usulan,
                    'file_perbaikan' =>  $laporan_perbaikanUrl,
                ]);
                // $laporan = new LaporanKemajuan;
                // $laporan->usulan_id = $request->id_usulan;
                // $laporan->file_perbaikan = $fileUrl;
                // $laporan->save();
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

    function ubahBiaya($id){
        $biaya = Usulan::select('biaya_diusulkan','id')->where('id',$id)->first();
        return $biaya;
    }

    public function ubahBiayaPost(Request $request){
        $usulan = Usulan::where('id',$request->id)->update([
            'biaya_diusulkan'   =>  $request->biaya_diusulkan,
        ]);

        return redirect()->route('pengusul.laporan_kemajuan')->with(['success'  =>  'Jumlah Anggaran Berhasil Diubah !!']);
    }

    public function detailPenilaian($id){
        $per_dosen = Usulan::join('nilai_formulirs','nilai_formulirs.usulan_id','usulans.id')
                            ->join('reviewers','reviewers.nip','nilai_formulirs.reviewer_id')
                            ->join('skims','skims.id','usulans.skim_id')
                            ->select('nip','nama','total_skor','jenis_reviewer')
                            ->groupBy('nilai_formulirs.id')
                            ->get();
        $review3 = Usulan::join('nilai_formulirs','nilai_formulirs.usulan_id','usulans.id')
                            ->join('users','users.id','nilai_formulirs.reviewer_id')
                            ->join('skims','skims.id','usulans.skim_id')
                            ->select('nm_user','total_skor')
                            ->groupBy('nilai_formulirs.id')
                            ->get();
        $komentars = Usulan::leftJoin('komentar1s','komentar1s.usulan_id','usulans.id')
                            ->join('reviewers','reviewers.nip','komentar1s.reviewer_id')
                            ->select('komentar1s.komentar','nama','komentar_anggaran','nip')
                            ->get();
        $komentar_operator = Usulan::leftJoin('komentar1s','komentar1s.usulan_id','usulans.id')
                            ->join('users','users.id','komentar1s.reviewer_id')
                            ->select('komentar1s.komentar','nm_user','komentar_anggaran')
                            ->get();
        $total     = TotalSkor::join('usulans','usulans.id','total_skors.usulan_id')
                                ->select('total_skor')
                                ->get();
        $sub_total     = TotalSkor::join('usulans','usulans.id','total_skors.usulan_id')
                                ->select(DB::raw('sum(total_skor) as total_skor'))
                                ->first();
        $jumlah = count(TotalSkor::select('reviewer_id')->where('usulan_id',$id)->get());
        $total2     = TotalSkor::join('usulans','usulans.id','total_skors.usulan_id')
                                ->select(DB::raw('sum(total_skor) as total_skor'))
                                ->get();
        return view('pengusul/usulan.laporan_kemajuan.detail_penilaian',compact('per_dosen','review3','komentars','komentar_operator','total','sub_total','jumlah','total2'));
    }
}
