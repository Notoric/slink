@extends('default')

@section('title')
    Profile
@endsection

@section('content')
    <div id="profile-container" class="form-container"> <!-- TODO: Add ability to verify/reverify email -->
        <h1>Profile</h1>
        <form method="post" action="profile/update">
            @csrf
            <label for="name">Username</label>
            <input type="text" name="name" id="name" value="{{ Auth::user()->name }}" required>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ Auth::user()->email }}" required>
            <label for="created_at">Created On</label>
            <input type="text" name="created_at" id="created_at" class="unmodifiable" value="{{ Carbon\Carbon::parse(explode( " ", Auth::user()->created_at)[0])->format('D jS F Y') }}" required readonly>
            <div class="button-row">
                <button type="submit">Save</button>
                <a href="profile/change-password"><button type="button">Change Password</button></a>
            </div>
            @if ($errors->any())
                <p class="error">{{ $errors->first() }}</p>
            @endif
            @if (session('success'))
                <p class="success">{{ session('success') }}</p>
            @endif
        </form>
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