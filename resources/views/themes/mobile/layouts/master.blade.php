@extends('themes.mobile.layouts.frame')

@section('body')

    <body class="hold-transition skin-red sidebar-mini" scroll="no">

        @yield('css')

        @yield('content')

        @if($mark == 'detail')
            @include('themes.mobile.widgets.action')
        @elseif($mark == 'cart')
            @include('themes.mobile.cart.footer')
        @else
            @include('themes.mobile.widgets.nav')
        @endif

        @yield('js')
    </body>
@endsection
