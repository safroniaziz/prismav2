<li>
    <a href=" {{ route('operator.dashboard') }} "><i class="fa fa-home"></i> Dashboard</a>
</li>

<li><a><i class="fa fa-users"></i> Manajemen User <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href=" {{ route('operator.dosen') }} ">Dosen</a></li>
        <li><a href=" {{ route('operator.operator') }} ">Operator</a></li>
    </ul>
</li>

<li style="padding-left:2px;">
    <a class="dropdown-item" href="{{ route('logout') }}"
        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
        <i class="fa fa-power-off text-danger"></i>{{ __('Logout') }}
    </a>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

</li>
