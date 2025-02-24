@extends('backend.layouts.app')

@section('content')

@php
    CoreComponentRepository::instantiateShopRepository();
    CoreComponentRepository::initializeCache();
@endphp

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{translate('All company')}}</h1>
        </div>
            <div class="col text-right">
                <a href="{{ route('job.company.create') }}" class="btn btn-circle btn-info">
                    <span>{{translate('Add New company')}}</span>
                </a>
            </div>
    </div>
</div>
<br>

<div class="card">
    <form class="" id="sort_companys" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ translate('All company') }}</h5>
            </div>
            
        </div>
    
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th >#</th>
                        <th>LOGO</th>
                        <th>{{translate('Name')}}</th>
                        <th >{{translate('Status')}}</th>
                       <!--  <th>{{translate('Analytics')}}</th> -->
                        <th  class="text-right">{{translate('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($companies as $key => $company)
                    <tr>
                          <td>{{ ($key+1) + ($companies->currentPage() - 1)*$companies->perPage() }}</td>
                        <td>
                            <div class="row gutters-5 w-200px w-md-300px mw-100">
                                <div class="col-auto">
                                    <img src="{{ uploaded_asset($company->logo)}}" alt="Image" class="size-50px img-fit">
                                </div>
                            </div>
                        </td>
                      
                       
                        <td>
                            <span class="text-muted text-truncate-2">{{ $company->name }}</span>
                        </td>
                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input onchange="job_company_status(this)" value="{{ $company->id }}" type="checkbox" <?php if ($company->status == 1) echo "checked"; ?> >
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td class="text-right">
                            
                                 <a class="btn btn-success  btn-sm" href="{{route('job.company.analytics', ['id'=>$company->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Analytics') }}">
                                     {{translate('Analytics')}}
                                    </a>
                            @can('company_edit')
                               
                                    <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('job.company.edit', ['id'=>$company->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">
                                        <i class="las la-edit"></i>
                                    </a>
                          
                            @endcan
                         
                            @can('company_delete')
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('job.company.destroy', $company->id)}}" title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $companies->appends(request()->input())->links() }}
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
        
           function job_company_status(el){
        if(el.checked){
            var status = 1;
        }
        else{
            var status = 0;
        }
        $.post('{{ route('job.company.status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
            if(data == 1){
                AIZ.plugins.notify('success', '{{ translate('Company updated successfully') }}');
            }
            else{
                AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
            }
        });
    }

    </script>
@endsection
