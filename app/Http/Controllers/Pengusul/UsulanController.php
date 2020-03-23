<?php

namespace App\Http\Controllers\Pengusul;

use App\AnggaranBahanHabisPakai;
use App\AnggaranHonorOutput;
use App\AnggaranPeralatanPenunjang;
use App\AnggaranPerjalananLainnya;
use App\AnggotaUsulan;
use App\BidangPenelitian;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserLoginController;
use Session;
use Auth;
use DB;
use PDF;
use App\Dosen;
use App\Skim;
use App\Usulan;
use App\Fakultas;
use App\Prodi;
use App\RancanganAnggaran;

if(version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}
class UsulanController extends Controller
{
    public function index(){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                $usulans = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                                    ->leftJoin('skims','skims.id','usulans.skim_id')
                                    ->leftJoin('rancangan_anggarans','rancangan_anggarans.usulan_id','usulans.id')
                                    ->groupBy('usulans.id','rancangan_anggarans.id')
                                    ->where('usulans.ketua_peneliti_nip',Session::get('nip'))
                                    ->select('usulans.id','judul_kegiatan','jenis_kegiatan',
                                            'ketua_peneliti_nama','abstrak','kata_kunci','peta_jalan','file_usulan','biaya_diusulkan','status_usulan','tahun_usulan',
                                            DB::raw('group_concat(anggota_usulans.anggota_nama SEPARATOR "<br>") as "nm_anggota" '))
                                    ->get();
                $skims  =   Skim::select('id','nm_skim')->where('tahun',date('Y'))->get();
                $bidangs  =   BidangPenelitian::select('id','nm_bidang')->get();
                return view('pengusul/usulan.index',compact('usulans','skims','bidangs','fakultas'));
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

    public function post(Request $request){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                $model = $request->all();
                $model['peta_jalan'] = null;
                $model['file_usulan'] = null;
                if ($request->hasFile('peta_jalan')) {
                    $model['peta_jalan'] = Session::get('nip').'-'.date('now').$request->skim_id.'-'.$request->tahun_usulan.uniqid().'.'.$request->peta_jalan->getClientOriginalExtension();
                    $request->peta_jalan->move(public_path('/upload/peta_jalan'), $model['peta_jalan']);
                }

                if ($request->hasFile('file_usulan')) {
                    $model['file_usulan'] = Session::get('nip').'-'.date('now').$request->skim_id.'-'.$request->tahun_usulan.uniqid().'.'.$request->file_usulan->getClientOriginalExtension();
                    $request->file_usulan->move(public_path('/upload/file_usulan'), $model['file_usulan']);
                }

                $usulan = new Usulan;
                $usulan->judul_kegiatan = $request->judul_kegiatan;
                $usulan->skim_id = $request->skim_id;
                $usulan->jenis_kegiatan = $request->jenis_kegiatan;
                $usulan->ketua_peneliti_nip = Session::get('nip');
                $usulan->ketua_peneliti_nama = Session::get('nm_dosen');
                $usulan->ketua_peneliti_prodi_id = Session::get('prodi_kode');
                $usulan->ketua_peneliti_prodi_nama = Session::get('prodi_nama');
                $usulan->ketua_peneliti_nidn = Session::get('nidn');
                $usulan->ketua_peneliti_fakultas_id = Session::get('fakultas_kode');
                $usulan->ketua_peneliti_fakultas_nama = Session::get('fakultas_nama');
                $usulan->ketua_peneliti_jabatan_fungsional = Session::get('jabatan');
                $usulan->ketua_peneliti_jk = Session::get('jk');
                $usulan->ketua_peneliti_universitas = "Universitas Bengkulu";
                $usulan->abstrak    =   $request->abstrak;
                $usulan->kata_kunci =   implode(',',$request->kata_kunci);
                $usulan->peta_jalan    =   $model['peta_jalan'];
                $usulan->file_usulan    =   $model['file_usulan'];
                $usulan->tujuan    =   $model['tujuan'];
                $usulan->luaran    =   $model['luaran'];
                $usulan->biaya_diusulkan    =   $request->biaya_diusulkan;
                $usulan->tahun_usulan    =   date('Y');
                $usulan->save();

                return redirect()->route('pengusul.usulan')->with(['success' =>  'Usulan baru berhasil ditambahkan !']);
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

    public function edit($id){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                $usulan = Usulan::select('id','judul_kegiatan','jenis_kegiatan','skim_id','ketua_peneliti_nip as ketua_peneliti_id','abstrak','peta_jalan','file_usulan','biaya_diusulkan','tujuan','luaran','tahun_usulan')
                            ->where('id',$id)
                            ->first();
                return $usulan;
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

    public function update(Request $request){
        $usulan = Usulan::find($request->id);
        $input['peta_jalan'] = $usulan->peta_jalan;

        if ($request->hasFile('peta_jalan')) {
        	if ($usulan->peta_jalan != null) {
        		unlink(public_path('/upload/peta_jalan/'.$usulan->peta_jalan));
        	}
            $input['peta_jalan'] = Session::get('nip').'-'.date('now').$request->skim_id.'-'.$request->tahun_usulan.uniqid().'.'.$request->peta_jalan->getClientOriginalExtension();
        	$request->peta_jalan->move(public_path('/upload/peta_jalan'), $input['peta_jalan']);
        }
        $kata_kunci = Usulan::select('kata_kunci')->where('id',$request->id)->first();
        $model = $request->all();
        if ($request->kata_kunci[0] == null || $request->kata_kunci[0] == "") {
            $usulan->judul_kegiatan = $request->judul_kegiatan;
            $usulan->skim_id = $request->skim_id;
            $usulan->jenis_kegiatan = $request->jenis_kegiatan;
            $usulan->ketua_peneliti_nip = Session::get('nip');
            $usulan->ketua_peneliti_nama = Session::get('nm_dosen');
            $usulan->ketua_peneliti_prodi_id = Session::get('prodi_kode');
            $usulan->ketua_peneliti_prodi_nama = Session::get('prodi_nama');
            $usulan->ketua_peneliti_nidn = Session::get('nidn');
            $usulan->ketua_peneliti_fakultas_id = Session::get('fakultas_kode');
            $usulan->ketua_peneliti_fakultas_nama = Session::get('fakultas_nama');
            $usulan->ketua_peneliti_jabatan_fungsional = Session::get('jabatan');
            $usulan->ketua_peneliti_jk = Session::get('jk');
            $usulan->ketua_peneliti_universitas = "Universitas Bengkulu";
            $usulan->abstrak    =   $request->abstrak_edit;
            $usulan->kata_kunci = $kata_kunci->kata_kunci;
            $usulan->peta_jalan    =   $input['peta_jalan'];
            $usulan->file_usulan    =   $input['file_usulan'];
            $usulan->tujuan    =   $input['tujuan'];
            $usulan->luaran    =   $input['luaran'];
            $usulan->biaya_diusulkan    =   $request->biaya_diusulkan;
            $usulan->tahun_usulan    =   date('Y');
            $usulan->update();
        }
        else{
            $usulan->judul_kegiatan = $request->judul_kegiatan;
            $usulan->skim_id = $request->skim_id;
            $usulan->jenis_kegiatan = $request->jenis_kegiatan;
            $usulan->ketua_peneliti_nip = Session::get('nip');
            $usulan->ketua_peneliti_nama = Session::get('nm_dosen');
            $usulan->ketua_peneliti_prodi_id = Session::get('prodi_kode');
            $usulan->ketua_peneliti_prodi_nama = Session::get('prodi_nama');
            $usulan->ketua_peneliti_nidn = Session::get('nidn');
            $usulan->ketua_peneliti_fakultas_id = Session::get('fakultas_kode');
            $usulan->ketua_peneliti_fakultas_nama = Session::get('fakultas_nama');
            $usulan->ketua_peneliti_jabatan_fungsional = Session::get('jabatan');
            $usulan->ketua_peneliti_jk = Session::get('jk');
            $usulan->ketua_peneliti_universitas = "Universitas Bengkulu";
            $usulan->abstrak    =   $request->abstrak_edit;
            $usulan->kata_kunci =   implode(',',$request->kata_kunci);
            $usulan->peta_jalan    =   $input['peta_jalan'];
            $usulan->file_usulan    =   $input['file_usulan'];
            $usulan->tujuan    =   $input['tujuan'];
            $usulan->luaran    =   $input['luaran'];
            $usulan->biaya_diusulkan    =   $request->biaya_diusulkan;
            $usulan->tahun_usulan    =   date('Y');
            $usulan->update();
        }


        return redirect()->route('pengusul.usulan')->with(['success' =>  'Usulan berhasil diubah !']);
    }

    public function delete(Request $request){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                $usulan = Usulan::find($request->id);
                $usulan->delete();

                return redirect()->route('pengusul.usulan')->with(['success' =>  'Usulan berhasil dihapus !']);
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

    public function anggotaPost(Request $request){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){

                $sudah = AnggotaUsulan::select('anggota_nip')->where('anggota_nip',$request->nip_anggota)->first();
                if (count($sudah) != 0) {
                    return redirect()->route('pengusul.usulan.detail_anggota',[$request->usulan_id_anggaran])->with(['error' =>  'Anggota yang dipilih sudah ditambahkan !']);
                }
                else{
                    $anggota = new AnggotaUsulan;
                    $anggota->usulan_id = $request->usulan_id_anggaran;
                    $anggota->anggota_nip = $request->nip_anggota;
                    $anggota->anggota_nama = $request->nm_anggota;
                    $anggota->anggota_prodi_id = $request->prodi_kode_anggota;
                    $anggota->anggota_prodi_nama = $request->prodi_anggota;
                    $anggota->anggota_fakultas_id = $request->fakultas_kode_anggota;
                    $anggota->anggota_fakultas_nama = $request->fakultas_anggota;
                    $anggota->anggota_jabatan_fungsional = $request->jabatan_anggota;
                    $anggota->anggota_jk = $request->jk_anggota;
                    $anggota->anggota_universitas = "Universitas Bengkulu";
                    $anggota->save();
                    return redirect()->route('pengusul.usulan.detail_anggota',[$request->usulan_id_anggaran])->with(['success' =>  'Anggota berhasil ditambahkan !']);
                }
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

    public function hapusAnggota(Request $request){
        $anggota = AnggotaUsulan::find($request->id);
        $anggota->delete();

        return redirect()->route('pengusul.usulan.detail_anggota',[$request->id_usulan])->with(['success' =>  'Anggota kegiatan berhasil dihapus !']);
    }

    public function usulkan(Request $request){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                $usulan = Usulan::find($request->usulan_id_usulkan);
                $usulan->status_usulan = "1";
                $usulan->update();
                return redirect()->route('pengusul.usulan')->with(['success' =>  'Usulan Penelitian berhasil diteruskan !']);
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

    public function anggaranHonorPost(Request $request){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                if ($request->usulan_id_anggaran != null && $request->usulan_id_anggaran != "") {
                    $sudah = RancanganAnggaran::select('usulan_id')->where('usulan_id',$request->usulan_id_anggaran)->first();
                    if ($sudah != null || $sudah != "") {
                    }else{
                        $rancangan = new RancanganAnggaran;
                        $rancangan->usulan_id = $request->usulan_id_anggaran;
                        $rancangan->save();
                    }

                    $anggaran_id = RancanganAnggaran::select('id')->where('usulan_id',$request->usulan_id_anggaran)->first();

                    $anggaran = new AnggaranHonorOutput;
                    $anggaran->rancangan_anggaran_id = $anggaran_id->id;
                    $anggaran->keterangan_honor = $request->keterangan_honor;
                    $anggaran->biaya = $request->biaya;
                    $anggaran->hari_per_minggu = $request->hari_per_minggu;
                    $anggaran->jumlah_minggu = $request->jumlah_minggu;
                    $anggaran->save();
                }

                return redirect()->back()->with(['success' =>  'Anggaran berhasil ditambahkan !']);
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

    public function hapusHonor(Request $request){
        $anggota = AnggaranHonorOutput::find($request->id_anggaran);
        $anggota->delete();

        return redirect()->route('pengusul.usulan.detail_anggaran',[$request->id_usulan])->with(['success' =>  'Anggaran Honor Output kegiatan berhasil dihapus !']);
    }

    public function hapusHabis(Request $request){
        $anggota = AnggaranBahanHabisPakai::find($request->id_anggaran_habis);
        $anggota->delete();

        return redirect()->route('pengusul.usulan.detail_anggaran',[$request->id_usulan])->with(['success' =>  'Anggaran Belanja Bahan Habis Pakai berhasil dihapus !']);
    }

    public function hapusPenunjang(Request $request){
        $anggota = AnggaranPeralatanPenunjang::find($request->id_anggaran_penunjang);
        $anggota->delete();

        return redirect()->route('pengusul.usulan.detail_anggaran',[$request->id_usulan])->with(['success' =>  'Anggaran Peralatan Penunjang berhasil dihapus !']);
    }

    public function hapusLainnya(Request $request){
        $anggota = AnggaranPerjalananLainnya::find($request->id_anggaran_lainnya);
        $anggota->delete();

        return redirect()->route('pengusul.usulan.detail_anggaran',[$request->id_usulan])->with(['success' =>  'Anggaran Perjalanan Lainnya berhasil dihapus !']);
    }

    public function anggaranHabisPost(Request $request){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                if ($request->usulan_id_anggaran_habis != null && $request->usulan_id_anggaran_habis != "") {
                    $sudah = RancanganAnggaran::select('usulan_id')->where('usulan_id',$request->usulan_id_anggaran_habis)->first();
                    if ($sudah != null || $sudah != "") {
                    }else{
                        $rancangan = new RancanganAnggaran;
                        $rancangan->usulan_id = $request->usulan_id_anggaran_habis;
                        $rancangan->save();
                    }

                    $anggaran_id = RancanganAnggaran::select('id')->where('usulan_id',$request->usulan_id_anggaran_habis)->first();

                    $anggaran = new AnggaranBahanHabisPakai;
                    $anggaran->rancangan_anggaran_id = $anggaran_id->id;
                    $anggaran->material = $request->material_habis;
                    $anggaran->justifikasi_pembelian = $request->justifikasi_pembelian_habis;
                    $anggaran->kuantitas = $request->kuantitas_habis;
                    $anggaran->harga_satuan = $request->harga_satuan_habis;
                    $anggaran->save();
                }

                return redirect()->back()->with(['success' =>  'Anggaran berhasil ditambahkan !']);
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

    public function anggaranPenunjangPost(Request $request){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                if ($request->usulan_id_anggaran_penunjang != null && $request->usulan_id_anggaran_penunjang != "") {
                    $sudah = RancanganAnggaran::select('usulan_id')->where('usulan_id',$request->usulan_id_anggaran_penunjang)->first();
                    if ($sudah != null || $sudah != "") {
                    }else{
                        $rancangan = new RancanganAnggaran;
                        $rancangan->usulan_id = $request->usulan_id_anggaran_penunjang;
                        $rancangan->save();
                    }

                    $anggaran_id = RancanganAnggaran::select('id')->where('usulan_id',$request->usulan_id_anggaran_penunjang)->first();

                    $anggaran = new AnggaranPeralatanPenunjang();
                    $anggaran->rancangan_anggaran_id = $anggaran_id->id;
                    $anggaran->material = $request->material_penunjang;
                    $anggaran->justifikasi_pembelian = $request->justifikasi_pembelian_penunjang;
                    $anggaran->kuantitas = $request->kuantitas_penunjang;
                    $anggaran->harga_satuan = $request->harga_satuan_penunjang;
                    $anggaran->save();
                }

                return redirect()->back()->with(['success' =>  'Anggaran berhasil ditambahkan !']);
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

    public function anggaranLainnyaPost(Request $request){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                if ($request->usulan_id_anggaran_lainnya != null && $request->usulan_id_anggaran_lainnya != "") {
                    $sudah = RancanganAnggaran::select('usulan_id')->where('usulan_id',$request->usulan_id_anggaran_lainnya)->first();
                    if ($sudah != null || $sudah != "") {
                    }else{
                        $rancangan = new RancanganAnggaran;
                        $rancangan->usulan_id = $request->usulan_id_anggaran_lainnya;
                        $rancangan->save();
                    }

                    $anggaran_id = RancanganAnggaran::select('id')->where('usulan_id',$request->usulan_id_anggaran_lainnya)->first();

                    $anggaran = new AnggaranPerjalananLainnya();
                    $anggaran->rancangan_anggaran_id = $anggaran_id->id;
                    $anggaran->material = $request->material_lainnya;
                    $anggaran->justifikasi_pembelian = $request->justifikasi_pembelian_lainnya;
                    $anggaran->kuantitas = $request->kuantitas_lainnya;
                    $anggaran->harga_satuan = $request->harga_satuan_lainnya;
                    $anggaran->save();
                }

                return redirect()->back()->with(['success' =>  'Anggaran berhasil ditambahkan !']);
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

    public function anggaranCetak($id){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                $outputs = RancanganAnggaran::leftJoin('anggaran_honor_outputs','anggaran_honor_outputs.rancangan_anggaran_id','rancangan_anggarans.id')
                                            ->where('usulan_id',$id)
                                            ->get();
                $habis_pakais = RancanganAnggaran::leftJoin('anggaran_bahan_habis_pakais','anggaran_bahan_habis_pakais.rancangan_anggaran_id','rancangan_anggarans.id')
                                            ->where('usulan_id',$id)
                                            ->get();
                $penunjangs = RancanganAnggaran::leftJoin('anggaran_peralatan_penunjangs','anggaran_peralatan_penunjangs.rancangan_anggaran_id','rancangan_anggarans.id')
                                            ->where('usulan_id',$id)
                                            ->get();
                $lainnya = RancanganAnggaran::leftJoin('anggaran_perjalanan_lainnyas','anggaran_perjalanan_lainnyas.rancangan_anggaran_id','rancangan_anggarans.id')
                                            ->where('usulan_id',$id)
                                            ->get();
                $pdf = PDF::loadView('pengusul/usulan.cetak',compact('outputs','habis_pakais','penunjangs','lainnya'));
                $pdf->setPaper('a4', 'portrait');
                return $pdf->stream();
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

    public function getAnggaran($id){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                $habis = AnggaranBahanHabisPakai::join('rancangan_anggarans','rancangan_anggarans.id','anggaran_bahan_habis_pakais.rancangan_anggaran_id')
                            ->select(DB::raw('COUNT(anggaran_bahan_habis_pakais.id) as jumlah'))
                            ->where('rancangan_anggarans.usulan_id',$id)
                            ->get();
                $outputs = AnggaranHonorOutput::join('rancangan_anggarans','rancangan_anggarans.id','anggaran_honor_outputs.rancangan_anggaran_id')
                            ->select(DB::raw('COUNT(anggaran_honor_outputs.id) as jumlah'))
                            ->where('rancangan_anggarans.usulan_id',$id)
                            ->get();
                $penunjangs = AnggaranPeralatanPenunjang::join('rancangan_anggarans','rancangan_anggarans.id','anggaran_peralatan_penunjangs.rancangan_anggaran_id')
                            ->select(DB::raw('COUNT(anggaran_peralatan_penunjangs.id) as jumlah'))
                            ->where('rancangan_anggarans.usulan_id',$id)
                            ->get();
                $lainnya = AnggaranPerjalananLainnya::join('rancangan_anggarans','rancangan_anggarans.id','anggaran_perjalanan_lainnyas.rancangan_anggaran_id')
                            ->select(DB::raw('COUNT(anggaran_perjalanan_lainnyas.id) as jumlah'))
                            ->where('rancangan_anggarans.usulan_id',$id)
                            ->get();
                $usulan = Usulan::select('judul_kegiatan')->where('id',$id)->first();
                $data = [
                    'habis'    =>  $habis,
                    'outputs'    => $outputs,
                    'lainnya'    => $lainnya,
                    'penunjangs'    => $penunjangs,
                ];
                Session::put('usulan_id',$id);
                return $data;
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

    public function detailAnggaran($id){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                $outputs = RancanganAnggaran::leftJoin('anggaran_honor_outputs','anggaran_honor_outputs.rancangan_anggaran_id','rancangan_anggarans.id')
                                            ->where('usulan_id',$id)
                                            ->get();
                $habis_pakais = RancanganAnggaran::leftJoin('anggaran_bahan_habis_pakais','anggaran_bahan_habis_pakais.rancangan_anggaran_id','rancangan_anggarans.id')
                                            ->where('usulan_id',$id)
                                            ->get();
                $penunjangs = RancanganAnggaran::leftJoin('anggaran_peralatan_penunjangs','anggaran_peralatan_penunjangs.rancangan_anggaran_id','rancangan_anggarans.id')
                                            ->where('usulan_id',$id)
                                            ->get();
                $lainnyas = RancanganAnggaran::leftJoin('anggaran_perjalanan_lainnyas','anggaran_perjalanan_lainnyas.rancangan_anggaran_id','rancangan_anggarans.id')
                                            ->where('usulan_id',$id)
                                            ->get();
                $id_usulan = $id;
                $judul_kegiatan = Usulan::select('judul_kegiatan')->where('id',$id)->first();
                return view('pengusul/usulan.detail_anggaran',compact('outputs','habis_pakais','penunjangs','lainnyas','id_usulan','judul_kegiatan'));
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

    public function detailAnggota($id){
        $anggotas = AnggotaUsulan::join('usulans','usulans.id','anggota_usulans.usulan_id')
                                    ->select('anggota_usulans.id','anggota_nip','anggota_nama','anggota_prodi_nama','anggota_fakultas_nama','judul_kegiatan')
                                    ->get();
        $id_usulan = $id;
        $jumlah = count($anggotas);
        $judul_kegiatan = Usulan::select('judul_kegiatan')->where('id',$id)->first();
        return view('pengusul/usulan.anggota',compact('anggotas','id_usulan','judul_kegiatan','jumlah'));
    }

    public function cariAnggota(Request $request){
        $panda = new UserLoginController();
        $dosen = '
        {pegawai(pegNama:"'.$request->nm_anggota.'") {
            pegNip
            pegIsAktif
            pegNama
            pegawai_simpeg {
                pegJenkel
                pegNmJabatan
            }
            dosen {
              prodi {
                prodiKode
                prodiNamaResmi
                fakultas {
                  fakKode
                  fakKodeUniv
                  fakNamaResmi
                }
              }
            }
          }}
        ';
        $dosens = $panda->panda($dosen);
        $datas = count($dosens['pegawai']);
        $data = [
            'jumlah'    =>  $datas,
            'detail'    =>  $dosens,
        ];
        if($data['jumlah'] == 1){
            return response()->json($data);
        }
    }
}
