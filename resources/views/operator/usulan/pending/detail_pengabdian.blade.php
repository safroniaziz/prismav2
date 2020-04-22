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
<p style="text-align:center">CETAK USULAN KEGIATAN PENGABDIAN PADA MASYARAKAT PENDING</p>
<table style="width:100%; text-align:center;" border="1">
    <thead>
        <th>No</th>
        <th>Judul Pengabdian</th>
        <th>Tahun Pengabdian</th>
        <th>Jenis Pengabdian</th>
        <th>Ketua Pengabdian</th>
        <th>Kaca Kunci</th>
        <th>Biaya Diusulkan</th>
        <th>Anggota Pengabdian</th>
        <th>Status Usulan</th>
    </thead>
    <tbody>
        @php
            $no =1;
        @endphp
        @foreach ($pengabdians as $pengabdian)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $pengabdian->judul_kegiatan }}</td>
                <td>{{ $pengabdian->tahun_usulan }}</td>
                <td>{{ $pengabdian->jenis_kegiatan }}</td>
                <td>{{ $pengabdian->nm_ketua_peneliti }}</td>
                <td>{{ $pengabdian->kata_kunci }}</td>
                <td>{{ $pengabdian->biaya_diusulkan }}</td>
                <td>
                    @if ($pengabdian->nm_anggota == null)
                        -
                        @else
                        {!! $pengabdian->nm_anggota !!}
                    @endif
                </td>
                <td>
                    @if ($pengabdian->status_usulan == "0")
                        <label for="">Usulan Pending</label>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
