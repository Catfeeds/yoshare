@extends('themes.mobile.layouts.frame')

@section('body')

    <body class="hold-transition skin-red sidebar-mini" scroll="no">

        @yield('css')

        @yield('content')

        @if($system['mark'] == 'detail')
            @include('themes.mobile.widgets.action')
        @elseif($system['mark'] == 'cart')
            @include('themes.mobile.cart.footer')
        @elseif($system['mark'] == 'orders')
            @include('themes.mobile.orders.footer')
        @else
            @include('themes.mobile.widgets.nav')
        @endif

        @yield('js')
    </body>
@endsection
