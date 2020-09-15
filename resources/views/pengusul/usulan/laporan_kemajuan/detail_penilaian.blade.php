@extends('layouts.layout')
@section('title', 'Usulan Baru')
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
@push('styles')
    <style>
        #selengkapnya{
            color:#5A738E;
            text-decoration:none;
            cursor:pointer;
        }
        #selengkapnya:hover{
            color:#007bff;
        }
    </style>
@endpush
@section('content')
    <section class="panel" style="margin-bottom:20px;">
        <header class="panel-heading" style="color: #ffffff;background-color: #074071;border-color: #fff000;border-image: none;border-style: solid solid none;border-width: 4px 0px 0;border-radius: 0;font-size: 14px;font-weight: 700;padding: 15px;">
            <i class="fa fa-home"></i>&nbsp;Usulan Publikasi, Riset dan Pengabdian Kepada Masyarakat
        </header>
        <div class="panel-body" style="border-top: 1px solid #eee; padding:15px; background:white;">
            <div class="row" style="margin-right:-15px; margin-left:-15px;">
                <div class="col-md-12">
                    <div class="alert alert-primary alert-block text-center text-uppercase" id="berhasil">
                        
                        <strong><i class="fa fa-info-circle"></i>&nbsp;Detail Informasi Penilaian Reviewer : </strong> <br>
                        
                    </div>
                </div>
                <div class="col-md-12">
                    <a href="{{ route('pengusul.laporan_kemajuan') }}" class="btn btn-danger btn-sm"><i class="fa fa-arrow-left"></i>&nbsp; Kembali</a>
                </div>
                <div class="col-md-6">
                    <div class="x_panel">
                        <div class="x_title">
                            <h6>Detail Penilaian Oleh Reviewer</h6>
                            <div class="clearfix"></div>
                            <div class="x_content mt-3">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <td>
                                                NIP reviewer
                                            </td>
                                            <td>Nama Reviewer</td>
                                            <td>Skor</td>
                                            <td>Jenis Reviewer</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no=1;
                                        @endphp
                                        @foreach ($per_dosen as $item)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $item->nama }}</td>
                                                <td>{{ $item->total_skor }}</td>
                                                <td>{{ $item->jenis_reviewer }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h6>Detail Penilaian Oleh Operator</h6>
                                    <div class="clearfix"></div>
                                    <div class="x_content mt-3">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <td>
                                                        Nama Operator
                                                    </td>
                                                    <td>Skor</td>
                                                    <td>Jenis Reviewer</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $no=1;
                                                @endphp
                                                @foreach ($review3 as $item)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $item->nm_user }}</td>
                                                        <td>{{ $item->total_skor }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h6>Total Skor</h6>
                                    <div class="clearfix"></div>
                                    <div class="x_content mt-3">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <td>No</td>
                                                    <td>Sub Total Skor</td>
                                                </tr>
                                            </thead>
                                            @php
                                                $no=1;
                                            @endphp
                                            @foreach ($total as $item)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $item->total_skor }}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <th>Total Skor</th>
                                                <td>
                                                    {{ $sub_total->total_skor }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Nilai Akhir</th>
                                                <td>
                                                    {{ $sub_total->total_skor/3 }}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h6>Komentar Reviewer</h6>
                                    <div class="clearfix"></div>
                                    <div class="x_content mt-3">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <td>No</td>
                                                    <td>Nama Reviewer</td>
                                                    <td>Komentar Keseluruhan</td>
                                                    <td>Komentar Anggaran</td>
                                                </tr>
                                            </thead>
                                            @php
                                                $no=1;
                                            @endphp
                                            @foreach ($komentars as $item)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $item->nama }}</td>
                                                    <td>{!! $item->komentar !!}</td>
                                                    <td>{{ $item->komentar_anggaran }}</td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h6>Komentar Operator</h6>
                                    <div class="clearfix"></div>
                                    <div class="x_content mt-3">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <td>No</td>
                                                    <td>Nama Operator</td>
                                                    <td>Komentar Keseluruhan</td>
                                                    <td>Komentar Anggaran</td>
                                                </tr>
                                            </thead>
                                            @php
                                                $no=1;
                                            @endphp
                                            @foreach ($komentar_operator as $item)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $item->nm_user }}</td>
                                                    <td>{!! $item->komentar !!}</td>
                                                    <td>{{ $item->komentar_anggaran }}</td>
                                                </tr>
                                            @endforeach
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
