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
                    <div class="alert alert-primary alert-block text-center" id="keterangan">
                        
                        <strong class="text-uppercase"><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong><br> Silahkan tambahkan usulan kegiatan anda, harap melengkapi data terlebih dahulu agar proses pengajuan usulan tidak ada masalah kedepannya !!
                    </div>
                </div>
                <div class="col-md-12">
                    <a href="{{ route('pengusul.usulan') }}" class="btn btn-danger btn-sm"><i class="fa fa-arrow-left"></i>&nbsp; Kembali</a>
                </div>
                <form action="{{ route('pengusul.usulan.add') }}" enctype="multipart/form-data" method="POST">
                    {{ csrf_field() }} {{ method_field('POST') }}
                    <div class="col-md-12">
                        <div class="form-group col-md-4">
                            <label for="exampleInputEmail1">Jenis Kegiatan</label></label>
                            <select name="jenis_kegiatan"  class="form-control" id="jenis_kegiatan" style="font-size:13px;">
                                <option value="" disabled selected>-- pilih jenis kegiatan --</option>
                                <option value="penelitian" {{ old('jenis_kegiatan') == "penelitian" ? 'selected' : ''  }}>Penelitian</option>
                                <option value="pengabdian" {{ old('jenis_kegiatan') == "pengabdian" ? 'selected' : ''  }}>Pengabdian</option>
                            </select>
                            @if ($errors->has('jenis_kegiatan'))
                                <small class="form-text text-danger">{{ $errors->first('jenis_kegiatan') }}</small>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="exampleInputEmail1">Pilih Skim :</label>
                            <select name="skim_id" id="skim_id1" class="form-control" style="font-size:13px;">
                                <option value="" disabled selected>-- pilih skim --</option>
                                @foreach ($skims as $skim)
                                    <option value=" {{ $skim->id }}" {{ old('skim_id') == $skim->id ? 'selected' : '' }}> {{ $skim->nm_skim }} </option>
                                @endforeach
                            </select>
                            @if ($errors->has('skim_id'))
                                <small class="form-text text-danger">{{ $errors->first('skim_id') }}</small>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="exampleInputEmail1">Kata Kunci</label>
                            <input type="text" name="kata_kunci" class="tags form-control" />
                            @if ($errors->has('kata_kunci'))
                                <small class="form-text text-danger">{{ $errors->first('kata_kunci') }}</small>
                            @endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="exampleInputEmail1">Judul Kegiatan</label>
                            <textarea name="judul_kegiatan" cols="30" rows="3" class="form-control">{{ old('judul_kegiatan') }}</textarea>
                            @if ($errors->has('judul_kegiatan'))
                                <small class="form-text text-danger">{{ $errors->first('judul_kegiatan') }}</small>
                            @endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="exampleInputEmail1">Luaran Kegiatan</label>
                            <textarea name="luaran" class="form-control" cols="30" rows="3">{{ old('luaran') }}</textarea>
                            @if ($errors->has('luaran'))
                                <small class="form-text text-danger">{{ $errors->first('luaran') }}</small>
                            @endif
                        </div>
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1">Ringkasan</label>
                            <textarea name="abstrak" id="abstrak" class="form-control" cols="30" rows="10">{{ old('abstrak') }}</textarea>
                            @if ($errors->has('abstrak'))
                                <small class="form-text text-danger">{{ $errors->first('abstrak') }}</small>
                            @endif
                        </div>
                        
                        <div class="form-group col-md-12">
                            <label for="exampleInputEmail1">Tujuan Kegiatan</label>
                            <textarea name="tujuan" id="tujuan" class="form-control" cols="30" rows="10">{{ old('tujuan') }}</textarea>
                            @if ($errors->has('tujuan'))
                                <small class="form-text text-danger">{{ $errors->first('tujuan') }}</small>
                            @endif
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label for="exampleInputEmail1">Biaya Yang Diusulkan : <a class="text-danger">harap masukan angka tanpa tanda titik dan koma</a> </label>
                            <input type="number" name="biaya_diusulkan" value="{{ old('biaya_diusulkan') }}" class="form-control">
                            @if ($errors->has('biaya_diusulkan'))
                                <small class="form-text text-danger">{{ $errors->first('biaya_diusulkan') }}</small>
                            @endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="exampleInputEmail1">File Usulan : <a class="text-danger">Harap masukan file pdf. Max : 2MB</a></label>
                            <input type="file" name="file_usulan" id="file_usulan1" class="form-control" style="padding-bottom:30px;">
                            @if ($errors->has('file_usulan'))
                                <small class="form-text text-danger">{{ $errors->first('file_usulan') }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <hr style="width: 50%" class="mt-0">
                        <button type="reset" name="reset" class="btn btn-danger btn-sm"><i class="fa fa-refresh"></i>&nbsp;Ulangi</button>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-check-circle"></i>&nbsp;Kirim Usulan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
<script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
    <script>
            // CKEDITOR.replace( 'abstrak_edit', {height:150});
            CKEDITOR.replace( 'abstrak', {height:150});
            CKEDITOR.replace( 'tujuan', {height:150});
            // CKEDITOR.replace( 'tujuan_edit', {height:150});
    </script>
    <script>
        $(document).ready(function() {
            $('#table').DataTable({
                responsive : true,
            });
        } );

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
                        div.find('#skim_id1').html(" ");
                        div.find('#skim_id1').append(op);
                    },
                        error:function(){
                    }
                });
            })
        });
    </script>
@endpush
