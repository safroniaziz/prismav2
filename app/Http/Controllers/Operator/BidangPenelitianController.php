<?php

namespace App\Http\Controllers\Operator;

use App\BidangPenelitian;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BidangPenelitianController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $bidangs = BidangPenelitian::select('id','nm_bidang')->get();
        return view('operator/bidang_penelitian.index',compact('bidangs'));
    }

    public function post(Request $request){
        $bidang = new BidangPenelitian;
        $bidang->nm_bidang = $request->nm_bidang;
        $bidang->save();

        return redirect()->route('operator.bidang')->with(['success' =>  'Bidang Penelitian baru berhasil ditambahkan !']);
    }

    public function edit($id){
        $bidang = BidangPenelitian::find($id);
        return $bidang;
    }

    public function update(Request $request){
        $bidang = BidangPenelitian::find($request->id);
        $bidang->nm_bidang = $request->nm_bidang;
        $bidang->update();

        return redirect()->route('operator.bidang')->with(['success' =>  'Bidang Penelitian berhasil diubah !']);
    }

    public function delete(Request $request){
        $bidang = BidangPenelitian::find($request->id);
        $bidang->delete();

        return redirect()->route('operator.bidang')->with(['success' =>  'Bidang Penelitian berhasil dihapus !']);
    }
}
