@extends('backend.layouts.app')

@section('content')

<div class="col-lg-10  mx-auto">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Profile')}}</h5>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{ route('profile.update', Auth::user()->id) }}" method="POST" enctype="multipart/form-data">
                <input name="_method" type="hidden" value="PATCH">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" placeholder="{{translate('Name')}}" name="name" value="{{ Auth::user()->name }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Email')}}</label>
                    <div class="col-sm-9">
                        <input type="email" class="form-control" placeholder="{{translate('Email')}}" name="email" value="{{ Auth::user()->email }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="new_password">{{translate('New Password')}}</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" placeholder="{{translate('New Password')}}" name="new_password">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="confirm_password">{{translate('Confirm Password')}}</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" placeholder="{{translate('Confirm Password')}}" name="confirm_password">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 col-from-label">{{translate('Country')}}<span class="text-danger text-danger">*</span></label>
                    <div class="col-md-9">
                      <select class="form-control aiz-selectpicker" data-live-search="true" data-placeholder="{{ translate('Select your country') }}" name="country_id" required>
                        <option value="">{{ translate('Select your country') }}</option>
                        @foreach (\App\Models\Country::all() as $key => $country)
                        <option value="{{ $country->id }}" @if($shop && $shop->country_id == $country->id) selected @endif>{{ $country->name }}</option>

                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-from-label">{{translate('State')}}<span class="text-danger text-danger">*</span></label>
                <div class="col-md-9">
                    <select class="form-control aiz-selectpicker" data-live-search="true" name="state_id" required>
                        @if($shop && $shop->state_id)
                        <option value="{{ $shop->state_id }}" selected>{{ $shop->state->name }}</option>
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-from-label">{{translate('City')}}<span class="text-danger text-danger">*</span></label>
                <div class="col-md-9">
                   <select class="form-control aiz-selectpicker" data-live-search="true" name="city_id" required>
                       @if($shop && $shop->city_id)
                       <option value="{{ $shop->city_id }}" selected>{{ $shop->city ? $shop->city->name : 'City Not Available' }}</option>
                       @endif
                   </select>
               </div>
           </div>


           <div class="form-group row">
            <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Avatar')}} <small>(90x90)</small></label>
            <div class="col-md-9">
                <div class="input-group" data-toggle="aizuploader" data-type="image">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                    </div>
                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                    <input type="hidden" name="avatar" class="selected-files" value="{{ Auth::user()->avatar_original }}">
                </div>
                <div class="file-preview box sm">
                </div>
            </div>
        </div>
        <div class="form-group mb-0 text-right">
            <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
        </div>
    </form>
</div>
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


@endsection
