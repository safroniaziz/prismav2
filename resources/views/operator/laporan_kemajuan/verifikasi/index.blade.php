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
                                @else
                                <div class="alert alert-success alert-block" id="keterangan">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah semua usulan yang siap di verifikasi anda yang tersedia, silahkan verifikasi usulan yang anda disetujui !!
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
                                    <th>Judul Penelitian</th>
                                    <th>Total Skor</th>
                                    <th>Detail Skor</th>
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
                                        <td> {{ $usulan->judul_penelitian }} </td>
                                        <td style="padding:15px 27px;"> {{ number_format($usulan->totalskor, 2) }} </td>
                                        <td style="padding:15px 30px;">
                                            <a onclick="detail({{ $usulan->id }})" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-info-circle"></i></a>
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
    </script>
@endpush