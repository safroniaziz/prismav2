@php
    use App\Usulan;
    use App\TotalSkor;
    use App\NilaiFormulir3;
@endphp
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
            <form action="{{ route('operator.verifikasi.verifikasi') }}" method="POST">
                <div class="row" style="margin-right:-15px; margin-left:-15px;">
                    <div class="col-md-12">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-block" id="berhasil">
                                
                                <strong><i class="fa fa-info-circle"></i>&nbsp;Berhasil: </strong> {{ $message }}
                            </div>
                            @elseif ($message2 = Session::get('error'))
                                <div class="alert alert-danger alert-block" id="berhasil">
                                    
                                    <strong><i class="fa fa-info-circle"></i>&nbsp;Gagal: </strong> {{ $message2 }}
                                </div>
                                @else
                                <div class="alert alert-danger alert-block" id="keterangan">
                                    
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
                        <div style="margin-bottom:10px;">
                            <ul class="nav nav-tabs" id="myTab">
                                <li class="active"><a class="nav-item nav-link active" data-toggle="tab" href="#nav-penelitian"><i class="fa fa-book"></i>&nbsp;Usulan Penelitian</a></li>
                                <li><a class="nav-item nav-link" data-toggle="tab" href="#nav-pengabdian"><i class="fa fa-list-alt"></i>&nbsp;Usulan Pengabdian</a></li>
                            </ul>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-penelitian" role="tabpanel" aria-labelledby="nav-honor-tab">
                                    <div class="row">
                                        <div class="col-md-12" style="margin-top: 5px;">
                                            <a href="{{ route('operator.verifikasi.cetak') }}" target="_blank"  class="btn btn-primary btn-sm"><i class="fa fa-print"></i>&nbsp; Cetak Data</a>
                                        </div>
                                        <div class="col-md-12" style="margin-top:10px;">
                                            <table class="table table-striped table-bordered" id="table" style="width:100%; ">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align:center" >
                                                            <input type="checkbox" class="form-control selectall">
                                                        </th>
                                                        <th>No</th>
                                                        <th>Judul Kegiatan</th>
                                                        <th>Total Skor</th>
                                                        <th style="text-align:center;">Detail Penilaian</th>
                                                        <th>Reviewer 3</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $no=1;
                                                    @endphp
                                                    @foreach ($penelitians as $penelitan)
                                                        @php
                                                            $total_skor = TotalSkor::where('usulan_id',$penelitan->id)->select('reviewer_id',DB::raw('SUM(total_skor)/2 as total_skor'))->first();
                                                            $jumlah = count(TotalSkor::where('usulan_id',$penelitan->id)->select('reviewer_id')->get());
                                                        @endphp
                                                        <tr>
                                                            <td style="text-align:center;">
                                                                @if($penelitan->status_usulan != "3" && $penelitan->status_usulan != "4")
                                                                <input type="checkbox" name="ids[]" class="selectbox" value="{{ $penelitan->id }}">
                                                                @endif
                                                            </td>
                                                            <td> {{ $no++ }} </td>
                                                            <td style="width:40% !important;">
                                                                {!! $penelitan->shortJudul !!}
                                                                <a href="{{ route('operator.menunggu.detail',[$penelitan->id,\Illuminate\Support\Str::slug($penelitan->judul_kegiatan)]) }}" id="selengkapnya">selengkapnya</a>
                                                                <br>
                                                                <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                                                <span style="font-size:10px !important; text-transform:capitalize;" for="" class="badge badge-info">{{ $penelitan->nm_skim }}</span>
                                                                <span style="font-size:10px !important; text-transform:capitalize;" for="" class="badge badge-success">{{ $penelitan->jenis_kegiatan }}</span>
                                                                <span style="font-size:10px !important;" for="" class="badge badge-danger">{{ $penelitan->nm_ketua_peneliti }}</span>
                                                                <span style="font-size:10px !important;" for="" class="badge badge-secondary">{{ $penelitan->tahun_usulan }}</span> <br>
                                                                Diusulkan {{ $penelitan->created_at ? $penelitan->created_at->diffForHumans() : '-' }} ({{ \Carbon\Carbon::parse($penelitan->created_at)->format('j F Y H:i') }})
                                                            </td>
                                                            
                                                                <td style="padding:15px 27px;"> {{ $total_skor->total_skor }} </td>
                                                            {{-- <td style="padding:15px 30px;">
                                                                    <a onclick="detail({{ $penelitan->id }})" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-info-circle"></i></a>
                                                                </td> --}}
                                                                <td class="text-center">
                                                                    <a href="{{ route('operator.verifikasi.detail_penilaian',[$penelitan->id, $penelitan->skim_id,\Illuminate\Support\Str::slug($penelitan->judul_kegiatan)]) }}"  class="btn btn-primary btn-sm" style="color:white; cursor:pointer;">Lihat detail</a>
                                                                </td>
                                                                <td style="text-align:center;">
                                                                    @if ($jumlah > 2)
                                                                        <a style="color: red">sudah ditambahkan</a>
                                                                        @else
                                                                        <a href="{{ route('operator.verifikasi.reviewer3',[$penelitan->id, $penelitan->skim_id,\Illuminate\Support\Str::slug($penelitan->judul_kegiatan)]) }}"  class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"> <i class="fa fa-plus"></i></a>
                                                                    @endif
                                                                </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade show" id="nav-pengabdian" role="tabpanel" aria-labelledby="nav-honor-tab">
                                    <div class="row">
                                        <div class="col-md-12" style="margin-top: 5px;">
                                            <a href="{{ route('operator.verifikasi.cetak_pengabdian') }}" target="_blank"  class="btn btn-primary btn-sm"><i class="fa fa-print"></i>&nbsp; Cetak Data</a>
                                        </div>
                                        <div class="col-md-12" style="margin-top:10px;">
                                            <table class="table table-striped table-bordered" id="table" style="width:100%; ">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align:center" >
                                                            <input type="checkbox" class="form-control selectall2">
                                                        </th>
                                                        <th>No</th>
                                                        <th>Judul Kegiatan</th>
                                                        <th>Total Skor</th>
                                                        {{-- <th>Detail Per Indikator</th> --}}
                                                        <th>Detail Per Reviewer</th>
                                                        <th style="text-align:center;">Komentar Reviewer</th>
                                                        <th>Reviewer 3</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $no=1;
                                                    @endphp
                                                    @foreach ($pengabdians as $pengabdians)
                                                            <tr>
                                                                <td style="text-align:center;">
                                                                    @if($pengabdians->status_usulan != "3" && $pengabdians->status_usulan != "4")
                                                                    <input type="checkbox" name="ids[]" class="selectbox2" value="{{ $pengabdians->id }}">
                                                                    @endif
                                                                </td>
                                                                <td> {{ $no++ }} </td>
                                                                <td style="width:40% !important;">
                                                                    {!! $pengabdians->shortJudul !!}
                                                                    <a onclick="selengkapnya({{ $pengabdians->id }})" id="selengkapnya">selengkapnya</a>
                                                                    <br>
                                                                    <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                                                    <span style="font-size:10px !important; text-transform:capitalize;" for="" class="badge badge-info">{{ $pengabdians->jenis_kegiatan }}</span>
                                                                    <span style="font-size:10px !important;" for="" class="badge badge-danger">{{ $pengabdians->ketua_peneliti_nama }}</span>
                                                                    <span style="font-size:10px !important;" for="" class="badge badge-secondary">{{ $pengabdians->tahun_usulan }}</span>
                                                                    <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                                                    <a href="{{ asset('upload/file_usulan/'.$pengabdians->file_usulan) }}" download="{{ $pengabdians->file_usulan }}"><i class="fa fa-download"></i>&nbsp; download file usulan</a>
                                                                    <br>
                                                                    <a href="{{ asset('upload/file_anggaran/'.$pengabdians->file_anggaran) }}" download="{{ $pengabdians->file_anggaran }}"><i class="fa fa-download"></i>&nbsp; download file anggaran</a>
                                                                    <br>
                                                                    <a href="{{ asset('upload/peta_jalan/'.$pengabdians->peta_jalan) }}" download="{{ $pengabdians->peta_jalan }}"><i class="fa fa-download"></i>&nbsp; download file peta jalan</a>
                                                                </td>
                                                                @php
                                                                    $tambah = Usulan::join('nilai_formulir3s','nilai_formulir3s.usulan_id','usulans.id')
                                                                                // ->join('formulirs','formulirs.id','nilai_formulir3s.formulir_id')
                                                                                ->select('total_skor')
                                                                                ->where('status_usulan','2')
                                                                                ->where('usulans.id',$pengabdians->id)
                                                                                ->first();
                                                                    if (!empty($tambah->total_skor)) {
                                                                        $new_total = $pengabdians->totalskor+$tambah['total_skor'];
                                                                    }
                                                                @endphp
                                                                {{-- {{ $pengabdians->total_skor }} --}}
                                                                    @if (empty($tambah->total_skor))
                                                                        <td style="padding:15px 27px;"> {{ number_format(($pengabdians->totalskor), 2) }} </td>
                                                                        @else
                                                                        <td style="padding:15px 27px;"> {{ number_format($new_total/2, 2) }} </td>
                                                                    @endif

                                                                {{-- <td style="padding:15px 30px;">
                                                                        <a onclick="detail({{ $pengabdians->id }})" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-info-circle"></i></a>
                                                                    </td> --}}
                                                                    <td style="padding:15px 30px;">
                                                                        <a onclick="detailReviewer({{ $pengabdians->id }})" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-info-circle"></i></a>
                                                                    </td>
                                                                    <td style="text-align:center;">
                                                                        <a onclick="komentar( {{ $pengabdians->id }} )"  class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"> <i class="fa fa-comments"></i></a>
                                                                    </td>
                                                                    <?php 
                                                                        $rev3 = NilaiFormulir3::where('usulan_id',$pengabdians->id)->first();
                                                                    ?>
                                                                    <td style="text-align:center;">
                                                                        @if (empty($rev3))
                                                                            <a href="{{ route('operator.verifikasi.reviewer3',[$pengabdians->id, $pengabdians->skim_id]) }}"  class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"> <i class="fa fa-plus"></i></a>
                                                                            @else
                                                                            <a class="btn btn-primary btn-sm disabled" style="color:white; cursor:pointer;"> <i class="fa fa-plus"></i></a>
                                                                        @endif
                                                                    </td>
                                                            </tr>
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
                                <p style="font-size:15px; color:black;" class="modal-title" id="exampleModalLabel"><i class="fa fa-info-circle"></i>&nbsp;Detail Skor Usulan Penelitian</p>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-success alert-block" id="berhasil">
                                                
                                                <strong><i class="fa fa-info-circle"></i>&nbsp;Data Detail Skor Per Kriteria Penilaian</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <td>No</td>
                                                        <td>Nama Reviewer</td>
                                                        {{-- <td>Kriteria Penlitian</td> --}}
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
                         <!-- Modal Detail-->
                         <div class="modal fade" id="modaldetailreviewer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                                
                                                <strong><i class="fa fa-info-circle"></i>&nbsp;Data Detail Skor Per Kriteria Penilaian & Reviewer</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <td>No</td>
                                                        <td>Nama Reviewer</td>
                                                        <td>Nip Reviewer</td>
                                                        <td>Status Reviewer</td>
                                                        {{-- <td>Kriteria Penilaian</td> --}}
                                                        <td>Skor</td>
                                                    </tr>
                                                </thead>
                                                <tbody id="detail-reviewer">

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
            $("table[id^='table']").DataTable({
                responsive : true,
                "ordering": false,
            });
        } );

        function detail(id){
            $.ajax({
                url: "{{ url('operator/usulan_dosen/menunggu_verifikasi') }}"+'/'+ id + "/detail",
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

        function detailReviewer(id){
            $.ajax({
                url: "{{ url('operator/usulan_dosen/menunggu_verifikasi') }}"+'/'+ id + "/detail_reviewer",
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    $('#modaldetailreviewer').modal('show');
                    var no=1;
                    var res='';
                    $.each (data, function (key, value) {
                        res +=
                        '<tr>'+
                            '<td>'+no+++'</td>'+
                            '<td>'+value.reviewer_nama+'</td>'+
                            '<td>'+value.reviewer_nip+'</td>'+
                            '<td>'+value.jenis_reviewer+'</td>'+
                            // '<td>'+value.kriteria_penilaian+'</td>'+
                            '<td>'+value.total_skor+'</td>'+
                        '</tr>';
                    });
                    $('#detail-reviewer').html(res);
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

        $('.selectall2').click(function(){
            $('.selectbox2').prop('checked', $(this).prop('checked'));
        });

        $('.selectbox2').change(function(){
            var total = $('.selectbox2').length;
            var number = $('.selectbox2:checked').length;
            if(total == number){
                $('.selectall2').prop('checked', true);
            }
            else{
                $('.selectall2').prop('checked', false);
            }
        });

        function selengkapnya(id){
            $.ajax({
                url: "{{ url('operator/usulan_dosen/menunggu_verifikasi') }}"+'/'+ id + "/detail_judul",
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
                url: "{{ url('operator/usulan_dosen/menunggu_verifikasi') }}"+'/'+ id + "/komentar",
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
