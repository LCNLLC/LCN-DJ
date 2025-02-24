@extends('courier.layouts.app')

@section('panel_content')

<div class="aiz-titlebar mt-2 mb-4">
  <div class="row align-items-center">
    <div class="col-md-6">
        <h1 class="h3">{{ translate('Courier Info')}}
        </h1>
    </div>
</div>
</div>

<!-- Basic Info -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{ translate('Basic Info') }}</h5>
    </div>
    <div class="card-body">
        <form class="" action="{{ route('courier.vehicle.update') }}" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="courier_id" value="{{ $courier->id }}">
            @csrf
            <div class="row">
                <label class="col-md-2 col-form-label">{{ translate('Name') }}<span class="text-danger text-danger">*</span></label>
                <div class="col-md-10">
                    <input type="text" class="form-control mb-3" placeholder="{{ translate('Courier Name')}}" name="name" value="{{ $courier->name }}" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-md-2 col-form-label">{{ translate('Uoload National Identity Card') }}  <span class="text-danger text-danger">*</span></label>
                <div class="col-md-10">
                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                        </div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input type="hidden" name="national_identity_card" value="{{ $courier->national_identity_card }}" class="selected-files">
                    </div>
                    <div class="file-preview box sm">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <label>{{ translate('Phone') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-10">
                    <input type="text" class="form-control mb-3" placeholder="{{ translate('Phone')}}" name="phone" value="{{ $courier->phone }}" required>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-2 col-from-label">{{translate('Country')}}<span class="text-danger text-danger">*</span></label>
                <div class="col-md-10">
                  <select class="form-control aiz-selectpicker" data-live-search="true" data-placeholder="{{ translate('Select your country') }}" name="country_id" required>
                    <option value="">{{ translate('Select your country') }}</option>
                    @foreach (\App\Models\Country::all() as $key => $country)
                    <option value="{{ $country->id }}" @if($courier->country_id == $country->id) selected @endif>{{ $country->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2 col-from-label">{{translate('State')}}<span class="text-danger text-danger">*</span></label>
            <div class="col-md-10">
                <select class="form-control aiz-selectpicker" data-live-search="true" name="state_id" required>
                    @if($courier && $courier->state_id)
                    <option value="{{ $courier->state_id }}" selected>{{ $courier->state->name }}</option>
                    @endif
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2 col-from-label">{{translate('City')}}<span class="text-danger text-danger">*</span></label>
            <div class="col-md-10">
             <select class="form-control aiz-selectpicker" data-live-search="true" name="city_id" required>
                 @if($courier && $courier->city_id)
                 <option value="{{ $courier->city_id }}" selected>{{ $courier->city ? $courier->city->name : 'City Not Available' }}</option>
                 @endif
             </select>
         </div>
     </div>



     <div class="row">
        <label class="col-md-2 col-form-label">{{ translate('Address') }} <span class="text-danger text-danger">*</span></label>
        <div class="col-md-10">
            <input type="text" class="form-control mb-3" placeholder="{{ translate('Address')}}" name="address" value="{{ $courier->address }}" required>
        </div>
    </div>
    <div class="form-group mb-0 text-right">
        <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
    </div>
</form>
</div>
</div>
<!-- Vehicle -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{ translate('Vehicle Settings') }}</h5>
    </div>
    <div class="card-body">
        <form class="" action="{{ route('courier.vehicle.update') }}" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="courier_id" value="{{ $courier->id }}">
            @csrf
            <div class="form-group row">
                <label class="col-md-2 col-from-label">{{translate('Vehicle Type')}}<span class="text-danger text-danger">*</span></label>
                <div class="col-md-10">
                   <select class="form-control aiz-selectpicker" data-live-search="true" data-placeholder="{{ translate('Select your Vehicle') }}" name="vehicle_type" required>
                    <option value="">{{ translate('Select your Vehicle') }}</option>
                    <option value="Car" @if($courier->vehicle_type == 'Car') selected @endif>Car</option>
                    <option value="Motorcycle" @if($courier->vehicle_type == 'Motorcycle') selected @endif>Motorcycle</option>
                    <option value="Bicycle" @if($courier->vehicle_type == 'Bicycle') selected @endif>Bicycle</option>
                    <option value="Foot" @if($courier->vehicle_type == 'Foot') selected @endif>Foot</option>
                </select>
            </div>
        </div>
        <div class="row">
            <label class="col-md-2 col-form-label">{{ translate('License Plate Number') }} <span class="text-danger text-danger">*</span></label>
            <div class="col-md-10">
                <input type="text" class="form-control mb-3" placeholder="{{ translate('License Plate Number')}}" name="license_plate_number" value="{{ $courier->license_plate_number }}" required>
            </div>
        </div>
        <div class="row">
            <label class="col-md-2 col-form-label">{{ translate('Years of Experience') }} <span class="text-danger text-danger">*</span></label>
            <div class="col-md-10">
                <input type="text" class="form-control mb-3" placeholder="{{ translate('Years of Experience')}}" name="years_of_experience" value="{{ $courier->years_of_experience }}" required>
            </div>
        </div>
       <div class="row">
    <label class="col-md-2 col-form-label">{{ translate('Engine power') }} <span class="text-danger text-danger">*</span></label>
    <div class="col-md-10">
        <select class="form-control mb-3" name="engine_power" required>
            <option value="">{{ translate('Select Engine Power') }}</option>
            <option value="20cc" @if($courier->engine_power == '20cc') selected @endif>20cc</option>
            <option value="50cc" @if($courier->engine_power == '50cc') selected @endif>50cc</option>
            <option value="80cc" @if($courier->engine_power == '80cc') selected @endif>80cc</option>
            <option value="125cc" @if($courier->engine_power == '125cc') selected @endif>125cc</option>
            <option value="250cc" @if($courier->engine_power == '250cc') selected @endif>250cc</option>
            <option value="660cc" @if($courier->engine_power == '660cc') selected @endif>660cc</option>
            <option value="1000cc" @if($courier->engine_power == '1000cc') selected @endif>1000cc</option>
            <option value="1500cc" @if($courier->engine_power == '1500cc') selected @endif>1500cc</option>
            <option value="2000cc" @if($courier->engine_power == '2000cc') selected @endif>2000cc</option>
            <option value="2500cc" @if($courier->engine_power == '2500cc') selected @endif>2500cc</option>
            <option value="3000cc" @if($courier->engine_power == '3000cc') selected @endif>3000cc</option>
            <option value="3500cc" @if($courier->engine_power == '3500cc') selected @endif>3500cc</option>
            <option value="4000cc" @if($courier->engine_power == '4000cc') selected @endif>4000cc</option>
            <option value="4500cc" @if($courier->engine_power == '4500cc') selected @endif>4500cc</option>
            <option value="5000cc" @if($courier->engine_power == '5000cc') selected @endif>5000cc</option>
            <option value="5500cc" @if($courier->engine_power == '5500cc') selected @endif>5500cc</option>
            <option value="6000cc" @if($courier->engine_power == '6000cc') selected @endif>6000cc</option>
        </select>
    </div>
</div>

          <div class="row">
            <label class="col-md-2 col-form-label">{{ translate('Wallet Number') }} <span class="text-danger text-danger">*</span></label>
            <div class="col-md-10">
                <input type="text" class="form-control mb-3" placeholder="{{ translate('Wallet Number')}}" name="wallet_number" value="{{ $courier->wallet_number }}" required>
            </div>
        </div>
        <div class="row">
            <label class="col-md-2 col-form-label">{{ translate('Delivery Rang in kms') }} <span class="text-danger text-danger">*</span></label>
            <div class="col-md-10">
                <input type="Number" class="form-control mb-3" placeholder="{{ translate('Delivery Rang in kms')}}" name="delivery_rang" value="{{ $courier->delivery_rang }}" required>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-md-2 col-form-label">{{ translate('Uoload Driving License') }}  <span class="text-danger text-danger">*</span></label>
            <div class="col-md-10">
                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                    </div>
                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                    <input type="hidden" name="driving_license" value="{{ $courier->driving_license }}" class="selected-files">
                </div>
                <div class="file-preview box sm">
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <label class="col-md-2 col-form-label">{{ translate('Uoload Vehicle Registration') }}  <span class="text-danger text-danger">*</span></label>
            <div class="col-md-10">
                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                    </div>
                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                    <input type="hidden" name="vehicle_registration" value="{{ $courier->vehicle_registration }}" class="selected-files">
                </div>
                <div class="file-preview box sm">
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-md-2 col-form-label">{{ translate('Uoload Vehicle Insurance') }}  <span class="text-danger text-danger">*</span></label>
            <div class="col-md-10">
                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                    </div>
                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                    <input type="hidden" name="vehicle_insurance" value="{{ $courier->vehicle_insurance }}" class="selected-files">
                </div>
                <div class="file-preview box sm">
                </div>
            </div>
        </div>
   
        <div class="form-group mb-0 text-right">
            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
        </div>
    </form>
</div>
</div>


@endsection

@section('script')

<script>
    $(document).ready(function () {

        $(document).on('change', '[name=country_id]', function() {
            var country_id = $(this).val();
            get_states(country_id);
        });

        $(document).on('change', '[name=state_id]', function() {
            var state_id = $(this).val();
            get_city(state_id);
        });
        
        function get_states(country_id) {
            $('[name="state"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('get-all-state')}}",
                type: 'POST',
                data: {
                    country_id  : country_id
                },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj != '') {
                        $('[name="state_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }

        function get_city(state_id) {
            $('[name="city"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('get-all-city')}}",
                type: 'POST',
                data: {
                    state_id: state_id
                },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj != '') {
                        $('[name="city_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }

    });

</script>

@if (addon_is_activated('delivery_boy') && get_setting('google_map') == 1)

<script>


    function initialize(id_format = '') {
        let default_longtitude = '';
        let default_latitude = '';
        @if (get_setting('google_map_longtitude') != '' && get_setting('google_map_longtitude') != '')
        default_longtitude = {{ get_setting('google_map_longtitude') }};
        default_latitude = {{ get_setting('google_map_latitude') }};
        @endif

        var lat = -33.8688;
        var long = 151.2195;

        if (document.getElementById('latitude').value != '' &&
            document.getElementById('longitude').value != '') {
            lat = parseFloat(document.getElementById('latitude').value);
        long = parseFloat(document.getElementById('longitude').value);
    } else if (default_longtitude != '' &&
        default_latitude != '') {
        lat = default_latitude;
        long = default_longtitude;
    }


    var map = new google.maps.Map(document.getElementById('map'), {
        center: {
            lat: lat,
            lng: long
        },
        zoom: 13
    });

    var myLatlng = new google.maps.LatLng(lat, long);

    var input = document.getElementById(id_format + 'searchInput');
            // console.log(input);
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

            var autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.bindTo('bounds', map);

            var infowindow = new google.maps.InfoWindow();
            var marker = new google.maps.Marker({
                map: map,
                position: myLatlng,
                anchorPoint: new google.maps.Point(0, -29),
                draggable: true,
            });

            map.addListener('click', function(event) {
                marker.setPosition(event.latLng);
                document.getElementById(id_format + 'latitude').value = event.latLng.lat();
                document.getElementById(id_format + 'longitude').value = event.latLng.lng();
                infowindow.setContent('Latitude: ' + event.latLng.lat() + '<br>Longitude: ' + event.latLng.lng());
                infowindow.open(map, marker);
            });

            google.maps.event.addListener(marker, 'dragend', function(event) {
                document.getElementById(id_format + 'latitude').value = event.latLng.lat();
                document.getElementById(id_format + 'longitude').value = event.latLng.lng();
                infowindow.setContent('Latitude: ' + event.latLng.lat() + '<br>Longitude: ' + event.latLng.lng());
                infowindow.open(map, marker);
            });

            autocomplete.addListener('place_changed', function() {
                infowindow.close();
                marker.setVisible(false);
                var place = autocomplete.getPlace();

                if (!place.geometry) {
                    window.alert("Autocomplete's returned place contains no geometry");
                    return;
                }

                // If the place has a geometry, then present it on a map.
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }
                /*
                marker.setIcon(({
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(35, 35)
                }));
                */
                marker.setPosition(place.geometry.location);
                marker.setVisible(true);

                var address = '';
                if (place.address_components) {
                    address = [
                    (place.address_components[0] && place.address_components[0].short_name || ''),
                    (place.address_components[1] && place.address_components[1].short_name || ''),
                    (place.address_components[2] && place.address_components[2].short_name || '')
                    ].join(' ');
                }

                infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
                infowindow.open(map, marker);

                //Location details
                for (var i = 0; i < place.address_components.length; i++) {
                    if (place.address_components[i].types[0] == 'postal_code') {
                        document.getElementById('postal_code').innerHTML = place.address_components[i].long_name;
                    }
                    if (place.address_components[i].types[0] == 'country') {
                        document.getElementById('country').innerHTML = place.address_components[i].long_name;
                    }
                }
                document.getElementById('location').innerHTML = place.formatted_address;
                document.getElementById(id_format + 'latitude').value = place.geometry.location.lat();
                document.getElementById(id_format + 'longitude').value = place.geometry.location.lng();
            });

        }
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_API_KEY') }}&libraries=places&language=en&callback=initialize" async defer></script>

    @endif

    @endsection

