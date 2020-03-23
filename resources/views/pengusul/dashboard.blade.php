@extends('layouts.layout')
@section('title', 'Dashboard')
@section('user-login')
    @if(Session::get('login') && Session::get('login',1))
        {{ Session::get('nm_dosen') }}
    @endif
@endsection
@section('user-login2')
    @if(Session::get('login') && Session::get('login',1))
        {{ Session::get('nm_dosen') }}
    @endif
@endsection
@section('login_as', 'Dosen Pengusul')
@section('sidebar-menu')
    @include('pengusul/sidebar')
@endsection
@section('content')
    <section class="panel" style="margin-bottom:20px;">
        <header class="panel-heading" style="color: #ffffff;background-color: #074071;border-color: #fff000;border-image: none;border-style: solid solid none;border-width: 4px 0px 0;border-radius: 0;font-size: 14px;font-weight: 700;padding: 15px;">
            <i class="fa fa-home"></i>&nbsp;Dashboard
        </header>
        <div class="panel-body" style="border-top: 1px solid #eee; padding:15px; background:white;">
            <div class="row" style="margin-right:-15px; margin-left:-15px;">
                <div class="col-md-12">Selamat datang <strong> {{ Session::get('nm_dosen') }} </strong> di halaman Dashboard Dosen Pengul <b> Sistem Informasi Publikasi, Riset dan Pengabdian Kepada Masyarakat                            </b></div>
            </div>
        </div>
    </section>

    <section class="panel">
        <header class="panel-heading" style="color: #ffffff;background-color: #074071;border-color: #fff000;border-image: none;border-style: solid solid none;border-width: 4px 0px 0;border-radius: 0;font-size: 14px;font-weight: 700;padding: 15px;">
            <i class="fa fa-bar-chart"></i>&nbsp;Statistik Usulan Kegiatan
            <span class="tools pull-right" style="margin-top:-5px;">
                <a class="fa fa-chevron-down" href="javascript:;" style="float: left;margin-left: 3px;padding: 10px;text-decoration: none;"></a>
                <a class="fa fa-times" href="javascript:;" style="float: left;margin-left: 3px;padding: 10px;text-decoration: none;"></a>
            </span>
        </header>
        <div class="panel-body" style="border-top: 1px solid #eee; padding:15px; background:white;">
            <div class="row">
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua" style="margin-bottom:0px;">
                        <div class="inner">
                        <h3> {{ $usulans }} </h3>

                        <p>Semua Usulan Saya</p>
                        </div>
                        <div class="icon">
                        <i class="fa fa-list"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-red" style="margin-bottom:0px;">
                        <div class="inner">
                        <h3> {{ $belum_diteruskan }} </h3>

                        <p>Usulan Belum Diteruskan</p>
                        </div>
                        <div class="icon">
                        <i class="fa fa-home"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow" style="margin-bottom:0px;">
                        <div class="inner">
                        <h3>{{ $didanai }}</h3>

                        <p>Usulan Didanai</p>
                        </div>
                        <div class="icon">
                        <i class="fa fa-home"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green" style="margin-bottom:0px;">
                        <div class="inner">
                        <h3>{{ $tidak_didanai }}</h3>

                        <p>Usulan Tidak Didanai</p>
                        </div>
                        <div class="icon">
                        <i class="fa fa-home"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
