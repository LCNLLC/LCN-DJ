@extends('frontend.layouts.app')


@section('content')

    <div id="content" class="main-content">
        <div class="layout-px-spacing">

            <div class="row layout-top-spacing" id="cancel-row">


                <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                    <div class="widget-content widget-content-area br-6">
                        <h1>Referral history</h1>

{{--                        @include('frontend.refer.affiliate_rules_banner')--}}

                        @if (count($errors) > 0)
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-warning mb-4" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        &times;
                                    </button>
                                    <strong>{{__($error)}}</strong>
                                </div>
                            @endforeach

                        @endif
                        <div class="table-responsive mb-4 mt-4">
                            <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                                <thead>
                                <tr>
                                    <th scope="col">@lang('Date')</th>
                                    <th scope="col">@lang('Referred Users')</th>
                                    <th scope="col">@lang('Amount')</th>
                                    <th scope="col">@lang('Status')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($ref_comms)==0)
                                    <tr>
                                        <td colspan="5" class="text-center">@lang('No Data Available')</td>
                                    </tr>
                                @endif
                                @php
                                    $today = \Carbon\Carbon::now();
                                @endphp
                                @foreach($ref_comms as $data)
                                    <tr>

                                        <td scope="row">{{date('g:ia \o\n l jS F Y', strtotime($data->created_at))}}</td>
                                        <td>@if(isset($data->ref_user->name)){{__($data->ref_user->name)}}@else @endif</td>
                                        <td>{{abs($data->amount)}}</td>
                                        <td style="padding-top: 20px;">
                                            @php
                                                $day = $today->diffInDays(\Carbon\Carbon::parse($data->created_at));
                                            @endphp
{{--                                            @if($data->status == 0)--}}
                                                <span class="btn btn-success btn-sm"
                                                     >@lang('Complete')</span>
{{--                                                @dd( $day ,referlSetting('affiliate_withdraw_day'), $ref_comms->count(),  referlSetting('affiliate_withdraw_person'))--}}
{{--                                            @elseif($data->status == 1 && $day >=referlSetting('affiliate_withdraw_day') && $ref_comms->count() >= referlSetting('affiliate_withdraw_person') )--}}
{{--                                                <form action="{{ route('user.referral.com.adjust') }}" method="post"--}}
{{--                                                      style="padding-top: 10px;">--}}
{{--                                                    @csrf--}}
{{--                                                    <input type="hidden" name="ref_comm_id" value="{{ $data->id }}">--}}
{{--                                                    <button type="submit"--}}
{{--                                                            class="btn btn-success ">@lang('Add to Balance')</button>--}}
{{--                                                </form>--}}
{{--                                            @else--}}
{{--                                                <img style="width: 30px;" src="{{ asset('public/assets/img/load.gif') }}" alt="loader">--}}
{{--                                                <br>--}}
{{--                                                <span class="badge badge-warning">@lang('Running')</span>--}}
{{--                                            @endif--}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>



@endsection


