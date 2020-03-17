@extends('layouts.layout')
@section('title', 'Fakultas')
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
            <i class="fa fa-user"></i>&nbsp;Manajemen Fakultas
            <span class="tools pull-right" style="margin-top:-5px;">
                <a class="fa fa-chevron-down" href="javascript:;" style="float: left;margin-left: 3px;padding: 10px;text-decoration: none;"></a>
                <a class="fa fa-times" href="javascript:;" style="float: left;margin-left: 3px;padding: 10px;text-decoration: none;"></a>
            </span>
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
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah semua data Fakultas yang diambil dari pangkalan data universitas bengkulu, silahkan klik <b>update data</b> untuk memperbarui data Fakultas !!
                        </div>
                    @endif
                    <div class="alert alert-warning alert-block" id="proses" style="display:none;">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Proses generate membutuhkan beberapa waktu, harap tunggu !
                    </div>
                </div>
                <div class="col-md-12">
                    <a href="{{ route('operator.get_data_fakultas') }}" class="btn btn-primary  btn-flat" onclick="updatePortal()" style="font-size:12px;"  id="update"><i class="fa fa-refresh"></i>&nbsp;Generate Data</a>
                    <a class="btn btn-warning  btn-flat"  style=" display:none;color:white; font-size:12px;" id="tunggu"><i class="fa fa-cog fa-spin"></i>&nbsp;Harap Tunggu</a>
                </div>
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Fakultas Kode</th>
                                <th>Fakultas Kode Universitas</th>
                                <th>Nama Fakultas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no=1;
                            @endphp
                            @foreach ($fakultas as $fakultas)
                                <tr>
                                    <td> {{ $no++ }} </td>
                                    <td> {{ $fakultas->fakultas_kode }} </td>
                                    <td> {{ $fakultas->fakultas_kode_univ }} </td>
                                    <td> {{ $fakultas->nm_fakultas }} </td>
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
    <script>
        $('#table').DataTable({
            responsive: true,
        });
        function updatePortal(){
            $('#tunggu').show();
            $('#update').hide();
            $('#keterangan').hide();
            $('#proses').show();
            $('#berhasil').hide();
        }
    </script>
@endpush
