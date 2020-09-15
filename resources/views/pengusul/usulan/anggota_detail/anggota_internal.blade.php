<div class="col-md-12" >
    <hr style="width: 50%">
    <div class="alert alert-success alert-block" style="display:none;" id="sudah">
        
        <strong><i class="fa fa-check-circle"></i>&nbsp;Nip/Nik Portal Akademik (PAK) Dosen Berhasil Ditemukan  </strong>
    </div>
    <form action=" {{ route('pengusul.usulan.anggota_post') }} " method="POST">
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
            <label for="exampleInputEmail1">Pilih Anggota Kegiatan</label>
            <select name="anggota" id="anggota" class="form-control" id="">
                <option disabled selected>-- pilih anggota kegiatan --</option>
            </select>
            @if ($errors->has('anggota'))
                <small class="form-text text-danger">{{ $errors->first('anggota') }}</small>
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
            @foreach ($anggotas as $anggota)
                <tr>
                    <td style="text-align:center;"> {{ $no++ }} </td>
                    <td style="text-align:center"> {{ $anggota->anggota_nip }} </td>
                    <td style="text-align:center"> {{ $anggota->anggota_nama }} </td>
                    <td style="text-align:center"> {{ $anggota->anggota_prodi_nama }} </td>
                    <td style="text-align:center"> {{ $anggota->anggota_fakultas_nama }} </td>
                    <td style="text-align:center">
                        <a onclick="hapusAnggota({{ $anggota->id }})" class="btn btn-danger btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
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
                url: "{{ url('pengusul/manajemen_usulan/cari_anggota') }}",
                data:{'prodi':prodi},
                    success:function(data){
                        // alert(data['prodi'][0]['dosen'][0]['pegawai'].pegIsAktif);
                        op+='<option value="0" selected disabled>-- pilih anggota kegiatan --</option>';
                        for(var i=0; i<data['prodi'][0]['dosen'].length;i++){
                            // alert(data['jenis_publikasi'][i].prodi);
                            if (data['prodi'][0]['dosen'][i]['pegawai'].pegIsAktif == 1) {
                                op+='<option value="'+data['prodi'][0]['dosen'][i]['pegawai'].pegNip+'">'+data['prodi'][0]['dosen'][i]['pegawai'].pegNama+'</option>';
                            }
                        }
                        div.find('#anggota').html(" ");
                        div.find('#anggota').append(op);
                    },
                        error:function(){
                    }
                });
            })
        });
        
    </script>
@endpush