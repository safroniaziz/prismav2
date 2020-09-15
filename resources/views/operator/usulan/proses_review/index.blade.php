@php
    use App\TotalSkor;
@endphp
@extends('layouts.layout')
@section('title', 'Dalam Proses Review')
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
            <i class="fa fa-home"></i>&nbsp;Usulan Publikasi, Riset dan Pengabdian Kepada Masyarakat Dalam Proses Review
        </header>
        <div class="panel-body" style="border-top: 1px solid #eee; padding:15px; background:white;">
            <div class="row" style="margin-right:-15px; margin-left:-15px;">
                <div class="col-md-12">
                    <div class="alert alert-danger alert-block" id="keterangan">
                        
                        <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah semua usulan yang sedang dalam proses review, usulan akan pindah ke menu <b>Menunggu Verifikasi</b> jika kedua reviewer sudah memberikan reviewe masing-masing !!
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
                                    <div class="col-md-12" style="margin-top: 5px;">
                                        <a href="{{ route('operator.proses_review.cetak') }}" target="_blank"  class="btn btn-primary btn-sm"><i class="fa fa-print"></i>&nbsp; Cetak Data</a>
                                    </div>
                                    <div class="col-md-12" style="margin-top:10px;">

                                        <table class="table table-striped table-bordered" id="table" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th style="text-align:center;">No</th>
                                                    <th style="text-align:center;">Judul Kegiatan</th>
                                                    <th style="text-align:center;">Anggota Kelompok</th>
                                                    <th style="text-align:center;">Biaya Diusulkan</th>
                                                    <th style="text-align:center;">File Usulan</th>
                                                    <th style="text-align:center;">Reviewer</th>
                                                    <th style="text-align:center;">Ubah Reviewer</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $no=1;
                                                @endphp
                                                @foreach ($penelitians as $penelitian)
                                                    @php
                                                        $jumlah = count(TotalSkor::where('usulan_id',$penelitian->id)->select('reviewer_id')->get());
                                                    @endphp
                                                    @if ($jumlah > 1)
                                                        @else
                                                        <tr>
                                                            <td> {{ $no++ }} </td>
                                                            <td style="width:30% !important;">
                                                                {!! $penelitian->shortJudul !!}
                                                                <a href="{{ route('operator.proses_review.detail',[$penelitian->id,\Illuminate\Support\Str::slug($penelitian->judul_kegiatan)]) }}" id="selengkapnya">selengkapnya</a>
                                                                <br>
                                                                <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                                                <span style="font-size:10px !important; text-transform:capitalize;" for="" class="badge badge-info">{{ $penelitian->nm_skim }}</span>
                                                                <span style="font-size:10px !important;" for="" class="badge badge-danger">{{ $penelitian->nm_ketua_peneliti }}</span>
                                                                <span style="font-size:10px !important;" for="" class="badge badge-secondary">{{ $penelitian->tahun_usulan }}</span> <br>
                                                                Diusulkan {{ $penelitian->created_at ? $penelitian->created_at->diffForHumans() : '-' }} ({{ \Carbon\Carbon::parse($penelitian->created_at)->format('j F Y H:i') }})
                                                                
                                                            </td>
                                                            <td>
                                                                @if ($penelitian->nm_anggota == null)
                                                                    <label class="badge badge-danger"><i class="fa fa-close" style="padding:5px;"></i>&nbsp;Belum ditambahkan</label>
                                                                    @else
                                                                    <label class="badge" style="font-size:12px;">&nbsp;{!! $penelitian->nm_anggota !!}</label>
                                                                @endif
                                                            </td>
                                                            <td style="width:30%; text-align:center;">
                                                                <a>Rp. {{ number_format($penelitian->biaya_diusulkan, 2) }}</a>
                                                            </td>
                                                            <td class="text-center">
                                                                <a href="{{ asset('storage/'.$penelitian->file_usulan) }}" download="{{ $penelitian->file_usulan }}" class="btn btn-primary btn-sm"><i class="fa fa-download"></i></a>
                                                            </td>
                                                            <td style="text-align:center;">
                                                                @if ($penelitian->nm_reviewer == null || $penelitian->nm_reviewer == "")
                                                                    <label class="badge badge-danger" style="padding:5px;">-</label>
                                                                    @else
                                                                    <label class="badge" style="font-size:12px;">&nbsp;{!! $penelitian->nm_reviewer !!}</label>
                                                                @endif
                                                            </td>
                                                            <td style="text-align:center;">
                                                                <a href=" {{ route('operator.proses_review.detail_reviewer',[$penelitian->id]) }} " class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-user-plus"></i></a>
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
                                    <div class="col-md-12" style="margin-top: 5px;">
                                        <a href="{{ route('operator.proses_review.cetak_pengabdian') }}" target="_blank"  class="btn btn-primary btn-sm"><i class="fa fa-print"></i>&nbsp; Cetak Data</a>
                                    </div>
                                    <div class="col-md-12" style="margin-top:10px;">
                                        <table class="table table-striped table-bordered" id="table" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th style="text-align:center;">No</th>
                                                    <th style="text-align:center;">Judul Kegiatan</th>
                                                    <th style="text-align:center;">Anggota Kelompok</th>
                                                    <th style="text-align:center;">Biaya Diusulkan</th>
                                                    <th style="text-align:center;">File Usulan</th>
                                                    <th style="text-align:center;">Reviewer</th>
                                                    <th style="text-align:center;">Ubah Reviewer</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $no=1;
                                                @endphp
                                                @foreach ($pengabdians as $pengabdian)
                                                @php
                                                    $jumlah_pengabdian = count(TotalSkor::where('usulan_id',$pengabdian->id)->select('reviewer_id')->get());
                                                @endphp
                                                @if ($jumlah_pengabdian > 1)
                                                    @else
                                                    <tr>
                                                        <td> {{ $no++ }} </td>
                                                        <td style="width:30% !important;">
                                                            {!! $pengabdian->shortJudul !!}
                                                            <a href="{{ route('operator.proses_review.detail',[$pengabdian->id,\Illuminate\Support\Str::slug($pengabdian->judul_kegiatan)]) }}" id="selengkapnya">selengkapnya</a>
                                                            <br>
                                                            <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                                            <span style="font-size:10px !important; text-transform:capitalize;" for="" class="badge badge-info">{{ $pengabdian->nm_skim }}</span>
                                                            <span style="font-size:10px !important;" for="" class="badge badge-danger">{{ $pengabdian->nm_ketua_peneliti }}</span>
                                                            <span style="font-size:10px !important;" for="" class="badge badge-secondary">{{ $pengabdian->tahun_usulan }}</span> <br>
                                                            Diusulkan {{ $penelitian->created_at ? $penelitian->created_at->diffForHumans() : '-' }} ({{ \Carbon\Carbon::parse($penelitian->created_at)->format('j F Y H:i') }})
                                                        </td>
                                                        <td>
                                                            @if ($pengabdian->nm_anggota == null)
                                                                <label class="badge badge-danger"><i class="fa fa-close" style="padding:5px;"></i>&nbsp;Belum ditambahkan</label>
                                                                @else
                                                                <label class="badge" style="font-size:12px;">&nbsp;{!! $pengabdian->nm_anggota !!}</label>
                                                            @endif
                                                        </td>
                                                        <td style="width:30%; text-align:center;">
                                                            <a>Rp. {{ number_format($pengabdian->biaya_diusulkan, 2) }}</a>
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="{{ asset('storage/'.$penelitian->file_usulan) }}" download="{{ $penelitian->file_usulan }}" class="btn btn-primary btn-sm"><i class="fa fa-download"></i></a>
                                                        </td>
                                                        <td style="text-align:center;">
                                                            @if ($pengabdian->nm_reviewer == null || $pengabdian->nm_reviewer == "")
                                                                <label class="badge badge-danger" style="padding:5px;">-</label>
                                                                @else
                                                                <label class="badge" style="font-size:12px;">&nbsp;{!! $pengabdian->nm_reviewer !!}</label>
                                                            @endif
                                                        </td>
                                                        <td style="text-align:center;">
                                                            <a href=" {{ route('operator.proses_review.detail_reviewer',[$pengabdian->id]) }} " class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-user-plus"></i></a>
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
