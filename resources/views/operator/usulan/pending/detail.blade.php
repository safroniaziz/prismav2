<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        text-align: center;
        padding: 8px;
    }

    tr:nth-child(even) {background-color: #f2f2f2;}
</style>
<p style="text-align:center">CETAK USULAN KEGIATAN PENELITIAN PENDING</p>
<table style="width:100%; text-align:center;" border="1">
    <thead>
        <th>No</th>
        <th>Judul Penelitian</th>
        <th>Tahun Penelitian</th>
        <th>Jenis Penelitian</th>
        <th>Ketua Penelitian</th>
        <th>Kaca Kunci</th>
        <th>Biaya Diusulkan</th>
        <th>Anggota Penelitian</th>
        <th>Status Usulan</th>
    </thead>
    <tbody>
        @php
            $no =1;
        @endphp
        @foreach ($penelitians as $penelitian)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $penelitian->judul_kegiatan }}</td>
                <td>{{ $penelitian->tahun_usulan }}</td>
                <td>{{ $penelitian->jenis_kegiatan }}</td>
                <td>{{ $penelitian->nm_ketua_peneliti }}</td>
                <td>{{ $penelitian->kata_kunci }}</td>
                <td>{{ $penelitian->biaya_diusulkan }}</td>
                <td>
                    @if ($penelitian->nm_anggota == null)
                        -
                        @else
                        {!! $penelitian->nm_anggota !!}
                    @endif
                </td>
                <td>
                    @if ($penelitian->status_usulan == "0")
                        <label for="">Usulan Pending</label>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
