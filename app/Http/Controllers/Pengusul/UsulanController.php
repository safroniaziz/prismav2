<?php

namespace App\Http\Controllers\Pengusul;

use App\AnggotaAlumni;
use App\AnggotaEksternal;
use App\AnggotaMahasiswa;
use App\AnggotaUsulan;
use App\BidangPenelitian;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserLoginController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
// use Auth;
// use DB;
use Illuminate\Support\Facades\DB;
use PDF;
use App\Dosen;
use App\Skim;
use App\Usulan;
use App\Fakultas;
use App\JadwalUsulan;
use App\Prodi;
use Carbon;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
                                            'ketua_peneliti_nama','abstrak','kata_kunci','usulans.created_at','nm_skim','jenis_kegiatan','file_usulan','biaya_diusulkan','status_usulan','tahun_usulan',
                                            DB::raw('group_concat(anggota_usulans.anggota_nama SEPARATOR "<br>") as "nm_anggota" '))
                                    ->get();
                $skims  =   Skim::select('id','nm_skim')->where('tahun',date('Y'))->get();
                $jadwal = JadwalUsulan::select('tanggal_awal','tanggal_akhir')->where('status','1')->get();
                $mytime = Carbon\Carbon::now();
                $now =  $mytime->toDateString();
                return view('pengusul/usulan.index',compact('usulans','skims','fakultas','jadwal','now'));
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

    public function detailUsulan($id){
        $detail = Usulan::leftJoin('skims','skims.id','usulans.skim_id')
                        ->select('usulans.id','judul_kegiatan','jenis_kegiatan','nm_skim','usulans.created_at','tujuan','luaran',
                        'ketua_peneliti_nama','ketua_peneliti_nip','abstrak','file_usulan','kata_kunci','biaya_diusulkan','status_usulan','tahun_usulan')
                        ->where('usulans.id',$id)
                        ->first();
        $anggota_internal = Usulan::join('anggota_usulans','anggota_usulans.usulan_id','usulans.id')
                                    ->select('anggota_nip','anggota_nama','anggota_prodi_nama','anggota_fakultas_nama','anggota_universitas')
                                    ->groupBy('anggota_usulans.id')
                                    ->where('usulans.id',$id)
                                    ->get();
        $anggota_eksternal = Usulan::join('anggota_eksternals','anggota_eksternals.usulan_id','usulans.id')
                                    ->select('anggota_nip','anggota_nama','anggota_universitas')
                                    ->groupBy('anggota_eksternals.id')
                                    ->where('usulans.id',$id)
                                    ->get();
        $anggota_mahasiswa = Usulan::join('anggota_mahasiswas','anggota_mahasiswas.usulan_id','usulans.id')
                                    ->select('anggota_npm','anggota_nama','anggota_prodi_nama','anggota_fakultas_nama')
                                    ->groupBy('anggota_mahasiswas.id')
                                    ->where('usulans.id',$id)
                                    ->get();
        $anggota_alumni = Usulan::join('anggota_alumnis','anggota_alumnis.usulan_id','usulans.id')
                                    ->select('anggota_nama','jabatan')
                                    ->groupBy('anggota_alumnis.id')
                                    ->where('usulans.id',$id)
                                    ->get();
        return view('pengusul.usulan.detail',compact('detail','anggota_internal','anggota_eksternal','anggota_mahasiswa','anggota_alumni'));
    }

    public function create(){
        $skims  =   Skim::select('id','nm_skim')->where('tahun',date('Y'))->where('status','1')->get();
        return view('pengusul/usulan.create',compact('skims'));
    }

    public function post(Request $request){
        $this->validate($request, [
            'judul_kegiatan'    =>  'required',
            'skim_id'    =>  'required',
            'jenis_kegiatan'    =>  'required',
            'abstrak'    =>  'required',
            'kata_kunci'    =>  'required',
            'tujuan'    =>  'required',
            'luaran'    =>  'required',
            'biaya_diusulkan'    =>  'required|numeric',
            'file_usulan'   =>  'required|mimes:pdf|max:3024',
        ]);
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                
                $model = $request->all();
                $slug = Str::slug(Session::get('nm_dosen'));
                // $model['file_usulan'] = null;

                // if ($request->hasFile('file_usulan')) {
                //     $model['file_usulan'] = Str::slug(Session::get('nm_dosen')).'-'.date('now').$request->skim_id.'-'.$request->tahun_usulan.uniqid().'.'.$request->file_usulan->getClientOriginalExtension();
                //     $request->file_usulan->move(public_path('/upload/file_usulan'), $model['file_usulan']);
                // }
                $file_usulan = $request->file('file_usulan');
                $file_usulanUrl = $file_usulan->store('file_usulan/'.$slug.'-'.Session::get('nip'));

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
                $usulan->kata_kunci =   $request->kata_kunci;
                $usulan->file_usulan    =   $file_usulanUrl;
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

    public function edit($slug, $id){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                $skims  =   Skim::select('id','nm_skim')->where('tahun',date('Y'))->where('status','1')->get();
                $usulan = Usulan::select('id','judul_kegiatan','jenis_kegiatan','skim_id','kata_kunci','ketua_peneliti_nip as ketua_peneliti_id','abstrak','file_usulan','lembar_pengesahan','biaya_diusulkan','tujuan','luaran','tahun_usulan')
                            ->where('id',$id)
                            ->first();
                return view('pengusul.usulan.edit',compact('usulan','skims'));
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

    public function update(Request $request, $id){
        $this->validate($request, [
            'judul_kegiatan'    =>  'required',
            'skim_id'    =>  'required',
            'jenis_kegiatan'    =>  'required',
            'abstrak'    =>  'required',
            'kata_kunci'    =>  'required',
            'tujuan'    =>  'required',
            'luaran'    =>  'required',
            'biaya_diusulkan'    =>  'required|numeric',
            'file_usulan'   =>  'mimes:pdf|max:3024',
        ]);

        $file_lama = Usulan::where('id',$id)->select('file_usulan')->first();
        $slug = Str::slug(Session::get('nm_dosen'));
        DB::beginTransaction();
        try {
            if (!empty(request()->file('file_usulan'))) {
                Storage::delete($file_lama->file_usulan);
                $fileUrl = $request->file_usulan->store('file_usulan/'.$slug.'-'.Session::get('nip'));
            }
            Usulan::where('id',$id)->update([
                'judul_kegiatan'    =>  $request->judul_kegiatan,
                'skim_id'    =>  $request->skim_id,
                'jenis_kegiatan'    =>  $request->jenis_kegiatan,
                'ketua_peneliti_nip'    =>  Session::get('nip'),
                'ketua_peneliti_nama'    =>  Session::get('nm_dosen'),
                'ketua_peneliti_prodi_id'    =>  Session::get('prodi_kode'),
                'ketua_peneliti_prodi_nama'    =>  Session::get('prodi_nama'),
                'ketua_peneliti_universitas'    =>  "Universitas Bengkulu",
                'kata_kunci'    =>   $request->kata_kunci,
                'tujuan'    =>   $request->tujuan,
                'luaran'    =>   $request->luaran,
                'biaya_diusulkan'    =>   $request->biaya_diusulkan,
                'tahun_usulan'  => date('Y'),
                'file_usulan'   =>  $fileUrl,
            ]);
            DB::commit();
            return redirect()->route('pengusul.usulan')->with(['success' =>  'File usulan kegiatan berhasil diubah !!']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('pengusul.usulan')->with(['error' =>  'File usulan kegiatan gagal diubah, silahkan cek koneksi internet anda atau coba lagi nanti !! !']);
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

                $sudah = AnggotaUsulan::select('anggota_nip')->where('anggota_nip',$request->anggota)->where('usulan_id',$request->usulan_id)->first();
                if (count($sudah) > 0) {
                    return redirect()->route('pengusul.usulan.detail_anggota',[$request->usulan_id])->with(['error' =>  'Anggota yang dipilih sudah ditambahkan !']);
                }
                else{
                    $this->validate($request,[
                        'anggota'   =>  'required', 
                    ]);
                    $panda = new UserLoginController();
                    DB::beginTransaction();
                    try {
                        $anggota = '
                            {dosen(dsnPegNip:"'.$request->anggota.'") {
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
                                }
                            }}
                        ';
                        $anggotas = $panda->panda($anggota);
                        AnggotaUsulan::create([
                            'usulan_id' => $request->usulan_id,
                            'anggota_nip' => $anggotas['dosen'][0]['pegawai']['pegNip'],
                            'anggota_nama' => $anggotas['dosen'][0]['pegawai']['pegNama'],
                            'anggota_prodi_id' => $anggotas['dosen'][0]['prodi']['prodiKode'],
                            'anggota_prodi_nama'=> $anggotas['dosen'][0]['prodi']['prodiNamaResmi'],
                            'anggota_fakultas_id' =>  $anggotas['dosen'][0]['prodi']['fakultas']['fakKode'],
                            'anggota_fakultas_nama' => $anggotas['dosen'][0]['prodi']['fakultas']['fakNamaResmi'],
                            'anggota_universitas' => "Universitas Bengkulu",
                        ]);
                        DB::commit();
                        return redirect()->route('pengusul.usulan.detail_anggota',[$request->usulan_id])->with(['success' =>  'Anggota yang dipilih berhasil ditambahkan !']);
                    } catch (Exception $e) {
                        // Rollback Transaction
                        DB::rollback();
                        // ada yang error
                        return redirect()->route('pengusul.usulan.detail_anggota',[$request->usulan_id])->with(['error' =>  'Anggota yang dipilih gagal ditambahkan !']);
                    }
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

    public function anggotaEksternalPost(Request $request){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){

                $sudah = AnggotaUsulan::select('anggota_nip')->where('anggota_nip',$request->anggota)->where('usulan_id',$request->usulan_id)->first();
                if (count($sudah) > 0) {
                    return redirect()->route('pengusul.usulan.detail_anggota',[$request->usulan_id])->with(['error' =>  'Anggota yang dipilih sudah ditambahkan !']);
                }
                else{
                    $this->validate($request,[
                        'nip'   =>  'required',
                        'nama'   =>  'required',
                        'jenis_kelamin'   =>  'required',
                        'nidn'   =>  'required',
                        'jabatan_fungsional'   =>  'required',
                        'universitas'   =>  'required',
                    ]);

                    AnggotaEksternal::create([
                        'usulan_id' => $request->usulan_id,
                        'anggota_nip' => $request->nip,
                        'anggota_nama' => $request->nama,
                        'anggota_jk' =>  $request->jenis_kelamin,
                        'anggota_nidn' =>  $request->nidn,
                        'anggota_jabatan_fungsional' =>  $request->jabatan_fungsional,
                        'anggota_universitas' => $request->universitas,
                    ]);
                    return redirect()->route('pengusul.usulan.detail_anggota',[$request->usulan_id])->with(['success' =>  'Anggota Eksternal berhasil ditambahkan !']);
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

    public function anggotaMahasiswaPost(Request $request){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){

                $sudah = AnggotaMahasiswa::select('anggota_npm')->where('anggota_npm',$request->mahasiswa)->where('usulan_id',$request->usulan_id)->first();
                if (count($sudah) > 0) {
                    return redirect()->route('pengusul.usulan.detail_anggota',[$request->usulan_id])->with(['error' =>  'Anggota yang dipilih sudah ditambahkan !']);
                }
                else{
                    $this->validate($request,[
                        'mahasiswa' =>  'required',
                    ]);
                    $panda = new UserLoginController();
                    DB::beginTransaction();
                    try {
                        $mahasiswa = '
                        {mahasiswa(mhsNiu:"'.$request->mahasiswa.'") {
                            mhsNiu
                            mhsNama
                            mhsAngkatan
                            mhsJenisKelamin
                            prodi {
                                prodiNamaResmi
                                fakultas {
                                    fakNamaResmi
                                }
                            }
                        }}
                        ';
                        $mahasiswas = $panda->panda($mahasiswa);
                        AnggotaMahasiswa::create([
                            'usulan_id' =>  $request->usulan_id,
                            'anggota_npm' =>  $mahasiswas['mahasiswa'][0]['mhsNiu'],
                            'anggota_nama' =>  $mahasiswas['mahasiswa'][0]['mhsNama'],
                            'anggota_angkatan' =>  $mahasiswas['mahasiswa'][0]['mhsAngkatan'],
                            'anggota_jk' =>  $mahasiswas['mahasiswa'][0]['mhsJenisKelamin'],
                            'anggota_prodi_nama' =>  $mahasiswas['mahasiswa'][0]['prodi']['prodiNamaResmi'],
                            'anggota_fakultas_nama' =>  $mahasiswas['mahasiswa'][0]['prodi']['fakultas']['fakNamaResmi'],
                        ]);
                        DB::commit();
                        return redirect()->route('pengusul.usulan.detail_anggota',[$request->usulan_id])->with(['success' =>  'Mahasiswa Terlibat berhasil ditambahkan !']);
                    } catch (Exception $e) {
                        // Rollback Transaction
                        DB::rollback();
                        // ada yang error
                        return redirect()->route('pengusul.usulan.detail_anggota',[$request->usulan_id])->with(['error' =>  'Mahasiswa Terlibat yang dipilih gagal ditambahkan !']);
                    }
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

    public function anggotaAlumniPost(Request $request){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                $this->validate($request,[
                    'nama'   =>  'required',
                    'jabatan'   =>  'required',
                ]);
                AnggotaAlumni::create([
                    'usulan_id' => $request->usulan_id,
                    'anggota_nama' => $request->nama,
                    'jabatan'       =>  $request->jabatan,
                ]);
                return redirect()->route('pengusul.usulan.detail_anggota',[$request->usulan_id])->with(['success' =>  'Staf Pendukung Terlibat / Alumni berhasil ditambahkan !']);
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
    

    // public function anggotaPost(Request $request){
    //     $sesi = Session::get('akses');
    //     if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
    //         if($sesi == 1){

    //             $sudah = AnggotaUsulan::select('anggota_nip')->where('anggota_nip',$request->nip_anggota)->where('usulan_id',$request->usulan_id_anggaran)->first();
    //             if (count($sudah) != 0) {
    //                 return redirect()->route('pengusul.usulan.detail_anggota',[$request->usulan_id_anggaran])->with(['error' =>  'Anggota yang dipilih sudah ditambahkan !']);
    //             }
    //             else{
    //                 $anggota = new AnggotaUsulan;
    //                 $anggota->usulan_id = $request->usulan_id_anggaran;
    //                 $anggota->anggota_nip = $request->nip_anggota;
    //                 $anggota->anggota_nama = $request->nm_anggota;
    //                 $anggota->anggota_prodi_id = $request->prodi_kode_anggota;
    //                 $anggota->anggota_prodi_nama= $request->prodi_anggota;
    //                 $anggota->anggota_fakultas_id = $request->fakultas_kode_anggota;
    //                 $anggota->anggota_fakultas _nama = $request->fakultas_anggota;
    //                 $anggota->anggota_jabatan_fungsional = $request->jabatan_anggota;
    //                 $anggota->anggota_jk = $request->jk_anggota;
    //                 $anggota->anggota_universitas = "Universitas Bengkulu";
    //                 $anggota->save();
    //                 return redirect()->route('pengusul.usulan.detail_anggota',[$request->usulan_id_anggaran])->with(['success' =>  'Anggota berhasil ditambahkan !']);
    //             }
    //         }
    //         else{
    //             Session::flush();
    //             return redirect()->route('panda.login.form')->with(['error' => 'Anda Tidak Memiliki Akses Login']);
    //         }
    //     }
    //     else{
    //         return redirect()->route('panda.login.form')->with(['error' => 'Masukan Username dan Password Terlebih Dahulu !!']);
    //     }
    // }

    public function hapusAnggota(Request $request){
        $anggota = AnggotaUsulan::find($request->id);
        $anggota->delete();

        return redirect()->route('pengusul.usulan.detail_anggota',[$request->id_usulan])->with(['success' =>  'Anggota kegiatan berhasil dihapus !']);
    }

    public function hapusAnggotaAlumni(Request $request){
        $anggota = AnggotaAlumni::find($request->id);
        $anggota->delete();

        return redirect()->route('pengusul.usulan.detail_anggota',[$request->id_usulan])->with(['success' =>  'Anggota kegiatan berhasil dihapus !']);
    }

    public function hapusAnggotaMahasiswa(Request $request){
        $anggota = AnggotaMahasiswa::find($request->id);
        $anggota->delete();

        return redirect()->route('pengusul.usulan.detail_anggota',[$request->id_usulan])->with(['success' =>  'Anggota kegiatan berhasil dihapus !']);
    }

    public function hapusAnggotaEksternal(Request $request){
        $anggota = AnggotaEksternal::find($request->id);
        $anggota->delete();

        return redirect()->route('pengusul.usulan.detail_anggota',[$request->id_usulan])->with(['success' =>  'Anggota kegiatan berhasil dihapus !']);
    }

    public function usulkan($id){
        $sesi = Session::get('akses');
        if(Session::get('login') && Session::get('login',1) && Session::get('akses',1)){
            if($sesi == 1){
                $usulan = Usulan::find($id);
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
   
    public function detailAnggota($id){
        $panda = new UserLoginController();
        $anggotas = AnggotaUsulan::join('usulans','usulans.id','anggota_usulans.usulan_id')
                                    ->select('anggota_usulans.id','anggota_nip','anggota_nama','anggota_prodi_nama','anggota_fakultas_nama','judul_kegiatan')
                                    ->where('usulans.id',$id)
                                    ->get();
        $anggota_eksternals = AnggotaEksternal::join('usulans','usulans.id','anggota_eksternals.usulan_id')
                                    ->select('anggota_eksternals.id','anggota_nip','anggota_nidn','anggota_nama','judul_kegiatan','anggota_universitas')
                                    ->where('usulans.id',$id)
                                    ->get();
        $anggota_mahasiswas = AnggotaMahasiswa::join('usulans','usulans.id','anggota_mahasiswas.usulan_id')
                                    ->select('anggota_mahasiswas.id','anggota_npm','anggota_nama','anggota_prodi_nama','anggota_fakultas_nama','anggota_angkatan','anggota_jk')
                                    ->where('usulans.id',$id)
                                    ->get();
        $anggota_alumnis = AnggotaAlumni::join('usulans','usulans.id','anggota_alumnis.usulan_id')
                                    ->select('anggota_alumnis.id','anggota_nama','jabatan')
                                    ->where('usulans.id',$id)
                                    ->get();
        $id_usulan = $id;
        $jumlah = count($anggotas);
        $judul_kegiatan = Usulan::select('judul_kegiatan')->where('id',$id)->first();
        $fakultas = '
            {fakultas {
                fakKode
                fakNamaResmi
            }}
        ';
        $fakultases = $panda->panda($fakultas);
        return view('pengusul/usulan.anggota',compact('anggotas','anggota_eksternals','anggota_mahasiswas','anggota_alumnis','id_usulan','judul_kegiatan','jumlah','fakultases'));
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

    public function cariAnggota(Request $request){
        $panda = new UserLoginController();
        $anggota = '
            {prodi(prodiKode:'.$request->prodi.') {
                prodiKode
                prodiNamaResmi
                dosen {
                    dsnPegNip
                    pegawai {
                        pegNip
                        pegNama
                        pegIsAktif
                    }
                }
            }}
        ';
        $anggotas = $panda->panda($anggota);
        return $anggotas;

    }

    public function cariMahasiswa(Request $request){
        $panda = new UserLoginController();
        $start = date("Y")-4;
        $finish = date("Y")-1;
        // return $finish;
        $anggota = '
            {prodi(prodiKode:'.$request->prodi.') {
                prodiKode
                mahasiswa(tahunMulai:'.$start.',tahunAkhir:'.$finish.') {
                    mhsNiu
                    mhsNama
                }
            }}
        ';
        $anggotas = $panda->panda($anggota);
        return $anggotas;
    }

    // public function cariAnggota(Request $request){
    //     $panda = new UserLoginController();
    //     $dosen = '
    //     {pegawai(pegNip:"'.$request->nip_anggota.'") {
    //         pegNip
    //         pegIsAktif
    //         pegNama
    //         pegawai_simpeg {
    //             pegJenkel
    //             pegNmJabatan
    //         }
    //         dosen {
    //           prodi {
    //             prodiKode
    //             prodiNamaResmi
    //             fakultas {
    //               fakKode
    //               fakKodeUniv
    //               fakNamaResmi
    //             }
    //           }
    //         }
    //       }}
    //     ';
    //     $dosens = $panda->panda($dosen);
    //     $datas = count($dosens['pegawai']);
    //     $data = [
    //         'jumlah'    =>  $datas,
    //         'detail'    =>  $dosens,
    //     ];
    //     if($data['jumlah'] == 1){
    //         return response()->json($data);
    //     }
    // }

    public function cariSkim(Request $request){
        $skims = Skim::where('j_kegiatan', $request->jenis_kegiatan)->get();
        return $skims;
    }
}
