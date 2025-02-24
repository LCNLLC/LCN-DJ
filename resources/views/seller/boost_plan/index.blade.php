@extends('seller.layouts.app')

@section('panel_content')
<style>
    .pricingTable{
        margin-top:30px;
        text-align: center;
        position: relative;
    }
    .pricingTable .pricing_heading:after{
        content: "";
        width: 36px;
        height: 29.5%;
        background:#EF476F;
        position: absolute;
        top: -1px;
        right: 0;
        z-index: 2;
        transform: skewY(45deg) translateY(18px);
        transition: all 0.4s ease 0s;
    }
    .pricingTable .title{
        font-size: 20px;
        font-weight: 700;
        line-height: 30px;
        color: #000;
        text-transform: uppercase;
        background: #EF476F;
        padding: 15px 0 0;
        margin: 0 35px 0 0;
        transition: all 0.4s ease 0s;
    }
    .pricingTable .value{
        display: block;
        font-size: 20px;
        font-weight: 700;
        color: #000;
        background: #EF476F;
        padding: 5px 0 10px;
        margin: 0 35px 0 0;
        transition: all 0.4s ease 0s;
    }
    .pricingTable:hover .title,
    .pricingTable:hover .value{
        color: #fff;
    }
    .pricingTable .month{
        display: block;
        font-size: 13px;
        font-weight: 500;
        color: #fff;
        text-transform: uppercase;
    }
    .pricingTable .content{
        border-left: 1px solid #f2f2f2;
        position: relative;
    }
    .pricingTable .content:after{
        content: "";
        width: 35px;
        height: 100%;
        background: #f8f8f8;
        box-shadow: 9px 9px 20px #ddd;
        position: absolute;
        top: 0;
        right: 0;
        z-index: 1;
        transform: skewY(-45deg) translateY(-18px);
        transition: all 0.4s ease 0s;
    }
    .pricingTable .content ul{
        padding: 0;
        margin: 0 35px 0 0;
        list-style: none;
        background: #fff;
    }
    .pricingTable .content ul li{
        display: block;
        font-size: 15px;
        color: #333;
        line-height: 23px;
        padding: 11px 0;
        border-bottom: 1px solid #f2f2f2;
    }
    .pricingTable .link{
        background: #fff;
        padding: 20px 0;
        margin-right: 35px;
        border-bottom: 1px solid #f2f2f2;
    }
    .pricingTable .link a,input{
        display: inline-block;
        padding: 9px 20px;
        font-weight: 700;
        color: #EF476F;
        text-transform: uppercase;
        border: 1px solid #EF476F;
        background: #fff;
        transition: all 0.4s ease 0s;
    }
    .pricingTable:hover .link a{
        color: #fff;
        background: #06D6A0;
        border: 1px solid #06D6A0;
    }
    .pricingTable:hover .link input{
        color: #fff;
        background: #06D6A0;
        border: 1px solid #06D6A0;
    }
    .pricingTable:hover .pricing_heading:after,
    .pricingTable:hover .title,
    .pricingTable:hover .value{
        background:#06D6A0;
    }
    @media only screen and (max-width: 990px){
        .pricingTable{
            margin-bottom: 35px;
        }
    }
    #click_input{
        /*width: 20%;*/
        padding: 4px 0px;
    }

    .number-input {
      display: flex;
      align-items: center;
  }

  .quantity {
      width: 50px;
      text-align: center;
  }

  .increment,
  .decrement {
      background-color: #f1f1f1;
      border: none;
      cursor: pointer;
      padding: 5px 10px;
  }

  .increment:hover,
  .decrement:hover {
      background-color: #ddd;
  }
  .vertical-center {
    display: flex;
    align-items: center; /* Vertically center items */
}

@php
$tax = \App\Models\TaxValue::where('product_type', 3)->latest()->first();
@endphp

</style>

<div class="aiz-titlebar mt-2 mb-4">
  <div class="row align-items-center">
    <div class="col-md-6">
        <h1 class="h3">{{ translate('Product Boosting Plans') }}</h1>
    </div>
