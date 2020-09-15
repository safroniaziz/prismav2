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
                            
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Berhasil: </strong> {{ $message }}
                        </div>
                        @else
                        <div class="alert alert-danger alert-block" id="keterangan">
                            
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Silahkan review Judul Kegiatan : {{ $judul_kegiatan->judul_kegiatan }}
                        </div>
                    @endif
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger alert-block" id="berhasil">
                            
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
                                <th>Bobot Penilaian %</th>
                                <th>Skor <a style="color:red;"><i>(1 - 100)</i></a></th>
                                <th>Total Skor</th>
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
                                    <td>
                                        <input type="hidden" name="formulir_id{{ $nomor }}" id="formulir_id{{ $nomor }}" value="{{ $formulir->id }}">
                                        <input type="text" name="bobot" id="bobot{{ $nomor }}" value="{{ $formulir->bobot }}" readonly>
                                    </td>
                                    <td>
                                        <input type="number" max="100" min="1" name="nilai{{ $nomor }}" value="{{ old('nilai'.$nomor) }}" id="nilai{{ $nomor }}" required>
                                    </td>
                                    <td>
                                        <input type="text" max="100" min="1" name="total{{ $nomor }}" value="{{ old('total'.$nomor) }}" id="total{{ $nomor }}" readonly>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="4" class="text-center">
                                    <strong>Total Nilai</strong>
                                </td>
                                <td>
                                    <input type="text" name="total_nilai" id="total_nilai" class="form-control" readonly>
                                    @if ($errors->has('total_nilai'))
                                        <small class="form-text text-danger">{{ $errors->first('total_nilai') }}</small>
                                    @endif
                                    <br>
                                    <button type="reset" class="btn btn-danger btn-sm"><i class="fa fa-refresh"></i>&nbsp; Reset</button>
                                    <a onclick="hitung()" class="btn btn-primary btn-sm text-white" style="cursor: pointer"><i class="fa fa-calculator"></i>&nbsp; Hitung</a>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Komentar tentang isi proposal :</label>
                                        <textarea name="komentar" id="komentar" class="form-control" cols="30" rows="5">{{ old('komentar') }}</textarea>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Komentar tentang rancangan anggaran :</label>
                                        <textarea name="komentar_anggaran" id="komentar_anggaran" class="form-control" cols="30" rows="5">{{ old('komentar_anggaran') }}</textarea>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5" style="text-align:center;">
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

        function hitung(){
            var total_1 = parseFloat($('#total1').val());
            var total_2 = parseFloat($('#total2').val());
            var total_3 = parseFloat($('#total3').val());
            var total_4 = parseFloat($('#total4').val());
            var total_5 = parseFloat($('#total5').val());
            var total_6 = parseFloat($('#total6').val());
            var total_7 = parseFloat($('#total7').val());
            var total_8 = parseFloat($('#total8').val());
            var total_9 = parseFloat($('#total9').val());
            var total_10 = parseFloat($('#total10').val());
            var total_11 = parseFloat($('#total11').val());
            var total_12 = parseFloat($('#tota112').val());
            var total_13 = parseFloat($('#total13').val());
            var total_14 = parseFloat($('#total14').val());
            var total_15 = parseFloat($('#total15').val());
            var total_16 = parseFloat($('#total16').val());
            var total_17 = parseFloat($('#total17').val());
            var total_18 = parseFloat($('#total18').val());
            var total_19 = parseFloat($('#total19').val()); 
            var total_20 = parseFloat($('#total20').val());
            if (<?php echo $jumlah ?> == 1) {
                var hasil = total_1;
            }else if(<?php echo $jumlah ?> == 2) {
                var hasil = total_1+total_2;
            }else if(<?php echo $jumlah ?> == 2) {
                var hasil = total_1+total_2;
            }else if(<?php echo $jumlah ?> == 3) {
                var hasil = total_1+total_2+total_3;
            }else if(<?php echo $jumlah ?> == 4) {
                var hasil = total_1+total_2+total_3+total_4;
            }else if(<?php echo $jumlah ?> == 5) {
                var hasil = total_1+total_2+total_3+total_4+total_5;
            }else if(<?php echo $jumlah ?> == 6) {
                var hasil = total_1+total_2+total_3+total_4+total_5+total_6;
            }else if(<?php echo $jumlah ?> == 7) {
                var hasil = total_1+total_2+total_3+total_4+total_5+total_6+total_7;
            }else if(<?php echo $jumlah ?> == 8) {
                var hasil = total_1+total_2+total_3+total_4+total_5+total_6+total_7+total_8;
            }else if(<?php echo $jumlah ?> == 9) {
                var hasil = total_1+total_2+total_3+total_4+total_5+total_6+total_7+total_8+total_9;
            }else if(<?php echo $jumlah ?> == 10) {
                var hasil = total_1+total_2+total_3+total_4+total_5+total_6+total_7+total_8+total_9+total_10;
            }else if(<?php echo $jumlah ?> == 11) {
                var hasil = total_1+total_2+total_3+total_4+total_5+total_6+total_7+total_8+total_9+total_10+total_11;
            }else if(<?php echo $jumlah ?> == 12) {
                var hasil = total_1+total_2+total_3+total_4+total_5+total_6+total_7+total_8+total_9+total_10+total_11+total_12;
            }else if(<?php echo $jumlah ?> == 13) {
                var hasil = total_1+total_2+total_3+total_4+total_5+total_6+total_7+total_8+total_9+total_10+total_11+total_12+total_13;
            }else if(<?php echo $jumlah ?> == 14) {
                var hasil = total_1+total_2+total_3+total_4+total_5+total_6+total_7+total_8+total_9+total_10+total_11+total_12+total_13+total_14;
            }else if(<?php echo $jumlah ?> == 15) {
                var hasil = total_1+total_2+total_3+total_4+total_5+total_6+total_7+total_8+total_9+total_10+total_11+total_12+total_13+total_14+total_15;
            }else if(<?php echo $jumlah ?> == 16) {
                var hasil = total_1+total_2+total_3+total_4+total_5+total_6+total_7+total_8+total_9+total_10+total_11+total_12+total_13+total_14+total_15+total_16;
            }else if(<?php echo $jumlah ?> == 17) {
                var hasil = total_1+total_2+total_3+total_4+total_5+total_6+total_7+total_8+total_9+total_10+total_11+total_12+total_13+total_14+total_15+total_16+total_17;
            }else if(<?php echo $jumlah ?> == 18) {
                var hasil = total_1+total_2+total_3+total_4+total_5+total_6+total_7+total_8+total_9+total_10+total_11+total_12+total_13+total_14+total_15+total_16+total_17+total_18;
            }else if(<?php echo $jumlah ?> == 19) {
                var hasil = total_1+total_2+total_3+total_4+total_5+total_6+total_7+total_8+total_9+total_10+total_11+total_12+total_13+total_14+total_15+total_16+total_17+total_18+total_19;
            }else if(<?php echo $jumlah ?> == 20) {
                var hasil = total_1+total_2+total_3+total_4+total_5+total_6+total_7+total_8+total_9+total_10+total_11+total_12+total_13+total_14+total_15+total_16+total_17+total_18+total_19+total_20;
            }
            $('#total_nilai').val(hasil);
        }


        $(document).ready(function(){
            $("#bobot1, #nilai1").keyup(function(){
                var bobot = $("#bobot1").val();
                var nilai = $("#nilai1").val();
                var total = (bobot/100) * nilai;
                $('#total1').val(total);
            });
            $("#bobot5, #nilai2").keyup(function(){
                var bobot = $("#bobot5").val();
                var nilai = $("#nilai2").val();
                var total = (bobot/100) * nilai;
                $('#total2').val(total);
            });
            $("#bobot3, #nilai3").keyup(function(){
                var bobot = $("#bobot3").val();
                var nilai = $("#nilai3").val();
                var total = (bobot/100) * nilai;
                $('#total3').val(total);
            });
            $("#bobot4, #nilai4").keyup(function(){
                var bobot = $("#bobot4").val();
                var nilai = $("#nilai4").val();
                var total = (bobot/100) * nilai;
                $('#total4').val(total);
            });
            $("#bobot5, #nilai5").keyup(function(){
                var bobot = $("#bobot5").val();
                var nilai = $("#nilai5").val();
                var total = (bobot/100) * nilai;
                $('#total5').val(total);
            });
            $("#bobot6, #nilai6").keyup(function(){
                var bobot = $("#bobot6").val();
                var nilai = $("#nilai6").val();
                var total = (bobot/100) * nilai;
                $('#total6').val(total);
            });
            $("#bobot7, #nilai7").keyup(function(){
                var bobot = $("#bobot7").val();
                var nilai = $("#nilai7").val();
                var total = (bobot/100) * nilai;
                $('#total7').val(total);
            });
            $("#bobot8, #nilai8").keyup(function(){
                var bobot = $("#bobot8").val();
                var nilai = $("#nilai8").val();
                var total = (bobot/100) * nilai;
                $('#total8').val(total);
            });
            $("#bobot9, #nilai9").keyup(function(){
                var bobot = $("#bobot9").val();
                var nilai = $("#nilai9").val();
                var total = (bobot/100) * nilai;
                $('#total9').val(total);
            });
            $("#bobot10, #nilai10").keyup(function(){
                var bobot = $("#bobot10").val();
                var nilai = $("#nilai10").val();
                var total = (bobot/100) * nilai;
                $('#total10').val(total);
            });
            $("#bobot11, #nilai11").keyup(function(){
                var bobot = $("#bobot11").val();
                var nilai = $("#nilai11").val();
                var total = (bobot/100) * nilai;
                $('#total11').val(total);
            });
            $("#bobot12, #nilai12").keyup(function(){
                var bobot = $("#bobot12").val();
                var nilai = $("#nilai12").val();
                var total = (bobot/100) * nilai;
                $('#total12').val(total);
            });
            $("#bobot13, #nilai13").keyup(function(){
                var bobot = $("#bobot13").val();
                var nilai = $("#nilai13").val();
                var total = (bobot/100) * nilai;
                $('#total13').val(total);
            });
            $("#bobot14, #nilai14").keyup(function(){
                var bobot = $("#bobot14").val();
                var nilai = $("#nilai14").val();
                var total = (bobot/100) * nilai;
                $('#total14').val(total);
            });
            $("#bobot15, #nilai15").keyup(function(){
                var bobot = $("#bobot15").val();
                var nilai = $("#nilai15").val();
                var total = (bobot/100) * nilai;
                $('#total15').val(total);
            });
            $("#bobot16, #nilai16").keyup(function(){
                var bobot = $("#bobot16").val();
                var nilai = $("#nilai16").val();
                var total = (bobot/100) * nilai;
                $('#total16').val(total);
            });
            $("#bobot17, #nilai17").keyup(function(){
                var bobot = $("#bobot17").val();
                var nilai = $("#nilai17").val();
                var total = (bobot/100) * nilai;
                $('#total17').val(total);
            });
            $("#bobot18, #nilai18").keyup(function(){
                var bobot = $("#bobot18").val();
                var nilai = $("#nilai18").val();
                var total = (bobot/100) * nilai;
                $('#total18').val(total);
            });

            $("#bobot19, #nilai19").keyup(function(){
                var bobot = $("#bobot19").val();
                var nilai = $("#nilai19").val();
                var total = (bobot/100) * nilai;
                $('#total19').val(total);
            });
            $("#bobot20, #nilai20").keyup(function(){
                var bobot = $("#bobot20").val();
                var nilai = $("#nilai20").val();
                var total = (bobot/100) * nilai;
                $('#total20').val(total);
            });
        });

    </script>
@endpush
