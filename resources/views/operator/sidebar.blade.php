<li>
    <a href=" {{ route('operator.dashboard') }} "><i class="fa fa-home"></i>Dashboard</a>
</li>

{{-- <li><a><i class="fa fa-building-o"></i>Divisi Universitas <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href=" {{ route('operator.fakultas') }} ">Fakultas</a></li>
        <li><a href=" {{ route('operator.prodi') }} ">Program Study</a></li>
    </ul>
</li> --}}

{{-- <li>
    <a href=" {{ route('operator.skim') }} "><i class="fa fa-info-circle"></i>Skim Penelitian</a>
</li> --}}

<li><a><i class="fa fa-check-square"></i>Komponen Penilaian <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href=" {{ route('operator.skim') }} ">Skim Penelitian</a></li>
        <li><a href=" {{ route('operator.kriteria_penilaian') }} ">Kriteria Penilaian</a></li>
    </ul>
</li>

<li><a><i class="fa fa-reply"></i>Usulan Penelitian <span class="fa fa-chevron-down" ></span></a>
    <ul class="nav child_menu"
        @if (Route::current()->getName() == "operator.menunggu.detail_reviewer")
            style="display:block !important;"
        @endif
    >
        <li
            @if (Route::current()->getName() == "operator.menunggu.detail_reviewer")
                class="current-page"
            @endif
        ><a href=" {{ route('operator.menunggu') }} ">Tambah Reviewer</a></li>
        <li><a href=" {{ route('operator.proses_review') }} ">Dalam Proses Review</a></li>
        <li><a href=" {{ route('operator.verifikasi') }} ">Menunggu Verifikasi</a></li>
    </ul>
</li>

<li><a><i class="fa fa-check-circle"></i>Hasil Verifikasi Usulan<span class="fa fa-chevron-down" ></span></a>
    <ul class="nav child_menu">
        <li><a href=" {{ route('operator.diterima') }} ">Usulan Didanai</a></li>
        <li><a href=" {{ route('operator.ditolak') }} ">Usulan Tidak Didanai</a></li>
    </ul>
</li>


<li><a><i class="fa fa-upload"></i>Laporan Kemajuan <span class="fa fa-chevron-down" ></span></a>
    <ul class="nav child_menu"
        @if (Route::current()->getName() == "operator.laporan_kemajuan.detail_reviewer")
            style="display:block !important;"
        @endif
    >
        <li
            @if (Route::current()->getName() == "operator.laporan_kemajuan.detail_reviewer")
                class="current-page"
            @endif
        ><a href=" {{ route('operator.laporan_kemajuan') }} ">Tambah Reviewer</a></li>
        <li><a href=" {{ route('operator.laporan_kemajuan.proses_review') }} ">Dalam Proses Review</a></li>
        <li><a href=" {{ route('operator.laporan_kemajuan.verifikasi') }} ">Menunggu Verifikasi</a></li>
    </ul>
</li>

<li><a><i class="fa fa-check-circle"></i>Hasil Lap. Kemajuan<span class="fa fa-chevron-down" ></span></a>
    <ul class="nav child_menu">
        <li><a href=" {{ route('operator.laporan_kemajuan_diterima') }} ">Laporan Kemajuan Disetujui</a></li>
        <li><a href=" {{ route('operator.laporan_kemajuan_ditolak') }} ">Laporan Kemajuan Tidak Disetujui</a></li>
    </ul>
</li>

<li>
    <a href=" {{ route('operator.laporan_akhir') }} "><i class="fa fa-list"></i>Laporan Akhir</a>
</li>

<li>
    <a href=" {{ route('operator.operator') }} "><i class="fa fa-user"></i>Manajemen Operator</a>
</li>

<li style="padding-left:2px;">
    <a class="dropdown-item" href="{{ route('logout') }}"
        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
        <i class="fa fa-power-off text-danger"></i>{{__('Logout') }}
    </a>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

</li>
