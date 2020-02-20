@extends('layouts.layout')
@section('title', 'Dashboard')
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
@push('styles')
    <style>
        #detail:hover{
            text-decoration: underline !important;
            cursor: pointer !important;
            color:teal;
        }
    </style>
@endpush
@section('content')
    <section class="panel" style="margin-bottom:20px;">
        <header class="panel-heading" style="color: #ffffff;background-color: #074071;border-color: #fff000;border-image: none;border-style: solid solid none;border-width: 4px 0px 0;border-radius: 0;font-size: 14px;font-weight: 700;padding: 15px;">
            <i class="fa fa-home"></i>&nbsp;Tambah Reviewer Usulan Publikasi Didanai
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
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah semua usulan yang didanai yang telah mengupload laporan kemajuan, silahkan tambahakn reviewer laporan kemajuan !!
                        </div>
                    @endif
                </div>
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="table" style="width:100%; ">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul Penelitian</th>
                                <th>Ketua Penelitian</th>
                                <th>Anggota Penelitian</th>
                                <th>Laporan Kemajuan</th>
                                <th>Tambah Reviewer</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no=1;
                            @endphp
                            @foreach ($usulans as $usulan)
                                @php
                                    $jumlah = count(explode('&nbsp;|&nbsp;',$usulan->nm_reviewer));
                                @endphp
                                @if ($jumlah < 2)
                                    <tr>
                                        <td> {{ $no++ }} </td>
                                        <td> {{ $usulan->judul_penelitian }} </td>
                                        <td style="font-weight:bold;"> {{ $usulan->ketua_peneliti_nama }} </td>
                                        <td>
                                            <label class="badge">
                                                {!! $usulan->nm_anggota !!}
                                            </label>
                                        </td>
                                        <td>
                                            <a href="{{ asset('upload/laporan_kemajuan/'.$usulan->file_kemajuan) }}" download="{{ $usulan->file_kemajuan }}">
                                                <button type="button" class="btn btn-primary" style="padding:7px;font-size:13px;color:white;cursor:pointer;"><i class="fa fa-download"></i></button>
                                            </a>
                                        </td>
                                        <td>
                                            <a onclick="tambahReviewer({{ $usulan->id }})" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-user-plus"></i></a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Modal Anggota -->
                    <div class="modal fade" id="modalreviewer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Tambah Anggota Penelitian</p>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action=" {{ route('operator.laporan_kemajuan.reviewer_post') }} " method="POST">
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
                                                        <option value="" disabled selected>-- pilih prodi --</option>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label for="exampleInputEmail1">Pilih Reviewer</label>
                                                    <select name="reviewer_id" id="reviewer_id" class="form-control" required style="font-size:13px;">
                                                        <option value="" disabled selected>-- pilih reviewer --</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12">
                                                    <button type="submit" style="font-size:13px;" class="btn btn-primary" disabled id="btn-submit-reviewer"><i class="fa fa-save"></i>&nbsp;Simpan</button>
                                                    <button type="reset" style="font-size:13px;" class="btn btn-danger"><i class="fa fa-refresh"></i>&nbsp; Reset</button>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 table-responsive">
                                                    <div class="alert alert-success alert-block" id="berhasil">
                                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                                        <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah reviewer penelitian dengan judul : <a style="font-weight:bold; font-size:12px; text-decoration:underline;" id="judul"></a>
                                                    </div>
                                                    <table class="table table-bordered table-striped" id="anggota" style="width:100%;">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Nama Anggota</th>
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
            $('#id_usulan').val(id);
        }

        function tambahReviewer(id){
            $.ajax({
                url: "{{ url('operator/usulan_dosen/laporan_kemajuan') }}"+'/'+ id + "/get_reviewer",
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    $('#modalreviewer').modal('show');
                    $('#usulan_id').val(id);
                    $('#judul').text(data['usulan'].judul_penelitian);
                    var res='';
                    var no = 1;
                    $.each (data['reviewers'], function (key, value) {
                        res +=
                        '<tr>'+
                            '<td>'+no+++'</td>'+
                            '<td>'+value.nm_lengkap+'</td>'+
                            '<td>'+value.nip+'</td>'+
                            '<td>'+value.prodi+'</td>'+
                            '<td>'+value.fakultas+'</td>'+
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
                url: "{{ url('operator/usulan_dosen/laporan_kemajuan/cari_prodi') }}",
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
            $('#reviewer_id').change(function(){
                var status = $('#reviewer_id').val();
                if(status != null){
                    $('#btn-submit-reviewer').attr('disabled',false);
                }
                else{
                    $('#btn-submit-reviewer').attr('disabled',true);
                }
            });
        });

        $(document).ready(function(){
            $(document).on('change','#prodi_id',function(){
                // alert('berhasil');
                var prodi_id = $(this).val();
                var div = $(this).parent().parent();
                var op=" ";
                $.ajax({
                type :'get',
                url: "{{ url('operator/usulan_dosen/laporan_kemajuan/cari_reviewer') }}",
                data:{'prodi_id':prodi_id},
                success:function(data){
                    op+='<option value="0" selected disabled>-- pilih reviewer --</option>';
                    for(var i=0; i<data.length;i++){
                        if (data[i]['pegawai']['pegIsAktif'] == 1) {
                            op+='<option value="'+data[i].dsnPegNip+'">'+data[i]['pegawai'].pegNama+'</option>';
                        }
                    }
                    div.find('#reviewer_id').html(" ");
                    div.find('#reviewer_id').append(op);
                },
                error:function(){
                }
                });
            })
        });
    </script>
@endpush
