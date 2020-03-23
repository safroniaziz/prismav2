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
@section('login_as', 'Dosen Pengusul')
@section('sidebar-menu')
    @include('pengusul/sidebar')
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
            <i class="fa fa-home"></i>&nbsp;Usulan Publikasi Didanai
        </header>
        <div class="panel-body" style="border-top: 1px solid #eee; padding:15px; background:white;">
            <div class="row" style="margin-right:-15px; margin-left:-15px;">
                <div class="col-md-12">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block" id="berhasil">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Berhasil: </strong> {{ $message }}
                        </div>
                        @elseif(count($usulans)<1)
                        <div class="alert alert-danger alert-block" id="keterangan">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Belum Ada Usulan Yang Sampai Tahap Laporan Akhir Yang Disetujui!!
                        </div>
                        @else
                        <div class="alert alert-danger alert-block" id="keterangan">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Silahkan Upload Laporan Akhir & Luaran Kegiatan Anda !!
                        </div>
                    @endif
                </div>
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="table" style="width:100%; ">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul Kegiatan</th>
                                <th style="text-align:center;">Laporan akhir</th>
                                <th style="text-align:center;">Upload Laporan</th>
                                <th style="text-align:center;">Luaran Kegiatan</th>
                                <th style="text-align:center;">Konfirmasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no=1;
                            @endphp
                            @foreach ($usulans as $usulan)
                                <tr>
                                    <td> {{ $no++ }} </td>
                                    <td style="width:30% !important;">
                                        {!! $usulan->shortJudul !!}
                                        <a onclick="selengkapnya({{ $usulan->id }})" id="selengkapnya">selengkapnya</a>
                                        <br>
                                        <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                        <span style="font-size:10px !important; text-transform:capitalize;" for="" class="badge badge-info">{{ $usulan->jenis_kegiatan }}</span>
                                        <span style="font-size:10px !important;" for="" class="badge badge-danger">{{ $usulan->ketua_peneliti_nama }}</span>
                                        <span style="font-size:10px !important;" for="" class="badge badge-secondary">{{ $usulan->tahun_usulan }}</span>
                                    </td>
                                    <td style="text-align:center;">
                                        @if ($usulan->file_akhir == null)
                                            <a style="color:red;"><i>belum diupload</i></a>
                                            @else
                                                <label class="badge badge-success" style="padding:10px;"><i class="fa fa-check-circle"></i></label>
                                        @endif
                                    </td>
                                    <td style="text-align:center;">
                                        @if ($usulan->file_akhir == null)
                                            <a onclick="uploadLaporan({{ $usulan->id }})" style="color:white; cursor:pointer;" class="btn btn-primary btn-sm"><i class="fa fa-upload"></i></a>
                                            @else
                                                <button style="color:white; cursor:pointer;" disabled class="btn btn-primary btn-sm"><i class="fa fa-upload"></i></button>
                                        @endif
                                    </td>
                                    <td style="text-align:center;">
                                        <a href=" {{ route('pengusul.laporan_akhir.luaran',[$usulan->id]) }} " class="btn btn-danger btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-plus"></i></a>
                                    </td>
                                    <td style="text-align:center;">
                                        @if ($usulan->file_akhir != null && $usulan->judul_luaran != "0" && $usulan->status == "0")
                                            <a onclick="konfirmasi({{ $usulan->id }})" class="btn btn-info btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-arrow-right"></i></a>
                                                @elseif($usulan->file_akhir == null && $usulan->judul_luaran == "0")
                                                    <a style="color:red"><i>luaran kegiatan & laporan akhir belum ditambahkan</i></a>
                                                    @elseif($usulan->file_akhir != null && $usulan->judul_luaran == "0")
                                                        <a style="color:red"><i>luaran kegiatan belum ditambahkan</i></a>
                                                        @elseif($usulan->file_akhir == null && $usulan->judul_luaran != "0")
                                                        <a style="color:red"><i>laporan akhir belum ditambahkan</i></a>
                                                            @elseif($usulan->file_akhir != null && $usulan->judul_luaran != "0" && $usulan->status != "0")
                                                            <button disabled class="btn btn-info btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-arrow-right"></i></button>
                                                @else
                                                    <button class="btn btn-info btn-sm" style="color:white; cursor:pointer;" disabled><i class="fa fa-arrow-right"></i></button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <!-- Modal Konfirmasi -->
                        <div class="modal modal-danger fade" id="modalkonfirmasi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form action=" {{ route('pengusul.laporan_akhir.konfirmasi') }} " method="POST">
                                            {{ csrf_field() }} {{ method_field('PATCH') }}
                                            <input type="hidden" name="id_usulan" id="id_usulan">
                                            <div class="modal-header modal-header-danger">
                                                <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Konfirmasi Laporan Akhir dan Luaran Kegiatan</p>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                            Apakah anda yakin sudah menambahkan laporan akhir dan luaran dengan benar? Jika iya silahkan konfirmasi !!
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-light btn-sm " style="color:white; background:white; background:transparent;" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;Batalkan</button>
                                                <button type="submit" class="btn btn-outline-light btn-sm " style="color:white; background:white; background:transparent;"><i class="fa fa-check-circle"></i>&nbsp;Konfirmasi</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </table>
                    <!-- Modal Upload-->
                    <div class="modal fade" id="modalupload" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Upload Laporan akhir</p>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action=" {{ route('pengusul.upload_laporan_akhir') }} " method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }} {{ method_field('POST') }}
                                    <input type="hidden" name="id_usulan" id="id_usulan_upload">
                                    <div class="modal-body">
                                        <div class="alert alert-primary alert-block" id="berhasil">
                                            <button type="button" class="close" data-dismiss="alert">×</button>
                                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Silahkan Upload Laporan akhir Usulan Penelitian Anda !!
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="exampleInputEmail1">File Laporan Akhir : <a style="color:red; font-style:italic; font-size:12px;">Harap masukan file pdf</a></label>
                                            <input type="file" name="laporan_akhir" id="laporan_akhir" accept="application/pdf" class="form-control" style="padding-bottom:30px;" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;Batalkan</button>
                                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-check-circle"></i>&nbsp;Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
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
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#table').DataTable({
                responsive : true,
            });
        } );

        function uploadLaporan(id){
            $('#modalupload').modal('show');
            $('#id_usulan_upload').val(id);
        }

        function selengkapnya(id){
            $.ajax({
                url: "{{ url('pengusul/upload_laporan_akhir') }}"+'/'+ id + "/detail_judul",
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

        function konfirmasi(id){
            $('#modalkonfirmasi').modal('show');
            $('#id_usulan').val(id);
        }
    </script>
@endpush
