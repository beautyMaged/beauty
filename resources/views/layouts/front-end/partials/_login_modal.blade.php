<!-- Modal -->
<div class="modal fade" id="register_modal" tabindex="-1" aria-labelledby="register_modal_label" aria-hidden="true" dir="rtl">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body pt-5">
                <div class="row pt-1">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute;left: 27px;top: 29px;font-weight: bold;font-size: 45px;color: #000;"><span aria-hidden="true">&times;</span></button>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center"> <h4 class="bold s_22"> {{\App\CPU\translate('New Account')}} </h4> </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-10 m-auto text-center login_with" style="position: relative;z-index: 101; background: #fff;"><h4 class="bold s_20 second_color mt-0 py-4"> {{\App\CPU\translate('Login With')}} </h4> </div> </div>
                        <div class="row position-relative">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-12 m-auto text-center line_through" style=""><div style="height:5px;border-top: 5px solid #000;"></div></div></div>
                        <div class="row">
                            <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 m-auto py-2">
                                <a href="{{route('customer.auth.service-login', 'facebook')}}" class="login_by_btn bold"> {{\App\CPU\translate('Continue With Facebook')}} <img src="{{asset('assets/front-end/img/fb.png')}}" alt="fb-icon"> </a>
                            </div>
                            <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 m-auto py-2">
                                <a href="{{route('customer.auth.service-login', 'google')}}" class="login_by_btn bold"> {{\App\CPU\translate('Continue With Gmail')}} <img src="{{asset('assets/front-end/img/google.png')}}" alt="fb-icon"> </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 m-auto pt-3"> <span class="bold s_22">{{\App\CPU\translate('or')}}</span> </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-11 col-xl-11 col-lg-11 col-md-11 col-sm-11 col-12 m-auto py-2">
                                <form class="needs-validation_ row" id="form-id" action="{{ route('customer.auth.sign-up') }}" method="post">
                                    @csrf
                                    <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mt-2 {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}">
                                        <div class="form-group">
                                            <label for="reg-fn" class="bold s_20">{{\App\CPU\translate('First Name')}}</label>
                                            <input class="form-control" placeholder="{{\App\CPU\translate('First Name')}}" value="{{old('f_name')}}" type="text" name="f_name" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};" required>
                                            <div class="invalid-feedback">{{\App\CPU\translate('please Enter Your First name')}}</div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mt-2 {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}">
                                        <div class="form-group">
                                            <label for="reg-ln" class="bold s_20">{{\App\CPU\translate('Last name')}}</label>
                                            <input class="form-control" placeholder="{{\App\CPU\translate('Last name')}}" type="text" value="{{old('l_name')}}" name="l_name" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};" required>
                                            <div class="invalid-feedback">{{\App\CPU\translate('please Enter Your Last name')}}</div>
                                        </div>
                                    </div>
                                    <div
                                        class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mt-2 {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}">
                                        <label for="reg-email" class="bold s_20">{{\App\CPU\translate('Email')}}</label>
                                        <input id="reg-email" placeholder="{{\App\CPU\translate('Email')}}" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus required style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}}!important; ">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                                        @enderror
                                    </div>
                                    <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mt-2 {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}">
                                        <label for="phone" class="bold s_20">{{\App\CPU\translate('Phone')}}</label>
                                        <input id="phone" placeholder="{{\App\CPU\translate('Phone')}}" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}}!important; ">
                                        @error('phone')
                                        <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                                        @enderror
                                    </div>
                                    <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mt-2 {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}">
                                        <label for="password" class="bold s_20">{{\App\CPU\translate('Password')}}</label>
                                        <input id="password" type="password" placeholder="{{\App\CPU\translate('Password')}}" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="current-password" required style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}}!important; ">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                                        @enderror
                                    </div>
                                    <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mt-2 {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}">
                                        <div class="form-group">
                                            <label for="si-password" class="bold s_20">{{\App\CPU\translate('Confirm Password')}}</label>
                                            <div>
                                                <input class="form-control" name="con_password" type="password" placeholder="{{\App\CPU\translate('Min: 8 digits')}}" id="si-password" required style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}}!important; ">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mt-3 {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}" dir="{{session('direction')}}">
                                        <input class="form-check-input" type="checkbox" value="" id="terms" required>
                                        <label class="form-check-label bold s_20 pr-4" for="terms"> {{\App\CPU\translate('I Agree to')}}  <a class="privacy" href="{{route('terms')}}"> {{\App\CPU\translate('Privacy Policy')}} </a> {{\App\CPU\translate('and')}} <a class="terms" href="{{route('privacy-policy')}}"> {{\App\CPU\translate('Terms of Use')}} </a> </label>
                                        @error('password')
                                        <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                                        @enderror
                                    </div>
                                    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mt-4 text-center">
                                        <button type="submit" class="s_27 bold p-3" style="border-radius: 41%; background: #ED165F; border: none"> <img src="{{asset('assets/front-end/img/r-arrow.png')}}" alt="r-arrow"> </button>
                                    </div>
                                    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mt-4 text-center"> <span class="s_19 bold"> {{\App\CPU\translate('Already Has Account ?')}} </span> <span class="bold s_19 second_color cursor-pointer" id="sign_in_exchange">{{\App\CPU\translate('Log in')}}</span> </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="login_modal" tabindex="-1" aria-labelledby="login_modal_label" aria-hidden="true" dir="rtl">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body pt-5">
                <div class="row pt-1">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute;left: 27px;top: 29px;font-weight: bold;font-size: 45px;color: #000;"> <span aria-hidden="true">&times;</span> </button>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center">
                        <h4 class="bold s_22"> {{\App\CPU\translate('Login')}} </h4>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-10 m-auto text-center login_with" style="position: relative; z-index: 101;background: #fff;">
                                <h4 class="bold s_20 second_color mt-0 py-4"> {{\App\CPU\translate('Login With')}} </h4>
                            </div>
                        </div>
                        <div class="row position-relative">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-12 m-auto text-center" style="position: absolute;top: -48px;right: 25%;z-index: 100;"><div style="height:5px;border-top: 5px solid #000;"></div></div></div>
                        <div class="row">
                            <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 m-auto py-2">
                                <a href="{{route('customer.auth.service-login', 'facebook')}}" class="login_by_btn bold"> {{\App\CPU\translate('Continue With Facebook')}} <img src="{{asset('assets/front-end/img/fb.png')}}" alt="fb-icon"> </a>
                            </div>
                            <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 m-auto py-2">
                                <a href="{{route('customer.auth.service-login', 'google')}}" class="login_by_btn bold"> {{\App\CPU\translate('Continue With Gmail')}} <img src="{{asset('assets/front-end/img/google.png')}}" alt="fb-icon"> </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 m-auto pt-3">
                                <span class="bold s_22"> {{\App\CPU\translate('Or')}} </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-11 col-xl-11 col-lg-11 col-md-11 col-sm-11 col-12 m-auto py-2">
                                <form autocomplete="off" action="{{route('customer.auth.loginFromModal')}}" method="post" id="form-id" class="row needs-validation mt-2">
                                    @csrf
                                    <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mt-2 {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}" dir="{{session('direction')}}">
                                        <label for="si-email" class="bold s_20">{{\App\CPU\translate('Email')}}</label>
                                        <input class="form-control @error('user_id') is-invalid @enderror" type="text" name="user_id" id="si-email" placeholder="{{\App\CPU\translate('Email')}}" value="{{ old('user_id') }}" required autocomplete="user_id" autofocus>
                                        @error('user_id')
                                        <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                                        @enderror
                                    </div>
                                    <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mt-2 {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}" dir="{{session('direction')}}">
                                        <label for="password_log" class="bold s_20">{{\App\CPU\translate('Password')}}</label>
                                        <input id="password_log" type="password" placeholder="{{\App\CPU\translate('Password')}}" class="form-control @error('password') is-invalid @enderror" name="password_log" required autocomplete="current-password">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span>
                                        @enderror
                                    </div>
                                    <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 mt-3 text-right">
                                        <input class="form-check-input" type="checkbox" value="" id="remember_me">
                                        <label class="form-check-label bold s_20 pr-4" for="remember_me"> {{\App\CPU\translate('Remember Me')}} </label>
                                    </div>
                                    <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 mt-3 text-left"> <a href="#" class="s_19 bold "> {{\App\CPU\translate('Forget Password ?')}} </a> </div>
                                    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mt-4 text-center">
                                        <button type="submit" class="s_27 bold p-3" style="border-radius: 41%; background: #ED165F; border: none"> <img src="{{asset('assets/front-end/img/r-arrow.png')}}" alt="r-arrow"> </button>
                                    </div>
                                    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mt-4 text-center">
                                        <span class="s_19 bold"> {{\App\CPU\translate('Dont Have Account ?')}} </span>
                                        <span class="bold s_19 second_color cursor-pointer" id="register_exchange">{{\App\CPU\translate('Create New Account')}}</span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


