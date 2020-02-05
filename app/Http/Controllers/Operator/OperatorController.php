<?php

namespace App\Http\Controllers\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class OperatorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $operators = User::select('id','nm_user','username','email')->get();
        return view('operator/manajemen_operator.index',compact('operators'));
    }

    public function post(Request $request){
        $operator = new User;
        $operator->nm_user = $request->nm_user;
        $operator->username = $request->username;
        $operator->email = $request->email;
        $operator->password = bcrypt($request->password);
        $operator->save();

        return redirect()->route('operator.operator')->with(['success' =>  'Operator baru berhasil ditambahkan !']);
    }

    public function edit($id){
        $operator = User::find($id);
        return $operator;
    }

    public function update(Request $request){
        $operator = User::find($request->id);
        $operator->nm_user = $request->nm_user;
        $operator->username = $request->username;
        $operator->email = $request->email;
        $operator->update();

        return redirect()->route('operator.operator')->with(['success' =>  'Operator berhasil diubah !']);
    }

    public function delete(Request $request){
        $operator = User::find($request->id);
        $operator->delete();

        return redirect()->route('operator.operator')->with(['success' =>  'Operator berhasil dihapus !']);
    }

    public function cariEmail(Request $request){
        $data = User::select('email')->where('email',$request->email)->get();
        $datas = count($data);
        return response()->json($datas);
    }

    public function cariUsername(Request $request){
        $data = User::select('username')->where('username',$request->username)->get();
        $datas = count($data);
        return response()->json($datas);
    }

    public function aktifkanStatus($id){
        $operator = User::where('id',$id)->update([
            'status'    =>  '1',
        ]);
        return $operator;
    }

    public function nonaktifkanStatus($id){
        $operator = User::where('id',$id)->update([
            'status'    =>  '0',
        ]);
        return $operator;
    }

    public function updatePassword(Request $request){
        $operator = User::find($request->id);
        $operator->password = bcrypt($request->password2);
        $operator->update();

        return redirect()->route('operator.operator')->with(['success' =>  'Password Operator berhasil diubah !']);
    }
}

