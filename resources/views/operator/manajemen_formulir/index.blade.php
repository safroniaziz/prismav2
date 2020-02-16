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
            <i class="fa fa-home"></i>&nbsp;Manajemen Variabel Penilaian Penelitian
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
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah semua variabel penilaian yang tersedia, silahkan tambahkan variabel penilaian baru jika diperlukan !!
                        </div>
                    @endif
                </div>
                <div class="col-md-12" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal">
                        <i class="fa fa-plus" style="font-size:12px;"></i>&nbsp;Tambah Variabel Penilaian
                    </button>
                </div>
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Variabel</th>
                                <th>Deskripsi</th>
                                <th>Persentase Nilai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no=1;
                            @endphp
                            @foreach ($formulirs as $formulir)
                                <tr>
                                    <td> {{ $no++ }} </td>
                                    <td> {{ $formulir->variabel }} </td>
                                    <td> {{ $formulir->deskripsi }} </td>
                                    <td> {{ $formulir->persentase }} </td>
                                    <td>
                                        <a onclick="ubahFormulir({{ $formulir->id }})" class="btn btn-primary btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-edit"></i></a>
                                        <a onclick="hapusFormulir({{ $formulir->id }})" class="btn btn-danger btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-trash"></i></a>
                                    </td>
                                    <!-- Modal Ubah -->
                                    <div class="modal fade" id="modalubah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Ubah Data Variabel Penilaian</p>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action=" {{ route('operator.variabel_penilaian.update') }} " method="POST" enctype="multipart/form-data">
                                                {{ csrf_field() }} {{ method_field('PATCH') }}
                                                            <div class="modal-body">
                                                                <input type="hidden" name="id" id="id">
                                                                <div class="form-group col-md-12">
                                                                    <label>Variabel Penilaian</label>
                                                                    <input type="text" name="variabel" id="variabel" class="form-control" required placeholder="masukan variabel">
                                                                </div>

                                                                <div class="form-group col-md-12">
                                                                    <label>Deskripsi</label>
                                                                    <textarea name="deskripsi" id="deskripsi" class="form-control" cols="30" rows="3" placeholder="masukan deskripsi"></textarea>
                                                                </div>

                                                                <div class="form-group col-md-12">
                                                                    <label>Persentase Nilai <a style="color:red;"><i>hanya angka</i></a></label>
                                                                    <input type="number" name="persentase" id="persentase" class="form-control" required placeholder="masukan persentase">
                                                                </div>
                                                            </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;Batalkan</button>
                                                        <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i>&nbsp;Simpan Perubahan</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </tr>
                            @endforeach
                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Tambah Variabel Penilaian Baru</p>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action=" {{ route('operator.variabel_penilaian.add') }} " method="POST" enctype="multipart/form-data">
                                            {{ csrf_field() }} {{ method_field('POST') }}
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <label>Variabel Penilaian</label>
                                                            <input type="text" name="variabel" class="form-control" required placeholder="masukan variabel">
                                                        </div>

                                                        <div class="form-group col-md-12">
                                                            <label>Deskripsi</label>
                                                            <textarea name="deskripsi" class="form-control" id="" cols="30" rows="3" placeholder="masukan deskripsi"></textarea>
                                                        </div>

                                                        <div class="form-group col-md-12">
                                                            <label>Persentase Nilai <a style="color:red;"><i>hanya angka</i></a></label>
                                                            <input type="number" name="persentase" class="form-control" required placeholder="masukan persentase">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;Batalkan</button>
                                                    <button type="submit" class="btn btn-primary" id="btn-submit"><i class="fa fa-save"></i>&nbsp;Simpan</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </tbody>
                    </table>
                    <div class="modal modal-danger fade" id="modalhapus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header modal-header-danger">
                                <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Konfirmasi Hapus Data Operator</p>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                              Apakah anda yakin akan menghapus data Variabel Penilaian ?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-light btn-sm " style="color:white;" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;Close</button>
                                <form method="POST" action="{{ route('operator.variabel_penilaian.delete') }}">
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

        function ubahFormulir(id){
            $.ajax({
                url: "{{ url('operator/variabel_penilaian') }}"+'/'+ id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    $('#modalubah').modal('show');
                    $('#id').val(data.id);
                    $('#variabel').val(data.variabel);
                    $('#deskripsi').val(data.deskripsi);
                    $('#persentase').val(data.persentase);
                },
                error:function(){
                    alert("Nothing Data");
                }
            });
        }

        function hapusFormulir(id){
            $('#modalhapus').modal('show');
            $('#id_hapus').val(id);
        }
    </script>
@endpush
