@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{translate('Searchs')}}</h1>
        </div>
    </div>
</div>
<br>

<div class="card">
    <form class="" id="sort_faq" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col text-center text-md-left">
                <h5 class="mb-md-0 h6">{{ translate('Customer Search History') }}</h5>
            </div>
        </div>
    </form>
    <div class="card-body">
        <table class="table mb-0 aiz-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Search')}}</th>
                    <th>{{translate('Count')}}</th>

                    <!-- <th class="text-right">{{translate('Options')}}</th> -->
                </tr>
            </thead>
            <tbody>
                @foreach($searches as $key => $searche)
                <tr>
                    <td>
                        {{ ($key+1) + ($searches->currentPage() - 1) * $searches->perPage() }}
                    </td>
                    <td>{{ $searche->query }}</td>
                    <td>{{ $searche->count }}</td>
                  
            </tr>
            @endforeach

        </tbody>
    </table>
    <div class="aiz-pagination">
        {{ $searches->appends(request()->input())->links() }}
    </div>
</div>
</div>

@endsection

@section('modal')
@include('modals.delete_modal')
@endsection


@section('script')

    <script type="text/javascript">


        function update_status(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('faq.status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
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
