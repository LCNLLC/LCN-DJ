<form class="form-default" role="form" action="{{ route('addresses.update', $address_data->id) }}" method="POST">
    @csrf
    <div class="p-3">
        <!-- Address -->
        <div class="row">
            <div class="col-md-2">
                <label>{{ translate('Address')}}</label>
            </div>
            <div class="col-md-10">
                <textarea class="form-control mb-3 rounded-0" placeholder="{{ translate('Your Address')}}" rows="2" name="address" required>{{ $address_data->address }}</textarea>
            </div>
        </div>

        <!-- Country -->
        <div class="row">
            <div class="col-md-2">
                <label>{{ translate('Country')}}</label>
            </div>
            <div class="col-md-10">
                <div class="mb-3">
                    <select class="form-control aiz-selectpicker rounded-0" data-live-search="true" data-placeholder="{{ translate('Select your country')}}" name="country_id" id="edit_country" required>
                        <option value="">{{ translate('Select your country') }}</option>
                        @foreach (\App\Models\Country::where('status', 1)->get() as $key => $country)
                        <option value="{{ $country->id }}" @if($address_data->country_id == $country->id) selected @endif>
                            {{ $country->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- State -->
        <div class="row">
            <div class="col-md-2">
                <label>{{ translate('State')}}</label>
            </div>
            <div class="col-md-10">
                <select class="form-control mb-3 aiz-selectpicker rounded-0" name="state_id" id="edit_state"  data-live-search="true" required>
                    @foreach ($states as $key => $state)
                    <option value="{{ $state->id }}" @if($address_data->state_id == $state->id) selected @endif>
                        {{ $state->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- City -->
        <div class="row">
            <div class="col-md-2">
                <label>{{ translate('City')}}</label>
            </div>
            <div class="col-md-10">
                <select class="form-control mb-3 aiz-selectpicker rounded-0" data-live-search="true" name="city_id" required>
                    @foreach ($cities as $key => $city)
                    <option value="{{ $city->id }}" @if($address_data->city_id == $city->id) selected @endif>
                        {{ $city->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        @if (get_setting('google_map') == 1)
        <!-- Google Map -->
        <div class="row mt-3 mb-3">
            <input id="edit_searchInput" class="controls" type="text" placeholder="Enter a location">
            <div id="edit_map"></div>
            <ul id="geoData">
                <li style="display: none;">Full Address: <span id="location"></span></li>
                <li style="display: none;">Postal Code: <span id="postal_code"></span></li>
                <li style="display: none;">Country: <span id="country"></span></li>
                <li style="display: none;">Latitude: <span id="lat"></span></li>
                <li style="display: none;">Longitude: <span id="lon"></span></li>
            </ul>
        </div>
        <!-- Longitude -->
        <div class="row">
            <div class="col-md-2" id="">
                <label for="exampleInputuname">{{ translate('Longitude')}}</label>
            </div>
            <div class="col-md-10" id="">
                <input type="text" class="form-control mb-3 rounded-0" id="edit_longitude" name="longitude" value="{{ $address_data->longitude }}" readonly="">
            </div>
        </div>
        <!-- Latitude -->
        <div class="row">
            <div class="col-md-2" id="">
                <label for="exampleInputuname">{{ translate('Latitude')}}</label>
            </div>
            <div class="col-md-10" id="">
                <input type="text" class="form-control mb-3 rounded-0" id="edit_latitude" name="latitude" value="{{ $address_data->latitude }}" readonly="">
            </div>
        </div>
        @endif

        <!-- Postal code -->
        <div class="row">
            <div class="col-md-2">
                <label>{{ translate('Postal code')}}</label>
            </div>
            <div class="col-md-10">
                <input type="text" class="form-control mb-3 rounded-0" placeholder="{{ translate('Your Postal Code')}}" value="{{ $address_data->postal_code }}" name="postal_code" value="" required>
            </div>
        </div>

        <!-- Phone -->
        <div class="row">
            <div class="col-md-2">
                <label>{{ translate('Phone')}}</label>
            </div>
            <div class="col-md-10">
                <input type="text" class="form-control mb-3 rounded-0" placeholder="{{ translate('+880')}}" value="{{ $address_data->phone }}" name="phone" value="" required>
            </div>
        </div>

        <!-- Delivery Destination Type -->
        <div class="row">
            <div class="col-md-2">
                <label>{{ translate('Delivery Destination Type')}}</label>
            </div>
            <div class="col-md-10">
                <div class="mb-3">
                    <select class="form-control aiz-selectpicker rounded-0" data-live-search="true" data-placeholder="{{ translate('Select your country') }}" name="delivery_destination" required>
                        <option value="">{{ translate('Select your destination') }}</option>
                        <option value="house" {{ $address_data->delivery_destination === 'house' ? 'selected' : '' }}>{{ translate('House') }}</option>
                        <option value="complex" {{ $address_data->delivery_destination === 'complex' ? 'selected' : '' }}>{{ translate('Complex') }}</option>
                        <option value="building" {{ $address_data->delivery_destination === 'building' ? 'selected' : '' }}>{{ translate('Building') }}</option>
                        <option value="other" {{ $address_data->delivery_destination === 'other' ? 'selected' : '' }}>{{ translate('Other') }}</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Placement Specification -->
        <div class="row">
            <div class="col-md-2">
                <label>{{ translate('Placement Specification')}}</label>
            </div>
            <div class="col-md-10">
                <div class="mb-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="placement_specification" id="entrance_door" value="entrance_door" {{ $address_data->placement_specification === 'entrance_door' ? 'checked' : '' }}>
                        <label class="form-check-label" for="entrance_door">{{ translate('At the Entrance Door') }}</label>
                    </div>
                    
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="placement_specification" id="delivery_box" value="delivery_box" {{ $address_data->placement_specification === 'delivery_box' ? 'checked' : '' }}>
                        <label class="form-check-label" for="delivery_box">{{ translate('Delivery Box') }}</label>
                    </div>
                    
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="placement_specification" id="gas_meter_box" value="gas_meter_box" {{ $address_data->placement_specification === 'gas_meter_box' ? 'checked' : '' }}>
                        <label class="form-check-label" for="gas_meter_box">{{ translate('Gas Meter Box') }}</label>
                    </div>
                    
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="placement_specification" id="bicycle_basket" value="bicycle_basket" {{ $address_data->placement_specification === 'bicycle_basket' ? 'checked' : '' }}>
                        <label class="form-check-label" for="bicycle_basket">{{ translate('Bicycle Basket') }}</label>
                    </div>
                    
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="placement_specification" id="garage" value="garage" {{ $address_data->placement_specification === 'garage' ? 'checked' : '' }}>
                        <label class="form-check-label" for="garage">{{ translate('Garage') }}</label>
                    </div>
                    
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="placement_specification" id="receptionist_manager" value="receptionist_manager" {{ $address_data->placement_specification === 'receptionist_manager' ? 'checked' : '' }}>
                        <label class="form-check-label" for="receptionist_manager">{{ translate('Receptionist/Building Manager') }}</label>
                    </div>
                    
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="placement_specification" id="hand_delivery" value="hand_delivery" {{ $address_data->placement_specification === 'hand_delivery' ? 'checked' : '' }}>
                        <label class="form-check-label" for="hand_delivery">{{ translate('Hand delivery') }}</label>
                    </div>
                    
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="placement_specification" id="neighbors" value="neighbors" {{ $address_data->placement_specification === 'neighbors' ? 'checked' : '' }}>
                        <label class="form-check-label" for="neighbors">{{ translate('Neighbors') }}</label>
                    </div>
                    
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="placement_specification" id="none_of_these" value="none_of_these" {{ $address_data->placement_specification === 'none_of_these' ? 'checked' : '' }}>
                        <label class="form-check-label" for="none_of_these">{{ translate('None of these settings') }}</label>
                    </div>
                </div>
            </div>
        </div>


        <!-- Save button -->
        <div class="form-group text-right">
            <button type="submit" class="btn btn-primary rounded-0 w-150px">{{translate('Save')}}</button>
        </div>
    </div>
</form>