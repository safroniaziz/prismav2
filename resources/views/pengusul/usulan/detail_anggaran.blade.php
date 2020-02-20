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
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block" id="berhasil">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Berhasil: </strong> {{ $message }}
                        </div>
                        @else
                        <div class="alert alert-success alert-block" id="keterangan">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah rancangan anggaran detail untuk judul penelitian : {{ $judul_penelitian->judul_penelitian }}
                            <p>Harap memasukan anggaran yang sesuai, sehingga tidak terjadi kesalahan saat proses review oleh tim reviewer</p>
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
                    <a href="{{ route('pengusul.usulan') }}" class="btn btn-danger btn-sm"><i class="fa fa-arrow-left" style="font-size:12px;"></i>&nbsp; Kembali</a>
                    <a onclick="kelolaAnggaran({{ $id_usulan }})" class="btn btn-primary btn-sm" style="color:white;cursor:pointer;"><i class="fa fa-plus" style="font-size:12px;"></i>&nbsp; Tambah Anggaran</a>
                    @if (isset($id_usulan))
                        @php
                            $id = $id_usulan;
                        @endphp
                    @endif
                    <a href=" {{ route('pengusul.usulan.anggaran.cetak',[$id_usulan]) }} " class="btn btn-info btn-sm" style="color:white;cursor:pointer;"><i class="fa fa-print" style="font-size:12px;"></i>&nbsp; Cetak Anggaran</a>
                </div>
                <div class="col-md-12">
                    <p style="text-align:center;font-size:17px !important; font-weight:bold;"> RANCANGAN ANGGARAN BIAYA DAN JUSTIFIKASI ANGGARAN</p>
                    <table class="table table-bordered" style="width:100%" id="table">
                        <thead>
                            <tr>
                                <th colspan="6">1. Honor Output Kegiatan</th>
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
                                <th colspan="6">2. Belanja Bahan Habis Pakai</th>
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
                                <th colspan="6">3. Peralatan Penunjang</th>
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
                                <th colspan="6">4. Belanja / Perjalanan Lainnya</th>
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
                            @if (count($lainnya) > 0)
                                @if ($lainnya[0]->id == null)
                                    <tr>
                                        <td style="color:red; text-align:center;" colspan="6"><a><i>tabel belanja / perjalanan lainnya masih kosong</i></a></td>
                                    </tr>
                                    @else
                                    @php
                                        $no=1;
                                        $sub4 = 0;
                                    @endphp
                                    @foreach ($lainnya as $value)
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
                    <!-- Modal Anggaran -->
                    <div class="modal fade" id="modalanggaran" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Tambah Anggota Penelitian</p>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <form action=" {{ route('pengusul.usulan.anggaran_post') }} " method="POST">
                                    {{ csrf_field() }} {{ method_field('POST') }}
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="alert alert-success alert-block" id="berhasil">
                                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                                        <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Harap anda benar-benar memperhatikan & melengkapi rancangan anggaran anda hingga tidak ada kesalahan !!
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="usulan_id_anggaran" id="usulan_id_anggaran">
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="exampleInputEmail1">Pilih Jenis Anggaran</label>
                                                    <select name="jenis_anggaran" id="jenis_anggaran" class="form-control" required style="font-size:13px;">
                                                        <option value="" disabled selected>-- pilih jenis anggaran --</option>
                                                        <option value="honor_output">Honor Output Kegiatan</option>
                                                        <option value="bahan_habis">Belanja Bahan Habis Pakai</option>
                                                        <option value="peralatan_penunjang">Peralatan Penunjang</option>
                                                        <option value="perjalanan_lainnya">Belanja Perjalanan Lainnya</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row" id="honor_output" style="display:none;">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputEmail1">Keterangan Honor</label>
                                                    <input type="text" name="keterangan_honor" class="form-control" placeholder="masukan keterangan">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputEmail1">Biaya</label>
                                                    <input type="number" name="biaya" class="form-control" placeholder="masukan biaya">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputEmail1">Jumlah Hari / Minggu</label>
                                                    <input type="number" name="hari_per_minggu" class="form-control" placeholder="masukan jumlah hari / minggu">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputEmail1">Jumlah Minggu</label>
                                                    <input type="number" name="jumlah_minggu" class="form-control" placeholder="masukan jumlah minggu">
                                                </div>
                                            </div>

                                            <div class="row" id="bahan_habis" style="display:none;">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputEmail1">Material</label>
                                                    <input type="text" name="material_habis" class="form-control" placeholder="masukan material">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputEmail1">Justifikasi Pembelian</label>
                                                    <input type="text" name="justifikasi_pembelian_habis" class="form-control" placeholder="masukan justifikasi">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputEmail1">Kuantitas</label>
                                                    <input type="number" name="kuantitas_habis" class="form-control" placeholder="masukan kuantitas">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputEmail1">Satuan</label>
                                                    <input type="number" name="satuan_habis" class="form-control" placeholder="masukan satuan">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputEmail1">Harga Satuan :</label>
                                                    <input type="number" name="harga_satuan_habis" class="form-control" placeholder="masukan harga satuan">
                                                </div>
                                            </div>

                                            <div class="row" id="peralatan_penunjang" style="display:none;">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputEmail1">Material</label>
                                                    <input type="text" name="material_penunjang" class="form-control" placeholder="masukan material">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputEmail1">Justifikasi Pembelian</label>
                                                    <input type="text" name="justifikasi_pembelian_penunjang" class="form-control" placeholder="masukan justifikasi">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputEmail1">Kuantitas</label>
                                                    <input type="number" name="kuantitas_penunjang" class="form-control" placeholder="masukan kuantitas">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputEmail1">Satuan</label>
                                                    <input type="number" name="satuan_penunjang" class="form-control" placeholder="masukan satuan">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputEmail1">Harga Satuan :</label>
                                                    <input type="number" name="harga_satuan_penunjang" class="form-control" placeholder="masukan harga satuan">
                                                </div>
                                            </div>

                                            <div class="row" id="perjalanan_lainnya" style="display:none;">
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputEmail1">Material</label>
                                                    <input type="text" name="material_lainnya" class="form-control" placeholder="masukan material">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputEmail1">Justifikasi Pembelian</label>
                                                    <input type="text" name="justifikasi_pembelian_lainnya" class="form-control" placeholder="masukan justifikasi">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputEmail1">Kuantitas</label>
                                                    <input type="number" name="kuantitas_lainnya" class="form-control" placeholder="masukan kuantitas">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputEmail1">Satuan</label>
                                                    <input type="number" name="satuan_lainnya" class="form-control" placeholder="masukan satuan">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputEmail1">Harga Satuan :</label>
                                                    <input type="number" name="harga_satuan_lainnya" class="form-control" placeholder="masukan harga satuan">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" style="font-size:13px;" data-dismiss="modal"><i class="fa fa-arrow-left"></i>&nbsp;Kembali</button>
                                            <button type="submit" style="font-size:13px;" class="btn btn-primary" id="btn-submit"><i class="fa fa-save"></i>&nbsp;Simpan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Usulan -->
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

        function kelolaAnggaran(id){
            $('#modalanggaran').modal('show');
            $('#usulan_id_anggaran').val(id);
        }

        $(document).ready(function(){
            $('#jenis_anggaran').change(function(){
                var jenis = $('#jenis_anggaran').val();
                if(jenis == "honor_output"){
                    $("#honor_output").show();
                    $("#bahan_habis").hide();
                    $("#peralatan_penunjang").hide();
                    $("#perjalanan_lainnya").hide();

                    $('#honor_output input').prop('required',true);
                    $('#bahan_habis input').prop('required',false);
                    $('#peralatan_penunjang input').prop('required',false);
                    $('#perjalanan_lainnya input').prop('required',false);
                }else if(jenis == "bahan_habis"){
                    $("#bahan_habis").show();
                    $("#peralatan_penunjang").hide();
                    $("#perjalanan_lainnya").hide();
                    $("#honor_output").hide();

                    $('#honor_output input').prop('required',false);
                    $('#bahan_habis input').prop('required',true);
                    $('#peralatan_penunjang input').prop('required',false);
                    $('#perjalanan_lainnya input').prop('required',false);
                }else if(jenis == "peralatan_penunjang"){
                    $("#bahan_habis").hide();
                    $("#peralatan_penunjang").show();
                    $("#perjalanan_lainnya").hide();
                    $("#honor_output").hide();

                    $('#honor_output input').prop('required',false);
                    $('#bahan_habis input').prop('required',false);
                    $('#peralatan_penunjang input').prop('required',true);
                    $('#perjalanan_lainnya input').prop('required',false);
                }else if(jenis == "perjalanan_lainnya"){
                    $("#bahan_habis").hide();
                    $("#peralatan_penunjang").hide();
                    $("#perjalanan_lainnya").show();
                    $("#honor_output").hide();

                    $('#honor_output input').prop('required',false);
                    $('#bahan_habis input').prop('required',false);
                    $('#peralatan_penunjang input').prop('required',false);
                    $('#perjalanan_lainnya input').prop('required',true);
                }
            });
        });
    </script>
@endpush
