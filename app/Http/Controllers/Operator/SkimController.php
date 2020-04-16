<?php

namespace App\Http\Controllers\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Skim;
use GuzzleHttp\Client;

class SkimController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $skims = Skim::select('id','nm_skim','nm_unit','tahun','j_kegiatan')->orderBy('j_kegiatan','asc')->get();
        return view('operator/manajemen_skim.index',compact('skims'));
    }

    // public function generate(){
    //     Skim::truncate();
    //     $client = new Client();
    //     $skims = json_decode($client->get('https://prisma.unib.ac.id/Api/skim')->getBody()->getContents(),true);
    //     for ($i=0; $i < count($skims) ; $i++) {
    //         $skim = new Skim;
    //         if ($skims[$i]['tahun'] != "0000") {
    //             $skim->nm_skim = $skims[$i]['skim_ppm'];
    //             $skim->tahun = $skims[$i]['tahun'];
    //         }
    //         else{
    //             $skim->nm_skim = $skims[$i]['skim_ppm'];
    //         }
    //         $skim->save();
    //     }

    //     return redirect()->route('operator.skim')->with(['success' =>  'Skim berhasil diupdate !']);
    // }

    public function post(Request $request){
        $skim = new Skim;
        $skim->j_kegiatan = $request->jenis_kegiatan;
        $skim->nm_skim = $request->nm_skim;
        $skim->nm_unit = $request->nm_unit;
        $skim->tahun = $request->tahun;
        $skim->save();

        return redirect()->route('operator.skim')->with(['success'  =>  'Skim Baru Berhasil Ditambahkan !!']);
    }

    public function delete(Request $request){
        $skim = SKim::find($request->id);
        $skim->delete();

        return redirect()->route('operator.skim')->with(['success'  =>  'Skim Baru Berhasil Dihapus !!']);
    }
}
