<div class="col-md-12" >
    <hr style="width: 50%">
    <form action=" {{ route('pengusul.usulan.anggota_alumni_post') }} " method="POST">
        {{ csrf_field() }} {{ method_field('POST') }}
        <input type="hidden" name="usulan_id" id="usulan_id" value="{{ $id_usulan }}">
        <div class="form-group col-md-6">
            <label for="exampleInputEmail1">Nama Anggota</label>
            <input type="text" name="nama" value="{{ old('nama') }}" class="form-control">
            @if ($errors->has('nama'))
                <small class="form-text text-danger">{{ $errors->first('nama') }}</small>
            @endif
        </div>
        
        <div class="form-group col-md-6">
            <label for="exampleInputEmail1">Pilih Jabatan</label>
            <select name="jabatan" id="jabatan" class="form-control" id="">
                <option disabled selected>-- pilih jabatan --</option>
                <option value="laboran">Laboran</option>
                <option value="teknisi">Teknisi</option>
                <option value="administrasi">Administrasi</option>
                <option value="alumni">Alumni</option>
            </select>
            @if ($errors->has('jabatan'))
                <small class="form-text text-danger">{{ $errors->first('jabatan') }}</small>
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
                <th style="text-align:center">Nama Anggota</th>
                <th style="text-align:center">Jabatan</th>
                <th style="text-align:center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no=1;
            @endphp
            @foreach ($anggota_alumnis as $anggota)
                <tr>
                    <td style="text-align:center;"> {{ $no++ }} </td>
                    <td style="text-align:center"> {{ $anggota->anggota_nama }} </td>
                    <td style="text-align:center"> {{ $anggota->jabatan }} </td>
                    <td style="text-align:center">
                        <a onclick="hapusAlumni({{ $anggota->id }})" class="btn btn-danger btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Modal Hapus -->
    <div class="modal modal-danger fade" id="modalhapusalumni" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header modal-header-danger">
                <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Konfirmasi Hapus Data Anggota</p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              Apakah anda yakin akan menghapus anggota kegiatan ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-light btn-sm " style="color:white;" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;Close</button>
                <form method="POST" action="{{ route('pengusul.usulan.detail_anggota.hapus_alumni') }}">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input type="hidden" name="id" id="id_anggota_alumni">
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
        function hapusAlumni(id){
            $('#modalhapusalumni').modal('show');
            $('#id_anggota_alumni').val(id);
        }
    </script>
@endpush