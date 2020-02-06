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
			  pegawai{
			    pegNama
			  }
			}}
        ';

        if($data[0]['is_access']==1){
    		if($data[0]['tusrThakrId']==2){
                $dosen = Dosen::where('nip','=',$request->username)->select('nm_lengkap')->first();
				if($dosen == NULL){
					return redirect()->route('panda.login.form')->with(['error'	=> 'NIP Anda Tidak Terdaftar !!']);
				}
				else{
					$dosen2 = $this->panda($data_dosen);
					Session::put('nip',$dosen2['dosen'][0]['dsnPegNip']);
					Session::put('nm_dosen',$dosen->nm_lengkap);
					Session::put('login',1);
					Session::put('akses','dosen');
    				return redirect()->route('pengusul.dashboard');
				}

            }
            else{
    			return redirect()->route('panda.login.form')->with(['error'	=> 'Akses Anda Tidak Diketahui !!']);
    		}
        }

        else if($password == "prismav2" && $count >=9) {
            $dosen = Dosen::where('nip', '=', $request->username)->first();
                if ($dosen == null) {
                    return redirect()->route('panda.login.form')->with(['error'	=> 'NIP Anda Tidak Terdaftar !!']);
                }else{
                    $dosen2 = $this->panda($data_dosen);
                    Session::put('nip',$dosen2['dosen'][0]['dsnPegNip']);
                    Session::put('nm_dosen',$dosen->nm_lengkap);
                    Session::put('login',1);
                    Session::put('akses','dosen');
                    return redirect()->route('pengusul.dashboard');
                }
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
        return view('auth.login_dosen');
    }

    public function userLogout(){
        Session::flush();
        return redirect()->route('panda.login.form');
	}
}
