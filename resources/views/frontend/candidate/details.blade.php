@extends('frontend.layouts.app')



@section('meta')
<!-- Schema.org markup for Google+ -->


<!-- Twitter Card data -->
<meta name="twitter:card" content="website">
<meta name="twitter:site" content="@publisher_handle">

<meta property="og:site_name" content="{{ $candidate->name }}" />
@endsection

@section('content')

@php
$followed_sellers = [];
if (Auth::check()) {
$followed_sellers = \App\Models\FollowSeller::where('user_id', Auth::user()->id)->pluck('shop_id')->toArray();
}
@endphp

@if (!isset($type) || $type == 'top-selling' || $type == 'cupons')
@if ($candidate->top_banner)
<!-- Top Banner -->
<section class="h-160px h-md-200px h-lg-300px h-xl-100 w-100">
    <img class="d-block lazyload h-100 img-fit" 
    src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" 
    data-src="{{ uploaded_asset($candidate->top_banner) }}" alt="{{ env('APP_NAME') }} offer">
</section>
@endif
@endif

<section class="container @if (!isset($type) || $type == 'top-selling' || $type == 'cupons') mb-3 @endif border-top border mt-5" style="background: #fcfcfd;">
    <div class="container">
        <!-- Seller Info -->
        <div class="py-4">
            <div class="row align-items-center">
                <div class="col-lg-2 col-md-2">
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="d-flex align-items-center">
                        <!-- Shop Logo -->
                        <a href="#" class="overflow-hidden size-64px rounded-content" style="border: 1px solid #e5e5e5;
                        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.06);">
                        <img class="lazyload h-64px  mx-auto"
                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                        data-src="{{ uploaded_asset($candidate->avatar_original) }}"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    </a>
                    <div class="ml-3">
                        <!-- Shop Name & Verification Status -->
                        <a href="#"
                            class="text-dark d-block fs-16 fw-700">
                            {{ $candidate->name }}
                            @if (1 == 1)
                            <span class="ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17.5" height="17.5" viewBox="0 0 17.5 17.5">
                                    <g id="Group_25616" data-name="Group 25616" transform="translate(-537.249 -1042.75)">
                                        <path id="Union_5" data-name="Union 5" d="M0,8.75A8.75,8.75,0,1,1,8.75,17.5,8.75,8.75,0,0,1,0,8.75Zm.876,0A7.875,7.875,0,1,0,8.75.875,7.883,7.883,0,0,0,.876,8.75Zm.875,0a7,7,0,1,1,7,7A7.008,7.008,0,0,1,1.751,8.751Zm3.73-.907a.789.789,0,0,0,0,1.115l2.23,2.23a.788.788,0,0,0,1.115,0l3.717-3.717a.789.789,0,0,0,0-1.115.788.788,0,0,0-1.115,0l-3.16,3.16L6.6,7.844a.788.788,0,0,0-1.115,0Z" transform="translate(537.249 1042.75)" fill="#3490f3"/>
                                    </g>
                                </svg>
                            </span>
                            @else
                            <span class="ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17.5" height="17.5" viewBox="0 0 17.5 17.5">
                                    <g id="Group_25616" data-name="Group 25616" transform="translate(-537.249 -1042.75)">
                                        <path id="Union_5" data-name="Union 5" d="M0,8.75A8.75,8.75,0,1,1,8.75,17.5,8.75,8.75,0,0,1,0,8.75Zm.876,0A7.875,7.875,0,1,0,8.75.875,7.883,7.883,0,0,0,.876,8.75Zm.875,0a7,7,0,1,1,7,7A7.008,7.008,0,0,1,1.751,8.751Zm3.73-.907a.789.789,0,0,0,0,1.115l2.23,2.23a.788.788,0,0,0,1.115,0l3.717-3.717a.789.789,0,0,0,0-1.115.788.788,0,0,0-1.115,0l-3.16,3.16L6.6,7.844a.788.788,0,0,0-1.115,0Z" transform="translate(537.249 1042.75)" fill="red"/>
                                    </g>
                                </svg>
                            </span>
                            @endif
                        </a>
                        <!-- Ratting -->

                        <!-- Address -->
                        <div class="location fs-12 opacity-70 text-dark mt-1">{{ $candidate->address }}</div>
                    </div>
                </div>
            </div>
            <div class="col pl-5 pl-md-0 ml-5 ml-md-0">
                <div class="d-lg-flex align-items-center justify-content-lg-end">
                    <div class="d-md-flex justify-content-md-end align-items-md-baseline">
                        <!-- Member Since -->
                        <div class="pr-md-3 mt-2 mt-md-0 border-md-right">
                            <div class="fs-10 fw-400 text-secondary">{{ translate('Member Since') }}</div>
                            <div class="mt-1 fs-16 fw-700 text-secondary">{{ date('d M Y',strtotime($candidate->created_at)) }}</div>
                        </div>
                        <!-- Social Links -->
                        @if ($candidate->facebook || $candidate->instagram || $candidate->google || $candidate->twitter || $candidate->youtube)
                        <div class="pl-md-3 pr-lg-3 mt-2 mt-md-0 border-lg-right">
                            <span class="fs-10 fw-400 text-secondary">{{ translate('Social Media') }}</span><br>
                            <ul class="social-md colored-light list-inline mb-0 mt-1">
                                @if ($candidate->facebook)
                                <li class="list-inline-item mr-2">
                                    <a href="{{ $candidate->facebook }}" class="facebook"
                                        target="_blank">
                                        <i class="lab la-facebook-f"></i>
                                    </a>
                                </li>
                                @endif
                                @if ($candidate->instagram)
                                <li class="list-inline-item mr-2">
                                    <a href="{{ $candidate->instagram }}" class="instagram"
                                        target="_blank">
                                        <i class="lab la-instagram"></i>
                                    </a>
                                </li>
                                @endif
                                @if ($candidate->google)
                                <li class="list-inline-item mr-2">
                                    <a href="{{ $candidate->google }}" class="google"
                                        target="_blank">
                                        <i class="lab la-google"></i>
                                    </a>
                                </li>
                                @endif
                                @if ($candidate->twitter)
                                <li class="list-inline-item mr-2">
                                    <a href="{{ $candidate->twitter }}" class="twitter"
                                        target="_blank">
                                        <i class="lab la-twitter"></i>
                                    </a>
                                </li>
                                @endif
                                @if ($candidate->youtube)
                                <li class="list-inline-item">
                                    <a href="{{ $candidate->youtube }}" class="youtube"
                                        target="_blank">
                                        <i class="lab la-youtube"></i>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </div>
                        @endif
                    </div>
                    <!-- follow -->
                    <div class="d-flex justify-content-md-end pl-lg-3 pt-3 pt-lg-0">

                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2">
                </div>
        </div>
    </div>
</div>
</section>

<section class="py-4">
    <div class="container">
        <div class="row gutters-16 justify-content-center">

            <!-- Blog Details -->
            <div class="col-xxl-7 col-lg-8">
                <div class="mb-4">
                    <div class="p-3 border">
                        <h5>Candidate Information</h5>
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
                                    <h6> {{$jobCategory->getTranslation('name') }}</h6>
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
                                   {{$candidate->candidate_profile->level->getTranslation('name') }}
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
                         <h6> {{$jobskill->getTranslation('name') }}</h6>
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
                   {{$candidate->candidate_profile->experience->getTranslation('name') }}
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
             <h6> {{$jobType->getTranslation('name') }}</h6>
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
        {{$candidate->candidate_profile->country->name}}
    </h6>
</div>
</div>
</div>
</div>
<div class="row">
  <div class="col-lg-6">
    <div class="row">
        <div class="col-lg-6">
         <i class="fa-solid fa-person-half-dress" style="font-size: 20px;"></i>
         <span>{{ translate('Gender')}}</span> 
     </div>
     <div class="col-lg-6">
          <h6>
          {{$candidate->candidate_profile->gender}}
    </h6>
    </div>
</div>
</div>
  <div class="col-lg-6">
    <div class="row">
        <div class="col-lg-6">
         <i class="fa-solid fa-calendar-days" style="font-size: 20px;"></i>
         <span>{{ translate('Age')}}</span> 
     </div>
     <div class="col-lg-6">
          <h6>
        {{$candidate->candidate_profile->age}} {{ translate('Years')}}
    </h6>
    </div>
</div>
</div>

</div>

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

    function rangefilter(arg){
        $('input[name=min_price]').val(arg[0]);
        $('input[name=max_price]').val(arg[1]);
        filter();
    }
</script>
@endsection
