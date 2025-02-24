@extends('frontend.layouts.app')

@section('content')
    <section class="pb-4 pt-5">
        <div class="container">
            <div class="row gutters-16">
                <!-- Contents -->
                <div class="col-xl-9 order-xl-1">
                    <!-- Breadcrumb -->
                    <div class="row gutters-16 mb-4">
                        <div class="col-5 col-xl-6">
                            <h1 class="fw-700 fs-20 fs-md-24 text-dark mb-0">{{ translate('Jobs')}}</h1>
                        </div>
                        <div class="col-5 col-xl-6">
                            <ul class="breadcrumb bg-transparent p-0 justify-content-end">
                                <li class="breadcrumb-item has-transition opacity-60 hov-opacity-100">
                                    <a class="text-reset" href="{{ route('home') }}">
                                        {{ translate('Home')}}
                                    </a>
                                </li>
                                <li class="text-dark fw-600 breadcrumb-item">
                                    "{{ translate('Job') }}"
                                </li>
                            </ul>
                        </div>
                        <div class="col d-xl-none mb-lg-3 text-right">
                            <button type="button" class="btn btn-icon p-0 active" data-toggle="class-toggle" data-target=".aiz-filter-sidebar">
                                <i class="la la-filter la-2x"></i>
                            </button>
                        </div>
                    </div>
                    <!-- jobs -->
                    <div class="job card-columns">
                        @foreach($jobs as $job)
                            <div class="card mb-4 overflow-hidden shadow-none border rounded-0 hov-scale-img p-3">
                                <a href="{{ url("job").'/'. $job->name }}" class="text-reset d-block overflow-hidden h-180px">
                                    <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                        data-src="{{ uploaded_asset($job->cover_image) }}"
                                        alt="{{ $job->title }}"
                                        class="img-fit lazyload h-100 has-transition">
                                </a>
                                <div class="py-3">
                                    <h2 class="fs-16 fw-700 mb-3 h-35px text-truncate-2">
                                        <a href="{{ url("job").'/'. $job->name }}" class="text-reset hov-text-primary" title="{{ $job->name }}">
                                            {{ $job->name }}
                                        </a>
                                    </h2>
                                    <p class="opacity-70 mb-3 h-60px text-truncate-3" title="{{ $job->description }}">
                                        {{ $job->description }}
                                    </p>
                                    <div>
                                        <small class="fs-12 fw-400 opacity-60">{{ date('M d, Y',strtotime($job->created_at)) }}</small>
                                    </div>
                                    @if($job->category != null)
                                        <div>
                                            <small class="fs-12 fw-400 text-blue">{{ $job->category->category_name }}</small>
                                        </div>
                                    @endif
                                    <div class="mt-3 text-primary">
                                        <a href="{{ url("job").'/'. $job->name }}" class="fs-14 fw-700 text-primary has-transition d-flex align-items-center hov-column-gap-1">
                                            {{ translate('Read Full Job') }}
                                            <i class="las las-2x la-arrow-right fs-24 ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                    </div>
                    <!-- Pagination -->
                    <div class="aiz-pagination mt-4">
                        {{ $jobs->links() }}
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-xl-3">
                    <!-- Filters -->
                    <form class="mb-4" id="search-form" action="" method="GET">
                        <div class="aiz-filter-sidebar collapse-sidebar-wrap sidebar-xl sidebar-right z-1035">
                            <div class="overlay overlay-fixed dark c-pointer" data-toggle="class-toggle" data-target=".aiz-filter-sidebar" data-same=".filter-sidebar-thumb"></div>
                            <div class="collapse-sidebar c-scrollbar-light text-left" style="overflow-y: auto;">
                                <div class="d-flex d-xl-none justify-content-between align-items-center pl-3 border-bottom">
                                    <h3 class="h6 mb-0 fw-600">{{ translate('Filters') }}</h3>
                                    <button type="button" class="btn btn-sm p-2 filter-sidebar-thumb" data-toggle="class-toggle" data-target=".aiz-filter-sidebar" >
                                        <i class="las la-times la-2x"></i>
                                    </button>
                                </div>
                                <!-- Search -->
                                <div class="mb-4 mt-3 px-3 mt-xl-0 px-xl-0">
                                    <div class="input-group w-100">
                                        <input type="text" class="border border-right-0 rounded-0 fs-14 flex-grow-1" name="search" value="{{ $search }}" placeholder="{{translate('Search...')}}" autocomplete="off" style="padding: 10px;">
                                        <div class="input-group-append">
                                            <button class="btn bg-transparent hov-bg-light rounded-0 border border-left-0" type="submit" style="">
                                                <i class="la la-search la-flip-horizontal fs-18 text-gray"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Categories -->
                                <div class="bg-white border mb-3 mx-3 mx-xl-0">
                                    <div class="fs-16 fw-700 p-3">{{ translate('Job Types')}}</div>
                                    <div class="p-3 aiz-checkbox-list">
                                        @foreach (\App\Models\JobType::where('status',1)->get() as $type)
                                        <label class="aiz-checkbox mb-3">
                                            <input
                                                type="checkbox"
                                                name="selected_types[]"
                                                value="{{ $type->name }}" @if (in_array($type->name, $selected_types)) checked @endif
                                                onchange="filter()"
                                            >
                                            <span class="aiz-square-check"></span>
                                            <span class="fs-14 fw-400 text-dark has-transition hov-text-primary">
                                                {{ $type->getTranslation('name') }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </form>

                    <!-- recent posts -->
                    <div class="p-3 border">
                        <h3 class="fs-16 fw-700 text-dark mb-3">{{ translate('Recent Jobs') }}</h3>
                        <div class="row">
                            @foreach($recent_jobs as $recent_job)
                            <div class="col-xl-12 col-lg-4 col-sm-6 mb-4 hov-scale-img">
                                <div class="d-flex">
                                    <div class="">
                                        <a href="{{ url("job").'/'. $recent_job->name }}" class="text-reset d-block overflow-hidden size-80px size-xl-90px mr-2">
                                            <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                                data-src="{{ uploaded_asset($recent_job->cover_image) }}"
                                                alt="{{ $recent_job->title }}"
                                                class="img-fit lazyload h-100 has-transition">
                                        </a>
                                    </div>
                                    <div class="">
                                        <h2 class="fs-14 fw-700 mb-2 mb-xl-3 h-35px text-truncate-2">
                                            <a href="{{ url("job").'/'. $recent_job->name }}" class="text-reset hov-text-primary" title="{{ $recent_job->name }}">
                                                {{ $recent_job->name }}
                                            </a>
                                        </h2>
                                        <div>
                                            <small class="fs-12 fw-400 opacity-60">{{ date('M d, Y',strtotime($recent_job->created_at)) }}</small>
                                        </div>
                                        @if($recent_job->category != null)
                                            <div>
                                                <small class="fs-12 fw-400 text-blue">{{ $recent_job->category->category_name }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>
@endsection

@section('script')
    <script type="text/javascript">
        function filter(){
            $('#search-form').submit();
        }
        
    </script>
@endsection
