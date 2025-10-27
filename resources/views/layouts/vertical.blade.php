<!DOCTYPE html>
<html lang="en" @yield('html_attribute')>

<head>
    @include('layouts.partials/title-meta')

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('layouts.partials/head-css')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    @yield('css')
</head>

<body>
    <div class="wrapper">

        @include('layouts.partials/sidenav')

        <div class="page-content">

            @include('layouts.partials/topbar')

            <main>

                @yield('content')

            </main>

            @include('layouts.partials/footer')

        </div>

    </div>

    @include('layouts.partials/customizer')

    @yield('scripts')
</body>

</html>
