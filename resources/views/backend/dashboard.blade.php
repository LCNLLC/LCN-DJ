@extends('backend.layouts.app')

@section('content')
<div class="container-fluid">
    {{-- SMTP Configuration Alert --}}
    @if(auth()->user()->can('smtp_settings') && env('MAIL_USERNAME') == null && env('MAIL_PASSWORD') == null)
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ translate('Please Configure SMTP Setting to work all email sending functionality') }},
        <a class="alert-link" href="{{ route('smtp_settings.index') }}">{{ translate('Configure Now') }}</a>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @can('admin_dashboard')
    <div class="row">
        <!-- Dashboard Statistics -->
        <div class="col-12 mb-4">
            <div class="row">
                <!-- Total Customers -->
                <div class="col-md-4 mb-3">
                    <div class="card text-black shadow-sm bg-gradient-primary">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="card-title mb-1">{{ translate('Total Customer') }}</h6>
                                <h3 class="mb-0">{{ \App\Models\User::where('user_type', 'customer')->whereNotNull('email_verified_at')->count() }}</h3>
                            </div>
                            <div>
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Total Orders -->
                <div class="col-md-4 mb-3">
                    <div class="card text-black shadow-sm bg-gradient-success">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="card-title mb-1">{{ translate('Total Order') }}</h6>
                                <h3 class="mb-0">{{ \App\Models\Order::count() }}</h3>
                            </div>
                            <div>
                                <i class="fas fa-shopping-cart fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Product Categories -->
                <div class="col-md-4 mb-3">
                    <div class="card text-black shadow-sm bg-gradient-warning">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="card-title mb-1">{{ translate('Product Category') }}</h6>
                                <h3 class="mb-0">{{ \App\Models\Category::count() }}</h3>
                            </div>
                            <div>
                                <i class="fas fa-th-list fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Product Brands -->
                <div class="col-md-4 mb-3">
                    <div class="card text-black shadow-sm bg-gradient-info">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="card-title mb-1">{{ translate('Product Brand') }}</h6>
                                <h3 class="mb-0">{{ \App\Models\Brand::count() }}</h3>
                            </div>
                            <div>
                                <i class="fas fa-tags fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Used Products -->
                <div class="col-md-4 mb-3">
                    <div class="card text-black shadow-sm bg-gradient-danger">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="card-title mb-1">{{ translate('Used Products') }}</h6>
                                <h3 class="mb-0">{{ \App\Models\Product::where('product_type', '3')->count() }}</h3>
                            </div>
                            <div>
                                <i class="fas fa-box-open fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Donation & Aid -->
                <div class="col-md-4 mb-3">
                    <div class="card text-black shadow-sm bg-gradient-secondary">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="card-title mb-1">{{ translate('Donation & Aid') }}</h6>
                                <h3 class="mb-0">{{ \App\Models\Product::where('product_type', '2')->count() }}</h3>
                            </div>
                            <div>
                                <i class="fas fa-hand-holding-heart fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Total Products -->
                <div class="col-md-4 mb-3">
                    <div class="card text-black shadow-sm bg-gradient-dark">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="card-title mb-1">{{ translate('Products') }}</h6>
                                <h3 class="mb-0">{{ \App\Models\Product::count() }}</h3>
                            </div>
                            <div>
                                <i class="fas fa-boxes fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- In House Products -->
                <div class="col-md-4 mb-3">
                    <div class="card text-black shadow-sm bg-gradient-purple">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="card-title mb-1">{{ translate('In House Products') }}</h6>
                                <h3 class="mb-0">{{ \App\Models\Product::where('added_by','admin')->count() }}</h3>
                            </div>
                            <div>
                                <i class="fas fa-store fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Seller Products -->
                <div class="col-md-4 mb-3">
                    <div class="card text-black shadow-sm bg-gradient-teal">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="card-title mb-1">{{ translate('Seller Products') }}</h6>
                                <h3 class="mb-0">{{ \App\Models\Product::where('added_by','seller')->count() }}</h3>
                            </div>
                            <div>
                                <i class="fas fa-store-alt fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="col-12 mb-4">
            <div class="row">
                <!-- Products Doughnut Chart -->
                <div class="col-md-6 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h6 class="mb-0 fs-14">{{ translate('Products') }}</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="pie-1" class="w-100" height="305"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Sellers Doughnut Chart -->
                <div class="col-md-6 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h6 class="mb-0 fs-14">{{ translate('Sellers') }}</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="pie-2" class="w-100" height="305"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bar Graphs for Category-wise Sales and Stock -->
        <div class="col-12 mb-4">
            <div class="row">
                <!-- Category Wise Sales -->
                <div class="col-md-6 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h6 class="mb-0 fs-14">{{ translate('Category wise product sale') }}</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="graph-1" class="w-100" height="500"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Category Wise Stock -->
                <div class="col-md-6 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h6 class="mb-0 fs-14">{{ translate('Category wise product stock') }}</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="graph-2" class="w-100" height="500"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top 12 Products Carousel -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">{{ translate('Top 12 Products') }}</h6>
                </div>
                <div class="card-body">
                    <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="6" data-xl-items="5" data-lg-items="4" data-md-items="3" data-sm-items="2" data-arrows="true">
                        @foreach (filter_products(\App\Models\Product::where('published', 1)->orderBy('num_of_sale', 'desc'))->limit(12)->get() as $product)
                        <div class="carousel-box">
                            <div class="aiz-card-box border rounded shadow-sm hov-shadow-md transition bg-white">
                                <div class="position-relative">
                                    <a href="{{ route('product', $product->slug) }}" class="d-block">
                                        <img class="img-fit lazyload mx-auto" style="height:210px;" src="{{ static_asset('assets/img/placeholder.jpg') }}" data-src="{{ uploaded_asset($product->thumbnail_img) }}" alt="{{ $product->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                    </a>
                                </div>
                                <div class="p-3 text-left">
                                    <div class="fs-15">
                                        @if(home_base_price($product) != home_discounted_base_price($product))
                                        <del class="fw-600 opacity-50 mr-1">{{ home_base_price($product) }}</del>
                                        @endif
                                        <span class="fw-700 text-primary">{{ home_discounted_base_price($product) }}</span>
                                    </div>
                                    <div class="rating rating-sm mt-1">
                                        {{ renderStarRating($product->rating) }}
                                    </div>
                                    <h3 class="fw-600 fs-13 text-truncate-2 lh-1-4 mb-0">
                                        <a href="{{ route('product', $product->slug) }}" class="text-reset">{{ $product->getTranslation('name') }}</a>
                                    </h3>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>


    </div>
    @endcan
</div>
@endsection

@section('script')
<script type="text/javascript">
    AIZ.plugins.chart('#pie-1', {
        type: 'doughnut',
        data: {
            labels: [
                '{{ translate('Total published products') }}',
                '{{ translate('Total sellers products') }}',
                '{{ translate('Total admin products') }}'
            ],
            datasets: [{
                data: [
                    {{ \App\Models\Product::where('published', 1)->count() }},
                    {{ \App\Models\Product::where('published', 1)->where('added_by', 'seller')->count() }},
                    {{ \App\Models\Product::where('published', 1)->where('added_by', 'admin')->count() }}
                ],
                backgroundColor: [
                    "#fd3995",
                    "#34bfa3",
                    "#5d78ff"
                ]
            }]
        },
        options: {
            cutoutPercentage: 70,
            legend: {
                labels: {
                    fontFamily: 'Poppins',
                    boxWidth: 10,
                    usePointStyle: true
                },
                onClick: function () { return ''; },
                position: 'bottom'
            }
        }
    });

    AIZ.plugins.chart('#pie-2', {
        type: 'doughnut',
        data: {
            labels: [
                '{{ translate('Total sellers') }}',
                '{{ translate('Total approved sellers') }}',
                '{{ translate('Total pending sellers') }}'
            ],
            datasets: [{
                data: [
                    {{ \App\Models\Shop::count() }},
                    {{ \App\Models\Shop::where('verification_status', 1)->count() }},
                    {{ \App\Models\Shop::where('verification_status', 0)->count() }}
                ],
                backgroundColor: [
                    "#fd3995",
                    "#34bfa3",
                    "#5d78ff"
                ]
            }]
        },
        options: {
            cutoutPercentage: 70,
            legend: {
                labels: {
                    fontFamily: 'Montserrat',
                    boxWidth: 10,
                    usePointStyle: true
                },
                onClick: function () { return ''; },
                position: 'bottom'
            }
        }
    });

    AIZ.plugins.chart('#graph-1', {
        type: 'bar',
        data: {
            labels: [
                @foreach ($root_categories as $category)
                '{{ $category->getTranslation('name') }}',
                @endforeach
            ],
            datasets: [{
                label: '{{ translate('Number of sale') }}',
                data: [
                    {{ $cached_graph_data['num_of_sale_data'] }}
                ],
                backgroundColor: [
                    @foreach ($root_categories as $category)
                    'rgba(55, 125, 255, 0.4)',
                    @endforeach
                ],
                borderColor: [
                    @foreach ($root_categories as $category)
                    'rgba(55, 125, 255, 1)',
                    @endforeach
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    gridLines: { color: '#f2f3f8', zeroLineColor: '#f2f3f8' },
                    ticks: { fontColor: "#8b8b8b", fontFamily: 'Poppins', fontSize: 10, beginAtZero: true }
                }],
                xAxes: [{
                    gridLines: { color: '#f2f3f8' },
                    ticks: { fontColor: "#8b8b8b", fontFamily: 'Poppins', fontSize: 10 }
                }]
            },
            legend: {
                labels: { fontFamily: 'Poppins', boxWidth: 10, usePointStyle: true },
                onClick: function () { return ''; }
            }
        }
    });

    AIZ.plugins.chart('#graph-2', {
        type: 'bar',
        data: {
            labels: [
                @foreach ($root_categories as $category)
                '{{ $category->getTranslation('name') }}',
                @endforeach
            ],
            datasets: [{
                label: '{{ translate('Number of Stock') }}',
                data: [
                    {{ $cached_graph_data['qty_data'] }}
                ],
                backgroundColor: [
                    @foreach ($root_categories as $category)
                    'rgba(253, 57, 149, 0.4)',
                    @endforeach
                ],
                borderColor: [
                    @foreach ($root_categories as $category)
                    'rgba(253, 57, 149, 1)',
                    @endforeach
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    gridLines: { color: '#f2f3f8', zeroLineColor: '#f2f3f8' },
                    ticks: { fontColor: "#8b8b8b", fontFamily: 'Poppins', fontSize: 10, beginAtZero: true }
                }],
                xAxes: [{
                    gridLines: { color: '#f2f3f8' },
                    ticks: { fontColor: "#8b8b8b", fontFamily: 'Poppins', fontSize: 10 }
                }]
            },
            legend: {
                labels: { fontFamily: 'Poppins', boxWidth: 10, usePointStyle: true },
                onClick: function () { return ''; }
            }
        }
    });
</script>
@endsection
