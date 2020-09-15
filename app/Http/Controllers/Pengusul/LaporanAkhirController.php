<?php

namespace App\Http\Controllers\Pengusul;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;
use App\Usulan;
use App\LaporanAkhir;
use App\LuaranKegiatan;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class LaporanAkhirController extends Controller
{
    public function index(){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                $usulans = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                                    ->leftJoin('luaran_kegiatans','luaran_kegiatans.usulan_id','usulans.id')
                                    ->leftJoin('laporan_akhirs','laporan_akhirs.usulan_id','usulans.id')
                                    ->select('usulans.id','judul_kegiatan','file_akhir','usulans.jenis_kegiatan','ketua_peneliti_nama','tahun_usulan','status','status_usulan',
                                            DB::raw('COUNT(luaran_kegiatans.judul_luaran) as judul_luaran')
                                    )
                                    ->where('usulans.ketua_peneliti_nip',Session::get('nip'))
                                    ->where('status_usulan','4')
                                    ->groupBy('usulans.id')
                                    ->get();
                return view('pengusul/usulan/laporan_akhir.index',compact('usulans'));
            }
            else{
                Session::flush();
                return redirect()->route('panda.login.form')->with(['error' => 'Anda Tidak Memiliki Akses Login']);
            }
        }
        else{
            return redirect()->route('panda.login.form')->with(['error' => 'Masukan Username dan Password Terlebih Dahulu !!']);
        }
    }

    public function uploadLaporan(Request $request){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                $slug = Str::slug(Session::get('nm_dosen'));
                // $model = $request->all();
                // $model['laporan_akhir'] = null;
                // if ($request->hasFile('laporan_akhir')) {
                //     $model['laporan_akhir'] = Session::get('nip').'-'.date('now').$request->id_usulan.'-'.$request->id_usulan.uniqid().'.'.$request->laporan_akhir->getClientOriginalExtension();
                //     $request->laporan_akhir->move(public_path('/upload/laporan_akhir'), $model['laporan_akhir']);
                // }
                // $laporan = new LaporanAkhir;
                // $laporan->usulan_id = $request->id_usulan;
                // $laporan->file_akhir = $model['laporan_akhir'];
                // $laporan->save();

                $laporan_akhir = $request->file('laporan_akhir');
                $laporan_akhirUrl = $laporan_akhir->store('laporan_akhir/'.$slug.'-'.Session::get('nip'));
                
                LaporanAkhir::create([
                    'usulan_id' =>  $request->id_usulan,
                    'file_akhir' =>  $laporan_akhirUrl,
                ]);

                return redirect()->route('pengusul.laporan_akhir')->with(['success'  =>  'File Laporan Akhir Berhasil Diupload !!']);
            }
            else{
                Session::flush();
                return redirect()->route('panda.login.form')->with(['error' => 'Anda Tidak Memiliki Akses Login']);
            }
        }
        else{
            return redirect()->route('panda.login.form')->with(['error' => 'Masukan Username dan Password Terlebih Dahulu !!']);
        }
    }

    public function detailJudul($id){
        $judul = Usulan::find($id);
        return $judul;
    }

    public function konfirmasi(Request $request){
        DB::beginTransaction();
        try {
            LaporanAkhir::where('usulan_id', $request->id_usulan)->update([
                'status'    =>  '1',
            ]);
            Usulan::where('id',$request->id_usulan)->update([
                'status_usulan' =>  '5',
            ]);
            DB::commit();
            return redirect()->route('pengusul.laporan_akhir')->with(['success' =>  'Laporan Akhir dan Luaran Berhasil di Konfirmasi !!']);
            
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('pengusul.laporan_akhir')->with(['success' =>  'Laporan Akhir dan Luaran Berhasil di Konfirmasi !!']);
        }
    }

    public function luaran($id){
        $luarans = Usulan::rightJoin('luaran_kegiatans','luaran_kegiatans.usulan_id','usulans.id')
                                    ->select('luaran_kegiatans.id','usulans.id as usulan_id','judul_kegiatan','ketua_peneliti_nama','tahun_usulan','usulans.jenis_kegiatan','jenis_publikasi','judul_luaran')
                                    ->where('usulans.id',$id)
                                    ->get();

        $kegiatan_id = $id;
        $jenis_kegiatan = Usulan::select('jenis_kegiatan')->where('id',$kegiatan_id)->get();
        $jenis_kegiatan2 = Usulan::select('jenis_kegiatan')->where('id',$kegiatan_id)->get();
        return view('pengusul/usulan/laporan_akhir.luaran',compact('luarans','kegiatan_id','jenis_kegiatan','jenis_kegiatan2'));
    }

    public function luaranPost(Request $request){
        $usulan_id = $request->usulan_id;
        $luaran = new LuaranKegiatan;
        $luaran->usulan_id = $usulan_id;
        $luaran->judul_luaran = $request->judul_luaran;
        $luaran->jenis_publikasi = $request->jenis_publikasi;
        $luaran->jenis_kegiatan = $request->jenis_kegiatan;
        $luaran->save();

        return redirect()->route('pengusul.laporan_akhir.luaran',[$usulan_id])->with(['success'  =>  'Berhasil, Luaran Kegiatan Sudah Ditambahkan !!']);
    }

    public function cariPublikasi(Request $request){
        $client = new Client();
        $jenis_kegiatan = $request->jenis_kegiatan;
        $jenis_publikasi = json_decode($client->get('https://prisma.unib.ac.id/Api/publikasi_api')->getBody()->getContents(),true);
        $data = [
            'jenis_kegiatan'    =>  ucwords($jenis_kegiatan),
            'jenis_publikasi'             =>  $jenis_publikasi,
        ];

        return $data;
    }

    public function editLuaran($id){
        $luaran = LuaranKegiatan::find($id);
        $data = [
            'jenis_kegiatan'    =>  ucwords($luaran->jenis_kegiatan),
            'judul_luaran'      =>  $luaran->judul_luaran,
        ];
        return $data;
    }

    public function updateLuaran(Request $request){
        $luaran = LuaranKegiatan::where('id',$request->luaran_id_edit)->update([
            'judul_luaran'  =>  $request->judul_luaran_edit
        ]);

        return redirect()->route('pengusul.laporan_akhir.luaran',[$request->usulan_id_edit])->with(['success'  =>  'Berhasil, Luaran Kegiatan Berhasil Diubah !!']);
    }

    public function hapusLuaran(Request $request){
        $luaran = LuaranKegiatan::find($request->luaran_id_hapus);
        $luaran->delete();

        return redirect()->route('pengusul.laporan_akhir.luaran',[$request->usulan_id_hapus])->with(['success'  =>  'Berhasil, Luaran Kegiatan Berhasil Dihapus !!']);
    }

}
