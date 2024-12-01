@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.event.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("events.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Input Nama Event -->
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">{{ trans('cruds.event.fields.name') }}*</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($event) ? $event->name : '') }}" required>
                @if($errors->has('name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </em>
                @endif
            </div>

            <!-- Input Waktu Mulai -->
            <div class="form-group {{ $errors->has('start_time') ? 'has-error' : '' }}">
                <label for="start_time">{{ trans('cruds.event.fields.start_time') }}*</label>
                <input type="text" id="start_time" name="start_time" class="form-control datetime" value="{{ old('start_time', isset($event) ? $event->start_time : '') }}" required>
                @if($errors->has('start_time'))
                    <em class="invalid-feedback">
                        {{ $errors->first('start_time') }}
                    </em>
                @endif
            </div>

            <!-- Input Waktu Selesai -->
            <div class="form-group {{ $errors->has('end_time') ? 'has-error' : '' }}">
                <label for="end_time">{{ trans('cruds.event.fields.end_time') }}*</label>
                <input type="text" id="end_time" name="end_time" class="form-control datetime" value="{{ old('end_time', isset($event) ? $event->end_time : '') }}" required>
                @if($errors->has('end_time'))
                    <em class="invalid-feedback">
                        {{ $errors->first('end_time') }}
                    </em>
                @endif
            </div>

            <!-- Input Lokasi -->
            <div class="form-group {{ $errors->has('location') ? 'has-error' : '' }}">
                <label for="location">{{ trans('cruds.event.fields.location') }}*</label>
                <input type="text" id="location" name="location" class="form-control" value="{{ old('location', isset($event) ? $event->location : '') }}" required>
                @if($errors->has('location'))
                    <em class="invalid-feedback">
                        {{ $errors->first('location') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.event.fields.location_helper') }}
                </p>
                <button type="button" class="btn btn-info" onclick="getLocation()">Use My Location</button>
            </div>

            <!-- Peta OpenStreetMap dengan Leaflet -->
            <div class="form-group">
                <label>{{ trans('') }}</label>
                <div id="map" style="height: 400px; width: 100%;"></div>
                <p class="helper-block">
                    Klik pada peta untuk memilih lokasi.
                </p>
            </div>

            <!-- Input Foto -->
            <div class="form-group {{ $errors->has('photo') ? 'has-error' : '' }}">
                <label for="photo">{{ trans('cruds.event.fields.photo') }}</label>
                <input type="file" id="photo" name="photo" class="form-control">
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

    // Add a marker for location selection
    var marker;
    
    // Function to handle location clicks
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
                // Get the address from the API response
                var address = data.display_name;

                // Set the address in the location input field
                document.getElementById("location").value = address;
            })
            .catch(error => {
                console.log('Error fetching address:', error);
            });
    });

    // Use browser's geolocation to set map location
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lon = position.coords.longitude;

                // Set the map's view to the user's current location
                map.setView([lat, lon], 13);

                // Place a marker at the user's location
                if (marker) {
                    map.removeLayer(marker);
                }
                marker = L.marker([lat, lon]).addTo(map);

                // Use Nominatim API to get address from latitude and longitude
                fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`)
                    .then(response => response.json())
                    .then(data => {
                        // Get the address from the API response
                        var address = data.display_name;

                        // Set the address in the location input field
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
