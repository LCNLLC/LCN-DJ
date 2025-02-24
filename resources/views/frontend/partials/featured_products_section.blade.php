{{--  @php
    $featured_products = Cache::remember('featured_products', 3600, function () {
      return filter_products(\App\Models\Product::where('published', 1)->where('featured', '1'))->latest()->limit(12)->get();
    });
    $featured_products = $featured_products->shuffle();
    $featured_products = App\Models\Product::where('published', 1)->where('featured', '1')->inRandomOrder()->limit(12)->get();
@endphp

@if (count($featured_products) > 0)
    <section class="mb-2 mb-md-3 mt-2 mt-md-3 ">
        <div class="container">
            <!-- Top Section -->
            <div class="d-flex mb-2 mb-md-3 align-items-baseline justify-content-between">
                <!-- Title -->
                <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                    <span class="">{{ translate('Featured Products') }}</span>
                </h3>
                <!-- Links -->
                <div class="d-flex">
                    <a type="button" class="arrow-prev slide-arrow link-disable text-secondary mr-2" onclick="clickToSlide('slick-prev','section_featured')"><i class="las la-angle-left fs-20 fw-600"></i></a>
                     <a class="text-blue fs-10 fs-md-12 fw-700 hov-text-primary animate-underline-primary" href="{{ route('search',['sort_by'=>'featured']) }}">{{ translate('View All') }}</a>
                    <a type="button" class="arrow-next slide-arrow text-secondary ml-2" onclick="clickToSlide('slick-next','section_featured')"><i class="las la-angle-right fs-20 fw-600"></i></a>
                </div>
            </div>
            <!-- Products Section -->
            <div class="px-sm-3">
                <div class="aiz-carousel sm-gutters-16 arrow-none " data-items="6" data-xl-items="5" data-lg-items="4"  data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true' data-infinite='false'>
                    @foreach ($featured_products as $key => $product)
                    <div class="carousel-box px-3 position-relative rounded-3 shadow has-transition border-right border-top border-bottom @if($key == 0) border-left @endif"
     style="padding: 0 1rem; position: relative; border-radius: 0.375rem; box-shadow: 0 1px 3px #06D6A0; transition: all 0.2s ease; border-right: 1px solid; border-top: 1px solid; border-bottom: 1px solid; outline: 1px solid transparent;"
     onmouseover="this.style.outline='1px solid #06D6A0'"
     onmouseout="this.style.outline='1px solid transparent'">

                        @include('frontend.partials.product_box_1',['product' => $product])
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif  --}}




@php
    $featured_products = Cache::remember('featured_products', 3600, function () {
        return filter_products(\App\Models\Product::where('published', 1)
            ->where('featured', '1'))
            ->latest()
            ->limit(12)
            ->get();
    });
    // Option to randomize; inRandomOrder() already introduces randomness
    $featured_products = App\Models\Product::where('published', 1)
        ->where('featured', '1')
        ->inRandomOrder()
        ->limit(12)
        ->get();
@endphp

<style>
    .japan-theme-featured-products {
    background-color: #f9f8f4; /* off-white/paper tone */
    font-family: 'Noto Sans JP', sans-serif;
}

.japan-theme-heading {
    color: #333;
    font-family: 'Noto Serif JP', serif;
    letter-spacing: 0.5px;
}

.japan-theme-arrow {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    transition: background-color 0.3s ease;
}

.japan-theme-arrow:hover {
    background-color: #e0e0e0;
}

.japan-theme-view-all {
    color: #b22222; /* a subtle accent color; you can adjust it */
    font-weight: bold;
    transition: color 0.3s ease;
}

.japan-theme-view-all:hover {
    color: #8b0000;
}

.japan-theme-product-box {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s, box-shadow 0.3s;
    box-shadow: 0 2px 4px rgba(0,0,0,0.8);
    border: 1px solid #eee;
}

.japan-theme-product-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

</style>
@if (count($featured_products) > 0)
    <section class="japan-theme-featured-products py-4">
        <div class="container">
            <!-- Top Section -->
            <div class="d-flex align-items-center justify-content-between mb-3">
                <!-- Title -->
                <h3 class="japan-theme-heading fs-20 fw-bold mb-0">
                    <span>{{ translate('Featured Products') }}</span>
                </h3>
                <!-- Links & Navigation -->
                <div class="d-flex align-items-center">
                    <a type="button" class="japan-theme-arrow arrow-prev" onclick="clickToSlide('slick-prev','section_featured')">
                        <i class="las la-angle-left fs-20"></i>
                    </a>
                    <a class="japan-theme-view-all mx-3 text-decoration-none" href="{{ route('search',['sort_by'=>'featured']) }}">
                        {{ translate('View All') }}
                    </a>
                    <a type="button" class="japan-theme-arrow arrow-next" onclick="clickToSlide('slick-next','section_featured')">
                        <i class="las la-angle-right fs-20"></i>
                    </a>
                </div>
            </div>
            <!-- Products Section -->
            <div class="japan-theme-carousel px-sm-3">
                <div class="aiz-carousel sm-gutters-16 arrow-none" data-items="6" data-xl-items="5" data-lg-items="4" data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows="true" data-infinite="false">
                    @foreach ($featured_products as $key => $product)
                        <div class="japan-theme-product-box carousel-box px-3 position-relative">
                            @include('frontend.partials.product_box_1',['product' => $product])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif
