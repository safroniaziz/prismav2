@extends('layouts.layout')
@section('title', 'Usulan Baru')
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
                        @if (count($jadwal) > 0)
                            @if ($now >= $jadwal[0]->tanggal_awal && $now <= $jadwal[0]->tanggal_akhir)
                                <div class="alert alert-danger alert-block" id="keterangan">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Silahkan tambahkan usulan kegiatan anda, harap melengkapi data terlebih dahulu sebelum anda mengusulkan kegiatan !!
                                </div>
                                @else
                                <div class="alert alert-danger alert-block" id="keterangan">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Saat Ini Bukan Masa Upload Usulan Kegiatan !!
                                </div>
                            @endif
                            @else
                            <div class="alert alert-danger alert-block" id="keterangan">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Jadwal Upload Usulan Kegiatan Belum Diatur !!
                            </div>
                        @endif
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
                    @if (count($jadwal) > 0)
                        @if ($now >= $jadwal[0]->tanggal_awal && $now <= $jadwal[0]->tanggal_akhir)
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal">
                                <i class="fa fa-plus" style="font-size:12px;"></i>&nbsp;Tambah Usulan
                            </button>
                            @else
                            <button type="button" class="btn btn-primary btn-sm disabled">
                                <i class="fa fa-plus" style="font-size:12px;"></i>&nbsp;Tambah Usulan
                            </button>
                        @endif
                        @else
                        <button type="button" class="btn btn-primary btn-sm disabled">
                            <i class="fa fa-plus" style="font-size:12px;"></i>&nbsp;Tambah Usulan
                        </button>
                    @endif
                </div>
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="table" style="width:100%;">
                        <thead>
                            <tr>
                                <th style="text-align:center;">No</th>
                                <th style="text-align:center;">Judul Kegiatan</th>
                                <th style="text-align:center;">Anggota Kelompok</th>
                                <th style="text-align:center;">Biaya Diusulkan</th>
                                <th style="text-align:center;">File Usulan</th>
                                <th style="text-align:center;">File Anggaran</th>
                                <th style="text-align:center;">Peta Jalan</th>
                                <th style="text-align:center;">Status Usulan</th>
                                <th style="text-align:center;">Usulkan</th>
                                <th style="text-align:center;">Aksi</th>
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
                                    <td style="width:27% !important; text-align:center;">
                                        @if ($usulan->nm_anggota == null)
                                            <a style="color:red;"><i>belum ditambahkan</i></a>
                                            @else
                                            <label class="badge" style="font-size:12px;">&nbsp;{!! $usulan->nm_anggota !!}</label>
                                        @endif
                                        <br>
                                        <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                        <a href="{{ route('pengusul.usulan.detail_anggota',[$usulan->id]) }}" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-user-plus"></i>&nbsp; tambah anggota</a>

                                    </td>
                                    <td style="width:25%; text-align:center;">
                                        <a>Rp. {{ number_format($usulan->biaya_diusulkan, 2) }}</a>
                                        {{-- <br> --}}
                                        {{-- <hr style="margin-bottom:5px !important; margin-top:5px !important;"> --}}
                                        {{-- <a href="{{ route('pengusul.usulan.detail_anggaran',[$usulan->id]) }}" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-gear"></i>&nbsp; kelola anggaran</a> --}}
                                    </td>
                                    <td style="text-align:center">
                                        @if ($usulan->file_usulan == null)
                                            <label class="badge badge-danger"><i class="fa fa-close" style="padding:5px;"></i></label>
                                            @else
                                            <label class="badge badge-success"><i class="fa fa-check-circle" style="padding:5px;"></i></label>
                                        @endif
                                    </td>
                                    <td style="text-align:center">
                                        @if ($usulan->file_anggaran == null)
                                            <label class="badge badge-danger"><i class="fa fa-close" style="padding:5px;"></i></label>
                                            @else
                                            <label class="badge badge-success"><i class="fa fa-check-circle" style="padding:5px;"></i></label>
                                        @endif
                                    </td>
                                    <td style="text-align:center">
                                        @if ($usulan->peta_jalan == null)
                                            <label class="badge badge-danger"><i class="fa fa-close" style="padding:5px;"></i></label>
                                            @else
                                            <label class="badge badge-success"><i class="fa fa-check-circle" style="padding:5px;"></i></label>
                                        @endif
                                    </td>
                                    <td style="text-align:center">
                                        @if ($usulan->status_usulan == '0')
                                            <label class="badge badge-warning" style="color:white;"><i class="fa fa-clock-o" style="padding:5px;"></i>&nbsp;belum diusulkan</label>
                                            @elseif($usulan->status_usulan == "1")
                                            <label class="badge badge-info" style="color:white;"><i class="fa fa-clock-o" style="padding:5px;"></i>&nbsp;menunggu verifikasi</label>
                                            @elseif($usulan->status_usulan == "2")
                                            <label class="badge badge-success" style="color:white;"><i class="fa fa-clock-o" style="padding:5px;"></i>&nbsp;sudah di review</label>
                                            @elseif($usulan->status_usulan == "3")
                                            <label class="badge badge-success" style="color:white;"><i class="fa fa-check-circle" style="padding:5px;"></i>&nbsp;usulan diterima</label>
                                            @elseif($usulan->status_usulan == "4")
                                            <label class="badge badge-danger" style="color:white;"><i class="fa fa-close" style="padding:5px;"></i>&nbsp;usulan ditolak</label>
                                            @elseif($usulan->status_usulan == "5")
                                            <label class="badge badge-info" style="color:white;"><i class="fa fa-info-circle" style="padding:5px;"></i>&nbsp;laporan kemajuan</label>
                                            @elseif($usulan->status_usulan == "6")
                                            <label class="badge badge-success" style="color:white;"><i class="fa fa-check-circle" style="padding:5px;"></i>&nbsp;laporan kemajuan diterima</label>
                                            @elseif($usulan->status_usulan == "7")
                                            <label class="badge badge-danger" style="color:white;"><i class="fa fa-close" style="padding:5px;"></i>&nbsp;laporan kemajuan ditolak</label>
                                        @endif
                                    </td>
                                    <td style="text-align:center">
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
                                    <td style="text-align:center">
                                        @if ($usulan->status_usulan != 0)
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
                                                <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Ubah Data Usulan (Ketua Kegiatan : <a> {{ Session::get('nm_dosen') }} </a> )</p>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action=" {{ route('pengusul.usulan.update') }} " method="POST" enctype="multipart/form-data">
                                                {{ csrf_field() }} {{ method_field('PATCH') }}
                                                            <div class="modal-body">
                                                                <input type="hidden" name="id" id="id">

                                                                <div class="form-group col-md-6">
                                                                    <label for="exampleInputEmail1">Jenis Kegiatan</label></label>
                                                                    <select name="jenis_kegiatan" class="form-control" id="jenis_kegiatan" style="font-size:13px;" required>
                                                                        <option value="" disabled selected>-- pilih jenis kegiatan --</option>
                                                                        <option value="penelitian">Penelitian</option>
                                                                        <option value="pengabdian">Pengabdian</option>
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
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputEmail1">Judul Kegiatan</label>
                                                                    <textarea name="judul_kegiatan" id="judul_kegiatan" cols="30" rows="3" class="form-control" required></textarea>
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputEmail1">Ringkasan</label>
                                                                    <textarea name="abstrak" id="abstrak_edit" cols="30" rows="10" required></textarea>
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputEmail1">Kata Kunci</label>
                                                                    <input id="tags_2" type="text" name="kata_kunci[]" id="kata_kunci" class="tags form-control" />
                                                                    <div id="suggestions-container" style="position: relative; float: left; width: 250px;"></div>
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputEmail1">Tujuan Kegiatan</label>
                                                                    <textarea name="tujuan" id="tujuan_edit" cols="30" rows="10" required></textarea>
                                                                </div>
                                                                <div class="form-group col-md-12">
                                                                    <label for="exampleInputEmail1">Luaran Kegiatan</label>
                                                                    <textarea name="luaran" id="luaran" class="form-control" cols="30" rows="3" required></textarea>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="exampleInputEmail1">Biaya Yang Diusulkan :</label>
                                                                    <input type="number" name="biaya_diusulkan" id="biaya_diusulkan" class="form-control" required>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="exampleInputEmail1">File Peta Jalan : <a style="color:red; font-style:italic; font-size:12px;">masukan file pdf/jpg/png/jpeg. Max : 5MB</a></label>
                                                                    <input type="file" name="peta_jalan" id="peta_jalan" accept="application/pdf, image/jpg, image/jpeg, image/png" class="form-control" style="padding-bottom:30px;">
                                                                    <div style="color:red;" style="margin-bottom:10px;">
                                                                        File Lama :<a id="peta_name"></a>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group col-md-6">
                                                                    <label for="exampleInputEmail1">File Lembar Pengesahan : <a style="color:red; font-style:italic; font-size:12px;">File pdf dan wajib  ttd dekan</a></label>
                                                                    <input type="file" name="lembar_pengesahan" id="lembar_pengesahan" accept="application/pdf" class="form-control" style="padding-bottom:30px;">
                                                                    <div style="color:red;" style="margin-bottom:10px;">
                                                                        File Lama :<a id="lembar_name"></a>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group col-md-6">
                                                                    <label for="exampleInputEmail1">File Usulan : <a style="color:red; font-style:italic; font-size:12px;">Harap masukan file pdf. Max : 5MB</a></label>
                                                                    <input type="file" name="file_usulan" id="file_usulan" accept="application/pdf" class="form-control" style="padding-bottom:30px;">
                                                                    <div style="color:red;" style="margin-bottom:10px;">
                                                                        File Lama :<a id="usulan_name"></a>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group col-md-6">
                                                                    <label for="exampleInputEmail1">File Anggaran : <a style="color:red; font-style:italic; font-size:12px;">Harap masukan file pdf. Max : 5MB</a></label>
                                                                    <input type="file" name="file_anggaran" id="file_anggaran" accept="application/pdf" class="form-control" style="padding-bottom:30px;">
                                                                    <div style="color:red;" style="margin-bottom:10px;">
                                                                        File Lama :<a id="usulan_name"></a>
                                                                    </div>
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
                                            <p style="font-size:15px; font-weight:bold;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Tambah Usulan Baru (Ketua Kegiatan : <a> {{ Session::get('nm_dosen') }} </a> )</p>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action=" {{ route('pengusul.usulan.add') }} " method="POST" enctype="multipart/form-data">
                                            {{ csrf_field() }} {{ method_field('POST') }}
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputEmail1">Jenis Kegiatan</label></label>
                                                            <select name="jenis_kegiatan" class="form-control" id="jenis_kegiatan" style="font-size:13px;" required>
                                                                <option value="" disabled selected>-- pilih jenis kegiatan --</option>
                                                                <option value="penelitian">Penelitian</option>
                                                                <option value="pengabdian">Pengabdian</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputEmail1">Pilih Skim :</label>
                                                            <select name="skim_id" id="skim_id1" class="form-control" style="font-size:13px;" required>
                                                                <option value="" disabled selected>-- pilih skim --</option>
                                                                {{-- @foreach ($skims as $skim)
                                                                    <option value=" {{ $skim->id }}"> {{ $skim->nm_skim }} </option>
                                                                @endforeach --}}
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputEmail1">Judul Kegiatan</label>
                                                            <textarea name="judul_kegiatan" cols="30" rows="3" class="form-control" required></textarea>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputEmail1">Ringkasan</label>
                                                            <textarea name="abstrak" id="abstrak" class="form-control" cols="30" rows="10" required></textarea>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputEmail1">Kata Kunci <a style="color:red"><i>klik tombol tab untuk setiap kata kunci</i></a></label>
                                                            <input id="tags_1" type="text" name="kata_kunci[]" class="tags form-control" required />
                                                            <div id="suggestions-container" style="position: relative; float: left; width: 250px;"></div>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputEmail1">Tujuan Kegiatan</label>
                                                            <textarea name="tujuan" id="tujuan" class="form-control" cols="30" rows="10" required></textarea>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="exampleInputEmail1">Luaran Kegiatan</label>
                                                            <textarea name="luaran" class="form-control" cols="30" rows="3" required></textarea>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputEmail1">Biaya Yang Diusulkan :</label>
                                                            <input type="number" name="biaya_diusulkan" class="form-control" required>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputEmail1">File Peta Jalan : <a style="color:red; font-style:italic; font-size:12px;">masukan file pdf/jpg/png/jpeg. Max : 5MB</a></label>
                                                            <input type="file" name="peta_jalan" id="peta_jalan1" accept="application/pdf, image/jpg, image/jpeg, image/png" class="form-control" style="padding-bottom:30px;" required>
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputEmail1">File Lembar Pengesahan : <a style="color:red; font-style:italic; font-size:12px;">File pdf dan wajib  ttd dekan</a></label>
                                                            <input type="file" name="lembar_pengesahan" id="lembar_pengesahan1" accept="application/pdf" class="form-control" style="padding-bottom:30px;" required>
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputEmail1">File Usulan : <a style="color:red; font-style:italic; font-size:12px;">Harap masukan file pdf. Max : 5MB</a></label>
                                                            <input type="file" name="file_usulan" id="file_usulan1" accept="application/pdf" class="form-control" style="padding-bottom:30px;" required>
                                                        </div>

                                                        <div class="form-group col-md-6">
                                                            <label for="exampleInputEmail1">File Anggaran : <a style="color:red; font-style:italic; font-size:12px;">Harap masukan file pdf. Max : 1MB</a></label>
                                                            <input type="file" name="file_anggaran" id="file_anggaran1" accept="application/pdf" class="form-control" style="padding-bottom:30px;" required>
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
                                <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Konfirmasi Hapus Data Anggota</p>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                              Apakah anda yakin akan menghapus data anggota ?
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
            </div>
        </div>
        <!-- Modal Usulan -->
        <div class="modal fade" id="modalusulan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Tambah Anggota Kegiatan</p>
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
<script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
    <script>
            CKEDITOR.replace( 'abstrak_edit', {height:150});
            CKEDITOR.replace( 'abstrak', {height:150});
            CKEDITOR.replace( 'tujuan', {height:150});
            CKEDITOR.replace( 'tujuan_edit', {height:150});
    </script>
    <script>
        $(document).ready(function() {
            $('#table').DataTable({
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
                    $('#judul_kegiatan').val(data.judul_kegiatan);
                    $('#jenis_kegiatan').val(data.jenis_kegiatan);
                    $('#skim_id').val(data.skim_id);
                    $('#biaya_diusulkan').val(data.biaya_diusulkan);
                    $('#tujuan_edit').val(data.tujuan);
                    $('#luaran').val(data.luaran);
                    CKEDITOR.instances['abstrak_edit'].setData(data.abstrak);
                    CKEDITOR.instances['tujuan_edit'].setData(data.tujuan);
                    $('#peta_name').text(data.peta_jalan);
                    $('#lembar_name').text(data.lembar_pengesahan);
                    $('#usulan_name').text(data.file_usulan);
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

        function selengkapnya(id){
            $.ajax({
                url: "{{ url('pengusul/manajemen_usulan') }}"+'/'+ id + "/detail_judul",
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
        function usulkan(id){
            // $.ajax({
            //     url: "{{ url('pengusul/manajemen_usulan') }}"+'/'+ id + "/get_anggaran",
            //     type: "GET",
            //     dataType: "JSON",
            //     success: function(data){
                    $('#modalusulan').modal('show');
                    $('#usulan_id_usulkan').val(id);
            //         if (data['habis'][0].jumlah != "0" && data['outputs'][0].jumlah != "0" && data['lainnya'][0].jumlah != "0" && data['penunjangs'][0].jumlah != "0") {
                        $('#usulan-success').show();
                        $('#usulan-danger').hide();
                        $('#btn-submit-usulan').attr("disabled", false);
                //     }
                //     else{
                //         $('#usulan-danger').show();
                //         $('#usulan-success').hide();
                //         $('#btn-submit-usulan').attr("disabled", true);
                //     }
                // },
                // error:function(){
                //     alert("Nothing Data");
                // }
            // });
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

        $(document).ready(function() {
            $("#anggota_id").select2({ dropdownParent: "#modalanggota" });
        });

        $(document).ready(function(){
            $(document).on('change','#jenis_kegiatan',function(){
                // alert('berhasil');
                var jenis_kegiatan = $(this).val();
                var div = $(this).parent().parent();
                var op=" ";
                $.ajax({
                type :'get',
                url: "{{ url('pengusul/manajemen_usulan/cari_skim') }}",
                data:{'jenis_kegiatan':jenis_kegiatan},
                    success:function(data){
                        op+='<option value="0" selected disabled>-- pilih skim --</option>';
                        for(var i=0; i<data.length;i++){
                            // alert(data['jenis_publikasi'][i].jenis_kegiatan);
                            op+='<option value="'+data[i].id+'">'+data[i].nm_skim+'</option>';
                        }
                        div.find('#skim_id1').html(" ");
                        div.find('#skim_id1').append(op);
                    },
                        error:function(){
                    }
                });
            })
        });
    </script>
@endpush
