<?php

namespace App\Http\Controllers\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Publikasi;

class PublikasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $publikasis = Publikasi::select('id','jenis_kegiatan','jenis_publikasi','status')->get();
        return view('operator/manajemen_publikasi.index',compact('publikasis'));
    }

    // public function generate(){
    //     Skim::truncate();
    //     $client = new Client();
    //     $publikasis = json_decode($client->get('https://prisma.unib.ac.id/Api/skim')->getBody()->getContents(),true);
    //     for ($i=0; $i < count($publikasis) ; $i++) {
    //         $publikasi = new Skim;
    //         if ($publikasis[$i]['tahun'] != "0000") {
    //             $publikasi->nm_skim = $publikasis[$i]['skim_ppm'];
    //             $publikasi->tahun = $publikasis[$i]['tahun'];
    //         }
    //         else{
    //             $publikasi->nm_skim = $publikasis[$i]['skim_ppm'];
    //         }
    //         $publikasi->save();
    //     }

    //     return redirect()->route('operator.publikasi')->with(['success' =>  'Jenis Publikasi berhasil diupdate !']);
    // }

    public function post(Request $request){
        $publikasi = new Publikasi;
        $publikasi->jenis_kegiatan = $request->jenis_kegiatan;
        $publikasi->jenis_publikasi = $request->jenis_publikasi;
        $publikasi->save();

        return redirect()->route('operator.publikasi')->with(['success'  =>  'Jenis Publikasi Baru Berhasil Ditambahkan !!']);
    }

    public function delete(Request $request){
        $publikasi = Publikasi::find($request->id);
        $publikasi->delete();

        return redirect()->route('operator.publikasi')->with(['success'  =>  'Jenis Publikasi Baru Berhasil Dihapus !!']);
    }

    public function aktifkanStatus($id){
        $publikasi = Publikasi::where('id',$id)->update([
            'status'    =>  '1',
        ]);
    }

    public function nonAktifkanStatus($id){
        $publikasi = Publikasi::where('id',$id)->update([
            'status'    =>  '0',
        ]);
    }
}
