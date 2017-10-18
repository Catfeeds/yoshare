@extends('themes.mobile.layouts.frame')

@section('body')
    <body class="hold-transition skin-red sidebar-mini">

        @if($mark == 'detail')
            @include('themes.mobile.widgets.back')
        @elseif($mark !== 'user' && $mark != 'detail')
            @include('themes.mobile.widgets.header')
        @endif

        @yield('css')

        @yield('content')

        @if($mark !== 'detail')
            @include('themes.mobile.widgets.nav')
        @else
            @include('themes.mobile.widgets.action')
        @endif

        @yield('js')

    <!-- ./wrapper -->
    </body>
@endsection
