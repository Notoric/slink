@extends('default')

@section('title')
    Log In
@endsection

@section('content')
    <h1>Log In</h1>
    <form method="post" action="login">
        @csrf
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}" required>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Log In</button>
    </form>
    <pre style="color: red;">{{ $errors->first() }}</pre>
@endsection