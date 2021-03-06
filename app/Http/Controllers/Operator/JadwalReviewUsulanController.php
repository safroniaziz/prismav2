<?php

namespace App\Http\Controllers\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\JadwalReviewUsulan;
if(version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}
class JadwalReviewUsulanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $jadwals = JadwalReviewUsulan::all();
        return view('operator/jadwal_review_usulan.index',compact('jadwals'));
    }

    public function post(Request $request){
        $jadwal = new JadwalReviewUsulan;
        $jadwal->tanggal_awal = $request->tanggal_awal;
        $jadwal->tanggal_akhir = $request->tanggal_akhir;
        $jadwal->status = '0';
        $jadwal->save();

        return redirect()->route('operator.jadwal_review_usulan')->with(['success' =>  'Jadwal Usulan Berhasil Di Tambahkan !!']);
    }

    public function nonaktifkanStatus($id){
        JadwalReviewUsulan::where('id',$id)->update([
            'status'    =>  '0'
        ]);
        return redirect()->route('operator.jadwal_review_usulan')->with(['success' =>  'Jadwal Usulan Berhasil Di Nonaktifkan !!']);
    }

    public function aktifkanStatus($id){
        JadwalReviewUsulan::where('id','!=',$id)->update([
            'status'    =>  '0'
        ]);
        JadwalReviewUsulan::where('id',$id)->update([
            'status'    =>  '1'
        ]);
        return redirect()->route('operator.jadwal_review_usulan')->with(['success' =>  'Jadwal Usulan Berhasil Di Aktifkan !!']);
    }

    public function delete(Request $request){
        $jadwal = JadwalReviewUsulan::where('id',$request->id)->delete();
        return redirect()->route('operator.jadwal_review_usulan')->with(['success' =>  'Jadwal Usulan Berhasil Di Hapus !!']);
    }
}
