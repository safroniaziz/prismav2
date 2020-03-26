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
            <i class="fa fa-home"></i>&nbsp;Verifikasi Usulan Publikasi, Riset dan Pengabdian Kepada Masyarakat
        </header>
        <div class="panel-body" style="border-top: 1px solid #eee; padding:15px; background:white;">
            <form action="{{ route('operator.laporan_kemajuan.verifikasi.verifikasi') }}" method="POST">

                <div class="row" style="margin-right:-15px; margin-left:-15px;">
                    <div class="col-md-12">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-block" id="berhasil">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong><i class="fa fa-info-circle"></i>&nbsp;Berhasil: </strong> {{ $message }}
                            </div>
                            @elseif ($message2 = Session::get('error'))
                                <div class="alert alert-danger alert-block" id="berhasil">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong><i class="fa fa-info-circle"></i>&nbsp;Gagal: </strong> {{ $message2 }}
                                </div>
                        @endif
                        @if (count($usulans)>0)
                            <div class="alert alert-danger alert-block" id="keterangan">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah semua usulan yang siap di verifikasi anda yang tersedia, silahkan verifikasi usulan yang anda disetujui !!
                            </div>
                        @else
                            <div class="alert alert-danger alert-block" id="keterangan">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Belum ada laporan kemajuan yang siap di verifikasi !!
                            </div>
                        @endif
                    </div>
                    {{ csrf_field() }} {{ method_field('PATCH') }}
                    <div class="col-md-12">
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modaldidanai">
                            <i class="fa fa-check-circle"></i>&nbsp;Setujui
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modaltidakdidanai">
                            <i class="fa fa-close"></i>&nbsp;Tidak Setujui
                        </button>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered" id="table" style="width:100%; ">
                            <thead>
                                <tr>
                                    <th style="text-align:center" >
                                        <input type="checkbox" class="form-control selectall">
                                    </th>
                                    <th>No</th>
                                    <th>Judul Kegiatan</th>
                                    <th style="text-align:center;">Total Skor</th>
                                    <th style="text-align:center;">Detail Skor</th>
                                    <th style="text-align:center;">Komentar Reviewer</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no=1;
                                @endphp
                                @foreach ($usulans as $usulan)
                                    <tr>

                                        <td style="text-align:center;">
                                            @if($usulan->status_usulan != "6" && $usulan->status_usulan != "7")
                                            <input type="checkbox" name="ids[]" class="selectbox" value="{{ $usulan->id }}">
                                            @endif
                                        </td>
                                        <td> {{ $no++ }} </td>
                                        <td style="width:40% !important;">
                                            {!! $usulan->shortJudul !!}
                                            <a onclick="selengkapnya({{ $usulan->id }})" id="selengkapnya">selengkapnya</a>
                                            <br>
                                            <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                            <span style="font-size:10px !important; text-transform:capitalize;" for="" class="badge badge-info">{{ $usulan->jenis_kegiatan }}</span>
                                            <span style="font-size:10px !important;" for="" class="badge badge-danger">{{ $usulan->ketua_peneliti_nama }}</span>
                                            <span style="font-size:10px !important;" for="" class="badge badge-secondary">{{ $usulan->tahun_usulan }}</span>
                                            <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                            <a href="{{ asset('upload/laporan_perbaikan/'.$usulan->file_perbaikan) }}" download="{{ $usulan->file_perbaikan }}"><i class="fa fa-download"></i>&nbsp; download file laporan perbaikan</a>
                                            <br>
                                            <a href="{{ asset('upload/laporan_kemajuan/'.$usulan->file_kemajuan) }}" download="{{ $usulan->file_kemajuan }}"><i class="fa fa-download"></i>&nbsp; download file laporan kemajuan</a>

                                        <td style="padding:15px 27px; text-align:center;"> {{ number_format($usulan->totalskor, 2) }} </td>
                                        <td style="padding:15px 30px; text-align:center;">
                                            <a onclick="detail({{ $usulan->id }})" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-info-circle"></i></a>
                                        </td>
                                        <td style="text-align:center;">
                                            <a onclick="komentar( {{ $usulan->id }} )"  class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"> <i class="fa fa-comments"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Modal Detail-->
                        <div class="modal fade" id="modaldetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <p style="font-size:15px; color:black;" class="modal-title" id="exampleModalLabel"><i class="fa fa-info-circle"></i>&nbsp;Detail Skor Usulan Penelitian</p>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-success alert-block" id="berhasil">
                                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                                    <strong><i class="fa fa-info-circle"></i>&nbsp;Data Detail Skor Per Kriteria Penilaian</strong>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <table class="table table-bordered table-striped" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <td>No</td>
                                                            <td>Kriteria Penlitian</td>
                                                            <td>Skor</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="detail-skor">

                                                    </tbody>
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
                    <!-- Modal Didanai -->
                    <div class="modal modal-danger fade" id="modaltidakdidanai" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header modal-header-danger">
                                <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Verifikasi Tidak Menyetujui Usulan Di Danai</p>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            Apakah anda yakin untuk tidak menyetujui memberi dana terhadap usulan penelitian terpilih ?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-light btn-sm " style="color:white; background:white; background:transparent;" data-dismiss="modal">Close</button>
                                <input type="submit" name="verifikasi" value="Tidak Setujui" class="btn btn-outline-light btn-sm" style="color:white; background-color:transparent;">
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Didanai -->
                <div class="modal modal-primary fade" id="modaldidanai" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header modal-header-danger">
                            <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Verifikasi Menyetujui Usulan Di Danai</p>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        Apakah anda yakin untuk memberi dana terhadap usulan penelitian terpilih ?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-light btn-sm " style="color:white; background:white; background:transparent;" data-dismiss="modal">Close</button>
                            <input type="submit" name="verifikasi" value="Setujui" class="btn btn-outline-light btn-sm" style="color:white; background-color:transparent;">
                        </div>
                    </div>
                    </div>
                </div>
            </form>
            <!-- Modal Detail -->
            <div class="modal fade" id="modaldetailjudul" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
        <!-- Modal Detail -->
        <div class="modal fade" id="modalkomentar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Informasi Detail Komentar Reviewer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <p style="font-weight:bold;">Komentar Reviewer:</p>
                    <hr>
                    <div id="detail-komentar" style="text-align:justify; ">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" style="font-size:12px;"><i class="fa fa-close"></i>&nbsp;Keluar</button>
                </div>
            </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#table').DataTable({
                responsive : true,
                "ordering": false,
            });
        } );

        function detail(id){
            $.ajax({
                url: "{{ url('operator/usulan_dosen/laporan_kemajuan/menunggu_verifikasi') }}"+'/'+ id + "/detail",
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    $('#modaldetail').modal('show');
                    var no=1;
                    var res='';
                    $.each (data, function (key, value) {
                        res +=
                        '<tr>'+
                            '<td>'+no+++'</td>'+
                            '<td>'+value.kriteria_penilaian+'</td>'+
                            '<td>'+value.skor.toFixed(2)+'</td>'+
                        '</tr>';
                    });
                    $('#detail-skor').html(res);
                },
                error:function(){
                    alert("Nothing Data");
                }
            });
        }

        function verifikasi(id){
            $('#modalverifikasi').modal('show');
            $('#usulan_id_verifikasi').val(id);
        }

        $('.selectall').click(function(){
            $('.selectbox').prop('checked', $(this).prop('checked'));
        });

        $('.selectbox').change(function(){
            var total = $('.selectbox').length;
            var number = $('.selectbox:checked').length;
            if(total == number){
                $('.selectall').prop('checked', true);
            }
            else{
                $('.selectall').prop('checked', false);
            }
        });

        function selengkapnya(id){
            $.ajax({
                url: "{{ url('operator/usulan_dosen/laporan_kemajuan/menunggu_verifikasi') }}"+'/'+ id + "/detail_judul",
                type: "GET",
                dataType: "JSON",
                success: function(data){
                $('#modaldetailjudul').modal('show');
                    $('#id').val(data.id);
                    $('#detail-text').text(data.judul_kegiatan);
                },
                error:function(){
                    alert("Nothing Data");
                }
            });
        }

        function komentar(id){
            $.ajax({
                url: "{{ url('operator/usulan_dosen/laporan_kemajuan/menunggu_verifikasi') }}"+'/'+ id + "/komentar",
                type: "GET",
                dataType: "JSON",
                success: function(data){
                $('#modalkomentar').modal('show');
                var no=1;
                var res='<table class="table table-bordered" id="table">'
                    res += '<tr>'+
                        '<td>'+'No'+'</td>'+
                        '<td>'+'Nama Reviewer'+'</td>'+
                        '<td>'+'Nip Reviewer'+'</td>'+
                        '<td>'+'Komentar'+'</td>'+
                    '</tr>';
                    $.each (data, function (key, value) {
                            res +='<tr>'+
                                '<td>'+ no++ +'</td>'+
                                '<td>'+value.reviewer_nama+'</td>'+
                                '<td>'+value.reviewer_nip+'</td>'+
                                '<td>'+value.komentar+'</td>'+
                            '</tr>';
                        });
                res +='</table>';

                    $('#detail-komentar').html(res);
                },
                error:function(){
                    alert("Nothing Data");
                }
            });
        }
    </script>
@endpush
