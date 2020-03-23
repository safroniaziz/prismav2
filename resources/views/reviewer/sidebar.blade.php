<li>
    <a href=" {{ route('reviewer.dashboard') }} "><i class="fa fa-home"></i>Dashboard</a>
</li>

<li
    @if (Route::current()->getName() == "reviewer.usulan.review")
    class="current-page"
    @endif
>
    <a href=" {{ route('reviewer.menunggu') }} "><i class="fa fa-newspaper-o"></i>Review Penelitian</a>
</li>

<li
    @if (Route::current()->getName() == "reviewer.laporan_kemajuan.review")
    class="current-page"
    @endif
>
    <a href=" {{ route('reviewer.laporan_kemajuan') }} "><i class="fa fa-upload"></i>Review Laporan Kemajuan</a>
</li>

<li><a><i class="fa fa-history"></i>Riwayat Review<span class="fa fa-chevron-down" ></span></a>
    <ul class="nav child_menu">
        <li><a href=" {{ route('reviewer.riwayat') }} ">Review Usulan Kegiatan</a></li>
        <li><a href=" {{ route('reviewer.riwayat_kemajuan') }} ">Review Laporan Kemajuan</a></li>
    </ul>
</li>

<li>
    <a href=" {{ route('logout_reviewer') }} "><i class="fa fa-power-off text-danger"></i>Logout</a>
</li>

