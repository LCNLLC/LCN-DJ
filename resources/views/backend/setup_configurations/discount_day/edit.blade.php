@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
  <h5 class="mb-0 h6">{{translate('Cost & Size Information')}}</h5>
</div>

<div class="row">
  <div class="col-lg-8 mx-auto">
    <div class="card">
      <div class="card-body p-0">
        <ul class="nav nav-tabs nav-fill border-light">
          @foreach (\App\Models\Language::all() as $key => $language)
          <li class="nav-item">
            <a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3" href="{{ route('discount_day.edit', ['id'=>$discount_day->id, 'lang'=> $language->code] ) }}">
             <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
             <span>{{ $language->name }}</span>
           </a>
         </li>
         @endforeach
       </ul>
       <form class="p-4" action="{{ route('discount_day.update', $discount_day->id) }}" method="POST" enctype="multipart/form-data">
        <input name="_method" type="hidden" value="PATCH">
        <input type="hidden" name="lang" value="{{ $lang }}">
        @csrf

        <div class="form-group mb-3">
            <label for="name">{{ translate('Name') }}</label>
            <input type="text" placeholder="{{ translate('Name') }}" name="name" value="{{ old('name', $discount_day->name) }}" class="form-control" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="discount_date">{{ translate('Date') }}</label>
            <input type="date" name="discount_date" value="{{ old('discount_date', \Carbon\Carbon::parse($discount_day->discount_date)->format('Y-m-d')) }}" class="form-control" required>
            @error('discount_date')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="input-group">
            <label for="discount">{{ translate('Discount') }}</label>
        </div>
        <div class="input-group mb-3">
            <input type="number" class="form-control" name="discount" min="1" step="1" value="{{ old('discount', $discount_day->discount) }}" placeholder="{{ translate('Discount') }}" required>
            <div class="input-group-prepend">
                <span class="input-group-text">%</span>
            </div>
            @error('discount')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="start_time">{{ translate('Start Time') }}</label>
            <input type="time" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($discount_day->start_time)->format('H:i')) }}" class="form-control" required>
            @error('start_time')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="end_time">{{ translate('End Time') }}</label>
            <input type="time" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($discount_day->end_time)->format('H:i')) }}" class="form-control" required>
            @error('end_time')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3 text-right">
            <button onclick="this.form.submit(); this.disabled=true; this.innerHTML='<span class=\'spinner-border spinner-border-sm\' role=\'status\' aria-hidden=\'true\'></span> {{ translate('Please wait...') }}';" type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
        </div>
    </form>

    </div>
  </div>
</div>
</div>

@endsection
