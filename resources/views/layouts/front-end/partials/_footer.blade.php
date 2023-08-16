<!-- Footer -->
<style>
    .social-media :hover {
        color: {{$web_config['secondary_color']}}    !important;
    }
    .start_address_under_line {
    {{Session::get('direction') === "rtl" ? 'width: 344px;' : 'width: 331px;'}}
    }
</style>
@php($whatsapp = \App\CPU\Helpers::get_business_settings('whatsapp'))

@php($refund_policy = \App\CPU\Helpers::get_business_settings('refund-policy'))
@php($return_policy = \App\CPU\Helpers::get_business_settings('return-policy'))
@php($cancellation_policy = \App\CPU\Helpers::get_business_settings('cancellation-policy'))
<div class="__inline-9 rtl mt-5 ">
    {{-- <div class="row bold d-flex justify-content-center text-center mx-0 {{Session::get('direction') === "rtl" ? 'text-md-right' : 'text-md-left'}} mt-3" style="background: {{$web_config['primary_color']}}10;padding:20px;" dir="{{session('direction')}}">
        <div class="col-6  pb-3 d-none d-lg-inline-block">
            <div class="text-center">
                <h4 class="s_18 always mb-0 bold">{{\App\CPU\translate('We Are Always Here For ')}} <span class="second_color">{{\App\CPU\translate('Your Service')}}</span></h4>
                <span class="s_18 always_span">{{\App\CPU\translate('Contact With us Through The Next Support Channels')}}</span>
            </div>
        </div>
        <div class="col-6 pb-3 help_desk {{session('direction') == 'rtl' ? 'text-right' : 'text-left'}}">
            <div class="text-center"> <span class="s_18"> {{\App\CPU\translate('Help Center')}} </span> <br>
                <a href="mailto:help@beauty-centeer.com" class="mt-1">
                    <span dir="ltr" class="s_18"> <i class="fa fa-envelope-o mx-1" aria-hidden="true"></i> help@beauty-centeer.com </span>
                </a>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-3 col-md-3 col-md-3 col-sm-3 col-12 pb-3 support {{session('direction') == 'rtl' ? 'text-right' : 'text-left'}}">
            <div>
                <span> {{\App\CPU\translate('Support & Contact Through Our Email')}} </span> <br>
                <a href="mailto:care@beauty-centeer.com" class="mt-1">
                    <span dir="ltr" class="s_18"> <i class="fa fa-chevron-down bordered_chevron mx-1" aria-hidden="true"></i> care@beauty-centeer.com </span>
                </a>
            </div>
        </div>
    </div> --}}

    <footer class="page-footer font-small mdb-color rtl" style="background: #F6F6F6!important;">
        <div class="pt-4" style="background:#F6F6F6">
            <div class="container text-center __pb-13px">
                <!-- Footer links -->
                <div class="row text-center {{Session::get('direction') === "rtl" ? 'text-md-right' : 'text-md-left'}}  pb-3 " dir="{{session('direction')}}">
                    <!-- Grid column -->
                    <div class="col-md-3 footer-web-logo">
                        <a class="d-block" href="{{route('home')}}">
                            <img class="" src="{{asset("storage/company/")}}/{{ $web_config['footer_logo']->value }}" onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'" alt="{{ $web_config['name']->value }}"/>
                        </a>
                        <div class="col-md-12 mt-2">
                            <a href="#" class=""> <i class="fa-solid fa-location-dot ml-1 second_color"></i> <span class="bold">{{\App\CPU\translate('Saudi Arabia')}}</span> </a>
                        </div>
                        <div class="col-md-12 mt-2">
                            <a href="#" class=""> <i class="fa fa-phone ml-1 second_color"></i> <span class="bold num_fam" dir="ltr">+966530852675  </span> </a>
                        </div>
                        <div class="col-md-12 mt-2">
                            <a href="#" class=""> <i class="fa fa-chevron-down border_second_color ml-1 second_color"></i> <span class="bold" dir="ltr" style="border-bottom: 1px solid #000">care@beauty-centeer.com</span> </a>
                        </div>
                        <div class="col-md-12 mt-3 social_footer ">
                            <a href="https://web.whatsapp.com/send/?phone={{ $whatsapp['phone'] }}?text=Hello%20there!" class="px-1 d-inline-block">
                                <img src="{{asset('assets/front-end/img/whatsapp.png')}}" alt="icon" style="width: 27px">
                            </a>
                            <a href="{{App\Model\SocialMedia::where('name', 'facebook')->first()->link}}" class="px-1 d-inline-block">
                                <img src="{{asset('assets/front-end/img/fb.png')}}" alt="icon" style="width: 23px">
                            </a>
                            <a href="{{App\Model\SocialMedia::where('name', 'linkedin')->first()->link}}" class="px-1 d-inline-block">
                                <img src="{{asset('assets/front-end/img/linkedin.png')}}" alt="icon" style="width: 23px">
                            </a>
                            <a href="{{App\Model\SocialMedia::where('name', 'instagram')->first()->link}}" class="px-1 d-inline-block">
                                <img src="{{asset('assets/front-end/img/instagram.png')}}" alt="icon" style="width: 23px">
                            </a>
                            <a href="{{App\Model\SocialMedia::where('name', 'twitter')->first()->link}}" class="px-1 d-inline-block">
                                <img src="{{asset('assets/front-end/img/twitter.png')}}" alt="icon" style="width: 23px">
                            </a>
                        </div>
                        @php($ios = \App\CPU\Helpers::get_business_settings('download_app_apple_stroe'))
                        @php($android = \App\CPU\Helpers::get_business_settings('download_app_google_stroe'))
                        <div class="col-md-12 s_19 mt-2 pl-5 download_app download_app_mobile bold color_gray d-none ">
                            <div class="row">
                                @if($ios['status'])
                                    <div class="col-md-12 col-6 mt-2">
                                        <a class="" href="{{ $ios['link'] }}" role="button">
                                            <img class="w-75" style="height: auto" src="{{asset("assets/front-end/img/google-store.png")}}" alt="">
                                        </a>
                                    </div>
                                @endif
                                @if($android['status'])
                                    <div class="col-md-12 col-6 mt-2">
                                        <a href="{{ $android['link'] }}" role="button">
                                            <img class="w-75" src="{{asset("assets/front-end/img/ios-store.png")}}" alt="" style="height: auto">
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 col-12 mt-4">
                        <div class="row">
                            <div class="{{Session::get('direction') === "rtl" ? 'col-md-5' : 'col-md-4'}} col-6 footer-padding-bottom">
                                    <h6 class="text-uppercase mb-3 font-weight-bold footer-heder">{{\App\CPU\translate('Terms & Policies')}}</h6>
                                <ul class="widget-list __pb-10px">
                                    @if(isset($return_policy['status']) && $return_policy['status'] == 1)
                                        <li class="widget-list-item"> <a class="widget-list-link bold" href="{{route('return-policy')}}">{{\App\CPU\translate('return_policy')}}</a>
                                        </li>
                                    @endif
                                    <li class="widget-list-item"><a class="widget-list-link bold" href="{{route('terms')}}">{{\App\CPU\translate('Service Terms')}}</a> </li>
                                    <li class="widget-list-item"><a class="widget-list-link bold" href="{{route('privacy-policy')}}">{{\App\CPU\translate('Privacy policy')}}</a> </li>
                                    <li class="widget-list-item"><a class="widget-list-link bold" href="{{route('track-order.index')}}">{{\App\CPU\translate('Track Your Order')}}</a> </li>
                                </ul>
                            </div>
                            <div class="{{Session::get('direction') === "rtl" ? 'col-md-3' : 'col-md-4'}} col-6 footer-padding-bottom" style="{{Session::get('direction') === "rtl" ? 'padding-right:20px;' : ''}}">
                                <h6 class="text-uppercase mb-3 font-weight-bold footer-heder">{{\App\CPU\translate('Customer Service')}}</h6>
                                @if(auth('customer')->check())
                                    <ul class="widget-list __pb-10px">
                                        <li class="widget-list-item">
                                            <a class="widget-list-link bold" href="{{route('about-us')}}">{{\App\CPU\translate('About Us')}}</a>
                                        </li>
                                        <li class="widget-list-item">
                                            <a class="widget-list-link bold" href="{{route('helpTopic')}}">{{\App\CPU\translate('FAQ')}}</a>
                                        </li>
                                        <li class="widget-list-item">
                                            <a class="widget-list-link bold" href="{{route('contacts')}}">{{\App\CPU\translate('Contact Us')}}</a>
                                        </li>

                                        <li class="widget-list-item">
                                            <a class="widget-list-link bold" href="#">{{\App\CPU\translate('Be a Partner')}}</a>
                                        </li>
                                    </ul>
                                @else
                                    <ul class="widget-list __pb-10px">
                                        <li class="widget-list-item">
                                            <a class="widget-list-link bold" href="{{route('about-us')}}">{{\App\CPU\translate('About Us')}}</a>
                                        </li>
                                        <li class="widget-list-item">
                                            <a class="widget-list-link bold" href="{{route('helpTopic')}}">{{\App\CPU\translate('FAQ')}}</a>
                                        </li>
                                        <li class="widget-list-item">
                                            <a class="widget-list-link bold" href="{{route('contacts')}}">{{\App\CPU\translate('Contact Us')}}</a>
                                        </li>
                                        <li class="widget-list-item">
                                            <a class="widget-list-link bold" href="#">{{\App\CPU\translate('Be a Partner')}}</a>
                                        </li>
                                    </ul>
                                @endif
                            </div>
                            <div class="col-md-4 footer-padding-bottom">
                                <div class="mb-2">
                                    <h6 class="text-uppercase mb-3 font-weight-bold footer-heder">{{\App\CPU\translate('Join us Now')}}</h6>
                                    <span class="bold" style="color: #636161 ">{{\App\CPU\translate('Sign up to Get Latest Offer')}}</span>
                                </div>
                                <div class="text-nowrap mb-4 position-relative">
                                    <form action="{{ route('subscription') }}" method="post" style="border: 1px solid #f14061;border-radius: 5px;height: 45px;">
                                        @csrf
                                        <input type="email" name="subscription_email" class="form-control subscribe-border second_color" placeholder="{{\App\CPU\translate('email')}}" required style="padding: 0 11px;text-align: right;">
                                        <button class="subscribe-button" type="submit" style="background: #f14061; color: #fff">
                                            {{\App\CPU\translate('Confirm')}}
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-12">
                                <ul class="stor_app">
                                    @if($ios['status'])
                                        <li>
                                            <a class="" href="{{ $ios['link'] }}" role="button">
                                                <img class="w-75" style="height: auto" src="{{asset("assets/front-end/img/google-store.png")}}" alt="">
                                            </a>
                                        </li>
                                    @endif
                                    @if($android['status'])
                                        <li>
                                            <a href="{{ $android['link'] }}" role="button">
                                                <img class="w-75" src="{{asset("assets/front-end/img/ios-store.png")}}" alt="" style="height: auto">
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Footer links -->
            </div>
        </div>


        <!-- Grid row -->
        <div style="background: #F6F6F6;border-top: 1px solid #fca1a8" class="mt-5 ">
            <div class="container ">
                <div class="row" dir="rtl">
                    <div class="last_part col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                        <span class="bold color_gray" style="display:block; margin-top: 20px;font-size: small;">
                            © جميع الحقوق محفوظة beautycenter <span class="num_fam">2023</span>
                        </span>
                    </div>
                    <div class="last_part col-xl-8 col-lg-8 col-md-8 col-sm-8 col-12 text-left">
                        <img class="px-2 py-3" style="width: 80px" src="{{asset('assets/front-end/img/1.png')}}" alt="visa">
                        <img class="px-2 py-3" style="width: 80px" src="{{asset('assets/front-end/img/2.png')}}" alt="paypal">
                        <img class="px-2 py-3" style="width: 80px" src="{{asset('assets/front-end/img/3.png')}}" alt="master">
                        <img class="px-2 py-3" style="width: 80px" src="{{asset('assets/front-end/img/4.png')}}" alt="west">
                        <img class="px-2 py-3" style="width: 80px" src="{{asset('assets/front-end/img/5.png')}}" alt="west">
                        <img class="px-2 py-3" style="width: 80px" src="{{asset('assets/front-end/img/6.png')}}" alt="west">
                        <img class="px-2 py-3" style="width: 80px" src="{{asset('assets/front-end/img/7.png')}}" alt="west">
                        <img class="px-2 py-3" style="width: 80px" src="{{asset('assets/front-end/img/8.png')}}" alt="west">
                    </div>
                </div>
            </div>
            <!-- Grid row -->
        </div>
        <!-- Footer Links -->

        <!-- Cookie Settings -->
        @php($cookie = $web_config['cookie_setting'] ? json_decode($web_config['cookie_setting']['value'], true):null)
        @if($cookie && $cookie['status']==1)
            <section id="cookie-section"></section>
        @endif
    </footer>
</div>
