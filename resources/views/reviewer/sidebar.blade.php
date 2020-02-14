<li>
    <a href=" {{ route('reviewer.dashboard') }} "><i class="fa fa-home"></i>Dashboard</a>
</li>

<li><a><i class="fa fa-newspaper-o"></i>Usulan Penelitian <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href=" {{ route('reviewer.menunggu') }} ">Menunggu Direview</a></li>
    </ul>
</li>

<li>
    <a href=" {{ route('logout_reviewer') }} "><i class="fa fa-power-off text-danger"></i>Logout</a>
</li>

