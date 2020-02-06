<?php

namespace App\Http\Controllers\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Skim;

class SkimController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $skims = Skim::select('id','nm_skim','tahun')->get();
        return view('operator/manajemen_skim.index',compact('skims'));
    }

    public function post(Request $request){
        $skim = new Skim;
        $skim->nm_skim = $request->nm_skim;
        $skim->tahun = $request->tahun;
        $skim->save();

        return redirect()->route('operator.skim')->with(['success' =>  'Skim baru berhasil ditambahkan !']);
    }

    public function edit($id){
        $skim = Skim::find($id);
        return $skim;
    }

    public function update(Request $request){
        $skim = Skim::find($request->id);
        $skim->nm_skim = $request->nm_skim;
        $skim->tahun = $request->tahun;
        $skim->update();

        return redirect()->route('operator.skim')->with(['success' =>  'Skim berhasil diubah !']);
    }

    public function delete(Request $request){
        $skim = Skim::find($request->id);
        $skim->delete();

        return redirect()->route('operator.skim')->with(['success' =>  'Skim berhasil dihapus !']);
    }
}
