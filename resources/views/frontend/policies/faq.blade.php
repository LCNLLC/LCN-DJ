@extends('frontend.layouts.app')

@section('meta_title'){{ $page->meta_title }}@stop

@section('meta_description'){{ $page->meta_description }}@stop

@section('meta_keywords'){{ $page->tags }}@stop

@section('meta')
<!-- Schema.org markup for Google+ -->
<meta itemprop="name" content="{{ $page->meta_title }}">
<meta itemprop="description" content="{{ $page->meta_description }}">
<meta itemprop="image" content="{{ uploaded_asset($page->meta_image) }}">

<!-- Twitter Card data -->
<meta name="twitter:card" content="website">
<meta name="twitter:site" content="@publisher_handle">
<meta name="twitter:title" content="{{ $page->meta_title }}">
<meta name="twitter:description" content="{{ $page->meta_description }}">
<meta name="twitter:creator" content="@author_handle">
<meta name="twitter:image" content="{{ uploaded_asset($page->meta_image) }}">

<!-- Open Graph data -->
<meta property="og:title" content="{{ $page->meta_title }}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="{{ URL($page->slug) }}" />
<meta property="og:image" content="{{ uploaded_asset($page->meta_image) }}" />
<meta property="og:description" content="{{ $page->meta_description }}" />
<meta property="og:site_name" content="{{ env('APP_NAME') }}" />
<style>
	.collapsible {
		/*background-color: #777;*/
		background-color: #e5ad06;
		color: white;
		cursor: pointer;
		padding: 10px;
		width: 100%;
		border: none;
		text-align: center;
		outline: none;
		font-size: 15px;
		margin-top: 21px;
		border-radius: 10px;
	}

	.active_collapsible, .collapsible:hover {
		/*background-color: #555;*/
		background-color: #e5ad06;
	}

	.collapsible:after {
		content: '\002B';
		color: white;
		font-weight: bold;
		float: right;
		margin-left: 5px;
	}

	.active_collapsible:after {
		content: "\2212";
	}

	.content {
		padding: 0 18px;
		max-height: 0;
		overflow: hidden;
		transition: max-height 0.2s ease-out;
		/*background-color: #f1f1f1;*/
		text-align: center;
		/*width: 70%;*/
	}
</style>
@endsection

@section('content')
<section class="pt-4 mb-4">
	<div class="container text-center">

		<div class="row">
			<div class="col-lg-6 text-center text-lg-left">
				<h1 class="fw-600 h4">{{ $page->getTranslation('title') }}</h1>
			</div>
			<div class="col-lg-6">
				<ul class="breadcrumb bg-transparent p-0 justify-content-center justify-content-lg-end">
					<li class="breadcrumb-item has-transition opacity-50 hov-opacity-100">
						<a class="text-reset" href="{{ route('home') }}">{{ translate('Home')}}</a>
					</li>
					<li class="text-dark fw-600 breadcrumb-item">
						"{{ translate('FAQ') }}"
					</li>
				</ul>
			</div>
		</div>
		<div class="row  text-center pt-4">
			<div class="col-lg-3">
			</div>
			<div class="col-lg-6">
				    <!-- Search field -->
                <div class="flex-grow-1 front-header-search d-flex align-items-center bg-white mx-xl-5">
                    <div class="position-relative flex-grow-1 px-3 px-lg-0">
                        <form action="{{ route('search') }}" method="GET" class="stop-propagation">
                            <div class="d-flex position-relative align-items-center">
                                <div class="d-lg-none" data-toggle="class-toggle" data-target=".front-header-search">
                                    <button class="btn px-2" type="button"><i class="la la-2x la-long-arrow-left"></i></button>
                                </div>
                                <div class="search-input-box">
                                    <input type="text" class="border border-soft-light form-control fs-14 hov-animate-outline" id="faq_search" name="keyword" @isset($query)
                                        value="{{ $query }}"
                                    @endisset placeholder="{{translate('I am searching for...')}}" autocomplete="off">

                                    <svg id="Group_723" data-name="Group 723" xmlns="http://www.w3.org/2000/svg" width="20.001" height="20" viewBox="0 0 20.001 20">
                                        <path id="Path_3090" data-name="Path 3090" d="M9.847,17.839a7.993,7.993,0,1,1,7.993-7.993A8,8,0,0,1,9.847,17.839Zm0-14.387a6.394,6.394,0,1,0,6.394,6.394A6.4,6.4,0,0,0,9.847,3.453Z" transform="translate(-1.854 -1.854)" fill="#b5b5bf"/>
                                        <path id="Path_3091" data-name="Path 3091" d="M24.4,25.2a.8.8,0,0,1-.565-.234l-6.15-6.15a.8.8,0,0,1,1.13-1.13l6.15,6.15A.8.8,0,0,1,24.4,25.2Z" transform="translate(-5.2 -5.2)" fill="#b5b5bf"/>
                                    </svg>
                                </div>
                            </div>
                        </form>
                        <div class="faq-typed-search-box stop-propagation document-click-d-none d-none bg-white rounded shadow-lg position-absolute left-0 top-100 w-100" style="min-height: 200px;position: absolute;z-index: 9999;">
                            <div class="search-preloader absolute-top-center">
                                <div class="dot-loader"><div></div><div></div><div></div></div>
                            </div>
                            <div class="search-nothing d-none p-3 text-center fs-16">

                            </div>
                            <div id="faq-search-content" class="text-left">

                            </div>
                        </div>
                    </div>
                </div>
                <!-- Search box -->
			</div>
		</div>
	</div>
</section>
<section class="mb-4">
	<div class="container">
		<div class="p-4 bg-white rounded shadow-sm overflow-hidden mw-100 text-left">
		   @php
                //echo $page->getTranslation('content');
            @endphp
            	@if(count($faqs)>0)

            	<div class="col-lg-12 col-md-12   text-center">

				<!-- <p>Shipping:</p> -->
				@foreach($faqs as $faq)
				<button class="collapsible">{{$faq->subject}}</button>

				<div class="content">
					<p>{!!$faq->description!!}</p>
				</div>
				@endforeach
			</div>
			<div class="aiz-pagination mt-4">
                 {{ $faqs->links() }}
            </div>
            @endif
		</div>
	</div>
</section>
<script>
	var coll = document.getElementsByClassName("collapsible");
	var i;

	for (i = 0; i < coll.length; i++) {
		coll[i].addEventListener("click", function() {
			this.classList.toggle("active_collapsible");
			var content = this.nextElementSibling;
			if (content.style.maxHeight){
				content.style.maxHeight = null;
			} else {
				content.style.maxHeight = content.scrollHeight + "px";
			}
		});
	}
</script>
@endsection
