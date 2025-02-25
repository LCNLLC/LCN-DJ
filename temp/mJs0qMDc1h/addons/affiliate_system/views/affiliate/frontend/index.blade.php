@extends('frontend.layouts.app')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="d-flex align-items-start">
                @include('frontend.inc.user_side_nav')
                <div class="aiz-user-panel">
                    <div class="aiz-titlebar mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h3 class="fs-20 fw-700 text-dark">{{ translate('Affiliate') }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row gutters-16 mb-2">
                        <!-- Affiliate Balance -->
                        <div class="col-md-4 mx-auto mb-4">
                            <div class="bg-dark text-white overflow-hidden text-center p-4 h-100">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17.402" height="32"
                                    viewBox="0 0 17.402 32">
                                    <path id="Path_32606" data-name="Path 32606"
                                        d="M14.888-4.338a4.994,4.994,0,0,0-2.021-1.9,6.687,6.687,0,0,0-3.175-.7,5.029,5.029,0,0,0-3.258.969,3.108,3.108,0,0,0-1.2,2.536q0,2.515,3.34,3.175l3.052.577a13.933,13.933,0,0,1,2.8.825,7.913,7.913,0,0,1,2.227,1.381,5.876,5.876,0,0,1,1.485,2.082A7.211,7.211,0,0,1,18.682,7.5a6.445,6.445,0,0,1-.536,2.7,6.111,6.111,0,0,1-1.505,2.041A7.129,7.129,0,0,1,14.332,13.6a11.987,11.987,0,0,1-2.907.66v2.474a1.62,1.62,0,0,1-.371,1.093,1.334,1.334,0,0,1-1.072.433,1.334,1.334,0,0,1-1.072-.433,1.62,1.62,0,0,1-.371-1.093V14.219A9.33,9.33,0,0,1,5.61,13.5a9.09,9.09,0,0,1-2.082-1.258,6.581,6.581,0,0,1-1.34-1.464,6.227,6.227,0,0,1-.7-1.381,2.691,2.691,0,0,1-.206-.948A1.548,1.548,0,0,1,1.734,7.27a1.6,1.6,0,0,1,1.155-.433,1.3,1.3,0,0,1,.928.33,3.373,3.373,0,0,1,.639.866,13.046,13.046,0,0,0,.763,1.175A4.954,4.954,0,0,0,6.332,10.3a5.722,5.722,0,0,0,1.67.8,7.922,7.922,0,0,0,2.351.309,4.989,4.989,0,0,0,3.629-1.175,3.727,3.727,0,0,0,1.2-2.742,3.53,3.53,0,0,0-1.052-2.763,6.445,6.445,0,0,0-3.072-1.361L7.837,2.755A8.572,8.572,0,0,1,3.115.507a5.631,5.631,0,0,1-1.381-3.9A5.738,5.738,0,0,1,3.589-7.843,8.258,8.258,0,0,1,8.538-9.822v-2.433a1.45,1.45,0,0,1,.412-1.072,1.45,1.45,0,0,1,1.072-.412,1.316,1.316,0,0,1,1.031.412,1.542,1.542,0,0,1,.371,1.072v2.474a9.785,9.785,0,0,1,2.412.66,9.885,9.885,0,0,1,1.856,1.031,6.7,6.7,0,0,1,1.32,1.216,6.849,6.849,0,0,1,.8,1.216A2.018,2.018,0,0,1,18.1-4.627,1.4,1.4,0,0,1,17.692-3.6a1.5,1.5,0,0,1-1.113.412,1.5,1.5,0,0,1-.99-.309A3.423,3.423,0,0,1,14.888-4.338Z"
                                        transform="translate(-1.28 13.74)" fill="#fff" />
                                </svg>
                                <div class="py-2 mt-2">
                                    <div class="fs-14 fw-400 text-center">{{ translate('Affiliate Balance') }}</div>
                                    <div class="fs-30 fw-700 text-center">
                                        {{ single_price(Auth::user()->affiliate_user->balance) }}</div>
                                </div>
                            </div>
                        </div>
                        <!-- Configure Payout -->
                        <div class="col-md-4 mx-auto mb-4">
                            <a href="{{ route('affiliate.payment_settings') }}">
                                <div
                                    class="p-4 mb-3 c-pointer text-center bg-light has-transition border h-100 hov-bg-soft-light">
                                    <span
                                        class="size-60px rounded-circle mx-auto bg-dark d-flex align-items-center justify-content-center mb-3">
                                        <i class="las la-cog la-3x text-white"></i>
                                    </span>
                                    <div class="fs-14 fw-600 text-dark">{{ translate('Configure Payout') }}</div>
                                </div>
                            </a>
                        </div>
                        <!-- Affiliate Withdraw Request -->
                        <div class="col-md-4 mx-auto mb-4">
                            <div class="p-4 mb-3 c-pointer text-center bg-light has-transition border h-100 hov-bg-soft-light"
                                onclick="show_affiliate_withdraw_modal()">
                                <span
                                    class="size-60px rounded-circle mx-auto bg-dark d-flex align-items-center justify-content-center mb-3">
                                    <i class="las la-plus la-3x text-white"></i>
                                </span>
                                <div class="fs-14 fw-600 text-dark">{{ translate('Affiliate Withdraw Request') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Copy Url -->
                    @if (addon_is_activated('affiliate_system') && get_affliate_purchase_option_status())
                        <div class="row">
                            @php
                                if (Auth::user()->referral_code == null) {
                                    Auth::user()->referral_code = substr(Auth::user()->id . Str::random(), 0, 10);
                                    Auth::user()->save();
                                }
                                $referral_code = Auth::user()->referral_code;
                                $referral_code_url = URL::to('/users/registration') . "?referral_code=$referral_code";
                            @endphp
                            <div class="col mb-2">
                                <div class="card rounded-0 shadow-none border mb-4">
                                    <div class="form-box-content p-4">
                                        <div class="row gutters-16">
                                            <div class="col-lg-10 col-sm-9 form-group mb-3 mb-sm-0">
                                                <textarea id="referral_code_url" class="form-control rounded-0" readonly type="text">{{ $referral_code_url }}</textarea>
                                            </div>
                                            <div class="col-lg-2 col-sm-3">
                                                <button type=button id="ref-cpurl-btn"
                                                    class="btn btn-primary btn-block rounded-0"
                                                    data-attrcpy="{{ translate('Copied') }}"
                                                    onclick="copyToClipboard('url')">{{ translate('Copy Url') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Affiliate Stats -->
                    <div class="card rounded-0 shadow-none border mb-3">
                        <form class="" id="sort_blogs" action="" method="GET">
                            <div class="card-header border-bottom-0 row pt-4">
                                <div class="col text-center text-md-left">
                                    <h5 class="mb-md-0 fs-18 fw-700 text-dark">{{ translate('Affiliate Status') }}</h5>
                                </div>
                                <div class="col-md-5 col-xl-4">
                                    <div class="input-group mb-0">
                                        <select class="form-control aiz-selectpicker" name="type"
                                            data-live-search="true">
                                            <option value="">Choose</option>
                                            <option value="Today" @if ($type == 'Today') selected @endif>Today
                                            </option>
                                            <option value="7" @if ($type == '7') selected @endif>Last 7
                                                Days</option>
                                            <option value="30" @if ($type == '30') selected @endif>Last 30
                                                Days</option>
                                        </select>
                                        <button class="btn btn-primary rounded-0 input-group-append"
                                            type="submit">{{ translate('Filter') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="card-body">
                            <div class="row gutters-16">
                                <!-- No. of Click -->
                                <div class="col-md-3 mx-auto mb-3">
                                    <a href="#">
                                        <div
                                            class="p-4 mb-3 c-pointer text-center bg-light has-transition border h-100 hov-bg-soft-light">
                                            <span class="fs-30 fw-700 text-dark mb-3">
                                                @if ($affliate_stats->count_click)
                                                    {{ $affliate_stats->count_click }}
                                                @else
                                                    0
                                                @endif
                                            </span>
                                            <div class="fs-14 fw-700 text-dark">{{ translate('No. of Click') }}</div>
                                        </div>
                                    </a>
                                </div>
                                <!-- No. of Item -->
                                <div class="col-md-3 mx-auto mb-3">
                                    <a href="#">
                                        <div
                                            class="p-4 mb-3 c-pointer text-center bg-light has-transition border h-100 hov-bg-soft-light">
                                            <span class="fs-30 fw-700 text-dark">
                                                @if ($affliate_stats->count_item)
                                                    {{ $affliate_stats->count_item }}
                                                @else
                                                    0
                                                @endif
                                            </span>
                                            <div class="fs-14 fw-700 text-dark">{{ translate('No. of Item') }}</div>
                                        </div>
                                    </a>
                                </div>
                                <!-- No. of Deliverd -->
                                <div class="col-md-3 mx-auto mb-3">
                                    <a href="#">
                                        <div
                                            class="p-4 mb-3 c-pointer text-center bg-light has-transition border h-100 hov-bg-soft-light">
                                            <span class="fs-30 fw-700 text-dark">
                                                @if ($affliate_stats->count_delivered)
                                                    {{ $affliate_stats->count_delivered }}
                                                @else
                                                    0
                                                @endif
                                            </span>
                                            <div class="fs-14 fw-700 text-dark">{{ translate('No. of Deliverd') }}</div>
                                        </div>
                                    </a>
                                </div>
                                <!-- No. of Cancel -->
                                <div class="col-md-3 mx-auto mb-3">
                                    <a href="#">
                                        <div
                                            class="p-4 mb-3 c-pointer text-center bg-light has-transition border h-100 hov-bg-soft-light">
                                            <span class="fs-30 fw-700 text-dark">
                                                @if ($affliate_stats->count_cancel)
                                                    {{ $affliate_stats->count_cancel }}
                                                @else
                                                    0
                                                @endif
                                            </span>
                                            <div class="fs-14 fw-700 text-dark">{{ translate('No. of Cancel') }}</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>

                    <!-- Affiliate Earning History -->
                    <div class="card rounded-0 shadow-none border">
                        <div class="card-header border-bottom-0">
                            <h5 class="mb-0 fs-18 fw-700 text-dark mt-4">{{ translate('Affiliate Earning History') }}</h5>
                        </div>
                        <div class="card-body pt-0">
                            <table class="table aiz-table mb-0">
                                <thead class="text-gray fs-12">
                                    <tr>
                                        <th class="pl-0">#</th>
                                        <th>{{ translate('Referral User') }}</th>
                                        <th>{{ translate('Amount') }}</th>
                                        <th data-breakpoints="lg">{{ translate('Order Id') }}</th>
                                        <th data-breakpoints="lg">{{ translate('Referral Type') }}</th>
                                        <th data-breakpoints="lg">{{ translate('Product') }}</th>
                                        <th data-breakpoints="lg" class="text-right pr-0">{{ translate('Date') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="fs-14">
                                    @foreach ($affiliate_logs as $key => $affiliate_log)
                                        <tr>
                                            <td class="pl-0" style="vertical-align: middle;">
                                                {{ sprintf('%02d', $key + 1 + ($affiliate_logs->currentPage() - 1) * $affiliate_logs->perPage()) }}
                                            </td>
                                            <td class="fw-700" style="vertical-align: middle;">
                                                @if ($affiliate_log->user_id !== null)
                                                    {{ $affiliate_log->user->name }}
                                                @else
                                                    {{ translate('Guest') . ' (' . $affiliate_log->guest_id . ')' }}
                                                @endif
                                            </td>
                                            <td class="fw-700" style="vertical-align: middle;">
                                                {{ single_price($affiliate_log->amount) }}</td>
                                            <td class="fw-700 text-primary" style="vertical-align: middle;">
                                                @if ($affiliate_log->order != null)
                                                    {{ $affiliate_log->order->code }}
                                                @else
                                                    {{ $affiliate_log->order_detail != null ?? $affiliate_log->order_detail->order->code }}
                                                @endif
                                            </td>
                                            <td style="vertical-align: middle;">
                                                {{ ucwords(str_replace('_', ' ', $affiliate_log->affiliate_type)) }}</td>
                                            <td class="fw-700" style="vertical-align: middle;">
                                                @if ($affiliate_log->order_detail_id != null)
                                                    @php
                                                        $url = $affiliate_log->order_detail != null ?? ($affiliate_log->order_detail->product->auction_product == 1 ? route('auction-product', $affiliate_log->order_detail->product->slug) : route('product', $affiliate_log->order_detail->product->slug));
                                                    @endphp
                                                    <a href="{{ $url }}" target="_blank"
                                                        title="{{ $affiliate_log->order_detail != null ?? $affiliate_log->order_detail->product->name }}"
                                                        class="text-reset text-truncate hov-text-primary">
                                                        {{ $affiliate_log->order_detail != null ?? $affiliate_log->order_detail->product->name }}
                                                    </a>
                                                @endif
                                            </td>
                                            <td class="text-right pr-0" style="vertical-align: middle;">
                                                {{ $affiliate_log->created_at->format('d-m-Y') }} </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="aiz-pagination">
                                {{ $affiliate_logs->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('modal')
    <!-- Affiliate Withdraw Modal -->
    <div class="modal fade" id="affiliate_withdraw_modal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('Affiliate Withdraw Request') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>

                <form class="" action="{{ route('affiliate.withdraw_request.store') }}" method="post">
                    @csrf
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="row">
                            <div class="col-md-3">
                                <label>{{ translate('Amount') }} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-9">
                                <input type="number" class="form-control mb-3 rounded-0" name="amount" min="1"
                                    max="{{ Auth::user()->affiliate_user->balance }}"
                                    placeholder="{{ translate('Amount') }}" required>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit"
                                class="btn btn-sm btn-primary rounded-0 transition-3d-hover mr-1">{{ translate('Confirm') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        function copyToClipboard(btn) {
            // var el_code = document.getElementById('referral_code');
            var el_url = document.getElementById('referral_code_url');
            // var c_b = document.getElementById('ref-cp-btn');
            var c_u_b = document.getElementById('ref-cpurl-btn');

            // if(btn == 'code'){
            //     if(el_code != null && c_b != null){
            //         el_code.select();
            //         document.execCommand('copy');
            //         c_b .innerHTML  = c_b.dataset.attrcpy;
            //     }
            // }

            if (btn == 'url') {
                if (el_url != null && c_u_b != null) {
                    el_url.select();
                    document.execCommand('copy');
                    c_u_b.innerHTML = c_u_b.dataset.attrcpy;
                }
            }
        }

        function show_affiliate_withdraw_modal() {
            $('#affiliate_withdraw_modal').modal('show');
        }
    </script>
@endsection
