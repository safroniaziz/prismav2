<?php

namespace App\Http\Controllers\AuthReviewerUsulan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:reviewerusulan')->except('logout');
    }

    public function showLoginForm(){
        return view('authReviewerUsulan.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nip' => 'required',
            'password' => 'required|min:6'
        ]);

        $credential = [
            'nip' => $request->nip,
            'password' => $request->password
        ];
        // return $credential;

        // Attempt to log the user in
        if (Auth::guard('reviewerusulan')->attempt($credential, $request->member)){
            // If login succesful, then redirect to their intended location
            return redirect()->intended(route('reviewer_usulan.menunggu'));
            // return 'a';
        }

        // If Unsuccessful, then redirect back to the login with the form data
        return redirect()->back()->withInput($request->only('nip', 'remember'));
    }

    public function username()
    {
        return 'nip';
    }
}
