@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-11 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Add Template')}}</h5>
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

            <form class="form-horizontal" action="{{route('add.email.language')}}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card-body">
                    <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('Name')}}</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" name="name" placeholder="{{ translate('name') }}" value="{{ old('subject') }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{ translate('Code')}}</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" name="code" placeholder="{{ translate('code') }}" value="{{ old('subject') }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">Subject (English)</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" name="subject_en" placeholder="{{ translate('Email Subject') }}" value="{{ old('subject') }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">Subject (Portugese)</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" name="subject_br" placeholder="{{ translate('Email Subject') }}" value="{{ old('subject') }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">Subject (Japanese)</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" name="subject_jp" placeholder="{{ translate('Email Subject') }}" value="{{ old('subject') }}">
                    </div>
                </div>
                

                <div class="form-group row">
                    <label class="col-md-2 col-from-label">
                        Body (English)
                    </label>

                    <div class="col-md-10">
                       <textarea class="form-control" name="message_en" rows="10"></textarea>
                </div>
            </div>

              <div class="form-group row">
                    <label class="col-md-2 col-from-label">
                        Body (Portugese)
                    </label>

                    <div class="col-md-10">
                       <textarea class="form-control" name="message_br" rows="10"></textarea>
                </div>
            </div>
              <div class="form-group row">
                    <label class="col-md-2 col-from-label">
                        Body (Japanese)
                    </label>

                    <div class="col-md-10">
                       <textarea class="form-control" name="message_jp" rows="10"></textarea>
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
