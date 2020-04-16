@extends('layouts.layout')
@section('title', 'Tambah Reviewer Usulan')
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
            <i class="fa fa-home"></i>&nbsp;Tambah Reviewer Usulan Publikasi, Riset dan Pengabdian Kepada Masyarakat
        </header>
        <div class="panel-body" style="border-top: 1px solid #eee; padding:15px; background:white;">
            <div class="row" style="margin-right:-15px; margin-left:-15px;">
                <div class="col-md-12">
                    @if ($jumlah >0)
                        <div class="alert alert-danger alert-block" id="keterangan">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah reviewer kegiatan untuk judul kegiaan : <a style="font-style:italic;">{{ $judul_kegiatan->judul_kegiatan }}</a>
                            <p style="margin-top:10px;">Silahkan Masukan Nama Lengkap Reviewer Kegiatan, Lalu Klik Simpan. Jika Anda Sudah Mencari Nama Reviewer dan Ternyata Salah, Silahkan Reload dan Cari Kembali Nama Reviewer Yang Benar !!</p>
                        </div>
                        @else
                        <div class="alert alert-danger alert-block" id="keterangan">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Anda belum menambahkan reviewer kegiatan !!</a>
                            <p style="margin-top:10px;">Silahkan Masukan Nama Lengkap Reviewer Kegiatan, Lalu Klik Simpan. Jika Anda Sudah Mencari Nama Reviewer dan Ternyata Salah, Silahkan Reload dan Cari Kembali Nama Anggota Yang Benar !!</p>
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
                    @if ($jumlah >= 2)
                        <div class="alert alert-danger alert-block" id="error">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Anda hanya dapat menambahkan maksimal 2 reviewer, anda tidak lagi dapat menambahkan reviewer yang lain, data usulan akan masuk ke dalam menu <b>Dalam Proses Review</b> Dan akan hilang dari menu <b>Tambah Reviewer</b> !!
                        </div>
                    @endif
                </div>
                <div style="margin-bottom:10px;">
                    <ul class="nav nav-tabs" id="myTab">
                        <li class="active"><a class="nav-item nav-link active" data-toggle="tab" href="#nav-internal"><i class="fa fa-book"></i>&nbsp;Reviewer Internal</a></li>
                        <li><a class="nav-item nav-link" data-toggle="tab" href="#nav-eksternal"><i class="fa fa-list-alt"></i>&nbsp;Reviewer Eksternal</a></li>
                    </ul>
                </div>
                <hr style="width:80%; text-align:center;">
                <div class="col-md-12">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-internal" role="tabpanel" aria-labelledby="nav-honor-tab">
                            <a href=" {{ route('operator.proses_review') }} " class="btn btn-danger btn-sm" style="color:white;"><i class="fa fa-arrow-left"></i>&nbsp; Kembali</a>
                            @if ($jumlah >= 2)
                                <button disabled class="btn btn-primary btn-sm" style="color:white;cursor:pointer;"><i class="fa fa-user-plus" style="font-size:12px;"></i>&nbsp; Tambah Reviewer Internal</button>
                                @else
                                <a onclick="tambahReviewer({{ $id_usulan }})" class="btn btn-primary btn-sm" style="color:white;cursor:pointer;"><i class="fa fa-user-plus" style="font-size:12px;"></i>&nbsp; Tambah Reviewer Internal</a>
                            @endif
                            <hr style="width:80%; text-align:center;">
                            <div class="row">
                                <div class="col-md-12" id="form-tambah" style="display:none;">
                                    <div class="alert alert-success alert-block" style="display:none;" id="sudah">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong><i class="fa fa-check-circle"></i>&nbsp;Nama Berhasil Ditemukan  </strong>
                                    </div>
                                    <form action=" {{ route('operator.proses_review.reviewer_post') }} " method="POST">
                                        {{ csrf_field() }} {{ method_field('POST') }}
                                        <input type="hidden" name="usulan_id_reviewer" id="usulan_id_reviewer">
                                        <div class="row">
                                            <input type="hidden" name="prodi_kode_reviewer" id="prodi_kode_reviewer">
                                            <input type="hidden" name="fakultas_kode_reviewer" id="fakultas_kode_reviewer">
                                            <input type="hidden" name="jk_reviewer" id="jk_reviewer">
                                            <input type="hidden" name="jabatan_reviewer" id="jabatan_reviewer">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Nip/Nik Reviewer</label>
                                                <input type="text" name="nip_reviewer" id="nip_reviewer" class="form-control" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Nama Reviewer</label>
                                                <input type="text" name="nm_reviewer" id="nm_reviewer" class="form-control" required readonly>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Prodi Reviewer</label>
                                                <input type="text" name="prodi_reviewer" id="prodi_reviewer" class="form-control" readonly required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Fakultas Reviewer</label>
                                                <input type="text" name="fakultas_reviewer" id="fakultas_reviewer" class="form-control" readonly required>
                                            </div>
                                            <div class="col-md-12" style="text-align:center;">
                                                <button type="reset" class="btn btn-danger btn-sm" style="font-size:13px;" ><i class="fa fa-refresh"></i>&nbsp;Ulangi</button>
                                                <button type="submit" style="font-size:13px;" class="btn btn-primary btn-sm" id="btn-submit" disabled><i class="fa fa-check-circle"></i>&nbsp;Tambah Reviewer</button>
                                            </div>
                                        </div>
                                    </form>
                                    <hr style="width:80%; text-align:center;">
                                </div>
                                <div class="col-md-12" style="margin-top:5px;">
                                    <table class="table table-bordered table-striped" id="table" style="width:100%;">
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
                                            @foreach ($reviewers as $reviewer)
                                                <tr>
                                                    <td style="text-align:center;"> {{ $no++ }} </td>
                                                    <td style="text-align:center"> {{ $reviewer->reviewer_nip }} </td>
                                                    <td style="text-align:center"> {{ $reviewer->reviewer_nama }} </td>
                                                    <td style="text-align:center"> {{ $reviewer->reviewer_prodi_nama }} </td>
                                                    <td style="text-align:center"> {{ $reviewer->reviewer_fakultas_nama }} </td>
                                                    <td style="text-align:center">
                                                        <a onclick="hapusReviewer({{ $reviewer->id }})" class="btn btn-danger btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="nav-eksternal" role="tabpanel" aria-labelledby="nav-honor-tab">
                            <a href=" {{ route('operator.proses_review') }} " class="btn btn-danger btn-sm" style="color:white;"><i class="fa fa-arrow-left"></i>&nbsp; Kembali</a>
                            @if ($jumlah >= 2)
                                <button disabled class="btn btn-primary btn-sm" style="color:white;cursor:pointer;"><i class="fa fa-user-plus" style="font-size:12px;"></i>&nbsp; Tambah Reviewer Eksternal</button>
                                @else
                                <a onclick="tambahReviewerEksternal({{ $id_usulan }})" class="btn btn-primary btn-sm" style="color:white;cursor:pointer;"><i class="fa fa-user-plus" style="font-size:12px;"></i>&nbsp; Tambah Reviewer Eksternal</a>
                            @endif
                            <hr style="width:80%; text-align:center;">
                            <div class="row">
                                <div class="col-md-12" id="form-tambah-eksternal" style="display:none;">
                                    <form action=" {{ route('operator.proses_review.reviewer_eksternal_post') }} " method="POST">
                                        {{ csrf_field() }} {{ method_field('POST') }}
                                        <input type="hidden" name="usulan_id_reviewer_eksternal" id="usulan_id_reviewer_eksternal">
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Nama Reviewer</label>
                                                <input type="text" name="nm_reviewer" id="nm_reviewer_eksternal" class="form-control" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Nip/Nik Reviewer</label>
                                                <input type="number" name="nip_reviewer" id="nip_reviewer_eksternal" class="form-control">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Nidn Reviewer</label>
                                                <input type="number" name="nip_reviewer" id="nip_reviewer_eksternal" class="form-control" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Prodi Reviewer</label>
                                                <input type="text" name="prodi_reviewer" id="prodi_reviewer_eksternal" class="form-control" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Fakultas Reviewer</label>
                                                <input type="text" name="fakultas_reviewer" id="fakultas_reviewer_eksternal" class="form-control" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Asal Universitas</label>
                                                <input type="text" name="universitas" id="universitas_eksternal" class="form-control" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputEmail1">Password Login</label>
                                                <input type="text" name="password" id="password" class="form-control" required>
                                            </div>
                                            <div class="col-md-12" style="text-align:center;">
                                                <button type="reset" class="btn btn-danger btn-sm" style="font-size:13px;" ><i class="fa fa-refresh"></i>&nbsp;Ulangi</button>
                                                <button type="submit" style="font-size:13px;" class="btn btn-primary btn-sm" id="btn-submit-eksternal" disabled><i class="fa fa-check-circle"></i>&nbsp;Tambah Reviewer</button>
                                            </div>
                                        </div>
                                    </form>
                                    <hr style="width:80%; text-align:center;">
                                </div>
                                <div class="col-md-12" style="margin-top:5px;">
                                    <table class="table table-bordered table-striped" id="table" style="width:100%;">
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
                                            @foreach ($reviewer_eksternals as $reviewer)
                                                <tr>
                                                    <td style="text-align:center;"> {{ $no++ }} </td>
                                                    <td style="text-align:center"> {{ $reviewer->reviewer_nip }} </td>
                                                    <td style="text-align:center"> {{ $reviewer->reviewer_nama }} </td>
                                                    <td style="text-align:center"> {{ $reviewer->reviewer_prodi_nama }} </td>
                                                    <td style="text-align:center"> {{ $reviewer->reviewer_fakultas_nama }} </td>
                                                    <td style="text-align:center">
                                                        <a onclick="hapusReviewerEksternal({{ $reviewer->id }})" class="btn btn-danger btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <!-- Modal Hapus Eksternal -->
                                        <div class="modal modal-danger fade" id="modalhapuseksternal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header modal-header-danger">
                                                    <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Konfirmasi Hapus Data Reviewer</p>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                Apakah anda yakin akan menghapus reviewer ?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-light btn-sm " style="color:white;" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;Close</button>
                                                    <form method="POST" action="{{ route('operator.proses_review.detail_reviewer_eksternal.hapus') }}">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                        <input type="hidden" name="id" id="id_reviewer_eksternal">
                                                        <input type="hidden" name="id_usulan" value="{{ $id_usulan }}">
                                                        <button type="submit" class="btn btn-outline-light btn-sm" style="color:white;"><i class="fa fa-check-circle"></i>&nbsp; Ya, Hapus Data !</button>
                                                    </form>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Hapus -->
        <div class="modal modal-danger fade" id="modalhapus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header modal-header-danger">
                    <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Konfirmasi Hapus Data Reviewer</p>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                  Apakah anda yakin akan menghapus reviewer ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-light btn-sm " style="color:white;" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;Close</button>
                    <form method="POST" action="{{ route('operator.proses_review.detail_reviewer.hapus') }}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <input type="hidden" name="id" id="id_reviewer">
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
            $("table[id^='table']").DataTable({
                responsive : true,
            });
        } );

        function tambahReviewer(id){
            $('#form-tambah').show(300);
            $('#usulan_id_reviewer').val(id);
        }

        function tambahReviewerEksternal(id){
            $('#form-tambah-eksternal').show(300);
            $('#usulan_id_reviewer_eksternal').val(id);
        }

        $(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#nip_reviewer").keyup(function(){
            var nip_reviewer = $("#nip_reviewer").val();
            url = "{{ url('operator/usulan_dosen/proses_review/cari_reviewer') }}";
            $.ajax({
                url :url,
                data : {nip_reviewer:nip_reviewer},
                method :"get",
                success:function(data){
                    if(data['jumlah'] == 1){
                        if(data['detail']['pegawai'][0]['pegawai_simpeg']= "" || data['detail']['pegawai'][0]['pegawai_simpeg'] == null){
                            $('#sudah').show();
                            $('#nip_reviewer').val(data['detail']['pegawai'][0]['pegNip']);
                            $('#nm_reviewer').val(data['detail']['pegawai'][0]['pegNama']);
                            $('#prodi_reviewer').val(data['detail']['pegawai'][0]['dosen']['prodi']['prodiNamaResmi']);
                            $('#prodi_kode_reviewer').val(data['detail']['pegawai'][0]['dosen']['prodi']['prodiKode']);
                            $('#fakultas_reviewer').val(data['detail']['pegawai'][0]['dosen']['prodi']['fakultas']['fakNamaResmi']);
                            $('#fakultas_kode_reviewer').val(data['detail']['pegawai'][0]['dosen']['prodi']['fakultas']['fakKode']);
                            $('#btn-submit').prop('disabled',false);
                        }
                        else{
                            $('#sudah').show();
                            $('#nip_reviewer').val(data['detail']['pegawai'][0]['pegNip']);
                            $('#nm_reviewer').val(data['detail']['pegawai'][0]['pegNama']);
                            $('#prodi_reviewer').val(data['detail']['pegawai'][0]['dosen']['prodi']['prodiNamaResmi']);
                            $('#prodi_kode_reviewer').val(data['detail']['pegawai'][0]['dosen']['prodi']['prodiKode']);
                            $('#fakultas_reviewer').val(data['detail']['pegawai'][0]['dosen']['prodi']['fakultas']['fakNamaResmi']);
                            $('#fakultas_kode_reviewer').val(data['detail']['pegawai'][0]['dosen']['prodi']['fakultas']['fakKode']);
                            $('#jk_reviewer').val(data['detail']['pegawai'][0]['pegawai_simpeg']['pegJenkel']);
                            $('#jabatan_reviewer').val(data['detail']['pegawai'][0]['pegawai_simpeg']['pegNmJabatan']);
                            $('#btn-submit').prop('disabled',false);
                        }
                    }
                }
            })
        })
    })

    $(document).ready(function(){
        $('#nip_reviewer_eksternal').keyup(function(){
            nip = $('#nip_reviewer_eksternal').val()
            if(nip != null || nip != ""){
                $('#btn-submit-eksternal').prop('disabled',false);
            }
            else{
                $('#btn-submit-eksternal').prop('disabled',true);
            }
        });
    });

    function hapusReviewer(id){
        $('#modalhapus').modal('show');
        $('#id_reviewer').val(id);
    }

    function hapusReviewerEksternal(id){
        $('#modalhapuseksternal').modal('show');
        $('#id_reviewer_eksternal').val(id);
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
