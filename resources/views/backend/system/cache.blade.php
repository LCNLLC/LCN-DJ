@extends('backend.layouts.app')

@section('content')
<div class="row">
		<div class="col-md-12">
			<div class="tile">
				<h3 class="tile-title ">{{ translate('Clear Cache Commands') }}</h3>
				<div class="tile-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover">
							<thead>
							<tr>
								<th> # </th>
								<th> {{ translate('DESCRIPTION') }} </th>
								<th>{{ translate('Action') }}</th>
							</tr>
							</thead>
							<tbody>
							<tr>
								<td> 1 </td>
								<td> Clear System caching: database caching, static blocks... Run this command when you don't see the changes after updating data.</td>
								<td> <a href="{{route('system.clearcache')}}" class="btn btn-primary">Clear all System Cache</a></td>
							</tr>
							<tr>
								<td> 2 </td>
								<td> Clear compiled views to make views up to date.</td>
								<td> <a href="{{route('system.clearview')}}" class="btn btn-primary">Refresh Compiled Views</a></td>
							</tr>
							<tr>
								<td> 3 </td>
								<td> You might need to refresh the config caching when you change something on production environment.</td>
								<td> <a href="{{route('system.clearconfig')}}" class="btn btn-primary">Clear config Cache</a></td>
							</tr>
							<tr>
								<td> 4 </td>
								<td> Clear cache routing.</td>
								<td> <a href="{{route('system.clearroute')}}" class="btn btn-primary">Clear Route Cache</a></td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
