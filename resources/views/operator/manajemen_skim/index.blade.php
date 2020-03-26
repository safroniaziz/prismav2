@extends('layouts.layout')
@section('title', 'Skim Penelitian')
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
                    @endif
                    <div class="alert alert-danger alert-block" id="keterangan">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah semua skim anda yang tersedia, silahkan tambahkan skim beserta tahun berlakunya jika diperlukan !!
                    </div>
                </div>
                <div class="col-md-12">
                    <a onclick="tambahSkim()" style="color:white; cursor:pointer;" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i>&nbsp; Tambah Skim</a>
                </div>

                <div class="col-md-12" style="display:none;" id="form-skim">
                    <hr style="width:50% !important;">
                    <form action="" method="POST" action=" {{ route('operator.skim.add') }} ">
                        {{ csrf_field() }} {{ method_field('POST') }}
                        <div class="form-group col-md-4">
                            <label for="exampleInputEmail1">Nama Skim</label>
                            <input type="text" name="nm_skim" id="nm_skim" class="form-control" placeholder="masukan nama skim" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="exampleInputEmail1">Nama Unit</label>
                            <input type="text" name="nm_unit" id="nm_unit" class="form-control" placeholder="masukan nama unit" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="exampleInputEmail1">Tahun Berlaku</label>
                            <select name="tahun" id="tahun" class="form-control" required></select>
                        </div>
                        <div class="col-md-12" style="text-align:center;">
                            <button type="reset" class="btn btn-danger btn-sm"><i class="fa fa-refresh"></i>&nbsp; Ulangi</button>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i>&nbsp; Simpan Skim</button>
                        </div>
                    </form>
                    <hr style="width:50%;">
                </div>

                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Skim</th>
                                <th>Nama Unit</th>
                                <th>Tahun</th>
                                <th>Aksi</th>
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
                                    <td> {{ $skim->nm_unit }} </td>
                                    <td>
                                        {{ $skim->tahun }}
                                    </td>
                                    <td>
                                        <a onclick="hapusSkim( {{ $skim->id }} )" style="color:white; cursor:pointer;" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Modal Hapus -->
                    <div class="modal modal-danger fade" id="modalhapus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header modal-header-danger">
                                <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Konfirmasi Hapus Data Skim</p>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            Apakah anda yakin akan menghapus skim ?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-light btn-sm " style="color:white;" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;Close</button>
                                <form method="POST" action="{{ route('operator.skim.delete') }}">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <input type="hidden" name="id" id="id_hapus">
                                    <button type="submit" class="btn btn-outline-light btn-sm" style="color:white;"><i class="fa fa-check-circle"></i>&nbsp; Ya, Hapus Data !</button>
                                </form>
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
                responsive: true,
            });
        } );

        $('#tahun').each(function() {

            var year = (new Date()).getFullYear();
            var current = year;
            year -= 3;
            for (var i = 0; i < 6; i++) {
            if ((year+i) == current)
                $(this).append('<option selected value="' + (year + i) + '">' + (year + i) + '</option>');
            else
                $(this).append('<option value="' + (year + i) + '">' + (year + i) + '</option>');
            }

        });

        function tambahSkim(){
            $("#form-skim").show(200);
        }

        function hapusSkim(id){
            $('#modalhapus').modal('show');
            $('#id_hapus').val(id);
        }
    </script>
@endpush
