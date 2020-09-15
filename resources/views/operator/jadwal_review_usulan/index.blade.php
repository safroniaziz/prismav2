@extends('layouts.layout')
@section('title', 'Manajemen Jadwal Review Usulan')
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
@section('content')
    <section class="panel" style="margin-bottom:20px;">
        <header class="panel-heading" style="color: #ffffff;background-color: #074071;border-color: #fff000;border-image: none;border-style: solid solid none;border-width: 4px 0px 0;border-radius: 0;font-size: 14px;font-weight: 700;padding: 15px;">
            <i class="fa fa-user"></i>&nbsp;Manajemen Jadwal Review Usulan
            <span class="tools pull-right" style="margin-top:-5px;">
                <a class="fa fa-chevron-down" href="javascript:;" style="float: left;margin-left: 3px;padding: 10px;text-decoration: none;"></a>
                <a class="fa fa-times" href="javascript:;" style="float: left;margin-left: 3px;padding: 10px;text-decoration: none;"></a>
            </span>
        </header>
        <div class="panel-body" style="border-top: 1px solid #eee; padding:15px; background:white;">
            <div class="row" style="margin-right:-15px; margin-left:-15px;">
                <div class="col-md-12">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block" id="berhasil">
                            
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Berhasil: </strong> {{ $message }}
                        </div>
                        @else
                        <div class="alert alert-success alert-block" id="keterangan">
                            
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah semua data Jadwal Review Usulan yang tersedia, silahkan tambahkan Jadwal Review Usulan baru jika diperlukan !!
                        </div>
                    @endif
                </div>
                <div class="col-md-12" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal">
                        <i class="fa fa-plus" style="font-size:12px;"></i>&nbsp;Tambah Jadwal Review Usulan
                    </button>
                </div>
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="table" style="width:100%;">
                        <thead>
                            <tr>
                                <th style="text-align:center;">No</th>
                                <th style="text-align:center;">Tanggal Awal</th>
                                <th style="text-align:center;">Tanggal Akhir</th>
                                <th style="text-align:center;">Status</th>
                                <th style="text-align:center;">Ubah Status</th>
                                <th style="text-align:center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no=1;
                            @endphp
                            @foreach ($jadwals as $jadwal)
                                <tr>
                                    <td style="text-align:center;"> {{ $no++ }} </td>
                                    <td style="text-align:center;"> {{ $jadwal->tanggal_awal }} </td>
                                    <td style="text-align:center;"> {{ $jadwal->tanggal_akhir }} </td>
                                    <td style="text-align:center;">
                                        @if ($jadwal->status == "1")
                                            <label for="" class="badge badge-primary"><i class="fa fa-check-circle"></i>&nbsp; Aktif</label>
                                            @else
                                            <label for="" class="badge badge-danger"><i class="fa fa-close"></i>&nbsp; Tidak Aktif</label>
                                        @endif
                                    </td>
                                    <td style="text-align:center;">
                                        @if ($jadwal->status == "1")
                                            <form action="{{ route('operator.jadwal_review_usulan.nonaktifkan_status', [$jadwal->id]) }}" method="POST">
                                                {{ csrf_field() }} {{ method_field('PATCH') }}
                                                <button type="submit" class="btn btn-danger btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-thumbs-down"></i></button>
                                            </form>
                                            @else
                                            <form action="{{ route('operator.jadwal_review_usulan.aktifkan_status', [$jadwal->id]) }}" method="POST">
                                                {{ csrf_field() }} {{ method_field('PATCH') }}
                                                <button type="submit" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-thumbs-up"></i></button>
                                            </form>
                                        @endif
                                    </td>
                                    </td>
                                    <td style="text-align:center;">
                                        <a onclick="hapusJadwal({{ $jadwal->id }})" class="btn btn-danger btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-trash"></i></a>
                                        <div class="modal modal-danger fade" id="modalhapus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                              <div class="modal-content">
                                                <div class="modal-header modal-header-danger">
                                                    <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Konfirmasi Hapus Data Operator</p>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                  Apakah anda yakin akan menghapus data operator ?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-light btn-sm " style="color:white;" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;Close</button>
                                                    <form method="POST" action="{{ route('operator.jadwal_review_usulan.delete') }}">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <input type="hidden" name="id" id="id_hapus">
                                                        <button type="submit" class="btn btn-outline-light btn-sm" style="color:white;"><i class="fa fa-check-circle"></i>&nbsp; Ya, Hapus Data !</button>
                                                    </form>
                                                </div>
                                              </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Tambah Operator Baru</p>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action=" {{ route('operator.jadwal_review_usulan.add') }} " method="POST">
                                            {{ csrf_field() }} {{ method_field('POST') }}
                                                <div class="modal-body">

                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Tanggal Awal</label>
                                                        <input type="date" name="tanggal_awal" class="form-control" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Tanggal Akhir</label>
                                                        <input type="date" name="tanggal_akhir" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;Batalkan</button>
                                                    <button type="submit" class="btn btn-primary" id="btn-submit"><i class="fa fa-save"></i>&nbsp;Simpan</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </tbody>
                    </table>
                    <!-- Modal Ubah Password-->
                    <div class="modal fade" id="modalubahpassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Ubah Password Operator</p>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action=" {{ route('operator.operator.update_password') }} " method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }} {{ method_field('PATCH') }}
                                            <div class="modal-body">
                                                <input type="hidden" name="id" id="id_password">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Password Login</label>
                                                    <input type="password" name="password2" id="password2" class="form-control" placeholder="********" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Ulangi Password Login</label>
                                                    <input type="password" name="ulangi_password" id="ulangi_password2"  class="form-control" placeholder="********" required>
                                                </div>
                                                <div class="alert alert-success" id="konfirmasi2" style="display:none;">
                                                    
                                                    <i class="fa fa-check-circle"></i>&nbsp;<strong style="font-style:italic;">Password Sama !</strong>
                                                </div>
                                                <div class="alert alert-danger" id="konfirmasi-gagal2" style="display:none;">
                                                    
                                                    <i class="fa fa-close"></i>&nbsp;<strong style="font-style:italic;">Password Tidak Sama !</strong>
                                                </div>

                                            </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;Batalkan</button>
                                        <button type="submit" class="btn btn-primary" id="btn-submit2" disabled><i class="fa fa-check-circle"></i>&nbsp;Simpan Perubahan Password</button>
                                    </div>
                                </div>
                            </form>
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
                responsive: true,
            });
        } );

        $(document).ready(function(){
            $("#password, #ulangi").keyup(function(){
                var password = $("#password").val();
                var ulangi = $("#ulangi").val();
                if($("#password").val() == $("#ulangi").val()){
                    $('#konfirmasi').show(200);
                    $('#konfirmasi-gagal').hide(200);
                    $('#btn-submit').attr("disabled",false);
                }
                else{
                    $('#konfirmasi').hide(200);
                    $('#konfirmasi-gagal').show(200);
                    $('#btn-submit').attr("disabled",true);
                }
            });
        });

        function hapusJadwal(id){
            $('#modalhapus').modal('show');
            $('#id_hapus').val(id);
        }

    </script>
@endpush
