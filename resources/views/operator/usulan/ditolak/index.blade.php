@extends('layouts.layout')
@section('title', 'Dashboard')
@section('login_as', 'Administrator')
@section('user-login')
    @if (Auth::check())
    {{ Auth::user()->nm_user }}
    @endif
@endsection
@section('user-login2')
    @if (Auth::check())
    {{ Auth::user()->nm_user }}
    @endif
@endsection
@section('sidebar-menu')
    @include('operator/sidebar')
@endsection
@push('styles')
    <style>
        #detail:hover{
            text-decoration: underline !important;
            cursor: pointer !important;
            color:teal;
        }
    </style>
@endpush
@section('content')
    <section class="panel" style="margin-bottom:20px;">
        <header class="panel-heading" style="color: #ffffff;background-color: #074071;border-color: #fff000;border-image: none;border-style: solid solid none;border-width: 4px 0px 0;border-radius: 0;font-size: 14px;font-weight: 700;padding: 15px;">
            <i class="fa fa-home"></i>&nbsp;Usulan Publikasi Tidak Didanai
        </header>
        <div class="panel-body" style="border-top: 1px solid #eee; padding:15px; background:white;">
            <div class="row" style="margin-right:-15px; margin-left:-15px;">
                <div class="col-md-12">
                    <div class="alert alert-success alert-block" id="keterangan">
                        
                        <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah semua usulan yang tidak didanai, usulan yang sudah diverifikasi tidak bisa dibatalkan !!
                    </div>
                </div>
                <div class="col-md-12">
                    <div style="margin-bottom:10px;">
                        <ul class="nav nav-tabs" id="myTab">
                            <li class="active"><a class="nav-item nav-link active" data-toggle="tab" href="#nav-penelitian"><i class="fa fa-book"></i>&nbsp;Usulan Penelitian</a></li>
                            <li><a class="nav-item nav-link" data-toggle="tab" href="#nav-pengabdian"><i class="fa fa-list-alt"></i>&nbsp;Usulan Pengabdian</a></li>
                        </ul>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-penelitian" role="tabpanel" aria-labelledby="nav-honor-tab">
                                <div class="row">
                                    <div class="col-md-12" style="margin-top:10px;">
                                        <table class="table table-striped table-bordered" id="table" style="width:100%; ">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Judul Kegiatan</th>
                                                    <th>Ketua Penelitian</th>
                                                    <th>Anggota Kegiatan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $no=1;
                                                @endphp
                                                @foreach ($penelitians as $penelitian)
                                                    <tr>
                                                        <td> {{ $no++ }} </td>
                                                        <td> {{ $penelitian->judul_kegiatan }} </td>
                                                        <td>
                                                            <label for="" class="badge" style="font-size:12px;">{!! $penelitian->nm_ketua_peneliti !!}</label>
                                                        </td>
                                                        <td>
                                                            <label for="" class="badge" style="font-size:12px;">{!! $penelitian->nm_anggota !!}</label>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="nav-pengabdian" role="tabpanel" aria-labelledby="nav-honor-tab">
                                <div class="row">
                                    <div class="col-md-12" style="margin-top:10px;">
                                        <table class="table table-striped table-bordered" id="table" style="width:100%; ">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Judul Kegiatan</th>
                                                    <th>Ketua Penelitian</th>
                                                    <th>Anggota Kegiatan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $no=1;
                                                @endphp
                                                @foreach ($pengabdians as $pengabdian)
                                                    <tr>
                                                        <td> {{ $no++ }} </td>
                                                        <td> {{ $pengabdian->judul_kegiatan }} </td>
                                                        <td>
                                                            <label for="" class="badge" style="font-size:12px;">{!! $pengabdian->nm_ketua_peneliti !!}</label>
                                                        </td>
                                                        <td>
                                                            <label for="" class="badge" style="font-size:12px;">{!! $pengabdian->nm_anggota !!}</label>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $("table[id^='table']").DataTable({
                responsive : true,
            });
        } );

        $(document).ready(function(){
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                localStorage.setItem('activeTab', $(e.target).attr('href'));
            });
            var activeTab = localStorage.getItem('activeTab');
            if(activeTab){
                $('#myTab a[href="' + activeTab + '"]').tab('show');
            }
        });
    </script>
@endpush
