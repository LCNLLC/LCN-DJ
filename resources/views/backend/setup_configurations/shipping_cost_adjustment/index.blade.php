@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-6">
        <div class="card">
          <div class="card-header">
              <h5 class="mb-0 h6">{{translate('shipping cost Adjustment')}}</h5>
          </div>
          <div class="card-body">
              <form class="form-horizontal" action="{{ route('business_settings.shipping_cost_adjustment.update') }}" method="POST" enctype="multipart/form-data">
                 @csrf
                 <div class="form-group row">
                    <label class="col-md-4 col-from-label">{{translate('Cost Adjustment')}}</label>
                    <div class="col-md-8">
                        <input type="hidden" name="types[]" value="shipping_cost_adjustment">
                        <div class="input-group">
                            <input type="number" lang="en"  step="0.01" value="{{ get_setting('shipping_cost_adjustment') }}" placeholder="{{translate('Price Adjustment')}}" name="shipping_cost_adjustment" class="form-control">
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="col-lg-6">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Note')}}</h5>
        </div>
        <div class="card-body">
            <ul class="list-group">
                <li class="list-group-item text-muted">
                    @php
                    $priceAdjustment = get_setting('shipping_cost_adjustment');
                    $message = ($priceAdjustment > 0)
                    ? translate('An additional'). ' ' . $priceAdjustment . '% ' . translate(' of shipping cost will be added.')
                    : translate('A deduction of'). ' ' . abs($priceAdjustment) . '% ' . translate('shipping cost will be deducted.');
                    @endphp

                    {{ $message }}

                </li>
            </ul>
        </div>
    </div>
</div>
<div class="col-lg-6">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0 h6 text-center">{{translate('shipping cost Adjustment Activatation')}}</h3>
        </div>
        <div class="card-body text-center">
            <label class="aiz-switch aiz-switch-success mb-0">
                <input type="checkbox" onchange="updateSettings(this, 'shipping_pr_adjustment_active')" <?php if(get_setting('shipping_pr_adjustment_active') == 1) echo "checked";?>>
                <span class="slider round"></span>
            </label>
        </div>
    </div>
</div>
</div>

@endsection

@section('script')
<script type="text/javascript">
    function updateSettings(el, type){
        if($(el).is(':checked')){
            var value = 1;
        }
        else{
            var value = 0;
        }

        $.post('{{ route('business_settings.update.activation') }}', {_token:'{{ csrf_token() }}', type:type, value:value}, function(data){
            if(data == '1'){
                AIZ.plugins.notify('success', '{{ translate('Settings updated successfully') }}');
            }
            else{
                AIZ.plugins.notify('danger', 'Something went wrong');
            }
        });
    }
</script>
@endsection
