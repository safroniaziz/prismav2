@php
    use App\JadwalReviewUsulan;
@endphp
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
        <title>Prisma V2 | Login</title>
        <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}">
        <link href="{{ asset('assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
        <link rel="stylesheet" href=" {{ asset('css/style_login.css') }} ">
        <link href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
	</head>
	<body>
		<div id="particles-js">
            <div class="loginBox">
                <img src=" {{ asset('assets/images/logo.png') }} " class="user">
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-block" style="font-size:13px;">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>Perhatian:</strong> <i>{{ $message }}</i>
                    </div>
                    @else
                    @php
                        $jadwal = JadwalReviewUsulan::select('tanggal_awal','tanggal_akhir')->where('status','1')->get();
                        $mytime = Carbon\Carbon::now();

                        $now =  $mytime->toDateString();
                    @endphp
                        @if (count($jadwal) > 0)
                            @if ($now >= $jadwal[0]->tanggal_awal && $now <= $jadwal[0]->tanggal_akhir)
                            <h6>Login Reviewer (Usulan Kegiatan)</h6>
                            <p style="text-align:center; margin-bottom:20px;">Sistem Informasi Publikasi, Riset dan Pengabdian Kepada Masyarakat</p>
                            @else
                            <div class="alert alert-danger alert-block" style="font-size:13px;">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>Perhatian:</strong> <b>Saat ini bukan masa review usulan kegiatan !!</b>
                            </div>
                        @endif
                        @else
                        <div class="alert alert-danger alert-block" style="font-size:13px;">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>Perhatian:</strong> <b>Jadwal review usulan belum diatur !!</b>
                        </div>
                    @endif
                @endif
                <form method="post" action="{{ route('reviewer_usulan.login.submit') }}">
                    @csrf
                    <p>Nip Reviewer</p>
                    <input type="text" name="reviewer_nip" placeholder="masukan nip">
                    <p>Password</p>
                    <input type="password" name="password" placeholder="••••••">

                    @if (count($jadwal) >0)
                        <button type="submit" name="submit" style="margin-bottom:10px;r"><i class="fa fa-sign-in"></i>&nbsp; Login</button>
                        @else
                        <button type="submit" name="submit" style="margin-bottom:10px;r" disabled><i class="fa fa-sign-in"></i>&nbsp; Login</button>
                    @endif
                    <a href="#" style="font-weight:200; font-style:italic;">Versi 2.0</a>
                </form>
            </div>
        </div>
    </body>
    <script type="text/javascript" src=" {{ asset('assets/particles/particles.min.js') }} "></script>
    <script type="text/javascript" src=" {{ asset('assets/particles/app.js') }} "></script>
    <script>
        document.addEventListener('contextmenu', event => event.preventDefault());
    </script>
</html>
