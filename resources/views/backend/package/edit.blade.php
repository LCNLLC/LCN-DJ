@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Edit package')}}</h5>
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
            <form class="form-horizontal" action="{{ route('package.update',$package->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{translate('Name')}}</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="name" placeholder="{{ translate('Name') }}" value="{{ old('Name',isset($package) ? $package->name : '') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{translate('short name')}}</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="short_name" placeholder="{{ translate('Short Name') }}" value="{{ old('short_name',isset($package) ? $package->short_name : '') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{translate('Select Plan Type')}}</label>
                        <div class="col-md-8">
                            <select class="form-control aiz-selectpicker" name="plan_type" id="plan_type" required>
                                <option value="">{{translate('Select')}}</option>
                                <option value="days" @if(old('plan_type', isset($package) ? $package->plan_type : '') == 'days') selected @endif>{{translate('Days')}}</option>
                                <option value="click" @if(old('plan_type', isset($package) ? $package->plan_type : '') == 'click') selected @endif>{{translate('Clicks')}}</option>
                            </select>
                        </div>
                    </div>
                    <div id="days" style="display: {{ isset($package) && $package->plan_type == 'days' ? 'block' : 'none' }}">
                     <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{translate('Permotion days')}}</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="promo_duration" placeholder="{{ translate('Permotion days') }}" value="{{ old('name',isset($package->promo_duration) ? $package->promo_duration : '') }}" >
                        </div>
                    </div>
                </div>

                <div id="click" style="display:  {{ isset($package) && $package->plan_type == 'click' ? 'block' : 'none' }}">
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{translate('Minimum click buy')}}</label>
                        <div class="col-md-8">
                            <input type="number" class="form-control" name="minimum_click_buy" placeholder="" value="{{ old('name',isset($package->minimum_click_buy) ? $package->minimum_click_buy : '') }}" >
                        </div>
                    </div>

                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-from-label" id="per_click_price" style="display: {{ isset($package) && $package->plan_type == 'click' ? 'block' : 'none' }}">
                        {{translate('Price per click')}}
                    </label>
                    <label class="col-md-3 col-from-label" id="price_lab_2" style="display: {{ isset($package) && $package->plan_type == 'days' ? 'block' : 'none' }}">
                        {{translate('Price')}}
                    </label>
                    <div class="col-md-8">
                        <input type="number" class="form-control" name="price" placeholder="{{ translate('Price') }}" value="{{ old('Price',isset($package) ? $package->price : '') }}">
                    </div>
                </div>
                <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{translate('Currency')}}</label>
                        <div class="col-md-8">
                            <select class="form-control aiz-selectpicker" name="currency_id">
                                @foreach ($currencies as $currency)
                                <option value="{{ $currency->id }}" @if(old('currency_id', isset($package) ? $package->currency_id : '') == $currency->id) selected @endif> {{ $currency->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{translate('Status')}}</label>
                        <div class="col-md-8">
                            <select class="form-control aiz-selectpicker" name="status">
                                <option value="1" @if(old('currency_id', isset($package) ? $package->status : '') == 1) selected @endif>{{translate('Active')}}</option>
                                <option value="0" @if(old('currency_id', isset($package) ? $package->status : '') == 0) selected @endif>{{translate('In Active')}}</option>
                            </select>
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

