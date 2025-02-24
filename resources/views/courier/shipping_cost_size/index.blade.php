@extends('courier.layouts.app')


@section('panel_content')
    <div class="card">
        <form class="" action="" id="sort_commission_history" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-md-0 h6">{{ translate('Shipping Cost & Size') }}</h5>
                </div>
            
            </div>
        </form>
      <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th data-breakpoints="lg">#</th>
                                <th>{{translate('From city')}}</th>
                                <th>{{translate('From State')}}</th>
                                <th>{{translate('To city')}}</th>
                                <th>{{translate('To State')}}</th>
                                <th>{{translate('Size')}}</th>
                                <th>{{translate('Cost')}}</th>
                                <th>{{translate('Show/Hide')}}</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shipping_details as $key => $shipping_detail)
                                <tr>
                                    <td>{{ ($key+1) + ($shipping_details->currentPage() - 1) * $shipping_details->perPage() }}</td>
                                    <td>{{ $shipping_detail->from_city->getTranslation('name') }}</td>
                                    <td>{{ $shipping_detail->from_state->name }}</td>
                                    <td>{{ $shipping_detail->to_city->getTranslation('name') }}</td>
                                    <td>{{ $shipping_detail->to_state->name }}</td>
                                    <td>{{ $shipping_detail->size == 0 ? translate('Compact') : $shipping_detail->size }}</td>
                                    <td>
                                        @php
                                          if(get_setting('courier_commission_activation') == 1)
                                            {    
                                                $commission_percentage = get_setting('courier_commission');
                                                $shipping_cost = $shipping_detail->cost;
                                                $courier_shipping_cost = $shipping_cost * ($commission_percentage / 100);
                                              
                                            }
                                            @endphp
                                        {{ single_price($courier_shipping_cost) }}
                                    </td>
                                    <td>
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                          <input  value="{{ $shipping_detail->id }}" type="checkbox" <?php if($shipping_detail->status == 1) echo "checked";?> disabled >
                                          <span class="slider round"></span>
                                        </label>
                                      </td>
                             
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination">
                        {{ $shipping_details->appends(request()->input())->links() }}
                    </div>
                </div>
    </div>
@endsection

@section('script')
<script type="text/javascript">
 
</script>
@endsection
