@extends('backend.layouts.app')

@section('content')


<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row">
        <div class="col-md-6">
            @php
                // Call the helper function directly in the Blade view
                $deals = get_running_and_upcoming_flash_deals();
            @endphp

            <div class="alert alert-primary" role="alert">
                <h5>Currently Running Flash Deals <img width="35" src="{{ url('assets/img/deal/deal-svgrepo-com (1).svg') }}" alt=""></h5>
                <div class="row">

                @if($deals['running']->isNotEmpty())
                @foreach($deals['running'] as $deal)
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-warning">
                                <h5 class="text-white">
                                    {{ $deal->title }}
                                </h5>
                                <img width="35" src="{{ url('assets/img/deal/stopwatch-svgrepo-com.svg') }}" alt="">

                            </div>
                            <div class="card-body">
                                <strong>Start Date:</strong> {{ \Carbon\Carbon::createFromTimestamp($deal->start_date)->format('d-m-Y H:i:s') }}<br>
                                <strong>End Date:</strong> {{ \Carbon\Carbon::createFromTimestamp($deal->end_date)->format('d-m-Y H:i:s') }}

                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else

                        <strong class="ml-4" > <img class="mr-2" width="30" src="{{ url('assets/img/deal/not-running-deal.svg') }}" alt=""> No running flash deals.</strong>


                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="alert alert-info" role="alert">
                <h5>Upcoming Flash Deals (Within 2 Days)
                    <img width="35" src="{{ url('assets/img/deal/deal-svgrepo-com (1).svg') }}" alt="">
                </h5>
                <div class="row">
                    @if($deals['upcoming']->isNotEmpty())
                        @foreach($deals['upcoming'] as $deal)
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-dark">
                                        <h5 class="text-white mb-0">
                                            {{ $deal->title }}
                                        </h5>
                                        <img width="35" src="{{ url('assets/img/deal/upcoming.svg') }}" alt="not">
                                    </div>
                                    <div class="card-body">
                                        <strong>Start Date:</strong> {{ \Carbon\Carbon::createFromTimestamp($deal->start_date)->format('d-m-Y H:i:s') }}<br>
                                        <strong>End Date:</strong> {{ \Carbon\Carbon::createFromTimestamp($deal->end_date)->format('d-m-Y H:i:s') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12">
                            <strong class="ml-4">
                                <img class="mr-2" width="30" src="{{ url('assets/img/deal/not-running-deal.svg') }}" alt="">
                                No upcoming flash deals within 2 days.
                            </strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{translate('All Flash Deals')}}</h1>
		</div>
        @can('add_flash_deal')
            <div class="col-md-6 text-md-right">
                <a href="{{ route('flash_deals.create') }}" class="btn btn-circle btn-info">
                    <span>{{translate('Create New Flash Deal')}}</span>
                </a>
            </div>
        @endcan
	</div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Flash Deals')}}</h5>
        <div class="pull-right clearfix">
            <form class="" id="sort_flash_deals" action="" method="GET">
                <div class="box-inline pad-rgt pull-left">
                    <div class="" style="min-width: 200px;">
                        <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name & Enter') }}">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <form method="GET" action="{{ route('flash_deals.index') }}" id="flashDealForm">
        <div class="card-header row gutters-5">
            <!-- Search by Title -->
            <div class="col-lg-3 col-md-6 mb-lg-0 mb-2">
                <label for="search">Search by Title</label>
                <input type="text" name="search" class="form-control" id="search" placeholder="Search by Title" value="{{ request('search') }}" onchange="submitForm()">
            </div>

            <!-- Filter by Start Date -->
            <div class="col-lg-3 col-md-6 mb-lg-0 mb-2">
                <label for="start_date">Start Date</label>
                <input type="date" name="start_date" class="form-control" id="start_date" value="{{ request('start_date') }}" onchange="submitForm()">
            </div>

            <!-- Filter by End Date -->
            <div class="col-lg-3 col-md-6 mb-lg-0 mb-2">
                <label for="end_date">End Date</label>
                <input type="date" name="end_date" class="form-control" id="end_date" value="{{ request('end_date') }}" onchange="submitForm()">
            </div>

            <!-- Filter by Status -->
            <div class="col-lg-3 col-md-6 mb-lg-0 mb-2">
                <label for="status">Status</label>
                <select name="status" class="form-control" id="status" onchange="submitForm()">
                    <option value="">Filter by Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- Reset Filters -->
            <div class="col-12  mt-2 ">
                <a href="{{ route('flash_deals.index') }}" class="btn btn-outline-primary">
                    <i class="fa fa-filter mr-1" aria-hidden="true"></i>Reset Filters
                </a>
            </div>
        </div>
    </form>
    <div class="card-body">
        <table class="table aiz-table mb-0" >
            <thead>
                <tr>
                    <th data-breakpoints="lg">#</th>
                    <th>{{translate('Title')}}</th>
                    <th data-breakpoints="lg">{{ translate('Banner') }}</th>
                    <th data-breakpoints="lg">{{ translate('Start Date') }}</th>
                    <th data-breakpoints="lg">{{ translate('End Date') }}</th>
                    <th data-breakpoints="lg">{{ translate('Duration') }}</th>

                    <th data-breakpoints="lg">{{ translate('Status') }}</th>
                    <th data-breakpoints="lg">{{ translate('Featured') }}</th>
                    <th data-breakpoints="lg">{{ translate('Page Link') }}</th>
                    <th class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($flash_deals as $key => $flash_deal)
                    <tr>
                        <td>{{ ($key+1) + ($flash_deals->currentPage() - 1)*$flash_deals->perPage() }}</td>
                        <td>{{ $flash_deal->getTranslation('title') }}</td>
                        <td><img src="{{ uploaded_asset($flash_deal->banner) }}" alt="banner" class="h-50px"></td>
                        <td>{{ date('d-m-Y H:i:s', $flash_deal->start_date) }}</td>
                        <td>{{ date('d-m-Y H:i:s', $flash_deal->end_date) }}</td>
                           <!-- Calculating Duration -->
                        <!-- Calculating Duration -->
                            <td>
                                @php
                                    $durationInSeconds = $flash_deal->end_date - $flash_deal->start_date;
                                    $durationInHours = $durationInSeconds / 3600; // Convert to hours
                                    $durationInDays = $durationInHours / 24; // Convert to days

                                    // Display in hours if less than 1 day
                                    if ($durationInDays < 1) {
                                        echo round($durationInHours) . ' hours';
                                    } else {
                                        echo round($durationInDays) . ' days';
                                    }
                                @endphp
                            </td>

                        <td>
							<label class="aiz-switch aiz-switch-success mb-0">
								<input onchange="update_flash_deal_status(this)" value="{{ $flash_deal->id }}" type="checkbox" <?php if($flash_deal->status == 1) echo "checked";?> >
								<span class="slider round"></span>
							</label>
						</td>
						<td>
							<label class="aiz-switch aiz-switch-success mb-0">
								<input
                                    @can('publish_flash_deal') onchange="update_flash_deal_feature(this)" @endcan
                                    value="{{ $flash_deal->id }}" type="checkbox"
                                    <?php if($flash_deal->featured == 1) echo "checked";?>
                                    @cannot('publish_flash_deal') disabled @endcan
                                >
								<span class="slider round"></span>
							</label>
						</td>
						<td>{{ url('flash-deal/'.$flash_deal->slug) }}</td>
						<td class="text-right">
                            @can('edit_flash_deal')
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('flash_deals.edit', ['id'=>$flash_deal->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                            @endcan
                            @can('delete_flash_deal')
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('flash_deals.destroy', $flash_deal->id)}}" title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            @endcan
                            {{--  @can('clone_flash_deal')
                            <a class="btn btn-soft-info btn-icon btn-circle btn-sm " href="{{ route('flash_deals.create', $flash_deal->id) }}" title="{{ translate('Clone') }}">
                                <i class="las la-copy"></i>
                            </a>  --}}
                        {{--  @endcan  --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="clearfix">
            <div class="pull-right">
                {{ $flash_deals->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')

    <script type="text/javascript">
        function update_flash_deal_status(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('flash_deals.update_status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    location.reload();
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
        function update_flash_deal_feature(el){
            if(el.checked){
                var featured = 1;
            }
            else{
                var featured = 0;
            }
            $.post('{{ route('flash_deals.update_featured') }}', {_token:'{{ csrf_token() }}', id:el.value, featured:featured}, function(data){
                if(data == 1){
                    location.reload();
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
    <script>
        function submitForm() {
            document.getElementById('flashDealForm').submit();
        }
    </script>
@endsection
