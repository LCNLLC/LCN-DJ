@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-11 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Add FAQ')}}</h5>
            </div>
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

               <form action="{{ route('faq.store') }}" method="POST">
                @csrf
                <div class="card-body">
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('artical code')}}</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" name="artical_code" placeholder="{{ translate('artical code') }}" value="{{ old('artical_code') }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('subject')}} (English)</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" name="subject_en" placeholder="{{ translate('subject') }}" value="{{ old('subject') }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('subject')}} (Portugese)</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" name="subject_br" placeholder="{{ translate('subject') }}" value="{{ old('subject') }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('subject')}} (Japanese)</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" name="subject_jp" placeholder="{{ translate('subject') }}" value="{{ old('subject') }}">
                    </div>
                </div>
                

                <div class="form-group row">
                    <label class="col-md-2 col-from-label">
                        {{ translate('description')}} (English)
                    </label>

                    <div class="col-md-10">
                       <textarea class="form-control" name="description_en" rows="10"></textarea>
                </div>
            </div>

              <div class="form-group row">
                    <label class="col-md-2 col-from-label">
                        {{ translate('description')}} (Portugese)
                    </label>

                    <div class="col-md-10">
                       <textarea class="form-control" name="description_br" rows="10"></textarea>
                </div>
            </div>
              <div class="form-group row">
                    <label class="col-md-2 col-from-label">
                        {{ translate('description')}} (Japanese)
                    </label>

                    <div class="col-md-10">
                       <textarea class="form-control" name="description_jp" rows="10"></textarea>
                </div>
            </div>

            <div class="form-group mb-0 text-right">
                <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
            </div>
        </div>
    </form>
</div>
</div>
</div>

@endsection
<script src="{{ static_asset('assets/js/nicEdit-latest.js') }}" ></script>
<script>
    bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
</script>
