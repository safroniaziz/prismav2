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
            <i class="fa fa-home"></i>&nbsp;Usulan Publikasi, Riset dan Pengabdian Kepada Masyarakat
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
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah semua usulan anda yang tersedia, silahkan tambahkan usulan baru jika diperlukan !!
                        </div>
                    @endif
                    <div class="alert alert-success alert-block" style="display:none;" id="usulan-berhasil">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <i class="fa fa-success-circle"></i><strong>Berhasil :</strong> Penelitian anda sudah diusulkan !!
                    </div>
                    <div class="alert alert-danger alert-block" style="display:none;" id="gagal">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <i class="fa fa-close"></i><strong>&nbsp;Gagal :</strong> Proses pengusulan gagal !!
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul Penelitian</th>
                                <th>Bidang Penelitian</th>
                                <th>Ketua Peneliti</th>
                                <th>Anggota Kelompok</th>
                                <th>Biaya Diusulkan</th>
                                <th>Rancangan Anggaran</th>
                                <th>Peta Jalan Penelitian</th>
                                <th>Status Usulan</th>
                                <th>Reviewer</th>
                                <th>Tambah Reviewer</th>
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
                                @if ($jumlah < 2)
                                <tr>
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
                                    <td>
                                        @if ($usulan->status_usulan == '0')
                                            <label class="badge badge-warning" style="color:white;"><i class="fa fa-clock-o" style="padding:5px;"></i>&nbsp;belum diusulkan</label>
                                            @elseif($usulan->status_usulan == "1")
                                            <label class="badge badge-info" style="color:white;"><i class="fa fa-clock-o" style="padding:5px;"></i>&nbsp;menunggu verifikasi</label>
                                            @elseif($usulan->status_usulan == "2")
                                            <label class="badge badge-success" style="color:white;"><i class="fa fa-clock-o" style="padding:5px;"></i>&nbsp;disetujui</label>
                                            @elseif($usulan->status_usulan == "3")
                                            <label class="badge badge-danger" style="color:white;"><i class="fa fa-close" style="padding:5px;"></i>&nbsp;ditolak</label>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($usulan->nm_reviewer == null || $usulan->nm_reviewer == "")
                                            <label class="badge badge-danger" style="padding:5px;">-</label>
                                            @else
                                            <label class="badge" style="font-size:12px;">&nbsp;{!! $usulan->nm_reviewer !!}</label>
                                        @endif
                                    </td>
                                    <td>
                                        <a onclick="tambahReviewer({{ $usulan->id }})" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-user-plus"></i></a>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
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
            </div>
            <!-- Modal Anggota -->
            <div class="modal fade" id="modalreviewer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Tambah Anggota Penelitian</p>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action=" {{ route('operator.usulan.reviewer_post') }} " method="POST">
                            {{ csrf_field() }} {{ method_field('POST') }}
                                <div class="modal-body">
                                    <input type="hidden" name="usulan_id" id="usulan_id">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="exampleInputEmail1">Pilih Fakultas</label>
                                            <select name="fakultas_id" id="fakultas_id" class="form-control" required style="font-size:13px;">
                                                <option value="" disabled selected>-- pilih fakultas --</option>
                                                @foreach ($fakultas as $fakultas)
                                                    <option value=" {{ $fakultas->fakultas_kode }} "> {{ $fakultas->nm_fakultas }} </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="exampleInputEmail1">Pilih Program Studi</label>
                                            <select name="prodi_id" id="prodi_id" class="form-control" required style="font-size:13px;">
                                                <option value="" disabled selected>-- pilih prodi --</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="exampleInputEmail1">Pilih Reviewer</label>
                                            <select name="reviewer_id" id="reviewer_id" class="form-control" required style="font-size:13px;">
                                                <option value="" disabled selected>-- pilih reviewer --</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                             <button type="submit" style="font-size:13px;" class="btn btn-primary" disabled id="btn-submit-reviewer"><i class="fa fa-save"></i>&nbsp;Simpan</button>
                                             <button type="reset" style="font-size:13px;" class="btn btn-danger"><i class="fa fa-refresh"></i>&nbsp; Reset</button>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 table-responsive">
                                            <div class="alert alert-success alert-block" id="berhasil">
                                                <button type="button" class="close" data-dismiss="alert">×</button>
                                                <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah reviewer penelitian dengan judul : <a style="font-weight:bold; font-size:12px; text-decoration:underline;" id="judul"></a>
                                            </div>
                                            <table class="table table-bordered table-striped" id="anggota" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Anggota</th>
                                                        <th>Nip</th>
                                                        <th>Program Studi</th>
                                                        <th>Fakultas</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" style="font-size:13px;" data-dismiss="modal"><i class="fa fa-arrow-left"></i>&nbsp;Kembali</button>
                                </div>
                            </div>
                        </form>
                    </div>
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

        function detail(id){
            $.ajax({
                url: "{{ url('operator/usulan_dosen/menunggu_disetujui') }}"+'/'+ id + "/detail",
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    $('#modaldetail').modal('show');
                    $('#judul_penelitian_detail').text(data['usulan'].judul_penelitian);
                    $('#skim_penelitian_detail').text(data['usulan'].nm_skim);
                    $('#bidang_penelitian_detail').text(data['usulan'].bidang_penelitian);
                    $('#ketua_peneliti_detail').text(data['usulan'].nm_ketua_peneliti);
                    $('#ketua_nip').text(data['usulan'].nip);
                    $('#ketua_prodi').text(data['usulan'].prodi);
                    $('#ketua_fakultas').text(data['usulan'].fakultas);
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
                            '<li>'+'Nip : '+value.nip+'</li>'+
                            '<li>'+'Fakultas : '+value.fakultas+'</li>'+
                            '<li>'+'Program Studi : '+value.prodi+'</li>'+
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
                                '<li>'+'Nip : '+value.nip+'</li>'+
                                '<li>'+'Fakultas : '+value.fakultas+'</li>'+
                                '<li>'+'Program Studi : '+value.prodi+'</li>'+
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

        $(document).ready(function(){
            $(document).on('change','#fakultas_id',function(){
                // alert('berhasil');
                var fakultas_id = $(this).val();
                var div = $(this).parent().parent();
                var op=" ";
                $.ajax({
                type :'get',
                url: "{{ url('operator/usulan_dosen/menunggu_disetujui/cari_prodi') }}",
                data:{'fakultas_id':fakultas_id},
                success:function(data){
                    op+='<option value="0" selected disabled>-- pilih prodi --</option>';
                    for(var i=0; i<data.length;i++){
                        op+='<option value="'+data[i].prodi_kode+'">'+data[i].nm_prodi+'</option>';
                    }
                    div.find('#prodi_id').html(" ");
                    div.find('#prodi_id').append(op);
                },
                error:function(){
                }
                });
            })
        });

        $(document).ready(function(){
            $(document).on('change','#prodi_id',function(){
                // alert('berhasil');
                var prodi_id = $(this).val();
                var div = $(this).parent().parent();
                var op=" ";
                $.ajax({
                type :'get',
                url: "{{ url('operator/usulan_dosen/menunggu_disetujui/cari_reviewer') }}",
                data:{'prodi_id':prodi_id},
                success:function(data){
                    op+='<option value="0" selected disabled>-- pilih reviewer --</option>';
                    for(var i=0; i<data.length;i++){
                        if (data[i]['pegawai']['pegIsAktif'] == 1) {
                            op+='<option value="'+data[i].dsnPegNip+'">'+data[i]['pegawai'].pegNama+'</option>';
                        }
                    }
                    div.find('#reviewer_id').html(" ");
                    div.find('#reviewer_id').append(op);
                },
                error:function(){
                }
                });
            })
        });

        function tambahReviewer(id){
            $.ajax({
                url: "{{ url('operator/usulan_dosen/menunggu_disetujui') }}"+'/'+ id + "/get_reviewer",
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    $('#modalreviewer').modal('show');
                    $('#usulan_id').val(id);
                    $('#judul').text(data['usulan'].judul_penelitian);
                    var res='';
                    var no = 1;
                    $.each (data['reviewers'], function (key, value) {
                        res +=
                        '<tr>'+
                            '<td>'+no+++'</td>'+
                            '<td>'+value.nm_lengkap+'</td>'+
                            '<td>'+value.nip+'</td>'+
                            '<td>'+value.prodi+'</td>'+
                            '<td>'+value.fakultas+'</td>'+
                        '</tr>';
                    });
                    $('#tbody').html(res);
                },
                error:function(){
                    alert("Nothing Data");
                }
            });
        }

        $(document).ready(function(){
            $('#reviewer_id').change(function(){
                var status = $('#reviewer_id').val();
                if(status != null){
                    $('#btn-submit-reviewer').attr('disabled',false);
                }
                else{
                    $('#btn-submit-reviewer').attr('disabled',true);
                }
            });
        });
    </script>
@endpush
