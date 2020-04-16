<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Prisma Unib v2 | Cetak Rancangan Anggaran</title>

    <style>
        #table {
          font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
          border-collapse: collapse;
          width: 100%;
          font-size: 13px;
        }

        #table td, #table th {
          padding: 8px;
        }

        #table tr:nth-child(even){background-color: #f2f2f2;}

        #table tr:hover {background-color: #ddd;}

        #table th {
          padding-top: 5px;
          padding-bottom: 5px;
          text-align: left;
        }
    </style>
</head>
<body>
    <p style="text-align:center;font-size:17px !important; font-weight:bold;"> RANCANGAN ANGGARAN BIAYA DAN JUSTIFIKASI ANGGARAN</p>
    <table class="table table-bordered" style="width:100%" id="table">
        <thead>
            <tr>
                <th colspan="6">1. Honorarium</th>
            </tr>
            <tr>
                <th>No</th>
                <th>Honor</th>
                <th>Honor/Hari</th>
                <th>Waktu (Hari/Minggu)</th>
                <th>Minggu</th>
                <th>Honor Per Tahun</th>
            </tr>
        </thead>
        <tbody>
            @if (count($outputs) > 0)
                @if ($outputs[0]->id == null)
                    <tr>
                        <td style="color:red; text-align:center;" colspan="6"><a><i>tabel Honorarium masih kosong</i></a></td>
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
                        </tr>
                        @php
                            $sub1 += $total;
                        @endphp
                    @endforeach
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Subtotal :</th>
                        <th>
                            Rp. {{ number_format($sub1, 2) }}
                        </th>
                    </tr>
                @endif
                @else
                <tr>
                    <td style="color:red; text-align:center;" colspan="6"><a><i>tabel Honorarium masih kosong</i></a></td>
                </tr>
            @endif
        </tbody>
    </table>
    <br>
    <table class="table table-bordered" style="width:100%" id="table">
        <thead>
            <tr>
                <th colspan="6">2. Belanja Bahan Habis Pakai</th>
            </tr>
            <tr>
                <th>No</th>
                <th>Material</th>
                <th>Justifikasi Pembelian</th>
                <th>Kuantitias</th>
                <th>Harga Satuan</th>
                <th>Honor Per Tahun</th>
            </tr>
        </thead>
        <tbody>
            @if (count($habis_pakais) > 0)
                @if ($habis_pakais[0]->id == null)
                    <tr>
                        <td style="color:red; text-align:center;" colspan="6"><a><i>tabel belanja habis pakai masih kosong</i></a></td>
                    </tr>
                    @else
                    @php
                        $no=1;
                        $sub2 = 0;
                    @endphp
                    @foreach ($habis_pakais as $value)
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
                        </tr>
                        @php
                            $sub2 += $total;
                        @endphp
                    @endforeach
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Subtotal :</th>
                        <th>
                            Rp. {{ number_format($sub2, 2) }}
                        </th>
                    </tr>
                @endif
                @else
                <tr>
                    <td style="color:red; text-align:center;" colspan="6"><a><i>tabel belanja habis pakai masih kosong</i></a></td>
                </tr>
            @endif
        </tbody>
    </table>

    <br>
    <table class="table table-bordered" style="width:100%" id="table">
        <thead>
            <tr>
                <th colspan="6">3. Perjalanan</th>
            </tr>
            <tr>
                <th>No</th>
                <th>Material</th>
                <th>Justifikasi Pembelian</th>
                <th>Kuantitias</th>
                <th>Harga Satuan</th>
                <th>Honor Per Tahun</th>
            </tr>
        </thead>
        <tbody>
            @if (count($penunjangs) >0)
                @if ($penunjangs[0]->id == null)
                    <tr>
                        <td style="color:red; text-align:center;" colspan="6"><a><i>tabel Perjalanan masih kosong</i></a></td>
                    </tr>
                    @else
                    @php
                        $no=1;
                        $sub3 = 0;
                    @endphp
                    @foreach ($penunjangs as $value)
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
                        </tr>
                        @php
                            $sub3 += $total;
                        @endphp
                    @endforeach
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Subtotal :</th>
                        <th>
                            Rp. {{ number_format($sub3, 2) }}
                        </th>
                    </tr>
                @endif
                @else
                <tr>
                    <td style="color:red; text-align:center;" colspan="6"><a><i>tabel Perjalanan masih kosong</i></a></td>
                </tr>
            @endif
        </tbody>
    </table>

    <br>
    <table class="table table-bordered" style="width:100%" id="table">
        <thead>
            <tr>
                <th colspan="6">4. Lain-lain</th>
            </tr>
            <tr>
                <th>No</th>
                <th>Material</th>
                <th>Justifikasi Pembelian</th>
                <th>Kuantitias</th>
                <th>Harga Satuan</th>
                <th>Honor Per Tahun</th>
            </tr>
        </thead>
        <tbody>
            @if (count($lainnya) > 0)
                @if ($lainnya[0]->id == null)
                    <tr>
                        <td style="color:red; text-align:center;" colspan="6"><a><i>tabel Lain-lain masih kosong</i></a></td>
                    </tr>
                    @else
                    @php
                        $no=1;
                        $sub4 = 0;
                    @endphp
                    @foreach ($lainnya as $value)
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
                        </tr>
                        @php
                            $sub4 += $total;
                        @endphp
                    @endforeach
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Subtotal :</th>
                        <th>
                            Rp. {{ number_format($sub4, 2) }}
                        </th>
                    </tr>
                @endif
                @else
                <tr>
                    <td style="color:red; text-align:center;" colspan="6"><a><i>tabel Lain-lain masih kosong</i></a></td>
                </tr>
            @endif
            <tr>
                <td></td>
            </tr>
            <tr>
                <th colspan="5">Total Anggaran Yang Diperlukan Setiap Tahun :</th>
                @if (!empty($sub1) && !empty($sub2) && !empty($sub3) && !empty($sub4))
                    <th> Rp. {{ number_format($sub1 + $sub2 + $sub3 + $sub4, 2) }}</th>
                @endif
            </tr>
        </tbody>
    </table>

</body>
</html>
