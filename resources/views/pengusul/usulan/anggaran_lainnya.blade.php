<div class="tab-pane fade" id="nav-lainnya" role="tabpanel" aria-labelledby="nav-lainnya-tab">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12" style="margin-bottom:10px; margin-top:10px; text-align:center;">
                <a href="{{ route('pengusul.usulan') }}" class="btn btn-danger btn-sm"><i class="fa fa-arrow-left" style="font-size:12px;"></i>&nbsp; Halaman Usulan</a>
                <a onclick="kelolaAnggaranLainnya({{ $id_usulan }})" class="btn btn-primary btn-sm" style="color:white;cursor:pointer;"><i class="fa fa-plus" style="font-size:12px;"></i>&nbsp; Tambah Anggaran</a>
                @if (isset($id_usulan))
                    @php
                        $id = $id_usulan;
                    @endphp
                @endif
            </div>
        </div>
        <div class="col-md-12">
            <form action=" {{ route('pengusul.usulan.anggaran_lainnya_post') }} " method="POST">
                {{ csrf_field() }} {{ method_field('POST') }}
                <input type="hidden" name="usulan_id_anggaran_lainnya" id="usulan_id_anggaran_lainnya">
                <div class="row" id="formanggaranlainnya" style="display:none;">
                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1">Material</label>
                        <input type="text" name="material_lainnya" class="form-control" required placeholder="masukan material">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1">Justifikasi Pembelian</label>
                        <input type="text" name="justifikasi_pembelian_lainnya" class="form-control" required placeholder="masukan justifikasi">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1">Kuantitas</label>
                        <input type="number" name="kuantitas_lainnya" class="form-control" required placeholder="masukan kuantitas">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1">Harga Satuan :</label>
                        <input type="number" name="harga_satuan_lainnya" class="form-control" required placeholder="masukan harga satuan">
                    </div>
                    <div class="col-md-12" style="text-align:center;">
                        <button class="btn btn-warning btn-sm" style="font-size:13px; color:white;"><i class="fa fa-close"></i>&nbsp; Batalkan</button>
                        <button type="reset" class="btn btn-danger btn-sm" style="font-size:13px;" ><i class="fa fa-refresh"></i>&nbsp;Reset</button>
                        <button type="submit" style="font-size:13px;" class="btn btn-primary btn-sm" id="btn-submit"><i class="fa fa-save"></i>&nbsp;Simpan</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-12">
            <table class="table table-bordered" style="width:100%" id="table">
                <thead>
                    <tr>
                        <th colspan="7">Rancangan Anggaran Lain-Lain</th>
                    </tr>
                    <tr>
                        <th>No</th>
                        <th>Material</th>
                        <th>Justifikasi Pembelian</th>
                        <th>Kuantitias</th>
                        <th>Harga Satuan</th>
                        <th>Honor Per Tahun</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($lainnyas) >0)
                        @if ($lainnyas[0]->id == null)
                            <tr>
                                <td style="color:red; text-align:center;" colspan="6"><a><i>tabel Lain-lain masih kosong</i></a></td>
                            </tr>
                            @else
                            @php
                                $no=1;
                                $sub4 = 0;
                            @endphp
                            @foreach ($lainnyas as $value)
                                @php
                                    $total = $value->kuantitas * $value->harga_satuan;
                                @endphp
                                <tr>
                                    <td> {{ $no++ }} </td>
                                    <td> {{ $value->material }} </td>
                                    <td> {{ $value->justifikasi_pembelian }} </td>
                                    <td> {{ $value->kuantitas }} </td>
                                    <td> Rp. {{ number_format($value->harga_satuan, 2) }} </td>
                                    <td> Rp.{{ number_format($total, 2) }}</td>
                                    <td>
                                        <a onclick="hapusLainnya({{ $value->id }})" class="btn btn-danger btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                @php
                                    $sub4 += $total;
                                @endphp
                            @endforeach
                            <tr>
                                <th colspan="5" style="text-align:center;">Subtotal :</th>
                                <th colspan="2">
                                    Rp. {{ number_format($sub4, 2) }}
                                </th>
                            </tr>
                        @endif
                        @else
                        <tr>
                            <td style="color:red; text-align:center;" colspan="6"><a><i>tabel Lain-lain masih kosong</i></a></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <!-- Modal Hapus -->
        <div class="modal modal-danger fade" id="modalhapuslainnya" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-header-danger">
                    <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Konfirmasi Hapus Data Anggaran Perjalanan Lainnya</p>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                Apakah anda yakin akan menghapus data anggaran ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-light btn-sm " style="color:white;" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;Close</button>
                    <form method="POST" action="{{ route('pengusul.usulan.detail_anggaran.lainnya_hapus') }}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <input type="hidden" name="id_anggaran_lainnya" id="id_anggaran_lainnya">
                        <input type="hidden" name="id_usulan" value="{{ $id_usulan }}">
                        <button type="submit" class="btn btn-outline-light btn-sm" style="color:white;"><i class="fa fa-check-circle"></i>&nbsp; Ya, Hapus Data !</button>
                    </form>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function hapusLainnya(id){
            $('#modalhapuslainnya').modal('show');
            $('#id_anggaran_lainnya').val(id);
        }
    </script>
@endpush
