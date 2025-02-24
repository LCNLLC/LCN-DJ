@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-12 mx-auto">
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

            <form class="form-horizontal" action="{{route('general.email.update')}}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-12">
                       <textarea class="form-control" name="message" id="emessage" rows="10">{{ $general_email}}</textarea>
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
