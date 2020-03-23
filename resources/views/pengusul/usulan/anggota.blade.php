@extends('layouts.layout')
@section('title', 'Tambah Anggota Kegiatan')
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
            <i class="fa fa-home"></i>&nbsp;Kelola Anggota Penelitian
        </header>
        <div class="panel-body" style="border-top: 1px solid #eee; padding:15px; background:white;">
            <div class="row" style="margin-right:-15px; margin-left:-15px;">
                <div class="col-md-12">
                    @if ($jumlah >0)
                        <div class="alert alert-danger alert-block" id="keterangan">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah anggota kelompok untuk judul kegiaan : <a style="font-style:italic;">{{ $judul_kegiatan->judul_kegiatan }}</a>
                            <p style="margin-top:10px;">Silahkan Masukan Nama Lengkap Anggota Anda, Lalu Klik Simpan. Jika Anda Sudah Mencari Nama Anggota dan Ternyata Salah, Silahkan Reload dan Cari Kembali Nama Anggota Yang Benar !!</p>
                        </div>
                        @else
                        <div class="alert alert-danger alert-block" id="keterangan">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Anda belum menambahkan anggota kegiatan !!</a>
                            <p style="margin-top:10px;">Silahkan Masukan Nama Lengkap Anggota Anda, Lalu Klik Simpan. Jika Anda Sudah Mencari Nama Anggota dan Ternyata Salah, Silahkan Reload dan Cari Kembali Nama Anggota Yang Benar !!</p>
                        </div>
                    @endif
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger alert-block" id="error">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Gagal: </strong> {{ $message }}
                        </div>
                    @endif
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block" id="error">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Berhasil: </strong> {{ $message }}
                        </div>
                    @endif
                </div>
                <hr style="width:80%; text-align:center;">
                <div class="col-md-12">
                    <a href=" {{ route('pengusul.usulan',[$id_usulan]) }} " class="btn btn-danger btn-sm" style="color:white;"><i class="fa fa-arrow-left"></i>&nbsp; Kembali</a>
                    <a onclick="tambahAnggota({{ $id_usulan }})" class="btn btn-primary btn-sm" style="color:white;cursor:pointer;"><i class="fa fa-plus" style="font-size:12px;"></i>&nbsp; Tambah Anggota</a>
                    <hr style="width:80%; text-align:center;">
                </div>

                <div class="col-md-12" id="form-tambah" style="display:none;">
                    <div class="alert alert-success alert-block" style="display:none;" id="sudah">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong><i class="fa fa-check-circle"></i>&nbsp;Nama Berhasil Ditemukan  </strong>
                    </div>
                    <form action=" {{ route('pengusul.usulan.anggota_post') }} " method="POST">
                        {{ csrf_field() }} {{ method_field('POST') }}
                        <input type="hidden" name="usulan_id_anggaran" id="usulan_id_anggaran">
                        <div class="row">
                            <input type="hidden" name="prodi_kode_anggota" id="prodi_kode_anggota">
                            <input type="hidden" name="fakultas_kode_anggota" id="fakultas_kode_anggota">
                            <input type="hidden" name="jk_anggota" id="jk_anggota">
                            <input type="hidden" name="jabatan_anggota" id="jabatan_anggota">
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Nama Anggota</label>
                                <input type="text" name="nm_anggota" id="nm_anggota" class="form-control" required >
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Nip Anggota</label>
                                <input type="text" name="nip_anggota" id="nip_anggota" class="form-control" required readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Prodi Anggota</label>
                                <input type="text" name="prodi_anggota" id="prodi_anggota" class="form-control" readonly required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Fakultas Anggota</label>
                                <input type="text" name="fakultas_anggota" id="fakultas_anggota" class="form-control" readonly required>
                            </div>
                            <div class="col-md-12" style="text-align:center;">
                                <a href=" {{ route('pengusul.usulan',[$id_usulan]) }} " class="btn btn-warning btn-sm" style="color:white;"><i class="fa fa-arrow-left"></i>&nbsp; Kembali</a>
                                <button type="reset" class="btn btn-danger btn-sm" style="font-size:13px;" ><i class="fa fa-refresh"></i>&nbsp;Ulangi</button>
                                <button type="submit" style="font-size:13px;" class="btn btn-primary btn-sm" id="btn-submit" disabled><i class="fa fa-check-circle"></i>&nbsp;Tambah Anggota</button>
                            </div>
                        </div>
                    </form>
                    <hr style="width:80%; text-align:center;">
                </div>
                <div class="col-md-12" style="margin-top:5px;">
                    <table class="table table-bordered table-striped" id="table">
                        <thead>
                            <tr>
                                <th style="text-align:center">No</th>
                                <th style="text-align:center">Nip Anggota</th>
                                <th style="text-align:center">Nama Anggota</th>
                                <th style="text-align:center">Prodi Anggota</th>
                                <th style="text-align:center">Fakultas Anggota</th>
                                <th style="text-align:center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no=1;
                            @endphp
                            @foreach ($anggotas as $anggota)
                                <tr>
                                    <td style="text-align:center;"> {{ $no++ }} </td>
                                    <td style="text-align:center"> {{ $anggota->anggota_nip }} </td>
                                    <td style="text-align:center"> {{ $anggota->anggota_nama }} </td>
                                    <td style="text-align:center"> {{ $anggota->anggota_prodi_nama }} </td>
                                    <td style="text-align:center"> {{ $anggota->anggota_fakultas_nama }} </td>
                                    <td style="text-align:center">
                                        <a onclick="hapusAnggota({{ $anggota->id }})" class="btn btn-danger btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Modal Hapus -->
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
                    <form method="POST" action="{{ route('pengusul.usulan.detail_anggota.hapus') }}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <input type="hidden" name="id" id="id_anggota">
                        <input type="hidden" name="id_usulan" value="{{ $id_usulan }}">
                        <button type="submit" class="btn btn-outline-light btn-sm" style="color:white;"><i class="fa fa-check-circle"></i>&nbsp; Ya, Hapus Data !</button>
                    </form>
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

        function tambahAnggota(id){
            $('#form-tambah').show(300);
            $('#usulan_id_anggaran').val(id);
        }

        $(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#nm_anggota").keyup(function(){
            var nm_anggota = $("#nm_anggota").val();
            url = "{{ url('pengusul/manajemen_usulan/cari_anggota') }}";
            $.ajax({
                url :url,
                data : {nm_anggota:nm_anggota},
                method :"get",
                success:function(data){
                    if(data['jumlah'] == 1){
                        $('#sudah').show();
                        $('#nip_anggota').val(data['detail']['pegawai'][0]['pegNip']);
                        $('#nm_anggota').val(data['detail']['pegawai'][0]['pegNama']);
                        $('#prodi_anggota').val(data['detail']['pegawai'][0]['dosen']['prodi']['prodiNamaResmi']);
                        $('#prodi_kode_anggota').val(data['detail']['pegawai'][0]['dosen']['prodi']['prodiKode']);
                        $('#fakultas_anggota').val(data['detail']['pegawai'][0]['dosen']['prodi']['fakultas']['fakNamaResmi']);
                        $('#fakultas_kode_anggota').val(data['detail']['pegawai'][0]['dosen']['prodi']['fakultas']['fakKode']);
                        $('#jk_anggota').val(data['detail']['pegawai'][0]['pegawai_simpeg']['pegJenkel']);
                        $('#jabatan_anggota').val(data['detail']['pegawai'][0]['pegawai_simpeg']['pegNmJabatan']);
                        $('#btn-submit').prop('disabled',false);
                    }
                }
            })
        })
    })

    function hapusAnggota(id){
        $('#modalhapus').modal('show');
        $('#id_anggota').val(id);
    }
    </script>
@endpush
