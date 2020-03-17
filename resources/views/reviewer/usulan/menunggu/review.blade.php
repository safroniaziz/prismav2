@extends('layouts.layout')
@section('title', 'Dashboard')
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
    @include('reviewer/sidebar')
@endsection
@section('content')
    <section class="panel" style="margin-bottom:20px;">
        <header class="panel-heading" style="color: #ffffff;background-color: #074071;border-color: #fff000;border-image: none;border-style: solid solid none;border-width: 4px 0px 0;border-radius: 0;font-size: 14px;font-weight: 700;padding: 15px;">
            <i class="fa fa-home"></i>&nbsp;Rancangan Anggaran Penelitian Detail
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
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Silahkan review judul penelitian : {{ $judul_kegiatan->judul_kegiatan }}
                        </div>
                    @endif
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger alert-block" id="berhasil">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Gagal: </strong> {{ $message }}
                        </div>
                    @endif
                </div>
                <div class="col-md-12">
                    <form action="{{ route('reviewer.usulan.review_post') }}" method="POST">
                        {{ csrf_field() }} {{ method_field('POST') }}
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th>No</th>
                                <th>Kriteria Penilaian</th>
                                <th>Bobot Penilaian</th>
                                <th>Skor <a style="color:red;"><i>(1 - 100)</i></a></th>
                            </tr>
                            @php
                                $no=1;
                                $nomor=0;
                            @endphp
                            <input type="hidden" name="jumlah" value="{{ $jumlah }}">
                            <input type="hidden" name="usulan_id" value="{{ $id_usulan }}">
                            @foreach ($formulirs as $formulir)
                                @php
                                    $nomor++;
                                @endphp
                                <tr>
                                    <td> {{ $no++ }} </td>
                                    <td>{{ $formulir->kriteria_penilaian }}</td>
                                    <td>{{ $formulir->bobot }}%</td>
                                    <th style="padding:5px 20px;">
                                        <input type="number" max="100" min="1" name="nilai{{ $nomor }}">
                                    </th>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="4" style="text-align:center;">
                                    <a href="{{ route('reviewer.menunggu') }}" class="btn btn-info btn-sm"><i class="fa fa-arrow-left" style="font-size:12px; color:white;"></i>&nbsp; Kembali</a>
                                    <button type="reset" class="btn btn-danger btn-sm"><i class="fa fa-refresh"></i>&nbsp; Reset</button>
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i>&nbsp; Simpan</button>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal Usulan -->
    </section>
@endsection
@push('scripts')
    <script>
        $(function () {
            $("input").keydown(function () {
                // Save old value.
                if (!$(this).val() || (parseInt($(this).val()) <= 100 && parseInt($(this).val()) >= 1))
                $(this).data("old", $(this).val());
            });
            $("input").keyup(function () {
                // Check correct, else revert back to old value.
                if (!$(this).val() || (parseInt($(this).val()) <= 100 && parseInt($(this).val()) >= 1))
                ;
                else
                $(this).val($(this).data("old"));
            });
            });
    </script>
@endpush
