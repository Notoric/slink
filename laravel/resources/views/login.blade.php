@extends('default')

@section('title')
    Log In
@endsection

@section('content')
    <div id="login" class="form-container">
        <h1>Log In</h1>
        <form method="post" action="login">
            @csrf
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">Log In</button>
        </form>
        @if ($errors->any())
            <p class="error">{{ $errors->first() }}</p>
        @endif
    </div>
@endsection