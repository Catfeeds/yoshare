@extends('themes.mobile.layouts.frame')

@section('body')

    <body class="hold-transition skin-red sidebar-mini">

        @yield('css')

        @yield('content')

        @yield('js')
    </body>
@endsection
