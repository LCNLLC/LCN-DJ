@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('All Couriers')}}</h1>
        </div>
    </div>
</div>

<div class="card">
    <form class="" id="sort_couriers" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ translate('Couriers') }}</h5>
            </div>
            
            @can('delete_seller')
                <div class="dropdown mb-2 mb-md-0">
                    <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                        {{translate('Bulk Action')}}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#" onclick="bulk_delete()">{{translate('Delete selection')}}</a>
                    </div>
                </div>
            @endcan
            
            <div class="col-md-3 ml-auto">
                <select class="form-control aiz-selectpicker" name="approved_status" id="approved_status" onchange="sort_couriers()">
                    <option value="">{{translate('Filter by Approval')}}</option>
                    <option value="1"  @isset($approved) @if($approved == '1') selected @endif @endisset>{{translate('Approved')}}</option>
                    <option value="0"  @isset($approved) @if($approved == '0') selected @endif @endisset>{{translate('Non-Approved')}}</option>
                </select>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-0">
                  <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name, phone or email & Enter') }}">
                </div>
            </div>
        </div>
    
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                <tr>
                    
                    <th>
                        @if(auth()->user()->can('delete_courier'))
                            <div class="form-group">
                                <div class="aiz-checkbox-inline">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" class="check-all">
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>
                            </div>
                        @else
                            #
                        @endif
                    </th>
                    <th>{{translate('Name')}}</th>
                    <th data-breakpoints="lg">{{translate('Phone')}}</th>
                    <th data-breakpoints="lg">{{translate('Email Address')}}</th>
                    <th data-breakpoints="lg">{{translate('Verification Info')}}</th>
                    <th data-breakpoints="lg">{{translate('Approval')}}</th>
                    <th data-breakpoints="lg">{{ translate('Num. of Products') }}</th>
                    <th data-breakpoints="lg">{{ translate('Due to seller') }}</th>
                    <th width="10%">{{translate('Options')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($couriers as $key => $courier)
                    <tr>
                        <td>
                            @if(auth()->user()->can('delete_courier'))
                                <div class="form-group">
                                    <div class="aiz-checkbox-inline">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" class="check-one" name="id[]" value="{{$courier->id}}">
                                            <span class="aiz-square-check"></span>
                                        </label>
                                    </div>
                                </div>
                            @else
                                {{ ($key+1) + ($couriers->currentPage() - 1)*$couriers->perPage() }}
                            @endif
                        </td>
                        <td>@if($courier->user->banned == 1) <i class="fa fa-ban text-danger" aria-hidden="true"></i> @endif {{$courier->name}}</td>
                        <td>{{$courier->user->phone}}</td>
                        <td>{{$courier->user->email}}</td>
                        <td>
                            @if ($courier->verification_status != 1 && $courier->verification_info != null)
                                <a href="{{ route('sellers.show_verification_request', $courier->id) }}">
                                    <span class="badge badge-inline badge-info">{{translate('Show')}}</span>
                                </a>
                            @endif
                        </td>
                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input 
                                    @can('approve_courier') onchange="update_approved(this)" @endcan
                                    value="{{ $courier->id }}" type="checkbox" 
                                    <?php if($courier->verification_status == 1) echo "checked";?> 
                                    @cannot('approve_courier') disabled @endcan
                                >
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td>{{ $courier->user->products->count() }}</td>
                        <td>
                            @if ($courier->admin_to_pay >= 0)
                                {{ single_price($courier->admin_to_pay) }}
                            @else
                                {{ single_price(abs($courier->admin_to_pay)) }} ({{ translate('Due to Admin') }})
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-circle btn-soft-primary btn-icon dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                                    <i class="las la-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                    @can('view_courier_profile')
                                        <a href="#" onclick="show_courier_profile('{{$courier->id}}');"  class="dropdown-item">
                                            {{translate('Profile')}}
                                        </a>
                                    @endcan
                                    @can('login_as_courier')
                                        <a href="{{route('couriers.login', encrypt($courier->id))}}" class="dropdown-item">
                                            {{translate('Log in as this Courier')}}
                                        </a>
                                    @endcan
                                    @can('pay_to_courier')
                                        <a href="#" onclick="show_courier_payment_modal('{{$courier->id}}');" class="dropdown-item">
                                            {{translate('Go to Payment')}}
                                        </a>
                                    @endcan
                                    @can('courier_payment_history')
                                        <a href="{{route('couriers.payment_history', encrypt($courier->user_id))}}" class="dropdown-item">
                                            {{translate('Payment History')}}
                                        </a>
                                    @endcan
                                    @can('edit_courier')
                                        <a href="{{route('couriers.edit', encrypt($courier->id))}}" class="dropdown-item">
                                            {{translate('Edit')}}
                                        </a>
                                    @endcan
                                    @can('ban_courier')
                                        @if($courier->user->banned != 1)
                                            <a href="#" onclick="confirm_ban('{{route('couriers.ban', $courier->id)}}');" class="dropdown-item">
                                            {{translate('Ban this courier')}}
                                            <i class="fa fa-ban text-danger" aria-hidden="true"></i>
                                            </a>
                                        @else
                                            <a href="#" onclick="confirm_unban('{{route('couriers.ban', $courier->id)}}');" class="dropdown-item">
                                            {{translate('Unban this courier')}}
                                            <i class="fa fa-check text-success" aria-hidden="true"></i>
                                            </a>
                                        @endif
                                    @endcan
                                    @can('delete_seller')
                                        <a href="#" class="dropdown-item confirm-delete" data-href="{{route('courier.destroy', $courier->id)}}" class="">
                                            {{translate('Delete')}}
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
              {{ $couriers->appends(request()->input())->links() }}
            </div>
        </div>
    </form>
</div>

@endsection

@section('modal')
	<!-- Delete Modal -->
	@include('modals.delete_modal')

	<!-- Seller Profile Modal -->
	<div class="modal fade" id="profile_modal">
		<div class="modal-dialog">
			<div class="modal-content" id="profile-modal-content">

			</div>
		</div>
	</div>

	<!-- Seller Payment Modal -->
	<div class="modal fade" id="payment_modal">
	    <div class="modal-dialog">
	        <div class="modal-content" id="payment-modal-content">

	        </div>
	    </div>
	</div>

	<!-- Ban Seller Modal -->
	<div class="modal fade" id="confirm-ban">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title h6">{{translate('Confirmation')}}</h5>
					<button type="button" class="close" data-dismiss="modal">
					</button>
				</div>
				<div class="modal-body">
                    <p>{{translate('Do you really want to ban this courier?')}}</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
					<a class="btn btn-primary" id="confirmation">{{translate('Proceed!')}}</a>
				</div>
			</div>
		</div>
	</div>

	<!-- Unban Seller Modal -->
	<div class="modal fade" id="confirm-unban">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title h6">{{translate('Confirmation')}}</h5>
						<button type="button" class="close" data-dismiss="modal">
						</button>
					</div>
					<div class="modal-body">
							<p>{{translate('Do you really want to unban this courier?')}}</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-light" data-dismiss="modal">{{translate('Cancel')}}</button>
						<a class="btn btn-primary" id="confirmationunban">{{translate('Proceed!')}}</a>
					</div>
				</div>
			</div>
		</div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).on("change", ".check-all", function() {
            if(this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;                        
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;                       
                });
            }
          
        });
        
        function show_courier_payment_modal(id){
            $.post('{{ route('couriers.payment_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
                $('#payment_modal #payment-modal-content').html(data);
                $('#payment_modal').modal('show', {backdrop: 'static'});
                $('.demo-select2-placeholder').select2();
            });
        }

        function show_courier_profile(id){
            $.post('{{ route('couriers.profile_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
                $('#profile_modal #profile-modal-content').html(data);
                $('#profile_modal').modal('show', {backdrop: 'static'});
            });
        }

        function update_approved(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('couriers.approved') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Approved couriers updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function sort_couriers(el){
            $('#sort_couriers').submit();
        }

        function confirm_ban(url)
        {
            $('#confirm-ban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmation').setAttribute('href' , url);
        }

        function confirm_unban(url)
        {
            $('#confirm-unban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmationunban').setAttribute('href' , url);
        }
        
        function bulk_delete() {
            var data = new FormData($('#sort_couriers')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('bulk-courier-delete')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response == 1) {
                        location.reload();
                    }
                }
            });
        }

    </script>
@endsection
