@extends('seller.layouts.app')

@section('panel_content')

<div class="card">
    <form class="" id="sort_products" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ translate('All Bids') }}</h5>
            </div>  
            
        </div>

        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th >#</th>
                       
                        <th>Bidder</th>
                        <th data-breakpoints="sm">Product</th>
                        <th data-breakpoints="md">Bid Amount</th>
                        <th data-breakpoints="md">{{translate('Status')}}</th>

                        <th data-breakpoints="lg">{{translate('Bidding Status')}}</th>
                        <th data-breakpoints="sm" class="text-right">{{translate('Options')}}</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($bids as $key => $bid)
                    <tr>

                        <td>{{ ($key+1) + ($bids->currentPage() - 1)*$bids->perPage() }}</td>
                        
                        <td>
                            {{ $bid->user->name }}
                       </td>
                       <td>
                           {{ $bid->product->name }}
                       </td>
                         <td>
                           {{ single_price($bid->amount) }}
                       </td>
                     
                    <td>
                        @if($bid->status =='Peding')
                        <span class="badge badge-inline badge-success" style="padding: 12px;">{{ $bid->status }}</span>
                        @elseif($bid->status =='Accepted')
                        <span class="badge badge-inline badge-warning" style="padding: 12px;">{{ $bid->status }}</span>
                        @else
                        <span class="badge badge-inline badge-danger" style="padding: 12px;">{{ $bid->status }}</span>
                        @endif

                    </td>
                    <td>
                        <select class="form-control" name="bidding_status" data-id="{{ $bid->id }}" onchange="bid_status(this)" id="bidding_status" style="width: 15%;">
                            <option value="1" <?php if ($bid->status == 'Peding') echo "selected"; ?>>{{translate('Peding')}}</option>
                            <option value="2" <?php if ($bid->status == 'Accepted') echo "selected"; ?> >{{translate('Accepted')}}</option>
                            <option value="3" <?php if ($bid->status == 'Rejected') echo "selected"; ?> >{{translate('Rejected')}}</option>
                        </select>
                    </td>

                    <td class="text-right">
                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('product_bids_destroy.seller', $bid->id)}}" title="{{ translate('Delete') }}">
                            <i class="las la-trash"></i>
                        </a>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $bids->appends(request()->input())->links() }}
        </div>
    </div>
</form>
</div>


@endsection

@section('modal')
@include('modals.delete_modal')
@endsection

@section('script')
<script type="text/javascript">
    function bid_status(el){
        var bid_status_val = $('#bidding_status').val();
        var bid_id = $(el).data('id');
        $.post('{{ route('seller.bid.status') }}', {_token:'{{ csrf_token() }}', id:bid_id, status:bid_status_val}, function(data){
            if(data == 1){
                AIZ.plugins.notify('success', '{{ translate('Bid status updated successfully') }}');
            }
            else{
                AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
            }
        });
    }
</script>
@endsection
