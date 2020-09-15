@push('styles')
    <style>
        .select2 {
width:100%!important;
}
    </style>
@endpush
<div class="col-md-12" >
    <hr style="width: 50%">
    <div class="alert alert-success alert-block" style="display:none;" id="sudah">
        
        <strong><i class="fa fa-check-circle"></i>&nbsp;Nip/Nik Portal Akademik (PAK) Dosen Berhasil Ditemukan  </strong>
    </div>
    <form action=" {{ route('pengusul.usulan.anggota_mahasiswa_post') }} " method="POST">
        {{ csrf_field() }} {{ method_field('POST') }}
        <input type="hidden" name="usulan_id" id="usulan_id" value="{{ $id_usulan }}">
        <div class="form-group col-md-6">
            <label for="exampleInputEmail1">Pilih Fakultas</label>
            <select name="fakultas" id="fakultas" class="form-control" id="">
                <option disabled selected>-- pilih fakultas --</option>
                @foreach ($fakultases['fakultas'] as $fakultas)
                    <option value="{{ $fakultas['fakKode'] }}">{{ $fakultas['fakNamaResmi'] }}</option>
                @endforeach
                @if ($errors->has('fakultas'))
                    <small class="form-text text-danger">{{ $errors->first('fakultas') }}</small>
                @endif
            </select>
        </div>
        <div class="form-group col-md-6">
            <label for="exampleInputEmail1">Pilih Program Study</label>
            <select name="prodi" id="prodi" class="form-control" id="">
                <option disabled selected>-- pilih program study --</option>
            </select>
            @if ($errors->has('prodi'))
                <small class="form-text text-danger">{{ $errors->first('prodi') }}</small>
            @endif
        </div>
        <div class="form-group col-md-6">
            <label for="exampleInputEmail1">Pilih Mahasiswa Terlibat</label>
            <select name="mahasiswa" id="mahasiswa" class="form-control js-example-basic-single" id="">
                <option disabled selected>-- pilih mahasiswa terlibat --</option>
            </select>
            @if ($errors->has('mahasiswa'))
                <small class="form-text text-danger">{{ $errors->first('mahasiswa') }}</small>
            @endif
        </div>
        <div class="col-md-12" style="text-align:center;">
            <a href=" {{ route('pengusul.usulan',[$id_usulan]) }} " class="btn btn-warning btn-sm" style="color:white;"><i class="fa fa-arrow-left"></i>&nbsp; Kembali</a>
            <button type="reset" class="btn btn-danger btn-sm" style="font-size:13px;" ><i class="fa fa-refresh"></i>&nbsp;Ulangi</button>
            <button type="submit" style="font-size:13px;" class="btn btn-primary btn-sm" id="btn-submit"><i class="fa fa-check-circle"></i>&nbsp;Tambah Anggota</button>
        </div>
    </form>
    <hr style="width:80%; text-align:center;">
</div>
<div class="col-md-12" style="margin-top:5px;">
    <table class="table table-bordered table-striped" id="table">
        <thead>
            <tr>
                <th style="text-align:center">No</th>
                <th style="text-align:center">Nip Anggota</th>
                <th style="text-align:center">Nama Anggota</th>
                <th style="text-align:center">Prodi Anggota</th>
                <th style="text-align:center">Fakultas Anggota</th>
                <th style="text-align:center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no=1;
            @endphp
            @foreach ($anggota_mahasiswas as $anggota)
                <tr>
                    <td style="text-align:center;"> {{ $no++ }} </td>
                    <td style="text-align:center"> {{ $anggota->anggota_npm }} </td>
                    <td style="text-align:center"> {{ $anggota->anggota_nama }} </td>
                    <td style="text-align:center"> {{ $anggota->anggota_prodi_nama }} </td>
                    <td style="text-align:center"> {{ $anggota->anggota_fakultas_nama }} </td>
                    <td style="text-align:center">
                        <a onclick="hapusMahasiswa({{ $anggota->id }})" class="btn btn-danger btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Modal Hapus -->
    <div class="modal modal-danger fade" id="modalhapusmahasiswa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header modal-header-danger">
                <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Konfirmasi Hapus Anggota</p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              Apakah anda yakin akan menghapus anggota ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-light btn-sm " style="color:white;" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;Close</button>
                <form method="POST" action="{{ route('pengusul.usulan.detail_anggota.hapus_mahasiswa') }}">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input type="hidden" name="id" id="id_anggota_mahasiswa">
                    <input type="hidden" name="id_usulan" value="{{ $id_usulan }}">
                    <button type="submit" class="btn btn-outline-light btn-sm" style="color:white;"><i class="fa fa-check-circle"></i>&nbsp; Ya, Hapus Data !</button>
                </form>
            </div>
          </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function(){
            $(document).on('change','#fakultas',function(){
                var fakultas = $(this).val();
                var div = $(this).parent().parent();
                var op=" ";
                $.ajax({
                type :'get',
                url: "{{ url('pengusul/manajemen_usulan/cari_prodi') }}",
                data:{'fakultas':fakultas},
                    success:function(data){
                        // alert(data['prodi'][0].prodiKodeUniv);
                        op+='<option value="0" selected disabled>-- pilih program study --</option>';
                        for(var i=0; i<data['prodi'].length;i++){
                            // alert(data['jenis_publikasi'][i].fakultas);
                            op+='<option value="'+data['prodi'][i].prodiKode+'">'+data['prodi'][i].prodiNamaResmi+'</option>';
                        }
                        div.find('#prodi').html(" ");
                        div.find('#prodi').append(op);
                    },
                        error:function(){
                    }
                });
            })

            $(document).on('change','#prodi',function(){
                var prodi = $(this).val();
                var div = $(this).parent().parent();
                var op=" ";
                $.ajax({
                type :'get',
                url: "{{ url('pengusul/manajemen_usulan/cari_mahasiswa') }}",
                data:{'prodi':prodi},
                    success:function(data){
                        // alert(data['prodi'][0]['dosen'][0]['pegawai'].pegIsAktif);
                        op+='<option value="0" selected disabled>-- pilih mahasiswa terlibat --</option>';
                        for(var i=0; i<data['prodi'][0]['mahasiswa'].length;i++){
                            op+='<option value="'+data['prodi'][0]['mahasiswa'][i].mhsNiu+'">'+data['prodi'][0]['mahasiswa'][i].mhsNama+'</option>';
                        }
                        div.find('#mahasiswa').html(" ");
                        div.find('#mahasiswa').append(op);
                    },
                        error:function(){
                    }
                });
            })
        });

        $('#angkatan').each(function() {

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

        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });

        function hapusMahasiswa(id){
            // alert(id);
            $('#modalhapusmahasiswa').modal('show');
            $('#id_anggota_mahasiswa').val(id);
        }
        
    </script>
@endpush