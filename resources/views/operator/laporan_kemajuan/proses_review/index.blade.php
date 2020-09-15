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
            <i class="fa fa-home"></i>&nbsp;Usulan Publikasi, Riset dan Pengabdian Kepada Masyarakat Dalam Tahap Review
        </header>
        <div class="panel-body" style="border-top: 1px solid #eee; padding:15px; background:white;">
            <div class="row" style="margin-right:-15px; margin-left:-15px;">
                <div class="col-md-12">
                    <div class="alert alert-danger alert-block" id="keterangan">
                        
                        <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah laporan kegiatan yang masih dalam tahap review !!
                    </div>
                </div>
                <div class="col-md-12">
                    <div style="margin-bottom:10px;">
                        <ul class="nav nav-tabs" id="myTab">
                            <li class="active"><a class="nav-item nav-link active" data-toggle="tab" href="#nav-penelitian"><i class="fa fa-book"></i>&nbsp;Usulan Penelitian</a></li>
                            <li><a class="nav-item nav-link" data-toggle="tab" href="#nav-pengabdian"><i class="fa fa-list-alt"></i>&nbsp;Usulan Pengabdian</a></li>
                        </ul>
                    </div>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-penelitian" role="tabpanel" aria-labelledby="nav-honor-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered" id="table" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Judul Kegiatan</th>
                                                <th>Anggota Kelompok</th>
                                                <th>Reviewer</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no=1;
                                            @endphp
                                            @foreach ($penelitians as $penelitian)
                                                @php
                                                    $jumlah = count(explode('&nbsp;|&nbsp;',$penelitian->nm_reviewer));
                                                @endphp
                                                @if ($jumlah == 2)
                                                <tr>
                                                    <td> {{ $no++ }} </td>
                                                    <td style="width:40% !important;">
                                                        {!! $penelitian->shortJudul !!}
                                                        <a onclick="selengkapnya({{ $penelitian->id }})" id="selengkapnya">selengkapnya</a>
                                                        <br>
                                                        <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                                        <span style="font-size:10px !important; text-transform:capitalize;" for="" class="badge badge-info">{{ $penelitian->jenis_kegiatan }}</span>
                                                        <span style="font-size:10px !important;" for="" class="badge badge-danger">{{ $penelitian->nm_ketua_peneliti }}</span>
                                                        <span style="font-size:10px !important;" for="" class="badge badge-secondary">{{ $penelitian->tahun_usulan }}</span>
                                                        <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                                        <a href="{{ asset('upload/laporan_kemajuan/'.$penelitian->file_kemajuan) }}" download="{{ $penelitian->file_kemajuan }}"><i class="fa fa-download"></i>&nbsp; download file laporan kemajuan</a>
                                                   </td>
                                                    <td>
                                                        @if ($penelitian->nm_anggota == null)
                                                            <label class="badge badge-danger"><i class="fa fa-close" style="padding:5px;"></i>&nbsp;Belum ditambahkan</label>
                                                            @else
                                                            <label class="badge" style="font-size:12px;">&nbsp;{!! $penelitian->nm_anggota !!}</label>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($penelitian->nm_reviewer == null || $penelitian->nm_reviewer == "")
                                                            <label class="badge badge-danger" style="padding:5px;">-</label>
                                                            @else
                                                            <label class="badge" style="font-size:12px;">&nbsp;{!! $penelitian->nm_reviewer !!}</label>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="nav-pengabdian" role="tabpanel" aria-labelledby="nav-honor-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered" id="table" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Judul Kegiatan</th>
                                                <th>Anggota Kelompok</th>
                                                <th>Reviewer</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no=1;
                                            @endphp
                                            @foreach ($pengabdians as $pengabdian)
                                                @php
                                                    $jumlah = count(explode('&nbsp;|&nbsp;',$pengabdian->nm_reviewer));
                                                @endphp
                                                @if ($jumlah == 2)
                                                <tr>
                                                    <td> {{ $no++ }} </td>
                                                    <td style="width:40% !important;">
                                                        {!! $pengabdian->shortJudul !!}
                                                        <a onclick="selengkapnya({{ $pengabdian->id }})" id="selengkapnya">selengkapnya</a>
                                                        <br>
                                                        <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                                        <span style="font-size:10px !important; text-transform:capitalize;" for="" class="badge badge-info">{{ $pengabdian->jenis_kegiatan }}</span>
                                                        <span style="font-size:10px !important;" for="" class="badge badge-danger">{{ $pengabdian->nm_ketua_peneliti }}</span>
                                                        <span style="font-size:10px !important;" for="" class="badge badge-secondary">{{ $pengabdian->tahun_usulan }}</span>
                                                        <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                                        <a href="{{ asset('upload/laporan_kemajuan/'.$pengabdian->file_kemajuan) }}" download="{{ $pengabdian->file_kemajuan }}"><i class="fa fa-download"></i>&nbsp; download file laporan kemajuan</a>
                                                   </td>
                                                    <td>
                                                        @if ($pengabdian->nm_anggota == null)
                                                            <label class="badge badge-danger"><i class="fa fa-close" style="padding:5px;"></i>&nbsp;Belum ditambahkan</label>
                                                            @else
                                                            <label class="badge" style="font-size:12px;">&nbsp;{!! $pengabdian->nm_anggota !!}</label>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($pengabdian->nm_reviewer == null || $pengabdian->nm_reviewer == "")
                                                            <label class="badge badge-danger" style="padding:5px;">-</label>
                                                            @else
                                                            <label class="badge" style="font-size:12px;">&nbsp;{!! $pengabdian->nm_reviewer !!}</label>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endif
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
