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
    @include('reviewer/sidebar')
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
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Silahkan review judul penelitian : {{ $judul_penelitian->judul_penelitian }}
                        </div>
                    @endif
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger alert-block" id="berhasil">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Gagal: </strong> {{ $message }}
                        </div>
                    @endif
                </div>
                <div class="col-md-12" style="margin-bottom:5px;">
                    <a href="{{ route('reviewer.menunggu') }}" class="btn btn-danger btn-sm"><i class="fa fa-arrow-left" style="font-size:12px;"></i>&nbsp; Kembali</a>
                </div>
                <div class="col-md-12">

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
                }else if(jenis == "bahan_habis"){
                    $("#bahan_habis").show();
                    $("#peralatan_penunjang").hide();
                    $("#perjalanan_lainnya").hide();
                    $("#honor_output").hide();
                }else if(jenis == "peralatan_penunjang"){
                    $("#bahan_habis").hide();
                    $("#peralatan_penunjang").show();
                    $("#perjalanan_lainnya").hide();
                    $("#honor_output").hide();
                }else if(jenis == "perjalanan_lainnya"){
                    $("#bahan_habis").hide();
                    $("#peralatan_penunjang").hide();
                    $("#perjalanan_lainnya").show();
                    $("#honor_output").hide();
                }
            });
        });
    </script>
@endpush
