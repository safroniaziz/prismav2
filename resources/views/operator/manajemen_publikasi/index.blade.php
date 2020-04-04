@extends('layouts.layout')
@section('title', 'Jenis Publikasi')
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
            <i class="fa fa-home"></i>&nbsp;Manajemen Jenis Publikasi
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
                        <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah semua Jenis Publikasi anda yang tersedia, silahkan tambahkan Jenis Publikasi beserta tahun berlakunya jika diperlukan !!
                    </div>
                </div>
                <div class="col-md-12">
                    <a onclick="tambahPublikasi()" style="color:white; cursor:pointer;" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i>&nbsp; Tambah Jenis Publikasi</a>
                </div>

                <div class="col-md-12" style="display:none;" id="form-publikasi">
                    <hr style="width:50% !important;">
                    <form action="" method="POST" action=" {{ route('operator.publikasi.add') }} ">
                        {{ csrf_field() }} {{ method_field('POST') }}
                        <div class="form-group col-md-6">
                            <label for="exampleInputEmail1">Jenis Kegiatan</label>
                            <select name="jenis_kegiatan" id="" class="form-control" required>
                                <option value="" selected disabled>-- pilih jenis kegiatan --</option>
                                <option value="penelitian">Penelitian</option>
                                <option value="pengabdiam">Penelitian Pada Masyarakat (PPM)</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="exampleInputEmail1">Jenis Publikasi</label>
                            <input type="text" name="jenis_publikasi" id="jenis_publikasi" class="form-control" placeholder="masukan jenis publikasi" required>
                        </div>
                        <div class="col-md-12" style="text-align:center;">
                            <button type="reset" class="btn btn-danger btn-sm"><i class="fa fa-refresh"></i>&nbsp; Ulangi</button>
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i>&nbsp; Simpan Jenis Publikasi</button>
                        </div>
                    </form>
                    <hr style="width:50%;">
                </div>

                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Jenis Kegiatan</th>
                                <th>Jenis Publikasi</th>
                                <th>Status</th>
                                <th>Ubah Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no=1;
                            @endphp
                            @foreach ($publikasis as $publikasi)
                                <tr>
                                    <td> {{ $no++ }} </td>
                                    <td> {{ $publikasi->jenis_kegiatan }} </td>
                                    <td> {{ $publikasi->jenis_publikasi }} </td>
                                    <td>
                                        @if ($publikasi->status == "1")
                                            <label for="" class="badge badge-success"><i class="fa fa-check-circle"></i>&nbsp; aktif</label>
                                            @else
                                            <label for="" class="badge badge-danger"><i class="fa fa-close"></i>&nbsp; tidak aktif</label>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($publikasi->status == "1")
                                            <a onclick="nonAktifkanStatus( {{ $publikasi->id }} )" class="btn btn-danger btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-thumbs-down"></i></a>
                                            @else
                                            <a onclick="aktifkanStatus( {{ $publikasi->id }} )" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-thumbs-up"></i></a>
                                        @endif
                                    <td>
                                        <a onclick="hapusPublikasi( {{ $publikasi->id }} )" style="color:white; cursor:pointer;" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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
                                <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Konfirmasi Hapus Data Jenis Publikasi</p>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            Apakah anda yakin akan menghapus Jenis Publikasi ?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-light btn-sm " style="color:white;" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;Close</button>
                                <form method="POST" action="{{ route('operator.publikasi.delete') }}">
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

        function tambahPublikasi(){
            $("#form-publikasi").show(200);
        }

        function hapusPublikasi (id){
            $('#modalhapus').modal('show');
            $('#id_hapus').val(id);
        }

        function aktifkanStatus(id){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            url = "{{ url('operator/jenis_publikasi/aktifkan_status').'/' }}"+id;
            $.ajax({
                url : url,
                type : 'PATCH',
                success : function($data){
                    $('#berhasil').show(100);
                    location.reload();
                },
                error:function(){
                    $('#gagal').show(100);
                }
            });
            return false;
        }

        function nonAktifkanStatus(id){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            url = "{{ url('operator/jenis_publikasi/non_aktifkan_status').'/' }}"+id;
            $.ajax({
                url : url,
                type : 'PATCH',
                success : function($data){
                    $('#berhasil').show(100);
                    location.reload();
                },
                error:function(){
                    $('#gagal').show(100);
                }
            });
            return false;
        }
    </script>
@endpush
