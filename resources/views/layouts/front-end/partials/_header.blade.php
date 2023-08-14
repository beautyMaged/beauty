@php( $local = session()->has('local')?session('local'):'en')
@php($lang = \App\Model\BusinessSetting::where('type', 'language')->first())

<style>
.for-count-value { color: {{$web_config['primary_color']}};}
.count-value {color: {{$web_config['primary_color']}};} 
.all_cats_list { {{session('direction') == 'rtl' ? 'right:-20px!important' : ''}} }
.owl-carousel.owl-rtl .owl-item { {{session('direction') == 'rtl' ? 'float:right!important' : 'float:left!important'}} }
#categories_list .owl-nav button.owl-prev { {{session('direction') == 'rtl' ? '' : 'right:auto!important;left: 0!important'}}}
#categories_list .owl-nav button.owl-next { {{session('direction') == 'rtl' ? '' : 'left:auto!important;right: 0!important;'}}}
.nav_2 {max-width: 100% !important;}
@media (min-width: 768px) {.navbar-stuck-menu {background-color: {{$web_config['primary_color']}};}}
@media (max-width: 767px) {.search_button .input-group-text i {color: {{$web_config['primary_color']}} !important;}.navbar-expand-md .dropdown-menu > .dropdown > .dropdown-toggle {padding- {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 1.95rem;}


    /**/
</style>
@php($announcement=\App\CPU\Helpers::get_business_settings('announcement'))
    @if (isset($announcement) && $announcement['status']==1)
        <div class="text-center position-relative px-4 py-1" id="anouncement" style="background-color: {{ $announcement['color'] }};color:{{$announcement['text_color']}}">
            <span>{{ $announcement['announcement'] }} </span>
            <span class="__close-anouncement" onclick="myFunction()">X</span>
        </div>
    @endif


<header class="box-shadow-sm rtl __inline-10" style="z-index: 999; position:relative;">
    <!-- Topbar-->
    @php($banner=\App\Model\Banner::inRandomOrder()->where(['published'=>1,'banner_type'=>'Popup Banner'])->first())
    @if(isset($banner))
        <div class="pop_up_banner d-lg-none d-md-none row m-0 p-0 justify-content-lg-center" style="background: #000">
            <div class="col-3 col-lg-1 pop_image_div m" style="">
                <img src="{{asset('storage/banner')}}/{{$banner['photo']}}" alt="place-holder" class="pop_image">
            </div>
            <div class="col-5 col-lg-4 pop_text" style="">
                <h5 class="text-muted d-lg-inline-block s_10 f_pop_txt">{{$banner->main_title}}</h5>
                <h5 class="text-muted d-lg-inline-block s_10 s_pop_txt">{{$banner->title}}</h5>
            </div>
            @php($ios = \App\CPU\Helpers::get_business_settings('download_app_apple_stroe'))
            @php($android = \App\CPU\Helpers::get_business_settings('download_app_google_stroe'))
            <div class="col-3 col-lg-3 pop_download" style="">
                <div class="row">
                    @if($ios['status'])

                        <div class="col-md-5 col-12 text-center" id="ios_app">
                            <a class="download_btn" href="{{ $ios['link'] }}" role="button">
                                {{\App\CPU\translate('Download')}}
                            </a>
                        </div>
                    @endif
                    @if($android['status'])
                        <div class="col-md-5 col-12 text-center" id="android_app">
                            <a href="{{ $android['link'] }}" role="button" class="download_btn">
                                {{\App\CPU\translate('Download')}}
                            </a>
                        </div>
                    @endif
                </div>
                {{--   <a href="{{$banner->url}}" class="btn btn-primary" style="">تنزيل</a>--}}
            </div>
            <div class="close_pop_up" style="">
                <i class="bold s_14 fa-solid fa-xmark" style="color: #aca8a8;"></i>
            </div>
        </div>
    @endif

    <div class="new_topbar text-center" dir="{{session('direction')}}" style="background: #fcfcfd">
        {{-- <div class="container"><div><div class="topbar-text dropdown d-md-none {{Session::get('direction') === "rtl" ? 'mr-auto' : 'ml-auto'}}"><a class="topbar-link" href="tel: {{$web_config['phone']->value}}"><i class="fa fa-phone"></i> {{$web_config['phone']->value}}</a></div><div class="d-none d-md-block {{Session::get('direction') === "rtl" ? 'mr-2' : 'mr-2'}} text-nowrap"><a class="topbar-link d-none d-md-inline-block" href="tel:{{$web_config['phone']->value}}"><i class="fa fa-phone"></i> {{$web_config['phone']->value}}</a></div></div> --}}
        <div class="row">
            @if(auth('customer')->check())
                <div class="col-xxl-3 col-xl-3 col-md-3 col-sm-6 col-6 pt-3 pr-2 position-relative text-right deliverly_to">
                    <div class="dropdown">
                        <a class="navbar-tool ml-lg-3" type="button" data-toggle="dropdown" aria-haspopup="true"aria-expanded="false">
                            <div class="navbar-tool-icon-box bg-secondary">
                                <div class="navbar-tool-icon-box bg-secondary">
                                    <img src="{{asset('storage/profile/' . auth('customer')->user()->image)}}" onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'" class="img-profile rounded-circle __inline-14">
                                </div>
                            </div>
                            <div class="navbar-tool-text mr-3 pt-1" style="display: inline-block">
                                <span>{{\App\CPU\translate('Welcome')}}, <span class="bold">{{auth('customer')->user()->f_name}}</span></span>
                            </div>
                        </a>
                        <div class="dropdown-menu text-{{session('direction') == 'rtl' ? 'right' : 'left'}}" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{route('account-oder')}}"> {{ \App\CPU\translate('my_order')}} </a>
                            <a class="dropdown-item" href="{{route('user-account')}}"> {{ \App\CPU\translate('my_profile')}}</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{route('customer.auth.logout')}}">{{ \App\CPU\translate('logout')}}</a>
                        </div>
                    </div>
                    <div class="list-ship position-relative pc_hide mob_inline" data-toggle="modal" data-target="#location_modal" style="padding: 5px 0;">
                        <i class="fa-solid fa-location-dot px-1 primary_color heartbeat" style="margin-top:-15px; font-size:22px;"></i>
                        {{-- <img class="px-1 mr-2" src="{{asset('assets/front-end/img/flag.png')}}" alt="flag" style="width: 33px;border-radius: 6px; margin-top: 5px;  margin-left: 0!important;margin-right: 1px!important;"> --}}
                        <div class="d-inline-block">
                            {{-- <span class="px-1">توصيل إلي</span>--}}
                            <span class="light_pink px-1 bold" id="header_loc_mob">
                                @if(auth('customer')->check())
                                    {{auth('customer')->user()->city != null ? auth('customer')->user()->city : 'الرياض'}}
                                @else
                                    @if(session::has('current_city'))
                                        {{Session::get('current_city')}}
                                    @else
                                        الرياض
                                    @endif
                                @endif
                            </span>
                        </div>
                        {{-- <i class="fa-solid fa-chevron-down  px-1 mt-1"style="position: absolute;top: 5px;left:-23px;color: #979797"></i>--}}
                    </div>
                    <div class="d-inline-block pc_hide mob_inline" style="padding-right: 11px;">
                        @foreach(json_decode($lang['value'],true) as $data)
                            @if($data['code']!=$local)
                                <a href="{{route('lang', $data['code'])}}"><span class="bold" style="font-size:12px;margin-right: 0px;cursor: pointer">{{\App\CPU\translate($data['name'])}}</span> </a>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="col-sm-6 col-6 pt-2 text-left pc_hide">
                    <div class="navbar-toolbar shorthand_icons  text-center pc_hide" dir="rtl" style="margin-top: 1rem">
                        @php($currency_model = \App\CPU\Helpers::get_business_settings('currency_model'))
                        @if($currency_model=='multi_currency')
                            <div class="topbar-text dropdown disable-autohide d-inline-block position-relative">
                                <a class="topbar-link dropdown-toggle" href="#" data-toggle="dropdown">
                                    <span> {{session('currency_symbol')}} <i class="fa-solid fa-globe" style="font-size: 21px"></i></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}"
                                    style="min-width: 160px!important;text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                    @foreach (\App\Model\Currency::where('status', 1)->get() as $key => $currency)
                                        <li class="dropdown-item cursor-pointer" onclick="currency_change('{{$currency['code']}}')">
                                            {{ $currency->name }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div id="cart_items_mobile" class="d-inline-block mr-lg-2 position-relative">
                            @include('layouts.front-end.partials.cart')
                        </div>
                        <div class="navbar-tool d-inline-block mr-lg-1 position-relative dropdown">
                            <a class="navbar-tool-icon-box bg-secondary dropdown-toggle" href="{{route('wishlists')}}">
                            <span class="navbar-tool-label">
                                @if(auth('customer')->check())
                                    <span class="countWishlist">{{\App\Model\Wishlist::where('customer_id', auth('customer')->id())->count()}}</span>
                                @else
                                    <span class="countWishlist">{{session()->has('wish_list')?count(session('wish_list')):0}}</span>
                                @endif
                            </span>
                                <i class="navbar-tool-icon czi-heart"></i>
                            </a>
                        </div>
                        @php($cs_phone=\App\Model\BusinessSetting::where('type','customer_service')->first())
                        <div class="navbar-tool d-inline-block mr-lg-1 position-relative dropdown">
                            <a class="navbar-tool-icon-box bg-secondary dropdown-toggle" href="tel:{{$cs_phone->value}}">
                                <i class="fa-solid fa-headset px-1 s_19"></i>
                                {{--<span class="s_12 bold mobile_hide">خدمة العملاء</span>--}}
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-xxl-3 col-xl-3 col-md-3 col-sm-6 col-6 pt-4 position-relative {{session('direction') == 'rtl' ? 'text-right pr-3 ' : 'text-left  pl-4 '}} deliverly_to">
                    <span type="button" data-toggle="modal" data-target="#register_modal" class="mobile_hide reg_aa" style="cursor: pointer">
                        <i class="fa-regular fa-user px-1 s_19 primary_color"></i>
                        <span class="s_18 bold"><span class="mobile_hide">{{\App\CPU\translate('You are a Guest / ')}}</span> {{\App\CPU\translate('Sign in')}}</span>
                    </span>
                    <div class="list-ship position-relative pc_hide mob_inline" data-toggle="modal" data-target="#location_modal" style="padding: 5px 0;">
                        <i class="fa-solid fa-location-dot px-1 primary_color heartbeat" style="margin-top:-15px; font-size:22px;"></i>
                        {{-- <img class="px-1 mr-2" src="{{asset('assets/front-end/img/flag.png')}}" alt="flag" style="width: 33px;border-radius: 6px; margin-top: 5px;  margin-left: 0!important;margin-right: 1px!important;"> --}}
                        <div class="d-inline-block">
                            {{-- <span class="px-1">توصيل إلي</span>--}}
                            <span class="light_pink px-1 bold" id="header_loc_mob">
                                @if(auth('customer')->check())
                                    {{auth('customer')->user()->city != null ? auth('customer')->user()->city : 'الرياض'}}
                                @else
                                    @if(session::has('current_city'))
                                        {{Session::get('current_city')}}
                                    @else
                                        الرياض
                                    @endif
                                @endif
                            </span>
                        </div>
                        {{--  <i class="fa-solid fa-chevron-down  px-1 mt-1" style="position: absolute;top: 5px;left:-23px;color: #979797"></i>--}}
                    </div>
                    <div class="d-inline-block pc_hide mob_inline" style="    padding-right: 11px;">
                        @foreach(json_decode($lang['value'],true) as $data)
                            @if($data['code']!=$local)
                                <a href="{{route('lang', $data['code'])}}"><span class="bold" style="font-size:12px;margin-right: 9px;cursor: pointer">{{\App\CPU\translate($data['name'])}}</span></a>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="col-sm-6 col-6 pt-2 text-left pc_hide">
                    <div class="navbar-toolbar shorthand_icons  text-center pc_hide" dir="rtl" style="margin-top:9px">
                        <div class="d-inline-block position-relative" style="margin-right: 2px!important;">
                        <span type="button" data-toggle="modal" data-target="#register_modal" style="cursor: pointer">
                            <i class="fa-regular fa-user s_19 primary_color"></i>
                            <span class="s_12 bold"><span class="mobile_hide">{{\App\CPU\translate('You are a Guest / ')}}</span> {{\App\CPU\translate('Sign in')}}</span>
                        </span>
                        </div>
                        <div id="cart_items_mobile" class="d-inline-block mr-lg-2 position-relative">
                            @include('layouts.front-end.partials.cart')
                        </div>
                        <div class="navbar-tool d-inline-block mr-lg-1 position-relative dropdown">
                            <a class="navbar-tool-icon-box bg-secondary dropdown-toggle" href="{{route('wishlists')}}">
                            <span class="navbar-tool-label">
                                @if(auth('customer')->check())
                                    <span class="countWishlist">{{\App\Model\Wishlist::where('customer_id', auth('customer')->id())->count()}}</span>
                                @else
                                    <span class="countWishlist">{{session()->has('wish_list')?count(session('wish_list')):0}}</span>
                                @endif
                            </span>
                                <i class="navbar-tool-icon czi-heart"></i>
                            </a>
                        </div>
                        @php($cs_phone=\App\Model\BusinessSetting::where('type','customer_service')->first())
                        <div class="navbar-tool d-inline-block mr-lg-1 position-relative dropdown">
                            <a class="navbar-tool-icon-box bg-secondary dropdown-toggle" href="tel:{{$cs_phone->value}}">
                                <i class="fa-solid fa-headset px-1 s_19"></i>
                                {{-- <span class="s_12 bold mobile_hide">خدمة العملاء</span>--}}
                            </a>
                        </div>
                    </div>
                </div>
            @endif
            @php($whatsapp = \App\CPU\Helpers::get_business_settings('whatsapp'))
            <div class=" col-sm-6 col-6 pt-2 text-left social_icons_mobile d-none" style="padding-left: 23px; display: none">
                <a href="https://web.whatsapp.com/send/?phone={{ $whatsapp['phone'] }}?text=Hello%20there!" class="px-1">
                    <img src="{{asset('assets/front-end/img/whatsapp.png')}}" alt="icon" style="width: 27px">
                </a>
                <a href="{{App\Model\SocialMedia::where('name', 'twitter')->first()->link}}" class="px-1">
                    <i class="fa-brands fa-twitter"></i>
                </a>
                <a href="{{App\Model\SocialMedia::where('name', 'instagram')->first()->link}}" class="px-1">
                    <i class="fa-brands fa-instagram"></i>
                </a>
                <a href="{{App\Model\SocialMedia::where('name', 'facebook')->first()->link}}" class="px-1">
                    <i class="fa-brands fa-facebook"></i>
                </a>
            </div>
            <div class="col-xxl-6 col-xl-6 col-md-6 col-sm-12 col-12 bold pt-2 mobile_hide">
                <a class="navbar-brand d-none d-sm-block {{Session::get('direction') === "rtl" ? 'mr-3' : 'mr-3'}} flex-shrink-0 __min-w-7rem" href="{{route('home')}}">
                    <img class="__inline-11" src="{{asset("storage/company")."/".$web_config['web_logo']->value}}" style="height: 50px!important;"
                        onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'" alt="{{$web_config['name']->value}}"/>
                </a>
            </div>
            <div
                class="col-xxl-3 col-xl-3 col-md-3 col-sm-12 col-12 {{session('direction') == 'rtl' ? 'text-left' : 'text-right'}} social_icons mobile_hide"
                style=" {{session('direction') == 'rtl' ? 'padding-left: 23px;' : 'padding-right: 23px;'}} padding-top: 20px">
                <a href="https://web.whatsapp.com/send/?phone={{ $whatsapp['phone'] }}?text=Hello%20there!" target="_blank" class="px-1">
                    <img src="{{asset('assets/front-end/img/whatsapp.png')}}" alt="icon" style="width: 27px">
                </a>
                <a href="{{App\Model\SocialMedia::where('name', 'facebook')->first()->link}}" class="px-1">
                    <img src="{{asset('assets/front-end/img/fb.png')}}" alt="icon" style="width: 23px">
                </a>
                <a href="{{App\Model\SocialMedia::where('name', 'linkedin')->first()->link}}" class="px-1">
                    <img src="{{asset('assets/front-end/img/linkedin.png')}}" alt="icon" style="width: 23px">
                </a>
                <a href="{{App\Model\SocialMedia::where('name', 'instagram')->first()->link}}" class="px-1">
                    <img src="{{asset('assets/front-end/img/instagram.png')}}" alt="icon" style="width: 23px">
                </a>
                <a href="{{App\Model\SocialMedia::where('name', 'twitter')->first()->link}}" class="px-1">
                    <img src="{{asset('assets/front-end/img/twitter.png')}}" alt="icon" style="width: 23px">
                </a>
            </div>
        </div>
    </div>
    <div class="navbar-sticky bg-light mobile-head">
        <div class="navbar navbar-expand-md navbar-light">
            <div class="container nav_2 row m-auto" dir="{{session('direction')}}">
                <button class="navbar-toggler" type="button" id="btn_expand_mobile_list">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="col-lg-4 col-md-4 mobile_hide pr-0">
                    {{--  data-toggle="modal" data-target="#location_modal"--}}
                    <div class="list-ship position-relative " data-toggle="modal" data-target="#location_modal" style="width: auto;padding: 5px 0 0 5px;">
                        <i class="fa-solid fa-location-dot pl-1 pr-0 primary_color heartbeat" style="margin-top:-15px; font-size:22px;"></i>
                        {{-- <img class="px-1 mr-2" src="{{asset('assets/front-end/img/flag.png')}}" alt="flag" style="width: 33px;border-radius: 6px; margin-top: 2px;  margin-left: 0!important;margin-right: 1px!important;"> --}}
                        <div class="d-inline-block">
                            {{-- <span class="px-1">توصيل إلي</span>--}}
                            <span class="light_pink px-1 bold" id="header_loc_pc">
                                @if(auth('customer')->check())
                                    {{auth('customer')->user()->city != null ? auth('customer')->user()->city : 'الرياض'}}
                                @else
                                    @if(session::has('current_city'))
                                        {{Session::get('current_city')}}
                                    @else
                                        الرياض
                                    @endif
                                @endif
                            </span>
                        </div>
                        {{-- <i class="fa-solid fa-chevron-down  px-1 mt-1" style="position: absolute;top: 5px;left:16px;color: #979797"></i>--}}
                        <div class="shipping-list-items" style="padding: 15px">
                            <select name="city" id="city_loc" style="width: 100%">
                                <option value="1">الرياض</option>
                                <option value="2">الكويت</option>
                                <option value="3">قطر</option>
                                <option value="4">عمان</option>
                                <option value="5">مصر</option>
                                <option value="6">الإمارات</option>
                            </select>
                            <div class="input-group mt-2" dir="ltr">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1" style="padding: 4px" data-toggle="modal" data-target="#location_modal">
                                        <img src="{{asset('assets/front-end/img/send.png')}}" alt="send" style="width: 21px">
                                    </span>
                                </div>
                                <input type="text" class="form-control" placeholder="اسم المنطقة" style="padding: 6px 7px 0 7px!important;height: 33px;text-align: right;">
                            </div>
                        </div>
                    </div>
                    <div class="d-inline-block">
                        @foreach(json_decode($lang['value'],true) as $data)
                            @if($data['code']!=$local)
                                <a href="{{route('lang', $data['code'])}}"><span class="s_19 bold " style="margin-right: 0px;cursor: pointer">{{\App\CPU\translate($data['name'])}}</span> <i class="fa-solid fa-globe" style="font-size: 16px"></i></a>
                            @endif
                        @endforeach
                    </div>
                </div>
                <a class="navbar-brand mobile_logo d-sm-none {{Session::get('direction') === "rtl" ? 'mr-2' : 'mr-2'}}" href="{{route('home')}}">
                    <img class="mobile-logo-img __inline-12" src="{{asset("storage/company")."/".$web_config['web_logo']->value}}"
                        onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'" alt="{{$web_config['name']->value}}"/>
                </a>
                <!-- Search-->
                <div class="input-group-overlay d-none d-md-inline-block col-lg-4 col-md-4 mx-4" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}}">
                    <form action="{{route('products')}}" type="submit" class="search_form">
                        <input class="form-control appended-form-control search-bar-input" type="text" autocomplete="off" dir="rtl" placeholder="{{\App\CPU\translate('Search in Store')}} ..." name="name">
                        <button class="input-group-append-overlay search_button secondary_bg" type="submit" style="border-radius: 5px 0 0 5px!important; right: unset; left: -6px;top:0;">
                                <span class="input-group-text __text-20px">
                                    <i class="czi-search text-white"></i>
                                </span>
                        </button>
                        <input name="data_from" value="search" hidden>
                        <input name="page" value="1" hidden>
                        <diV class="card search-card __inline-13">
                            <div class="card-body search-result-box __h-400px overflow-x-hidden overflow-y-auto"></div>
                        </diV>
                    </form>
                </div>
                <!-- Toolbar-->
                <div class="navbar-toolbar shorthand_icons col-lg-4 col-md-4 pt-4 pb-2 mobile_hide" dir="{{session('direction')}}" style="{{session('direction') == 'rtl' ? 'text-align: left;margin-right: -44px;' : 'text-align: right;margin-left: -44px;'}}">
                    @php($currency_model = \App\CPU\Helpers::get_business_settings('currency_model'))
                    @if($currency_model=='multi_currency')
                        <div class="topbar-text dropdown disable-autohide d-inline-block position-relative">
                            <a class="topbar-link dropdown-toggle" href="#" data-toggle="dropdown">
                                <span> {{session('currency_symbol')}} </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}" style="min-width: 160px!important;text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                @foreach (\App\Model\Currency::where('status', 1)->get() as $key => $currency)
                                    <li class="dropdown-item cursor-pointer"
                                        onclick="currency_change('{{$currency['code']}}')">
                                        {{ $currency->name }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div id="cart_items" class="d-inline-block position-relative">
                        @include('layouts.front-end.partials.cart')
                    </div>
                    <div class="navbar-tool d-inline-block mr-1 position-relative dropdown">
                        <a class="navbar-tool-icon-box bg-secondary dropdown-toggle" href="{{route('wishlists')}}">
                            <span class="navbar-tool-label">
                                @if(auth('customer')->check())
                                    <span class="countWishlist">{{\App\Model\Wishlist::where('customer_id', auth('customer')->id())->count()}}</span>
                                @else
                                    <span class="countWishlist">{{session()->has('wish_list')?count(session('wish_list')):0}}</span>
                                @endif
                            </span>
                            <i class="navbar-tool-icon czi-heart"></i>
                        </a>
                    </div>
                    {{-- <div class="d-inline-block mr-1 position-relative"> <i class="fa-solid fa-globe px-1 s_19"></i> </div>--}}
                    @php($cs_phone=\App\Model\BusinessSetting::where('type','customer_service')->first())
                    <div class="navbar-tool d-inline-block mr-1 position-relative dropdown">
                        <a class="navbar-tool-icon-box bg-secondary dropdown-toggle" href="tel:{{$cs_phone->value}}">
                            <i class="fa-solid fa-headset px-1 " style="font-size: 20px!important;"></i>
                            {{--<span class="s_12 bold mobile_hide">خدمة العملاء</span>--}}
                        </a>
                    </div>
                </div>
            </div>
            @include('layouts.front-end.partials._login_modal')
        </div>
        <div class="row cats_pc" style="background: #f14061; background: #f44972;">
            <div class="m-auto col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="row pb-0 categories_container position-relative" dir="rtl">
                    @if(session('direction') == "rtl")
                        <div class="col-lg-2 col-md-2 col-sm-2 col-2 {{session('direction') == 'rtl' ? 'text-right' : 'text-left'}}  all_cats_div" style="padding: 7px">
                            <a class="all_cats_btn s_16 bold " href="#" style="{{session('direction') == 'rtl' ? '' : 'padding: 7px 0 7px 18px;'}}">
                                {{\App\CPU\translate('All Categories_')}}
                                <i class="fa fa-caret-down position-absolute" style="top: 16px; {{session('direction') == 'rtl' ? 'left:50px;' : 'right:50px;'}} color: #fff" aria-hidden="true"></i>
                            </a>
                        </div>
                    @endif
                    <div class="col-lg-10 col-md-10 col-sm-10 col-10">
                        <div class="categories_list pr-0 py-1 my-0 text-right" dir="ltr">
                            <div class="owl-carousel owl-theme " id="categories_list" style="padding-left: 2.5rem!important; padding-right: 2.5rem!important;">
                                <div style="" class="category-item_1">
                                    <a href="{{route('home')}}/products?data_from=best-selling&page=1"><span class="boldy">{{\App\CPU\translate('top_sell_pro')}}</span></a>
                                </div>
                                <div style="" class="category-item_1">
                                    <a href="{{route('home')}}/products?data_from=top-rated&page=1"><span class="boldy">{{\App\CPU\translate('top_rate_pro')}}</span></a>
                                </div>
                                <div style="" class="category-item_1">
                                    <a href="{{route('home')}}/flash-deals/1"><span class="boldy">{{\App\CPU\translate('daily_offers')}}</span></a>
                                </div>
                                <div style="" class="category-item_1">
                                    <a href="{{route('home')}}/products?data_from=latest&page=1"><span class="boldy">{{\App\CPU\translate('recent_pro')}}</span></a>
                                </div>
                                <div style="" class="category-item_1">
                                    <a href="{{route('products',['data_from'=>'featured_deal','page'=>1])}}"><span class="boldy">{{\App\CPU\translate('special_offers')}}</span></a>
                                </div>
                                @php($cats = \App\Model\Category::where('position', 0)->where('home_status', true)->orderBy('priority')->get())
                                @foreach($cats as $cat)
                                    <div style="" class="category-item" data-value="menu_cat_{{$cat->id}}" data-id="{{$cat->id}}">
                                        <a href="{{route('home')}}/products?id={{$cat->id}}&data_from=category&page=1"><span class="boldy">{{$cat->name}}</span></a>
                                    </div>
                                @endforeach 
                            </div>
                        </div>
                    </div>
                    @if(session('direction') == "ltr")
                        <div class="col-xxl-2 col-xl-2 col-md-2 col-sm-2 col-2 {{session('direction') == 'rtl' ? 'text-right' : 'text-left'}}  all_cats_div" style="padding: 7px">
                            <a class="all_cats_btn s_16 bold " href="#" style="{{session('direction') == 'rtl' ? '' : 'padding: 7px 0 7px 18px;'}}">
                                {{\App\CPU\translate('All Categories_')}}
                                <i class="fa fa-caret-down position-absolute" style="top: 16px; {{session('direction') == 'rtl' ? 'left:20px;' : 'right:20px;'}} color: #f14061" aria-hidden="true"></i>
                            </a>
                        </div>
                    @endif
                    @foreach($cats as $cat)
                        <div class="sub-categories-list col-lg-12 col-md-12 col-sm-12 col-12" id="menu_cat_{{$cat->id}}">
                            <div class="row ">
                                <div class="col-xxl-3 col-xl-3 col-md-3 col-sm-3 col-3" style="padding: 22px;">
                                    <img src="{{asset('storage/category/'. $cat->icon)}}" alt="" style="width:300px; height:300px;border-radius: 5px;box-shadow: 3px 2px 2px 1px #eadcdc33;">
                                </div>
                                <div class="col-xxl-2 col-xl-2 col-md-2 col-sm-2 col-2" style="padding: 22px 17px 0 0;">
                                    <div class="row">
                                        <div class="{{$cat->childes->count() > 0 ? 'col-xxl-5 col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12' : 'col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 text-center'}}" style="padding: 74px 0px;border-left: 1px solid #ddd;">
                                            <a href="{{route('home')}}/products?id={{$cat->id}}&data_from=category&page=1" class="bold s_16 d-block" style="padding: 13px 0;">{{\App\CPU\translate('View All')}}</a>
                                            <a href="{{route('home')}}/products?id={{$cat->id}}&data_from=top-rated&page=1" class="bold s_16 d-block"style="padding: 13px 0;">{{\App\CPU\translate('Top Rated')}}</a>
                                        </div>
                                        @if($cat->childes->count() > 0)
                                            <div class="col-xxl-7 col-xl-7 col-lg-7 col-md-7 col-sm-7 col-12 s_14" style="padding-right: 14px;border-left: 1px solid #ddd;">
                                                <h3 class="bold s_14">{{\App\CPU\translate('Sub Categories')}}</h3>
                                                <ul style="list-style: none;padding-right: 11px;">
                                                    @foreach($cat->childes as $single_child)
                                                        <li style="padding: 4px 0;"><a href="{{route('home')}}/products?id={{$single_child->id}}&data_from=category&page=1">{{$single_child->name}}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @php($porduct_data = App\Model\Product::active())
                                @php($products = $porduct_data->get())
                                @php($product_ids = [])
                                @foreach ($products as $product)
                                    @foreach (json_decode($product['category_ids'], true) as $category)
                                        @if ($category['id'] == $cat->id)
                                            @php(array_push($product_ids, $product['id']))
                                        @endif
                                    @endforeach
                                @endforeach
                                @php($products = $porduct_data->whereIn('id', $product_ids)->get())
                                @php($colors = App\Model\Color::pluck('code')->toArray())
                                @php($selected_colors = [])
                                @foreach ($products as $product)
                                    @if (count(json_decode($product->colors)) > 0)
                                        @foreach (json_decode($product->colors) as $color)

                                            @if (in_array($color, $colors))
                                                @php(array_push($selected_colors, $color))
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                                @php($colors = App\Model\Color::whereIn('code', $selected_colors)->get())

                                @php($brands_ids = $porduct_data->whereIn('id', $product_ids)->pluck('brand_id')->toArray())
                                @php($brands = App\Model\Brand::whereIn('id', $brands_ids)->limit(6)->get())
                                @if($colors->count() > 0)
                                    <div class="col-xxl-1 col-xl-1 col-md-1 col-sm-1 col-1 text-center" style="padding: 22px 17px 0 0;">
                                        <div class="row">
                                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 s_14" style="padding-left: 20px;border-left: 1px solid #ddd;">
                                                <h3 class="bold s_14">{{\App\CPU\translate('Shop With Color')}}</h3>
                                                <div>
                                                    @foreach($colors as $color)
                                                        <a href="{{route('products',['id' => $cat->id,'data_from'=> 'category', 'color' => str_replace('#', '', $color->code), 'page'=>1])}}">
                                                            <div class="color-item-nav" style="background: {{$color->code}}"></div>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-xxl-3 col-xl-3 col-md-3 col-sm-3 col-3" style="padding: 22px 17px 0 0;">
                                    <div class="row">
                                        <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 text-center">
                                            <h3 class="bold s_18">{{\App\CPU\translate('Most Popular Brands')}}</h3>
                                        </div>
                                        {{--   @php($most_pop = \App\Model\Product::)--}}
                                        @foreach ($brands as $brand)
                                            <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4 col-4 text-center py-3">
                                                <a href="#">
                                                    <img src="{{asset('storage/brand/' . $brand->image)}}" class="most_pop_prod d-block" alt="" style="border-radius: 0;padding: 7px;width: 100%;height: 93px">
                                                    <span class="s_14 bold d-block">{{$brand->name}}</span>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-xl-3 col-md-3 col-sm-3 col-3" style="padding: 22px 17px 0 0;">
                                    <div class="row">
                                        <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12 text-center">
                                            <h3 class="bold s_18">{{\App\CPU\translate('Most Populer')}}</h3>
                                        </div>
                                        {{-- @php($most_pop = \App\Model\Product::)--}}

                                        @php($porduct_data = App\Model\Product::active())
                                        @php($products = $porduct_data->get())
                                        @php($product_ids = [])

                                        @foreach ($products as $product)
                                            @foreach (json_decode($product['category_ids'], true) as $category)
                                                @if ($category['id'] == $cat->id)
                                                    @php(array_push($product_ids, $product['id']))
                                                @endif
                                            @endforeach
                                        @endforeach
                                        @php($most_pop = $porduct_data->whereIn('id', $product_ids)->limit(4)->get())
                                        @foreach($most_pop as $single_pop)
                                            <div
                                                class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6 text-center py-3">
                                                <a href="{{route('product', $single_pop->slug)}}">
                                                    <img
                                                        src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$single_pop['thumbnail']}}"
                                                        class="most_pop_prod d-block"
                                                        alt=""
                                                        style="border-radius: 50%;padding: 19px;width: 150px;height: 150px">
                                                    <span class="s_14 bold d-block">{{$single_pop->name}}</span>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach


                    <div class="all_cats_list col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 colxs-6 col-6" dir="{{session('direction')}}" style="{{session('direction') == 'ltr' ? 'left:6px;' : ''}}">
                        <div class="row all_cats_row">
                            <div class="all_main_cats col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4 col-4" style="border: 1px solid #ddd;">
                                <ul class="all_main_cats_list">
                                    @php($special_main_cats=\App\CPU\CategoryManager::parents())
                                    @foreach($special_main_cats as $cat)
                                        <li class="single-main-item" data-target="{{$cat->id}}">
                                            <a href="{{route('home')}}/products?id={{$cat->id}}&data_from=category&page=1" class="s_18">{{$cat->name}}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            @foreach($special_main_cats as $key_sp => $cat)
                                <div class="all_main_cats sub_cats_menu col-xxl-8 col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8 col-8 {{$key_sp= '0' ? 'show' : ''}}" id="sub_items_from_main_{{$cat->id}}">
                                    <div class="row" style="padding: 10px">
                                        <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12" style="border-bottom: 1px solid #ddd">
                                            <h3 class="bold s_18">{{$cat->name}}</h3>
                                        </div>
                                        <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
                                            <h3 class="bold s_18 pt-3">المشهورة أكتر</h3>
                                            <ul style="list-style: none; padding: 0px 13px;border-left: 1px solid #ddd;">
                                                @if($cat->childes->count() > 0)
                                                    @foreach($cat->childes as $single_sub)
                                                        <li class="" data-target="{{$single_sub->id}}">
                                                            <a href="{{route('home')}}/products?id={{$single_sub->id}}&data_from=category&page=1" class="s_14">{{$single_sub->name}}</a>
                                                        </li>
                                                        @foreach($single_sub->childes as $sub_sub_single)
                                                            <li class="" data-target="{{$sub_sub_single->id}}">
                                                                <a href="{{route('home')}}/products?id={{$sub_sub_single->id}}&data_from=category&page=1" class="s_14">{{$sub_sub_single->name}}</a>
                                                            </li>
                                                        @endforeach
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                        @php($porduct_data = App\Model\Product::active())
                                        @php($products = $porduct_data->get())
                                        @php($product_ids = [])

                                        @foreach ($products as $product)
                                            @foreach (json_decode($product['category_ids'], true) as $category)
                                                @if ($category['id'] == $cat->id)
                                                    @php(array_push($product_ids, $product['id']))
                                                @endif
                                            @endforeach
                                        @endforeach

                                        @php($brands_in_category = $porduct_data->whereIn('id', $product_ids)->pluck('brand_id')->toArray())
                                        @php($brands = App\Model\Brand::get())
                                        @php($selected_brands = [])
                                        @foreach($brands as $brand)
                                            @if(in_array($brand->id, $brands_in_category))
                                                @php(array_push($selected_brands, $brand->id))
                                            @endif
                                        @endforeach

                                        @if($selected_brands)
                                            <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6 col-6">
                                                <h3 class="bold s_18 pt-3">أفضل الماركات</h3>
                                                <ul style="list-style: none; padding: 0 13px;">
                                                    @foreach($selected_brands as $brand)
                                                        @php($brand = App\Model\Brand::find($brand))
                                                        <li class="">
                                                            <a href="{{route('home')}}/products?id={{$brand->id}}&data_from=brand&page=1" class="s_14">{{$brand->name}}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="mobile_nav_list" style="display: none">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-12">
                <form action="{{route('products')}}" type="submit" class="search_form" dir="ltr">
                    <input class="form-control appended-form-control search-bar-input" type="text" autocomplete="off" dir="rtl" placeholder="ابحث ..." name="name">
                    <button class="input-group-append-overlay search_button" type="submit" style="border-radius: {{Session::get('direction') === "rtl" ? '7px 0px 0px 7px; right: unset; left: 0' : '0px 0 7px 7px; right: unset; left: 0'}};top:0">
                            <span class="input-group-text __text-20px">
                                <i class="czi-search text-white"></i>
                            </span>
                    </button>
                    <input name="data_from" value="search" hidden>
                    <input name="page" value="1" hidden>
                    <diV class="card search-card __inline-13">
                        <div class="card-body search-result-box __h-400px overflow-x-hidden overflow-y-auto"></div>
                    </diV>
                </form>
            </div>
            <div class="col-md-12 col-sm-12 col-12">
                <ul class="navbar-nav mega-nav1 pr-2 pl-2 d-block d-xl-none text-right" dir="rtl"><!--mobile-->
                    <li class="nav-item dropdown">
                        {{--<a class="nav-link dropdown-toggle expanding_btn" href="javascript:void(0);"> <i class="czi-menu align-middle mt-n1 {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}"></i> <span class="bold" style="margin-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 20px !important;">جميع الفئات</span> </a>--}}
                        <ul class="dropdown-menu __dropdown-menu-2" style="text-align: right;width: 100%;display: block">
                            @php($categories=\App\CPU\CategoryManager::parents())
                            @foreach($categories as $category)
                                <li class="dropdown">
                                    <a href="{{route('products',['id'=> $category->id,'data_from'=>'category','page'=>1])}}">
                                        <img src="{{asset("assets/front-end/img/sub-1.png")}}" onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'" class="__img-18" style="margin-left: 11px;">
                                        <span
                                            class="bold {{Session::get('direction') === "rtl" ? 'pr-3' : 'pl-3'}}">{{$category->name}}</span>
                                    </a>
                                    <a class='__ml-50px expanding_btn_sub'>
                                        <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'right' : 'left'}} __inline-16 "></i>
                                    </a>
                                    <ul class="dropdown-menu sub_dropdown-menu"
                                        style="text-align: {{Session::get('direction') === "rtl" ? 'left' : 'right'}};">
                                        @foreach($category->childes as $child)
                                            <li class="dropdown">
                                                <a href="{{route('products',['id'=> $child->id,'data_from'=>'category','page'=>1])}}">
                                                    <span class="bold {{Session::get('direction') === "rtl" ? 'pl-3' : 'pr-3'}}">{{$child->name}}</span>
                                                </a>
                                                <a style="font-family:  sans-serif !important;font-size: 1rem;font-weight: 300;line-height: 1.5;margin-left:50px;" class="expanding_btn_sub_sub">
                                                    <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'right' : 'left'}} __inline-16"></i>
                                                </a>
                                                <ul class="dropdown-menu sub_sub_dropdown-menu">
                                                    @foreach($child->childes as $ch)
                                                        <li>
                                                            <a class="dropdown-item bold" href="{{route('products',['id'=> $ch->id,'data_from'=>'category','page'=>1])}}">
                                                                {{$ch->name}}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
