@extends('default')

@section('title')
    Profile
@endsection

@section('content')
    <div id="title-container" class="container">
        <h1>Profile</h1>
    </div>
    <div id="profile-container" class="container">
        <p>Username: <em>{{ Auth::user()->name }}</em></p>
        <p>Email: <em>{{ Auth::user()->email }}</em></p>
        <p>Created at: <em>{{ Auth::user()->created_at }}</em></p>
    </div>
    <div id="table-container" class="container">
        <h2>My Short URLs</h2>
        <table>
            <tbody>
                <tr>
                    <th>Link</th>
                    <th>Destination</th>
                    <th>Created at</th>
                </tr>
                @foreach ($shortlinks as $shortlink)
                    <tr>
                        <td><a href="{{ url()->to("l/" . $shortlink->shortid) }}">{{ $shortlink['shortid'] }}</a></td>
                        <td class="table-truncate"><div class="destination">{{ $shortlink['destination'] }}</div></td>
                        <td>{{ Carbon\Carbon::parse($shortlink->created_at)->format('M jS Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"><a href="/home">Shorten a new link</a></td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection