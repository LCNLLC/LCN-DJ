@extends('backend.layouts.app')

@section('content')

    <div class="">
        <div class="row ">

            <div class="col-md-6">
                <div class="nav border-bottom aiz-nav-tabs">
                    <a class="p-3 fs-16 text-reset show active" data-toggle="tab" href="#installed">{{ translate('Installed Addon')}}</a>
                    <a class="p-3 fs-16 text-reset" data-toggle="tab" href="#available">{{ translate('Available Addon')}}</a>
                </div>
            </div>

            <div class="col-auto mt-3 mt-sm-0 text-center text-md-right">
                <a href="{{ route('addons.create')}}" class="btn btn-primary">{{ translate('Install/Update Addon')}}</a>
            </div>
        </div>
    </div>
    <br>
    <div class="tab-content">
        <div class="tab-pane fade in active show" id="installed">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <ul class="list-group">
                                @forelse($addons as $key => $addon)
                                    <li class="list-group-item">
                                        <div class="align-items-center d-flex flex-column flex-md-row">
                                            <img class="h-60px mb-3 mb-md-0" src="{{ static_asset($addon->image) }}" alt="Image">
                                            <div class="mr-md-3 ml-md-5">
                                                <h4 class="fs-16 fw-600">{{ ucfirst($addon->name) }}</h4>
                                            </div>
                                            <div class="mr-md-3 ml-0">
                                                <p><small>{{ translate('Version')}}: </small>{{ $addon->version }}</p>
                                            </div>
                                            @if (env('DEMO_MODE') != 'On')
                                                <div class="mr-md-3 ml-0">
                                                    <p><small>{{ translate('Purchase code')}}: </small>{{ $addon->purchase_code }}</p>
                                                </div>
                                            @endif
                                            <div class="ml-auto mr-0">
                                                <label class="aiz-switch mb-0">
                                                    <input type="checkbox" onchange="updateStatus(this, {{ $addon->id }})" <?php if($addon->activated) echo "checked";?>>
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <li class="list-group-item">
                                        <div class="text-center">
                                            <img class="mw-100 h-200px" src="{{ static_asset('assets/img/nothing.svg') }}" alt="Image">
                                            <h5 class="mb-0 h5 mt-3">{{ translate('No Addon Installed')}}</h5>
                                        </div>
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="available">
            <div class="row" id="available-addons-content">

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            @php
                 $todayDiscountTimes = get_today_discount_times();
            @endphp
            <div class="card">
                <div class="card-header d-flex align-items-center bg-info">

                        <div class="w-80">
                            <h5 class="font-weight:bolder" >Today's Discount Day Offer</h5>
                        </div>
                        <div class="w-20">
                            <img width="35" src="{{ url('assets/img/discount/today_discount.svg') }}" alt="">
                        </div>

                </div>
                <div class="card-body">
                    @if(!empty($todayDiscountTimes))
                    @php
                        $discountTime = $todayDiscountTimes[0]; // Access the first element
                    @endphp

                    <div class="mb-2">
                        <p style="font-size: 17px; min-height:30px; background-color: rgb(10, 187, 117); border-radius: 8px;" class="text-white px-2 mb-0">
                            {{ $discountTime['discount_day']->name }}
                        </p>
                        <br>
                        <strong>Start Time:</strong> {{ $discountTime['start_time'] }}<br>
                        <strong>End Time:</strong> {{ $discountTime['end_time'] }}
                    </div>
                @else
                    <strong> <img class="mr-2" width="30" src="{{ url('assets/img/deal/not-running-deal.svg') }}" alt="">No discount days for today.</strong>
                @endif

                </div>
            </div>
        </div>
        <div class="col-md-4">
            @php
                // Call the helper function directly in the Blade view
                $deals = get_running_and_upcoming_flash_deals();
            @endphp

            <div class="alert alert-primary" role="alert">
                <div class="d-flex">
                    <div class="w-80">
                        <h5 class="font-weight:bolder" >Currently Running Flash Deals </h5>
                    </div>
                    <div class="w-20">
                        <img width="35" src="{{ url('assets/img/deal/deal-svgrepo-com (1).svg') }}" alt="">
                    </div>
                </div>
                <div class="row">

                @if($deals['running']->isNotEmpty())
                @foreach($deals['running'] as $deal)
                    <div class="col-12 px-0">
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
        <div class="col-md-4">
            <div class="alert alert-info" role="alert">
                <div class="d-flex">
                    <div class="w-80">
                        <h5 class="font-weight:bolder">Upcoming Flash Deals (Within 2 Days)</h5>
                    </div>
                    <div class="w-20">
                        <img width="35" src="{{ url('assets/img/deal/deal-svgrepo-com (1).svg') }}" alt="">

                    </div>
                </div>

                <div class="row">
                    @if($deals['upcoming']->isNotEmpty())
                        @foreach($deals['upcoming'] as $deal)
                            <div class="col-12 px-0">
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
@endsection

@section('script')
    <script type="text/javascript">
        function updateStatus(el, id){
            if($(el).is(':checked')){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('addons.activation') }}', {_token:'{{ csrf_token() }}', id:id, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Status updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        $(document).ready(function(){
            $.post('https://activeitzone.com/addons/public/addons', {item: 'ecommerce'}, function(data){
                //console.log(data);
                html = '';
                data.forEach((item, i) => {
                    if(item.link != null){
                        html += `<div class="col-lg-4 col-md-6 ">
                                    <div class="card addon-card">
                                        <div class="card-body">
                                            <a href="${item.link}" target="_blank"><img class="img-fluid" src="${item.image}"></a>
                                            <div class="pt-4">
                                                <a class="fs-16 fw-600 text-reset" href="${item.link}" target="_blank">${item.name}</a>
                                                <div class="rating mb-2"><i class="la la-star active"></i><i class="la la-star active"></i><i class="la la-star active"></i><i class="la la-star active"></i><i class="la la-star active"></i></div>
                                                <p class="mar-no text-truncate-3">${item.short_description}</p>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="text-danger fs-22 fw-600">$${item.price}</div>
                                            <div class=""><a href="${item.link}" target="_blank" class="btn btn-sm btn-secondary">Preview</a> <a href="${item.purchase}" target="_blank" class="btn btn-sm btn-primary">Purchase</a></div>
                                        </div>
                                    </div>
                                </div>`;
                    }
                    else {
                        html += `<div class="col-lg-4 col-md-6 ">
                                    <div class="card addon-card">
                                        <div class="card-body">
                                            <a><img class="img-fluid" src="${item.image}"></a>
                                            <div class="pt-4">
                                                <a class="fs-16 fw-600 text-reset" >${item.name}</a>
                                                <div class="rating mb-2"><i class="la la-star active"></i><i class="la la-star active"></i><i class="la la-star active"></i><i class="la la-star active"></i><i class="la la-star active"></i></div>
                                                <p class="mar-no text-truncate-3">${item.short_description}</p>
                                            </div>
                                            <div class="card-footer">
                                                <div class="text-center"><div class="btn btn-outline btn-primary">Coming Soon</div></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                    }

                });
                $('#available-addons-content').html(html);
            });
        })
    </script>
@endsection
