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
                            <h1 class="fw-700 fs-20 fs-md-24 text-dark mb-0">{{ translate('Candidates')}}</h1>
                        </div>
                        <div class="col-5 col-xl-6">
                            <ul class="breadcrumb bg-transparent p-0 justify-content-end">
                                <li class="breadcrumb-item has-transition opacity-60 hov-opacity-100">
                                    <a class="text-reset" href="{{ route('home') }}">
                                        {{ translate('Home')}}
                                    </a>
                                </li>
                                <li class="text-dark fw-600 breadcrumb-item">
                                    "{{ translate('Candidates') }}"
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
                        @foreach($candidates as $candidate)
                            <div class="card mb-4 overflow-hidden shadow-none border rounded-0 hov-scale-img p-3">
                                <a href="{{ url("job").'/'. $candidate->name }}" class="text-reset d-block overflow-hidden h-180px">
                                    <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                        data-src="{{ uploaded_asset($candidate->avatar_original) }}"
                                        alt="{{ $candidate->title }}"
                                        class="img-fit lazyload h-100 has-transition">
                                </a>
                                <div class="py-3">
                                    <h2 class="fs-16 fw-700 mb-3 h-35px text-truncate-2">
                                        <a href="{{ url("job").'/'. $candidate->name }}" class="text-reset hov-text-primary" title="{{ $candidate->name }}">
                                            {{ $candidate->name }}
                                        </a>
                                    </h2>
                                 <!--    <p class="opacity-70 mb-3 h-60px text-truncate-3" title="{{ $candidate->description }}">
                                        {{ $candidate->description }}
                                    </p> -->
                                    <div>
                                        <small class="fs-12 fw-400 opacity-60">{{ date('M d, Y',strtotime($candidate->created_at)) }}</small>
                                    </div>
                                    @if($candidate->category != null)
                                        <div>
                                            <small class="fs-12 fw-400 text-blue">{{ $candidate->category->category_name }}</small>
                                        </div>
                                    @endif
                                    <div class="mt-3 text-primary">
                                        <a href="{{ route('candidate.details', $candidate->name) }}" class="fs-14 fw-700 text-primary has-transition d-flex align-items-center hov-column-gap-1">
                                            {{ translate('Visit Profile') }}
                                            <i class="las las-2x la-arrow-right fs-24 ml-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                    </div>
                    <!-- Pagination -->
                    <div class="aiz-pagination mt-4">
                        {{ $candidates->links() }}
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
                                    <div class="fs-16 fw-700 p-3">{{ translate('Skills')}}</div>
                                    <div class="p-3 aiz-checkbox-list">
                                        @foreach (\App\Models\JobSkill::where('status',1)->get() as $skill)
                                        <label class="aiz-checkbox mb-3">
                                            <input
                                                type="checkbox"
                                                name="selected_skills[]"
                                                value="{{ $skill->id }}" @if (in_array($skill->id, $selected_skills)) checked @endif
                                                onchange="filter()"
                                            >
                                            <span class="aiz-square-check"></span>
                                            <span class="fs-14 fw-400 text-dark has-transition hov-text-primary">
                                                {{ $skill->getTranslation('name') }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </form>

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
