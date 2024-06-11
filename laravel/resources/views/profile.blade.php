@extends('default')

@section('title')
    Profile
@endsection

@section('content')
    <h1>Profile</h1>
    <p>Username: <em>{{ Auth::user()->name }}</em></p>
    <p>Email: <em>{{ Auth::user()->email }}</em></p>
    <p>Created at: <em>{{ Auth::user()->created_at }}</em></p>
    <table>
        <tr>
            <th>Link</th>
            <th>Destination</th>
            <th>Created at</th>
        </tr>
        @foreach ($shortlinks as $shortlink)
            <tr>
                <td><a href="{{ url()->to("l/" . $shortlink->shortid) }}">{{ $shortlink['shortid'] }}</a></td>
                <td>{{ $shortlink['destination'] }}</td>
                <td>{{ $shortlink['created_at'] }}</td>
            </tr>
        @endforeach
    </table>
@endsection