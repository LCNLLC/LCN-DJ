@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center">
		<h1 class="h3">{{translate('All Job Degree Types')}}</h1>
	</div>
</div>

<div class="row">
	<div class="@if(auth()->user()->can('add_brand')) col-lg-7 @else col-lg-12 @endif">
		<div class="card">
		    <div class="card-header row gutters-5">
				<div class="col text-center text-md-left">
					<h5 class="mb-md-0 h6">{{ translate('Job Degree Types') }}</h5>
				</div>
				<div class="col-md-4">
					<form class="" id="sort_brands" action="" method="GET">
						<div class="input-group input-group-sm">
					  		<input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name & Enter') }}">
						</div>
					</form>
				</div>
		    </div>
		    <div class="card-body">
		        <table class="table aiz-table mb-0">
		            <thead>
		                <tr>
		                    <th>#</th>
		                    <th>{{translate('Name')}}</th>
		                    <th>{{translate('Degree level')}}</th>
		                    <th>{{translate('Status')}}</th>
		                    <th class="text-right">{{translate('Options')}}</th>
		                </tr>
		            </thead>
		            <tbody>
		                @foreach($JobDegreeTypes as $key => $JobDegreeType)
		                    <tr>
		                        <td>{{ ($key+1) + ($JobDegreeTypes->currentPage() - 1)*$JobDegreeTypes->perPage() }}</td>
		                        <td>{{ $JobDegreeType->getTranslation('name') }}</td>
		                        <td>{{ $JobDegreeType->jobDegreeLevel->getTranslation('name') }}</td>
								<td>
		                            <label class="aiz-switch aiz-switch-success mb-0">
		                                <input onchange="job_degree_type_status(this)" value="{{ $JobDegreeType->id }}" type="checkbox" <?php if ($JobDegreeType->status == 1) echo "checked"; ?> >
		                                <span class="slider round"></span>
		                            </label>
                                </td>
		                        <td class="text-right">
									@can('edit_job_degree_type')
										<a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('job.degree.type.edit', ['id'=>$JobDegreeType->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">
											<i class="las la-edit"></i>
										</a>
									@endcan
									@can('delete_job_degree_type')
										<a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('job.degree.type.destroy', $JobDegreeType->id)}}" title="{{ translate('Delete') }}">
											<i class="las la-trash"></i>
										</a>
									@endcan
		                        </td>
		                    </tr>
		                @endforeach
		            </tbody>
		        </table>
		        <div class="aiz-pagination">
                	{{ $JobDegreeTypes->appends(request()->input())->links() }}
            	</div>
		    </div>
		</div>
	</div>
	@can('add_job_type')
		<div class="col-md-5">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0 h6">{{ translate('Add New Job Type') }}</h5>
				</div>
				<div class="card-body">
					<form action="{{ route('job.degree.type.store') }}" method="POST">
						@csrf
						<div class="form-group mb-3">
							<label for="name">{{translate('Name')}} In English</label>
							<input type="text" placeholder="{{translate('Name')}}" name="name_en" value="{{old('name_en')}}" class="form-control" required>
						</div>
						<div class="form-group mb-3">
							<label for="name">{{translate('Name')}} In Portuguese</label>
							<input type="text" placeholder="{{translate('Name')}}" name="name_br" value="{{old('name_br')}}" class="form-control" required>
						</div>
						<div class="form-group mb-3">
							<label for="name">{{translate('Name')}} In Japanese</label>
							<input type="text" placeholder="{{translate('Name')}}" name="name_jp" value="{{old('name_jp')}}" class="form-control" required>
						</div>
						<div class="form-group mb-3">
                            <label for="status">{{translate('Parent')}}</label>
                            <select class="form-control aiz-selectpicker" name="job_degree_level_id" required>
                            	<option value="" selected>{{translate('None')}}</option>
                            	@foreach($jobDegreeLevels as $key => $jobDegreeLevel)
                                <option value="{{$jobDegreeLevel->id}}" >{{ $jobDegreeLevel->getTranslation('name') }}</option>
                                @endforeach
                            </select>
                        </div>
						<div class="form-group mb-3">
                            <label for="status">{{translate('Status')}}</label>
                            <select class="form-control aiz-selectpicker" name="status" >
                                <option value="1" selected>{{ translate('Active') }}</option>
                                <option value="0">{{ translate('In Active') }}</option>
                            </select>
                        </div>
                        <div class="form-group mb-3 text-right">
							<button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
						</div>
                    </div>
						
					</form>
				</div>
			</div>
		</div>
	@endcan
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
<script type="text/javascript">
    function sort_brands(el){
       // $('#sort_brands').submit();
    }
    function job_degree_type_status(el){
        if(el.checked){
            var status = 1;
        }
        else{
            var status = 0;
        }
        $.post('{{ route('job.degree.type.status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
            if(data == 1){
                AIZ.plugins.notify('success', '{{ translate('Job Degree Type updated successfully') }}');
            }
            else{
                AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
            }
        });
    }
</script>
@endsection
