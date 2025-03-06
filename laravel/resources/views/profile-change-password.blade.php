@extends('default')

@section('title')
    Change Password
@endsection

@section('content')
    <div id="register" class="form-container">
        <h1>Change Password</h1>
        <form method="post" action="change-password/update">
            @csrf
            <label for="name">Username</label>
            <input type="text" name="name" id="name" class="unmodifiable" value="{{  Auth::user()->name  }}" readonly required>
            <label for="password">New Password</label>
            <input type="password" name="password" id="password" required>
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>
            <button type="submit">Change Password</button>
        </form>
        @if ($errors->any())
            <p class="error">{{ $errors->first() }}</p>
        @endif
    </div>
@endsection