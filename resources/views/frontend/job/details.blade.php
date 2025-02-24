@extends('frontend.layouts.app')

@section('meta_title'){{ $job->meta_title }}@stop

@section('meta_description'){{ $job->meta_description }}@stop

@section('meta_keywords'){{ $job->meta_keywords }}@stop

@section('meta')
<!-- Schema.org markup for Google+ -->
<meta itemprop="name" content="{{ $job->meta_title }}">
<meta itemprop="description" content="{{ $job->meta_description }}">
<meta itemprop="image" content="{{ uploaded_asset($job->meta_img) }}">

<!-- Twitter Card data -->
<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="@publisher_handle">
<meta name="twitter:title" content="{{ $job->meta_title }}">
<meta name="twitter:description" content="{{ $job->meta_description }}">
<meta name="twitter:creator" content="@author_handle">
<meta name="twitter:image" content="{{ uploaded_asset($job->meta_img) }}">

<!-- Open Graph data -->
<meta property="og:title" content="{{ $job->meta_title }}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="{{ route('blog.details', $job->name) }}" />
<meta property="og:image" content="{{ uploaded_asset($job->meta_img) }}" />
<meta property="og:description" content="{{ $job->meta_description }}" />
<meta property="og:site_name" content="{{ env('APP_NAME') }}" />

@endsection

@section('content')

<section class="py-4">
    <div class="container">
        <div class="row gutters-16 justify-content-center">

            <!-- Blog Details -->
            <div class="col-xxl-7 col-lg-8">
                <div class="mb-4">
                    <!-- Title -->
                    <h2 class="fs-20 fs-md-24 fw-700 mb-3">
                        <a class="text-reset hov-text-primary" title="{{ $job->name }}">
                            {{ $job->name }}
                        </a>
                    </h2>
                    <div class="row">
                        <div class="col-4">
                            <!-- Date -->
                            <div>
                                <small class="fs-12 fw-400 opacity-60">{{ date('M d, Y',strtotime($job->created_at)) }}</small>
                            </div>
                            <!-- Caregory -->
                            @if($job->category != null)
                            <div>
                                <small class="fs-12 fw-400 text-blue">{{ $job->category->category_name }}</small>
                            </div>
                            @endif
                        </div>
                        <!-- Share -->
                        <div class="col-8 text-right">
                            <div class="aiz-share"></div>
                        </div>
                    </div>
                    <!-- Image -->
                    <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                    data-src="{{ uploaded_asset($job->cover_image) }}"
                    alt="{{ $job->name }}"
                    class="img-fluid lazyload w-100 mt-3 mb-4">
                    <!-- Description -->
                    <div class="p-3 border">
                        <h3>Employment Information</h3>
                        <hr>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-lg-6">
                                     <i class="las la-industry aiz-side-nav-icon" style="font-size: 20px;"></i>
                                     <span>{{ translate('Industry')}}</span> 
                                 </div>
                                 <div class="col-lg-6">

                                    @foreach ($jobsCategories as $jobCategory) 
                                    <h6> {{$jobCategory->SelectedjobCategory->getTranslation('name') }}</h6>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                         <div class="row">
                            <div class="col-lg-6 " >
                                <i class="las la-graduation-cap aiz-side-nav-icon" style="font-size: 20px;"></i>
                                <span>{{ translate('Job level')}}</span>
                            </div>
                            <div class="col-lg-6">
                                <h6>
                                   {{$job->level->getTranslation('name') }}
                               </h6>
                           </div>
                       </div>
                   </div>
               </div>

               <div class="row">
                  <div class="col-lg-6">
                    <div class="row">
                        <div class="col-lg-6">
                         <i class="las la-briefcase aiz-side-nav-icon" style="font-size: 20px;"></i>
                         <span>Job Skills</span> 
                     </div>
                     <div class="col-lg-6">
                        <h6>
                         @foreach ($jobsSkills as $jobskill) 
                         <h6> {{$jobskill->SelectedjobSkill->getTranslation('name') }}</h6>
                         @endforeach
                     </h6>
                 </div>
             </div>
         </div>
         <div class="col-lg-6">
             <div class="row">
               <div class="col-lg-6">
                <i class="las la-award aiz-side-nav-icon" style="font-size: 20px;"></i>
                <span>{{ translate('Experience')}}</span>
            </div>
            <div class="col-lg-6">
                <h6>
                   {{$job->experience->getTranslation('name') }}
               </h6>
           </div>
       </div>
   </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="row">
            <div class="col-lg-6">
             <i class="las la-briefcase aiz-side-nav-icon" style="font-size: 20px;"></i>
             <span>{{ translate('Job types')}}</span> 
         </div>
         <div class="col-lg-6">
            <h6>
             @foreach ($jobsTypes as $jobType) 
             <h6> {{$jobType->SelectedjobType->getTranslation('name') }}</h6>
             @endforeach
         </h6>
     </div>
 </div>
</div>
<div class="col-lg-6">
 <div class="row">
   <div class="col-lg-6">
    <i class="las la-map-marker-alt aiz-side-nav-icon" style="font-size: 20px;"></i>
    <span>{{ translate('Location')}}</span>
</div>
<div class="col-lg-6">
    <h6>
       {{$job->city->name}} ,  {{$job->state->name}} ,  {{$job->country->name}}
   </h6>
</div>
</div>
</div>
</div>
<div class="row">

  <div class="col-lg-6">
    <div class="row">
        <div class="col-lg-6">
         <i class="las la-hand-holding-usd aiz-side-nav-icon" style="font-size: 20px;"></i>
         <span>{{ translate('Salary')}}</span> 
     </div>
     <div class="col-lg-6">
        <h6 class="text-warning">
         {{ isset($job->currency) ? $job->currency->symbol : '' }}{{$job->salary_from}} -  {{ isset($job->currency) ? $job->currency->symbol : '' }}{{$job->salary_to}} /  {{$job->salary_range}}
     </h6>
 </div>
</div>
</div>

</div>

</div>

<div class="mb-4 mt-4 overflow-hidden">
    {!! $job->description !!}
</div>
<div class="mb-4 overflow-hidden">
    {!! $job->content !!}
</div>
<div class="mb-4 overflow-hidden">
    @if($applied)
    <a class="btn btn-primary buy-now fw-600 add-to-cart min-w-150px rounded-0"> {{ translate('Applied')}}</a>
    @elseif (Auth::check())
        <a href="{{route('apply',$job->id)}}" class="btn btn-primary buy-now fw-600 add-to-cart min-w-150px rounded-0"> {{ translate('Apply Now')}}</a>
         @php
         $userApplied = \App\Models\JobApplication::where('user_id', Auth::id())
                        ->exists();
        @endphp

        @if ($userApplied)
            <a href="{{ route('quick.apply', $job->id) }}" class="btn btn-primary buy-now fw-600 add-to-cart min-w-150px rounded-0">{{ translate('Quick Apply') }}</a>
        @endif
    @else
        <button type="button" class="btn btn-primary buy-now fw-600 add-to-cart min-w-150px rounded-0" onclick="showLoginModal()">
            <i class="las la-check-circle"></i> {{ translate('Apply Now')}}
            </button>
    @endif
  
</div>
</div>
</div>


<!-- recent posts -->
<div class="col-xxl-3 col-lg-4">
    <div class="p-3 border">
        <h3 class="fs-16 fw-700 text-dark mb-3">{{ translate('Recent Jobs') }}</h3>
        <div class="row">
            @foreach($recent_jobs as $recent_job)
            <div class="col-lg-12 col-sm-6 mb-4 hov-scale-img">
                <div class="d-flex">
                    <div class="">
                        <a href="{{ url("job").'/'. $recent_job->name }}" class="text-reset d-block overflow-hidden size-80px size-xl-90px mr-2">
                            <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                            data-src="{{ uploaded_asset($recent_job->cover_image) }}"
                            alt="{{ $recent_job->name }}"
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
@include('frontend.partials.jop_application_modal')

@endsection


@section('script')
@if (get_setting('facebook_comment') == 1)
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v9.0&appId={{ env('FACEBOOK_APP_ID') }}&autoLogAppEvents=1" nonce="ji6tXwgZ"></script>
@endif
@endsection
