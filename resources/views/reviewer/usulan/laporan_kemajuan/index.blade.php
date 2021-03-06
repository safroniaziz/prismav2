@extends('layouts.layout')
@section('title', 'Dashboard')
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
@section('login_as', 'Reviewer')
@section('sidebar-menu')
    @include('reviewer/sidebar')
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
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block" id="berhasil">
                            
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Berhasil: </strong> {{ $message }}
                        </div>
                        @else
                        @if (count($jadwal) > 0)
                            @if ($now >= $jadwal[0]->tanggal_awal && $now <= $jadwal[0]->tanggal_akhir)
                                @if (count($usulans)>0)
                                    <div class="alert alert-danger alert-block" id="keterangan">
                                        
                                        <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah usulan kegiatan yang akan anda review, silakan review semua usulan penelitian !!
                                    </div>
                                    @else
                                        <div class="alert alert-danger alert-block" id="keterangan">
                                            
                                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Anda tidak memiliki usulan kegiatan untuk di review !!
                                        </div>
                                @endif
                                @else
                                <div class="alert alert-danger alert-block" id="keterangan">
                                    
                                    <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Saat Ini Bukan Masa Review Usulan Kegiatan !!
                                </div>
                            @endif
                            @else
                            <div class="alert alert-danger alert-block" id="keterangan">
                                
                                <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Jadwal Review Usulan Belum Diatur !!
                            </div>
                        @endif
                    @endif
                    <div class="alert alert-success alert-block" style="display:none;" id="usulan-berhasil">
                        
                        <i class="fa fa-success-circle"></i><strong>Berhasil :</strong> Penelitian anda sudah diusulkan !!
                    </div>
                    <div class="alert alert-danger alert-block" style="display:none;" id="gagal">
                        
                        <i class="fa fa-close"></i><strong>&nbsp;Gagal :</strong> Proses pengusulan gagal !!
                    </div>
                </div>
                <div class="col-md-12">
                    @if (count($jadwal) > 0)
                        @if ($now >= $jadwal[0]->tanggal_awal && $now <= $jadwal[0]->tanggal_akhir)
                            <table class="table table-striped table-bordered" id="table" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th style="text-align:center;">No</th>
                                        <th style="text-align:center;">Judul Kegiatan</th>
                                        <th style="text-align:center;">Ketua Peneliti</th>
                                        <th style="text-align:center;">Anggota Kelompok</th>
                                        <th style="text-align:center;">Review</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no=1;
                                    @endphp
                                    @foreach ($usulans as $usulan)
                                        <tr>
                                            <td> {{ $no++ }} </td>
                                            <td style="width:40% !important;">
                                                {!! $usulan->shortJudul !!}
                                                <a onclick="detail({{ $usulan->id }})" id="selengkapnya">selengkapnya</a>
                                                <br>
                                                <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                                <span style="font-size:10px !important; text-transform:capitalize;" for="" class="badge badge-info">{{ $usulan->jenis_kegiatan }}</span>
                                                <span style="font-size:10px !important;" for="" class="badge badge-danger">{{ $usulan->nm_ketua_peneliti }}</span>
                                                <span style="font-size:10px !important;" for="" class="badge badge-secondary">{{ $usulan->tahun_usulan }}</span>
                                                <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                                <a href="{{ asset('upload/laporan_kemajuan/'.$usulan->file_kemajuan) }}" download="{{ $usulan->file_kemajuan }}"><i class="fa fa-download"></i>&nbsp; download file laporan kemajuan</a>
                                        </td>
                                            <td style="text-align:center;"> {{ $usulan->nm_ketua_peneliti }} </td>
                                            <td style="text-align:center;">
                                                @if ($usulan->nm_anggota == null)
                                                    <label class="badge badge-danger"><i class="fa fa-close" style="padding:5px;"></i>&nbsp;Belum ditambahkan</label>
                                                    @else
                                                    <label class="badge" style="font-size:12px;">&nbsp;{!! $usulan->nm_anggota !!}</label>
                                                @endif
                                            </td>
                                            <td style="text-align:center;">
                                                @if ($usulan->reviewer_id == null)
                                                    <a href=" {{ route('reviewer.laporan_kemajuan.review',[$usulan->id, $usulan->skim_id]) }} " class="btn btn-primary btn-sm" style="color:white;"><i class="fa fa-star"></i></a>
                                                    @else
                                                    <button class="btn btn-primary btn-sm" style="color:white;" disabled><i class="fa fa-star"></i></button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                                @else
                            @endif
                    @endif
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
                                                <td>Reviewer Kegiatan</td>
                                                <td> : </td>
                                                <td>
                                                    <p id="reviewer_penelitian_detail"></p>
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
                url: "{{ url('reviewer/usulan_dosen/laporan_kemajuan') }}"+'/'+ id + "/detail",
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    $('#modaldetail').modal('show');
                    $('#judul_kegiatan_detail').text(data['usulan'].judul_kegiatan);
                    $('#skim_penelitian_detail').text(data['usulan'].nm_skim);
                    $('#jenis_kegiatan_detail').text(data['usulan'].jenis_kegiatan);
                    $('#ketua_peneliti_detail').text(data['usulan'].nm_ketua_peneliti);
                    $('#ketua_nip').text(data['usulan'].ketua_peneliti_nip);
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
    </script>
@endpush
