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
                            
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Berhasil: </strong> {{ $message }}
                        </div>
                        @elseif(count($usulans)<1)
                        <div class="alert alert-danger alert-block" id="keterangan">
                            
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Belum Ada Usulan Penelitian Yang Disetujui!!
                        </div>
                        @else
                        <div class="alert alert-danger alert-block" id="keterangan">
                            
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah semua usulan yang didanai, usulan yang sudah diverifikasi tidak bisa dibatalkan !!
                        </div>
                    @endif
                </div>
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="table" style="width:100%; ">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul Kegiatan</th>
                                <th style="text-align:center;">Penilaian Reviewer</th>
                                <th style="text-align:center;">Laporan Perbaikan</th>
                                <th style="text-align:center;">Anggaran Kegiatan</th>
                                <th style="text-align:center;">Laporan Kemajuan</th>
                                <th style="text-align:center;">Ubah Data</th>
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
                                    <td>
                                        <a href="{{ route('pengusul.verifikasi.detail_penilaian',[$usulan->id, $usulan->skim_id,\Illuminate\Support\Str::slug($usulan->judul_kegiatan)]) }}"  class="btn btn-primary btn-sm" style="color:white; cursor:pointer;">Lihat detail</a>
                                    </td>
                                    <td style="text-align:center;">
                                        @if ($usulan->file_perbaikan == null)
                                            <a style="color:red;"><i>belum diupload</i></a>
                                            <hr>
                                            <a onclick="uploadPerbaikan({{ $usulan->id }})" style="color:white; cursor:pointer;" class="btn btn-primary btn-sm"><i class="fa fa-upload"></i></a>
                                            @else
                                                <label class="badge badge-success" style="padding:10px;"><i class="fa fa-check-circle"></i></label>
                                                <hr>
                                                <button style="color:white; cursor:pointer;" disabled class="btn btn-primary btn-sm"><i class="fa fa-upload"></i></button>
                                        @endif
                                    </td>
                                    <td style="width:25%; text-align:center;">
                                        <a style="color:#5A738E; cursor:pointer;"> Rp. {{ number_format($usulan->biaya_diusulkan, 2) }} </a>
                                        
                                    </td>
                                    <td style="text-align:center;">
                                        @if ($usulan->file_kemajuan == null)
                                            <a style="color:red;"><i>belum diupload</i></a>
                                            <hr>
                                                @if ($usulan->file_perbaikan != null)
                                                    <a onclick="uploadLaporan({{ $usulan->id }})" style="color:white; cursor:pointer;" class="btn btn-primary btn-sm"><i class="fa fa-upload"></i></a>
                                                    @else
                                                    <a style="color:red;"><i>upload laporan perbaikan terlebih dahulu</i></a>
                                                @endif
                                            @else
                                                <label class="badge badge-success" style="padding:10px;"><i class="fa fa-check-circle"></i></label>
                                                <hr>
                                                <button style="color:white; cursor:pointer;" disabled class="btn btn-primary btn-sm"><i class="fa fa-upload"></i></button>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('pengusul.usulan.edit',[\Illuminate\Support\Str::slug(Session::get('nm_dosen')),$usulan->id]) }}" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-edit"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Modal -->
                    <div class="modal fade" id="modalupload" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Upload Laporan kemajuan</p>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action=" {{ route('pengusul.upload_laporan_kemajuan') }} " method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }} {{ method_field('POST') }}
                                    <div class="modal-body">
                                        <div class="alert alert-primary alert-block" id="berhasil">
                                            
                                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Silahkan Upload Laporan Kemajuan Usulan Penelitian Anda !!
                                        </div>
                                        <input type="hidden" name="id_usulan" id="id_usulan">
                                        <div class="form-group col-md-12">
                                            <label for="exampleInputEmail1">File Laporan Kemajuan : <a style="color:red; font-style:italic; font-size:12px;">Harap masukan file pdf</a></label>
                                            <input type="file" name="laporan_kemajuan" id="laporan_kemajuan" accept="application/pdf" class="form-control" style="padding-bottom:30px;" required>
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
            <!-- Modal -->
            <div class="modal fade" id="modalperbaikan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Upload Laporan kemajuan</p>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action=" {{ route('pengusul.upload_laporan_perbaikan') }} " method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }} {{ method_field('PATCH') }}`
                            <div class="modal-body">
                                <div class="alert alert-primary alert-block" id="berhasil">
                                    
                                    <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Silahkan Upload Laporan Perbaikan Usulan Penelitian Anda !!
                                </div>
                                <input type="hidden" name="id_usulan" id="id_perbaikan">
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">File Laporan Perbaikan : <a style="color:red; font-style:italic; font-size:12px;">Harap masukan file pdf</a></label>
                                    <input type="file" name="laporan_perbaikan" id="laporan_perbaikan" accept="application/pdf" class="form-control" style="padding-bottom:30px;" required>
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
        <!-- Modal -->
        <div class="modal fade" id="modalubahbiaya" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Upload Laporan kemajuan</p>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action=" {{ route('pengusul.upload_laporan_kemajuan.ubah_biaya_post') }} " method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }} {{ method_field('PATCH') }}`
                        <div class="modal-body">
                            <div class="alert alert-primary alert-block" id="berhasil">
                                
                                <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Silahkan ubah anggaran biaya anda dengan benar !!
                            </div>
                            <input type="hidden" name="id_usulan" id="id_perbaikan">
                            <div class="form-group col-md-12">
                                <input type="hidden" name="id" id="id_usulan_biaya">
                                <label for="exampleInputEmail1">Anggaran Biaya :</label>
                                <input type="text" name="biaya_diusulkan" id="biaya_diusulkan"  class="form-control" required>
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
            $('#id_usulan').val(id);
        }

        function uploadPerbaikan(id){
            $('#modalperbaikan').modal('show');
            $('#id_perbaikan').val(id);
        }

        function selengkapnya(id){
            $.ajax({
                url: "{{ url('pengusul/upload_laporan_kemajuan') }}"+'/'+ id + "/detail_judul",
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

        function ubahAnggaran(id){
            $.ajax({
                url: "{{ url('pengusul/upload_laporan_kemajuan') }}"+'/'+ id + "/ubah_biaya",
                type: "GET",
                dataType: "JSON",
                success: function(data){
                $('#modalubahbiaya').modal('show');
                    $('#id_usulan_biaya').val(data.id);
                    $('#biaya_diusulkan').val(data.biaya_diusulkan);
                },
                error:function(){
                    alert("Nothing Data");
                }
            });
        }
    </script>
@endpush
