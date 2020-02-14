<?php

namespace App\Http\Controllers\Operator;

use App\Fakultas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserLoginController;

class FakultasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $fakultas = Fakultas::select('fakultas_kode','fakultas_kode_univ','nm_fakultas')->get();
        return view('operator/manajemen_fakultas.index',compact('fakultas'));
    }

    public function getDataFakultas(){
        Fakultas::truncate();
        $panda = new UserLoginController();
        $qr_mk = '
        {fakultas {
            fakKode
            fakKodeUniv
            fakNamaResmi
          }}
        ';
        $mk = $panda->panda($qr_mk);
        for($a =0; $a<sizeof($mk['fakultas']); $a++){
            Fakultas::create([
                'fakultas_kode'                         =>  $mk['fakultas'][$a]['fakKode'],
                'fakultas_kode_univ'                    =>  $mk['fakultas'][$a]['fakKodeUniv'],
                'nm_fakultas'                           =>  $mk['fakultas'][$a]['fakNamaResmi'],
            ]);
        }

        return redirect()->route('operator.fakultas')->with(['success' =>  'Data Program Studi Berhasil Diupdate !!']);
    }
}
