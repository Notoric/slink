<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>@yield('title')</title>
        <link rel="stylesheet" href="{{ asset('css/default.css') }}">
        @yield('head')
    </head>
    <body>
        <style>
            :root {
                --primary: {{ config('user-config.accent_color') }};
            }
        </style>
        <header>
            <div class="container">
                <a id="logo" href="/home">[slink]</a>
                @if (Auth::check())
                    <nav>
                        <a href="/profile"><img title="Profile" src="{{ asset('img/icons/profile.svg') }}"></a>
                        <a href="/logout"><img title="Logout" src="{{ asset('img/icons/logout.svg') }}"></a>
                    </nav>
                @else
                    <nav>
                        <a href="/register">Register</a>
                        <a href="/login">Log In</a>
                    </nav>
                @endif
            </div>
        </header>
        <main>
            @yield('content')
        </main>
        @php
            $footer = [];
            $footer['left'] = config('user-config.footer_left');
            $footer['right'] = config('user-config.footer_right');
        @endphp
        <footer>
            <p>{!! $footer['left'] !!}</p>
            <p>{!! $footer['right'] !!}</p>
        </footer>
        @yield('scripts')
    </body>
</html>