@extends('themes.default.layouts.frame')

@section('body')
    <body class="hold-transition skin-red sidebar-mini">

        @if($mark !== 'user')
            @include('themes.default.widgets.header')
        @endif

        @yield('css')

        @yield('content')

        @if($mark !== 'detail')
            @include('themes.default.widgets.nav')
        @else
            @include('themes.default.widgets.action')
        @endif

        @yield('js')

    <!-- ./wrapper -->
    </body>
@endsection
