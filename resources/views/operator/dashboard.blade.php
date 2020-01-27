@extends('layouts.layout')
@section('main-title','Dashboard')
@section('user-login')
    {{ Auth::user()->nm_user }}
@endsection
@section('second-user-login')
    {{ Auth::user()->nm_user }}
@endsection
@section('sidebar_menu')
    @include('operator/sidebar')
@endsection
@section('user-level-login','Login Sebagai Operator')

@section('content')
    <div class="alert alert-success dashboard4" role="alert">
        <h4 class="alert-heading"><i class="fa fa-dashboard" style="color:#13a471 !important;"></i> DASHBOARD!</h4>
        <hr style="padding:2px;">
        <p>Selamat Datang <u style="text-transform:uppercase;"> {{ Auth::user()->nm_user }} </u> , Silahkan Gunakan Menu Yang Telah Disediakan Untuk Mengelola Aplikasi</p>
        <p class="mb-0">Jangan Lupa Logout Setelah Menggunakan Aplikasi !!</p>
    </div>
@endsection
@section('manajemen-icon')
    <i class="fa fa-bar-chart icon-md text-dark"></i>
@endsection
@section('manajemen-title')
    Persentase Tahapan Tesis Mahasiswa
@endsection
@push('styles')
<style>
        #chartdiv {
          width: 100%;
          height: 500px;
        }
        </style>

@endpush
@section('manajemen-content')
    @section('chart_data')
        chart.data = [
            {{-- @foreach ($persentase2 as $persentase)

            {
                "country": " {{ $persentase['keterangan'] }} ",
                "litres": " {{ $persentase['data'] }} ",
            },

            @endforeach --}}

        ];
    @endsection
    <div id="chartdiv"></div>
@endsection
@push('scripts')
    <!-- Resources -->
    <script src="https://www.amcharts.com/lib/4/core.js"></script>
    <script src="https://www.amcharts.com/lib/4/charts.js"></script>
    <script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>

    <!-- Chart code -->
    <script>
        am4core.ready(function() {

        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Themes end

        // Create chart instance
        var chart = am4core.create("chartdiv", am4charts.PieChart);

        // Add data
        @yield('chart_data')

        // Add and configure Series
        var pieSeries = chart.series.push(new am4charts.PieSeries());
        pieSeries.dataFields.value = "litres";
        pieSeries.dataFields.category = "country";
        pieSeries.slices.template.stroke = am4core.color("#fff");
        pieSeries.slices.template.strokeWidth = 2;
        pieSeries.slices.template.strokeOpacity = 1;

        // This creates initial animation
        pieSeries.hiddenState.properties.opacity = 1;
        pieSeries.hiddenState.properties.endAngle = -90;
        pieSeries.hiddenState.properties.startAngle = -90;

        }); // end am4core.ready()
    </script>
@endpush
