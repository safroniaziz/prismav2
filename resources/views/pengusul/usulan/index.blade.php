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
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger alert-block" id="berhasil">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Gagal: </strong> {{ $message }}
                        </div>
                    @endif
                    <div class="alert alert-success alert-block" style="display:none;" id="usulan-berhasil">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <i class="fa fa-success-circle"></i><strong>Berhasil :</strong> Penelitian anda sudah diusulkan !!
                    </div>
                    <div class="alert alert-danger alert-block" style="display:none;" id="gagal">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <i class="fa fa-close"></i><strong>&nbsp;Gagal :</strong> Proses pengusulan gagal !!
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
                                <th>Detail Anggota</th>
                                <th>Biaya Diusulkan</th>
                                <th>Kelola Anggaran</th>
                                <th>Peta Jalan</th>
                                <th>Status Usulan</th>
                                <th>Usulkan</th>
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
                                    <td> {{ $usulan->ketua_peneliti_nama }} </td>
                                    <td>
                                        @if ($usulan->nm_anggota == null)
                                            <label class="badge badge-danger"><i class="fa fa-close" style="padding:5px;"></i>&nbsp;Belum ditambahkan</label>
                                            @else
                                            <label class="badge" style="font-size:12px;">&nbsp;{!! $usulan->nm_anggota !!}</label>
                                        @endif
                                    </td>
                                    <td>
                                        <a onclick="tambahAnggota({{ $usulan->id }})" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-user-plus"></i></a>
                                    </td>
                                    <td> Rp. {{ number_format($usulan->biaya_diusulkan, 2) }} </td>
                                    <td>
                                        <a href="{{ route('pengusul.usulan.detail_anggaran',[$usulan->id]) }}" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-gear"></i></a>
                                    </td>
                                    <td>
                                        @if ($usulan->peta_jalan == null)
                                            <label class="badge badge-danger"><i class="fa fa-close" style="padding:5px;"></i></label>
                                            @else
                                            <label class="badge badge-success"><i class="fa fa-check-circle" style="padding:5px;"></i></label>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($usulan->status_usulan == '0')
                                            <label class="badge badge-warning" style="color:white;"><i class="fa fa-clock-o" style="padding:5px;"></i>&nbsp;belum diusulkan</label>
                                            @elseif($usulan->status_usulan == "1")
                                            <label class="badge badge-info" style="color:white;"><i class="fa fa-clock-o" style="padding:5px;"></i>&nbsp;menunggu verifikasi</label>
                                            @elseif($usulan->status_usulan == "2")
                                            <label class="badge badge-success" style="color:white;"><i class="fa fa-clock-o" style="padding:5px;"></i>&nbsp;disetujui</label>
                                            @elseif($usulan->status_usulan == "3")
                                            <label class="badge badge-danger" style="color:white;"><i class="fa fa-close" style="padding:5px;"></i>&nbsp;ditolak</label>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($usulan->nm_anggota != null && $usulan->status_usulan != "1" && $usulan->peta_jalan != null)
                                            <a onclick="usulkan( {{ $usulan->id }} )" class="btn btn-primary btn-sm" style="color:white;font-size:13px;cursor:pointer"><i class="fa fa-arrow-right"></i></a>
                                            @elseif($usulan->nm_anggota !=null && $usulan->peta_jalan == null && $usulan->status_usulan != "1")
                                            <a style="color:red;"><i>harap lengkapi file peta jalan</i></a>
                                            @elseif($usulan->nm_anggota ==null && $usulan->peta_jalan != null && $usulan->status_usulan != "1")
                                            <a style="color:red;"><i>harap lengkapi anggota kelompok</i></a>
                                            @elseif($usulan->status_usulan == "1" && $usulan->nm_anggota != null && $usulan->peta_jalan != null)
                                            <button class="btn btn-primary btn-sm" disabled style="color:white;font-size:13px;cursor:pointer"><i class="fa fa-arrow-right"></i></button>
                                            @elseif($usulan->status_usulan == "1" && $usulan->nm_anggota != null && $usulan->peta_jalan != null)
                                            <button class="btn btn-primary btn-sm" disabled style="color:white;font-size:13px;cursor:pointer"><i class="fa fa-arrow-right"></i></button>
                                            @else
                                            <a style="color:red;"><i>harap lengkapi anggota kelompok dan peta jalan</i></a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($usulan->status_usulan ==1)
                                            <button class="btn btn-primary btn-sm" disabled style="color:white; cursor:pointer;"><i class="fa fa-edit"></i></button>
                                            <button class="btn btn-danger btn-sm" disabled style="color:white; cursor:pointer;"><i class="fa fa-trash"></i></button>
                                            @else
                                            <a onclick="ubahUsulan({{ $usulan->id }})" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-edit"></i></a>
                                            <a onclick="hapusUsulan({{ $usulan->id }})" class="btn btn-danger btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-trash"></i></a>
                                        @endif
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
                                                                    <input type="text" name="ketua_peneliti" id="ketua_peneliti" value="{{ Session::get('nm_dosen') }}" disabled class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="exampleInputEmail1">Biaya Yang Diusulkan :</label>
                                                                    <input type="number" name="biaya_diusulkan" id="biaya_diusulkan" class="form-control" required>
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputEmail1">File Peta Jalan : <a style="color:red; font-style:italic; font-size:12px;">Harap masukan file pdf</a></label>
                                                                    <input type="file" name="peta_jalan" id="peta_jalan" accept="application/pdf" class="form-control" style="padding-bottom:30px;" required>
                                                                    <div style="color:red;" style="margin-bottom:10px;">
                                                                        File Lama :<a id="peta_name"></a>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputEmail1">Abstrak</label>
                                                                    <textarea name="abstrak_edit" id="abstrak_edit" cols="30" rows="10" required></textarea>
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputEmail1">Kata Kunci</label>
                                                                    <input id="tags_2" type="text" name="kata_kunci[]" id="kata_kunci" class="tags form-control" required />
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
                                                            <input type="text" name="ketua_peneliti" value="{{ Session::get('nm_dosen') }}" disabled class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputEmail1">Biaya Yang Diusulkan :</label>
                                                            <input type="number" name="biaya_diusulkan" class="form-control" required>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputEmail1">File Peta Jalan : <a style="color:red; font-style:italic; font-size:12px;">Harap masukan file pdf</a></label>
                                                            <input type="file" name="peta_jalan" id="peta_jalan1" accept="application/pdf" class="form-control" style="padding-bottom:30px;" required>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputEmail1">Abstrak</label>
                                                            <textarea name="abstrak" cols="30" rows="10" required></textarea>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputEmail1">Kata Kunci</label>
                                                            <input id="tags_1" type="text" name="kata_kunci[]" class="tags form-control" required />
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
                                <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Tambah Anggota Penelitian</p>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action=" {{ route('pengusul.usulan.anggota_post') }} " method="POST">
                                {{ csrf_field() }} {{ method_field('POST') }}
                                    <div class="modal-body">
                                        <input type="hidden" name="usulan_id" id="usulan_id">
                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <label for="exampleInputEmail1">Pilih Fakultas</label>
                                                <select name="fakultas_id" id="fakultas_id" class="form-control" required style="font-size:13px;">
                                                    <option value="" disabled selected>-- pilih fakultas --</option>
                                                    @foreach ($fakultas as $fakultas)
                                                        <option value=" {{ $fakultas->fakultas_kode }} "> {{ $fakultas->nm_fakultas }} </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="exampleInputEmail1">Pilih Program Studi</label>
                                                <select name="prodi_id" id="prodi_id" class="form-control" required style="font-size:13px;">
                                                    <option value="">-- pilih prodi --</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="exampleInputEmail1">Pilih Anggota</label>
                                                <select name="anggota_id" id="anggota_id" class="form-control" required style="font-size:13px;">
                                                    <option value="">-- pilih anggota --</option>
                                                </select>
                                            </div>
                                            <div class="col-md-12">
                                                 <button type="submit" style="font-size:13px;" class="btn btn-primary" disabled id="btn-submit-anggota"><i class="fa fa-save"></i>&nbsp;Simpan</button>
                                                 <button type="reset" style="font-size:13px;" class="btn btn-danger"><i class="fa fa-refresh"></i>&nbsp; Reset</button>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 table-responsive">
                                                <div class="alert alert-success alert-block" id="berhasil">
                                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                                    <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah kelompok penelitian dengan judul : <a style="font-weight:bold; font-size:12px; text-decoration:underline;" id="judul">, silahkan tambahkan anggota baru jika diperlukan</a>
                                                </div>
                                                <table class="table table-bordered table-striped" id="anggota" style="width:100%;">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Nama Aggota</th>
                                                            <th>Nip</th>
                                                            <th>Program Studi</th>
                                                            <th>Fakultas</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tbody">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" style="font-size:13px;" data-dismiss="modal"><i class="fa fa-arrow-left"></i>&nbsp;Kembali</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Usulan -->
        <div class="modal fade" id="modalusulan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Tambah Anggota Penelitian</p>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form action=" {{ route('pengusul.usulan.usulkan') }} " method="POST">
                        {{ csrf_field() }} {{ method_field('PATCH') }}
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger alert-block" id="usulan-danger" style="display:none;">
                                            <button type="button" class="close" data-dismiss="alert">×</button>
                                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> apakah anda yakin? rancangan anggaran anda belum lengkap, klik jika sudah tidak ada perubahan
                                        </div>
                                        <div class="alert alert-success alert-block" id="usulan-success" style="display:none;">
                                            <button type="button" class="close" data-dismiss="alert">×</button>
                                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> apakah anda yakin? klik teruskan jika sudah tidak ada perubahan !!
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="usulan_id_usulkan" id="usulan_id_usulkan">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" style="font-size:13px;" data-dismiss="modal"><i class="fa fa-arrow-left"></i>&nbsp;Batalkan</button>
                                <button type="submit" style="font-size:13px;" class="btn btn-primary" id="btn-submit-usulan"><i class="fa fa-send-o"></i>&nbsp;Teruskan</button>
                            </div>
                        </div>
                    </form>
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
    </script>
    <script>
        $(document).ready(function() {
            $('#table').DataTable({
                responsive : true,
            });
        } );

        $(document).ready(function() {
            $('#anggota').DataTable({
                responsive : true,
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
                    $('#peta_name').text(data.peta_jalan);
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
            $.ajax({
                url: "{{ url('pengusul/manajemen_usulan') }}"+'/'+ id + "/get_anggota",
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    $('#modalanggota').modal('show');
                    $('#usulan_id').val(id);
                    $('#judul').text(data['usulan'].judul_penelitian);
                    var res='';
                    var no = 1;
                    $.each (data['anggotas'], function (key, value) {
                        res +=
                        '<tr>'+
                            '<td>'+no+++'</td>'+
                            '<td>'+value.anggota_nama+'</td>'+
                            '<td>'+value.anggota_nip+'</td>'+
                            '<td>'+value.anggota_prodi_nama+'</td>'+
                            '<td>'+value.anggota_fakultas_nama+'</td>'+
                        '</tr>';
                    });
                    $('#tbody').html(res);
                },
                error:function(){
                    alert("Nothing Data");
                }
            });
        }

        $(document).ready(function(){
            $(document).on('change','#fakultas_id',function(){
                // alert('berhasil');
                var fakultas_id = $(this).val();
                var div = $(this).parent().parent();
                var op=" ";
                $.ajax({
                type :'get',
                url: "{{ url('pengusul/manajemen_usulan/cari_prodi') }}",
                data:{'fakultas_id':fakultas_id},
                success:function(data){
                    op+='<option value="0" selected disabled>-- pilih prodi --</option>';
                    for(var i=0; i<data.length;i++){
                        op+='<option value="'+data[i].prodi_kode+'">'+data[i].nm_prodi+'</option>';
                    }
                    div.find('#prodi_id').html(" ");
                    div.find('#prodi_id').append(op);
                },
                error:function(){
                }
                });
            })
        });

        $(document).ready(function(){
            $(document).on('change','#prodi_id',function(){
                // alert('berhasil');
                var prodi_id = $(this).val();
                var div = $(this).parent().parent();
                var op=" ";
                $.ajax({
                type :'get',
                url: "{{ url('pengusul/manajemen_usulan/cari_anggota') }}",
                data:{'prodi_id':prodi_id},
                success:function(data){
                    // alert(data['prodi'][0]['dosen'][0]['pegawai'].pegNama);
                    op+='<option value="0" selected disabled>-- pilih anggota --</option>';
                    for(var i=0; i<data.length;i++){
                        if (data[i]['pegawai']['pegIsAktif'] == 1) {
                            op+='<option value="'+data[i].dsnPegNip+'">'+data[i]['pegawai'].pegNama+'</option>';
                        }
                    }
                    div.find('#anggota_id').html(" ");
                    div.find('#anggota_id').append(op);
                },
                error:function(){
                }
                });
            })
        });

        function usulkan(id){
            $.ajax({
                url: "{{ url('pengusul/manajemen_usulan') }}"+'/'+ id + "/get_anggaran",
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    $('#modalusulan').modal('show');
                    $('#usulan_id_usulkan').val(id);
                    if (data['habis'][0].jumlah != "0" && data['outputs'][0].jumlah != "0" && data['lainnya'][0].jumlah != "0" && data['penunjangs'][0].jumlah != "0") {
                        $('#usulan-success').show();
                        $('#usulan-danger').hide();
                        $('#btn-submit-usulan').attr("disabled", false);
                    }
                    else{
                        $('#usulan-danger').show();
                        $('#usulan-success').hide();
                        $('#btn-submit-usulan').attr("disabled", true);
                    }
                },
                error:function(){
                    alert("Nothing Data");
                }
            });
        }

        $(document).ready(function(){
            $('#anggota_id').change(function(){
                var status = $('#anggota_id').val();
                if(status != null){
                    $('#btn-submit-anggota').attr('disabled',false);
                }
                else{
                    $('#btn-submit-anggota').attr('disabled',true);
                }
            });
        });
    </script>
@endpush
