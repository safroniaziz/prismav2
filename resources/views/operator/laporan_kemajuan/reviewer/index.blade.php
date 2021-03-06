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
            <i class="fa fa-home"></i>&nbsp;Tambah Reviewer Usulan Publikasi Didanai
        </header>
        <div class="panel-body" style="border-top: 1px solid #eee; padding:15px; background:white;">
            <div class="row" style="margin-right:-15px; margin-left:-15px;">
                <div class="col-md-12">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block" id="berhasil">
                            
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Berhasil: </strong> {{ $message }}
                        </div>
                    @endif
                    @if (count($usulans)>0)
                        <div class="alert alert-danger alert-block" id="keterangan">
                            
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah semua usulan yang telah mengupload laporan kemajuan, silahkan tambahakn reviewer laporan kemajuan !!
                        </div>
                        @else
                            <div class="alert alert-danger alert-block" id="keterangan">
                                
                                <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Belum ada usulan yang mengupload laporan kemajuan !!
                            </div>
                    @endif
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
                                    <table class="table table-striped table-bordered" id="table" style="width:100%; ">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th style="text-align:center;">Judul Kegiatan</th>
                                                <th>Proposal Revisi</th>
                                                <th>File Kemajuan</th>
                                                <th style="text-align:center;">Anggota Kegiatan Internal</th>
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
                                                @if ($jumlah < 2)
                                                    <tr>
                                                        <td> {{ $no++ }} </td>
                                                        <td style="width:40% !important;">
                                                            {!! $penelitian->shortJudul !!}
                                                            <a href="{{ route('operator.pending.detail',[$penelitian->id,\Illuminate\Support\Str::slug($penelitian->judul_kegiatan)]) }}" id="selengkapnya">selengkapnya</a>
                                                            <br>
                                                            <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                                            <span style="font-size:10px !important; text-transform:capitalize;" for="" class="badge badge-info">{{ $penelitian->nm_skim }}</span>
                                                            <span style="font-size:10px !important;" for="" class="badge badge-danger">{{ $penelitian->ketua_peneliti_nama }}</span>
                                                            <span style="font-size:10px !important;" for="" class="badge badge-secondary">{{ $penelitian->tahun_usulan }}</span> <br>
                                                            Diusulkan {{ $penelitian->created_at ? $penelitian->created_at->diffForHumans() : '-' }} ({{ \Carbon\Carbon::parse($penelitian->created_at)->format('j F Y H:i') }})

                                                        </td>
                                                        <td style="font-weight:bold; text-align:center;">
                                                                {!! $penelitian->nm_anggota !!}
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
                                    <table class="table table-striped table-bordered" id="table" style="width:100%; ">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th style="text-align:center;">Judul Kegiatan</th>
                                                <th>Proposal Revisi</th>
                                                <th>File Kemajuan</th>
                                                <th style="text-align:center;">Anggota Kegiatan Internal</th>
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
                                                @if ($jumlah < 2)
                                                    <tr>
                                                        <td> {{ $no++ }} </td>
                                                        <td style="width:40% !important;">
                                                            {!! $pengabdian->shortJudul !!}
                                                            <a onclick="selengkapnya({{ $pengabdian->id }})" id="selengkapnya">selengkapnya</a>
                                                            <br>
                                                            <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                                            <span style="font-size:10px !important; text-transform:capitalize;" for="" class="badge badge-info">{{ $pengabdian->jenis_kegiatan }}</span>
                                                            <span style="font-size:10px !important;" for="" class="badge badge-danger">{{ $pengabdian->ketua_peneliti_nama }}</span>
                                                            <span style="font-size:10px !important;" for="" class="badge badge-secondary">{{ $pengabdian->tahun_usulan }}</span>
                                                            <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                                            <a href="{{ asset('upload/laporan_perbaikan/'.$pengabdian->file_perbaikan) }}" download="{{ $pengabdian->file_perbaikan }}"><i class="fa fa-download"></i>&nbsp; download file laporan perbaikan</a>
                                                            <br>
                                                            <a href="{{ asset('upload/laporan_kemajuan/'.$pengabdian->file_kemajuan) }}" download="{{ $pengabdian->file_kemajuan }}"><i class="fa fa-download"></i>&nbsp; download file laporan kemajuan</a>

                                                        </td>
                                                        <td style="font-weight:bold; text-align:center;">
                                                                {!! $pengabdian->nm_anggota !!}
                                                        </td>
                                                        <td style="text-align:center;">
                                                            @if ($pengabdian->nm_reviewer == null || $pengabdian->nm_reviewer == "")
                                                                <label class="badge badge-danger" style="padding:5px;">-</label>
                                                                @else
                                                                <label class="badge" style="font-size:12px;">&nbsp;{!! $pengabdian->nm_reviewer !!}</label>
                                                            @endif
                                                        </td>
                                                        <td style="text-align:center;">
                                                            <a href=" {{ route('operator.laporan_kemajuan.detail_reviewer',[$pengabdian->id]) }} " class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-user-plus"></i></a>
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
                 <!-- Modal Detail -->
                <div class="modal fade" id="modaldetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Informasi Detail Judul Kegiatan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                            <h6 style="font-weight:bold;">Judul Kegiatan:</h6>
                            <hr>
                            <div id="detail-text" style="text-align:justify; font-weight:bold; ">

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" style="font-size:12px;"><i class="fa fa-close"></i>&nbsp;Keluar</button>
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

        function selengkapnya(id){
            $.ajax({
                url: "{{ url('operator/usulan_dosen/laporan_kemajuan') }}"+'/'+ id + "/detail_judul",
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    $('#modaldetail').modal('show');
                    $('#id').val(data.id);
                    $('#detail-text').text(data.judul_kegiatan);
                },
                error:function(){
                    alert("Nothing Data");
                }
            });
        }
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
