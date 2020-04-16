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
                        <button type="button" class="close" data-dismiss="alert">×</button>
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
                                    <div class="col-md-12" style="margin-top:10px;">
                                        <table class="table table-striped table-bordered" id="table" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th style="text-align:center;">No</th>
                                                    <th style="text-align:center;">Judul Kegiatan</th>
                                                    <th style="text-align:center;">Anggota Kelompok</th>
                                                    <th style="text-align:center;">Biaya Diusulkan</th>
                                                    <th style="text-align:center;">Peta Jalan Penelitian</th>
                                                    <th style="text-align:center;">Reviewer</th>
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
                                                        <td style="width:30% !important;">
                                                            {!! $penelitian->shortJudul !!}
                                                            <a onclick="detail({{ $penelitian->id }})" id="selengkapnya">selengkapnya</a>
                                                            <br>
                                                            <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                                            <span style="font-size:10px !important; text-transform:capitalize;" for="" class="badge badge-info">{{ $penelitian->jenis_kegiatan }}</span>
                                                            <span style="font-size:10px !important;" for="" class="badge badge-danger">{{ $penelitian->nm_ketua_peneliti }}</span>
                                                            <span style="font-size:10px !important;" for="" class="badge badge-secondary">{{ $penelitian->tahun_usulan }}</span>
                                                            <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                                            <a href="{{ asset('upload/file_usulan/'.$penelitian->file_usulan) }}" download="{{ $penelitian->file_usulan }}"><i class="fa fa-download"></i>&nbsp; download file usulan</a>
                                                            <br>
                                                            <a href="{{ asset('upload/peta_jalan/'.$penelitian->peta_jalan) }}" download="{{ $penelitian->peta_jalan }}"><i class="fa fa-download"></i>&nbsp; download file peta jalan</a>
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
                                                            <br>
                                                            <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                                            <a href="{{ route('operator.usulan.anggaran.cetak',[$penelitian->id]) }}" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-print"></i>&nbsp; Cetak</a>
                                                        </td>
                                                        <td style="text-align:center;">
                                                            <a href="{{ asset('upload/peta_jalan/'.$penelitian->peta_jalan) }}" download="{{ $penelitian->peta_jalan }}">
                                                                <button type="button" class="btn btn-primary" style="padding:7px;font-size:13px;color:white;cursor:pointer;"><i class="fa fa-download"></i></button>
                                                            </a>
                                                        </td>
                                                        <td style="text-align:center;">
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
                                    <div class="col-md-12" style="margin-top:10px;">
                                        <table class="table table-striped table-bordered" id="table" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th style="text-align:center;">No</th>
                                                    <th style="text-align:center;">Judul Kegiatan</th>
                                                    <th style="text-align:center;">Anggota Kelompok</th>
                                                    <th style="text-align:center;">Biaya Diusulkan</th>
                                                    <th style="text-align:center;">Peta Jalan Penelitian</th>
                                                    <th style="text-align:center;">Reviewer</th>
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
                                                        <td style="width:30% !important;">
                                                            {!! $pengabdian->shortJudul !!}
                                                            <a onclick="detail({{ $pengabdian->id }})" id="selengkapnya">selengkapnya</a>
                                                            <br>
                                                            <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                                            <span style="font-size:10px !important; text-transform:capitalize;" for="" class="badge badge-info">{{ $pengabdian->jenis_kegiatan }}</span>
                                                            <span style="font-size:10px !important;" for="" class="badge badge-danger">{{ $pengabdian->nm_ketua_peneliti }}</span>
                                                            <span style="font-size:10px !important;" for="" class="badge badge-secondary">{{ $pengabdian->tahun_usulan }}</span>
                                                            <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                                            <a href="{{ asset('upload/file_usulan/'.$pengabdian->file_usulan) }}" download="{{ $pengabdian->file_usulan }}"><i class="fa fa-download"></i>&nbsp; download file usulan</a>
                                                            <br>
                                                            <a href="{{ asset('upload/peta_jalan/'.$pengabdian->peta_jalan) }}" download="{{ $pengabdian->peta_jalan }}"><i class="fa fa-download"></i>&nbsp; download file peta jalan</a>
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
                                                            <br>
                                                            <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                                            <a href="{{ route('operator.usulan.anggaran.cetak',[$pengabdian->id]) }}" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-print"></i>&nbsp; Cetak</a>
                                                        </td>
                                                        <td style="text-align:center;">
                                                            <a href="{{ asset('upload/peta_jalan/'.$pengabdian->peta_jalan) }}" download="{{ $pengabdian->peta_jalan }}">
                                                                <button type="button" class="btn btn-primary" style="padding:7px;font-size:13px;color:white;cursor:pointer;"><i class="fa fa-download"></i></button>
                                                            </a>
                                                        </td>
                                                        <td style="text-align:center;">
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
                <div class="col-md-12">
                    <!-- Modal Detail-->
                    <div class="modal fade" id="modaldetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                            <p style="font-size:15px; color:black;" class="modal-title" id="exampleModalLabel"><i class="fa fa-info-circle"></i>&nbsp;Detail Usulan Penelitian</p>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-success alert-block" id="berhasil">
                                            <button type="button" class="close" data-dismiss="alert">×</button>
                                            <strong><i class="fa fa-info-circle"></i>&nbsp;Data Detail Usulan Penelitian Dosen Universitas Bengkulu</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped" style="width:100%">
                                            <tr>
                                                <td style="width:20%;">Judul Kegiatan</td>
                                                <td> : </td>
                                                <td>
                                                    <p id="judul_kegiatan_detail"></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Skim Penelitian</td>
                                                <td> : </td>
                                                <td>
                                                    <p id="skim_penelitian_detail"></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Jenis Kegiatan</td>
                                                <td> : </td>
                                                <td>
                                                    <p style="text-transform:capitalize;" id="jenis_kegiatan_detail"></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Ketua Peneliti</td>
                                                <td> : </td>
                                                <td>
                                                    <b> <a id="ketua_peneliti_detail"></a> </b><br>
                                                    <ul>
                                                        <li>Nip: <a id="ketua_nip"></a></li>
                                                        <li>Fakultas: <a id="ketua_fakultas"></a></li>
                                                        <li>Program Studi: <a id="ketua_prodi"></a></li>
                                                    </ul>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Anggota Kegiatan</td>
                                                <td> : </td>
                                                <td>
                                                    <p id="anggota_penelitian_detail"></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Ringkasan</td>
                                                <td> : </td>
                                                <td>
                                                    <p id="abstrak_detail"> </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Kata Kunci</td>
                                                <td> : </td>
                                                <td>
                                                    <p id="kata_kunci_detail"></p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal" style="font-size:13px;"><i class="fa fa-arrow-left"></i>&nbsp;Kembali</button>
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

        function detail(id){
            $.ajax({
                url: "{{ url('operator/usulan_dosen/menunggu_disetujui') }}"+'/'+ id + "/detail",
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    $('#modaldetail').modal('show');
                    $('#judul_kegiatan_detail').text(data['usulan'].judul_kegiatan);
                    $('#skim_penelitian_detail').text(data['usulan'].nm_skim);
                    $('#jenis_kegiatan_detail').text(data['usulan'].jenis_kegiatan);
                    $('#ketua_peneliti_detail').text(data['usulan'].nm_ketua_peneliti);
                    $('#ketua_nip').text(data['usulan'].nip);
                    $('#ketua_prodi').text(data['usulan'].prodi);
                    $('#ketua_fakultas').text(data['usulan'].fakultas);
                    $('#abstrak_detail').html(data['usulan'].abstrak);
                    $('#kata_kunci_detail').html(data['usulan'].kata_kunci);
                    var res='';
                    $.each (data['anggotas'], function (key, value) {
                        res +=
                        '<b>'+value.nm_anggota+'</b>'+
                        '<ul>'+
                            '<li>'+'Nip : '+value.nip+'</li>'+
                            '<li>'+'Fakultas : '+value.fakultas+'</li>'+
                            '<li>'+'Program Studi : '+value.prodi+'</li>'+
                        '</ul>';
                    });
                    $('#anggota_penelitian_detail').html(res);
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
