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
                    <div class="alert alert-success alert-block" id="keterangan">
                        
                        <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah semua usulan anda yang tersedia, silahkan tambahkan usulan baru jika diperlukan !!
                    </div>
                </div>
                <div class="col-md-12">
                    <form action="{{ route('operator.laporan_akhir.filter') }}" method="GET">
                        <div class="form-group col-md-4">
                            <label for="exampleInputEmail1">Jenis Kegiatan</label>
                            <select name="jenis_kegiatan" id="jenis_kegiatan" class="form-control">
                                <option value="" disabled selected>-- pilih jenis kegiatan --</option>
                                <option value="penelitian">Penelitian</option>
                                <option value="pengabdian">Pengabdian</option>
                            </select>
                            @if ($errors->has('jenis'))
                                <small class="form-text text-danger">{{ $errors->first('jenis') }}</small>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="exampleInputEmail1">Skim Kegiatan</label>
                            <select name="skim_id" id="skim_id" class="form-control">
                                <option selected disabled>-- pilih skim kegiatan --</option>
                                
                            </select>
                            @if ($errors->has('jenis'))
                                <small class="form-text text-danger">{{ $errors->first('skim_id') }}</small>
                            @endif
                        </div>

                        <div class="form-group col-md-4">
                            <label for="exampleInputEmail1">Pilih Unib</label>
                            <select name="unit" id="unit" class="form-control">
                                <option selected disabled>-- pilih unit --</option>
                                
                            </select>
                            @if ($errors->has('unit'))
                                <small class="form-text text-danger">{{ $errors->first('unit') }}</small>
                            @endif
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i>&nbsp; Cari</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul Kegiatan</th>
                                <th>Anggota Kelompok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no=1;
                            @endphp
                            @foreach ($usulans as $usulan)
                                <tr>
                                    <td> {{ $no++ }} </td>
                                    <td style="width:40% !important;">
                                        {!! $usulan->shortJudul !!}
                                        <a href="{{ route('operator.pending.detail',[$usulan->id,\Illuminate\Support\Str::slug($usulan->judul_kegiatan)]) }}" id="selengkapnya">selengkapnya</a>
                                                        <br>
                                                        <hr style="margin-bottom:5px !important; margin-top:5px !important;">
                                                        <span style="font-size:10px !important; text-transform:capitalize;" for="" class="badge badge-info">{{ $usulan->nm_skim }}</span>
                                                        <span style="font-size:10px !important;" for="" class="badge badge-danger">{{ $usulan->nm_ketua_peneliti }}</span>
                                                        <span style="font-size:10px !important;" for="" class="badge badge-secondary">{{ $usulan->tahun_usulan }}</span> <br>
                                                        Diusulkan {{ $usulan->created_at ? $usulan->created_at->diffForHumans() : '-' }} ({{ \Carbon\Carbon::parse($usulan->created_at)->format('j F Y H:i') }})
                                   </td>
                                    <td>
                                        @if ($usulan->nm_anggota == null)
                                            <label class="badge badge-danger"><i class="fa fa-close" style="padding:5px;"></i>&nbsp;Belum ditambahkan</label>
                                            @else
                                            <label class="badge" style="font-size:12px;">&nbsp;{!! $usulan->nm_anggota !!}</label>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Modal Detail-->
                    <div class="modal fade" id="modaldetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                            <p style="font-size:15px; color:black;" class="modal-title" id="exampleModalLabel"><i class="fa fa-info-circle"></i>&nbsp;Detail Usulan Penelitian</p>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-success alert-block" id="berhasil">
                                            
                                            <strong><i class="fa fa-info-circle"></i>&nbsp;Data Detail Usulan Penelitian Dosen Universitas Bengkulu</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped" style="width:100%">
                                            <tr>
                                                <td style="width:20%;">Judul Kegiatan</td>
                                                <td> : </td>
                                                <td>
                                                    <p id="judul_kegiatan_detail"></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Jenis Kegiatan</td>
                                                <td> : </td>
                                                <td>
                                                    <p style="text-transform:capitalize;" id="jenis_kegiatan_detail"></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Skim Penelitian</td>
                                                <td> : </td>
                                                <td>
                                                    <p id="skim_penelitian_detail"></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Ketua Peneliti</td>
                                                <td> : </td>
                                                <td>
                                                    <b> <a id="ketua_peneliti_detail"></a> </b><br>
                                                    <ul>
                                                        <li>Nip: <a id="ketua_nip"></a></li>
                                                        <li>Fakultas: <a id="ketua_fakultas"></a></li>
                                                        <li>Program Studi: <a id="ketua_prodi"></a></li>
                                                    </ul>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Anggota Kegiatan</td>
                                                <td> : </td>
                                                <td>
                                                    <p id="anggota_penelitian_detail"></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Ringkasan</td>
                                                <td> : </td>
                                                <td>
                                                    <p id="abstrak_detail"> </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Kata Kunci</td>
                                                <td> : </td>
                                                <td>
                                                    <p id="kata_kunci_detail"></p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal" style="font-size:13px;"><i class="fa fa-arrow-left"></i>&nbsp;Kembali</button>
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
        $(document).ready(function() {
            $('#table').DataTable({
                responsive : true,
            });
        } );

        function detail(id){
            $.ajax({
                url: "{{ url('operator/usulan_dosen/menunggu_disetujui') }}"+'/'+ id + "/detail",
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    $('#modaldetail').modal('show');
                    $('#judul_kegiatan_detail').text(data['usulan'].judul_kegiatan);
                    $('#skim_penelitian_detail').text(data['usulan'].nm_skim);
                    $('#jenis_kegiatan_detail').text(data['usulan'].jenis_kegiatan);
                    $('#ketua_peneliti_detail').text(data['usulan'].nm_ketua_peneliti);
                    $('#ketua_nip').text(data['usulan'].nip);
                    $('#ketua_prodi').text(data['usulan'].prodi);
                    $('#ketua_fakultas').text(data['usulan'].fakultas);
                    $('#abstrak_detail').html(data['usulan'].abstrak);
                    $('#kata_kunci_detail').html(data['usulan'].kata_kunci);
                    var res='';
                    $.each (data['anggotas'], function (key, value) {
                        res +=
                        '<b>'+value.nm_anggota+'</b>'+
                        '<ul>'+
                            '<li>'+'Nip : '+value.nip+'</li>'+
                            '<li>'+'Fakultas : '+value.fakultas+'</li>'+
                            '<li>'+'Program Studi : '+value.prodi+'</li>'+
                        '</ul>';
                    });
                    $('#anggota_penelitian_detail').html(res);
                },
                error:function(){
                    alert("Nothing Data");
                }
            });
        }

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
                        div.find('#skim_id').html(" ");
                        div.find('#skim_id').append(op);
                    },
                        error:function(){
                    }
                });
            })
        });

        $(document).ready(function(){
            $(document).on('change','#skim_id',function(){
                // alert('berhasil');
                var skim_id = $(this).val();
                var div = $(this).parent().parent();
                var op=" ";
                $.ajax({
                type :'get',
                url: "{{ url('pengusul/manajemen_usulan/cari_unit') }}",
                data:{'skim_id':skim_id},
                    success:function(data){
                        op+='<option value="0" selected disabled>-- pilih skim --</option>';
                        for(var i=0; i<data.length;i++){
                            // alert(data['jenis_publikasi'][i].skim_id);
                            op+='<option value="'+data[i].nm_unit+'">'+data[i].nm_unit+'</option>';
                        }
                        div.find('#unit').html(" ");
                        div.find('#unit').append(op);
                    },
                        error:function(){
                    }
                });
            })
        });
    </script>
@endpush
