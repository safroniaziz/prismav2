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

<li>
    <a href=" {{ route('logout_reviewer') }} "><i class="fa fa-power-off text-danger"></i>Logout</a>
</li>
