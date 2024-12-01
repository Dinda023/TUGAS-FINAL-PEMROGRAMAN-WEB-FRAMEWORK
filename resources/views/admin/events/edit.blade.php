@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.event.title_singular') }}
    </div>

    <div class="card-body">
        <form 
            action="{{ route('events.update', [$event->id]) }}" 
            method="POST" 
            enctype="multipart/form-data"
            @if($event->events_count || $event->event) 
                onsubmit="return confirm('Do you want to apply these changes to all future recurring events, too?');" 
            @endif
        >
            @csrf
            @method('PUT')

            <!-- Input Nama Event -->
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">{{ trans('cruds.event.fields.name') }}*</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $event->name) }}" required>
                @if($errors->has('name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.event.fields.name_helper') }}
                </p>
            </div>

            <!-- Input Waktu Mulai -->
            <div class="form-group {{ $errors->has('start_time') ? 'has-error' : '' }}">
                <label for="start_time">{{ trans('cruds.event.fields.start_time') }}*</label>
                <input type="text" id="start_time" name="start_time" class="form-control datetime" value="{{ old('start_time', $event->start_time) }}" required>
                @if($errors->has('start_time'))
                    <em class="invalid-feedback">
                        {{ $errors->first('start_time') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.event.fields.start_time_helper') }}
                </p>
            </div>

            <!-- Input Waktu Selesai -->
            <div class="form-group {{ $errors->has('end_time') ? 'has-error' : '' }}">
                <label for="end_time">{{ trans('cruds.event.fields.end_time') }}*</label>
                <input type="text" id="end_time" name="end_time" class="form-control datetime" value="{{ old('end_time', $event->end_time) }}" required>
                @if($errors->has('end_time'))
                    <em class="invalid-feedback">ave
                        {{ $errors->first('end_time') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.event.fields.end_time_helper') }}
                </p>
            </div>

            <!-- Input Lokasi -->
            <div class="form-group {{ $errors->has('location') ? 'has-error' : '' }}">
                <label for="location">{{ trans('cruds.event.fields.location') }}*</label>
                <input type="text" id="location" name="location" class="form-control" value="{{ old('location', $event->location) }}" required>
                @if($errors->has('location'))
                    <em class="invalid-feedback">
                        {{ $errors->first('location') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.event.fields.location_helper') }}
                </p>
                <button type="button" class="btn btn-info" onclick="useMyLocation()">Use My Location</button>
            </div>
                        <!-- Peta OpenStreetMap -->
            <div class="form-group">
                <div id="map" style="height: 400px; width: 100%;"></div>
                <p class="helper-block">
                    Klik pada peta untuk memilih lokasi.
                </p>
            </div>

            <!-- Input Foto -->
            <div class="form-group {{ $errors->has('photo') ? 'has-error' : '' }}">
                <label for="photo">{{ trans('cruds.event.fields.photo') }}</label>
                <input type="file" id="photo" name="photo" class="form-control">
                @if($event->photo)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $event->photo) }}" alt="Event Photo" width="150" />
                    </div>
                @endif
                @if($errors->has('photo'))
                    <em class="invalid-feedback">
                        {{ $errors->first('photo') }}
                    </em>
                @endif
            </div>

            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>


        </form>
    </div>
</div>

<!-- Include Leaflet.js -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<script>
    var map = L.map('map').setView([0, 0], 2);  // Set default center to global view (0, 0) with zoom level 2

    // Load OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var marker;

    // If location is set, center map to the location and place marker
    @if($event->location)
        // You can use a geocoding service like Nominatim to fetch coordinates from the location name
        var geocodeUrl = 'https://nominatim.openstreetmap.org/search?format=json&q={{ urlencode($event->location) }}';
        fetch(geocodeUrl)
            .then(response => response.json())
            .then(data => {
                if (data && data[0]) {
                    var lat = data[0].lat;
                    var lon = data[0].lon;
                    map.setView([lat, lon], 13);
                    marker = L.marker([lat, lon]).addTo(map);
                    document.getElementById("location").value = data[0].display_name;
                }
            })
            .catch(error => {
                console.log('Error geocoding address:', error);
            });
    @endif

    // Handle map click to place marker and update location input field
    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lon = e.latlng.lng;

        // If a marker already exists, remove it
        if (marker) {
            map.removeLayer(marker);
        }

        // Place new marker where clicked
        marker = L.marker([lat, lon]).addTo(map);

        // Use Nominatim API to get address from latitude and longitude
        fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`)
            .then(response => response.json())
            .then(data => {
                var address = data.display_name;
                document.getElementById("location").value = address;
            })
            .catch(error => {
                console.log('Error fetching address:', error);
            });
    });

    // Function to use current location of the user
    function useMyLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lon = position.coords.longitude;

                // Center map to user's location and add marker
                map.setView([lat, lon], 13);
                if (marker) {
                    map.removeLayer(marker);
                }
                marker = L.marker([lat, lon]).addTo(map);

                // Use Nominatim API to get address from latitude and longitude
                fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`)
                    .then(response => response.json())
                    .then(data => {
                        var address = data.display_name;
                        document.getElementById("location").value = address;
                    })
                    .catch(error => {
                        console.log('Error fetching address:', error);
                    });
            });
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }
</script>

@endsection
