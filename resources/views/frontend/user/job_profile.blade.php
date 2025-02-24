@extends('frontend.layouts.user_panel')

@section('panel_content')
<style>
    ul {
        list-style: none;
    }

    ol {
        list-style: none;
    }
</style>
<div class="aiz-titlebar mb-4">
  <div class="row align-items-center">
    <div class="col-md-6">
        <h1 class="fs-20 fw-700 text-dark">{{ translate('Manage Job Profile') }}</h1>
    </div>
</div>
</div>

<!-- Basic Info-->
<div class="card rounded-0 shadow-none border">
    <div class="card-header pt-4 border-bottom-0">
        <h5 class="mb-0 fs-18 fw-700 text-dark">{{ translate('Job Info')}}</h5>
    </div>
    <div class="card-body">
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
        <form class="form form-horizontal mar-top" action="{{route('job.profile.save')}}" method="POST" enctype="multipart/form-data" id="choice_form">
            <div class="row gutters-5">

                @csrf
                <div class="col-lg-12">
                 <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Basic Info')}}</h5>
                    </div>
                    <div class="card-body">
                       <div class="form-group row">
                        <label class="col-md-12 col-form-label fs-14 fs-14">{{ translate('Nationality') }}</label>
                        <div class="form-group col-md-12">
                            <select class="form-control aiz-selectpicker" data-live-search="true" data-placeholder="{{ translate('Select your nationality') }}" name="nationality" >
                                @foreach (\App\Models\Country::orderBy('name')->get() as $key => $country)
                                <option value="{{ $country->id }}" @if(isset($job_profile->nationality) && $country->id == $job_profile->nationality) selected @endif>
                                    {{ $country->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12 col-form-label fs-14 fs-14">{{ translate('Gender') }}</label>
                        <div class="form-group col-md-12">
                            <select class="form-control aiz-selectpicker" data-live-search="true" data-placeholder="{{ translate('Select your nationality') }}" name="gender" >
                      
                                <option value="Male" @if(isset($job_profile->gender) && $job_profile->gender == "Male") selected @endif>
                                   {{ translate('Male') }}
                                </option>
                                <option value="Female" @if(isset($job_profile->gender) && $job_profile->gender == "Female") selected @endif>
                                   {{ translate('Female') }}
                                </option>
                      
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                    <label class="col-md-12 col-form-label fs-14">{{ translate('Date of birth') }}</label>
                    <div class="col-md-12">
                        <input type="date" class="form-control rounded-0" placeholder="{{ translate('Date of birth')}}" name="dob" value="{{ isset($job_profile->dob)? $job_profile->dob : '' }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12 col-form-label fs-14">{{ translate('Age') }}</label>
                    <div class="col-md-12">
                        <input type="number" class="form-control rounded-0" placeholder="{{ translate('age')}}" name="age" value="{{isset($job_profile->age)? $job_profile->age : ''}}" required>
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
                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" @if(isset($job_profile->categories) && in_array($category->id, $job_profile->categories)) checked @endif>
                                    <label>{{ $category->getTranslation('name') }}</label>
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
                                <input type="checkbox" name="skills[]" value="{{ $skill->id }}" @if(isset($job_profile->skills) && in_array($skill->id, $job_profile->skills)) checked @endif>
                                <label>{{ $skill->getTranslation('name') }}</label>
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
                            <input type="checkbox" name="types[]" value="{{ $type->id }}" @if(isset($job_profile->types) && in_array($type->id, $job_profile->types)) checked @endif>
                            <label>{{ $type->getTranslation('name') }}</label>
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
                    <option value="{{ $shift->id }}" @if(isset($job_profile->job_shift_id) && $job_profile->job_shift_id == $shift->id) selected @endif>
                        {{ $shift->getTranslation('name') }}
                    </option>
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
                <option value="{{ $career_level->id }}" @if(isset($job_profile->career_level_id) &&  $job_profile->career_level_id == $career_level->id) selected @endif>
                    {{ $career_level->getTranslation('name') }}
                </option>

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
                <option value="{{ $functional_area->id }}" @if(isset($job_profile->functional_area_id) && $job_profile->functional_area_id == $functional_area->id) selected @endif>
                    {{ $functional_area->getTranslation('name') }}
                </option>

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
               <select class="form-control aiz-selectpicker" data-live-search="true" data-placeholder="{{ translate('Select your country') }}" name="degree_level_id">
                <option value="">{{ translate('Degree level') }}</option>
                @foreach (\App\Models\JobDegreeLevel::where('status', 1)->get() as $key => $degree_level)
                <option value="{{ $degree_level->id }}" @if(isset($job_profile->degree_level_id) && $job_profile->degree_level_id == $degree_level->id) selected @endif>
                    {{ $degree_level->getTranslation('name') }}
                </option>

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
                <option value="{{ $experience->id }}" @if(isset($job_profile->job_experience_id) && $job_profile->job_experience_id == $experience->id) selected @endif>
                    {{ $experience->getTranslation('name') }}
                </option>

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
            @if(empty($job_profile))

            <!-- If the job profile does not exist, show the Save button -->
            <button type="submit" name="status" value="0" class="btn btn-primary action-btn">{{ translate('Save') }}</button>
            @else
            <input type="hidden" name="id" value="{{$job_profile->id}}">
            <!-- If the job profile exists, show the Update button -->
            <button type="submit" name="status" value="1" class="btn btn-success action-btn">{{ translate('Update') }}</button>
            @endif
        </div>
    </div>
</div>

</div>
</form>







<

</div>

</div>
</div>

@endsection

@section('modal')
<!-- Address modal -->
@include('frontend.partials.address_modal')
@endsection

@section('script')
<script type="text/javascript">
    $('.new-email-verification').on('click', function() {
        $(this).find('.loading').removeClass('d-none');
        $(this).find('.default').addClass('d-none');
        var email = $("input[name=email]").val();

        $.post('{{ route('user.new.verify') }}', {_token:'{{ csrf_token() }}', email: email}, function(data){
            data = JSON.parse(data);
            $('.default').removeClass('d-none');
            $('.loading').addClass('d-none');
            if(data.status == 2)
                AIZ.plugins.notify('warning', data.message);
            else if(data.status == 1)
                AIZ.plugins.notify('success', data.message);
            else
                AIZ.plugins.notify('danger', data.message);
        });
    });
</script>

@if (get_setting('google_map') == 1)
@include('frontend.partials.google_map')
@endif

@endsection
