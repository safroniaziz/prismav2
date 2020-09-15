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
@push('styles')
    <style>
        #detail:hover{
            text-decoration: underline !important;
            cursor: pointer !important;
            color:teal;
        }
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
            <i class="fa fa-home"></i>&nbsp;Luaran Kegiatan Detail
        </header>
        <div class="panel-body" style="border-top: 1px solid #eee; padding:15px; background:white;">
            <div class="row" style="margin-right:-15px; margin-left:-15px;">
                <div class="col-md-12">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block" id="berhasil">
                            
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Berhasil: </strong> {{ $message }}
                        </div>
                        @elseif(count($luarans)<1)
                        <div class="alert alert-danger alert-block" id="keterangan">
                            
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Belum Ada luaran kegiatan yang ditambahkan !!
                        </div>
                        @else
                        <div class="alert alert-success alert-block" id="keterangan">
                            
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Silahkan tambahkan luaran Penelitian Anda jika dibutuhkan !!
                        </div>
                    @endif
                </div>
                <div class="col-md-12" style="margin-bottom:5px;">
                    <a href=" {{ route('pengusul.laporan_akhir') }} " class="btn btn-warning btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-arrow-left"></i>&nbsp; Kembali</a>
                    <a onclick="tambahLuaran({{ $kegiatan_id }})" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-plus"></i>&nbsp; Tambah Luaran</a>
                </div>
                <div class="col-md-12" style="display:none;" id="form-luaran">
                    <form action=" {{ route('pengusul.laporan_akhir.luaran_post',[$kegiatan_id]) }} " method="POST">
                        {{ csrf_field() }} {{ method_field('POST') }}
                        <div class="row">
                            <input type="hidden" name="usulan_id" id="usulan_id" value="{{ $kegiatan_id }}">
                            <input type="hidden" name="luaran_id" id="luaran_id">
                            <div class="form-group col-md-12">
                                <label for="exampleInputEmail1">Judul Luaran</label>
                                <textarea name="judul_luaran" class="form-control" id="judul_luaran" cols="30" rows="3" required></textarea>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Jenis Kegiatan</label>
                                <select name="jenis_kegiatan" id="jenis_kegiatan" class="form-control" required>
                                    <option value="" selected disabled>-- pilih jenis_kegiatan --</option>
                                    @foreach ($jenis_kegiatan as $jenis_kegiatan)
                                        <option style="text-transform:capitalize;" value=" {{ $jenis_kegiatan->jenis_kegiatan }} "> {{ $jenis_kegiatan->jenis_kegiatan }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Jenis Publikasi</label>
                                <select name="jenis_publikasi" id="jenis_publikasi" class="form-control" required>
                                    <option value="" >-- pilih jenis publikasi --</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="text-align:center;">
                                <a onclick="batalkan()" class="btn btn-danger btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-close"></i>&nbsp; Tutup</a>
                                <button type="reset" class="btn btn-info btn-sm"><i class="fa fa-refresh"></i>&nbsp; Ulangi</button>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-check-circle"></i>&nbsp; Simpan Luaran</button>
                            </div>
                        </div>
                        <hr style="width:50%;">
                    </form>
                </div>
                <div class="col-md-12" style="display:none;" id="form-luaran-edit">
                    <form action=" {{ route('pengusul.usulan.update_luaran') }} " method="POST">
                        {{ csrf_field() }} {{ method_field('PATCH') }}
                        <div class="row">
                            <input type="hidden" name="usulan_id_edit" id="usulan_id_edit" value="{{ $kegiatan_id }}">
                            <input type="hidden" name="luaran_id_edit" id="luaran_id_edit">
                            <div class="form-group col-md-12">
                                <label for="exampleInputEmail1">Judul Luaran</label>
                                <textarea name="judul_luaran_edit" id="judul_luaran_edit" class="form-control" id="judul_luaran" cols="30" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="text-align:center;">
                                <a onclick="batalkan()" class="btn btn-danger btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-close"></i>&nbsp; Tutup</a>
                                <button type="reset" class="btn btn-info btn-sm"><i class="fa fa-refresh"></i>&nbsp; Ulangi</button>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-check-circle"></i>&nbsp; Simpan Perubahan</button>
                            </div>
                        </div>
                        <hr style="width:50%;">
                    </form>
                </div>
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="table" style="width:100%; ">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul Kegiatan</th>
                                <th style="text-align:center;">Judul Luaran</th>
                                <th style="text-align:center;">Jenis Publikasi</th>
                                <th style="text-align:center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no=1;
                            @endphp
                            @foreach ($luarans as $luaran)
                                <tr>
                                    <td> {{ $no++ }} </td>
                                    <td style="width:30% !important;">
                                        {!! $luaran->shortJudul !!}
                                        <a onclick="selengkapnya({{ $luaran->usulan_id }})" id="selengkapnya">selengkapnya</a>
                                        <br>
                                        <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                        <span style="font-size:10px !important; text-transform:capitalize;" for="" class="badge badge-info">{{ $luaran->jenis_kegiatan }}</span>
                                        <span style="font-size:10px !important;" for="" class="badge badge-danger">{{ $luaran->ketua_peneliti_nama }}</span>
                                        <span style="font-size:10px !important;" for="" class="badge badge-secondary">{{ $luaran->tahun_usulan }}</span>
                                    </td>
                                    <td style="text-align:center;">
                                        {{ $luaran->judul_luaran }}
                                    </td>
                                    <td style="text-align:center;">
                                        {{ $luaran->jenis_publikasi }}
                                    </td>
                                    <td style="text-align:center;">
                                        <a onclick="ubahLuaran({{ $luaran->id }})" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-edit"></i></a>
                                        <a onclick="hapusLuaran({{ $luaran->id }})" class="btn btn-danger btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Modal Konfirmasi -->
            <div class="modal modal-danger fade" id="modalhapus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="row">
                        <div class="col-md-12">
                            <form action=" {{ route('pengusul.usulan.hapus_laran') }} " method="POST">
                                {{ csrf_field() }} {{ method_field('DELETE') }}
                                <input type="hidden" name="usulan_id_hapus" id="usulan_id_hapus" value="{{ $kegiatan_id }}">
                                <input type="hidden" name="luaran_id_hapus" id="luaran_id_hapus">
                                <div class="modal-header modal-header-danger">
                                    <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Konfirmasi Hapus Data Luaran</p>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                Apakah anda yakin ingin menghapus data luaran kegiatan? Jika iya, klik hapus luaran !!
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-light btn-sm " style="color:white; background:white; background:transparent;" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;Batalkan</button>
                                    <button type="submit" class="btn btn-outline-light btn-sm " style="color:white; background:white; background:transparent;"><i class="fa fa-check-circle"></i>&nbsp;Hapus Luaran</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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
    <script>
        $(document).ready(function() {
            $('#table').DataTable({
                responsive : true,
            });
        } );

        function uploadLaporan(id){
            $('#modalupload').modal('show');
            $('#id_luaran').val(id);
        }

        function konfirmasi(id){
            $('#modalkonfirmasi').modal('show');
            $('#id_luaran').val(id);
        }

        $(document).ready(function(){
            $(document).on('change','#jenis_kegiatan',function(){
                // alert('berhasil');
                var jenis_kegiatan = $(this).val();
                var div = $(this).parent().parent();
                var op=" ";
                $.ajax({
                type :'get',
                url: "{{ url('pengusul/upload_laporan_akhir/cari_publikasi') }}",
                data:{'jenis_kegiatan':jenis_kegiatan},
                    success:function(data){
                        op+='<option value="0" selected disabled>-- pilih jenis publikasi --</option>';
                        for(var i=0; i<data['jenis_publikasi'].length;i++){
                            // alert(data['jenis_publikasi'][i].jenis_kegiatan);
                            if (data['jenis_kegiatan'] == data['jenis_publikasi'][i].jenis_kegiatan) {
                                op+='<option value="'+data['jenis_publikasi'][i].jenis_publikasi+'">'+data['jenis_publikasi'][i].jenis_publikasi+'</option>';
                            }
                        }
                        div.find('#jenis_publikasi').html(" ");
                        div.find('#jenis_publikasi').append(op);
                    },
                        error:function(){
                    }
                });
            })
        });

        function tambahLuaran(){
            $('#form-luaran').show(300);
            $('#form-luaran-edit').hide(300);
        }

        function ubahLuaran(id){
            $.ajax({
                url: "{{ url('pengusul/upload_laporan_akhir/luaran') }}"+'/'+ id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    $('#luaran_id_edit').val(id);
                    $('#form-luaran').hide(300);
                    $('#form-luaran-edit').show(300);
                    $('#judul_luaran_edit').val(data.judul_luaran);
                },
                error:function(){
                    alert("Nothing Data");
                }
            });
        }

        function hapusLuaran(id){
            $('#modalhapus').modal('show');
            $('#luaran_id_hapus').val(id);
        }

        function batalkan(){
            $('#form-luaran').hide(300);
        }

        function selengkapnya(id){
            $.ajax({
                url: "{{ url('pengusul/upload_laporan_akhir/usulan') }}"+'/'+ id + "/detail_judul",
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
    </script>
@endpush
