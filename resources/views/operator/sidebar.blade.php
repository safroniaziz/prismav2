<li>
    <a href=" {{ route('operator.dashboard') }} "><i class="fa fa-home"></i>Dashboard</a>
</li>

<li><a><i class="fa fa-building-o"></i>Divisi Universitas <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href=" {{ route('operator.fakultas') }} ">Fakultas</a></li>
        <li><a href=" {{ route('operator.prodi') }} ">Program Study</a></li>
    </ul>
</li>

<li><a><i class="fa fa-info-circle"></i>Skim & Bidang <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href=" {{ route('operator.skim') }} ">Skim Penelitian</a></li>
        <li><a href=" {{ route('operator.bidang') }} ">Bidang Penelitian</a></li>
    </ul>
</li>

<li><a><i class="fa fa-reply"></i>Usulan Penelitian <span class="fa fa-chevron-down" ></span></a>
    <ul class="nav child_menu">
        <li><a href=" {{ route('operator.menunggu') }} ">Tambah Reviewer</a></li>
        <li><a href=" {{ route('operator.proses_review') }} ">Dalam Proses Review</a></li>
    </ul>
</li>

<li><a><i class="fa fa-check-square"></i>Formulir Penilaian <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        <li><a href=" {{ route('operator.variabel_penilaian') }} ">Variabel Penilaian</a></li>
    </ul>
</li>

<li>
    <a href=" {{ route('operator.operator') }} "><i class="fa fa-users"></i>Manajemen Operator</a>
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
