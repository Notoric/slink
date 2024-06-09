@extends('default')

@section('title')
    Profile
@endsection

@section('content')
    <h1>Profile</h1>
    <p>Username: <em>{{ Auth::user()->name }}</em></p>
    <p>Email: <em>{{ Auth::user()->email }}</em></p>
    <p>Created at: <em>{{ Auth::user()->created_at }}</em></p>
@endsection