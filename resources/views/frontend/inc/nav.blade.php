<!-- Top Banner -->
@if(get_setting('topbar_banner') != null)
<div class="position-relative top-banner removable-session z-1035"
     data-key="top-banner" data-value="removed">
    <a href="{{ get_setting('topbar_banner_link') }}" class="d-block text-reset">
        <!-- Large device -->
        <img src="{{ uploaded_asset(get_setting('topbar_banner')) }}"
             class="d-none d-xl-block img-fit w-100">
        <!-- Medium device -->
        <img src="{{ get_setting('topbar_banner_medium')
                    ? uploaded_asset(get_setting('topbar_banner_medium'))
                    : uploaded_asset(get_setting('topbar_banner')) }}"
             class="d-none d-md-block d-xl-none img-fit w-100">
        <!-- Small device -->
        <img src="{{ get_setting('topbar_banner_small')
                    ? uploaded_asset(get_setting('topbar_banner_small'))
                    : uploaded_asset(get_setting('topbar_banner')) }}"
             class="d-md-none img-fit w-100">
    </a>
    <!-- Close Button -->
    <button class="btn text-white absolute-top-right set-session"
            data-key="top-banner" data-value="removed" data-toggle="remove-parent"
            data-parent=".top-banner">
        <i class="la la-close la-2x"></i>
    </button>
</div>
@endif

