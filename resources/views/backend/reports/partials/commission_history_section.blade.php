<form action="{{ route('commission-log.index') }}" method="GET">
    <div class="card-header row gutters-5">
        <div class="col text-center text-md-left">
            <h5 class="mb-md-0 h6">{{ translate('Commission History') }}</h5>
        </div>
        @if(Auth::user()->user_type != 'seller')
        <div class="col-md-3 ml-auto">
            <select id="demo-ease" class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" name="seller_id">
                <option value="">{{ translate('Choose Seller') }}</option>
                @foreach (App\Models\User::where('user_type', '=', 'seller')->get() as $key => $seller)
                    <option value="{{ $seller->id }}" @if($seller->id == $seller_id) selected @endif >
                        {{ $seller->name }}
                    </option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="col-md-3">
            <div class="form-group mb-0">
                <input type="text" class="form-control form-control-sm aiz-date-range" id="search" name="date_range"@isset($date_range) value="{{ $date_range }}" @endisset placeholder="{{ translate('Daterange') }}">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group mb-0">
                <input type="number" class="form-control form-control-sm" name="transaction_value" placeholder="{{ translate('Min Transaction Value') }}">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group mb-0">
                <select class="form-control form-control-sm aiz-selectpicker" name="payment_method">
                    <option value="">{{ translate('Payment Method') }}</option>
                    <option value="cash">{{ translate('Cash') }}</option>
                    <option value="card">{{ translate('Card') }}</option>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <button class="btn btn-md btn-primary" type="submit">
                {{ translate('Filter') }}
            </button>
        </div>
    </div>
</form>
<div class="card-body">
    @if($summary)
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6>{{ translate('Total Transactions') }}: {{ $summary->total_transactions }}</h6>
                    <h6>{{ translate('Total Value') }}: {{ single_price($summary->total_value) }}</h6>
                </div>
            </div>
        </div>
    </div>
    @endif

    <table class="table aiz-table mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th data-breakpoints="lg">{{ translate('Order Code') }}</th>
                <th>{{ translate('Admin Commission') }}</th>
                <th>{{ translate('Seller Earning') }}</th>
                {{--  <th>{{ translate('Payment Method') }}</th>  --}}
                <th>{{ translate('Transaction Value') }}</th>
                <th data-breakpoints="lg">{{ translate('Created At') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($commission_history as $key => $history)
            <tr>
                <td>{{ ($key+1) }}</td>
                <td>
                    @if(isset($history->order))
                        {{ $history->order->code }}
                    @else
                        <span class="badge badge-inline badge-danger">
                            {{ translate('Order Deleted') }}
                        </span>
                    @endif
                </td>


                <td>{{ single_price($history->admin_commission) }}</td>
                <td>{{ single_price($history->seller_earning) }}</td>
                {{--  <td>{{ ucfirst($history->payment_method) }}</td>  --}}
                <td>{{ single_price($history->transaction_value) }}</td>
                <td>{{ $history->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="aiz-pagination mt-4">
        {{ $commission_history->appends(request()->input())->links() }}
    </div>
</div>
