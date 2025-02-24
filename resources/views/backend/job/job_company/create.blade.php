@extends('backend.layouts.app')

@section('content')

@php
CoreComponentRepository::instantiateShopRepository();
CoreComponentRepository::initializeCache();
@endphp

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Add New Company')}}</h5>
</div>
<div class="">
    <!-- Error Meassages -->
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form class="form form-horizontal mar-top" action="{{route('job.company.store')}}" method="POST" enctype="multipart/form-data" id="choice_form">
        <div class="row gutters-5">
            <div class="col-lg-8">
                @csrf
                <input type="hidden" name="added_by" value="admin">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Company Information')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Company Name')}} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="name" value="{{old('name')}}" placeholder="{{ translate('Company Name') }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Description')}} </label>
                            <div class="col-md-8">
                             <textarea class="form-control" name="description" placeholder="{{ translate('Short Description') }}"></textarea>
                         </div>
                     </div>

                     <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{translate('Content')}} </label>
                        <div class="col-md-8">
                         <textarea class="aiz-text-editor" name="content" ></textarea>
                     </div>
                 </div>


                 <div class="form-group row">
                    <label class="col-md-3 col-from-label">{{translate('Company CEO / President')}} </label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="ceo" value="{{old('ceo')}}" placeholder="{{ translate('Company CEO / President') }}" >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-from-label">{{translate('Email')}} </label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="email" value="{{old('email')}}" placeholder="{{ translate('Ex: contact@your-company.com') }}" >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-from-label">{{translate('Phone')}} </label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="phone" value="{{old('phone')}}" placeholder="{{ translate('Phone number') }}" >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-from-label">{{translate('Website')}} </label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="website" value="{{old('website')}}" placeholder="https://" >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-from-label">{{translate('Year founded')}} </label>
                    <div class="col-md-8">
                        <input type="number" class="form-control" name="year_founded" value="{{old('year_founded')}}" placeholder="Ex: 1987" >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-from-label">{{translate('Number of offices')}} </label>
                    <div class="col-md-8">
                        <input type="number" class="form-control" name="number_of_offices" value="{{old('number_of_offices')}}" placeholder="Ex: 3" >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-from-label">{{translate('Number of employees')}} </label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="number_of_employees" value="{{old('number_of_employees')}}" placeholder="Ex: 100-250" >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-from-label">{{translate('Annual revenue')}} </label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="annual_revenue" value="{{old('annual_revenue')}}" placeholder="Ex: 2M" >
                    </div>
                </div>

                <div class="form-group row" id="brand">
                    <label class="col-md-3 col-from-label">{{translate('Country')}}</label>
                    <div class="col-md-8">
                      <select class="form-control aiz-selectpicker" data-live-search="true" data-placeholder="{{ translate('Select your country') }}" name="country_id" required>
                        <option value="">{{ translate('Select your country') }}</option>
                        @foreach (\App\Models\Country::all() as $key => $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="form-group row" id="brand">
                <label class="col-md-3 col-from-label">{{translate('State')}}</label>
                <div class="col-md-8">
                   <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="state_id" required>

                   </select>
               </div>
           </div>
           <div class="form-group row" id="brand">
            <label class="col-md-3 col-from-label">{{translate('City')}}</label>
            <div class="col-md-8">
             <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="city_id" required>

             </select>
         </div>
     </div>
     <div class="form-group row">
        <label class="col-md-3 col-from-label">{{translate('Address')}} </label>
        <div class="col-md-8">
            <input type="text" class="form-control" name="address" value="{{old('address')}}" placeholder="{{ translate('Address') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-from-label">{{translate('Postal code')}} </label>
        <div class="col-md-8">
         <input type="text" class="form-control" name="postal_code" value="{{old('postal_code')}}" placeholder="{{ translate('Postal code') }}">
     </div>
 </div>
 <div class="form-group row">
    <label class="col-md-3 col-from-label">{{translate('Latitude')}} </label>
    <div class="col-md-8">
      <input type="text" class="form-control" name="latitude" value="{{old('latitude')}}" placeholder="Ex: 1.462260">
      <small class="text-muted">
          <a class="help-block" href="https://www.latlong.net/convert-address-to-lat-long.html" target="_blank" rel="nofollow">
             Go here to get Latitude from address.    
         </a></small>
     </div>
 </div>
 <div class="form-group row">
    <label class="col-md-3 col-from-label">{{translate('Longitude')}} </label>
    <div class="col-md-8">
        <input type="text" class="form-control" name="longitude" value="{{old('longitude')}}" placeholder="Ex: 1.462260">
        <small class="text-muted">
            <a class="help-block" href="https://www.latlong.net/convert-address-to-lat-long.html" target="_blank" rel="nofollow">
             Go here to get Longitude from address.    
         </a></small>
     </div>
 </div>
</div>
</div>
</div>

<div class="col-lg-4">
    <div class="card" style="display: none;">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Flash Deal')}}</h5>
        </div>
        <div class="card-body">

            <div class="form-group mb-3">
                <label for="name">
                    {{translate('Discount Type')}}
                </label>
                <select class="form-control aiz-selectpicker"  id="flash_discount_type">
                    <option value="">{{ translate('Choose Discount Type') }}</option>
                    <option value="amount">{{translate('Flat')}}</option>
                    <option value="percent">{{translate('Percent')}}</option>
                </select>
            </div>
        </div>
    </div>

    <div class="card" style="display: none;">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Product Type')}}</h5>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-12">
                    <select id="product_type" class="form-control aiz-selectpicker" >
                        <option value="1" selected>{{translate('New Product')}}</option>
                        <option value="2">{{translate('Donation & Aid')}}</option>
                        <option value="3">{{translate('Used Products')}}</option>
                    </select>
                </div>
            </div>  
        </div>
    </div>



    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Logo And Cover')}}</h5>
        </div>
        <div class="card-body">

            <div class="form-group row">

                <div class="form-group col-md-12">
                    <label >{{translate('Logo Image')}} <small>(300x300)</small></label>
                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                        </div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input type="hidden" name="logo" class="selected-files">
                    </div>
                    <div class="file-preview box sm">
                    </div>
                    <small class="text-muted">{{translate('This image is visible in job box. Use 300x300 sizes image. Keep some blank space around main object of your image as we had to crop some edge in different devices to make it responsive.')}}</small>
                </div>
            </div>
            <div class="form-group row">

                <div class="form-group col-md-12">
                    <label >{{translate('Cover Image')}} <small>(600x600)</small></label>
                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                        </div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input type="hidden" name="cover_image" class="selected-files">
                    </div>
                    <div class="file-preview box sm">
                    </div>
                    <small class="text-muted">{{translate('These image is visible in  details page gallery. Use 600x600 sizes images.')}}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Social links')}}</h5>
        </div>
        <div class="card-body">
           <div class="form-group row">
            <div class="col-md-12">
                <label >Facebook Profile URL</label>
                <input type="text" class="form-control" name="facebook" value="{{old('facebook')}}" 
                placeholder="https://facebook.com/company-name">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-12">
                <label >Twitter Profile URL</label>
                <input type="text" id="twitter" class="form-control" name="twitter" value="{{old('twitter')}}" 
                placeholder="https://twitter.com/company-name">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-12">
                <label >Linkedin Profile URL</label>
                <input type="text" class="form-control" name="linkedin" value="{{old('linkedin')}}" 
                placeholder="https://linkedin.com/company/company-name">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-12">
                <label >Instagram Profile URL</label>
                <input type="text"  class="form-control" name="instagram" value="{{old('instagram')}}" 
                placeholder="https://instagram.com/company-name">
            </div>
        </div>
    </div>
</div>




</div>
<div class="col-12">
    <div class="btn-toolbar float-right mb-3" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group mr-2" role="group" aria-label="Third group">
            <button type="submit" name="status" value="0" class="btn btn-primary action-btn">{{ translate('Save & Unpublish') }}</button>
        </div>
        <div class="btn-group" role="group" aria-label="Second group">
            <button type="submit" name="status" value="1" class="btn btn-success action-btn">{{ translate('Save & Publish') }}</button>
        </div>
    </div>
</div>
</div>
</form>
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
