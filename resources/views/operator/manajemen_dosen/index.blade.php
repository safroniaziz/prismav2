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
            <i class="fa fa-user"></i>&nbsp;Manajemen Dosen
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
                            <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Berikut adalah semua data dosen yang diambil dari pangkalan data universitas bengkulu, silahkan klik <b>update data</b> untuk memperbarui data dosen !!
                        </div>
                    @endif
                    <div class="alert alert-warning alert-block" id="proses" style="display:none;">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong><i class="fa fa-info-circle"></i>&nbsp;Perhatian: </strong> Proses update membutuhkan beberapa waktu, harap tunggu !
                    </div>
                </div>
                <div class="col-md-12">
                    <a href="{{ route('operator.get_data_dosen') }}" class="btn btn-primary  btn-flat" onclick="updatePortal()" style="font-size:12px;"  id="update"><i class="fa fa-refresh"></i>&nbsp;Update Data</a>
                    <a class="btn btn-warning  btn-flat"  style=" display:none;color:white; font-size:12px;" id="tunggu"><i class="fa fa-cog fa-spin"></i>&nbsp;Harap Tunggu</a>
                </div>
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Dosen</th>
                                <th>NIP</th>
                                <th>NIDN</th>
                                <th>Pangkat</th>
                                <th>Golongan</th>
                                <th>Jenis Kelamin</th>
                                <th>Prodi</th>
                                <th>Fakultas</th>
                                <th>Telephone</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        $('#table').DataTable({
            serverside: true,
            responsive: true,
            ajax: "{{ route('operator.dosen_api') }}",
            columns: [
                {data: 'rownum',name:'rownum'},
                {data: 'nm_lengkap',name:'nm_lengkap'},
                {data: 'nip',name:'nip'},
                {data: 'nidn',
                        render:function(data){
                            if(data == null || data == "")
                            {
                                return '<label class="badge badge-danger" style="font-size:12px; padding:5px;">'+'-'+'</label>';
                            }
                            else
                            {
                                return data;
                            }
                        }
                },
                {data: 'pangkat',
                        render:function(data){
                            if(data == null)
                            {
                                return '<label class="badge badge-danger" style="font-size:12px; padding:5px;">'+'-'+'</label>';
                            }
                            else
                            {
                                return data;
                            }
                        }
                },
                {data: 'golongan',
                        render:function(data){
                            if(data == null)
                            {
                                return '<label class="badge badge-danger" style="font-size:12px; padding:5px;">'+'-'+'</label>';
                            }
                            else
                            {
                                return data;
                            }
                        }
                },
                {data: 'jenis_kelamin',
                        render:function(data){
                            if(data == "L")
                            {
                                return '<label class="badge badge-primary" style="font-size:12px;">'+'<i class="fa fa-male"></i>'+'&nbsp;Laki-Laki'+'</label>';
                            }
                            else if(data =="P")
                            {
                                return '<label class="badge badge-warning" style="font-size:12px;">'+'<i class="fa fa-female"></i>'+'&nbsp;Perempuan'+'</label>';
                            }
                            else{
                                return '<label class="badge badge-danger" style="font-size:12px; padding:5px;">'+'-'+'</label>';
                            }
                        }
                },
                {data: 'prodi',name:'prodi'},
                {data: 'fakultas',name:'fakultas'},
                {data: 'telephone',
                        render:function(data){
                            if(data == null)
                            {
                                return '<label class="badge badge-danger" style="font-size:12px; padding:5px;">'+'-'+'</label>';
                            }
                            else
                            {
                                return data;
                            }
                        }
                },
                {data: 'email',
                        render:function(data){
                            if(data == null)
                            {
                                return '<label class="badge badge-danger" style="font-size:12px; padding:5px;">'+'-'+'</label>';
                            }
                            else
                            {
                                return data;
                            }
                        }
                },
            ],

        });
        function updatePortal(){
            $('#tunggu').show();
            $('#update').hide();
            $('#keterangan').hide();
            $('#proses').show();
            $('#berhasil').hide();
            $('#table-admin-manajemen-mahasiswa').hide();
        }
    </script>
@endpush
