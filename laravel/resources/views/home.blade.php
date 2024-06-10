@extends('default')

@section('title')
    Home
@endsection

@section('content')
    <div id="shortener-container">
        @if (auth()->check())
            <h1>Shorten your URL</h1>
            <form action="shorten" method="post">
                @csrf
                <input type="text" name="url" placeholder="Enter your URL here" required>
                <button type="submit">Shorten</button>
            </form>
            @if ($errors->any())
                <p class="error">{{ $errors->first() }}</p>
            @endif
        @else
            <h1>Welcome to slink URL Shortener</h1>
            <p><a href="register">Sign Up</a> or <a href="login">Log In</a> to shorten your URLs</p>
        @endif
    </div>

@endsection