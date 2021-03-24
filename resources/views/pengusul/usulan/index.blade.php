@php
    use Illuminate\Support\Str;
@endphp

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
                            
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Berhasil: </strong> {{ $message }}
                        </div>
                        @else
                        @if (count($jadwal) > 0)
                            @if ($now >= $jadwal[0]->tanggal_awal && $now <= $jadwal[0]->tanggal_akhir)
                                <div class="alert alert-primary alert-block text-center" id="keterangan">
                                    <h6><strong class="text-uppercase"><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong></h6>
                                    <h6>Silahkan selesaikan tahapan dalam membuat usulan kegiatan baru berikut ini:</h6>
                                    <div>
                                        1. Klik tombol tambah usulan dan lengkapi semua field yang harus diisi <br>
                                        2. Lengkapi anggota kelompok maupun mahasiswa yang terlibat dalam kegiatan dengan meng-klik tombol tambah anggota <br>
                                        3. Setelah klik tombol tambah anggota, silahkan cari NIP(untuk dosen) dan NPM(untuk mahasiswa), dan simpan anggota yang sudah dicari <br>
                                        4. Kirimkan usulan yang sudah anda lengkapi sebelumnya dengan meng-klik tombol kirimkan usulan <br>
                                    </div>
                                </div>
                                @else
                                <div class="alert alert-danger alert-block" id="keterangan">
                                    
                                    <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Saat Ini Bukan Masa Upload Usulan Kegiatan !!
                                </div>
                            @endif
                            @else
                            <div class="alert alert-danger alert-block" id="keterangan">
                                
                                <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Jadwal Upload Usulan Kegiatan Belum Diatur !!
                            </div>
                        @endif
                    @endif
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger alert-block" id="berhasil">
                            
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Gagal: </strong> {{ $message }}
                        </div>
                    @endif
                    <div class="alert alert-success alert-block" style="display:none;" id="usulan-berhasil">
                        
                        <i class="fa fa-success-circle"></i><strong>Berhasil :</strong> Penelitian anda sudah diusulkan !!
                    </div>
                    <div class="alert alert-danger alert-block" style="display:none;" id="gagal">
                        
                        <i class="fa fa-close"></i><strong>&nbsp;Gagal :</strong> Proses pengusulan gagal !!
                    </div>
                </div>
                <div class="col-md-12" style="margin-bottom:5px;">
                    @if (count($jadwal) > 0)
                        @if ($now >= $jadwal[0]->tanggal_awal && $now <= $jadwal[0]->tanggal_akhir)
                            <a href="{{ route('pengusul.usulan.create',[\Illuminate\Support\Str::slug(Session::get('nm_dosen'))]) }}" class="btn btn-primary btn-sm"><i class="fa fa-plus" style="font-size:12px;"></i>&nbsp;Tambah Usulan</a>
                            {{-- <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal">
                                <i class="fa fa-plus" style="font-size:12px;"></i>&nbsp;Tambah Usulan
                            </button> --}}
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
                                <th style="text-align:center;">Anggota Kegiatan Internal</th>
                                <th style="text-align:center;">Biaya Diusulkan</th>
                                <th style="text-align:center;">File Usulan</th>
                                <th style="text-align:center;">Status Usulan</th>
                                <th style="text-align:center;">Kirim Usulan</th>
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
                                        <a href="{{ route('pengusul.usulan.detail',[$usulan->id, \Illuminate\Support\Str::slug(Session::get('nm_dosen')),\Illuminate\Support\Str::slug($usulan->judul_kegiatan)]) }}" id="selengkapnya">selengkapnya</a>
                                        <br>
                                        <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                        <span style="font-size:10px !important; text-transform:capitalize;" class="badge badge-info">{{ $usulan->nm_skim }}</span>
                                        <span style="font-size:10px !important; text-transform:capitalize;" class="badge badge-primary">{{ $usulan->jenis_kegiatan }}</span>
                                        <span style="font-size:10px !important;" class="badge badge-success">{{ $usulan->ketua_peneliti_nama }}</span>
                                        <span style="font-size:10px !important;" class="badge badge-secondary">{{ $usulan->tahun_usulan }}</span> <br>
                                        Diusulkan {{ $usulan->created_at ? $usulan->created_at->diffForHumans() : '-' }} ({{ \Carbon\Carbon::parse($usulan->created_at)->format('j F Y H:i') }})
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
                                    <td class="text-center">
                                        <a class="btn btn-primary btn-sm" href="{{ asset('upload/file_usulan/'.Str::slug(Session::get('nm_dosen')).'-'.Str::slug(Session::get('nip')).'/'.$usulan->file_usulan) }}" download="{{ $usulan->file_usulan }}"><i class="fa fa-download"></i></a>
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
                                        @if ($usulan->nm_anggota != null && $usulan->status_usulan == "0")
                                            <form action="{{ route('pengusul.usulan.usulkan',[$usulan->id])}}" method="POST">
                                                {{ csrf_field() }} {{ method_field('PATCH') }}
                                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-arrow-right"></i>&nbsp; Kirimkan Usulan</button>
                                            </form>
                                            @elseif($usulan->nm_anggota == null && $usulan->status_usulan != "1")
                                            <a style="color:red;"><i>harap lengkapi anggota kelompok</i></a>
                                            @else
                                            <label class="badge badge-success">
                                                <i class="fa fa-check-circle"></i>
                                            </label>
                                        @endif
                                    </td>
                                    <td style="text-align:center">
                                        @if ($usulan->status_usulan != 0)
                                            <button class="btn btn-primary btn-sm" disabled style="color:white; cursor:pointer;"><i class="fa fa-edit"></i></button>
                                            <button class="btn btn-danger btn-sm" disabled style="color:white; cursor:pointer;"><i class="fa fa-trash"></i></button>
                                            @else
                                            <a href="{{ route('pengusul.usulan.edit',[\Illuminate\Support\Str::slug(Session::get('nm_dosen')),$usulan->id]) }}" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-edit"></i></a>
                                            <a onclick="hapusUsulan({{ $usulan->id }})" class="btn btn-danger btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-trash"></i></a>
                                        @endif
                                    </td>
                                  
                                </tr>
                            @endforeach
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

        // function ubahUsulan(id){
        //     $.ajax({
        //         url: "{{ url('pengusul/manajemen_usulan') }}"+'/'+ id + "/edit",
        //         type: "GET",
        //         dataType: "JSON",
        //         success: function(data){
        //             $('#modalubah').modal('show');
        //             $('#id').val(data.id);
        //             $('#judul_kegiatan').val(data.judul_kegiatan);
        //             $('#jenis_kegiatan').val(data.jenis_kegiatan);
        //             $('#skim_id').val(data.skim_id);
        //             $('#biaya_diusulkan').val(data.biaya_diusulkan);
        //             $('#tujuan_edit').val(data.tujuan);
        //             $('#luaran').val(data.luaran);
        //             CKEDITOR.instances['abstrak_edit'].setData(data.abstrak);
        //             CKEDITOR.instances['tujuan_edit'].setData(data.tujuan);
        //             $('#peta_name').text(data.peta_jalan);
        //             $('#lembar_name').text(data.lembar_pengesahan);
        //             $('#usulan_name').text(data.file_usulan);
        //         },
        //         error:function(){
        //             alert("Nothing Data");
        //         }
        //     });
        // }

        function hapusUsulan(id){
            $('#modalhapus').modal('show');
            $('#id_hapus').val(id);
        }

        // function selengkapnya(id){
        //     $.ajax({
        //         url: "{{ url('pengusul/manajemen_usulan') }}"+'/'+ id + "/detail_judul",
        //         type: "GET",
        //         dataType: "JSON",
        //         success: function(data){
        //         $('#modaldetail').modal('show');
        //             $('#id').val(data.id);
        //             $('#detail-text').text(data.judul_kegiatan);
        //         },
        //         error:function(){
        //             alert("Nothing Data");
        //         }
        //     });
        // }
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

        
    </script>
@endpush
