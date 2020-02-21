<?php

namespace App\Http\Controllers\Operator;

use App\Formulir;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon;
use App\Skim;

class KriteriaPenilaianController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $skims = Skim::where('tahun',date('Y'))->get();
        $formulirs = Formulir::join('skims','skims.id','formulirs.skim_id')->select('formulirs.id','nm_skim','kriteria_penilaian','bobot')->get();
        return view('operator/manajemen_formulir.index',compact('formulirs','skims'));
    }

    public function post(Request $request){
        $formulir = new Formulir;
        $formulir->kriteria_penilaian = $request->kriteria_penilaian;
        $formulir->bobot = $request->bobot;
        $formulir->skim_id = $request->skim_id;
        $formulir->save();

        return redirect()->route('operator.kriteria_penilaian')->with(['success' =>  'Kriteria Penilaian baru berhasil ditambahkan !']);
    }

    public function edit($id){
        $formulir = Formulir::join('skims','skims.id','formulirs.skim_id')->select('formulirs.id','skims.id as skim_id','nm_skim','kriteria_penilaian','bobot')->where('formulirs.id',$id)->first();
        return $formulir;
    }

    public function update(Request $request){
        $formulir = Formulir::find($request->id);
        $formulir->kriteria_penilaian = $request->kriteria_penilaian;
        $formulir->bobot = $request->bobot;
        $formulir->update();

        return redirect()->route('operator.kriteria_penilaian')->with(['success' =>  'Kriteria Penilaian berhasil diubah !']);
    }

    public function delete(Request $request){
        $formulir = Formulir::find($request->id);
        $formulir->delete();

        return redirect()->route('operator.kriteria_penilaian')->with(['success' =>  'Kriteria Penilaian berhasil dihapus !']);
    }
}
