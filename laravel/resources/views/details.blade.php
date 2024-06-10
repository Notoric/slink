@extends('default')

@section('title')
    Details & Logs
@endsection

@section('content')
    <div id="banner-container" class="container">
        <h1>Details & Logs</h1>
        <p>View and edit information for your link</p>
    </div>
    <div id="details" class="container">
        <h2>Details</h2>
        <form id="update-form" method="post" action="{{ url()->current() }}/update">
            @csrf
            <div id="info-container">
                <label id="destination-label" for="destination">Destination</label>
                <a href="{{ $shortlink->destination }}" target="_blank" id="destination">{{ $shortlink->destination }}<button title="Copy Link" id="destination_clipboard">🔗</button></a>
                <label id="URL-label" for="url">URL</label>
                <a href="{{ url()->to($shortlink->shortid) }}" target="_blank" id="url">{{ url()->to($shortlink->shortid) }}<button title="Copy Link" id="url_clipboard">🔗</button></a>
                <label id="maxclicks-label" for="maxclicks">Max Clicks</label>
                <img id="maxclicks-info" class="info" src="{{ asset("img/icons/info.svg") }}" title="This link will stop working after the maximum number of clicks has been reached. Set this to 0 to allow an infinite number of uses.">
                <input id="maxclicks" name="maxclicks" type="number" value="{{ $shortlink->max_clicks }}" required>
                <label id="expiry-label" for="expiry-toggle">Expiry</label>
                <img id="expiry-info" class="info" src="{{ asset("img/icons/info.svg") }}" title="This link will stop working after the specified time. This uses UTC time.">
                <input id="expiry-toggle" name="expiry-toggle" type="checkbox" 
                    @if ($shortlink->expires_at != null)
                        checked
                    @endif
                >
                <label id="date-label" for="expiry-date">Date</label>
                <input id="expiry-date" name="expiry-date" type="date" 
                    @if ($shortlink->expires_at != null)
                        value="{{ Carbon\Carbon::parse($shortlink->expires_at)->format('Y-m-d') }}"
                    @endif
                >
                <label id="time-label" for="expiry-hour">Time</label>
                <select id="expiry-hour" name="expiry-hour">
                    @for ($i = 0; $i < 24; $i++)
                        {{$j = str_pad($i, 2, '0', STR_PAD_LEFT)}}
                        <option value="{{ $j }}" 
                            @if ($shortlink->expires_at != null && Carbon\Carbon::parse($shortlink->expires_at)->hour == $j)
                                selected
                            @endif
                        >{{ $j }}</option>
                    @endfor
                </select>
                <label id="time-separator" for="expiry-minute">:</label>
                <select id="expiry-minute" name=expiry-minute>
                    @for ($i = 0; $i < 60; $i+=5)
                        {{$j = str_pad($i, 2, '0', STR_PAD_LEFT)}}
                        <option value="{{ $j }}" 
                            @if ($shortlink->expires_at != null && Carbon\Carbon::parse($shortlink->expires_at)->minute == $j)
                                selected
                            @endif
                        >{{ $j }}</option>
                    @endfor
                </select>
                <label id="timestamp-label" for="created">Timestamps</label>
                <p id="created">{{ Carbon\Carbon::parse($shortlink->created_at)->format('H:i l jS F Y') }}</p>
                <p id="updated">{{ Carbon\Carbon::parse($shortlink->updated_at)->format('H:i l jS F Y') }}</p>
                <button type="submit" form="update-form">Save</button>
            </div>
        </form>
    </div>
    <div id="graphs" class="container">

    </div>
    <div id="stats" class="container">
        <h2>Link Clicks</h2>
        <table>
            <thead>
                <th>📍</th>
                <th>Country</th>
                <th>Clicks</th>
            </thead>
            @foreach ($countrylist as $country)
                <tr>
                    <td>{{ $country['emoji'] }}</td>
                    <td>{{ $country['country'] ?? 'Unknown' }}</td>
                    <td>{{ $country['total'] }}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/details.js') }}"></script>
@endsection