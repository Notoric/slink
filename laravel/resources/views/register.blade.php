@extends('default')

@section('title')
    Register
@endsection

@section('content')
    <div id="register" class="form-container">
        <h1>Register</h1>
        <form method="post" action="register">
            @csrf
            <label for="name">Username</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>
            <button type="submit">Register</button>
        </form>
        @if ($errors->any())
            <p class="error">{{ $errors->first() }}</p>
        @endif
    </div>
@endsection