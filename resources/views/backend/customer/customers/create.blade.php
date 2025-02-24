@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Add New Customer') }}</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('customers.index') }}" class="btn btn-soft-secondary">
                <i class="las la-angle-left"></i> {{ translate('Back to Customers') }}
            </a>
        </div>
    </div>
</div>

<div class="card">
    <form action="{{ route('customers.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label for="name">{{ translate('Name') }}</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">{{ translate('Email') }}</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">{{ translate('Phone') }}</label>
                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required>
                @error('phone')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">{{ translate('Password') }}</label>
                <input type="password" class="form-control" id="password" name="password" required>
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary">{{ translate('Create Customer') }}</button>
        </div>
    </form>
</div>
@endsection
