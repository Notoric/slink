@extends('default')

@section('title')
    Details & Logs
@endsection

@section('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
@endsection

@section('content')
    <div id="banner-container" class="container">
        <h1>Details & Stats</h1>
        <p>View and edit information about your link</p>
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
        <div id="map-wrapper">
            <h2>Heatmap</h2>
            <div id="map"></div>
        </div>
        <div id="timeline-wrapper">
            <h2>Timeline</h2>
            <canvas id="cumulativeGraph" width="540" height="400"></canvas>
        </div>
    </div>
    <div id="stats" class="container">
        <h2>Clicks by Country</h2>
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
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>
    <script>
        var map = L.map('map').setView([50, 0], 3);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 10,
        }).addTo(map);
        var heat = L.heatLayer([], {
            radius: 50,
            blur: 20,
            maxZoom: 3,
            max: 5,
        }).addTo(map);

        async function loadHeatmapData() {
            var data = [
                @foreach ($coordinates as $coordinate)
                    {lat:{{ $coordinate['latitude'] }}, lng:{{ $coordinate['longitude'] }}},
                @endforeach
            ];
            heat.setLatLngs(data);
        }
        loadHeatmapData();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <script>
        const timestamps = [
            @foreach ($timestamps as $timestamp)
                '{{ $timestamp['created_at'] }}',
            @endforeach
        ];

        // Function to count occurrences cumulatively
        function getCumulativeCounts(timestamps) {
            const counts = [];
            const dateCounts = {};
            timestamps.forEach((timestamp) => {
                const date = new Date(timestamp).toISOString(); // Get date part only
                if (dateCounts[date]) {
                    dateCounts[date]++;
                } else {
                    dateCounts[date] = 1;
                }
                counts.push({ date: date, count: Object.values(dateCounts).reduce((a, b) => a + b, 0) });
            });
            return counts;
        }

        // Get the cumulative data
        const cumulativeData = getCumulativeCounts(timestamps);

        // Extract dates and counts for the chart
        const labels = cumulativeData.map(data => data.date);
        const data = cumulativeData.map(data => data.count);

        // Create the chart
        const ctx = document.getElementById('cumulativeGraph').getContext('2d');
        const cumulativeGraph = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Clicks',
                    data: data,
                    borderColor: 'rgba(255, 0, 80, 1)',
                    backgroundColor: 'rgba(255, 0, 80, 0.2)',
                    borderWidth: 1,
                    fill: true
                }]
            },
            options: {
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'day'
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    
@endsection