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
            <i class="fa fa-home"></i>&nbsp;Form Review Usulan Kegiatan
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
                        <div class="alert alert-danger alert-block" id="keterangan">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Silahkan review Judul Kegiatan : {{ $judul_kegiatan->judul_kegiatan }}
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
                    <form action="{{ route('operator.verifikasi.reviewer3_post') }}" method="POST">
                        {{ csrf_field() }} {{ method_field('POST') }}
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th>No</th>
                                <th>Kriteria Penilaian</th>
                                <th>Bobot Penilaian</th>
                                {{-- <th>Skor <a style="color:red;"><i>(1 - 100)</i></a></th> --}}
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
                                    {{-- <th style="padding:5px 20px;">
                                        <input type="number" max="100" min="1" name="nilai{{ $nomor }}">
                                    </th> --}}
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="4">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Masukan Total Nilai :</label>
                                        <input type="number" name="total_skor" max="100" min="1" class="form-control" required>
                                      </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Tambahkan Komentar :</label>
                                        <textarea name="komentar" id="komentar" class="form-control" cols="30" rows="5"></textarea>
                                      </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" style="text-align:center;">
                                    <a href="{{ route('operator.verifikasi') }}" class="btn btn-info btn-sm"><i class="fa fa-arrow-left" style="font-size:12px; color:white;"></i>&nbsp; Kembali</a>
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
    <script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
    <script>
            CKEDITOR.replace( 'komentar', {height:100});
    </script>
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
