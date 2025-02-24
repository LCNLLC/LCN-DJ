@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{translate('All Packages')}}</h1>
        </div>
        @can('add_blog')
            <div class="col text-right">
                <a href="{{ route('package.create') }}" class="btn btn-circle btn-info">
                    <span>{{translate('Add New Package')}}</span>
                </a>
            </div>
        @endcan
    </div>
</div>
<br>

<div class="card">
    <form class="" id="sort_blogs" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col text-center text-md-left">
                <h5 class="mb-md-0 h6">{{ translate('All Packages') }}</h5>
            </div>
            
            <div class="col-md-2">
                <div class="form-group mb-0">
                    <input type="text" class="form-control form-control-sm" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type & Enter') }}">
                </div>
            </div>
        </div>
        </form>
        <div class="card-body">
            <table class="table mb-0 aiz-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{translate('Name')}}</th>
                        <th >{{translate('short name')}}</th>
                        <th >{{translate('Plan Type')}}</th>
                        <th >{{translate('Days')}}</th>
                         <th >{{translate('Minimum click buy')}}</th>
                        <th >{{translate('price')}}</th>
                        <th >{{translate('Status')}}</th>
                        <th class="text-right">{{translate('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($packages as $key => $package)
                    <tr>
                        <td>
                            {{ ($key+1) + ($packages->currentPage() - 1) * $packages->perPage() }}
                        </td>
                        <td>
                            {{ $package->name }}
                        </td>
                           <td>
                            {{ $package->short_name }}
                        </td>
                         <td>
                            {{ $package->plan_type }}
                        </td>
                        <td>
                            {{ $package->promo_duration }}
                        </td>
                        <td>
                            {{ $package->minimum_click_buy }}
                        </td>

                        <td>
                           {{ $package->currency->symbol }} {{ $package->price }}
                        </td>
                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" 
                                    @can('publish_blog') onchange="change_status(this)" @endcan
                                    value="{{ $package->id }}" 
                                    <?php if($package->status == 1) echo "checked";?>
                                    @cannot('publish_blog') disabled @endcan
                                >
                                <span></span>
                            </label>
                        </td>
                        <td class="text-right">
                            @can('edit_blog')
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{ route('package.edit',$package->id)}}" title="{{ translate('Edit') }}">
                                    <i class="las la-pen"></i>
                                </a>
                            @endcan
                            @can('delete_blog')
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('package.destroy', $package->id)}}" title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $packages->appends(request()->input())->links() }}
            </div>
        </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection


@section('script')

    <script type="text/javascript">
        function change_status(el){
            var status = 0;
            if(el.checked){
                var status = 1;
            }
            $.post('{{ route('package.change-status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Change package status successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>

@endsection
