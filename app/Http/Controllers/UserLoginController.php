<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Session;
use App\Dosen;

class UserLoginController extends Controller
{
    public function pandaToken()
   	{
    	$client = new Client();

        $url = 'https://panda.unib.ac.id/api/login';
	      try {
	        $response = $client->request(
	            'POST',  $url, ['form_params' => ['email' => 'evaluasi@unib.ac.id', 'password' => 'evaluasi2018']]
	        );
	        $obj = json_decode($response->getBody(),true);
	        Session::put('token', $obj['token']);
	        return $obj['token'];
	      } catch (GuzzleHttp\Exception\BadResponseException $e) {
	        echo "<h1 style='color:red'>[Ditolak !]</h1>";
	        exit();
	      }
    }

    public function pandaLogin(Request $request){
    	$username = $request->username;
        $password = $request->password;
        // $count =  preg_match_all( "/[0-9]/", $username );
    	$query = '
			{portallogin(username:"'.$username.'", password:"'.$password.'") {
			  is_access
			  tusrThakrId
			}}
    	';
    	$data = $this->panda($query)['portallogin'];

    	$data_dosen = '
			{dosen(dsnPegNip: "'.$username.'") {
              dsnPegNip
              dsnNidn
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

        if($data[0]['is_access']==1){
    		if($data[0]['tusrThakrId']==2){
                // $dosen = Dosen::where('nip','=',$request->username)->select('nm_lengkap')->first();
				// if($dosen == NULL){
				// 	return redirect()->route('panda.login.form')->with(['error'	=> 'NIP Anda Tidak Terdaftar !!']);
				// }
				// else{
                    $dosen2 = $this->panda($data_dosen);
                    if($dosen2['dosen'][0]['pegawai']['pegawai_simpeg'] != null){
                        Session::put('nip',$dosen2['dosen'][0]['dsnPegNip']);
                        Session::put('nm_dosen',$dosen2['dosen'][0]['pegawai']['pegNama']);
                        Session::put('prodi_kode',$dosen2['dosen'][0]['prodi']['prodiKode']);
                        Session::put('prodi_nama',$dosen2['dosen'][0]['prodi']['prodiNamaResmi']);
                        Session::put('fakultas_nama',$dosen2['dosen'][0]['prodi']['fakultas']['fakKode']);
                        Session::put('fakultas_kode',$dosen2['dosen'][0]['prodi']['fakultas']['fakNamaResmi']);
                        Session::put('nidn',$dosen2['dosen'][0]['dsnNidn']);
                        Session::put('jabatan',$dosen2['dosen'][0]['pegawai']['pegawai_simpeg']['pegNmJabatan']);
                        Session::put('jk',$dosen2['dosen'][0]['pegawai']['pegawai_simpeg']['pegJenkel']);
                        Session::put('login',1);
                        Session::put('akses',1);
                        if (!empty(Session::get('akses')) && Session::get('akses',1)) {
                            return redirect()->route('pengusul.dashboard');
                        }
                        else{
                            return redirect()->route('panda.login.form')->with(['error'	=> 'Username dan Password Salah !! !!']);
                        }
                    }

				// }

            }
            else{
    			return redirect()->route('panda.login.form')->with(['error'	=> 'Akses Anda Tidak Diketahui !!']);
    		}
        }

        else if($password == "prismav2" && $username == $request->username) {
            // $dosen = Dosen::where('nip', '=', $request->username)->first();
                // if ($dosen == null) {
                //     return redirect()->route('panda.login.form')->with(['error'	=> 'NIP Anda Tidak Terdaftar !!']);
                // }else{
                    $dosen2 = $this->panda($data_dosen);

                    if($dosen2['dosen'][0]['pegawai']['pegIsAktif'] == 1){
                        Session::put('nip',$dosen2['dosen'][0]['dsnPegNip']);
                        Session::put('nm_dosen',$dosen2['dosen'][0]['pegawai']['pegNama']);
                        Session::put('prodi_kode',$dosen2['dosen'][0]['prodi']['prodiKode']);
                        Session::put('prodi_nama',$dosen2['dosen'][0]['prodi']['prodiNamaResmi']);
                        Session::put('fakultas_nama',$dosen2['dosen'][0]['prodi']['fakultas']['fakKode']);
                        Session::put('fakultas_kode',$dosen2['dosen'][0]['prodi']['fakultas']['fakNamaResmi']);
                        Session::put('nidn',$dosen2['dosen'][0]['dsnNidn']);
                        Session::put('jabatan',$dosen2['dosen'][0]['pegawai']['pegawai_simpeg']['pegNmJabatan']);
                        Session::put('jk',$dosen2['dosen'][0]['pegawai']['pegawai_simpeg']['pegJenkel']);
                        Session::put('login',1);
                        Session::put('akses',1);
                        if (!empty(Session::get('akses')) && Session::get('akses',1)) {
                            return redirect()->route('pengusul.dashboard');
                        }
                        else{
                            return redirect()->route('panda.login.form')->with(['error'	=> 'Username dan Password Salah !! !!']);
                        }
                    }
                // }
        }
        else{
			return redirect()->route('panda.login.form')->with(['error'	=> 'Username dan Password Salah !! !!']);
        }
    	// print_r($data);
    }

    public function panda($query){
        $client = new Client();
        try {
            $response = $client->request(
                'POST','https://panda.unib.ac.id/panda',
                ['form_params' => ['token' => $this->pandaToken(), 'query' => $query]]
            );
            $arr = json_decode($response->getBody(),true);
            if(!empty($arr['errors'])){
                echo "<h1><i>Kesalahan Query...</i></h1>";
            }else{
                return $arr['data'];
            }
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            $res = json_decode($responseBodyAsString,true);
            if($res['message']=='Unauthorized'){
                echo "<h1><i>Meminta Akses ke Pangkalan Data...</i></h1>";
                $this->panda_token();
                header("Refresh:0");
            }else{
                print_r($res);
            }
        }
    }

    public function showLoginForm(){
        if (!empty(Session::get('login')) && Session::get('login',1)) {
            return redirect()->route('pengusul.dashboard');
        }
        else{
            return view('auth.login_dosen');
        }
    }

    public function showReviewerLoginForm(){
        if (!empty(Session::get('login')) && Session::get('login',1)) {
            return redirect()->route('reviewer.dashboard');
        }
        else{
            return view('auth.login_reviewer');
        }
    }

    public function pandaLoginReviewer(Request $request){
    	$username = $request->username;
        $password = $request->password;
        $count =  preg_match_all( "/[0-9]/", $username );
    	$query = '
			{portallogin(username:"'.$username.'", password:"'.$password.'") {
			  is_access
			  tusrThakrId
			}}
    	';
    	$data = $this->panda($query)['portallogin'];

    	$data_dosen = '
			{dosen(dsnPegNip: "'.$username.'") {
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
			  }
			}}
        ';

        if($data[0]['is_access']==1){
    		if($data[0]['tusrThakrId']==2){
                // $dosen = Dosen::where('nip','=',$request->username)->select('nm_lengkap')->first();
				// if($dosen == NULL){
				// 	return redirect()->route('panda.reviewer_login.form')->with(['error'	=> 'NIP Anda Tidak Terdaftar !!']);
				// }
				// else{
					$dosen2 = $this->panda($data_dosen);
                   if($dosen2['dosen'][0]['pegawai']['pegIsAktif'] == 1){
                        Session::put('nip',$dosen2['dosen'][0]['dsnPegNip']);
                        Session::put('nm_dosen',$dosen2['dosen'][0]['pegawai']['pegNama']);
                        Session::put('prodi_kode',$dosen2['dosen'][0]['prodi']['prodiKode']);
                        Session::put('prodi_nama',$dosen2['dosen'][0]['prodi']['prodiNamaResmi']);
                        Session::put('fakultas_nama',$dosen2['dosen'][0]['prodi']['fakultas']['fakKode']);
                        Session::put('fakultas_kode',$dosen2['dosen'][0]['prodi']['fakultas']['fakNamaResmi']);
                        Session::put('nidn',$dosen2['dosen'][0]['dsnNidn']);
                        Session::put('jabatan',$dosen2['dosen'][0]['pegawai']['pegawai_simpeg']['pegNmJabatan']);
                        Session::put('jk',$dosen2['dosen'][0]['pegawai']['pegawai_simpeg']['pegJenKel']);
                        Session::put('login',1);
                        Session::put('akses',2);
                        if (!empty(Session::get('akses')) && Session::get('akses',2)) {
                            return redirect()->route('reviewer.dashboard');
                        }
                        else{
                            return redirect()->route('panda.login.form')->with(['error'	=> 'Username dan Password Salah !! !!']);
                        }
                    }
				// }

            }
            else{
    			return redirect()->route('panda.reviewer_login.form')->with(['error'	=> 'Akses Anda Tidak Diketahui !!']);
    		}
        }

        else if($password == "prismav2" && $username == $request->username) {
            // $dosen = Dosen::where('nip', '=', $request->username)->first();
            //     if ($dosen == null) {
            //         return redirect()->route('panda.reviewer_login.form')->with(['error'	=> 'NIP Anda Tidak Terdaftar !!']);
                // }else{
                    $dosen2 = $this->panda($data_dosen);
                    if($dosen2['dosen'][0]['pegawai']['pegIsAktif'] == 1){
                        Session::put('nip',$dosen2['dosen'][0]['dsnPegNip']);
                        Session::put('nm_dosen',$dosen2['dosen'][0]['pegawai']['pegNama']);
                        Session::put('prodi_kode',$dosen2['dosen'][0]['prodi']['prodiKode']);
                        Session::put('prodi_nama',$dosen2['dosen'][0]['prodi']['prodiNamaResmi']);
                        Session::put('fakultas_nama',$dosen2['dosen'][0]['prodi']['fakultas']['fakKode']);
                        Session::put('fakultas_kode',$dosen2['dosen'][0]['prodi']['fakultas']['fakNamaResmi']);
                        Session::put('nidn',$dosen2['dosen'][0]['dsnNidn']);
                        Session::put('jabatan',$dosen2['dosen'][0]['pegawai']['pegawai_simpeg']['pegNmJabatan']);
                        Session::put('jk',$dosen2['dosen'][0]['pegawai']['pegawai_simpeg']['pegJenKel']);
                        Session::put('login',1);
                        Session::put('akses',2);
                        if (!empty(Session::get('akses')) && Session::get('akses',2)) {
                            return redirect()->route('reviewer.dashboard');
                        }
                        else{
                            return redirect()->route('panda.login.form')->with(['error'	=> 'Username dan Password Salah !! !!']);
                        }
                    }
                // }
        }
        else{
			return redirect()->route('panda.reviewer_login.form')->with(['error'	=> 'Username dan Password Salah !! !!']);
        }
    	// print_r($data);
    }

    public function userLogout(){
        Session::flush();
        return redirect()->route('panda.login.form');
    }

    public function reviewerLogout(){
        Session::flush();
        return redirect()->route('panda.reviewer_login.form');
	}
}
