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
@section('content')
    <section class="panel" style="margin-bottom:20px;">
        <header class="panel-heading" style="color: #ffffff;background-color: #074071;border-color: #fff000;border-image: none;border-style: solid solid none;border-width: 4px 0px 0;border-radius: 0;font-size: 14px;font-weight: 700;padding: 15px;">
            <i class="fa fa-home"></i>&nbsp;Manajemen Skim Penelitian
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
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah semua skim anda yang tersedia, silahkan tambahkan skim baru jika diperlukan !!
                        </div>
                    @endif
                    <div class="alert alert-warning alert-block" id="proses" style="display:none;">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Proses update membutuhkan beberapa waktu, harap tunggu !
                    </div>
                </div>
                <div class="col-md-12" style="margin-bottom:5px;">
                    <a href="{{ route('operator.skim.generate') }}" id="generate" class="btn btn-primary btn-sm"><i class="fa fa-spinner fa-spin"></i>&nbsp; Generate Data</a>
                    <a class="btn btn-warning  btn-flat"  style=" display:none;color:white; font-size:12px;" id="tunggu"><i class="fa fa-cog fa-spin"></i>&nbsp;Harap Tunggu</a>
                </div>
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Skim</th>
                                <th>Tahun</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no=1;
                            @endphp
                            @foreach ($skims as $skim)
                                <tr>
                                    <td> {{ $no++ }} </td>
                                    <td> {{ $skim->nm_skim }} </td>
                                    <td>
                                        @if ($skim->tahun == null)
                                            <label for="" class="badge badge-danger" style="padding:5px;">-</label>
                                            @else
                                            {{ $skim->tahun }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
    <script>
            CKEDITOR.replace( 'abstrak_edit', {height:150});
            CKEDITOR.replace( 'abstrak', {height:150});
    </script>
    <script>
        $(document).ready(function() {
            $('#table').DataTable({
                responsive: true,
            });
        } );

        $(document).ready(function(){
            $("#generate").click(function(){
                $('#tunggu').show();
                $('#generate').hide();
                $('#keterangan').hide();
                $('#proses').show();
                $('#berhasil').hide();
            });
        });
    </script>
@endpush
