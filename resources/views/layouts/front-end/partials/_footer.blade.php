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
    <div
        class="row bold text-center mx-0 {{Session::get('direction') === "rtl" ? 'text-md-right' : 'text-md-left'}} mt-3"
        style="background: {{$web_config['primary_color']}}10;padding:20px;" dir="{{session('direction')}}">


        <div class="col-xxl-6 col-xl-6 col-md-6 col-md-6 col-sm-6 col-12 pb-3 d-none d-lg-inline-block">
            <div class="text-center">
                <h4 class="s_27 always mb-0 bold">{{\App\CPU\translate('We Are Always Here For ')}} <span class="second_color">{{\App\CPU\translate('Your Service')}}</span></h4>
                <span class="s_19 always_span">{{\App\CPU\translate('Contact With us Through The Next Support Channels')}}</span>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-3 col-md-3 col-md-3 col-sm-3 col-12 pb-3 help_desk {{session('direction') == 'rtl' ? 'text-right' : 'text-left'}}">
            <div>
               <span class="s_16">
                   {{\App\CPU\translate('Help Center')}}
               </span>
                <br>
                <a href="mailto:help@beauty-centeer.com" class="mt-1">
                    <span dir="ltr">

                    <i class="fa fa-envelope-o mx-1" aria-hidden="true"></i>
                    help@beauty-centeer.com
                    </span>
                </a>
            </div>
        </div>
        <div class="col-xxl-3 col-xl-3 col-md-3 col-md-3 col-sm-3 col-12 pb-3 support {{session('direction') == 'rtl' ? 'text-right' : 'text-left'}}">
            <div>
                <span>
                    {{\App\CPU\translate('Support & Contact Through Our Email')}}
                </span>
                <br>
                <a href="mailto:care@beauty-centeer.com" class="mt-1">
                    <span dir="ltr">
                    <i class="fa fa-chevron-down bordered_chevron mx-1" aria-hidden="true"></i>
                    care@beauty-centeer.com
                    </span>
                </a>
            </div>
        </div>
        {{--        <div class="col-xxl-6 col-xl-6 col-md-6 col-md-6 col-sm-6 col-12 d-flex justify-content-center">--}}
        {{--            <div>--}}
        {{--                <a href="{{route('about-us')}}">--}}
        {{--                    <div class="text-center">--}}
        {{--                        <img class="size-60" src="{{asset("assets/front-end/png/about company.png")}}"--}}
        {{--                                alt="">--}}
        {{--                    </div>--}}
        {{--                    <div class="text-center">--}}

        {{--                            <p class="m-0">--}}
        {{--                                {{ \App\CPU\translate('About Company')}}--}}
        {{--                            </p>--}}

        {{--                    </div>--}}
        {{--                </a>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--        <div class="col-md-3 d-flex justify-content-center">--}}
        {{--            <div >--}}
        {{--                <a href="{{route('contacts')}}">--}}
        {{--                    <div class="text-center">--}}
        {{--                        <img class="size-60" src="{{asset("assets/front-end/png/contact us.png")}}"--}}
        {{--                                alt="">--}}
        {{--                    </div>--}}
        {{--                    <div class="text-center">--}}
        {{--                        <p class="m-0">--}}
        {{--                        {{ \App\CPU\translate('Contact Us')}}--}}
        {{--                    </p>--}}
        {{--                    </div>--}}
        {{--                </a>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--        <div class="col-md-3 d-flex justify-content-center">--}}
        {{--            <div >--}}
        {{--                <a href="{{route('helpTopic')}}">--}}
        {{--                    <div class="text-center">--}}
        {{--                        <img class="size-60" src="{{asset("assets/front-end/png/faq.png")}}"--}}
        {{--                                alt="">--}}
        {{--                    </div>--}}
        {{--                    <div class="text-center">--}}
        {{--                        <p class="m-0">--}}
        {{--                        {{ \App\CPU\translate('FAQ')}}--}}
        {{--                    </p>--}}
        {{--                    </div>--}}
        {{--                </a>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--        --}}{{-- <div class="col-md-1">--}}

        {{--        </div> --}}
    </div>

    <footer class="page-footer font-small mdb-color rtl" style="background: #F6F6F6!important;">
        <!-- Footer Links -->
        {{--        <div class="pt-4" style="background:{{$web_config['primary_color']}}20;">--}}
        <div class="pt-4" style="background:#F6F6F6">
            <div class="container text-center __pb-13px">

                <!-- Footer links -->
                {{--                <div class="row text-center {{Session::get('direction') === "rtl" ? 'text-md-right' : 'text-md-left'}} mt-3 pb-3 " dir="rtl">--}}
                <div class="row text-center {{Session::get('direction') === "rtl" ? 'text-md-right' : 'text-md-left'}}  pb-3 " dir="{{session('direction')}}">
                    <!-- Grid column -->
                    <div class="col-md-3 footer-web-logo">
                        <a class="d-block" href="{{route('home')}}">
                            {{--                            <img class="{{Session::get('direction') === "rtl" ? 'rightalign' : ''}}" src="{{asset("storage/app/public/company/")}}/{{ $web_config['footer_logo']->value }}"--}}
                            {{--                                onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"--}}
                            {{--                                alt="{{ $web_config['name']->value }}"/>--}}

                            <img class="" src="{{asset("storage/company/")}}/{{ $web_config['footer_logo']->value }}"
                                 onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                 alt="{{ $web_config['name']->value }}"/>
                        </a>
                        <div class="col-md-12 mt-2">
                            <a href="#" class="">
                                <i class="fa-solid fa-location-dot ml-1 second_color"></i>
                                <span class="bold">{{\App\CPU\translate('Saudi Arabia')}}</span>
                            </a>
                        </div>
                        <div class="col-md-12 mt-2">
                            <a href="#" class="">
                                <i class="fa fa-phone ml-1 second_color"></i>
                                <span class="bold num_fam" dir="ltr">+96566897770</span>
                            </a>
                        </div>
                        <div class="col-md-12 mt-2">
                            <a href="#" class="">
                                <i class="fa fa-chevron-down border_second_color ml-1 second_color"></i>
                                <span class="bold" dir="ltr" style="border-bottom: 1px solid #000">care@beauty-centeer.com</span>

                            </a>
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
                                            <img class="w-75" style="height: auto"
                                                 src="{{asset("assets/front-end/img/google-store.png")}}"
                                                 alt="">
                                        </a>
                                    </div>
                                @endif
                                @if($android['status'])
                                    <div class="col-md-12 col-6 mt-2">
                                        <a href="{{ $android['link'] }}" role="button">
                                            <img class="w-75" src="{{asset("assets/front-end/img/ios-store.png")}}"
                                                 alt="" style="height: auto">
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{--                        @if($ios['status'] || $android['status'])--}}
                        {{--                            <div class="mt-4 pt-lg-4">--}}
                        {{--                                <h6 class="text-uppercase font-weight-bold footer-heder align-items-center">--}}
                        {{--                                    {{\App\CPU\translate('download_our_app')}}--}}
                        {{--                                </h6>--}}
                        {{--                            </div>--}}
                        {{--                        @endif--}}


                        {{--                        <div class="store-contents d-flex justify-content-center pr-lg-4">--}}
                        {{--                            @if($ios['status'])--}}
                        {{--                                <div class="{{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}} mb-2">--}}
                        {{--                                    <a class="" href="{{ $ios['link'] }}" role="button">--}}
                        {{--                                        <img class="w-100" src="{{asset("assets/front-end/png/apple_app.png")}}"--}}
                        {{--                                             alt="">--}}
                        {{--                                    </a>--}}
                        {{--                                </div>--}}
                        {{--                            @endif--}}

                        {{--                            @if($android['status'])--}}
                        {{--                                <div class="{{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}} mb-2">--}}
                        {{--                                    <a href="{{ $android['link'] }}" role="button">--}}
                        {{--                                        <img class="w-100" src="{{asset("assets/front-end/png/google_app.png")}}"--}}
                        {{--                                             alt="">--}}
                        {{--                                    </a>--}}
                        {{--                                </div>--}}
                        {{--                            @endif--}}
                        {{--                        </div>--}}
                    </div>
                    <div class="col-md-9 col-12 mt-4">
                        <div class="row">

                            <div class="{{Session::get('direction') === "rtl" ? 'col-md-5' : 'col-md-4'}} col-6 footer-padding-bottom">
                                    <h6 class="text-uppercase mb-3 font-weight-bold footer-heder">{{\App\CPU\translate('Terms & Policies')}}</h6>
                                <ul class="widget-list __pb-10px">
{{--                                    @php($flash_deals=\App\Model\FlashDeal::where(['status'=>1,'deal_type'=>'flash_deal'])->whereDate('start_date','<=',date('Y-m-d'))->whereDate('end_date','>=',date('Y-m-d'))->first())--}}
{{--                                    @if(isset($flash_deals))--}}
{{--                                        <li class="widget-list-item">--}}
{{--                                            <a class="widget-list-link"--}}
{{--                                               href="{{route('flash-deals',[$flash_deals['id']])}}">--}}
{{--                                                {{\App\CPU\translate('flash_deal')}}--}}
{{--                                            </a>--}}
{{--                                        </li>--}}
{{--                                    @endif--}}
                                    {{--                                    <li class="widget-list-item"><a class="widget-list-link"--}}
                                    {{--                                                                    href="{{route('products',['data_from'=>'featured','page'=>1])}}">{{\App\CPU\translate('featured_products')}}</a>--}}
                                    {{--                                    </li>--}}
                                    {{--                                    <li class="widget-list-item"><a class="widget-list-link"--}}
                                    {{--                                                                    href="{{route('products',['data_from'=>'latest','page'=>1])}}">{{\App\CPU\translate('latest_products')}}</a>--}}
                                    {{--                                    </li>--}}
                                    {{--                                    <li class="widget-list-item"><a class="widget-list-link"--}}
                                    {{--                                                                    href="{{route('products',['data_from'=>'best-selling','page'=>1])}}">{{\App\CPU\translate('best_selling_product')}}</a>--}}
                                    {{--                                    </li>--}}
                                    {{--                                    <li class="widget-list-item"><a class="widget-list-link"--}}
                                    {{--                                                                    href="{{route('products',['data_from'=>'top-rated','page'=>1])}}">{{\App\CPU\translate('top_rated_product')}}</a>--}}
                                    {{--                                    </li>--}}
{{--                                    <li class="widget-list-item"><a class="widget-list-link bold"--}}
{{--                                                                    href="#">الشحن والتسليم</a>--}}
{{--                                    </li>--}}
                                    @if(isset($return_policy['status']) && $return_policy['status'] == 1)
                                        <li class="widget-list-item">
                                            <a class="widget-list-link bold"
                                               href="{{route('return-policy')}}">{{\App\CPU\translate('return_policy')}}</a>
                                        </li>
                                    @endif
                                    <li class="widget-list-item"><a class="widget-list-link bold"
                                                                    href="{{route('terms')}}">{{\App\CPU\translate('Service Terms')}}</a>
                                    </li>
                                    <li class="widget-list-item"><a class="widget-list-link bold"
                                                                    href="{{route('privacy-policy')}}">{{\App\CPU\translate('Privacy policy')}}</a>
                                    </li>
                                    <li class="widget-list-item"><a class="widget-list-link bold"
                                                                    href="{{route('track-order.index')}}">{{\App\CPU\translate('Track Your Order')}}</a>
                                    </li>


                                </ul>


                            </div>
                            <div class="{{Session::get('direction') === "rtl" ? 'col-md-3' : 'col-md-4'}} col-6 footer-padding-bottom"
                                 style="{{Session::get('direction') === "rtl" ? 'padding-right:20px;' : ''}}">
                                <h6 class="text-uppercase mb-3 font-weight-bold footer-heder">{{\App\CPU\translate('Customer Service')}}</h6>
                                @if(auth('customer')->check())
                                    <ul class="widget-list __pb-10px">
                                        <li class="widget-list-item">
                                            <a class="widget-list-link bold"
                                               href="{{route('about-us')}}">{{\App\CPU\translate('About Us')}}</a>
                                        </li>
                                        <li class="widget-list-item">
                                            <a class="widget-list-link bold"
                                               href="{{route('helpTopic')}}">{{\App\CPU\translate('FAQ')}}</a>
                                        </li>
                                        <li class="widget-list-item">
                                            <a class="widget-list-link bold"
                                               href="{{route('contacts')}}">{{\App\CPU\translate('Contact Us')}}</a>
                                        </li>

                                        <li class="widget-list-item">
                                            <a class="widget-list-link bold"
                                               href="#">{{\App\CPU\translate('Be a Partner')}}</a>
                                        </li>


                                    </ul>
                                @else
                                    <ul class="widget-list __pb-10px">

                                        <li class="widget-list-item">
                                            <a class="widget-list-link bold"
                                               href="{{route('about-us')}}">{{\App\CPU\translate('About Us')}}</a>
                                        </li>
                                        <li class="widget-list-item">
                                            <a class="widget-list-link bold"
                                               href="{{route('helpTopic')}}">{{\App\CPU\translate('FAQ')}}</a>
                                        </li>
                                        <li class="widget-list-item">
                                            <a class="widget-list-link bold"
                                               href="{{route('contacts')}}">{{\App\CPU\translate('Contact Us')}}</a>
                                        </li>
                                        <li class="widget-list-item">
                                            <a class="widget-list-link bold"
                                               href="#">{{\App\CPU\translate('Be a Partner')}}</a>
                                        </li>
                                    </ul>
                                @endif
                            </div>
                            <div class="col-md-4 footer-padding-bottom">
                                <div class="mb-2">
                                    <h6 class="text-uppercase mb-3 font-weight-bold footer-heder">{{\App\CPU\translate('Join us Now')}}</h6>
                                    <span class="bold"
                                          style="color: #636161 ">{{\App\CPU\translate('Sign up to Get Latest Offer')}}</span>
                                </div>
                                <div class="text-nowrap mb-4 position-relative">
                                    <form action="{{ route('subscription') }}" method="post" style="border: 1px solid #f14061;border-radius: 5px;height: 45px;">
                                        @csrf
                                        <input type="email" name="subscription_email"
                                               class="form-control subscribe-border second_color"
                                               placeholder="{{\App\CPU\translate('email')}}" required
                                               style="padding: 0 11px;text-align: right;">
                                        <button class="subscribe-button" type="submit"
                                                style="background: #f14061; color: #fff">
                                            {{\App\CPU\translate('Confirm')}}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4 download_app_div  {{Session::get('direction') === "rtl" ? ' flex-row-reverse text-right' : 'text-left'}}" dir="{{session('direction')}}">
                    <div class="col-md-3 col-lg-3 s_19 {{session('direction') == 'rtl' ? 'pl-5' : 'pr-5'}} download_app bold color_gray d-none d-lg-inline-block">
                        <div class="row">
                            @if($ios['status'])

                                <div class="col-md-12 col-6 mt-2">
                                    <a class="" href="{{ $ios['link'] }}" role="button">
                                        <img class="w-75" style="height: auto"
                                             src="{{asset("assets/front-end/img/google-store.png")}}"
                                             alt="">
                                    </a>
                                </div>
                            @endif
                            @if($android['status'])
                                <div class="col-md-12 col-6 mt-2">
                                    <a href="{{ $android['link'] }}" role="button">
                                        <img class="w-75" src="{{asset("assets/front-end/img/ios-store.png")}}"
                                             alt="" style="height: auto">
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2 col-lg-2 col-12 s_19  bold">
                        <h6 class="text-uppercase mb-3 font-weight-bold footer-heder">{{\App\CPU\translate('Important Links')}}</h6>
                        <ul class="widget-list __pb-10px">
                            @php($pages=\App\Model\StaticPage::get())
                            @if(isset($pages) && $pages->count() > 0)
                                @foreach($pages as $page)
                                    <li class="widget-list-item">
                                        <a class="widget-list-link bold"
                                           href="{{route('static.page', $page->id)}}">
                                            {{$page->title}}
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>

                    <div class="col-md-4 col-lg-4 col-12 s_19 pl-5 under_download_app bold color_gray">
                                <span class="pb-2 d-block">
                                    {{\App\CPU\translate('We are Partners')}} <span class="second_color">
                                        {{\App\CPU\translate('to the Largest')}}
                                    </span> {{\App\CPU\translate('National & international Stores')}}
                                </span>
                                <span class="pb-3 d-block">
                                    {{\App\CPU\translate('We Deal With')}} <span class="second_color">
                                        {{\App\CPU\translate('Trusted Suppliers')}}
                                    </span>
                                </span>
                                <span class="pb-3 d-block">
                                    {{\App\CPU\translate('Check More Than')}}
                                    <span class="second_color">
                                        {{\App\CPU\translate('50 Thousands Product')}}
                                    </span>
                                    {{\App\CPU\translate('& Category with Daily New Products')}}
                                </span>
                    </div>
                    <div class="col-md-3 col-lg-3 col-12 s_19 pl-5  bold">
                                <span class="d-block color_black">
                                    {{\App\CPU\translate('Be a Partner')}}
                                </span>
                        <span class="d-block color_gray mb-2">
                                    {{\App\CPU\translate('Welcome to Be our Partner and Publish Your Products and your Different Activities')}}
                                </span>
                        <span class="d-block color_black">
                                    {{\App\CPU\translate('Contact with us Through E-mail')}}
                            <br>
                            <span class="second_color">
                                    {{\App\CPU\translate('& Be a Partner Right Now')}}
                                    </span>
                                </span>
                    </div>


                    {{--                            <div class="col-md-7">--}}
                    {{--                                <div--}}
                    {{--                                    class="row d-flex align-items-center mobile-view-center-align  justify-content-center justify-content-md-startr">--}}
                    {{--                                    <div style="{{Session::get('direction') === "rtl" ? 'margin-right:23px;' : ''}}">--}}
                    {{--                                        <span--}}
                    {{--                                            class="mb-4 font-weight-bold footer-heder">{{ \App\CPU\translate('Start a conversation')}}</span>--}}
                    {{--                                    </div>--}}
                    {{--                                    <div--}}
                    {{--                                        class="flex-grow-1 d-none d-md-block {{Session::get('direction') === "rtl" ? 'mr-4 mx-sm-4' : 'mx-sm-4'}}">--}}
                    {{--                                        <hr class="start_address_under_line"/>--}}
                    {{--                                    </div>--}}
                    {{--                                </div>--}}
                    {{--                                <div class="row ">--}}
                    {{--                                    <div class="col-11 start_address ">--}}
                    {{--                                        <div class="">--}}
                    {{--                                            <a class="widget-list-link" href="tel: {{$web_config['phone']->value}}">--}}
                    {{--                                                <span><i class="fa fa-phone m-2"></i>{{\App\CPU\Helpers::get_business_settings('company_phone')}} </span>--}}
                    {{--                                            </a>--}}

                    {{--                                        </div>--}}
                    {{--                                        <div>--}}
                    {{--                                            <a class="widget-list-link"--}}
                    {{--                                               href="mailto: {{\App\CPU\Helpers::get_business_settings('company_email')}}">--}}
                    {{--                                                <span><i class="fa fa-envelope m-2"></i> {{\App\CPU\Helpers::get_business_settings('company_email')}} </span>--}}
                    {{--                                            </a>--}}
                    {{--                                        </div>--}}
                    {{--                                        <div>--}}
                    {{--                                            @if(auth('customer')->check())--}}
                    {{--                                                <a class="widget-list-link" href="{{route('account-tickets')}}">--}}
                    {{--                                                    <span><i class="fa fa-user-o m-2"></i> {{ \App\CPU\translate('Support Ticket')}} </span>--}}
                    {{--                                                </a><br>--}}
                    {{--                                            @else--}}
                    {{--                                                <a class="widget-list-link" href="{{route('customer.auth.login')}}">--}}
                    {{--                                                    <span><i class="fa fa-user-o m-2"></i> {{ \App\CPU\translate('Support Ticket')}} </span>--}}
                    {{--                                                </a><br>--}}
                    {{--                                            @endif--}}
                    {{--                                        </div>--}}
                    {{--                                    </div>--}}
                    {{--                                </div>--}}
                    {{--                            </div>--}}
                    {{--                            <div class="col-md-5 ">--}}
                    {{--                                <div--}}
                    {{--                                    class="row pl-2 d-flex align-items-center mobile-view-center-align justify-content-center justify-content-md-start">--}}
                    {{--                                    <div>--}}
                    {{--                                        <span--}}
                    {{--                                            class="mb-4 font-weight-bold footer-heder">{{ \App\CPU\translate('address')}}</span>--}}
                    {{--                                    </div>--}}
                    {{--                                    <div--}}
                    {{--                                        class="flex-grow-1 d-none d-md-block {{Session::get('direction') === "rtl" ? 'mr-3 ' : 'ml-3'}}">--}}
                    {{--                                        <hr class="address_under_line"/>--}}
                    {{--                                    </div>--}}
                    {{--                                </div>--}}
                    {{--                                <div class="pl-2">--}}
                    {{--                                    <span class="__text-14px"><i class="fa fa-map-marker m-2"></i> {{ \App\CPU\Helpers::get_business_settings('shop_address')}} </span>--}}
                    {{--                                </div>--}}
                    {{--                            </div>--}}
                </div>

                <!-- Footer links -->
            </div>
        </div>


        <!-- Grid row -->
        <div style="background: #F6F6F6;border-top: 1px solid #fca1a8" class="mt-5 ">
            <div class="container ">
                <div class="row" dir="rtl">
                    <div class="last_part col-xl-8 col-lg-8 col-md-8 col-sm-8 col-12">
                        <span class="bold color_gray" style="display: block;   margin-top: 20px;">
                            © جميع الحقوق محفوظة beautycenter <span class="num_fam">2023</span> ... تم التصميم والتطوير بواسطة MatrixClouds
                        </span>
                    </div>
                    <div class="last_part col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 text-left">
                        <img class="px-2 py-3" style="width: 60px" src="{{asset('assets/front-end/img/visa.png')}}" alt="visa">
                        <img class="px-2 py-3" style="width: 60px" src="{{asset('assets/front-end/img/paypal.png')}}" alt="paypal">
                        <img class="px-2 py-3" style="width: 60px" src="{{asset('assets/front-end/img/master.png')}}" alt="master">
                        <img class="px-2 py-3" style="width: 60px" src="{{asset('assets/front-end/img/west.png')}}" alt="west">
                    </div>
                </div>
{{--                <div class="d-flex flex-wrap end-footer footer-end last-footer-content-align">--}}
{{--                    <div class="mt-3">--}}
{{--                        <p class="{{Session::get('direction') === "rtl" ? 'text-right ' : 'text-left'}} __text-16px">{{ $web_config['copyright_text']->value }}</p>--}}
{{--                    </div>--}}
{{--                    <div--}}
{{--                        class="max-sm-100 justify-content-center d-flex flex-wrap mt-md-3 mt-0 mb-md-3 {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}">--}}
{{--                        @php($social_media = \App\Model\SocialMedia::where('active_status', 1)->get())--}}
{{--                        @if(isset($social_media))--}}
{{--                            @foreach ($social_media as $item)--}}
{{--                                <span class="social-media ">--}}
{{--                                        <a class="social-btn text-white sb-light sb-{{$item->name}} {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}} mb-2"--}}
{{--                                           target="_blank" href="{{$item->link}}">--}}
{{--                                            <i class="{{$item->icon}}" aria-hidden="true"></i>--}}
{{--                                        </a>--}}
{{--                                    </span>--}}
{{--                            @endforeach--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                    <div class="d-flex __text-14px">--}}
{{--                        <div class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">--}}
{{--                            <a class="widget-list-link"--}}
{{--                               href="{{route('terms')}}">{{\App\CPU\translate('terms_&_conditions')}}</a>--}}
{{--                        </div>--}}
{{--                        <div>--}}
{{--                            <a class="widget-list-link" href="{{route('privacy-policy')}}">--}}
{{--                                {{\App\CPU\translate('privacy_policy')}}--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
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