<!-- Top Bar -->
<div class="top-navbar text-white z-1035 py-2" style="background-color: #212129 !important;">
    <div class="container">
        <div class="row align-items-center">
            <!-- Left Section -->
            <div class="col-lg-5 col-6">
                <ul class="list-inline mb-0">
                    <!-- Language Switcher -->
                    @if(get_setting('show_language_switcher') == 'on')
                        <li class="list-inline-item dropdown" id="lang-change">
                            @php
                                $locale = Session::has('locale')
                                          ? Session::get('locale', Config::get('app.locale'))
                                          : 'en';
                            @endphp
                            <a href="javascript:void(0)"
                               class="dropdown-toggle text-white fs-13 py-2"
                               data-toggle="dropdown" data-display="static">
                                {{ \App\Models\Language::where('code', $locale)->first()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                @foreach (\App\Models\Language::where('status', 1)->get() as $language)
                                    <li>
                                        <a href="javascript:void(0)"
                                           data-flag="{{ $language->code }}"
                                           class="dropdown-item @if($locale == $language) active @endif">
                                            <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                 data-src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}"
                                                 class="mr-1 lazyload"
                                                 alt="{{ $language->name }}"
                                                 height="11">
                                            <span>{{ $language->name }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif

                    <!-- Currency Switcher -->
                    @if(get_setting('show_currency_switcher') == 'on')
                        <li class="list-inline-item dropdown ml-3" id="currency-change">
                            @php
                                $currency_code = Session::has('currency_code')
                                    ? Session::get('currency_code')
                                    : \App\Models\Currency::findOrFail(get_setting('system_default_currency'))->code;
                            @endphp
                            <a href="javascript:void(0)"
                               class="dropdown-toggle text-white fs-13 py-2"
                               data-toggle="dropdown" data-display="static">
                                {{ \App\Models\Currency::where('code', $currency_code)->first()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                @foreach (\App\Models\Currency::where('status', 1)->get() as $currency)
                                    <li>
                                        <a class="dropdown-item @if($currency_code == $currency->code) active @endif"
                                           href="javascript:void(0)"
                                           data-currency="{{ $currency->code }}">
                                           {{ $currency->name }} ({{ $currency->symbol }})
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- Right Section -->
            <div class="col-lg-7 col-6 text-right d-none d-lg-block">
                <ul class="list-inline mb-0">
                    @if (get_setting('vendor_system_activation') == 1)
                        <li class="list-inline-item border-right pr-3">
                            <a href="{{ route('shops.create') }}" class="text-white fs-13">
                                {{ translate('Become a Seller!')}}
                            </a>
                        </li>
                        <li class="list-inline-item border-right px-3">
                            <a href="{{ route('seller.login') }}" class="text-white fs-13">
                                {{ translate('Seller Login')}}
                            </a>
                        </li>
                        <li class="list-inline-item border-right px-3">
                            <a href="{{ route('couriers.create') }}" class="text-white fs-13">
                                {{ translate('Become a Courier!')}}
                            </a>
                        </li>
                        <li class="list-inline-item px-3">
                            <a href="{{ route('courier.login') }}" class="text-white fs-13">
                                {{ translate('Courier Login')}}
                            </a>
                        </li>
                    @endif

                    @if (get_setting('helpline_number'))
                        <li class="list-inline-item ml-3">
                            <a href="tel:{{ get_setting('helpline_number') }}" class="text-white fs-13">
                                {{ translate('Helpline') }}: {{ get_setting('helpline_number') }}
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Main Header -->
<header class="@if(get_setting('header_stikcy') == 'on') sticky-top @endif shadow-sm" style="background: #06D6A0; color: whitesmoke">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light py-2">
            <!-- Mobile menu button -->
            <button class="navbar-toggler border-0 p-2" type="button"
                    data-toggle="collapse" data-target="#mobileMenu"
                    aria-controls="mobileMenu" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Logo -->
            <a class="navbar-brand ml-3" href="{{ route('home') }}">
                @php
                    $header_logo = get_setting('header_logo');
                @endphp
                @if($header_logo != null)
                    <img src="{{ uploaded_asset($header_logo) }}" alt="{{ env('APP_NAME') }}"
                         class="mw-100" style="height: 40px;">
                @else
                    <img src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}"
                         class="mw-100" style="height: 40px;">
                @endif
            </a>

            <!-- Search for LG+ screens -->
            <form action="{{ route('search') }}" method="GET" class="d-none d-lg-block ml-3 flex-grow-1">
                <div class="input-group border rounded-pill overflow-hidden shadow-sm">
                    <input type="text" name="keyword" id="search"
                           class="form-control border-0 px-4"
                           placeholder="{{translate('I am shopping for...')}}"
                           @isset($query) value="{{ $query }}" @endisset
                           autocomplete="off">
                    <div class="input-group-append">
                        <button class="btn btn-white px-4 border-0 d-flex align-items-center" type="submit">
                            <i class="las la-search la-lg"></i>
                        </button>
                    </div>
                </div>
            </form>
            <!-- Right Icons (Compare, Wishlist, Cart, Profile, etc.) -->
            <ul class="navbar-nav ml-auto align-items-center d-flex flex-row">
                <!-- Search icon for mobile -->
                <li class="nav-item d-block d-lg-none">
                    <a class="nav-link text-white" href="javascript:void(0);"
                       data-toggle="collapse" data-target="#mobileSearch">
                        <i class="las la-search la-2x text-white"></i>
                    </a>
                </li>

                <!-- Compare -->
                <li class="ml-3 nav-item d-block">
                    <div id="compare">
                        @include('frontend.partials.compare')
                    </div>
                </li>

                <!-- Wishlist -->
                <li class="nav-item d-block ml-2 ml-lg-3">
                    <div id="wishlist">
                        @include('frontend.partials.wishlist')
                    </div>
                </li>

                <!-- Notifications (Only if not Admin) -->
                @if(!isAdmin())
                    <li class="nav-item dropdown d-none d-xl-block ml-3">
                        <a class="nav-link text-dark" href="javascript:void(0);"
                           id="notificationDropdown" data-toggle="dropdown">
                            <i class="las la-bell la-2x position-relative text-white">
                                @if(Auth::check() && count(Auth::user()->unreadNotifications) > 0)
                                    <span class="badge badge-primary badge-pill position-absolute"
                                          style="top: -5px; right: -10px;">
                                          {{ count(Auth::user()->unreadNotifications) }}
                                    </span>
                                @endif
                            </i>
                        </a>
                        @auth
                            <div class="dropdown-menu dropdown-menu-right p-0"
                                 aria-labelledby="notificationDropdown" style="min-width: 250px;">
                                <div class="p-3 bg-light border-bottom">
                                    <h6 class="mb-0">{{ translate('Notifications') }}</h6>
                                </div>
                                <div class="px-3 c-scrollbar-light" style="max-height:300px; overflow:auto;">
                                    <ul class="list-group list-group-flush">
                                        @forelse(Auth::user()->unreadNotifications as $notification)
                                            <li class="list-group-item">
                                                @if($notification->type == 'App\Notifications\OrderNotification')
                                                    @if(Auth::user()->user_type == 'customer')
                                                        <a href="{{ route('purchase_history.details', encrypt($notification->data['order_id'])) }}"
                                                           class="text-dark fs-14">
                                                            {{translate('Order code:')}} {{$notification->data['order_code']}}
                                                            {{ translate('has been '. ucfirst(str_replace('_', ' ', $notification->data['status']))) }}
                                                        </a>
                                                    @elseif (Auth::user()->user_type == 'seller')
                                                        <a href="{{ route('seller.orders.show', encrypt($notification->data['order_id'])) }}"
                                                           class="text-dark fs-14">
                                                            {{translate('Order code:')}} {{$notification->data['order_code']}}
                                                            {{ translate('has been '. ucfirst(str_replace('_', ' ', $notification->data['status']))) }}
                                                        </a>
                                                    @endif
                                                @endif
                                            </li>
                                        @empty
                                            <li class="list-group-item text-center">
                                                <span class="fs-14">{{ translate('No notification found') }}</span>
                                            </li>
                                        @endforelse
                                    </ul>
                                </div>
                                <div class="text-center border-top">
                                    <a href="{{ route('all-notifications') }}" class="text-dark fs-14 d-block py-2">
                                        {{translate('View All Notifications')}}
                                    </a>
                                </div>
                            </div>
                        @endauth
                    </li>
                @endif
                <!-- User Info -->
                <li class="nav-item dropdown ml-3 text-white">
                    @auth
                        <a class="nav-link text-white text-bold d-flex align-items-center"
                           href="javascript:void(0)" id="userMenu" data-toggle="dropdown">
                            <span class="size-20px rounded-circle overflow-hidden border">
                                @if(Auth::user()->avatar_original)
                                    <img src="{{ uploaded_asset(Auth::user()->avatar_original) }}"
                                         class="img-fit h-100 w-100"
                                         onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                @else
                                    <img src="{{ static_asset('assets/img/avatar-place.png') }}"
                                         class="img-fit h-100 w-100">
                                @endif
                            </span>
                            <span class="ml-2 text-white">{{ Auth::user()->name }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userMenu">
                            @if(isAdmin())
                                <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    {{ translate('Admin Dashboard') }}
                                </a>
                            @else
                                <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    {{ translate('My Dashboard') }}
                                </a>
                            @endif
                            <a class="dropdown-item text-primary" href="{{ route('logout') }}">
                                {{ translate('Logout') }}
                            </a>
                        </div>
                    @else
                        <a class="nav-link text-white" href="{{ route('user.login') }}">
                            <i class="las la-user-circle la-2x"></i>
                        </a>
                    @endauth
                </li>

                <!-- Cart (Desktop) -->
                <li class="nav-item d-none d-xl-block ml-3">
                    <div class="nav-cart-box dropdown" id="cart_items">
                        @include('frontend.partials.cart')
                    </div>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Mobile Search Collapse -->
    <div class="collapse bg-light" id="mobileSearch">
        <div class="container py-3">
            <form action="{{ route('search') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="keyword" class="form-control"
                           placeholder="{{ translate('I am shopping for...') }}"
                           @isset($query) value="{{ $query }}" @endisset>
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="las la-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Mobile Menu Collapse -->
    <div class="collapse d-lg-none bg-light" id="mobileMenu">
        <div class="p-3">
            <!-- If you want the same links as your header_menu_labels/links, you can loop them here -->
            @if (get_setting('header_menu_labels') != null)
                @foreach (json_decode(get_setting('header_menu_labels'), true) as $key => $value)
                    <a href="{{ json_decode(get_setting('header_menu_links'), true)[$key] }}"
                       class="d-block py-2 border-bottom text-dark">
                        {{ translate($value) }}
                    </a>
                @endforeach
            @endif

            @auth
                <!-- Example additional mobile links -->
                <a href="{{ route('dashboard') }}" class="d-block py-2 text-dark">
                    {{ translate('My Account') }}
                </a>
                <a href="{{ route('wishlists.index') }}" class="d-block py-2 text-dark">
                    {{ translate('Wishlist') }}
                </a>
                <a href="{{ route('compare') }}" class="d-block py-2 text-dark">
                    {{ translate('Compare') }}
                </a>
                <a href="{{ route('logout') }}" class="d-block py-2 text-primary">
                    {{ translate('Logout') }}
                </a>
            @else
                <a href="{{ route('user.login') }}" class="d-block py-2 text-dark">
                    {{ translate('Login') }}
                </a>
                <a href="{{ route('user.registration') }}" class="d-block py-2 text-dark">
                    {{ translate('Registration') }}
                </a>
            @endauth
        </div>
    </div>
</header>

<!-- Optional: Category Menu Bar (Desktop) -->
<!-- Modern Category Menu with Centered Text -->
<div class="d-none d-lg-block py-2" style="background: black">
    <div class="container">
        <div class="d-flex justify-content-center align-items-center">
            <!-- Header Menus -->
            <ul class="list-inline mb-0 text-center">
                @if (get_setting('header_menu_labels') != null)
                    @foreach (json_decode(get_setting('header_menu_labels'), true) as $key => $value)
                        <li class="list-inline-item">
                            <a href="{{ json_decode(get_setting('header_menu_links'), true)[$key] }}"
                               class="text-white px-4 py-2 d-inline-block transition-all hover:bg-primary rounded-pill">
                                {{ translate($value) }}
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
</div>

<!-- Modal for order details --><div class="modal fade" id="order_details" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div id="order-details-modal-body"></div>
        </div>
    </div>
</div>

@section('script')
<script type="text/javascript">
    function show_order_details(order_id){
        $('#order-details-modal-body').html(null);
        $.post('{{ route('orders.details') }}', {
            _token: AIZ.data.csrf,
            order_id: order_id
        }, function(data){
            $('#order-details-modal-body').html(data);
            $('#order_details').modal('show');
        });
    }
</script>
@endsection
