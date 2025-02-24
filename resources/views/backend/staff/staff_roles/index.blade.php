@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{translate('All Role')}}</h1>
		</div>
        @can('add_staff_role')
        <div class="col-md-6 text-md-right">
            <a href="{{ route('roles.create') }}" class="btn btn-circle btn-info">
                <span>{{translate('Add New Role')}}</span>
            </a>
        </div>
        @endcan
    </div>
</div>
{{-- <div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Add New Permission')}}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('roles.permission') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="name">{{translate('Name')}}</label>
                        <input type="text" id="name" name="name" placeholder="{{ translate('Permission') }}" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="name">{{translate('Parent')}}</label>
                        <input type="text" id="parent" name="parent" placeholder="{{ translate('Parent') }}" class="form-control" required>
                    </div>
                    <div class="form-group mb-3 text-right">
                        <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> --}}

<div class="card">
    <form class="" id="sort_role" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col text-center text-md-left">
                <h5 class="mb-0 h6">{{translate('Roles')}}</h5>
            </div>
              @can('role_delete')
                <div class="dropdown mb-2 mb-md-0">
                    <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                        {{translate('Bulk Action')}}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#" onclick="bulk_delete()"> {{translate('Delete selection')}}</a>
                    </div>
                </div>
            @endcan
            
            <div class="col-md-2">
                <div class="form-group mb-0">
                    <input type="text" class="form-control form-control-sm" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type & Enter') }}">
                </div>
            </div>
        </div>
   
    <div class="card-body">
        <table class="table aiz-table">
            <thead>
                <tr>
                    @if(auth()->user()->can('role_delete'))
                        <th>
                            <div class="form-group">
                                <div class="aiz-checkbox-inline">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" class="check-all">
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>
                            </div>
                        </th>
                    @else
                        <th data-breakpoints="lg">#</th>
                    @endif
                    <th>{{translate('Name')}}</th>
                    <th>{{translate('Description')}}</th>
                    <th class="text-right" width="10%">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $key => $role)
                <tr>
                     @if(auth()->user()->can('role_delete'))
                            <td>
                                <div class="form-group d-inline-block">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" class="check-one" name="id[]" value="{{$role->id}}">
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>
                            </td>
                        @else
                            <td>{{ ($key+1) + ($roles->currentPage() - 1)*$role->perPage() }}</td>
                        @endif
                    <td>{{ $role->name}}</td>
                    <td>{{ $role->description}}</td>
                    <td class="text-right">
                        @can('edit_staff_role')
                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('roles.edit', ['id'=>$role->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">
                            <i class="las la-edit"></i>
                        </a>
                        @endcan
                        @if($role->id != 1 && auth()->user()->can('delete_staff_role'))
                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('roles.destroy', $role->id)}}" title="{{ translate('Delete') }}">
                            <i class="las la-trash"></i>
                        </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $roles->appends(request()->input())->links() }}
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

        function bulk_delete() {
            var data = new FormData($('#sort_role')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('bulk-role-delete')}}",
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
