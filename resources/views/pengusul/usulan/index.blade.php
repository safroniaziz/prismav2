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
                                <th>Ketua Peneliti</th>
                                <th>Anggota Kelompok</th>
                                <th>Tambah Anggota</th>
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
                                    <td> {{ $usulan->nm_ketua_peneliti }} </td>
                                    <td>
                                        @if ($usulan->anggota_kelompok == null)
                                            <label for="" class="badge badge-danger"><i class="fa fa-close" style="padding:5px;"></i>&nbsp; Belum ditambahkan</label>
                                        @endif
                                    </td>
                                    <td>
                                        <a onclick="tambahAnggota({{ $usulan->id }})" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-user-plus"></i></a>
                                    </td>
                                    <td> Rp. {{ number_format($usulan->biaya_diusulkan, 2) }} </td>
                                    <td>
                                        <a onclick="ubahUsulan({{ $usulan->id }})" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-edit"></i></a>
                                        <a onclick="hapusUsulan({{ $usulan->id }})" class="btn btn-danger btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-trash"></i></a>
                                    </td>
                                    <!-- Modal Ubah -->
                                    <div class="modal fade" id="modalubah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Ubah Data Usulan</p>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action=" {{ route('pengusul.usulan.update') }} " method="POST" enctype="multipart/form-data">
                                                {{ csrf_field() }} {{ method_field('PATCH') }}
                                                            <div class="modal-body">
                                                                <input type="hidden" name="id" id="id">
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputEmail1">Judul Penelitian</label>
                                                                    <textarea name="judul_penelitian" id="judul_penelitian" cols="30" rows="3" class="form-control" required></textarea>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="exampleInputEmail1">Bidang Penelitian</label></label>
                                                                    <select name="bidang_id" class="form-control" id="bidang_id" style="font-size:13px;" required>
                                                                        <option value="" disabled selected>-- pilih bidang penelitian --</option>
                                                                        @foreach ($bidangs as $bidang)
                                                                            <option value="{{ $bidang->id }}"> {{ $bidang->nm_bidang }} </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="exampleInputEmail1">Pilih Skim :</label>
                                                                    <select name="skim_id" class="form-control" id="skim_id" style="font-size:13px;" required>
                                                                        <option value="" disabled selected>-- pilih skim --</option>
                                                                        @foreach ($skims as $skim)
                                                                            <option value="{{ $skim->id }}"> {{ $skim->nm_skim }} </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="exampleInputEmail1">Ketua Peneliti : </label>
                                                                    <input type="text" name="ketua_peneliti" id="ketua_peneliti" value="{{ $ketua->nm_lengkap }}" disabled class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="exampleInputEmail1">Biaya Yang Diusulkan :</label>
                                                                    <input type="number" name="biaya_diusulkan" id="biaya_diusulkan" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputEmail1">File Peta Jalan : <a style="color:red; font-style:italic; font-size:12px;">Harap masukan file pdf</a></label>
                                                                    <input type="file" name="peta_jalan" id="peta_jalan" accept="application/pdf" class="form-control" style="padding-bottom:30px;" required>
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputEmail1">Abstrak</label>
                                                                    <textarea name="abstrak_edit" id="abstrak_edit" cols="30" rows="10"></textarea>
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputEmail1">Kata Kunci</label>
                                                                    <input id="tags_2" type="text" name="kata_kunci[]" id="kata_kunci" class="tags form-control" />
                                                                    <div id="suggestions-container" style="position: relative; float: left; width: 250px;"></div>
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
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Tambah Usulan Baru</p>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action=" {{ route('pengusul.usulan.add') }} " method="POST" enctype="multipart/form-data">
                                            {{ csrf_field() }} {{ method_field('POST') }}
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputEmail1">Judul Penelitian</label>
                                                            <textarea name="judul_penelitian" cols="30" rows="3" class="form-control" required></textarea>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputEmail1">Bidang Penelitian</label></label>
                                                            <select name="bidang_id" class="form-control" style="font-size:13px;" required>
                                                                <option value="" disabled selected>-- pilih bidang penelitian --</option>
                                                                @foreach ($bidangs as $bidang)
                                                                    <option value=" {{ $bidang->id }}"> {{ $bidang->nm_bidang }} </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputEmail1">Pilih Skim :</label>
                                                            <select name="skim_id" class="form-control" style="font-size:13px;" required>
                                                                <option value="" disabled selected>-- pilih skim --</option>
                                                                @foreach ($skims as $skim)
                                                                    <option value=" {{ $skim->id }}"> {{ $skim->nm_skim }} </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputEmail1">Ketua Peneliti : </label>
                                                            <input type="text" name="ketua_peneliti" value="{{ $ketua->nm_lengkap }}" disabled class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputEmail1">Biaya Yang Diusulkan :</label>
                                                            <input type="number" name="biaya_diusulkan" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputEmail1">File Peta Jalan : <a style="color:red; font-style:italic; font-size:12px;">Harap masukan file pdf</a></label>
                                                            <input type="file" name="peta_jalan" id="peta_jalan1" accept="application/pdf" class="form-control" style="padding-bottom:30px;" required>
                                                            <div id="peta-name"></div>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputEmail1">Abstrak</label>
                                                            <textarea name="abstrak" cols="30" rows="10"></textarea>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputEmail1">Kata Kunci</label>
                                                            <input id="tags_1" type="text" name="kata_kunci[]" class="tags form-control" />
                                                            <div id="suggestions-container" style="position: relative; float: left; width: 250px;"></div>
                                                        </div>
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
                              Apakah anda yakin akan menghapus data usulan ?
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
                </div>
                <!-- Modal Anggota -->
                <div class="modal fade" id="modalanggota" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Tambah Usulan Baru</p>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action=" {{ route('pengusul.usulan.anggota_post') }} " method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }} {{ method_field('POST') }}
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="exampleInputEmail1">Judul Penelitian</label>
                                                <textarea name="judul_penelitian" cols="30" rows="3" class="form-control" required></textarea>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="exampleInputEmail1">Kata Kunci</label>
                                                <input id="tags_1" type="text" name="kata_kunci[]" class="tags form-control" />
                                                <div id="suggestions-container" style="position: relative; float: left; width: 250px;"></div>
                                            </div>
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
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
    <script>
            CKEDITOR.replace( 'abstrak_edit', {height:150});
            CKEDITOR.replace( 'abstrak', {height:150});

            function myFunction() {
                var x = document.getElementById("peta_jalan1").value;
                document.getElementById("peta-name").innerHTML = x;
            }
    </script>
    <script>
        $(document).ready(function() {
            $('#table').DataTable({
                responsive: true,
            });
        } );

        function ubahUsulan(id){
            $.ajax({
                url: "{{ url('pengusul/manajemen_usulan') }}"+'/'+ id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    $('#modalubah').modal('show');
                    $('#id').val(data.id);
                    $('#judul_penelitian').val(data.judul_penelitian);
                    $('#bidang_id').val(data.bidang_id);
                    $('#skim_id').val(data.skim_id);
                    $('#biaya_diusulkan').val(data.biaya_diusulkan);
                    CKEDITOR.instances['abstrak_edit'].setData(data.abstrak);
                },
                error:function(){
                    alert("Nothing Data");
                }
            });
        }

        function hapusUsulan(id){
            $('#modalhapus').modal('show');
            $('#id_hapus').val(id);
        }

        function tambahAnggota(id){
            $('#modalanggota').modal('show');
        }
    </script>
@endpush
