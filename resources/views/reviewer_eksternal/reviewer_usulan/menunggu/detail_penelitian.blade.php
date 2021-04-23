@extends('layouts.layout')
@section('title', 'Review Usulan Kegiatan')
@section('user-login')
    @if (Auth::guard('reviewerusulan')->check())
        {{ Auth::guard('reviewerusulan')->user()->reviewer_nama }}
    @endif
@endsection
@section('user-login2')
    @if (Auth::guard('reviewerusulan')->check())
        {{ Auth::guard('reviewerusulan')->user()->reviewer_nama }}
    @endif
@endsection
@section('login_as', 'Reviewer Eksternal')
@section('sidebar-menu')
    @include('reviewer_eksternal/sidebar')
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
                    <div class="alert alert-primary alert-block text-center text-uppercase" id="berhasil">
                        
                        <strong><i class="fa fa-info-circle"></i>&nbsp;Detail Informasi Usulan Kegiatan Dosen : </strong> <br>
                        
                    </div>
                </div>
                <div class="col-md-12">
                    <a href="{{ route('reviewer.menunggu') }}" class="btn btn-danger btn-sm"><i class="fa fa-arrow-left"></i>&nbsp; Kembali</a>
                </div>
                <div class="col-md-6">
                    <div class="x_panel">
                        <div class="x_title">
                            <h6>Detail Usulan Kegiatan Dosen</h6>
                            <div class="clearfix"></div>
                            <div class="x_content mt-3">
                                <table class="table table-hover">
                                    <tr>
                                        <th style="width: 20%">Judul Kegiatan</th>
                                        <td style="width: 2%"> : </td>
                                        <td>{{ $detail->judul_kegiatan }}</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 20%">Jenis Kegiatan</th>
                                        <td style="width: 2%"> : </td>
                                        <td>{{ $detail->jenis_kegiatan }}</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 20%">File Usulan</th>
                                        <td style="width: 2%"> : </td>
                                        <td>
                                            <a class="btn btn-primary btn-sm" href="{{ asset('upload/file_usulan/'.\Illuminate\Support\Str::slug($detail->ketua_peneliti_nama).'-'.$detail->ketua_peneliti_nip.'/'.$detail->file_usulan) }}" download="{{ $detail->file_usulan }}"><i class="fa fa-download"></i></a>

                                            {{-- <a href="{{ asset('storage/'.$detail->file_usulan) }}" download="{{ $detail->file_usulan }}" class="btn btn-primary btn-sm"><i class="fa fa-download"></i>&nbsp;Download Disini</a> --}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 20%">Nama Skim</th>
                                        <td style="width: 2%"> : </td>
                                        <td>{{ $detail->nm_skim }}</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 20%">Ringkasan Kegiatan</th>
                                        <td style="width: 2%"> : </td>
                                        <td>{!! $detail->abstrak !!}</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 20%">Kata Kunci</th>
                                        <td style="width: 2%"> : </td>
                                        <td>{!! $detail->kata_kunci !!}</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 20%">Tahun Usulan</th>
                                        <td style="width: 2%"> : </td>
                                        <td>{!! $detail->tahun_usulan !!}</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 20%">Tujuan Kegiatan</th>
                                        <td style="width: 2%"> : </td>
                                        <td>{!! $detail->tujuan !!}</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 20%">Luaran Kegiatan</th>
                                        <td style="width: 2%"> : </td>
                                        <td>{{ $detail->luaran }}</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 20%">Biaya Diusulkan</th>
                                        <td style="width: 2%"> : </td>
                                        <td>{{ $detail->biaya_diusulkan }}</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 20%">Nip Ketua Kegiatan</th>
                                        <td style="width: 2%"> : </td>
                                        <td>{{ $detail->ketua_peneliti_nip }}</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 20%">Nama Ketua Kegiatan</th>
                                        <td style="width: 2%"> : </td>
                                        <td>{{ $detail->ketua_peneliti_nama }}</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 20%">Diusulkan Pada</th>
                                        <td style="width: 2%"> : </td>
                                        <td>{{ $detail->created_at->diffForHumans() }} ({{ $detail->created_at->format('j F Y H:i') }})</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 20%">Status Usulan</th>
                                        <td style="width: 2%"> : </td>
                                        <td>
                                            @if ($detail->status_usulan == "0")
                                                <label class="badge badge-warning"><i class="fa fa-clock-o"></i>&nbsp; Belum Diusulkan</label>
                                                @elseif($detail->status_usulan == "1")
                                                <label class="badge badge-warning"><i class="fa fa-check"></i>&nbsp; Menunggu Verifikasi</label>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h6>Anggota Internal</h6>
                                    <div class="clearfix"></div>
                                    <div class="x_content mt-3">
                                        <table class="table table-hover">
                                            @php
                                                $no=1;
                                            @endphp
                                            @forelse ($anggota_internal as $anggota)
                                                <tr>
                                                    <th colspan="3" class="text-uppercase" style="background-color: #def0ff">{{ $no++ }}. {{ $anggota->anggota_nama }}</th>
                                                </tr>
                                                <tr>
                                                    <th style="width: 30%">Nip Anggota</th>
                                                    <td style="width: 2%"> : </td>
                                                    <td>{{ $anggota->anggota_nip }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 30%">Nama Anggota</th>
                                                    <td style="width: 2%"> : </td>
                                                    <td>{{ $anggota->anggota_nama }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 30%">Prodi Anggota</th>
                                                    <td style="width: 2%"> : </td>
                                                    <td>{{ $anggota->anggota_prodi_nama }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 30%">Fakultas Anggota</th>
                                                    <td style="width: 2%"> : </td>
                                                    <td>{{ $anggota->anggota_fakultas_nama }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3">Tidak ada anggota internal</td>
                                                </tr>
                                            @endforelse
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
        
                        <div class="col-md-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h6>Anggota Eksternal</h6>
                                    <div class="clearfix"></div>
                                    <div class="x_content mt-3">
                                        <table class="table table-hover">
                                            @php
                                                $no=1;
                                            @endphp
                                            @forelse ($anggota_eksternal as $anggota)
                                                <tr>
                                                    <th colspan="3" class="text-uppercase" style="background-color: #def0ff">{{ $no++ }}. {{ $anggota->anggota_nama }}</th>
                                                </tr>
                                                <tr>
                                                    <th style="width: 30%">Nip Anggota</th>
                                                    <td style="width: 2%"> : </td>
                                                    <td>{{ $anggota->anggota_nip }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 30%">Nama Anggota</th>
                                                    <td style="width: 2%"> : </td>
                                                    <td>{{ $anggota->anggota_nama }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 30%">Universitas</th>
                                                    <td style="width: 2%"> : </td>
                                                    <td>{{ $anggota->universitas }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3">Tidak ada anggota eksternal</td>
                                                </tr>
                                            @endforelse
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
        
                        <div class="col-md-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h6>Anggota Eksternal</h6>
                                    <div class="clearfix"></div>
                                    <div class="x_content mt-3">
                                        <table class="table table-hover">
                                            @php
                                                $no=1;
                                            @endphp
                                            @forelse ($anggota_mahasiswa as $anggota)
                                                <tr>
                                                    <th colspan="3" class="text-uppercase" style="background-color: #def0ff">{{ $no++ }}. {{ $anggota->anggota_nama }}</th>
                                                </tr>
                                                <tr>
                                                    <th style="width: 30%">NPM Anggota</th>
                                                    <td style="width: 2%"> : </td>
                                                    <td>{{ $anggota->anggota_npm }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 30%">Nama Anggota</th>
                                                    <td style="width: 2%"> : </td>
                                                    <td>{{ $anggota->anggota_nama }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 30%">Program Studi</th>
                                                    <td style="width: 2%"> : </td>
                                                    <td>{{ $anggota->anggota_prodi_nama }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 30%">Fakultas</th>
                                                    <td style="width: 2%"> : </td>
                                                    <td>{{ $anggota->anggota_fakultas_nama }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3">Tidak ada anggota eksternal</td>
                                                </tr>
                                            @endforelse
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
        
                        <div class="col-md-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h6>Anggota Eksternal</h6>
                                    <div class="clearfix"></div>
                                    <div class="x_content mt-3">
                                        <table class="table table-hover">
                                            @php
                                                $no=1;
                                            @endphp
                                            @forelse ($anggota_alumni as $anggota)
                                                <tr>
                                                    <th colspan="3" class="text-uppercase" style="background-color: #def0ff">{{ $no++ }}. {{ $anggota->anggota_nama }}</th>
                                                </tr>
                                                <tr>
                                                    <th style="width: 30%">Nama Anggota</th>
                                                    <td style="width: 2%"> : </td>
                                                    <td>{{ $anggota->anggota_nama }}</td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 30%">Jabatan</th>
                                                    <td style="width: 2%"> : </td>
                                                    <td>{{ $anggota->jabatan }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3">Tidak ada Staf Pendukung / Alumni Terlibat </td>
                                                </tr>
                                            @endforelse
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
