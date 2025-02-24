@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{translate('Shipping Cost & Size')}}</h1>
        </div>
        @can('add_blog')
            <div class="col text-right">
                <a href="{{ route('shipping_cost_size.create') }}" class="btn btn-circle btn-info">
                    <span>{{translate('Add New Shipping Place')}}</span>
                </a>
            </div>
        @endcan
    </div>
</div>
<br>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form class="" id="sort_cities" action="" method="GET">
                    <div class="card-header row gutters-5">
                        <div class="col text-center text-md-left">
                            <h5 class="mb-md-0 h6">{{ translate('Cost & Sizes') }}</h5>
                        </div>
                        <div class="col-md-4" style="padding-top: 5px; padding-bottom: 5px;">
                            <select class="form-control aiz-selectpicker" data-live-search="true" id="sort_city" name="sort_city">
                                <option value="">{{ translate('Select City') }}</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}" @if ($sort_city == $city->id) selected @endif {{$sort_city}}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4" style="padding-top: 5px; padding-bottom: 5px;">
                            <select class="form-control aiz-selectpicker" data-live-search="true" id="sort_state" name="sort_state">
                                <option value="">{{ translate('Select State') }}</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}" @if ($sort_state == $state->id) selected @endif {{$sort_state}}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-primary" style="padding: 0.6rem 0.5rem;" type="submit">{{ translate('Filter') }}</button>
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
                                <th data-breakpoints="lg" class="text-right">{{translate('Options')}}</th>
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
                                    <td>{{ single_price($shipping_detail->cost) }}</td>
                                    <td>
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                          <input onchange="update_status(this)" value="{{ $shipping_detail->id }}" type="checkbox" <?php if($shipping_detail->status == 1) echo "checked";?> >
                                          <span class="slider round"></span>
                                        </label>
                                      </td>
                                    <td class="text-right">
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{ route('shipping_cost_size.edit', ['id'=>$shipping_detail->id, 'lang'=>env('DEFAULT_LANGUAGE')]) }}" title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('shipping_cost_size.destroy', $shipping_detail->id)}}" title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>
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
        </div>
    </div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection


@section('script')
    <script type="text/javascript">
        function sort_cities(el){
            $('#sort_cities').submit();
        }

        function update_status(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('shipping_cost_size.status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Status updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

    </script>

@endsection
