<table class="table table-bordered" style="width:100%" id="table">
    <thead>
        <tr>
            <th colspan="7">Rancangan Anggaran Honor Output Kegiatan</th>
        </tr>
        <tr>
            <th>No</th>
            <th>Honor</th>
            <th>Honor/Hari</th>
            <th>Waktu (Hari/Minggu)</th>
            <th>Minggu</th>
            <th>Honor Per Tahun</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @if (count($outputs) > 0)
            @if ($outputs[0]->id == null)
                <tr>
                    <td style="color:red; text-align:center;" colspan="6"><a><i>tabel honor output kegiatan masih kosong</i></a></td>
                </tr>
                @else
                @php
                    $no=1;
                    $sub1 = 0;
                @endphp
                @foreach ($outputs as $output)
                    @php
                        $total = $output->hari_per_minggu * $output->jumlah_minggu * $output->biaya;
                    @endphp
                    <tr>
                        <td> {{ $no++ }} </td>
                        <td> {{ $output->keterangan_honor }} </td>
                        <td> Rp. {{ number_format($output->biaya, 2) }} </td>
                        <td> {{ $output->hari_per_minggu }} </td>
                        <td> {{ $output->jumlah_minggu }} </td>
                        <td> Rp. {{ number_format($total, 2)   }}</td>
                        <td>
                            <a onclick="hapusOutput({{ $output->id }})" class="btn btn-danger btn-sm" style="color:white; cursor:pointer;"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                    @php
                        $sub1 += $total;
                    @endphp
                @endforeach
                <tr>

                    <th colspan="5" style="text-align:center;">Subtotal :</th>
                    <th colspan="2">
                        Rp. {{ number_format($sub1, 2) }}
                    </th>
                </tr>
            @endif
            @else
            <tr>
                <td style="color:red; text-align:center;" colspan="6"><a><i>tabel honor output kegiatan masih kosong</i></a></td>
            </tr>
        @endif
    </tbody>
</table>
<!-- Modal Hapus -->
<div class="modal modal-danger fade" id="modalhapus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header modal-header-danger">
            <p style="font-size:15px;" class="modal-title" id="exampleModalLabel"><i class="fa fa-user"></i>&nbsp;Form Konfirmasi Hapus Data Anggaran Honor Output Kegiatan</p>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
          Apakah anda yakin akan menghapus data anggaran ?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline-light btn-sm " style="color:white;" data-dismiss="modal"><i class="fa fa-close"></i>&nbsp;Close</button>
            <form method="POST" action="{{ route('pengusul.usulan.detail_anggaran.honor_hapus') }}">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <input type="hidden" name="id_anggaran" id="id_anggaran">
                <input type="hidden" name="id_usulan" value="{{ $id_usulan }}">
                <button type="submit" class="btn btn-outline-light btn-sm" style="color:white;"><i class="fa fa-check-circle"></i>&nbsp; Ya, Hapus Data !</button>
            </form>
        </div>
      </div>
    </div>
</div>

@push('scripts')
    <script>
        function hapusOutput(id){
            $('#modalhapus').modal('show');
            $('#id_anggaran').val(id);
        }
    </script>
@endpush
