<li>
    <a href=" {{ route('pengusul.dashboard') }} "><i class="fa fa-home"></i>Dashboard</a>
</li>

<li
    @if (Route::current()->getName() == "pengusul.usulan.detail_anggaran")
    class="current-page"
    @endif
>
    <a href=" {{ route('pengusul.usulan') }} "><i class="fa fa-newspaper-o"></i>Usulan Baru</a>
</li>

<li>
    <a href=" {{ route('logout_user') }} "><i class="fa fa-power-off text-danger"></i>Logout</a>
</li>

