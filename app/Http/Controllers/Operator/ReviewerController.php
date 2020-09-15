<?php

namespace App\Http\Controllers\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserLoginController;
use App\Reviewer;
use Exception;
use Illuminate\Support\Facades\DB;

class ReviewerController extends Controller
{
    public function index(){
        $reviewers = Reviewer::where('jenis_reviewer','internal')->get();
        return view('operator/manajemen_reviewer/internal.index',compact('reviewers'));
    }

    public function add(){
        $panda = new UserLoginController();
        $fakultas = '
            {fakultas {
                fakKode
                fakNamaResmi
            }}
        ';
        $fakultases = $panda->panda($fakultas);
        return view('operator/manajemen_reviewer/internal.add',compact('fakultases'));
    }

    public function cariProdi(Request $request){
        $fakultas = $request->fakultas;
        $panda = new UserLoginController();
        $prodi = '
            {prodi(prodiFakKode:'.$fakultas.') {
            prodiNamaResmi
            prodiKode
          }}
        ';
        $prodis = $panda->panda($prodi);
        return $prodis;
    }

    public function post(Request $request){
        $panda = new UserLoginController();
        $this->validate($request,[
            'nip'   =>  'required',
            'nama'  =>  'required',
            'jenis_kelamin'  =>  'required',
            'universitas'  =>  'required',
            'jabatan_fungsional'  =>  'required',
        ]);
        DB::beginTransaction();
        try {
            $fakultas = '
                {fakultas(fakKode:'.$request->fakultas.') {
                    fakKode
                    fakNamaResmi
                }}
            ';
            $fak = $panda->panda($fakultas);
            
            $prodi = '
                {prodi(prodiKode:'.$request->prodi.') {
                    prodiNamaResmi
                    prodiKode
                }}
            ';
            $prodis = $panda->panda($prodi);
            
            Reviewer::create([
                'nip'   =>  $request->nip,
                'nama'   =>  $request->nama,
                'jenis_kelamin'   =>  $request->jenis_kelamin,
                'universitas'   =>  $request->universitas,
                'nidn'   =>  $request->nidn,
                'jabatan_fungsional'   =>  $request->jabatan_fungsional,
                'fakultas_nama'   =>  $fak['fakultas'][0]['fakNamaResmi'],
                'fakultas_id'       =>  $fak['fakultas'][0]['fakKode'],
                'prodi_nama'   =>  $prodis['prodi'][0]['prodiNamaResmi'],
                'prodi_id'   =>  $prodis['prodi'][0]['prodiKode'],
                'jenis_reviewer'    =>  'internal',
            ]);
            DB::commit();
            return redirect()->route('operator.reviewer')->with(['success' =>  'Reviewer baru berhasil ditambahkan']);
        } catch (Exception $e) {
            // Rollback Transaction
            DB::rollback();
            // ada yang error
            return redirect()->route('operator.reviewer')->with(['error' =>  'Reviewer baru gagal ditambahkan']);
        }
        
            
    }

    public function edit($id){
        $reviewer = Reviewer::find($id);
        $panda = new UserLoginController();
        $fakultas = '
            {fakultas {
                fakKode
                fakNamaResmi
            }}
        ';
        $fakultases = $panda->panda($fakultas);
        $prodi = '
            {prodi{
                prodiNamaResmi
                prodiKode
            }}
        ';
        $prodis = $panda->panda($prodi);
        return view('operator/manajemen_reviewer/internal.edit',compact('reviewer','fakultases','prodis'));
    }

    public function update(Request $request){
        $panda = new UserLoginController();
        $this->validate($request,[
            'nip'   =>  'required',
            'nama'   =>  'required',
            'jenis_kelamin'   =>  'required',
            'universitas'   =>  'required',
            'nidn'   =>  'required',
            'fakultas'   =>  'required',
            'prodi'   =>  'required',
        ]);
        DB::beginTransaction();
        try {
            $fakultas = '
                {fakultas(fakKode:'.$request->fakultas.') {
                    fakKode
                    fakNamaResmi
                }}
            ';
            $fak = $panda->panda($fakultas);
            
            $prodi = '
                {prodi(prodiKode:'.$request->prodi.') {
                    prodiNamaResmi
                    prodiKode
                }}
            ';
            $prodis = $panda->panda($prodi);
            Reviewer::where('id',$request->id)->update([
                'nip'   =>  $request->nip,
                'nama'   =>  $request->nama,
                'jenis_kelamin'   =>  $request->jenis_kelamin,
                'universitas'   =>  $request->universitas,
                'nidn'   =>  $request->nidn,
                'jabatan_fungsional'   =>  $request->jabatan_fungsional,
                'fakultas_nama'   =>  $fak['fakultas'][0]['fakNamaResmi'],
                'fakultas_id'       =>  $fak['fakultas'][0]['fakKode'],
                'prodi_nama'   =>  $prodis['prodi'][0]['prodiNamaResmi'],
                'prodi_id'   =>  $prodis['prodi'][0]['prodiKode'],
                'jenis_reviewer'    =>  'internal',
            ]);
            DB::commit();
            return redirect()->route('operator.reviewer')->with(['success' =>  'Data reviewer berhasil diubah']);

        } catch (Exception $e) {
            // Rollback Transaction
            DB::rollback();
            // ada yang error
            return redirect()->route('operator.reviewer')->with(['error' =>  'Reviewer baru gagal diubah']);
        }
    }

    public function delete(Request $request){
        Reviewer::destroy($request->id);
        return redirect()->route('operator.reviewer')->with(['success' =>  'Data reviewer berhasil dihapus']);
    }

    public function updatePassword(Request $request){
        Reviewer::where('id',$request->id)->update([
            'password'  =>  bcrypt($request->password),
        ]);
        return redirect()->route('operator.reviewer')->with(['success' =>  'Password reviewer berhasil diubah']);
    }

    public function nonaktifkanStatus($id){
        Reviewer::where('id',$id)->update([
            'status'    =>  '0'
        ]);

        return redirect()->route('operator.reviewer')->with(['success' =>  'Status reviewer berhasil dinonaktifkan']);
    }

    public function aktifkanStatus($id){
        Reviewer::where('id',$id)->update([
            'status'    =>  '1'
        ]);

        return redirect()->route('operator.reviewer')->with(['success' =>  'Status reviewer berhasil diaktifkan']);
    }

    public function eksternal(){
        // Reviewer::create([
        //     'nip'   =>  $request->nip,
        //     'nama'   =>  $request->nama,
        //     'jenis_kelamin'   =>  $request->jenis_kelamin,
        //     'jenis_reviewer'    =>  $request->jenis_reviewer,
        //     'jabatan_fungsional'   =>  $request->jabatan_fungsional,
        //     'universitas'   =>  $request->universitas,
        //     'password'          =>  bcrypt($request->password),
        // ]);
    }
}
