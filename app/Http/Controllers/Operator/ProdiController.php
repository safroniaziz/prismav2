<?php

namespace App\Http\Controllers\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserLoginController;
use App\Prodi;

class ProdiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $prodis = Prodi::leftJoin('fakultas','fakultas.fakultas_kode','prodis.fakultas_kode')->select('prodis.id','nm_prodi','nm_fakultas')
                        ->orderBy('fakultas.fakultas_kode')
                        ->get();
        return view('operator/manajemen_prodi.index',compact('prodis'));
    }

    public function getDataProdi(){
        Prodi::truncate();
        $panda = new UserLoginController();
        $qr_mk = '
        {prodi {
            prodiKode
            prodiNamaResmi
              fakultas {
                fakKode
              }
          }}
        ';
        $mk = $panda->panda($qr_mk);
        for($a =0; $a<sizeof($mk['prodi']); $a++){
            Prodi::create([
                'prodi_kode'                           =>  $mk['prodi'][$a]['prodiKode'],
                'nm_prodi'                             =>  $mk['prodi'][$a]['prodiNamaResmi'],
                'fakultas_kode'                        =>  $mk['prodi'][$a]['fakultas']['fakKode'],
            ]);
        }

        return redirect()->route('operator.prodi')->with(['success' =>  'Data Program Studi Berhasil Diupdate !!']);
    }
}
