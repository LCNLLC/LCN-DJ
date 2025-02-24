@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Edit Template')}}</h5>
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

            <form class="form-horizontal" action="{{route('email.language.update', $lang->id)}}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card-body">
                   <div class="row ">
                    <div class="col-md-12">
                        <span class="font-weight-bold">Template : </span><span>{{$lang->name}}</span>
                    </div>
                    <div class="col-md-12">
                        <span class="font-weight-bold">Language : </span><span>{{$lang->lang}}</span>
                    </div>

                </div>
                 <br>
                <div class="form-group row">
                    <label class="col-md-2 col-from-label">{{translate('Subject')}}</label>
                    <div class="col-md-10" style="overflow-x: hidden;">
                        <input type="text" class="form-control" name="subject" placeholder="{{ translate('Email Subject') }}" value="{{ old('subject',isset($lang->subject) ? $lang->subject : '') }}">
                    </div>
                </div>
                

                <div class="form-group row">
                    <label class="col-md-2 col-from-label" id="per_click_price" >
                        {{translate('Body')}}
                    </label>

                    <div class="col-md-10">
                       <textarea class="form-control" name="message" rows="10">
                        {{$lang->message}}
                    </textarea>
                </div>
            </div>

            <div class="form-group mb-0 text-right">
                <button type="submit" class="btn btn-primary">{{translate('update')}}</button>
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
