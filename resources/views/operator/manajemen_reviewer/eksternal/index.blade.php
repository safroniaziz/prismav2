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
@section('content')
    <section class="panel" style="margin-bottom:20px;">
        <header class="panel-heading" style="color: #ffffff;background-color: #074071;border-color: #fff000;border-image: none;border-style: solid solid none;border-width: 4px 0px 0;border-radius: 0;font-size: 14px;font-weight: 700;padding: 15px;">
            <i class="fa fa-user"></i>&nbsp;Manajemen Operator
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
                        <div class="alert alert-primary alert-block text-center" id="keterangan">
                            <strong><i class="fa fa-info-circle"></i>&nbsp;PERHATIAN: </strong>
                            <br>
                            Berikut adalah semua data reviewer yang tersedia, silahkan tambahkan reviewer baru jika diperlukan !!
                        </div>
                    @endif
                </div>
                <div class="col-md-12" style="margin-bottom:5px;">
                    <a href="{{ route('operator.reviewer_eksternal.add') }}" class="btn btn-primary btn-sm"><i class="fa fa-user-plus"></i>&nbsp; Tambah Reviewer</a>
                </div>
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nip Reviewer</th>
                                <th>Nama Reviewer</th>
                                <th>Nidn</th>
                                <th>Jabatan Fungsional</th>
                                <th>Jenis Kelamin</th>
                                <th>Universitas</th>
                                <th>Jenis Reviewer</th>
                                <th>Ubah Password</th>
                                <th>Status</th>
                                <th>Ubah Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no=1;
                            @endphp
                            @foreach ($reviewers as $reviewer)
                                <tr>
                                    <td> {{ $no++ }} </td>
                                    <td> {{ $reviewer->nip }} </td>
                                    <td> {{ $reviewer->nama }} </td>
                                    <td> {{ $reviewer->nidn }} </td>
                                    <td> {{ $reviewer->jabatan_fungsional }} </td>
                                    <td>
                                        @if ($reviewer->jenis_kelamin == "1")
                                            <label class="badge badge-primary"><i class="fa fa-female"></i>&nbsp; Laki-Laki</label>
                                            @else
                                            <label class="badge badge-info"><i class="fa fa-male"></i>&nbsp; Perempuan</label>
                                        @endif
                                    </td>
                                    <td> {{ $reviewer->universitas }} </td>
                                    <td> {{ $reviewer->jenis_reviewer }} </td>
                                    <td>
                                        <a onclick="ubahPassword({{ $reviewer->id }})" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-key"></i></a>
                                    </td>
                                    <td>
                                        @if ($reviewer->status == "1")
                                            <label class="badge badge-success"><i class="fa fa-check-circle"></i>&nbsp; Aktif</label>
                                            @else
                                            <label class="badge badge-danger"><i class="fa fa-close"></i>&nbsp; Tidak Aktif</label>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($reviewer->status == "1")
                                            <form action="{{ route('operator.reviewer_eksternal.nonaktifkan_status',[$reviewer->id]) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-danger btn sm"><i class="fa fa-thumbs-down"></i></button>
                                            </form>
                                            @else
                                            <form action="{{ route('operator.reviewer_eksternal.aktifkan_status',[$reviewer->id]) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-primary btn sm"><i class="fa fa-thumbs-up"></i></button>
                                            </form>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('operator.reviewer_eksternal.edit',[$reviewer->id]) }}" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-edit"></i></a>
                                        <a onclick="hapusReviewer({{ $reviewer->id }})" class="btn btn-danger btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-trash"></i></a>
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
                                                  Apakah anda yakin akan menghapus data reviewer ?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-light btn-sm " style="color:white;" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;Close</button>
                                                    <form method="POST" action="{{ route('operator.reviewer_eksternal.delete') }}">
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
                            <form action=" {{ route('operator.reviewer_eksternal.update_password') }} " method="POST" enctype="multipart/form-data">
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

        function ubahPassword(id){
            $('#modalubahpassword').modal('show');
            $('#id_password').val(id);
        }

        $(document).ready(function(){
            $("#password2, #ulangi_password2").keyup(function(){
                var password2 = $("#password2").val();
                var ulangi = $("#ulangi_password2").val();
                if($("#password2").val() == $("#ulangi_password2").val()){
                    $('#konfirmasi2').show(200);
                    $('#konfirmasi-gagal2').hide(200);
                    $('#btn-submit2').attr("disabled",false);
                }
                else{
                    $('#konfirmasi2').hide(200);
                    $('#konfirmasi-gagal2').show(200);
                    $('#btn-submit2').attr("disabled",true);
                }
            });
        });

        function hapusReviewer(id){
            $('#modalhapus').modal('show');
            $('#id_hapus').val(id);
        }

    </script>
@endpush
