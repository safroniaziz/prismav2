@extends('layouts.layout')
@section('title', 'Manajemen Reviewer Eksternal')
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
                    <a href="{{ route('operator.reviewer_eksternal') }}" class="btn btn-danger btn-sm"><i class="fa fa-arrow-left"></i>&nbsp; Kembali</a>
                </div>
                <div class="col-md-12">
                    <form action="{{ route('operator.reviewer_eksternal.post') }}" enctype="multipart/form-data" method="POST">
                        {{ csrf_field() }} {{ method_field('POST') }}
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Nip Reviewer :</label>
                                <input type="number" name="nip" value="{{ old('nip') }}" class="form-control">
                                @if ($errors->has('nip'))
                                    <small class="form-text text-danger">{{ $errors->first('nip') }}</small>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Nama Lengkap :</label>
                                <input type="text" name="nama" value="{{ old('nama') }}" class="form-control">
                                @if ($errors->has('nama'))
                                    <small class="form-text text-danger">{{ $errors->first('nama') }}</small>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Jenis Kelamin :</label>
                                <select name="jenis_kelamin" value="{{ old('jenis_kelamin') }}" class="form-control" id="">
                                    <option selected disabled>-- pilih jenis kelamin --</option>
                                    <option value="1">Laki-Laki</option>
                                    <option value="0">Perempuan</option>
                                </select>
                                @if ($errors->has('jenis_kelamin'))
                                    <small class="form-text text-danger">{{ $errors->first('jenis_kelamin') }}</small>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Password : <a style="color: red;" >minimal 6 karakter</a></label>
                                <input type="text" name="nama" value="{{ old('nama') }}" class="form-control">
                                @if ($errors->has('nama'))
                                    <small class="form-text text-danger">{{ $errors->first('nama') }}</small>
                                @endif
                            </div>

                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">NIDN :</label>
                                <input type="text" name="nidn" value="{{ old('nidn') }}" class="form-control">
                                @if ($errors->has('nidn'))
                                    <small class="form-text text-danger">{{ $errors->first('nidn') }}</small>
                                @endif
                            </div>
                            
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Jabatan Fungsional :</label>
                                <input type="text" name="jabatan_fungsional" value="{{ old('jabatan_fungsional') }}" class="form-control">
                                @if ($errors->has('jabatan_fungsional'))
                                    <small class="form-text text-danger">{{ $errors->first('jabatan_fungsional') }}</small>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">Universitas :</label>
                                <input type="text" name="universitas" value="{{ old('universitas') }}" id="universitas" class="form-control">
                                @if ($errors->has('universitas'))
                                    <small class="form-text text-danger">{{ $errors->first('universitas') }}</small>
                                @endif
                            </div>
                        <div class="col-md-12 text-center">
                            <hr style="width: 50%" class="mt-0">
                            <button type="reset" name="reset" value="{{ old('reset') }}" class="btn btn-danger btn-sm"><i class="fa fa-refresh"></i>&nbsp;Ulangi</button>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-check-circle"></i>&nbsp;Simpan</button>
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
    </script>
@endpush
