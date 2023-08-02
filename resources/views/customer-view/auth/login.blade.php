@extends('layouts.front-end.app')
@section('title', \App\CPU\translate('Login'))
@push('css_or_js')
    <style>
        .password-toggle-btn .custom-control-input:checked ~ .password-toggle-indicator {
            color: {{$web_config['primary_color']}};
        }
    </style>
@endpush
@section('content')
    <div class="row mb-3 __inline-35"
         style="background:{{$web_config['primary_color']}}10;">
        <div class="container" dir="rtl">
            <div class="row">
                <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-9 pl-3 py-3 serial_route">
                    <a href="{{route('home')}}" class="bold">الصفحة الرئيسية</a>
                    <div class="d-inline-block position-relative" style="width: 25px">
                        <i style="position: absolute;top: -15px;right: 3px;" class="fa-solid fa-chevron-left mt-1 px-1"></i>
                    </div>
                    <span class="bold">تسجيل دخول</span>

                </div>

                <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-3 py-3 text-left back">
                    <a href="{{ url()->previous() }}" class="bold">العودة</a>
                    <div class="d-inline-block position-relative" style="width: 25px"><i
                            style="position: absolute;top: -15px;right: 3px;"
                            class="fa-solid fa-chevron-left mt-1  px-1 "></i></div>

                </div>
            </div>

        </div>
    </div>
    <div class="container py-4 py-lg-5 my-4"
         style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="mx-auto __max-w-620">
            <div class="card border-0 box-shadow">
                <div class="card-body">
                    <h2 class="h4 mb-1">{{\App\CPU\translate('sign_in')}}</h2>
                    <form class="needs-validation mt-2" autocomplete="off" action="{{route('customer.auth.login')}}"
                            method="post" id="form-id">
                        @csrf
                        <div class="form-group">
                            <label for="si-email">{{\App\CPU\translate('email_address')}}
                                / {{\App\CPU\translate('phone')}}</label>
                            <input class="form-control" type="text" name="user_id" id="si-email"
                                    style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                    value="{{old('user_id')}}"
                                    placeholder="{{\App\CPU\translate('Enter_email_address_or_phone_number')}}"
                                    required>
                            <div
                                class="invalid-feedback">{{\App\CPU\translate('please_provide_valid_email_or_phone_number')}}
                                .
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="si-password">{{\App\CPU\translate('password')}}</label>
                            <div class="password-toggle">
                                <input class="form-control" name="password" type="password" id="si-password"
                                        style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                        required>
                                <label class="password-toggle-btn">
                                    <input class="custom-control-input" type="checkbox"><i
                                        class="czi-eye password-toggle-indicator"></i><span
                                        class="sr-only">{{\App\CPU\translate('Show')}} {{\App\CPU\translate('password')}} </span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group d-flex flex-wrap justify-content-between">
                            <div class="form-group">
                                <input type="checkbox"
                                        class="{{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}"
                                        name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="" for="remember">{{\App\CPU\translate('remember_me')}}</label>
                            </div>
                            <a class="font-size-sm" href="{{route('customer.auth.recover-password')}}">
                                {{\App\CPU\translate('forgot_password')}}?
                            </a>
                        </div>
                        {{-- recaptcha --}}
{{--                        @php($recaptcha = \App\CPU\Helpers::get_business_settings('recaptcha'))--}}
{{--                        @if(isset($recaptcha) && $recaptcha['status'] == 1)--}}
{{--                            <div id="recaptcha_element" class="w-100" data-type="image"></div>--}}
{{--                            <br/>--}}
{{--                        @else--}}
{{--                            <div class="row py-2">--}}
{{--                                <div class="col-6 pr-2">--}}
{{--                                    <input type="text" class="form-control border __h-40" name="default_captcha_value" value=""--}}
{{--                                        placeholder="{{\App\CPU\translate('Enter captcha value')}}" autocomplete="off">--}}
{{--                                </div>--}}
{{--                                <div class="col-6 input-icons mb-2 w-100 rounded bg-white">--}}
{{--                                    <a onclick="javascript:re_captcha();" class="d-flex align-items-center align-items-center">--}}
{{--                                        <img src="{{ URL('/customer/auth/code/captcha/1') }}" class="input-field rounded __h-40" id="default_recaptcha_id">--}}
{{--                                        <i class="tio-refresh icon cursor-pointer p-2"></i>--}}
{{--                                    </a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @endif--}}
                        <button class="btn btn--primary btn-block btn-shadow"
                                type="submit">{{\App\CPU\translate('sign_in')}}</button>
                    </form>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 d-flex flex-wrap justify-content-around justify-content-md-between align-items-center __gap-15" style="direction: {{ Session::get('direction') }}">
                            <div class="{{Session::get('direction') === "rtl" ? '' : 'ml-2'}}">
                                <h6 class="m-0">{{ \App\CPU\translate('no_account_Sign_up_now') }}</h6>
                            </div>
                            <div class="{{Session::get('direction') === "rtl" ? 'ml-2' : ''}}">
                                <a class="btn btn-outline-primary"
                                    href="{{route('customer.auth.sign-up')}}">
                                    <i class="fa fa-user-circle"></i> {{\App\CPU\translate('sign_up')}}
                                </a>
                            </div>
                        </div>
                        @foreach (\App\CPU\Helpers::get_business_settings('social_login') as $socialLoginService)
                            @if (isset($socialLoginService) && $socialLoginService['status']==true)
                                <div class="col-sm-6 text-center mt-3">
                                    <a class="btn btn-outline-primary w-100" href="{{route('customer.auth.service-login', $socialLoginService['login_medium'])}}">
                                        <i class="czi-{{ $socialLoginService['login_medium'] }} mr-2 ml-n1"></i>{{\App\CPU\translate('sign_in_with_'.$socialLoginService['login_medium'])}}
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

    {{-- recaptcha scripts start --}}
    @if(isset($recaptcha) && $recaptcha['status'] == 1)
        <script type="text/javascript">
            var onloadCallback = function () {
                grecaptcha.render('recaptcha_element', {
                    'sitekey': '{{ \App\CPU\Helpers::get_business_settings('recaptcha')['site_key'] }}'
                });
            };
        </script>
        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async
                defer></script>
        <script>
            $("#form-id").on('submit', function (e) {
                var response = grecaptcha.getResponse();

                if (response.length === 0) {
                    e.preventDefault();
                    toastr.error("{{\App\CPU\translate('Please check the recaptcha')}}");
                }
            });
        </script>
    @else
        <script type="text/javascript">
            function re_captcha() {
                $url = "{{ URL('/customer/auth/code/captcha') }}";
                $url = $url + "/" + Math.random();
                document.getElementById('default_recaptcha_id').src = $url;
                console.log('url: '+ $url);
            }
        </script>
    @endif
    {{-- recaptcha scripts end --}}
@endpush
