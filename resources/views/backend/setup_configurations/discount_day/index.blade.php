@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
    	<div class="row align-items-center">

            <div class="col-12">
                @php
                 $todayDiscountTimes = get_today_discount_times();
            @endphp
               <div class="alert alert-primary" role="alert">
                <h5>Today's Discount Day Offer
                    <img width="35" src="{{ url('assets/img/discount/today_discount.svg') }}" alt="">
                </h5>

                @if(!empty($todayDiscountTimes))
                    @php
                        $discountTime = $todayDiscountTimes[0];
                    @endphp

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-success">
                                    <h5 class="text-white">
                                        {{ $discountTime['discount_day']->name }}
                                    </h5>
                                    <img width="35" src="{{ url('assets/img/discount/today_discount.svg') }}" alt="">
                                </div>
                                <div class="card-body">
                                    <strong>Start Time:</strong> {{ $discountTime['start_time'] }}<br>
                                    <strong>End Time:</strong> {{ $discountTime['end_time'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <strong class="ml-4">
                        <img class="mr-2" width="30" src="{{ url('assets/img/deal/not-running-deal.svg') }}" alt="">
                        No discount days for today.
                    </strong>
                @endif
            </div>

            </div>
            <div class="col-12">
    			<h1 class="h3">{{translate('Days & Discount')}}</h1>

    		</div>
    	</div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                    <div class="card-header row gutters-5">
                        <div class="col text-center text-md-left">
                            <h5 class="mb-md-0 h6">{{ translate('Days & Discount') }}</h5>
                        </div>


                    </div>
                    <form method="GET" action="{{ route('discount_day.index') }}" id="daysDiscontForm">
                        <div class="card-header row gutters-5">
                            <div class="col-lg-4 col-md-6 mb-lg-0 mb-2">
                                <input type="date" name="date" class="form-control" placeholder="Filter by Date" value="{{ request('date') }}" onchange="submitForm()">
                            </div>
                            <div class="col-lg-4 col-md-6 mb-lg-0 mb-2">
                                <input type="number" name="discount" class="form-control" placeholder="{{ translate('Filter by Discount %') }}" value="{{ request('discount') }}" onchange="submitForm()">
                            </div>
                            <div class="col-lg-4 col-12">
                                <a href="{{ route('discount_day.index') }}" class="btn btn-outline-primary"> <i class="fa fa-filter mr-1" aria-hidden="true"></i>{{ translate('All Resuts') }}</a>
                            </div>
                        </div>
                    </form>

                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table aiz-table mb-0">
                            <thead>
                                <tr>
                                    <th data-breakpoints="lg">#</th>
                                    <th>{{ translate('Name') }}</th>
                                    <th>{{ translate('Date') }}</th>
                                    <th>{{ translate('Discount') }}</th>
                                    <th>{{ translate('Start Time') }}</th>
                                    <th>{{ translate('End Time') }}</th>
                                    <th>{{ translate('hide/show') }}</th>
                                    <th data-breakpoints="lg" class="text-right">{{ translate('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($discount_days as $key => $discount_day)
                                    <tr>
                                        <td>{{ translate(($key + 1) + ($discount_days->currentPage() - 1) * $discount_days->perPage()) }}</td>
                                        <td>{{ translate($discount_day->name) }}</td>
                                        <td>{{ translate(\Carbon\Carbon::parse($discount_day->discount_date)->format('F j, Y')) }}</td>
                                        <td>{{ translate($discount_day->discount . '%') }}</td>
                                        <td>{{ translate(\Carbon\Carbon::parse($discount_day->start_time)->format('g:i A')) }}</td>
                                        <td>{{ translate(\Carbon\Carbon::parse($discount_day->end_time)->format('g:i A')) }}</td>
                                        <td>
                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                <input onchange="update_status(this)" value="{{ $discount_day->id }}" type="checkbox" @checked($discount_day->status == 1)>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        <td class="text-right">
                                            <a href="{{ route('discount_day.edit', ['id' => $discount_day->id, 'lang' => env('DEFAULT_LANGUAGE')]) }}"
                                               class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                               title="{{ translate('Edit') }}">
                                                <i class="las la-edit"></i>
                                            </a>
                                            <a href="#"
                                               class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                               data-href="{{ route('discount_day.destroy', $discount_day->id) }}"
                                               title="{{ translate('Delete') }}">
                                                <i class="las la-trash"></i>
                                            </a>
                                            {{--  <a href="{{ route('discount_day.create', $discount_day->id) }}"
                                               class="btn btn-soft-success btn-icon btn-circle btn-sm"
                                               title="{{ translate('Clone') }}">
                                                <i class="las la-copy"></i>
                                            </a>  --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>


                    <div class="aiz-pagination">
                        {{ $discount_days->appends(request()->input())->links() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
    		<div class="card">
    			<div class="card-header">
    				<h5 class="mb-0 h6">{{ translate('Add Discount Day') }}</h5>
    			</div>

                <div class="card-body">
    				<form action="{{ route('discount_day.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name">{{ translate('Name') }}</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="{{ translate('Name') }}" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="discount_date">{{ translate('Date') }}</label>
                            <input type="date" name="discount_date" class="form-control @error('discount_date') is-invalid @enderror" value="{{ old('discount_date', date('Y-m-d')) }}" required>
                            @error('discount_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="discount">{{ translate('Discount') }}</label>
                            <div class="input-group">
                                <input type="number" name="discount" min="1" step="1" class="form-control @error('discount') is-invalid @enderror" placeholder="{{ translate('Discount') }}" value="{{ old('discount') }}" required>
                                <div class="input-group-prepend">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            @error('discount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="start_time">{{ translate('Start Time') }}</label>
                            <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}" required>
                            @error('start_time')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="end_time">{{ translate('End Time') }}</label>
                            <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}" required>
                            @error('end_time')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3 text-right">
                            <button onclick="this.form.submit(); this.disabled=true; this.innerHTML='<span class=\'spinner-border spinner-border-sm\' role=\'status\' aria-hidden=\'true\'></span> {{ translate('Please wait...') }}';" type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </form>


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
        function sort_cities(el){
            $('#sort_cities').submit();
        }

        function update_status(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('discount_day.status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Status updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

    </script>

    <script>
         function submitForm() {
        document.getElementById('daysDiscontForm').submit();
    }
    </script>

@endsection
