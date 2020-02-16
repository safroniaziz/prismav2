<?php

namespace App\Http\Controllers\Operator;

use App\Formulir;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VariabelPenilaianController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $formulirs = Formulir::select('id','variabel','deskripsi','persentase')->get();
        return view('operator/manajemen_formulir.index',compact('formulirs'));
    }

    public function post(Request $request){
        $formulir = new Formulir;
        $formulir->variabel = $request->variabel;
        $formulir->deskripsi = $request->deskripsi;
        $formulir->persentase = $request->persentase;
        $formulir->save();

        return redirect()->route('operator.variabel_penilaian')->with(['success' =>  'Variabel Penilaian baru berhasil ditambahkan !']);
    }

    public function edit($id){
        $formulir = Formulir::find($id);
        return $formulir;
    }

    public function update(Request $request){
        $formulir = Formulir::find($request->id);
        $formulir->variabel = $request->variabel;
        $formulir->deskripsi = $request->deskripsi;
        $formulir->persentase = $request->persentase;
        $formulir->update();

        return redirect()->route('operator.variabel_penilaian')->with(['success' =>  'Variabel Penilaian berhasil diubah !']);
    }

    public function delete(Request $request){
        $formulir = Formulir::find($request->id);
        $formulir->delete();

        return redirect()->route('operator.variabel_penilaian')->with(['success' =>  'Variabel Penilaian berhasil dihapus !']);
    }
}