</div>
</div>
<div class="card">
    <div class="card-body">
        <div class="row">
            @foreach ($packages as $key => $package)
              @php 

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
            <div class="col-md-4 col-sm-6">
                <div class="pricingTable">
                    <div class="pricing_heading">
                        <h3 class="title">{{ translate('Pricing Plan') }}</h3>
                        <span class="value">
                           {{ $package->currency->symbol}}{{ round($package->price_with_tax,0)}}
                           @if($package->plan_type == 'click')
                           / {{ translate('Per Click') }} 

                           @endif
                           <span class="month">({{translate('Including Tax')}} {{ round($taxAmount)}}%)</span>
                           <span class="month">{{ $package->name }}</span>
                       </span>

                   </div>
                   <div class="content">
                    <form action="{{route('seller.buy.plan')}}" method="post">
                        <ul>
                            <li>{{ $package->short_name }}</li>
                            <li> 
                                @if($package->plan_type == 'click')
                                <div class="row no-gutters vertical-center">
                                    <label class="col-md-4 col-from-label">{{ translate('Quantity') }}</label>
                                    <div class="col-md-5">
                                        <div class="number-input">
                                          <input type="hidden" name="plan_type" value="{{ $package->plan_type}}">
                                          <input type="hidden" id="per_click_price" name="per_click_price" value="{{ round($package->price_with_tax,0)}}">
                                          <input type="hidden" id="minimum_click_buy" name="minimum_click_buy" value="{{ $package->minimum_click_buy }}">
                                          <button class="decrement" type="button" >-</button>
                                          <input type="text" id="click_input" class="quantity"  name="quantity" min="{{ $package->minimum_click_buy }}"   value="{{ $package->minimum_click_buy }}">
                                          <button class="increment" type="button" >+</button>
                                      </div>
                                  </div>
                                  <div class="col-md-3">
                                   ={{ $package->currency->symbol}}  <span id="price_div">{{ ($package->minimum_click_buy * $package->price_with_tax)}}</span>
                               </div>
                           </div>
                           @else
                           {{ translate('Days') }} ({{$package->promo_duration}})
                           @endif

                       </li>
                       <li>{{translate('Increase your sale buy plan to boost your product on the top of category list')}}</li>
                   </ul>
                   @if(isset($product_id))

                   @csrf
                   <input type="hidden" name="product_id" value="{{$product_id}}">
                   <input type="hidden" name="plan_id"    value="{{ $package->id }}">
                   <div class="link">
                    <input type="submit" name="submit" value="{{translate('Buy Now')}}">
                </div>
            </form>
            @else
            <div class="link">
                <a href="{{ route('seller.products') }}">{{translate('Select Product')}}</a>
            </div>
            @endif

        </div>
    </div>
</div>

@endforeach
</div>
<div class="aiz-pagination">
    {{ $packages->links() }}
</div>
</div>
</div>


@endsection

@section('modal')
@include('modals.delete_modal')
@endsection

@section('script')
<script type="text/javascript">
    function update_featured(el){
        if(el.checked){
            var status = 1;
        }
        else{
            var status = 0;
        }
        $.post('{{ route('seller.products.featured') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
            if(data == 1){
                AIZ.plugins.notify('success', '{{ translate('Featured products updated successfully') }}');
            }
            else{
                AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                location.reload();
            }
        });
    }

    function update_published(el){
        if(el.checked){
            var status = 1;
        }
        else{
            var status = 0;
        }
        $.post('{{ route('seller.products.published') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
            if(data == 1){
                AIZ.plugins.notify('success', '{{ translate('Published products updated successfully') }}');
            }
            else if(data == 2){
                AIZ.plugins.notify('danger', '{{ translate('Please upgrade your package.') }}');
                location.reload();
            }
            else{
                AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                location.reload();
            }
        });
    }


    document.addEventListener("DOMContentLoaded", function () {
      var priceSpan = document.getElementById("price_div");
      const incrementButton = document.querySelector(".increment");
      const decrementButton = document.querySelector(".decrement");
      const quantityInput = document.querySelector(".quantity");
      const minimum_click_buy = document.querySelector("#minimum_click_buy");
      const per_click_price   = document.querySelector("#per_click_price");
      if (incrementButton) {
          incrementButton.addEventListener("click", function () {
            quantityInput.value = parseInt(quantityInput.value) + 1;
            var total  = (parseInt(quantityInput.value) * parseInt(per_click_price.value) );
            priceSpan.innerHTML = total;
        });
      }
      if (decrementButton) {
          decrementButton.addEventListener("click", function () {
            const currentValue = parseInt(quantityInput.value);
            var min = parseInt(minimum_click_buy.value);
            if (currentValue > min) {
              quantityInput.value = currentValue - 1;
              var total  = (parseInt(quantityInput.value) * parseInt(per_click_price.value));
              priceSpan.innerHTML = total;
          }
      });
      }
  });

</script>
@endsection
