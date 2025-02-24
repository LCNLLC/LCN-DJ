@extends('backend.layouts.app')

@section('content')

@php
CoreComponentRepository::instantiateShopRepository();
CoreComponentRepository::initializeCache();
@endphp
<style>
    ul {
    list-style: none;
}

ol {
    list-style: none;
}
</style>

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Edit Job')}}</h5>
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
    <form class="form form-horizontal mar-top" action="{{route('job.update', $Job_post->id)}}" method="POST" enctype="multipart/form-data" id="choice_form">
        <div class="row gutters-5">
            <div class="col-lg-8">
                @csrf
                <input type="hidden" name="added_by" value="admin">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Job Information')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Job Title')}} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="name" value="{{$Job_post->name}}" placeholder="{{ translate('Job Title') }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Description')}} </label>
                            <div class="col-md-8">
                               <textarea class="form-control" name="description" placeholder="{{ translate('Short Description') }}">{{$Job_post->description}}</textarea>
                           </div>
                       </div>

                       <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{translate('Content')}} </label>
                        <div class="col-md-8">
                           <textarea class="aiz-text-editor" name="content">{{$Job_post->content}}</textarea>
                       </div>
                   </div>
                   <div class="form-group row">
                    <label class="col-md-3 col-from-label">{{translate('Company')}}<span class="text-danger">*</span></label>
                    <div class="col-md-8">
                      <select class="form-control aiz-selectpicker" data-live-search="true" data-placeholder="{{ translate('Select your company') }}" name="company_id" required>
                        <option value="">{{ translate('Select your company') }}</option>
                        @foreach (\App\Models\JobCompany::where('status',1)->get() as $key => $company)
                        <option value="{{ $company->id }}" @if($Job_post->company_id == $company->id) selected @endif>{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 pl-0 pt-1">
                    <a href="{{ route('job.company.create') }}" class="btn btn-warning btn-icon btn-sm"  title="Add New">
                        <i class="las la-plus"></i>
                    </a>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-from-label">{{translate('Number of positions')}}</label>
                <div class="col-md-8">
                    <input type="number" class="form-control" name="number_of_positions" 
                     value="{{$Job_post->number_of_positions}}">          
              </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-from-label">{{translate('Country')}}</label>
                <div class="col-md-8">
                  <select class="form-control aiz-selectpicker" data-live-search="true" data-placeholder="{{ translate('Select your country') }}" name="country_id" required>
                    <option value="">{{ translate('Select your country') }}</option>
                    @foreach (\App\Models\Country::all() as $key => $country)
                    <option value="{{ $country->id }}"  @if($Job_post->country_id == $country->id) selected @endif>{{ $country->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-from-label">{{translate('State')}}</label>
            <div class="col-md-8">
                <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="state_id" required>
                    <option value="{{ $Job_post->state_id }}" >{{ $Job_post->state->name }}</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-from-label">{{translate('City')}}</label>
            <div class="col-md-8">
               <select class="form-control mb-3 aiz-selectpicker" data-live-search="true" name="city_id" required>
                    <option value="{{ $Job_post->city_id }}" >{{ $Job_post->city->name }}</option>
               </select>
           </div>
       </div>
       <div class="form-group row">
        <label class="col-md-3 col-from-label">{{translate('Address')}} </label>
        <div class="col-md-8">
            <input type="text" class="form-control" name="address" value="{{ $Job_post->address }}" placeholder="{{ translate('Address') }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-3 col-from-label">{{translate('Latitude')}} </label>
        <div class="col-md-8">
          <input type="text" class="form-control" name="latitude" value="{{ $Job_post->latitude }}" placeholder="Ex: 1.462260">
          <small class="text-muted">
              <a class="help-block" href="https://www.latlong.net/convert-address-to-lat-long.html" target="_blank" rel="nofollow">
               Go here to get Latitude from address.    
           </a></small>
       </div>
   </div>
   <div class="form-group row">
    <label class="col-md-3 col-from-label">{{translate('Longitude')}} </label>
    <div class="col-md-8">
        <input type="text" class="form-control aiz-tag-input" name="longitude" value="{{ $Job_post->longitude }}" placeholder="Ex: 1.462260">
        <small class="text-muted">
            <a class="help-block" href="https://www.latlong.net/convert-address-to-lat-long.html" target="_blank" rel="nofollow">
               Go here to get Longitude from address.    
           </a></small>
       </div>
   </div>

   <div class="form-group row">
    <label class="col-md-3 col-from-label">{{translate('Salary From')}}</label>
    <div class="col-md-8">
        <input type="text" class="form-control" name="salary_from" value="{{ $Job_post->salary_from }}" placeholder="{{ translate('Salary from') }}" >
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-from-label">{{translate('Salary To')}}</label>
    <div class="col-md-8">
        <input type="text" class="form-control" name="salary_to" value="{{ $Job_post->salary_to }}" placeholder="{{ translate('Salary to') }}" >
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-from-label">{{translate('Salary Range')}}</label>
    <div class="col-md-8">
        <select class="form-control aiz-selectpicker" data-live-search="true" data-placeholder="{{ translate('Select your country') }}" name="salary_range" >
            <option value="monthly" @if($Job_post->country_id == 'monthly') selected @endif>Monthly</option>
            <option value="yearly"  @if($Job_post->country_id == 'yearly') selected @endif>Yearly</option>
            <option value="hourly"  @if($Job_post->country_id == 'hourly') selected @endif>Hourly</option>
            <option value="weekly"  @if($Job_post->country_id == 'weekly') selected @endif>Weekly</option>
        </select>
    </div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-from-label">{{translate('Currency')}}</label>
    <div class="col-md-8">
      <select class="form-control aiz-selectpicker" data-live-search="true" data-placeholder="{{ translate('Select your country') }}" name="currency_id" >
        <option value="">{{ translate('Select your currency') }}</option>
        @foreach (\App\Models\Currency::where('status',1)->get() as $key => $currency)
        <option value="{{ $currency->id }}" @if($Job_post->currency_id == $currency->id) selected @endif>{{ $currency->name }} {{ $currency->symbol }}</option>
        @endforeach
    </select>
</div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-from-label">{{translate('Start date')}}</label>
    <div class="col-md-8">
     <input type="date" class="form-control" value="{{ $Job_post->start_date}}" name="start_date">
 </div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-from-label">{{translate('Close date')}}</label>
    <div class="col-md-8">
     <input type="date" class="form-control" value="{{ $Job_post->application_closing_date}}" name="application_closing_date">
 </div>
</div>
<div class="form-group row">
    <label class="col-md-3 col-from-label">{{translate('Email')}}</label>
    <div class="col-md-8">
        <input type="email" class="form-control" name="email" value="{{ $Job_post->employer_colleagues }}" placeholder="{{ translate('Email') }}">
        <small class="text-muted">
          {{translate('We will notify your colleagues when someone applied')}}   
      </small>
  </div>
</div>




</div>
</div>
</div>

<div class="col-lg-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Featured Image')}}</h5>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="form-group col-md-12">
                    <label >{{translate('Featured Image')}} <small>(300x300)</small></label>
                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                        </div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input type="hidden" name="cover_image" class="selected-files" value="{{ $Job_post->cover_image }}">
                    </div>
                    <div class="file-preview box sm">
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Job categories')}}</h5>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="form-group col-md-12">
                        <ul style="padding-left: 0px;">
                            @foreach (\App\Models\JobCategory::where('status',1)->get() as $key => $category)
                                <li>
                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                    @if($job_category->contains($category->id)) checked @endif >
                                    <label >{{ $category->getTranslation('name') }}</label>
                                </li> 
                            @endforeach
                        </ul>
                </div>
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Job skills')}}</h5>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="form-group col-md-12">
                       <ul style="padding-left: 0px;">
                            @foreach (\App\Models\JobSkill::where('status',1)->get() as $key => $skill)
                                <li>
                                    <input type="checkbox" name="skills[]" value="{{ $skill->id }}" 
                                    @if($job_skill->contains($skill->id)) checked @endif>
                                    <label >{{ $skill->getTranslation('name') }}</label>
                                </li> 
                            @endforeach
                        </ul>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Job types')}}</h5>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="form-group col-md-12">
                      <ul style="padding-left: 0px;">
                            @foreach (\App\Models\JobType::where('status',1)->get() as $key => $type)
                                <li>
                                    <input type="checkbox" name="types[]" value="{{ $type->id }}" 
                                     @if($job_type->contains($type->id)) checked @endif>
                                    <label >{{ $type->getTranslation('name') }}</label>
                                </li> 
                            @endforeach
                        </ul>
                </div>
            </div>
        </div>
    </div>
      <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Job Shift')}}</h5>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="form-group col-md-12">
                      <select class="form-control aiz-selectpicker" data-live-search="true" data-placeholder="{{ translate('Select your country') }}" name="job_shift_id" >
                        <option value="">{{ translate('Job Shift') }}</option>
                        @foreach (\App\Models\JobShift::where('status',1)->get() as $key => $shift)
                        <option value="{{ $shift->id }}" @if($Job_post->shift_id == $shift->id) selected @endif>{{ $shift->getTranslation('name') }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Career level')}}</h5>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="form-group col-md-12">
                  <select class="form-control aiz-selectpicker" data-live-search="true" data-placeholder="{{ translate('Select your country') }}" name="career_level_id" >
                    <option value="">{{ translate('Career level') }}</option>
                    @foreach (\App\Models\JobCareerLevel::where('status',1)->get() as $key => $career_level)
                    <option value="{{ $career_level->id }}" @if($Job_post->career_level_id == $career_level->id) selected @endif>{{ $career_level->getTranslation('name') }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Functional area')}}</h5>
    </div>
    <div class="card-body">
        <div class="form-group row">
            <div class="form-group col-md-12">
              <select class="form-control aiz-selectpicker" data-live-search="true" data-placeholder="{{ translate('Select your country') }}" name="functional_area_id" >
                <option value="">{{ translate('Functional area') }}</option>
                @foreach (\App\Models\JobFunctionalArea::where('status',1)->get() as $key => $functional_area)
                <option value="{{ $functional_area->id }}"  @if($Job_post->functional_area_id == $functional_area->id) selected @endif>{{ $functional_area->getTranslation('name') }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Degree level')}}</h5>
    </div>
    <div class="card-body">
        <div class="form-group row">
            <div class="form-group col-md-12">
              <select class="form-control aiz-selectpicker" data-live-search="true" data-placeholder="{{ translate('Select your country') }}" name="degree_level_id" >
                <option value="">{{ translate('Degree level') }}</option>
                @foreach (\App\Models\JobDegreeLevel::where('status',1)->get() as $key => $degree_level)
                <option value="{{ $degree_level->id }}" @if($Job_post->degree_level_id == $degree_level->id) selected @endif>{{ $degree_level->getTranslation('name') }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Job experience')}}</h5>
    </div>
    <div class="card-body">
        <div class="form-group row">
            <div class="form-group col-md-12">
              <select class="form-control aiz-selectpicker" data-live-search="true" data-placeholder="{{ translate('Select your country') }}" name="job_experience_id" >
                <option value="">{{ translate('Job experience') }}</option>
                @foreach (\App\Models\JobExperience::where('status',1)->get() as $key => $experience)
                <option value="{{ $experience->id }}" @if($Job_post->job_experience_id == $experience->id) selected @endif>{{ $experience->getTranslation('name') }}</option>
                @endforeach
            </select>
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
