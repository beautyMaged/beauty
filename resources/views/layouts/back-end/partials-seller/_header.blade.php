<div id="headerMain" class="d-none">
    <header id="header" class="navbar navbar-expand-lg navbar-fixed navbar-height navbar-flush navbar-container navbar-bordered">
        <div class="navbar-nav-wrap">
            <div class="navbar-brand-wrapper">
                <!-- Logo -->
                @php($shop=\App\Model\Shop::where(['seller_id'=>auth('seller')->id()])->first())

                <a class="navbar-brand" href="{{route('seller.dashboard.index')}}" aria-label="">
                    @if (isset($shop))
                        <img class="navbar-brand-logo"
                             onerror="this.src='{{asset('assets/back-end/img/160x160/img1.jpg')}}'"
                             src="{{asset("storage/shop/$shop->image")}}" alt="Logo" height="40">
                        <img class="navbar-brand-logo-mini"
                             onerror="this.src='{{asset('assets/back-end/img/160x160/img1.jpg')}}'"
                             src="{{asset("storage/shop/$shop->image")}}"
                             alt="Logo" height="40">

                    @else
                        <img class="navbar-brand-logo-mini"
                             src="{{asset('assets/back-end/img/160x160/img1.jpg')}}"
                             alt="Logo" height="40">
                    @endif

                </a>
                <!-- End Logo -->
            </div>

            <div class="navbar-nav-wrap-content-left">
                <!-- Navbar Vertical Toggle -->
                <button type="button" class="js-navbar-vertical-aside-toggle-invoker close mr-3 d-xl-none">
                    <i class="tio-first-page navbar-vertical-aside-toggle-short-align" data-toggle="tooltip"
                       data-placement="right" title="Collapse"></i>
                    <i class="tio-last-page navbar-vertical-aside-toggle-full-align"
                       data-template='<div class="tooltip d-none d-sm-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                       data-toggle="tooltip" data-placement="right" title="Expand"></i>
                </button>
                <!-- End Navbar Vertical Toggle -->

                <div class="d-none">
                    <form class="position-relative">
                    </form>
                </div>
            </div>


            <!-- Secondary Content -->
            <div class="navbar-nav-wrap-content-right"
                 style="{{Session::get('direction') === "rtl" ? 'margin-left:unset; margin-right: auto' : 'margin-right:unset; margin-left: auto'}}">
                <!-- Navbar -->
                <ul class="navbar-nav align-items-center flex-row">

                    <li class="nav-item d-none d-md-inline-block">
                        <div class="hs-unfold">
                            <div>
                                @php( $local = session()->has('local')?session('local'):'en')
                                @php($lang = \App\Model\BusinessSetting::where('type', 'language')->first())
                                <div
                                    class="topbar-text dropdown disable-autohide {{Session::get('direction') === "rtl" ? 'ml-3' : 'm-1'}} text-capitalize">
                                    <a class="topbar-link dropdown-toggle text-black d-flex align-items-center title-color" href="#" data-toggle="dropdown"
                                       >
                                        @foreach(json_decode($lang['value'],true) as $data)
                                            @if($data['code']==$local)
                                                <img class="{{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}"
                                                     width="20"
                                                     src="{{asset('assets/front-end')}}/img/flags/{{$data['code']}}.png"
                                                     alt="Eng">
                                                {{$data['name']}}
                                            @endif
                                        @endforeach
                                    </a>
                                    <ul class="dropdown-menu">
                                        @foreach(json_decode($lang['value'],true) as $key =>$data)
                                            @if($data['status']==1)
                                                <li>
                                                    <a class="dropdown-item pb-1"
                                                       href="{{route('lang',[$data['code']])}}">
                                                        <img
                                                            class="{{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}"
                                                            width="20"
                                                            src="{{asset('assets/front-end')}}/img/flags/{{$data['code']}}.png"
                                                            alt="{{$data['name']}}"/>
                                                        <span class="text-capitalize">{{$data['name']}}</span>
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item d-none d-md-inline-block">
                        <!-- Notification -->
                        <div class="hs-unfold">
                            <a title="Website Home"
                               class="js-hs-unfold-invoker btn btn-icon btn-ghost-secondary rounded-circle"
                               href="{{route('home')}}" target="_blank">
                                <i class="tio-globe"></i>
                                {{--<span class="btn-status btn-sm-status btn-status-danger"></span>--}}
                            </a>
                        </div>
                        <!-- End Notification -->
                    </li>

                    <li class="nav-item d-none d-md-inline-block">
                        <!-- Notification -->
                        <div class="hs-unfold">
                            <a
                                class="js-hs-unfold-invoker btn btn-icon btn-ghost-secondary rounded-circle media align-items-center gap-3 navbar-dropdown-account-wrapper dropdown-toggle dropdown-toggle-left-arrow" href="javascript:;"
                                data-hs-unfold-options='{
                                     "target": "#messageDropdown",
                                     "type": "css-animation"
                                   }'
                               >
                                <i class="tio-email"></i>
                                @php($message=\App\Model\Chatting::where(['seen_by_seller'=>0, 'seller_id'=>auth('seller')->id()])->count())
                                @if($message!=0)
                                    <span class="btn-status btn-sm-status btn-status-danger">{{ $message }}</span>
                                @endif
                            </a>
                            <div id="messageDropdown"
                                 class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right navbar-dropdown-menu navbar-dropdown-account"
                                 style="width: 16rem;">

                                <a class="dropdown-item position-relative"
                                   href="{{route('seller.messages.chat', ['type' => 'customer'])}}">
                                    <span class="text-truncate pr-2"
                                          title="Settings">{{\App\CPU\translate('Customer')}}</span>
                                    @php($message_customer=\App\Model\Chatting::where(['seen_by_seller'=>0, 'seller_id'=>auth('seller')->id()])->whereNotNull(['user_id'])->count())
                                    @if($message_customer > 0)
                                        <span class="btn-status btn-sm-status-custom btn-status-danger">{{$message_customer}}</span>
                                    @endif


                                </a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item position-relative"
                                   href="{{route('seller.messages.chat', ['type' => 'delivery-man'])}}">
                                    <span class="text-truncate pr-2"
                                          title="Settings">{{\App\CPU\translate('Delivery_man')}}</span>
                                    @php($message_d_man =\App\Model\Chatting::where(['seen_by_seller'=>0, 'seller_id'=>auth('seller')->id()])->whereNotNull(['delivery_man_id'])->count())
                                    @if($message_d_man > 0)
                                        <span class="btn-status btn-sm-status-custom btn-status-danger">{{ $message_d_man }}</span>
                                    @endif

                                </a>

                            </div>
                        </div>
                        <!-- End Notification -->
                    </li>

                    <li class="nav-item d-none d-md-inline-block">
                        <!-- Notification -->
                        <div class="hs-unfold">
                            <a class="js-hs-unfold-invoker btn btn-icon btn-ghost-secondary rounded-circle"
                               href="{{route('seller.orders.list',['pending'])}}">
                                <i class="tio-shopping-cart-outlined"></i>
                                @php($order=\App\Model\Order::where(['seller_is'=>'seller','seller_id'=>auth('seller')->id(), 'order_status'=>'pending'])->count())
                                @if($order!=0)
                                <span class="btn-status btn-sm-status btn-status-danger">{{ $order }}</span>
                               @endif
                            </a>
                        </div>
                        <!-- End Notification -->
                    </li>

                    <li class="nav-item view-web-site-info">
                        <div class="hs-unfold">
                            <a class="bg-white" onclick="openInfoWeb()" href="javascript:"
                               class="js-hs-unfold-invoker btn btn-icon btn-ghost-secondary rounded-circle">
                                <i class="tio-info"></i>
                            </a>
                        </div>
                    </li>

                    <!-- <li class="nav-item view-web-site-info">
                        <div class="hs-unfold" >
                            <a onclick="openInfoWeb()" href="javascript:" class="bg-white js-hs-unfold-invoker btn btn-icon btn-ghost-secondary rounded-circle">
                                <i class="tio-info"></i>
                            </a>
                        </div>
                    </li> -->

                    <li class="nav-item">
                        <!-- Account -->
                        <div class="hs-unfold">
                            <a class="js-hs-unfold-invoker media align-items-center gap-3 navbar-dropdown-account-wrapper dropdown-toggle dropdown-toggle-left-arrow" href="javascript:;"
                               data-hs-unfold-options='{
                                     "target": "#accountNavbarDropdown",
                                     "type": "css-animation"
                                   }'>
                                <div class="d-none d-md-block media-body text-right">
                                   <h5 class="profile-name mb-0">{{auth('seller')->user()->name}}</h5>
                                   <span class="fz-12">{{ \Illuminate\Support\Str::limit($shop->name, 20) }}</span>
                                </div>
                                <div class="avatar avatar-sm avatar-circle">
                                    <img class="avatar-img"
                                         onerror="this.src='{{asset('assets/back-end/img/160x160/img1.jpg')}}'"
                                         src="{{asset('storage/seller/')}}/{{auth('seller')->user()->image}}"
                                         alt="Image Description">
                                    <span class="avatar-status avatar-sm-status avatar-status-success"></span>
                                </div>
                            </a>

                            <div id="accountNavbarDropdown"
                                 class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right navbar-dropdown-menu navbar-dropdown-account __w-16rem">
                                <div class="dropdown-item-text">
                                    <div class="media align-items-center text-break">
                                        <div class="avatar avatar-sm avatar-circle mr-2">

                                            <img class="avatar-img"
                                                 onerror="this.src='{{asset('assets/back-end/img/160x160/img1.jpg')}}'"
                                                 src="{{asset('storage/seller/')}}/{{auth('seller')->user()->image}}"
                                                 alt="Image Description">
                                        </div>
                                        <div class="media-body">
                                            <span class="card-title h5">{{auth('seller')->user()->f_name}}</span>

                                            <span class="card-text">{{auth('seller')->user()->email}}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item"
                                   href="{{route('seller.profile.update',auth('seller')->user()->id)}}">
                                    <span class="text-truncate pr-2"
                                          title="Settings">{{\App\CPU\translate('Settings')}}</span>
                                </a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="javascript:" onclick="Swal.fire({
                                    title: '{{\App\CPU\translate('Do you want to logout')}}?',
                                    showDenyButton: true,
                                    showCancelButton: true,
                                    confirmButtonColor: '#377dff',
                                    cancelButtonColor: '#363636',
                                    confirmButtonText: `Yes`,
                                    denyButtonText: `Don't Logout`,
                                    }).then((result) => {
                                    if (result.value) {
                                    location.href='{{route('seller.auth.logout')}}';
                                    } else{
                                    Swal.fire('Canceled', '', 'info')
                                    }
                                    })">
                                    <span class="text-truncate pr-2"
                                          title="Sign out">{{\App\CPU\translate('Sign out')}}</span>
                                </a>
                            </div>
                        </div>
                        <!-- End Account -->
                    </li>
                </ul>
                <!-- End Navbar -->
            </div>
            <!-- End Secondary Content -->
        </div>
        <div id="website_info"
             style="display:none;" class="bg-secondary w-100">
            <div class="p-3">
                <div class="bg-white p-1 rounded">
                    @php( $local = session()->has('local')?session('local'):'en')
                    @php($lang = \App\Model\BusinessSetting::where('type', 'language')->first())
                    <div
                        class="topbar-text dropdown disable-autohide {{Session::get('direction') === "rtl" ? 'ml-3' : 'm-1'}} text-capitalize">
                        <a class="topbar-link dropdown-toggle title-color d-flex align-items-center" href="#" data-toggle="dropdown">
                            @foreach(json_decode($lang['value'],true) as $data)
                                @if($data['code']==$local)
                                    <img class="{{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}"
                                         width="20"
                                         src="{{asset('assets/front-end')}}/img/flags/{{$data['code']}}.png"
                                         alt="Eng">
                                    {{$data['name']}}
                                @endif
                            @endforeach
                        </a>
                        <ul class="dropdown-menu">
                            @foreach(json_decode($lang['value'],true) as $key =>$data)
                                @if($data['status']==1)
                                    <li>
                                        <a class="dropdown-item pb-1"
                                           href="{{route('lang',[$data['code']])}}">
                                            <img
                                                class="{{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}"
                                                width="20"
                                                src="{{asset('assets/front-end')}}/img/flags/{{$data['code']}}.png"
                                                alt="{{$data['name']}}"/>
                                            <span class="text-capitalize">{{$data['name']}}</span>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="bg-white p-1 rounded mt-2">
                    <a title="Website home" class="p-2 title-color"
                       href="{{route('home')}}" target="_blank">
                        <i class="tio-globe"></i>

                        {{\App\CPU\translate('view_website')}}
                    </a>
                </div>
                <div class="bg-white p-1 rounded mt-2">
                    <a class="p-2  title-color"
                       href="{{route('seller.messages.chat', ['type' => 'customer'])}}">
                        <i class="tio-email"></i>
                        {{\App\CPU\translate('message')}}
                        @php($message=\App\Model\Chatting::where(['seen_by_seller'=>1,'seller_id'=>auth('seller')->id()])->count())
                        @if($message!=0)
                            <span class="">({{ $message }})</span>
                        @endif
                    </a>
                </div>
                <div class="bg-white p-1 rounded mt-2">
                    <a class="p-2 title-color"
                       href="{{route('seller.orders.list',['pending'])}}">
                        <i class="tio-shopping-cart-outlined"></i>
                        {{\App\CPU\translate('Order_list')}}
                    </a>
                </div>

            </div>
        </div>
    </header>
</div>
<div id="headerFluid" class="d-none"></div>
<div id="headerDouble" class="d-none"></div>
