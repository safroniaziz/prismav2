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

class UsulanController extends Controller
{
    public function index(){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                $usulans = Usulan::leftJoin('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                                    ->leftJoin('skims','skims.id','usulans.skim_id')
                                    ->leftJoin('bidang_penelitians','bidang_penelitians.id','usulans.bidang_id')
                                    ->leftJoin('rancangan_anggarans','rancangan_anggarans.usulan_id','usulans.id')
                                    ->groupBy('usulans.id','rancangan_anggarans.id')
                                    ->where('status_usulan','0')
                                    ->where('usulans.ketua_peneliti_nip',Session::get('nip'))
                                    ->select('usulans.id','judul_penelitian','nm_bidang as bidang_penelitian',
                                            'ketua_peneliti_nama','abstrak','kata_kunci','peta_jalan','biaya_diusulkan','status_usulan',
                                            DB::raw('group_concat(anggota_usulans.anggota_nama SEPARATOR "<br>") as "nm_anggota" '))
                                    ->get();
                $skims  =   Skim::select('id','nm_skim')->where('tahun',date('Y'))->get();
                $bidangs  =   BidangPenelitian::select('id','nm_bidang')->get();
                $fakultas = Fakultas::select('fakultas_kode','nm_fakultas')->get();
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
                if ($request->hasFile('peta_jalan')) {
                    $model['peta_jalan'] = Session::get('nip').'-'.date('now').$request->skim_id.'-'.$request->tahun_usulan.uniqid().'.'.$request->peta_jalan->getClientOriginalExtension();
                    $request->peta_jalan->move(public_path('/upload/peta_jalan'), $model['peta_jalan']);
                }
                $usulan = new Usulan;
                $usulan->judul_penelitian = $request->judul_penelitian;
                $usulan->skim_id = $request->skim_id;
                $usulan->bidang_id = $request->bidang_id;
                $usulan->ketua_peneliti_nip = Session::get('nip');
                $usulan->ketua_peneliti_nama = Session::get('nm_dosen');
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
                $usulan = Usulan::select('id','judul_penelitian','bidang_id','skim_id','ketua_peneliti_nip as ketua_peneliti_id','abstrak','peta_jalan','biaya_diusulkan','tahun_usulan')
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
            $usulan->judul_penelitian = $request->judul_penelitian;
            $usulan->skim_id = $request->skim_id;
            $usulan->bidang_id = $request->bidang_id;
            $usulan->ketua_peneliti_nip = Session::get('nip');
            $usulan->ketua_peneliti_nama = Session::get('nm_dosen');
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
            $usulan->biaya_diusulkan    =   $request->biaya_diusulkan;
            $usulan->tahun_usulan    =   date('Y');
            $usulan->update();
        }
        else{
            $usulan->judul_penelitian = $request->judul_penelitian;
            $usulan->skim_id = $request->skim_id;
            $usulan->bidang_id = $request->bidang_id;
            $usulan->ketua_peneliti_nip = Session::get('nip');
            $usulan->abstrak    =   $request->abstrak_edit;
            $usulan->kata_kunci =   implode(',',$request->kata_kunci);
            $usulan->peta_jalan    =   $input['peta_jalan'];
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

    public function anggotaPost(Request $request){
        $panda = new UserLoginController();
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                $detail = '
                {dosen(dsnPegNip:'.$request->anggota_id.') {
                    dsnPegNip
                    prodi {
                        prodiKode
                        prodiNamaResmi
                        fakultas {
                          fakKode
                          fakNamaResmi
                        }
                      }
                      pegawai{
                        pegNama
                        pegIsAktif
                        pegawai_simpeg {
                            pegJenkel
                            pegNmJabatan
                        }
                      }

                  }}
                ';
                $details = $panda->panda($detail);
                // return $details['dosen'][0]['pegawai']['pegNama'];
                if ($details['dosen'][0]['pegawai']['pegawai_simpeg'] != null) {
                    $anggota = new AnggotaUsulan;
                    $anggota->usulan_id = $request->usulan_id;
                    $anggota->anggota_nip = $request->anggota_id;
                    $anggota->anggota_nama = $details['dosen'][0]['pegawai']['pegNama'];
                    $anggota->anggota_prodi_id = $details['dosen'][0]['prodi']['prodiKode'];
                    $anggota->anggota_prodi_nama = $details['dosen'][0]['prodi']['prodiNamaResmi'];
                    $anggota->anggota_fakultas_id = $details['dosen'][0]['prodi']['fakultas']['fakKode'];
                    $anggota->anggota_fakultas_nama = $details['dosen'][0]['prodi']['fakultas']['fakNamaResmi'];
                    $anggota->anggota_jabatan_fungsional = $details['dosen'][0]['pegawai']['pegawai_simpeg']['pegNmJabatan'];
                    $anggota->anggota_jk = $details['dosen'][0]['pegawai']['pegawai_simpeg']['pegJenkel'];
                    $anggota->anggota_universitas = "Universitas Bengkulu";
                    $anggota->save();
                }
                else{
                    $anggota = new AnggotaUsulan;
                    $anggota->usulan_id = $request->usulan_id;
                    $anggota->anggota_nip = $request->anggota_id;
                    $anggota->anggota_nama = $details['dosen'][0]['pegawai']['pegNama'];
                    $anggota->anggota_prodi_id = $details['dosen'][0]['prodi']['prodiKode'];
                    $anggota->anggota_prodi_nama = $details['dosen'][0]['prodi']['prodiNamaResmi'];
                    $anggota->anggota_fakultas_id = $details['dosen'][0]['prodi']['fakultas']['fakKode'];
                    $anggota->anggota_fakultas_nama = $details['dosen'][0]['prodi']['fakultas']['fakNamaResmi'];
                    $anggota->save();
                }

                return redirect()->route('pengusul.usulan')->with(['success' =>  'Anggota berhasil ditambahkan !']);
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

    public function getAnggota($id){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                $anggotas = AnggotaUsulan::select('anggota_nip','anggota_nama','anggota_prodi_nama','anggota_fakultas_nama')
                                            ->where('usulan_id',$id)
                                            ->groupBy('id')
                                            ->get();
                $usulan = Usulan::select('judul_penelitian')->where('id',$id)->first();
                $data = [
                    'anggotas'    =>  $anggotas,
                    'usulan'    =>  $usulan,
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

    public function cariProdi(Request $request){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                $fakultas_id = $request->fakultas_id;
                $prodi  =   Prodi::select('prodi_kode','nm_prodi')
                                    ->where('fakultas_kode',$fakultas_id)
                                    ->get();
                return $prodi;
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

    public function cariAnggota(Request $request){
        $panda = new UserLoginController();
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                $sudah = AnggotaUsulan::select('anggota_nip')->where('usulan_id',Session::get('usulan_id'))->get();
                $prodi_id = $request->prodi_id;
                $anggotas  =   '
                    {prodi(prodiKode:'.$prodi_id.') {
                        prodiKode
                        dosen {
                        dsnPegNip
                        pegawai {
                            pegNama
                            pegIsAktif
                        }
                        }
                    }}
                ';
                $anggota = $panda->panda($anggotas);
                return $anggota['prodi'][0]['dosen'];
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

    public function anggaranPost(Request $request){
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

                    if ($request->jenis_anggaran == "honor_output") {
                        $anggaran = new AnggaranHonorOutput;
                        $anggaran->rancangan_anggaran_id = $anggaran_id->id;
                        $anggaran->keterangan_honor = $request->keterangan_honor;
                        $anggaran->biaya = $request->biaya;
                        $anggaran->hari_per_minggu = $request->hari_per_minggu;
                        $anggaran->jumlah_minggu = $request->jumlah_minggu;
                        $anggaran->save();
                    }
                    elseif ($request->jenis_anggaran == "bahan_habis") {
                        $anggaran = new AnggaranBahanHabisPakai;
                        $anggaran->rancangan_anggaran_id = $anggaran_id->id;
                        $anggaran->material = $request->material_habis;
                        $anggaran->justifikasi_pembelian = $request->justifikasi_pembelian_habis;
                        $anggaran->kuantitas = $request->kuantitas_habis;
                        $anggaran->harga_satuan = $request->harga_satuan_habis;
                        $anggaran->save();
                    }
                    elseif ($request->jenis_anggaran == "peralatan_penunjang") {
                        $anggaran = new AnggaranPeralatanPenunjang();
                        $anggaran->rancangan_anggaran_id = $anggaran_id->id;
                        $anggaran->material = $request->material_penunjang;
                        $anggaran->justifikasi_pembelian = $request->justifikasi_pembelian_penunjang;
                        $anggaran->kuantitas = $request->kuantitas_penunjang;
                        $anggaran->harga_satuan = $request->harga_satuan_penunjang;
                        $anggaran->save();
                    }
                    elseif ($request->jenis_anggaran == "perjalanan_lainnya") {
                        $anggaran = new AnggaranPerjalananLainnya();
                        $anggaran->rancangan_anggaran_id = $anggaran_id->id;
                        $anggaran->material = $request->material_lainnya;
                        $anggaran->justifikasi_pembelian = $request->justifikasi_pembelian_lainnya;
                        $anggaran->kuantitas = $request->kuantitas_lainnya;
                        $anggaran->harga_satuan = $request->harga_satuan_lainnya;
                        $anggaran->save();
                    }
                    else{
                        return redirect()->route('pengusul.usulan')->with(['error' =>  'Anggaran Gagal ditambahkan !']);
                    }
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
                $usulan = Usulan::select('judul_penelitian')->where('id',$id)->first();
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
                $lainnya = RancanganAnggaran::leftJoin('anggaran_perjalanan_lainnyas','anggaran_perjalanan_lainnyas.rancangan_anggaran_id','rancangan_anggarans.id')
                                            ->where('usulan_id',$id)
                                            ->get();
                $id_usulan = $id;
                $judul_penelitian = Usulan::select('judul_penelitian')->where('id',$id)->first();
                return view('pengusul/usulan.detail_anggaran',compact('outputs','habis_pakais','penunjangs','lainnya','id_usulan','judul_penelitian'));
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
}
