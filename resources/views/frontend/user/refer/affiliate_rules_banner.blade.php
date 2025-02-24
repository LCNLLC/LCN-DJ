@extends('frontend.layouts.user_panel')

@section('panel_content')
    @php
        $user = Auth::user();
        $gn_setting = userAffiliateRules($user->id);
    @endphp
    <style>
        .tree ul {
            padding-left: 20px;
        }

        .tree li {
            list-style-type: none;
            position: relative;
        }

        .tree li::before {
            content: '';
            position: absolute;
            top: 0;
            left: -10px;
            border-left: 1px dotted #000;
            height: 100%;
        }

        .text {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;

        }
    </style>
    <div class="card shadow-none rounded-0 border">
        <div class="alert alert-warning mb-4 my-5" role="alert" style="margin: 0px 16px">
            <h4 style="color: black">{{ translate('Referral Role') }}</h4>
            <div class="form-body">
                <div class="form-row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                        <div class="form-group">
                            <label class="text">{{ translate('Ranking') }}</label>
                            <input type="text" class="form-control" id="inputLevel" placeholder="Level"
                                value="{{ isset($gn_setting['user_level']->level) ? $gn_setting['user_level']->level : '0' }}" disabled>
                            <small>{{ translate('To change your position, you must meet the rules and requirements. The current ranking is') }}:</small><b>{{ isset($gn_setting['user_level']->level) ? $gn_setting['user_level']->level : '0' }}</b>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                        <div class="form-group">
                            <label class="text">{{ translate('Commission') }}</label>
                            <input type="text" class="form-control" id="inputLevel" placeholder="Level"
                                value="{{ isset($gn_setting['user_level']->percent) ? $gn_setting['user_level']->percent . '%' : '0%' }}"
                                disabled>
                            <small>{{ translate('To get more commissions, you need to comply with the rules and regulations. The current commission is') }}:</small><b>{{ isset($gn_setting['user_level']->percent) ? $gn_setting['user_level']->percent . '%' : '0%' }}</b>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                        <div class="form-group">
                            <label class="text">{{ translate('Deadline') }}</label>
                            <input type="text" class="form-control" id="inputlogin" placeholder="login"
                                value="{{ isset($gn_setting['trans']) ? $gn_setting['trans'] : 0 }}" disabled>
                            <small>{{ translate('In order to withdraw profits, it is necessary to meet the rules and conditions. The current period is') }}:</small><b>{{ referlSetting('affiliate_withdraw_deadline') }}</b>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                        <div class="form-group">
                            <label class="text">{{ translate('Refrences') }}</label>
                            <input type="text" class="form-control" id="inputviews" placeholder="views"
                                value="{{ $gn_setting['user_referral'] }}" disabled disabled>
                            <small>{{ translate('In order to withdraw money, you need to meet the rules and regulations. The current reference is') }}:</small><b>{{ referlSetting('affiliate_withdraw_person') }}</b>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <p style="color: black">@lang('To receive commission you need to have at least 7 references.')</p> -->
            <hr>

            <div class="row">
                <p style="color: black;width: 95%;margin-left: 3%;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-alert-triangle">
                        <path
                            d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                        <line x1="12" y1="9" x2="12" y2="13" />
                        <line x1="12" y1="17" x2="12.01" y2="17" />
                    </svg>
                    {{ translate('To receive commission and cashback you have a need to fulfill these rules and follow these conditions') }}
                </p>
            </div>
        </div>

        <div class="card rounded-0 shadow-none border mx-3 mb-5">
            <div class="card-header pt-4 border-bottom-0">
                <h5 class="mb-0 fs-18 fw-700 text-dark">{{ translate('Referral link') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <label class="fs-14">{{ translate('Copy the link') }}</label>
                    </div>
                    <div class="col-md-10">
                        <div class="input-group mb-3">
                            <input type="email" id="referral-link" class="form-control rounded-0 w-50"
                                placeholder="Seu e-mail" name="email"
                                value="{{ env('APP_URL') . '/users/registration?referral_code=' . $user->referral_code }}">
                            <div class="input-group-append ">
                                <button type="button" id="copy-button"
                                    class="btn btn-outline-secondary new-email-verification">
                                    <span>
                                        {{ translate('Copy') }}
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card rounded-0 shadow-none border mx-3 mb-5">
            <div class="card-header pt-4 border-bottom-0">
                <h5 class="mb-0 fs-18 fw-700 text-dark">{{ translate('Referral Statistics') }}</h5>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <ul class="list-group d-flex">

                        <li class="list-group-item">{{ translate('User Name') }}: {{ $user->name }}</li>

                    </ul>


                    <div class="col-md-8">


                        <div class="tree">
                            {{ printReferralTree($user->id) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var copyButton = document.getElementById('copy-button');
            var referralLink = document.getElementById('referral-link');

            copyButton.addEventListener('click', function() {
                referralLink.select();
                document.execCommand('copy');
            });
        });
    </script>
@endsection
