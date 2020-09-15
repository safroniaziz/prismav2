@extends('layouts.layout')
@section('title', 'Manajemen Reviewer')
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
            <i class="fa fa-home"></i>&nbsp;Usulan Publikasi, Riset dan Pengabdian Kepada Masyarakat
        </header>
        <div class="panel-body" style="border-top: 1px solid #eee; padding:15px; background:white;">
            <div class="row" style="margin-right:-15px; margin-left:-15px;">
                <div class="col-md-12">
                    <div class="alert alert-primary alert-block text-center" id="keterangan">
                        <strong class="text-uppercase"><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong><br> Silahkan tambahkan reviewer kegiatan yang baru dengan mengisi semua form dengan sesuai hingga selesai, setelah itu klik tombol simpan data !!
                    </div>
                </div>
                <div class="col-md-12">
                    <a href="{{ route('operator.reviewer') }}" class="btn btn-danger btn-sm"><i class="fa fa-arrow-left"></i>&nbsp; Kembali</a>
                </div>
                <div class="col-md-12">
                    <form action="{{ route('operator.reviewer.update') }}" enctype="multipart/form-data" method="POST">
                        {{ csrf_field() }} {{ method_field('PATCH') }}
                            <input type="hidden" name="id" value="{{ $reviewer->id }}">
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Nip Reviewer :</label>
                                <input type="number" name="nip" value="{{ $reviewer->nip }}" class="form-control">
                                @if ($errors->has('nip'))
                                    <small class="form-text text-danger">{{ $errors->first('nip') }}</small>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Nama Lengkap :</label>
                                <input type="text" name="nama" value="{{ $reviewer->nama }}" class="form-control">
                                @if ($errors->has('nama'))
                                    <small class="form-text text-danger">{{ $errors->first('nama') }}</small>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Jenis Kelamin :</label>
                                <select name="jenis_kelamin" class="form-control" id="">
                                    <option selected disabled>-- pilih jenis kelamin --</option>
                                    <option value="1" {{ $reviewer->jenis_kelamin == "1" ? 'selected' : '' }}>Laki-Laki</option>
                                    <option value="0" {{ $reviewer->jenis_kelamin == "0" ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @if ($errors->has('jenis_kelamin'))
                                    <small class="form-text text-danger">{{ $errors->first('jenis_kelamin') }}</small>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Fakultas :</label>
                                <select name="fakultas" id="fakultas" class="form-control">
                                    <option disabled selected>-- pilih fakultas --</option>
                                    @foreach ($fakultases['fakultas'] as $fakultas)
                                        <option value="{{ $fakultas['fakKode'] }}" {{ $fakultas['fakKode'] == $reviewer->fakultas_id ? 'selected' : '' }}>{{ $fakultas['fakNamaResmi'] }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('fakultas'))
                                    <small class="form-text text-danger">{{ $errors->first('fakultas') }}</small>
                                @endif
                            </div>  
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Program Study :</label>
                                <select name="prodi" id="prodi" class="form-control">
                                    <option disabled selected>-- pilih program study --</option>
                                    @foreach ($prodis['prodi'] as $prodi)
                                        <option value="{{ $prodi['prodiKode'] }}" {{ $prodi['prodiKode'] == $reviewer->prodi_id ? 'selected' : '' }}>{{ $prodi['prodiNamaResmi'] }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('prodi'))
                                    <small class="form-text text-danger">{{ $errors->first('prodi') }}</small>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">NIDN :</label>
                                <input type="text" name="nidn" value="{{ $reviewer->nidn }}" class="form-control">
                                @if ($errors->has('nidn'))
                                    <small class="form-text text-danger">{{ $errors->first('nidn') }}</small>
                                @endif
                            </div>
                            
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Jabatan Fungsional :</label>
                                <input type="text" name="jabatan_fungsional" value="{{ $reviewer->jabatan_fungsional }}" class="form-control">
                                @if ($errors->has('jabatan_fungsional'))
                                    <small class="form-text text-danger">{{ $errors->first('jabatan_fungsional') }}</small>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Universitas :</label>
                                <input type="text" name="universitas" value="{{ $reviewer->universitas }}" readonly id="universitas" class="form-control">
                                @if ($errors->has('universitas'))
                                    <small class="form-text text-danger">{{ $errors->first('universitas') }}</small>
                                @endif
                            </div>
                        <div class="col-md-12 text-center">
                            <hr style="width: 50%" class="mt-0">
                            <button type="reset" name="reset" class="btn btn-danger btn-sm"><i class="fa fa-refresh"></i>&nbsp;Ulangi</button>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-check-circle"></i>&nbsp;Kirim Usulan</button>
                        </div>
                    </form>
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

        $(document).ready(function(){
            $(document).on('change','#fakultas',function(){
                // alert('berhasil');
                var fakultas = $(this).val();
                var div = $(this).parent().parent();
                var op=" ";
                $.ajax({
                type :'get',
                url: "{{ url('operator/manajemen_reviewer/cari_prodi') }}",
                data:{'fakultas':fakultas},
                    success:function(data){
                        alert(data['prodi'][0].prodiKode);
                        op+='<option value="0" selected disabled>-- pilih program study --</option>';
                        for(var i=0; i<data['prodi'].length;i++){
                            // alert(data['jenis_publikasi'][i].fakultas);
                            op+='<option value="'+data['prodi'][i].prodiKode+'">'+data['prodi'][i].prodiNamaResmi+'</option>';
                        }
                        div.find('#prodi').html(" ");
                        div.find('#prodi').append(op);
                    },
                        error:function(){
                    }
                });
            })
        });
    </script>
@endpush
