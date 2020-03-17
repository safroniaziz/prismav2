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
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Belum Ada Usulan Penelitian Yang Disetujui!!
                        </div>
                        @else
                        <div class="alert alert-success alert-block" id="keterangan">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah semua usulan yang didanai, usulan yang sudah diverifikasi tidak bisa dibatalkan !!
                        </div>
                    @endif
                </div>
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="table" style="width:100%; ">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul Penelitian</th>
                                <th>Laporan Kemajuan</th>
                                <th>Upload Laporan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no=1;
                            @endphp
                            @foreach ($usulans as $usulan)
                                <tr>
                                    <td> {{ $no++ }} </td>
                                    <td> {{ $usulan->judul_kegiatan }} </td>
                                    <td style="text-align:center;">
                                        @if ($usulan->file_kemajuan == null)
                                            <a style="color:red;"><i>belum diupload</i></a>
                                            @else
                                                <label class="badge badge-success" style="padding:10px;"><i class="fa fa-check-circle"></i></label>
                                        @endif
                                    </td>
                                    <td style="text-align:center;">
                                        @if ($usulan->file_kemajuan == null)
                                            <a onclick="uploadLaporan({{ $usulan->id }})" style="color:white; cursor:pointer;" class="btn btn-primary btn-sm"><i class="fa fa-upload"></i></a>
                                            @else
                                                <button style="color:white; cursor:pointer;" disabled class="btn btn-primary btn-sm"><i class="fa fa-upload"></i></button>
                                        @endif
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
                                            <button type="button" class="close" data-dismiss="alert">×</button>
                                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Silahkan Upload Laporan Kemajuan Usulan Penelitian Anda !!
                                        </div>
                                        <input type="hidden" name="id_usulan" id="id_usulan">
                                        <div class="form-group col-md-12">
                                            <label for="exampleInputEmail1">File Peta Jalan : <a style="color:red; font-style:italic; font-size:12px;">Harap masukan file pdf</a></label>
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
    </script>
@endpush
