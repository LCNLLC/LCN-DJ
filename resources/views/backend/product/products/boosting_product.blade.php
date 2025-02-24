@extends('backend.layouts.app')

@section('content')

@php
    CoreComponentRepository::instantiateShopRepository();
    CoreComponentRepository::initializeCache();
@endphp

<div class="aiz-titlebar mt-2 mb-4">
  <div class="row align-items-center">
    <div class="col-md-6">
        <h1 class="h3">{{ translate('Products') }}</h1>
    </div>
</div>
</div>

<div class="card">
    <div class="card-header row gutters-5">
        <div class="col">
            <h5 class="mb-md-0 h6">{{ translate('All Boosting Products') }}</h5>
        </div>
        <div class="col-md-4">
            <form class="" id="sort_brands" action="" method="GET">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" id="search" name="search" @isset($search) value="{{ $search }}" @endisset placeholder="{{ translate('Search product') }}">
                </div>
            </form>
        </div>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th width="30%">{{ translate('Name')}}</th>
                    <th data-breakpoints="md">{{ translate('Category')}}</th>
                    <th class="text-center">{{ translate('Status')}}</th>
                    <th data-breakpoints="md">{{ translate('Action')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $key => $product)
                <tr>
                    <td>{{ ($key+1) + ($products->currentPage() - 1)*$products->perPage() }}</td>
                    <td>
                        <a href="{{ route('product', $product->slug) }}" target="_blank" class="text-reset">
                            {{ $product->getTranslation('name') }}
                        </a>
                    </td>
                    <td>
                        @if ($product->category != null)
                        {{ $product->category->getTranslation('name') }}
                        @endif
                    </td>
                    <td class="text-center">
                        @if($product->boosting_type == 'days')
                        <?php 
                            $savedDate = $product->boosting_plan_end;
                            $currentDate = date('Y-m-d H:i:s');
                            $timeDifference = strtotime($savedDate) - strtotime($currentDate);
                            $days = floor($timeDifference / (60 * 60 * 24));
                            $hours = floor(($timeDifference % (60 * 60 * 24)) / (60 * 60));
                            $minutes = floor(($timeDifference % (60 * 60)) / 60);

                        ?>
                     <a href="#" class="btn btn-success" style="padding: 1px 7px 1px 5px;"><i class="lab la-telegram-plane"></i>{{ translate('Boosting')}}</a><br>
                     <a href="#" class="btn btn-white" style="padding: 1px 7px 1px 5px;"> {{$days}} days,{{$hours}} hours, {{$minutes}} minutes </a>
                     @else
                      <a href="#" class="btn btn-success" style="padding: 1px 7px 1px 5px;"><i class="lab la-telegram-plane"></i>{{ translate('Boosting')}}</a><br>
                      <a href="#" class="btn btn-white" style="padding: 1px 7px 1px 5px;"> {{($product->boosting_click - $product->click)}} 
                      </a>
                      @endif
                    </td>
                    <td>
                        <a href="{{route('product.boost.plan.remove',$product->id)}}" class="btn btn-danger" style="padding: 1px 7px 1px 5px;"><i class="lab la-telegram-plane"></i>{{ translate('Remove from Boost')}}</a>
                     </td>
             </tr>
             @endforeach
         </tbody>
     </table>
     <div class="aiz-pagination">
        {{ $products->links() }}
    </div>
</div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
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

        $(document).ready(function(){
            //$('#container').removeClass('mainnav-lg').addClass('mainnav-sm');
        });

        function update_todays_deal(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('products.todays_deal') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Todays Deal updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
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
            $.post('{{ route('products.published') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Published products updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
        
        function update_approved(el){
            if(el.checked){
                var approved = 1;
            }
            else{
                var approved = 0;
            }
            $.post('{{ route('products.approved') }}', {
                _token      :   '{{ csrf_token() }}', 
                id          :   el.value, 
                approved    :   approved
            }, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Product approval update successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function update_featured(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('products.featured') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Featured products updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function sort_products(el){
            $('#sort_products').submit();
        }
        
        function bulk_delete() {
            var data = new FormData($('#sort_products')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('bulk-product-delete')}}",
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