@push('script')
    {{--    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>--}}
    <script>
        function myFunction() {
            $('#anouncement').slideUp(300)
        }
        $(document).ready(function () {
            let close_pop_up = $('.close_pop_up');
            let pop_up_banner = $('.pop_up_banner');
            close_pop_up.on('click', function () {
                pop_up_banner.slideUp();
            });
            let list_ship = $('.list-ship');
            // list_ship.hover(function () {
            //     let select_list = $(this).find('.shipping-list-items');
            //     select_list.show();
            // }, function () {
            //     let select_list = $(this).find('.shipping-list-items');
            //     select_list.hide();
            // });

            // let select2_city_loc_results = $('input[aria-controls="select2-city_loc-results"]');
            // select2_city_loc_results.hover(function () {
            //     $('.shipping-list-items').show()
            // });

            let city_loc = $("#city_loc");
            city_loc.select2();
            let category_item = $('.category-item');
            category_item.hover(function () {
                $(this).parent().css('background', '#fe7a85');
            }, function () {
                $(this).parent().css('background', 'transparent');
            });
            let category_item_1 = $('.category-item_1');
            category_item_1.hover(function () {
                $(this).parent().css('background', '#F66D8E');
            }, function () {
                $(this).parent().css('background', 'transparent');
            });
            let all_cats_list = $('.all_cats_list');
            let all_cats_btn = $('.all_cats_btn');
            all_cats_btn.hover(function () {
                all_cats_list.css('display', 'block');
            }, function () {
                all_cats_list.css('display', 'none');
            });
            all_cats_list.hover(function () {
                all_cats_list.css('display', 'block');
            }, function () {
                all_cats_list.css('display', 'none');

            });
            $('.sub_cats_menu ').first().addClass('show');
            let single_main_item = $('.single-main-item');
            let sub_cats_menu = $('.sub_cats_menu');
            single_main_item.hover(function () {
                let id = $(this).attr('data-target');
                sub_cats_menu.removeClass('show');
                let single_main_item_id = $('#sub_items_from_main_' + id);
                single_main_item_id.addClass('show')
            });
            $('.category-item').hover(function () {
                let id_target = $(this).attr('data-value');
                $('#' + id_target).css('display', 'block');
                $('.overlay').addClass('active');
            }, function () {
                let id_target = $(this).attr('data-value');
                $('#' + id_target).css('display', 'none');
                $('.overlay').removeClass('active');
            });
            // $('.all_cats_btn').parent().hover(function () {
            //     $('.sub-categories-list').css('display', 'block');
            //     $('.overlay').addClass('active');
            // }, function () {
            //     $('.sub-categories-list').css('display', 'none');
            //     $('.overlay').removeClass('active');
            // });
            $('.sub-categories-list').hover(function () {
                $(this).css('display', 'block');
                $('.overlay').addClass('active');
            }, function () {
                $(this).css('display', 'none');
                $('.overlay').removeClass('active');
            });
            $('#categories_list').owlCarousel({
                loop: false,
                center: false,
                autoplay: false,
                margin: 10,
                nav: true,
                dots: false,
                autoplayHoverPause: true,
                // center: true,
                @if(session('direction') == "rtl")
                rtl: true,
                @else
                rtl: false,
                @endif
                    @if(session('direction') == "rtl")
                navText: ["<img src='{{asset('assets/front-end/img/chevron-right.png')}}'>", "<img src='{{asset('assets/front-end/img/chevron-left.png')}}'>"],
                @else
                navText: ["<img src='{{asset('assets/front-end/img/chevron-left.png')}}'>", "<img src='{{asset('assets/front-end/img/chevron-right.png')}}'>"],
                @endif
                responsive: {
                    //X-Small
                    0: {
                        items: 2
                    },
                    360: {
                        items: 3
                    },
                    375: {
                        items: 3
                    },
                    540: {
                        items: 4
                    },
                    //Small
                    576: {
                        items: 3
                    },
                    //Medium
                    768: {
                        items: 5
                    },
                    //Large
                    992: {
                        items: 5
                    },
                    //Extra large
                    1200: {
                        items: 7
                    },
                    //Extra extra large
                    1400: {
                        items: 9
                    }
                }
            });
            let sign_in_exchange = $('#sign_in_exchange');
            sign_in_exchange.on('click', function () {
                // alert('test');
                $('#register_modal').modal('hide');
                $('#login_modal').modal('show');
            });

            let register_exchange = $('#register_exchange');
            register_exchange.on('click', function () {
                // alert('test');
                $('#login_modal').modal('hide');
                $('#register_modal').modal('show');
            });

            let btn_expand_mobile_list = $('#btn_expand_mobile_list');
            let mobile_nav_list = $('#mobile_nav_list');
            btn_expand_mobile_list.on('click', function () {
                mobile_nav_list.slideToggle(500);
            });
        });
        $(".expanding_btn").click(function () {
            $(this).next(".dropdown-menu").toggle('fast');
        });
        $(".expanding_btn_sub").click(function () {
            $(this).next(".sub_dropdown-menu").toggle('fast');
        });
        $(".expanding_btn_sub_sub").click(function () {
            $(this).next(".sub_sub_dropdown-menu").toggle('fast');
        });

        $(window).scroll(function () {
            if ($(document).scrollTop() > 400) {
                $('.mobile-head').addClass('fixed_nav');
                $('#mobile_nav_list').addClass('fixed_nav_2');
            } else {
                $('.mobile-head').removeClass('fixed_nav');
                $('#mobile_nav_list').removeClass('fixed_nav_2');
            }
        });
        function getMobileOperatingSystem() {
            var userAgent = navigator.userAgent || navigator.vendor || window.opera;
            // Windows Phone must come first because its UA also contains "Android"
            if (/windows phone/i.test(userAgent)) {
                return "Windows Phone";
            }
            if (/android/i.test(userAgent)) {
                return "Android";
            }
            // iOS detection from: http://stackoverflow.com/a/9039885/177710
            if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
                return "iOS";
            }
            return "unknown";
        }
        $(document).ready(function () {
            if (getMobileOperatingSystem() === 'Android') {
                $('#android_app').show();
                $('#ios_app').hide();
            } else {
                $('#ios_app').show();
                $('#android_app').hide();
            }
        });
    </script>
@endpush
