<?php

namespace App\Http\Controllers\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Reviewer;
use Exception;
use Illuminate\Support\Facades\DB;

class ReviewerEksternalController extends Controller
{
    public function index(){
        $reviewers = Reviewer::where('jenis_reviewer','eksternal')->get();
        return view('operator/manajemen_reviewer/eksternal.index',compact('reviewers'));
    }

    public function add(){
        return view('operator/manajemen_reviewer/eksternal.add');
    }

    public function post(Request $request){
        $this->validate($request,[
            'nip'   =>  'required',
            'nama'  =>  'required',
            'jenis_kelamin'  =>  'required',
            'universitas'  =>  'required',
            'jabatan_fungsional'  =>  'required',
            'password'  =>  'required',
        ]);
        Reviewer::create([
            'nip'   =>  $request->nip,
            'nama'   =>  $request->nama,
            'jenis_kelamin'   =>  $request->jenis_kelamin,
            'jabatan_fungsional'   =>  $request->jabatan_fungsional,
            'universitas'   =>  $request->universitas,
            'jenis_reviewer'    =>  'eksternal',
            'nidn'              =>  $request->nidn,
            'password'          =>  bcrypt($request->password),
        ]);
        return redirect()->route('operator.reviewer_eksternal')->with(['success' =>  'Reviewer baru berhasil ditambahkan']);
    }

    public function edit($id){
        $reviewer = Reviewer::find($id);
        return view('operator/manajemen_reviewer/eksternal.edit',compact('reviewer'));
    }

    public function update(Request $request){
        Reviewer::where('id',$request->id)->update([
            'nip'   =>  $request->nip,
            'nama'   =>  $request->nama,
            'jenis_kelamin'   =>  $request->jenis_kelamin,
            'universitas'   =>  $request->universitas,
            'nidn'   =>  $request->nidn,
            'jabatan_fungsional'   =>  $request->jabatan_fungsional,
            'jenis_reviewer'    =>  'eksternal',
        ]);
        return redirect()->route('operator.reviewer_eksternal')->with(['success' =>  'Data reviewer berhasil ditambahkan']);
    }

    public function delete(Request $request){
        Reviewer::destroy($request->id);
        return redirect()->route('operator.reviewer_eksternal')->with(['success' =>  'Data reviewer berhasil dihapus']);
    }

    public function updatePassword(Request $request){
        Reviewer::where('id',$request->id)->update([
            'password'  =>  bcrypt($request->password),
        ]);
        return redirect()->route('operator.reviewer_eksternal')->with(['success' =>  'Password reviewer berhasil diubah']);
    }

    public function nonaktifkanStatus($id){
        Reviewer::where('id',$id)->update([
            'status'    =>  '0'
        ]);

        return redirect()->route('operator.reviewer_eksternal')->with(['success' =>  'Status reviewer berhasil dinonaktifkan']);
    }

    public function aktifkanStatus($id){
        Reviewer::where('id',$id)->update([
            'status'    =>  '1'
        ]);

        return redirect()->route('operator.reviewer_eksternal')->with(['success' =>  'Status reviewer berhasil diaktifkan']);
    }

}
