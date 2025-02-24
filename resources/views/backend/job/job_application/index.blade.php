@extends('backend.layouts.app')

@section('content')

@php
    CoreComponentRepository::instantiateShopRepository();
    CoreComponentRepository::initializeCache();
@endphp

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">{{translate('All Application')}}</h1>
        </div>
    </div>
</div>
<br>

<div class="card">
    <form class="" id="sort_Applications" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ translate('All Application') }}</h5>
            </div>
            
        </div>
    
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th >#</th>
                        <th>{{translate('Job Name')}}</th>
                        <th>{{translate('Phone')}}</th>
                        <th>{{translate('Email')}}</th>
                        <th >{{translate('Change Status')}}</th>
                        <th >{{translate('Status')}}</th>
                        <th  class="text-right">{{translate('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jobApplications as $key => $application)
                    <tr>
                        <td>{{ ($key+1) + ($jobApplications->currentPage() - 1)*$jobApplications->perPage() }}</td>                     
                        <td>
                            <span class="text-muted text-truncate-2">{{ isset($application->job->name) ? $application->job->name : '' }}</span>
                        </td>
                          <td>
                            <span class="text-muted text-truncate-2">{{ $application->phone }}</span>
                        </td>
                          <td>
                            <span class="text-muted text-truncate-2">{{ $application->email }}</span>
                        </td>
                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input onchange="job_application_status(this)" value="{{ $application->id }}" type="checkbox" <?php if ($application->status == 1) echo "checked"; ?> >
                                <span class="slider round"></span>
                            </label>
                        </td>
                          <td>
                           @if($application->status == 1)
                           		  <span class="badge badge-inline badge-success" style="padding: 12px;">Checked</span>
                           @else
                           		  <span class="badge badge-inline badge-warning" style="padding: 12px;">Pending</span>
                           @endif
                        </td>
                        <td class="text-right">
            
                           <!--  @can('Application_edit')
                               
                                    <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('job.application.edit', ['id'=>$application->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">
                                        <i class="las la-edit"></i>
                                    </a>
                          
                            @endcan -->
                         
                            @can('Application_delete')
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('job.application.destroy', $application->id)}}" title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $jobApplications->appends(request()->input())->links() }}
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
        
           function job_application_status(el){
        if(el.checked){
            var status = 1;
        }
        else{
            var status = 0;
        }
        $.post('{{ route('job.application.status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
            if(data == 1){
                AIZ.plugins.notify('success', '{{ translate('Application updated successfully') }}');
            }
            else{
                AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
            }
        });
    }

    </script>
@endsection
