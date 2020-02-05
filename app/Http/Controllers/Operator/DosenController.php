<?php

namespace App\Http\Controllers\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserLoginController;
use Client;
use DB;
use DataTables;
use App\Dosen;

class DosenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('operator/manajemen_dosen.index');
    }

    public function getDataDosen(){
        Dosen::truncate();
        $panda = new UserLoginController();
        $qr_mk = '
        {dosen {
            dsnNidn
            prodi {
              prodiKode
              prodiNamaResmi
              fakultas {
                fakKode
                fakNamaResmi
              }
            }
            pegawai {
              pegNip
              pegNama
              pegGelarDepan
              pegGelarBelakang
              pegIsAktif
                  pegawai_simpeg {
                    pegNip
                    pegNama
                    pegJenkel
                    pegTmtGol
                    pegNmJabatan

                  }
            }
        }}
        ';
        $mk = $panda->panda($qr_mk);
        for($a =0; $a<sizeof($mk['dosen']); $a++){
            if($mk['dosen'][$a]['pegawai']['pegIsAktif'] == 1){
                if($mk['dosen'][$a]['pegawai']['pegNip'] != "-" && $mk['dosen'][$a]['pegawai']['pegNip']    != "0" && $mk['dosen'][$a]['pegawai']['pegNip'] != "1"){
                    Dosen::create([
                        'nm_lengkap'                        =>  $mk['dosen'][$a]['pegawai']['pegGelarDepan'].$mk['dosen'][$a]['pegawai']['pegNama'].','.$mk['dosen'][$a]['pegawai']['pegGelarBelakang'],
                        'nip'                               =>  $mk['dosen'][$a]['pegawai']['pegNip'],
                        'nidn'                              =>  $mk['dosen'][$a]['dsnNidn'],
                        'prodi'                             =>  $mk['dosen'][$a]['prodi']['prodiNamaResmi'],
                        'prodi_kode'                        =>  $mk['dosen'][$a]['prodi']['prodiKode'],
                        'fakultas'                          =>  $mk['dosen'][$a]['prodi']['fakultas']['fakNamaResmi'],
                        'fakultas_kode'                     =>  $mk['dosen'][$a]['prodi']['fakultas']['fakKode'],
                        'jabatan_fungsional'                =>  $mk['dosen'][$a]['pegawai']['pegawai_simpeg']['pegNmJabatan'],
                        'keahlian'                          =>  $mk['dosen'][$a]['prodi']['prodiNamaResmi'],
                        'alamat_institusi'                  =>  'Jl. WR. Supratman, Kandang Limun, Kec. Muara Bangka Hulu, Sumatera, Bengkulu 38371',
                        'perguruan_tinggi'                  =>  'Universitas Bengkulu',
                    ]);
                }
            }
        }

        return redirect()->route('operator.dosen')->with(['success' =>  'Data Dosen Berhasil Diupdate !!']);
    }

    public function dataTable(){
        DB::statement(DB::raw('set @rownum=0'));
        $model = Dosen::select('id','nm_lengkap','nip','nidn','pangkat','golongan','jenis_kelamin','jabatan_fungsional',
                                'prodi_kode','prodi','fakultas_kode','fakultas','keahlian','alamat_institusi','telephone',
                                'fax','email',DB::raw('@rownum  := @rownum  + 1 AS rownum'))
                        ->get();
        return DataTables::of($model)
               ->make(true);
    }
}
