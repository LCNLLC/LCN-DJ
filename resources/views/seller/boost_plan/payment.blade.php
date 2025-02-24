@extends('seller.layouts.app')

@section('panel_content')
@php
    $tax = \App\Models\TaxValue::where('product_type', 3)->latest()->first();

    $taxAmount = $tax ? $tax->tax_value : 0;
    $taxType = $tax ? $tax->tax_type : null;

    $basePrice = $package->price;

    if ($taxType === 'amount') 
    {
        $taxValue = $taxAmount;
    } 
    elseif ($taxType === 'percent') 
    {
        $taxValue = ($basePrice * $taxAmount) / 100;
    } 
    else 
    {
        $taxValue = 0; 
    }

    $package->price_with_tax = $basePrice + $taxValue;
            
@endphp
<div class="aiz-titlebar mt-2 mb-4">
  <div class="row align-items-center">
    <div class="col-md-6">
        <h1 class="h3">{{ translate('Select Payment Method') }}</h1>
    </div>
</div>
</div>
<div class="card">
    <div class="card-body">
        <div class="row">
         <div class="col-md-12 text-center">
          <label><img src="{{ asset('public/assets/img/payment-method/stripe.png') }}" width="100px"></label>
          <form action="{{route('seller.stripe')}}" method="post">
            @csrf
            <input type="hidden" name="plan_id" value="{{$package->id}}">
            <input type="hidden" name="product_id" value="{{$request->product_id}}">
            <input type="hidden" name="ammount" value="{{$package->price_with_tax }}">
            <script
            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
            data-key="{{ $stripe_public_key }}"
            data-amount="<?php  echo $package->price_with_tax ;?>"
            data-name="Lacuna Market Place"
            data-description="Transection"
            data-image="{{ asset('public/assets/img/payment-method/stripe.png') }}"
            data-currency="{{$package->currency->code}}"
            data-email="" 

            >
        </script>
    </form>
</div>
</div>
</div>
</div>


@endsection

@section('modal')
@include('modals.delete_modal')
@endsection

@section('script')

@endsection
