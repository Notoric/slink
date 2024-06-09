<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>@yield('title')</title>
        <link rel="stylesheet" href="{{ asset('css/default.css') }}">
        @yield('head')
    </head>
    <body>
        <main>
            @yield('content')
        </main>
    </body>
</html>