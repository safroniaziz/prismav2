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
                    <div class="alert alert-success alert-block" style="display:none;" id="berhasil">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <i class="fa fa-success-circle"></i><strong>Berhasil :</strong> Status admin telah diubah !!
                    </div>
                </div>
                <div class="col-md-12" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal">
                        <i class="fa fa-plus" style="font-size:12px;"></i>&nbsp;Tambah Usulan
                    </button>
                </div>
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul Penelitian</th>
                                <th>Bidang Penelitian</th>
                                <th>Perguruan Tinggi</th>
                                <th>Program Study</th>
                                <th>Ketua Peneliti</th>
                                <th>Abstrak</th>
                                <th>Kata Kunci</th>
                                <th>Peta Jalan</th>
                                <th>Biaya Diusulkan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no=1;
                            @endphp
                            @foreach ($usulans as $usulan)
                                <tr>
                                    <td> {{ $no++ }} </td>
                                    <td> {{ $usulan->judul_penelitian }} </td>
                                    <td> {{ $usulan->bidang_penelitian }} </td>
                                    <td> {{ $usulan->perguruan_tinggi }} </td>
                                    <td> {{ $usulan->program_study }} </td>
                                    <td> {{ $usulan->nm_ketua_peneliti }} </td>
                                    <td> {{ $usulan->abstrak }} </td>
                                    <td> {{ $usulan->kata_kunci }} </td>
                                    <td> {{ $usulan->peta_jalan }} </td>
                                    <td> {{ $usulan->biaya_diusulkan }} </td>
                                    <td>
                                        <a onclick="ubahOperator({{ $usulan->id }})" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-edit"></i></a>
                                        <a onclick="hapusOperator({{ $usulan->id }})" class="btn btn-danger btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-trash"></i></a>
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
                                                  Apakah anda yakin akan menghapus data pengusul ?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-light btn-sm " style="color:white;" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;Close</button>
                                                    <form method="POST" action="{{ route('pengusul.usulan.delete') }}">
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
                                            <form action=" {{ route('pengusul.usulan.update') }} " method="POST" enctype="multipart/form-data">
                                                {{ csrf_field() }} {{ method_field('PATCH') }}
                                                            <div class="modal-body">
                                                                <input type="hidden" name="id" id="id">
                                                                <div class="form-group">
                                                                    <label for="exampleInputEmail1">Nama Operator</label>
                                                                    <input type="text" name="nm_user" id="nm_user" class="form-control" placeholder="masukan nama pengusul" required>
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
                                        <form action=" {{ route('pengusul.usulan.add') }} " method="POST">
                                            {{ csrf_field() }} {{ method_field('POST') }}
                                                <div class="modal-body">

                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Judul Penelitian</label>
                                                        <textarea name="judul_penelitian" cols="30" rows="3" class="form-control" required></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Bidang Penelitian</label> <a id="username-gagal" style="color:red;display:none;"><i>username sudah digunakan</i></a></label>
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

        function ubahOperator(id){
            $.ajax({
                url: "{{ url('pengusul/manajemen_operator') }}"+'/'+ id + "/edit",
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
