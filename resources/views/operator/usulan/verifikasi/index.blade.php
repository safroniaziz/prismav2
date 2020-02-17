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
            <div class="row" style="margin-right:-15px; margin-left:-15px;">
                <div class="col-md-12">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block" id="berhasil">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Berhasil: </strong> {{ $message }}
                        </div>
                        @else
                        <div class="alert alert-success alert-block" id="keterangan">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah semua usulan yang siap di verifikasi anda yang tersedia, silahkan verifikasi usulan yang anda disetujui !!
                        </div>
                    @endif
                </div>
                <form action="{{ route('operator.verifikasi.verifikasi') }}" method="POST">
                    {{ csrf_field() }} {{ method_field('PATCH') }}
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-info btn-sm"><i class="fa fa-check-circle"></i>&nbsp;Setujui</button>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered" id="table" style="width:100%;">
                            <thead>
                                <tr>
                                    <th style="text-align:center" >
                                        <input type="checkbox" class="form-control selectall">
                                    </th>
                                    <th>No</th>
                                    <th>Judul Penelitian</th>
                                    <th>Bidang Penelitian</th>
                                    <th>Ketua Peneliti</th>
                                    <th>Anggota Kelompok</th>
                                    <th>Biaya Diusulkan</th>
                                    <th>Rancangan Anggaran</th>
                                    <th>Peta Jalan Penelitian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no=1;
                                @endphp
                                @foreach ($usulans as $usulan)
                                    <tr>

                                        <td style="text-align:center;">
                                            @if($usulan->status_usulan != "3" && $usulan->status_usulan != "4")
                                            <input type="checkbox" name="ids[]" class="selectbox" value="{{ $usulan->id }}">
                                            @endif
                                        </td>
                                        <td> {{ $no++ }} </td>
                                        <td> <a onclick="detail( {{ $usulan->id }} )" id="detail">{{ $usulan->judul_penelitian }}</a> </td>
                                        <td> {{ $usulan->bidang_penelitian }} </td>
                                        <td> {{ $usulan->nm_ketua_peneliti }} </td>
                                        <td>
                                            @if ($usulan->nm_anggota == null)
                                                <label class="badge badge-danger"><i class="fa fa-close" style="padding:5px;"></i>&nbsp;Belum ditambahkan</label>
                                                @else
                                                <label class="badge" style="font-size:12px;">&nbsp;{!! $usulan->nm_anggota !!}</label>
                                            @endif
                                        </td>
                                        <td> Rp. {{ number_format($usulan->biaya_diusulkan, 2) }} </td>
                                        <td>
                                            <a href="{{ route('operator.usulan.anggaran.cetak',[$usulan->id]) }}" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-print"></i></a>
                                        </td>
                                        <td>
                                            <a href="{{ asset('upload/peta_jalan/'.$usulan->peta_jalan) }}" download="{{ $usulan->peta_jalan }}">
                                                <button type="button" class="btn btn-primary" style="padding:7px;font-size:13px;color:white;cursor:pointer;"><i class="fa fa-download"></i></button>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <!-- Modal Verifikasi -->
                            <div class="modal fade" id="modalverifikasi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <form action=" {{ route('operator.verifikasi.verifikasi') }} " method="POST">
                                    {{ csrf_field() }} {{ method_field('PATCH') }}
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                            <p style="font-size:15px; color:black;" class="modal-title" id="exampleModalLabel"><i class="fa fa-check-circle"></i>&nbsp;Form Verifikasi Usulan Dosen</p>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            </div>
                                            <div class="modal-body" style="padding-top:0px; padding-bottom:0px;">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <input type="hidden" name="usulan_id" id="usulan_id_verifikasi">
                                                        <div class="form-group">
                                                            <label for="recipient-name" class="col-form-label">Verifikasi:</label>
                                                            <select name="status_verifikasi" id="status_verifikasi" class="form-control">
                                                                <option value="" selected disabled>-- silahkan lakukan approve data --</option>
                                                                <option value="1">Setujui</option>
                                                                <option value="2">Tidak Setuju</option>
                                                            </select>
                                                            <small id="emailHelp" class="form-text text-danger"><i>Data yang terverifikasi tidak dapat diubah kembali !!</i></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" style="font-size:13px;"><i class="fa fa-arrow-left"></i>&nbsp;Kembali</button>
                                            <button type="submit" class="btn btn-primary btn-sm" style="font-size:13px;"><i class="fa fa-check-circle"></i>&nbsp;Verifikasi</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </table>
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
                                                    <td style="width:20%;">Judul Penelitian</td>
                                                    <td> : </td>
                                                    <td>
                                                        <p id="judul_penelitian_detail"></p>
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
                                                    <td>Bidang Penelitian</td>
                                                    <td> : </td>
                                                    <td>
                                                        <p id="bidang_penelitian_detail"></p>
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
                                                    <td>Anggota Penelitian</td>
                                                    <td> : </td>
                                                    <td>
                                                        <p id="anggota_penelitian_detail"></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Reviewer Penelitian</td>
                                                    <td> : </td>
                                                    <td>
                                                        <p id="reviewer_penelitian_detail"></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Abstrak</td>
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
                                                <tr>
                                                    <td>Peta Jalan</td>
                                                    <td> : </td>
                                                    <td>
                                                        <p id="peta_jalan_detail"></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Biaya Diusulkan</td>
                                                    <td> : </td>
                                                    <td>
                                                        <p id="biaya_diusulkan_detail"></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tahun Usulan</td>
                                                    <td> : </td>
                                                    <td>
                                                        <p id="tahun_usulan_detail"></p>
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
                </form>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script type="text/javascript">
        // $(document).ready(function() {
        //     $('#table').DataTable({
        //         responsive : true,
        //     });
        // } );

        function detail(id){
            $.ajax({
                url: "{{ url('operator/usulan_dosen/menunggu_verifikasi') }}"+'/'+ id + "/detail",
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    $('#modaldetail').modal('show');
                    $('#judul_penelitian_detail').text(data['usulan'].judul_penelitian);
                    $('#skim_penelitian_detail').text(data['usulan'].nm_skim);
                    $('#bidang_penelitian_detail').text(data['usulan'].bidang_penelitian);
                    $('#ketua_peneliti_detail').text(data['usulan'].nm_ketua_peneliti);
                    $('#ketua_nip').text(data['usulan'].nip);
                    $('#ketua_prodi').text(data['usulan'].ketua_peneliti_prodi_nama);
                    $('#ketua_fakultas').text(data['usulan'].ketua_peneliti_fakultas_nama);
                    $('#abstrak_detail').html(data['usulan'].abstrak);
                    $('#kata_kunci_detail').html(data['usulan'].kata_kunci);
                    $('#peta_jalan_detail').html(data['usulan'].peta_jalan);
                    $('#biaya_diusulkan_detail').html(data['usulan'].biaya_diusulkan);
                    $('#tahun_usulan_detail').html(data['usulan'].tahun_usulan);
                    var res='';
                    $.each (data['anggotas'], function (key, value) {
                        res +=
                        '<b>'+value.nm_anggota+'</b>'+
                        '<ul>'+
                            '<li>'+'Nip : '+value.anggota_nip+'</li>'+
                            '<li>'+'Fakultas : '+value.anggota_fakultas_nama+'</li>'+
                            '<li>'+'Program Studi : '+value.anggota_prodi_nama+'</li>'+
                        '</ul>';
                    });
                    $('#anggota_penelitian_detail').html(res);

                    if (data['reviewers'][0] == null) {
                        $('#reviewer_penelitian_detail').html("<i style="+"color:red;"+">Reviewer Belum Ditambahkan !!"+"</i>");
                    }
                    else{
                        var res2='';
                        $.each (data['reviewers'], function (key, value) {
                            res2 +=
                            '<b>'+value.nm_anggota+'</b>'+
                            '<ul>'+
                                '<li>'+'Nip : '+value.reviewer_nip+'</li>'+
                                '<li>'+'Fakultas : '+value.reviewer_fakultas_nama+'</li>'+
                                '<li>'+'Program Studi : '+value.reviewer_prodi_nama+'</li>'+
                            '</ul>';
                        });
                        $('#reviewer_penelitian_detail').html(res2);
                    }
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
