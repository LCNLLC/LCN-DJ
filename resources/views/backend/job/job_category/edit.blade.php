@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Job Category Information')}}</h5>
</div>

<div class="col-lg-8 mx-auto">
    <div class="card">
        <div class="card-body p-0">
            <ul class="nav nav-tabs nav-fill border-light">
  				@foreach (\App\Models\Language::all() as $key => $language)
  					<li class="nav-item">
  						<a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3" href="{{ route('job.category.edit', ['id'=>$JobCategory->id, 'lang'=> $language->code] ) }}">
  							<img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
  							<span>{{ $language->name }}</span>
  						</a>
  					</li>
	            @endforeach
  			</ul>
            <form class="p-4" action="{{ route('job.category.update', $JobCategory->id) }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="lang" value="{{ $lang }}">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Name')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Name')}}" id="name" name="name" value="{{ $JobCategory->getTranslation('name', $lang) }}" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Status')}} </label>
                    <div class="col-sm-9">
                            <select class="form-control aiz-selectpicker" name="status" >
                                <option value="1" @if($JobCategory->status == 1) selected @endif>{{ translate('Active') }}</option>
                                <option value="0" @if($JobCategory->status == 0) selected @endif>{{ translate('In Active') }}</option>
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
