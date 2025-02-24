@extends('backend.layouts.app')
@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Customer Withdraw Request')}}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th data-breakpoints="lg">#</th>
                        <th data-breakpoints="lg">{{translate('Date')}}</th>
                        <th>{{translate('Customer')}}</th>
                        <th data-breakpoints="lg">{{translate('Total Amount to Pay')}}</th>
                        <th>{{translate('Requested Amount')}}</th>
                        <th data-breakpoints="lg" width="40%">{{ translate('Message') }}</th>
                        <th data-breakpoints="lg">{{ translate('Status') }}</th>
                        <th data-breakpoints="lg" width="15%" class="text-right">{{translate('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customer_withdraw_requests as $key => $customer_withdraw_request)
                        @php $user = \App\Models\User::find($customer_withdraw_request->user_id); @endphp
                            <tr>
                                <td>{{ ($key+1) + ($customer_withdraw_requests->currentPage() - 1)*$customer_withdraw_requests->perPage() }}</td>
                                <td>{{ $customer_withdraw_request->created_at }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ single_price(admin_pay_to_customer($customer_withdraw_request->user_id)) }}</td>
                                <td>{{ single_price($customer_withdraw_request->amount) }}</td>
                                <td>
                                    {{ $customer_withdraw_request->message }}
                                </td>
                                <td>
                                    @if ($customer_withdraw_request->status == 1)
                                    <span class="badge badge-inline badge-success">{{translate('Paid')}}</span>
                                    @else
                                    <span class="badge badge-inline badge-info">{{translate('Pending')}}</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a onclick="show_customer_payment_modal('{{$customer_withdraw_request->user_id}}','{{ $customer_withdraw_request->id }}');" class="btn btn-soft-warning btn-icon btn-circle btn-sm" href="javascript:void(0);" title="{{ translate('Pay Now') }}">
                                        <i class="las la-money-bill"></i>
                                    </a>
                                    @can('pay_to_seller')
                                        <a onclick="show_message_modal('{{ $customer_withdraw_request->id }}');" class="btn btn-soft-success btn-icon btn-circle btn-sm" href="javascript:void(0);" title="{{ translate('Message View') }}">
                                            <i class="las la-eye"></i>
                                        </a>
                                    @endcan
                                    @can('courier_payment_history')
                                        <a href="{{route('customers.payment_history', encrypt($customer_withdraw_request->user_id))}}" class="btn btn-soft-primary btn-icon btn-circle btn-sm"  title="{{ translate('Payment History') }}">
                                            <i class="las la-history"></i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $customer_withdraw_requests->links() }}
            </div>
        </div>
    </div>

@endsection

@section('modal')
<!-- payment Modal -->
<div class="modal fade" id="payment_modal">
  <div class="modal-dialog">
    <div class="modal-content" id="payment-modal-content">

    </div>
  </div>
</div>


<!-- Message View Modal -->
<div class="modal fade" id="message_modal">
  <div class="modal-dialog">
    <div class="modal-content" id="message-modal-content">

    </div>
  </div>
</div>


@endsection



@section('script')
  <script type="text/javascript">
      function show_customer_payment_modal(id, customer_withdraw_request_id){
          $.post('{{ route('customer_withdraw_request.payment_modal') }}',{_token:'{{ @csrf_token() }}', id:id, customer_withdraw_request_id:customer_withdraw_request_id}, function(data){
              $('#payment-modal-content').html(data);
              $('#payment_modal').modal('show', {backdrop: 'static'});
              $('.demo-select2-placeholder').select2();
          });
      }

      function show_message_modal(id){
          $.post('{{ route('customer_withdraw_request.message_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
              $('#message-modal-content').html(data);
              $('#message_modal').modal('show', {backdrop: 'static'});
          });
      }
  </script>

@endsection
