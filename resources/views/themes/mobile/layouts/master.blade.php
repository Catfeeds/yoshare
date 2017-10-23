@extends('themes.mobile.layouts.frame')

@section('body')

    <body class="hold-transition skin-red sidebar-mini">

        @yield('css')

        @yield('content')

        @if($mark !== 'detail')
            @include('themes.mobile.widgets.nav')
        @else
            @include('themes.mobile.widgets.action')
        @endif

        @yield('js')
    </body>
@endsection
