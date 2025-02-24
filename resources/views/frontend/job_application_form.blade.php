@extends('frontend.layouts.app')

@section('content')
<section class="pt-4 mb-4">
    <div class="container text-center">
        <div class="row">
            <div class="col-lg-6 text-center text-lg-left">
                {{-- <h1 class="fw-700 fs-24 text-dark">{{ translate('Register your shop')}}</h1> --}}
            </div>
            <div class="col-lg-6">
                <ul class="breadcrumb bg-transparent p-0 justify-content-center justify-content-lg-end">
                    <li class="breadcrumb-item has-transition opacity-50 hov-opacity-100">
                        <a class="text-reset" href="{{ route('home') }}">{{ translate('Home')}}</a>
                    </li>
                    <li class="text-dark fw-600 breadcrumb-item">
                        "{{ translate('Job Application')}}"
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<section class="pt-4 mb-4">
    <div class="container">
        <div class="row">
            <div class="col-xxl-5 col-xl-6 col-md-8 mx-auto">
                <div class="bg-primary fw-600 rounded-2">
                <p class="fs-5 fs-md-18 text-dark text-center mb-1 pt-1" style="color:#004e9c !important">{{ translate('Job Application')}}</p>
                <h1 class="fw-700 fs-20 fs-md-24 text-white text-center mb-3 pb-3">{{ translate('Start Your Career Today')}}</h1>
                </div>
                 <h2 class="fw-700 fs-20 fs-md-24 text-dark text-center mb-3" style="color:#004e9c !important">{{$job->name}}</h2>
                <form id="shop" class="" action="{{ route('apply.job') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                        <input type="hidden" name="job_id" value="{{$job_id}}">
                        <div class="bg-white border mb-4">
                            <div class="fs-15 fw-600 p-3">
                                {{ translate('Personal Info')}}
                            </div>
                            <div class="fs-12 fw-600 p-3">
                                {{ translate('Please fill in your information and send it to the employer.')}}
                            </div>
                            <div class="p-3">
                                <div class="form-group">
                                    <label>{{ translate('First Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control rounded-0{{ $errors->has('first_name') ? ' is-invalid' : '' }}" value="{{ old('first_name') }}" placeholder="{{  translate('first_name') }}" name="first_name">
                                    @if ($errors->has('first_name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>{{ translate('Last Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control rounded-0{{ $errors->has('last_name') ? ' is-invalid' : '' }}" value="{{ old('last_name') }}" placeholder="{{  translate('last_name') }}" name="last_name">
                                    @if ($errors->has('last_name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>{{ translate('Your Email')}} <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control rounded-0{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{  translate('Email') }}" name="email">
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>{{ translate('Phone Number')}} </label>
                                    <input type="text" class="form-control rounded-0{{ $errors->has('phone') ? ' is-invalid' : '' }}" value="{{ old('phone') }}" placeholder="(+01) 234 567 89" name="phone">
                                    @if ($errors->has('phone'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>{{ translate('Message')}} </label>
                                    <input type="text" class="form-control rounded-0{{ $errors->has('message') ? ' is-invalid' : '' }}" value="{{ old('message') }}" placeholder="{{  translate('message') }}" name="message">
                                    @if ($errors->has('message'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('message') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                 <div class="form-group">
                                    <label>{{ translate('Resume Upload')}}<span class="text-danger">*</span></label>
                                    <input type="file" class="form-control rounded-0" name="resume">
                                </div>
                                <div class="form-group text-center">
                                     <label>{{ translate('Download CV Sample') }}</label><br>
                                  <a href="https://localhost/llacuna.me/public/download/resume-en.xlsx" download=""><button class="btn btn-info">In English</button></a>
                                  <a href="https://localhost/llacuna.me/public/download/resume-br.xlsx" download=""><button class="btn btn-info">In Portuguese</button></a>
                                  <a href="https://localhost/llacuna.me/public/download/resume-jp.xlsx" download=""><button class="btn btn-info">In Japanese</button></a>
                                </div>
                              
                            </div>
                        </div>

                    @if(get_setting('google_recaptcha') == 1)
                        <div class="form-group mt-2 mx-auto row">
                            <div class="g-recaptcha" data-sitekey="{{ env('CAPTCHA_KEY') }}"></div>
                        </div>
                    @endif

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary fw-600 rounded-0">{{ translate('Apply Now')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection

@section('script')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script type="text/javascript">
    // making the CAPTCHA  a required field for form submission
    $(document).ready(function(){
        $("#shop").on("submit", function(evt)
        {
            var response = grecaptcha.getResponse();
            if(response.length == 0)
            {
            //reCaptcha not verified
                alert("please verify you are humann!");
                evt.preventDefault();
                return false;
            }
            //captcha verified
            //do the rest of your validations here
            $("#reg-form").submit();
        });
    });
</script>
@endsection
