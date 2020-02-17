<?php

namespace App\Http\Controllers\Operator;

use App\Formulir;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KriteriaPenilaianController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $formulirs = Formulir::select('id','kriteria_penilaian','bobot')->get();
        return view('operator/manajemen_formulir.index',compact('formulirs'));
    }

    public function post(Request $request){
        $formulir = new Formulir;
        $formulir->kriteria_penilaian = $request->kriteria_penilaian;
        $formulir->bobot = $request->bobot;
        $formulir->save();

        return redirect()->route('operator.kriteria_penilaian')->with(['success' =>  'Kriteria Penilaian baru berhasil ditambahkan !']);
    }

    public function edit($id){
        $formulir = Formulir::find($id);
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
