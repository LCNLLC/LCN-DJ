@extends('seller.layouts.app')

@section('panel_content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
    	<div class="row align-items-center">
    		<div class="col-md-12">
    			<h1 class="h3">{{translate('Days & Discount')}}</h1>
    		</div>
    	</div>
    </div>
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <form class="" id="sort_cities" action="" method="GET">
                    <div class="card-header row gutters-5">
                        <div class="col text-center text-md-left">
                            <h5 class="mb-md-0 h6">{{ translate('Days & Discount') }}</h5>
                        </div>
                
                     
                    </div>
                </form>

                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th data-breakpoints="lg">#</th>
                                <th>{{translate('Name')}}</th>
                                <th>{{translate('Date')}}</th>
                                <th>{{translate('Discount')}}</th>
                                <th>{{translate('Show/Hide')}}</th>
                                <th data-breakpoints="lg" class="text-right">{{translate('Options')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($discount_days as $key => $discount_day)
                                <tr>
                                    <td>{{ ($key+1) + ($discount_days->currentPage() - 1) * $discount_days->perPage() }}</td>
                                    <td>{{ $discount_day->name }}</td>
                                    <td>{{ $discount_day->discount_date }}</td>
                                    <td>{{ $discount_day->discount}}%</td>
                                    <td>
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                          <input onchange="update_status(this)" value="{{ $discount_day->id }}" type="checkbox" <?php if($discount_day->status == 1) echo "checked";?> >
                                          <span class="slider round"></span>
                                        </label>
                                      </td>
                                    <td class="text-right">
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{ route('seller.discount_day.edit', ['id'=>$discount_day->id, 'lang'=>env('DEFAULT_LANGUAGE')]) }}" title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('seller.discount_day.destroy', $discount_day->id)}}" title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination">
                        {{ $discount_days->appends(request()->input())->links() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
    		<div class="card">
    			<div class="card-header">
    				<h5 class="mb-0 h6">{{ translate('Add Discount Day') }}</h5>
    			</div>

                <div class="card-body">
    				<form action="{{ route('seller.discount_day.store') }}" method="POST">
    					@csrf
                        <div class="form-group mb-3">
                            <label for="name">{{translate('Name')}}</label>
                            <input type="text"  placeholder="{{translate('Name')}}" name="name"  class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="name">{{translate('Date')}}</label>
                            <input type="date"  placeholder="{{translate('Date')}}" name="discount_date" value="{{date('Y-m-d')}}" class="form-control" required>
                        </div>
                        <div class="input-group">
                            <label for="name">{{translate('Discount')}}</label>
                        </div>
                        <div class="input-group mb-3">
                             
                                <input type="number" class="form-control" name="discount" min="1" step="1" placeholder="{{translate('Warranty Cost')}}" required>
                                <div class="input-group-prepend">
                                    <span class="input-group-text" >%</span>
                                </div>
                        </div>
    					<div class="form-group mb-3 text-right">
    						<button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
    					</div>
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
            $.post('{{ route('seller.discount_day.status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
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
