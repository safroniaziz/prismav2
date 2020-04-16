<?php

namespace App\Http\Controllers\ReviewerEksternal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReviewerUsulanController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:reviewerusulan');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        $usulans = [];
        return view('reviewer_eksternal/reviewer_usulan/dashboard',compact('usulans'));
    }
}
