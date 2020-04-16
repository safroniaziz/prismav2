@extends('layouts.layout')
@section('title', 'Dashboard')
@section('user-login')
    @if (Auth::guard('reviewerusulan')->check())
        {{ Auth::guard('reviewerusulan')->user()->reviewer_nama }}
    @endif
@endsection
@section('user-login2')
    @if (Auth::guard('reviewerusulan')->check())
        {{ Auth::guard('reviewerusulan')->user()->reviewer_nama }}
    @endif
@endsection
@section('login_as', 'Reviewer')
@section('sidebar-menu')
    @include('reviewer_eksternal/sidebar')
@endsection
@section('content')
    <section class="panel" style="margin-bottom:20px;">
        <header class="panel-heading" style="color: #ffffff;background-color: #074071;border-color: #fff000;border-image: none;border-style: solid solid none;border-width: 4px 0px 0;border-radius: 0;font-size: 14px;font-weight: 700;padding: 15px;">
            <i class="fa fa-home"></i>&nbsp;Dashboard
        </header>
        <div class="panel-body" style="border-top: 1px solid #eee; padding:15px; background:white;">
            <div class="row" style="margin-right:-15px; margin-left:-15px;">
                <div class="col-md-12">Selamat datang <strong> {{ Auth::guard('reviewerusulan')->user()->reviewer_nama }} </strong> di halaman Dashboard Admin <b> Sistem Informasi Publikasi, Riset dan Pengabdian Kepada Masyarakat                            </b></div>
            </div>
        </div>
    </section>
@endsection
