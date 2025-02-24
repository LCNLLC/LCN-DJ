@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Job Degree Type Information')}}</h5>
</div>

<div class="col-lg-8 mx-auto">
    <div class="card">
        <div class="card-body p-0">
            <ul class="nav nav-tabs nav-fill border-light">
  				@foreach (\App\Models\Language::all() as $key => $language)
  					<li class="nav-item">
  						<a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3" href="{{ route('job.type.edit', ['id'=>$JobDegreeType->id, 'lang'=> $language->code] ) }}">
  							<img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
  							<span>{{ $language->name }}</span>
  						</a>
  					</li>
	            @endforeach
  			</ul>
            <form class="p-4" action="{{ route('job.degree.type.update', $JobDegreeType->id) }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="lang" value="{{ $lang }}">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Name')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Name')}}" id="name" name="name" value="{{ $JobDegreeType->getTranslation('name', $lang) }}" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Parent')}} </label>
                    <div class="col-sm-9">
                            <select class="form-control aiz-selectpicker" name="job_degree_level_id" required>
                              @foreach($jobDegreeLevels as $key => $jobDegreeLevel)
                                <option value="{{$jobDegreeLevel->id}}" @if ($jobDegreeLevel->id == $JobDegreeType->job_degree_level_id) selected @endif >{{ $jobDegreeLevel->getTranslation('name') }}</option>
                                @endforeach
                            </select>
                      </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Status')}} </label>
                    <div class="col-sm-9">
                            <select class="form-control aiz-selectpicker" name="status" >
                                <option value="1" @if($JobDegreeType->status == 1) selected @endif>{{ translate('Active') }}</option>
                                <option value="0" @if($JobDegreeType->status == 0) selected @endif>{{ translate('In Active') }}</option>
                            </select>
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
