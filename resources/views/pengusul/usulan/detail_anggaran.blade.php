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
            <i class="fa fa-home"></i>&nbsp;Rancangan Anggaran Penelitian Detail
        </header>
        <div class="panel-body" style="border-top: 1px solid #eee; padding:15px; background:white;">
            <div class="row" style="margin-right:-15px; margin-left:-15px;">
                <div class="col-md-12">

                    <div class="alert alert-danger alert-block" id="keterangan">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah rancangan anggaran detail untuk judul penelitian : {{ $judul_kegiatan->judul_kegiatan }}
                        <p>Harap memasukan anggaran yang sesuai, sehingga tidak terjadi kesalahan saat proses review oleh tim reviewer</p>
                    </div>
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger alert-block" id="error">
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

                <div class="col-md-12">
                    <p style="text-align:center;font-size:17px !important; font-weight:bold;"> RANCANGAN ANGGARAN BIAYA DAN JUSTIFIKASI ANGGARAN</p>
                    <div>
                        <ul class="nav nav-tabs" id="myTab">
                            <li class="active"><a class="nav-item nav-link" data-toggle="tab" href="#nav-honor"><i class="fa fa-tasks"></i>&nbsp;Honor Output Kegiatan</a></li>
                            <li><a class="nav-item nav-link" data-toggle="tab" href="#nav-habis"><i class="fa fa-list-alt"></i>&nbsp;Belanja Bahan Habis Pakai</a></li>
                            <li><a class="nav-item nav-link" data-toggle="tab" href="#nav-penunjang"><i class="fa fa-th-list"></i>&nbsp;Peralatan Penunjang</a></li>
                            <li><a class="nav-item nav-link" data-toggle="tab" href="#nav-lainnya"><i class="fa fa-th-large"></i>&nbsp;Belanja/Perjalan Lainnya</a></li>
                            <li><a class="nav-item nav-link" data-toggle="tab" href="#nav-cetak"><i class="fa fa-print"></i>&nbsp;Cetak Anggaran</a></li>
                        </ul>
                    </div>
                    <div style="margin-top:10px;">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-block" id="berhasil">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong><i class="fa fa-info-circle"></i>&nbsp;Berhasil: </strong> {{ $message }}
                            </div>
                        @endif
                    </div>
                      <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-honor" role="tabpanel" aria-labelledby="nav-honor-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-12" style="margin-bottom:10px; margin-top:10px; text-align:center;">
                                        <a href="{{ route('pengusul.usulan') }}" class="btn btn-danger btn-sm"><i class="fa fa-arrow-left" style="font-size:12px;"></i>&nbsp; Halaman Usulan</a>
                                        <a onclick="kelolaAnggaranHonor({{ $id_usulan }})" class="btn btn-primary btn-sm" style="color:white;cursor:pointer;"><i class="fa fa-plus" style="font-size:12px;"></i>&nbsp; Tambah Anggaran</a>
                                        @if (isset($id_usulan))
                                            @php
                                                $id = $id_usulan;
                                            @endphp
                                        @endif
                                    </div>
                                    <form action=" {{ route('pengusul.usulan.anggaran_honor_post') }} " method="POST">
                                        {{ csrf_field() }} {{ method_field('POST') }}
                                        <input type="hidden" name="usulan_id_anggaran" id="usulan_id_anggaran">
                                        <div class="row" id="formanggaranhonor" style="display:none;">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Keterangan Honor</label>
                                                <input type="text" name="keterangan_honor" class="form-control" required placeholder="masukan keterangan">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Biaya</label>
                                                <input type="number" name="biaya" class="form-control" required placeholder="masukan biaya">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Jumlah Hari / Minggu</label>
                                                <input type="number" name="hari_per_minggu" class="form-control" required placeholder="masukan jumlah hari / minggu">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Jumlah Minggu</label>
                                                <input type="number" name="jumlah_minggu" class="form-control" required placeholder="masukan jumlah minggu">
                                            </div>
                                            <div class="col-md-12" style="text-align:center;">
                                                <button class="btn btn-warning btn-sm" style="font-size:13px; color:white;"><i class="fa fa-close"></i>&nbsp; Batalkan</button>
                                                <button type="reset" class="btn btn-danger btn-sm" style="font-size:13px;" ><i class="fa fa-refresh"></i>&nbsp;Reset</button>
                                                <button type="submit" style="font-size:13px;" class="btn btn-primary btn-sm" id="btn-submit"><i class="fa fa-save"></i>&nbsp;Simpan</button>
                                            </div>
                                        </div>
                                    </form>
                                    <table class="table table-bordered" style="width:100%" id="table">
                                        <thead>
                                            <tr>
                                                <th colspan="6">Rancangan Anggaran Honor Output Kegiatan</th>
                                            </tr>
                                            <tr>
                                                <th>No</th>
                                                <th>Honor</th>
                                                <th>Honor/Hari</th>
                                                <th>Waktu (Hari/Minggu)</th>
                                                <th>Minggu</th>
                                                <th>Honor Per Tahun</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($outputs) > 0)
                                                @if ($outputs[0]->id == null)
                                                    <tr>
                                                        <td style="color:red; text-align:center;" colspan="6"><a><i>tabel honor output kegiatan masih kosong</i></a></td>
                                                    </tr>
                                                    @else
                                                    @php
                                                        $no=1;
                                                        $sub1 = 0;
                                                    @endphp
                                                    @foreach ($outputs as $output)
                                                        @php
                                                            $total = $output->hari_per_minggu * $output->jumlah_minggu * $output->biaya;
                                                        @endphp
                                                        <tr>
                                                            <td> {{ $no++ }} </td>
                                                            <td> {{ $output->keterangan_honor }} </td>
                                                            <td> Rp. {{ number_format($output->biaya, 2) }} </td>
                                                            <td> {{ $output->hari_per_minggu }} </td>
                                                            <td> {{ $output->jumlah_minggu }} </td>
                                                            <td> Rp. {{ number_format($total, 2)   }}</td>
                                                        </tr>
                                                        @php
                                                            $sub1 += $total;
                                                        @endphp
                                                    @endforeach
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th>Subtotal :</th>
                                                        <th>
                                                            Rp. {{ number_format($sub1, 2) }}
                                                        </th>
                                                    </tr>
                                                @endif
                                                @else
                                                <tr>
                                                    <td style="color:red; text-align:center;" colspan="6"><a><i>tabel honor output kegiatan masih kosong</i></a></td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-habis" role="tabpanel" aria-labelledby="nav-habis-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-12" style="margin-bottom:10px; margin-top:10px; text-align:center;">
                                        <a href="{{ route('pengusul.usulan') }}" class="btn btn-danger btn-sm"><i class="fa fa-arrow-left" style="font-size:12px;"></i>&nbsp; Halaman Usulan</a>
                                        <a onclick="kelolaAnggaranHabis({{ $id_usulan }})" class="btn btn-primary btn-sm" style="color:white;cursor:pointer;"><i class="fa fa-plus" style="font-size:12px;"></i>&nbsp; Tambah Anggaran</a>
                                        @if (isset($id_usulan))
                                            @php
                                                $id = $id_usulan;
                                            @endphp
                                        @endif
                                    </div>
                                    <form action=" {{ route('pengusul.usulan.anggaran_habis_post') }} " method="POST">
                                        {{ csrf_field() }} {{ method_field('POST') }}
                                        <input type="hidden" name="usulan_id_anggaran_habis" id="usulan_id_anggaran_habis">
                                        <div class="row" id="formanggaranhabis" style="display:none;">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Material</label>
                                                <input type="text" name="material_habis" class="form-control" required placeholder="masukan material">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Justifikasi Pembelian</label>
                                                <input type="text" name="justifikasi_pembelian_habis" class="form-control" required placeholder="masukan justifikasi">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Kuantitas</label>
                                                <input type="number" name="kuantitas_habis" class="form-control" required placeholder="masukan kuantitas">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Harga Satuan :</label>
                                                <input type="number" name="harga_satuan_habis" class="form-control" required placeholder="masukan harga satuan">
                                            </div>
                                            <div class="col-md-12" style="text-align:center;">
                                                <button class="btn btn-warning btn-sm" style="font-size:13px; color:white;"><i class="fa fa-close"></i>&nbsp; Batalkan</button>
                                                <button type="reset" class="btn btn-danger btn-sm" style="font-size:13px;" ><i class="fa fa-refresh"></i>&nbsp;Reset</button>
                                                <button type="submit" style="font-size:13px;" class="btn btn-primary btn-sm" id="btn-submit"><i class="fa fa-save"></i>&nbsp;Simpan</button>
                                            </div>
                                        </div>
                                    </form>
                                    <table class="table table-bordered" style="width:100%" id="table">
                                        <thead>
                                            <tr>
                                                <th colspan="6">Rancangan Anggaran Belanja Bahan Habis Pakai</th>
                                            </tr>
                                            <tr>
                                                <th>No</th>
                                                <th>Material</th>
                                                <th>Justifikasi Pembelian</th>
                                                <th>Kuantitias</th>
                                                <th>Harga Satuan</th>
                                                <th>Honor Per Tahun</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($habis_pakais) > 0)
                                                @if ($habis_pakais[0]->id == null)
                                                    <tr>
                                                        <td style="color:red; text-align:center;" colspan="6"><a><i>tabel belanja habis pakai masih kosong</i></a></td>
                                                    </tr>
                                                    @else
                                                    @php
                                                        $no=1;
                                                        $sub2 = 0;
                                                    @endphp
                                                    @foreach ($habis_pakais as $value)
                                                        @php
                                                            $total = $value->kuantitas * $value->harga_satuan;
                                                        @endphp
                                                        <tr>
                                                            <td> {{ $no++ }} </td>
                                                            <td> {{ $value->material }} </td>
                                                            <td> {{ $value->justifikasi_pembelian }} </td>
                                                            <td> {{ $value->kuantitas }} </td>
                                                            <td> Rp. {{ number_format($value->harga_satuan, 2) }} </td>
                                                            <td> Rp.{{ number_format($total, 2) }}</td>
                                                        </tr>
                                                        @php
                                                            $sub2 += $total;
                                                        @endphp
                                                    @endforeach
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th>Subtotal :</th>
                                                        <th>
                                                            Rp. {{ number_format($sub2, 2) }}
                                                        </th>
                                                    </tr>
                                                @endif
                                                @else
                                                <tr>
                                                    <td style="color:red; text-align:center;" colspan="6"><a><i>tabel belanja habis pakai masih kosong</i></a></td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-penunjang" role="tabpanel" aria-labelledby="nav-penunjang-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-12" style="margin-bottom:10px; margin-top:10px; text-align:center;">
                                        <a href="{{ route('pengusul.usulan') }}" class="btn btn-danger btn-sm"><i class="fa fa-arrow-left" style="font-size:12px;"></i>&nbsp; Halaman Usulan</a>
                                        <a onclick="kelolaAnggaranPenunjang({{ $id_usulan }})" class="btn btn-primary btn-sm" style="color:white;cursor:pointer;"><i class="fa fa-plus" style="font-size:12px;"></i>&nbsp; Tambah Anggaran</a>
                                        @if (isset($id_usulan))
                                            @php
                                                $id = $id_usulan;
                                            @endphp
                                        @endif
                                    </div>
                                    <form action=" {{ route('pengusul.usulan.anggaran_penunjang_post') }} " method="POST">
                                        {{ csrf_field() }} {{ method_field('POST') }}
                                        <input type="hidden" name="usulan_id_anggaran_penunjang" id="usulan_id_anggaran_penunjang">
                                        <div class="row" id="formanggaranpenunjang" style="display:none;">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Material</label>
                                                <input type="text" name="material_penunjang" class="form-control" required placeholder="masukan material">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Justifikasi Pembelian</label>
                                                <input type="text" name="justifikasi_pembelian_penunjang" class="form-control" required placeholder="masukan justifikasi">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Kuantitas</label>
                                                <input type="number" name="kuantitas_penunjang" class="form-control" required placeholder="masukan kuantitas">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Harga Satuan :</label>
                                                <input type="number" name="harga_satuan_penunjang" class="form-control" required placeholder="masukan harga satuan">
                                            </div>
                                            <div class="col-md-12" style="text-align:center;">
                                                <button class="btn btn-warning btn-sm" style="font-size:13px; color:white;"><i class="fa fa-close"></i>&nbsp; Batalkan</button>
                                                <button type="reset" class="btn btn-danger btn-sm" style="font-size:13px;" ><i class="fa fa-refresh"></i>&nbsp;Reset</button>
                                                <button type="submit" style="font-size:13px;" class="btn btn-primary btn-sm" id="btn-submit"><i class="fa fa-save"></i>&nbsp;Simpan</button>
                                            </div>
                                        </div>
                                    </form>
                                    <table class="table table-bordered" style="width:100%" id="table">
                                        <thead>
                                            <tr>
                                                <th colspan="6">Rancangan Anggaran Peralatan Penunjang</th>
                                            </tr>
                                            <tr>
                                                <th>No</th>
                                                <th>Material</th>
                                                <th>Justifikasi Pembelian</th>
                                                <th>Kuantitias</th>
                                                <th>Harga Satuan</th>
                                                <th>Honor Per Tahun</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($penunjangs) >0)
                                                @if ($penunjangs[0]->id == null)
                                                    <tr>
                                                        <td style="color:red; text-align:center;" colspan="6"><a><i>tabel peralatan penunjang masih kosong</i></a></td>
                                                    </tr>
                                                    @else
                                                    @php
                                                        $no=1;
                                                        $sub3 = 0;
                                                    @endphp
                                                    @foreach ($penunjangs as $value)
                                                        @php
                                                            $total = $value->kuantitas * $value->harga_satuan;
                                                        @endphp
                                                        <tr>
                                                            <td> {{ $no++ }} </td>
                                                            <td> {{ $value->material }} </td>
                                                            <td> {{ $value->justifikasi_pembelian }} </td>
                                                            <td> {{ $value->kuantitas }} </td>
                                                            <td> Rp. {{ number_format($value->harga_satuan, 2) }} </td>
                                                            <td> Rp.{{ number_format($total, 2) }}</td>
                                                        </tr>
                                                        @php
                                                            $sub3 += $total;
                                                        @endphp
                                                    @endforeach
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th>Subtotal :</th>
                                                        <th>
                                                            Rp. {{ number_format($sub3, 2) }}
                                                        </th>
                                                    </tr>
                                                @endif
                                                @else
                                                <tr>
                                                    <td style="color:red; text-align:center;" colspan="6"><a><i>tabel peralatan penunjang masih kosong</i></a></td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-lainnya" role="tabpanel" aria-labelledby="nav-lainnya-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-12" style="margin-bottom:10px; margin-top:10px; text-align:center;">
                                        <a href="{{ route('pengusul.usulan') }}" class="btn btn-danger btn-sm"><i class="fa fa-arrow-left" style="font-size:12px;"></i>&nbsp; Halaman Usulan</a>
                                        <a onclick="kelolaAnggaranLainnya({{ $id_usulan }})" class="btn btn-primary btn-sm" style="color:white;cursor:pointer;"><i class="fa fa-plus" style="font-size:12px;"></i>&nbsp; Tambah Anggaran</a>
                                        @if (isset($id_usulan))
                                            @php
                                                $id = $id_usulan;
                                            @endphp
                                        @endif
                                    </div>
                                    <form action=" {{ route('pengusul.usulan.anggaran_lainnya_post') }} " method="POST">
                                        {{ csrf_field() }} {{ method_field('POST') }}
                                        <input type="hidden" name="usulan_id_anggaran_lainnya" id="usulan_id_anggaran_lainnya">
                                        <div class="row" id="formanggaranlainnya" style="display:none;">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Material</label>
                                                <input type="text" name="material_lainnya" class="form-control" required placeholder="masukan material">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Justifikasi Pembelian</label>
                                                <input type="text" name="justifikasi_pembelian_lainnya" class="form-control" required placeholder="masukan justifikasi">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Kuantitas</label>
                                                <input type="number" name="kuantitas_lainnya" class="form-control" required placeholder="masukan kuantitas">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Harga Satuan :</label>
                                                <input type="number" name="harga_satuan_lainnya" class="form-control" required placeholder="masukan harga satuan">
                                            </div>
                                            <div class="col-md-12" style="text-align:center;">
                                                <button class="btn btn-warning btn-sm" style="font-size:13px; color:white;"><i class="fa fa-close"></i>&nbsp; Batalkan</button>
                                                <button type="reset" class="btn btn-danger btn-sm" style="font-size:13px;" ><i class="fa fa-refresh"></i>&nbsp;Reset</button>
                                                <button type="submit" style="font-size:13px;" class="btn btn-primary btn-sm" id="btn-submit"><i class="fa fa-save"></i>&nbsp;Simpan</button>
                                            </div>
                                        </div>
                                    </form>
                                    <table class="table table-bordered" style="width:100%" id="table">
                                        <thead>
                                            <tr>
                                                <th colspan="6">Rancangan Anggaran Belanja/Perjalan Lainnya</th>
                                            </tr>
                                            <tr>
                                                <th>No</th>
                                                <th>Material</th>
                                                <th>Justifikasi Pembelian</th>
                                                <th>Kuantitias</th>
                                                <th>Harga Satuan</th>
                                                <th>Honor Per Tahun</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($lainnyas) >0)
                                                @if ($lainnyas[0]->id == null)
                                                    <tr>
                                                        <td style="color:red; text-align:center;" colspan="6"><a><i>tabel peralatan penunjang masih kosong</i></a></td>
                                                    </tr>
                                                    @else
                                                    @php
                                                        $no=1;
                                                        $sub3 = 0;
                                                    @endphp
                                                    @foreach ($lainnyas as $value)
                                                        @php
                                                            $total = $value->kuantitas * $value->harga_satuan;
                                                        @endphp
                                                        <tr>
                                                            <td> {{ $no++ }} </td>
                                                            <td> {{ $value->material }} </td>
                                                            <td> {{ $value->justifikasi_pembelian }} </td>
                                                            <td> {{ $value->kuantitas }} </td>
                                                            <td> Rp. {{ number_format($value->harga_satuan, 2) }} </td>
                                                            <td> Rp.{{ number_format($total, 2) }}</td>
                                                        </tr>
                                                        @php
                                                            $sub3 += $total;
                                                        @endphp
                                                    @endforeach
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th>Subtotal :</th>
                                                        <th>
                                                            Rp. {{ number_format($sub3, 2) }}
                                                        </th>
                                                    </tr>
                                                @endif
                                                @else
                                                <tr>
                                                    <td style="color:red; text-align:center;" colspan="6"><a><i>tabel peralatan penunjang masih kosong</i></a></td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-cetak" role="tabpanel" aria-labelledby="nav-cetak-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-12" style="margin-bottom:10px; margin-top:10px; text-align:center;">
                                        <a href=" {{ route('pengusul.usulan.anggaran.cetak',[$id_usulan]) }} " class="btn btn-info btn-sm" style="color:white;cursor:pointer;"><i class="fa fa-print" style="font-size:12px;"></i>&nbsp; Cetak Anggaran</a>
                                    </div>
                                    <table class="table table-bordered" style="width:100%" id="table">
                                        <thead>
                                            <tr>
                                                <th colspan="6">1. Rancangan Anggaran Honor Output Kegiatan</th>
                                            </tr>
                                            <tr>
                                                <th>No</th>
                                                <th>Honor</th>
                                                <th>Honor/Hari</th>
                                                <th>Waktu (Hari/Minggu)</th>
                                                <th>Minggu</th>
                                                <th>Honor Per Tahun</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($outputs) > 0)
                                                @if ($outputs[0]->id == null)
                                                    <tr>
                                                        <td style="color:red; text-align:center;" colspan="6"><a><i>tabel honor output kegiatan masih kosong</i></a></td>
                                                    </tr>
                                                    @else
                                                    @php
                                                        $no=1;
                                                        $sub1 = 0;
                                                    @endphp
                                                    @foreach ($outputs as $output)
                                                        @php
                                                            $total = $output->hari_per_minggu * $output->jumlah_minggu * $output->biaya;
                                                        @endphp
                                                        <tr>
                                                            <td> {{ $no++ }} </td>
                                                            <td> {{ $output->keterangan_honor }} </td>
                                                            <td> Rp. {{ number_format($output->biaya, 2) }} </td>
                                                            <td> {{ $output->hari_per_minggu }} </td>
                                                            <td> {{ $output->jumlah_minggu }} </td>
                                                            <td> Rp. {{ number_format($total, 2)   }}</td>
                                                        </tr>
                                                        @php
                                                            $sub1 += $total;
                                                        @endphp
                                                    @endforeach
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th>Subtotal :</th>
                                                        <th>
                                                            Rp. {{ number_format($sub1, 2) }}
                                                        </th>
                                                    </tr>
                                                @endif
                                                @else
                                                <tr>
                                                    <td style="color:red; text-align:center;" colspan="6"><a><i>tabel honor output kegiatan masih kosong</i></a></td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    <br>
                                    <table class="table table-bordered" style="width:100%" id="table">
                                        <thead>
                                            <tr>
                                                <th colspan="6">2. Rancangan Anggaran Belanja Bahan Habis Pakai</th>
                                            </tr>
                                            <tr>
                                                <th>No</th>
                                                <th>Material</th>
                                                <th>Justifikasi Pembelian</th>
                                                <th>Kuantitias</th>
                                                <th>Harga Satuan</th>
                                                <th>Honor Per Tahun</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($habis_pakais) > 0)
                                                @if ($habis_pakais[0]->id == null)
                                                    <tr>
                                                        <td style="color:red; text-align:center;" colspan="6"><a><i>tabel belanja habis pakai masih kosong</i></a></td>
                                                    </tr>
                                                    @else
                                                    @php
                                                        $no=1;
                                                        $sub2 = 0;
                                                    @endphp
                                                    @foreach ($habis_pakais as $value)
                                                        @php
                                                            $total = $value->kuantitas * $value->harga_satuan;
                                                        @endphp
                                                        <tr>
                                                            <td> {{ $no++ }} </td>
                                                            <td> {{ $value->material }} </td>
                                                            <td> {{ $value->justifikasi_pembelian }} </td>
                                                            <td> {{ $value->kuantitas }} </td>
                                                            <td> Rp. {{ number_format($value->harga_satuan, 2) }} </td>
                                                            <td> Rp.{{ number_format($total, 2) }}</td>
                                                        </tr>
                                                        @php
                                                            $sub2 += $total;
                                                        @endphp
                                                    @endforeach
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th>Subtotal :</th>
                                                        <th>
                                                            Rp. {{ number_format($sub2, 2) }}
                                                        </th>
                                                    </tr>
                                                @endif
                                                @else
                                                <tr>
                                                    <td style="color:red; text-align:center;" colspan="6"><a><i>tabel belanja habis pakai masih kosong</i></a></td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>

                                    <br>
                                    <table class="table table-bordered" style="width:100%" id="table">
                                        <thead>
                                            <tr>
                                                <th colspan="6">3. Rancangan Anggaran Peralatan Penunjang</th>
                                            </tr>
                                            <tr>
                                                <th>No</th>
                                                <th>Material</th>
                                                <th>Justifikasi Pembelian</th>
                                                <th>Kuantitias</th>
                                                <th>Harga Satuan</th>
                                                <th>Honor Per Tahun</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($penunjangs) >0)
                                                @if ($penunjangs[0]->id == null)
                                                    <tr>
                                                        <td style="color:red; text-align:center;" colspan="6"><a><i>tabel peralatan penunjang masih kosong</i></a></td>
                                                    </tr>
                                                    @else
                                                    @php
                                                        $no=1;
                                                        $sub3 = 0;
                                                    @endphp
                                                    @foreach ($penunjangs as $value)
                                                        @php
                                                            $total = $value->kuantitas * $value->harga_satuan;
                                                        @endphp
                                                        <tr>
                                                            <td> {{ $no++ }} </td>
                                                            <td> {{ $value->material }} </td>
                                                            <td> {{ $value->justifikasi_pembelian }} </td>
                                                            <td> {{ $value->kuantitas }} </td>
                                                            <td> Rp. {{ number_format($value->harga_satuan, 2) }} </td>
                                                            <td> Rp.{{ number_format($total, 2) }}</td>
                                                        </tr>
                                                        @php
                                                            $sub3 += $total;
                                                        @endphp
                                                    @endforeach
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th>Subtotal :</th>
                                                        <th>
                                                            Rp. {{ number_format($sub3, 2) }}
                                                        </th>
                                                    </tr>
                                                @endif
                                                @else
                                                <tr>
                                                    <td style="color:red; text-align:center;" colspan="6"><a><i>tabel peralatan penunjang masih kosong</i></a></td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>

                                    <br>
                                    <table class="table table-bordered" style="width:100%" id="table">
                                        <thead>
                                            <tr>
                                                <th colspan="6">4. Rancangan Anggaran Belanja / Perjalanan Lainnya</th>
                                            </tr>
                                            <tr>
                                                <th>No</th>
                                                <th>Material</th>
                                                <th>Justifikasi Pembelian</th>
                                                <th>Kuantitias</th>
                                                <th>Harga Satuan</th>
                                                <th>Honor Per Tahun</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($lainnyas) > 0)
                                                @if ($lainnyas[0]->id == null)
                                                    <tr>
                                                        <td style="color:red; text-align:center;" colspan="6"><a><i>tabel belanja / perjalanan lainnya masih kosong</i></a></td>
                                                    </tr>
                                                    @else
                                                    @php
                                                        $no=1;
                                                        $sub4 = 0;
                                                    @endphp
                                                    @foreach ($lainnyas as $value)
                                                        @php
                                                            $total = $value->kuantitas * $value->harga_satuan;
                                                        @endphp
                                                        <tr>
                                                            <td> {{ $no++ }} </td>
                                                            <td> {{ $value->material }} </td>
                                                            <td> {{ $value->justifikasi_pembelian }} </td>
                                                            <td> {{ $value->kuantitas }} </td>
                                                            <td> Rp. {{ number_format($value->harga_satuan, 2) }} </td>
                                                            <td> Rp.{{ number_format($total, 2) }}</td>
                                                        </tr>
                                                        @php
                                                            $sub4 += $total;
                                                        @endphp
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th>Subtotal :</th>
                                                        <th>
                                                            Rp. {{ number_format($sub4, 2) }}
                                                        </th>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                                @else
                                                <tr>
                                                    <td style="color:red; text-align:center;" colspan="6"><a><i>tabel belanja / perjalanan lainnya masih kosong</i></a></td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <th colspan="5">Total Anggaran Yang Diperlukan Setiap Tahun :</th>
                                                @if (!empty($sub1) && !empty($sub2) && !empty($sub3) && !empty($sub4))
                                                    <th> Rp. {{ number_format($sub1 + $sub2 + $sub3 + $sub4, 2) }}</th>
                                                @endif
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
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

        function kelolaAnggaranHonor(id){
            $('#formanggaranhonor').show(300);
            $('#usulan_id_anggaran').val(id);
        }

        function kelolaAnggaranHabis(id){
            $('#formanggaranhabis').show(300);
            $('#usulan_id_anggaran_habis').val(id);
        }

        function kelolaAnggaranPenunjang(id){
            $('#formanggaranpenunjang').show(300);
            $('#usulan_id_anggaran_penunjang').val(id);
        }

        function kelolaAnggaranLainnya(id){
            $('#formanggaranlainnya').show(300);
            $('#usulan_id_anggaran_lainnya').val(id);
        }

        $(document).ready(function(){
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                localStorage.setItem('activeTab', $(e.target).attr('href'));
            });
            var activeTab = localStorage.getItem('activeTab');
            if(activeTab){
                $('#myTab a[href="' + activeTab + '"]').tab('show');
            }
        });
    </script>
@endpush
