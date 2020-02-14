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
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Berhasil: </strong> {{ $message }}
                        </div>
                        @else
                        <div class="alert alert-success alert-block" id="keterangan">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah semua data operator yang tersedia, silahkan tambahkan operator baru jika diperlukan !!
                        </div>
                    @endif
                </div>
                <div class="col-md-12" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal">
                        <i class="fa fa-plus" style="font-size:12px;"></i>&nbsp;Tambah Operator
                    </button>
                </div>
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Operator</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Password</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no=1;
                            @endphp
                            @foreach ($operators as $operator)
                                <tr>
                                    <td> {{ $no++ }} </td>
                                    <td> {{ $operator->nm_user }} </td>
                                    <td> {{ $operator->username }} </td>
                                    <td> {{ $operator->email }} </td>
                                    <td>
                                        <a onclick="ubahPassword({{ $operator->id }})" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-key"></i></a>
                                    </td>
                                    <td>
                                        <a onclick="ubahOperator({{ $operator->id }})" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-edit"></i></a>
                                        <a onclick="hapusOperator({{ $operator->id }})" class="btn btn-danger btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-trash"></i></a>
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
                                                    <form method="POST" action="{{ route('operator.operator.delete') }}">
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
                                    <!-- Modal Ubah -->
                                    <div class="modal fade" id="modalubah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Ubah Data Operator</p>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action=" {{ route('operator.operator.update') }} " method="POST" enctype="multipart/form-data">
                                                {{ csrf_field() }} {{ method_field('PATCH') }}
                                                            <div class="modal-body">
                                                                <input type="hidden" name="id" id="id">
                                                                <div class="form-group">
                                                                    <label for="exampleInputEmail1">Nama Operator</label>
                                                                    <input type="text" name="nm_user" id="nm_user" class="form-control" placeholder="masukan nama operator" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="exampleInputEmail1">Username (unique) <a id="username-gagal" style="color:red;display:none;"><i>username sudah digunakan</i></a></label>
                                                                    <input type="text" name="username" id="username" class="form-control" placeholder="masukan username" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="exampleInputEmail1">E-mail (unique) : <a id="email-gagal" style="color:red;display:none;"><i>email sudah digunakan</i></a></label>
                                                                    <input type="email" name="email" id="email" class="form-control" placeholder="example@mail.com" required>
                                                                </div>
                                                            </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;Batalkan</button>
                                                        <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i>&nbsp;Simpan Perubahan</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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
                                        <form action=" {{ route('operator.operator.add') }} " method="POST">
                                            {{ csrf_field() }} {{ method_field('POST') }}
                                                <div class="modal-body">

                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Nama Operator</label>
                                                        <input type="text" name="nm_user" class="form-control" placeholder="masukan nama admin" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Username (unique) <a id="username-gagal" style="color:red;display:none;"><i>username sudah digunakan</i></a></label>
                                                        <input type="text" name="username" id="username" class="form-control" placeholder="masukan username" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">E-mail (unique) : <a id="email-gagal" style="color:red;display:none;"><i>email sudah digunakan</i></a></label>
                                                        <input type="email" name="email" id="email" class="form-control" placeholder="example@mail.com" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Password</label>
                                                        <input type="password" name="password" id="password" class="form-control" placeholder="masukan password" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Ulangi Password</label>
                                                        <input type="password" name="ulangi" id="ulangi" class="form-control" placeholder="ulangi password" required>
                                                    </div>

                                                    <div class="alert alert-success" id="konfirmasi" style="display:none;">
                                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                                        <i class="fa fa-check-circle"></i>&nbsp;<strong style="font-style:italic;">Password Sama !</strong>
                                                    </div>
                                                    <div class="alert alert-danger" id="konfirmasi-gagal" style="display:none;">
                                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                                        <i class="fa fa-close"></i>&nbsp;<strong style="font-style:italic;">Password Tidak Sama !</strong>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;Batalkan</button>
                                                    <button type="submit" class="btn btn-primary" id="btn-submit" disabled><i class="fa fa-save"></i>&nbsp;Simpan</button>
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
                                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                                    <i class="fa fa-check-circle"></i>&nbsp;<strong style="font-style:italic;">Password Sama !</strong>
                                                </div>
                                                <div class="alert alert-danger" id="konfirmasi-gagal2" style="display:none;">
                                                    <button type="button" class="close" data-dismiss="alert">×</button>
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

        function ubahOperator(id){
            $.ajax({
                url: "{{ url('operator/manajemen_operator') }}"+'/'+ id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    $('#modalubah').modal('show');
                    $('#id').val(data.id);
                    $('#nm_user').val(data.nm_user);
                    $('#username').val(data.username);
                    $('#email').val(data.email);
                },
                error:function(){
                    alert("Nothing Data");
                }
            });
        }

        function hapusOperator(id){
            $('#modalhapus').modal('show');
            $('#id_hapus').val(id);
        }

    </script>
@endpush
