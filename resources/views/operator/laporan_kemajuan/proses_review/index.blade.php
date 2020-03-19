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
            <i class="fa fa-home"></i>&nbsp;Usulan Publikasi, Riset dan Pengabdian Kepada Masyarakat
        </header>
        <div class="panel-body" style="border-top: 1px solid #eee; padding:15px; background:white;">
            <div class="row" style="margin-right:-15px; margin-left:-15px;">
                <div class="col-md-12">
                    <div class="alert alert-success alert-block" id="keterangan">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah semua usulan anda yang tersedia, silahkan tambahkan usulan baru jika diperlukan !!
                    </div>
                </div>
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
                            @foreach ($usulans as $usulan)
                                @php
                                    $jumlah = count(explode('&nbsp;|&nbsp;',$usulan->nm_reviewer));
                                @endphp
                                @if ($jumlah == 2)
                                <tr>
                                    <td> {{ $no++ }} </td>
                                    <td style="width:40% !important;">
                                        {!! $usulan->shortJudul !!}
                                        <a onclick="selengkapnya({{ $usulan->id }})" id="selengkapnya">selengkapnya</a>
                                        <br>
                                        <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                        <span style="font-size:10px !important; text-transform:capitalize;" for="" class="badge badge-info">{{ $usulan->jenis_kegiatan }}</span>
                                        <span style="font-size:10px !important;" for="" class="badge badge-danger">{{ $usulan->nm_ketua_peneliti }}</span>
                                        <span style="font-size:10px !important;" for="" class="badge badge-secondary">{{ $usulan->tahun_usulan }}</span>
                                        <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                        <a href="{{ asset('upload/laporan_kemajuan/'.$usulan->file_kemajuan) }}" download="{{ $usulan->file_kemajuan }}"><i class="fa fa-download"></i>&nbsp; download file laporan kemajuan</a>
                                   </td>
                                    <td>
                                        @if ($usulan->nm_anggota == null)
                                            <label class="badge badge-danger"><i class="fa fa-close" style="padding:5px;"></i>&nbsp;Belum ditambahkan</label>
                                            @else
                                            <label class="badge" style="font-size:12px;">&nbsp;{!! $usulan->nm_anggota !!}</label>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($usulan->nm_reviewer == null || $usulan->nm_reviewer == "")
                                            <label class="badge badge-danger" style="padding:5px;">-</label>
                                            @else
                                            <label class="badge" style="font-size:12px;">&nbsp;{!! $usulan->nm_reviewer !!}</label>
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
