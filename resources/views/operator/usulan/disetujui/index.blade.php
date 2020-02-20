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
            <i class="fa fa-home"></i>&nbsp;Usulan Publikasi Didanai
        </header>
        <div class="panel-body" style="border-top: 1px solid #eee; padding:15px; background:white;">
            <div class="row" style="margin-right:-15px; margin-left:-15px;">
                <div class="col-md-12">
                    <div class="alert alert-success alert-block" id="keterangan">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah semua usulan yang didanai, usulan yang sudah diverifikasi tidak bisa dibatalkan !!
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="table" style="width:100%; ">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul Penelitian</th>
                                <th>Ketua Penelitian</th>
                                <th>Anggota Penelitian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no=1;
                            @endphp
                            @foreach ($usulans as $usulan)
                                <tr>
                                    <td> {{ $no++ }} </td>
                                    <td> {{ $usulan->judul_penelitian }} </td>
                                    <td>
                                        <label for="" class="badge" style="font-size:12px;">{!! $usulan->nm_ketua_peneliti !!}</label>
                                    </td>
                                    <td>
                                        <label for="" class="badge" style="font-size:12px;">{!! $usulan->nm_anggota !!}</label>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#table').DataTable({
                responsive : true,
            });
        } );
    </script>
@endpush
