@php
    use App\Usulan;
@endphp
@extends('layouts.layout')
@section('title', 'Riwayat Review')
@section('user-login')
    @if (Auth::guard('reviewerusulan')->check())
        {{ Auth::guard('reviewerusulan')->user()->reviewer_nama }}
    @endif
@endsection
@section('user-login2')
    @if (Auth::guard('reviewerusulan')->check())
        {{ Auth::guard('reviewerusulan')->user()->reviewer_nama }}
    @endif
@endsection
@section('login_as', 'Reviewer Eksternal')
@section('sidebar-menu')
    @include('reviewer_eksternal/sidebar')
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
            <i class="fa fa-home"></i>&nbsp;Riwayat Review Usulan Publikasi, Riset, dan Pengabdian Kepada Masyarakat
        </header>
        <div class="panel-body" style="border-top: 1px solid #eee; padding:15px; background:white;">
            <div class="row" style="margin-right:-15px; margin-left:-15px;">
                <div class="col-md-12">
                    @if (count($riwayats) >0)
                        <div class="alert alert-danger alert-block" id="keterangan">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah semua usulan kegiatan yang sudah anda review !!
                        </div>
                        @else
                        <div class="alert alert-danger alert-block" id="keterangan">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Anda belum memiliki riwayat usulan kegiatan yang di review !!
                        </div>
                    @endif
                </div>
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="table" style="width:100%;">
                        <thead>
                            <tr>
                                <th style="text-align:center;">No</th>
                                <th style="text-align:center;">Judul Kegiatan</th>
                                <th style="text-align:center;">Anggota Kelompok</th>
                                <th style="text-align:center;">Biaya Diusulkan</th>
                                <th style="text-align:center;">Status Usulan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no=1;
                            @endphp
                            @foreach ($riwayats as $riwayat)
                                @php
                                    $sudah = Usulan::leftJoin('nilai_formulirs','nilai_formulirs.usulan_id','usulans.id')->select('usulans.id')->where('nilai_formulirs.reviewer_id',$riwayat->nip_reviewer)->where('usulans.id',$riwayat->id)->first();
                                @endphp
                                @if ($sudah['id'] != null)
                                    <tr>
                                        <td> {{ $no++ }} </td>
                                        <td style="width:40% !important;">
                                            {!! $riwayat->shortJudul !!}
                                            <a onclick="detail({{ $riwayat->id }})" id="selengkapnya">selengkapnya</a>
                                            <br>
                                            <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                            <span style="font-size:10px !important; text-transform:capitalize;" for="" class="badge badge-info">{{ $riwayat->jenis_kegiatan }}</span>
                                            <span style="font-size:10px !important;" for="" class="badge badge-danger">{{ $riwayat->nm_ketua_peneliti }}</span>
                                            <span style="font-size:10px !important;" for="" class="badge badge-secondary">{{ $riwayat->tahun_usulan }}</span>
                                            <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                            <a href="{{ asset('upload/file_usulan/'.$riwayat->file_usulan) }}" download="{{ $riwayat->file_usulan }}"><i class="fa fa-download"></i>&nbsp; download file usulan</a>
                                            <br>
                                            <a href="{{ asset('upload/peta_jalan/'.$riwayat->peta_jalan) }}" download="{{ $riwayat->peta_jalan }}"><i class="fa fa-download"></i>&nbsp; download file peta jalan</a>
                                            <br>
                                            <a href="{{ asset('upload/lembar_pengesahan/'.$riwayat->lembar_pengesahan) }}" download="{{ $riwayat->lembar_pengesahan }}"><i class="fa fa-download"></i>&nbsp; download file lembar pengesahan</a>
                                    </td>
                                        <td>
                                            @if ($riwayat->nm_anggota == null)
                                                <label class="badge badge-danger"><i class="fa fa-close" style="padding:5px;"></i>&nbsp;Belum ditambahkan</label>
                                                @else
                                                <label class="badge" style="font-size:12px;">&nbsp;{!! $riwayat->nm_anggota !!}</label>
                                            @endif
                                        </td>
                                        <td style="width:30%; text-align:center;">
                                            <a>Rp. {{ number_format($riwayat->biaya_diusulkan, 2) }}</a>
                                            <br>
                                            <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                            <a href="{{ route('operator.usulan.anggaran.cetak',[$riwayat->id]) }}" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-print"></i>&nbsp; Cetak</a>
                                        </td>
                                        <td style="text-align:center">
                                            @if ($riwayat->status_usulan == '0')
                                                <label class="badge badge-warning" style="color:white;"><i class="fa fa-clock-o" style="padding:5px;"></i>&nbsp;belum diusulkan</label>
                                                @elseif($riwayat->status_usulan == "1")
                                                <label class="badge badge-info" style="color:white;"><i class="fa fa-clock-o" style="padding:5px;"></i>&nbsp;menunggu verifikasi</label>
                                                @elseif($riwayat->status_usulan == "2")
                                                <label class="badge badge-success" style="color:white;"><i class="fa fa-clock-o" style="padding:5px;"></i>&nbsp;sudah di review</label>
                                                @elseif($riwayat->status_usulan == "3")
                                                <label class="badge badge-success" style="color:white;"><i class="fa fa-check-circle" style="padding:5px;"></i>&nbsp;usulan diterima</label>
                                                @elseif($riwayat->status_usulan == "4")
                                                <label class="badge badge-danger" style="color:white;"><i class="fa fa-close" style="padding:5px;"></i>&nbsp;usulan ditolak</label>
                                                @elseif($riwayat->status_usulan == "5")
                                                <label class="badge badge-info" style="color:white;"><i class="fa fa-info-circle" style="padding:5px;"></i>&nbsp;laporan kemajuan</label>
                                                @elseif($riwayat->status_usulan == "6")
                                                <label class="badge badge-success" style="color:white;"><i class="fa fa-check-circle" style="padding:5px;"></i>&nbsp;laporan kemajuan diterima</label>
                                                @elseif($riwayat->status_usulan == "7")
                                                <label class="badge badge-danger" style="color:white;"><i class="fa fa-close" style="padding:5px;"></i>&nbsp;laporan kemajuan ditolak</label>
                                            @endif
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
                                                <td style="width:20%;">Judul Kegiatan</td>
                                                <td> : </td>
                                                <td>
                                                    <p id="judul_kegiatan_detail"></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:20%;">Tujuan Kegiatan</td>
                                                <td> : </td>
                                                <td>
                                                    <p id="tujuan_kegiatan_detail"></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:20%;">Luaran Kegiatan</td>
                                                <td> : </td>
                                                <td>
                                                    <p id="luaran_kegiatan_detail"></p>
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
                                                <td>Skim Penelitian</td>
                                                <td> : </td>
                                                <td>
                                                    <p id="skim_penelitian_detail"></p>
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
            $('#table').DataTable({
                responsive : true,
            });
        } );

        function detail(id){
            $.ajax({
                url: "{{ url('reviewer/riwayat_review_usulan') }}"+'/'+ id + "/detail",
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    $('#modaldetail').modal('show');
                    $('#judul_kegiatan_detail').text(data['usulan'].judul_kegiatan);
                    $('#abstrak_kegiatan_detail').append(data['usulan'].abstrak);
                    $('#tujuan_kegiatan_detail').append(data['usulan'].tujuan);
                    $('#luaran_kegiatan_detail').text(data['usulan'].luaran);
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
    </script>
@endpush
