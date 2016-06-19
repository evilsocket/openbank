@include('layouts.header')

<body id="app-layout">
    @include('layouts.navbar')

    @yield('content')

    @include('layouts.footer')

    @yield('pagescript')
</body>
</html>
