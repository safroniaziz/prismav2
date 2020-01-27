
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>TESIS ONLINE - @yield('main-title')</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('assets/vendors/iconfonts/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/iconfonts/puse-icons-feather/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.addons.css') }}">
  <!-- endinject -->

  <!-- Datatables -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
  <!-- End Datatables -->

  <!-- Responsive Table -->
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap4.min.css">
  <!-- End Responsive Table -->


  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('assets/css/shared/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- endinject -->
  <!-- Layout styles -->
  <link rel="stylesheet" href="{{ asset('assets/css/demo_1/style.css') }}">
  <!-- End Layout styles -->
  <link rel="shortcut icon" href="{{ asset('assets/images/logo_unib.png') }}" />
  @stack('styles')
</head>

<body class="sidebar-fixed">
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center" style="background: #252C46; border-bottom:1px white solid;">
            <a class="navbar-brand brand-logo" href="" style="color:white;line-height:58px; font-size:23px;letter-spacing:2px;">
             PRISMA UNIB V2
            <a class="navbar-brand brand-logo-mini" href="">
              <i class="mdi mdi-home text-white"></i>
            </a>
        </div>

      <div class="navbar-menu-wrapper d-flex align-items-center">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="mdi mdi-menu"></span>
        </button>
        <ul class="navbar-nav navbar-nav-right">

          <li class="nav-item dropdown d-none d-xl-inline-block">
            <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false" style="text-transform:uppercase;">
              <span class="profile-text">@yield('second-user-login')</span>
              <i class="fa fa-user"></i> </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown" style="padding:5px 0px !important; cursor:pointer;">

              <a class="dropdown-item logout" href="" style="color:red !important;"><i class="fa fa-power-off" style="color:red !important;"></i> Keluar </a>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="icon-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper sidebar-dark">

      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
            <li class="nav-item nav-profile">
                <div class="nav-link"  style="flex-direction:unset !important; padding:16px 15px !important;">
                  <div class="user-wrapper">
                    <div class="profile-image">
                      <img src="{{ asset('assets/images/logo_unib.png') }}" alt="profile image" style="max-width:100% !important; height:39px !important;"> </div>
                    <div class="text-wrapper">
                        <p class="profile-name" style="color:white;text-transform:uppercase;font-size:12px;">
                            @yield('user-login')
                          {{-- {{ Session::get('nm_mahasiswa') }} --}}
                          </p>
                      <div >
                        <small class="designation text-white">
                            @yield('user-level-login')
                        </small>
                        <span class="status-indicator online"></span>
                      </div>
                    </div>
                  </div>
                </div>
              </li>

            @yield('sidebar_menu')
        </ul>
      </nav>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
            @yield('content')
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 grid-margin stretch-card dashboard5" style="padding:0px;">
                <div class="card card-statistics social-card card-default">
                    <div class="card-header header-sm" style="height:auto; padding:20px 15px 15px 15px !important;">
                        @yield('button_tambah')
                        <div class="d-flex align-items-center">
                            <div class="wrapper d-flex align-items-center media-info text-facebook">
                                @yield('manajemen-icon')
                                <h2 class="card-title ml-3">@yield('manajemen-title')</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="padding:20px 25px !important"  style="padding:10px 15px;">
                      @yield('form-filter')
                      @yield('manajemen-content')
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer" style="border-top:2px white solid; bottom:0; z-index:99; width:100%; padding:10px;">
            <div class="container-fluid clearfix">
              <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © 2019 <a href="http://lptik.unib.ac.id/en/home"
                  target="_blank">LPTIK UNIB</a>.</span>
              <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"><a href="https://www.unib.ac.id/" style="color:#858585;"><i class="fa fa-university"></i> UNIVERSITAS BENGKULU</a>
              </span>
            </div>
          </footer>

        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- Datatables -->
  <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
  <!-- End Datatables -->

  <!-- plugins:js -->
  <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
  <script src="{{ asset('assets/vendors/js/vendor.bundle.addons.js') }}"></script>

  <!-- Validador -->
  <script src="{{ asset('assets/validator/validator.min.js') }}"></script>
  <!-- End Validador -->


  <!-- Responsive Table -->
  <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js"></script>
  <!-- End Responsive Table -->


  <!-- endinject -->
  <!-- Plugin js for this page-->
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="{{ asset('assets/js/shared/off-canvas.js') }}"></script>
  <script src="{{ asset('assets/js/shared/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('assets/js/shared/misc.js') }}"></script>
  <script src="{{ asset('assets/js/shared/settings.js') }}"></script>
  <script src="{{ asset('assets/js/shared/todolist.js') }}"></script>
  <script src="{{ asset('assets/js/shared/file-upload.js') }}"></script>
  <!-- endinject -->

  <!-- SweetAlert2 -->
  <script src="{{ asset('assets/sweetalert2/sweetalert2.all.min.js') }}"></script>
  <!-- End SweetAlert2 -->

  <!-- Custom js for this page-->
  <script src="{{ asset('assets/js/demo_1/dashboard.js') }}"></script>
  <!-- End custom js for this page-->
  @stack('scripts')
</body>

</html>
