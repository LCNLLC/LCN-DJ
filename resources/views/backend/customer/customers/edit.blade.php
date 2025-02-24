@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Edit Customer') }}</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('customers.index') }}" class="btn btn-soft-secondary">
                <i class="las la-angle-left"></i> {{ translate('Back to Customers') }}
            </a>
        </div>
    </div>
</div>

<div class="card">
    <form action="{{ route('customers.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label for="name">{{ translate('Name') }}</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">{{ translate('Email') }}</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">{{ translate('Phone') }}</label>
                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                @error('phone')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">{{ translate('Password') }} <small>({{ translate('Leave blank if not changing') }})</small></label>
                <input type="password" class="form-control" id="password" name="password">
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary">{{ translate('Update Customer') }}</button>
        </div>
    </form>
</div>
@endsection
