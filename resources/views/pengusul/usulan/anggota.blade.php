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
                        <div class="alert alert-primary alert-block text-center" id="keterangan">
                            
                            <strong><i class="fa fa-info-circle"></i>&nbsp;PERHATIAN : </strong> Berikut adalah anggota kelompok untuk judul kegiaan : "<a style="font-style:italic;">{{ $judul_kegiatan->judul_kegiatan }}"</a>
                            <p style="margin-top:10px;">
                                <ol>
                                    <li>Pilih fakultas anggota anda</li>
                                    <li>Setelah fakultas dipilih, maka silahkan pilih program study dari anggota anda</li>
                                    <li>Setelah itu daftar dosen akan muncul di field dosen dan anda dapat memilih dosen yang menjadi anggota anda</li>
                                    <li>Selanjutnya silahkan klik tombol simpan anggota</li>
                                    <li>Ulangi langkah diatas jika ingin menambahkan dosen lainnya sebagai anggota</li>
                                </ol>
                            </p>
                        </div>
                        @else
                        <div class="alert alert-primary alert-block text-center" id="keterangan">
                            
                            <strong><i class="fa fa-info-circle"></i>&nbsp;PERHATIAN : </strong> Anda belum menambahkan anggota kegiatan, anda wajib memiliki minimal 1 anggota kelompok internal !!</a>
                            <p style="margin-top:10px;">
                                Silahkan cari anggota kelompok dengan mengikuti langkah-langkah berikut ini:
                                <ol>
                                    <li>Pilih fakultas anggota anda</li>
                                    <li>Setelah fakultas dipilih, maka silahkan pilih program study dari anggota anda</li>
                                    <li>Setelah itu daftar dosen akan muncul di field dosen dan anda dapat memilih dosen yang menjadi anggota anda</li>
                                    <li>Selanjutnya silahkan klik tombol simpan anggota</li>
                                    <li>Ulangi langkah diatas jika ingin menambahkan dosen lainnya sebagai anggota</li>
                                </ol>
                            </p>
                        </div>
                    @endif
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger alert-block" id="error">
                            
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Gagal: </strong> {{ $message }}
                        </div>
                    @endif
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block" id="error">
                            
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Berhasil: </strong> {{ $message }}
                        </div>
                    @endif
                </div>
                <div class="col-md-12">
                    <p style="text-align:center;font-size:17px !important; font-weight:bold;"> FORM TAMBAH ANGGOTA KEGIATAN PENELITIAN DAN PENGABDIAN</p>
                    <hr style="width:80%; text-align:center;">
                    <ul class="nav nav-tabs" id="myTab">
                        <li class="active"><a class="nav-item nav-link active" data-toggle="tab" href="#nav-internal"><i class="fa fa-book"></i>&nbsp;Anggota Internal</a></li>
                        <li><a class="nav-item nav-link" data-toggle="tab" href="#nav-eksternal "><i class="fa fa-list-alt"></i>&nbsp;Anggota Eksternal</a></li>
                        <li><a class="nav-item nav-link" data-toggle="tab" href="#nav-mahasiswa "><i class="fa fa-list-alt"></i>&nbsp;Mahasiswa Terlibat</a></li>
                        <li><a class="nav-item nav-link" data-toggle="tab" href="#nav-alumni "><i class="fa fa-list-alt"></i>&nbsp;Staf Pendukung Terlibat / Alumni</a></li>
                    </ul>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-internal" role="tabpanel" aria-labelledby="nav-honor-tab">
                            <div class="row">
                                @include('pengusul/usulan/anggota_detail/anggota_internal')
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="nav-eksternal" role="tabpanel" aria-labelledby="nav-honor-tab">
                            <div class="row">
                                @include('pengusul/usulan/anggota_detail/anggota_eksternal')
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="nav-mahasiswa" role="tabpanel" aria-labelledby="nav-honor-tab">
                            <div class="row">
                                @include('pengusul/usulan/anggota_detail/anggota_mahasiswa')
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="nav-alumni" role="tabpanel" aria-labelledby="nav-honor-tab">
                            <div class="row">
                                @include('pengusul/usulan/anggota_detail/anggota_alumni')
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
                    <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Konfirmasi Hapus Data Anggota</p>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                  Apakah anda yakin akan menghapus anggota kegiatan ?
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
            $("#nip_anggota").keyup(function(){
            var nip_anggota = $("#nip_anggota").val();
            url = "{{ url('pengusul/manajemen_usulan/cari_anggota') }}";
            $.ajax({
                url :url,
                data : {nip_anggota:nip_anggota},
                method :"get",
                success:function(data){
                    if(data['jumlah'] == 1){
                        if(data['detail']['pegawai'][0]['pegawai_simpeg']= "" || data['detail']['pegawai'][0]['pegawai_simpeg'] == null){
                            $('#sudah').show();
                            $('#nip_anggota').val(data['detail']['pegawai'][0]['pegNip']);
                            $('#nm_anggota').val(data['detail']['pegawai'][0]['pegNama']);
                            $('#prodi_anggota').val(data['detail']['pegawai'][0]['dosen']['prodi']['prodiNamaResmi']);
                            $('#prodi_kode_anggota').val(data['detail']['pegawai'][0]['dosen']['prodi']['prodiKode']);
                            $('#fakultas_anggota').val(data['detail']['pegawai'][0]['dosen']['prodi']['fakultas']['fakNamaResmi']);
                            $('#fakultas_kode_anggota').val(data['detail']['pegawai'][0]['dosen']['prodi']['fakultas']['fakKode']);
                            $('#btn-submit').prop('disabled',false);
                        }
                        else{
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
                }
            })
        })
    })

    function hapusAnggota(id){
        $('#modalhapus').modal('show');
        $('#id_anggota').val(id);
    }
    // $('#myTab a').click(function(e) {
    //     e.preventDefault();
    //     $(this).tab('show');
    // });

// store the currently selected tab in the hash value
//     $("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
//         var id = $(e.target).attr("href").substr(1);
//         window.location.hash = id;
//     });

// // on load of the page: switch to the currently selected tab
//     var hash = window.location.hash;
//     $('#myTab a[href="' + hash + '"]').tab('show');
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
