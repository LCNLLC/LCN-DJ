@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{ translate('Edit Tax Value') }}</h5>
</div>

<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Update Tax Info') }}</h5>
            </div>
            <div class="card-body p-0">
                <form class="p-4" action="{{ route('tax-value.update', $tax->id) }}" method="POST">
                    @method('PATCH')
                    @csrf
                    
                    <!-- Product Type Field -->
                    <div class="form-group row">
                        <label class="col-lg-3 control-label">{{ translate('Product Type') }}</label>
                        <div class="col-lg-9">
                            <select name="product_type" id="product_type" class="form-control" required>
                                @if($tax->product_type == 'new_product')
                                <option value="1" {{ $tax->product_type == 'new_product' ? 'selected' : '' }}>{{ translate('New Products') }}</option>
                                @elseif($tax->product_type == 'used_product')
                                <option value="2" {{ $tax->product_type == 'used_product' ? 'selected' : '' }}>{{ translate('Used Products') }}</option>
                                @elseif($tax->product_type == 'marketing_plan')
                                <option value="3" {{ $tax->product_type == 'marketing_plan' ? 'selected' : '' }}>{{ translate('Marketing Plans') }}</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <!-- Tax Value Field -->
                    <div class="form-group row">
                        <label class="col-lg-3 control-label">{{ translate('Tax Value') }}</label>
                        <div class="col-lg-9">
                            <input type="number" step="0.01" name="tax_value" class="form-control" placeholder="{{ translate('Tax Value') }}" value="{{ $tax->tax_value }}" required>
                        </div>
                    </div>

                    <!-- Tax Type Field -->
                    <div class="form-group row">
                        <label class="col-lg-3 control-label">{{ translate('Tax Type') }}</label>
                        <div class="col-lg-9">
                            <select name="tax_type" id="tax_type" class="form-control" required>
                                <option value="amount" {{ $tax->tax_type == 'amount' ? 'selected' : '' }}>{{ translate('Amount') }}</option>
                                <option value="percent" {{ $tax->tax_type == 'percent' ? 'selected' : '' }}>{{ translate('percent') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
