@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Social Login'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/assets/back-end/img/3rd-party.png')}}" alt="">
                {{\App\CPU\translate('3rd_party')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
        @include('admin-views.business-settings.third-party-inline-menu')
        <!-- End Inlile Menu -->

        <?php
        $data = App\Model\BusinessSetting::where(['type' => 'social_login'])->first();
        $socialLoginServices = json_decode($data['value'], true);
        ?>
        <div class="row gy-3">
            @if (isset($socialLoginServices))
                @foreach ($socialLoginServices as $socialLoginService)
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body text-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}">
                                <form
                                    action="{{route('admin.social-login.update',[$socialLoginService['login_medium']])}}"
                                    method="post">
                                    @csrf
                                    <label class="switcher position-absolute right-3 top-3">
                                        <input class="switcher_input" type="checkbox" {{$socialLoginService['status']==1?'checked':''}} value="1" name="status">
                                        <span class="switcher_control"></span>
                                    </label>

                                    <div class="d-flex flex-column align-items-center gap-2 mb-3">
                                        <h4 class="text-center">{{\App\CPU\translate($socialLoginService['login_medium'])}}</h4>
                                    </div>

                                    <div class="form-group">
                                        <label class="title-color font-weight-bold text-capitalize">{{\App\CPU\translate('Callback_URI')}}</label>
                                        <div class="form-control d-flex align-items-center justify-content-between py-1 pl-3 pr-2">
                                            <span class="form-ellipsis" id="id_{{$socialLoginService['login_medium']}}">{{ url('/') }}/customer/auth/login/{{$socialLoginService['login_medium']}}/callback</span>
                                            <span class="btn btn--primary text-nowrap btn-xs" onclick="copyToClipboard('#id_{{$socialLoginService['login_medium']}}')">
                                        <i class="tio-copy"></i>
                                        {{\App\CPU\translate('Copy URI')}}
                                    </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="title-color font-weight-bold text-capitalize">{{\App\CPU\translate('Store_Client_ID')}}</label><br>
                                        <input type="text" class="form-control form-ellipsis" name="client_id"
                                               value="{{ $socialLoginService['client_id'] }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="title-color font-weight-bold text-capitalize">{{\App\CPU\translate('Store_Client_Secret_Key')}}</label>
                                        <input type="text" class="form-control form-ellipsis" name="client_secret"
                                               value="{{ $socialLoginService['client_secret'] }}">
                                    </div>
                                    <div class="d-flex justify-content-between flex-wrap gap-2">
                                        <button class="btn btn-outline--primary" type="button" data-toggle="modal" data-target="#{{$socialLoginService['login_medium']}}-modal">
                                            {{\App\CPU\translate('See_Credential_Setup_Instructions')}}
                                        </button>
                                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" class="btn btn--primary px-4">{{\App\CPU\translate('save')}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        {{-- Modal Starts--}}
        <!-- Google -->
        <div class="modal fade" id="google-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">{{\App\CPU\translate('Google API Set up Instructions')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ol>
                            <li>{{\App\CPU\translate('Go to the Credentials page')}} ({{\App\CPU\translate('Click')}} <a href="https://console.cloud.google.com/apis/credentials" target="_blank">{{\App\CPU\translate('here')}}</a>)</li>
                            <li>{{\App\CPU\translate('Click')}} <b>{{\App\CPU\translate('Create credentials')}}</b> > <b>{{\App\CPU\translate('OAuth client ID')}}</b>.</li>
                            <li>{{\App\CPU\translate('Select the')}} <b>{{\App\CPU\translate('Web application')}}</b> {{\App\CPU\translate('type')}}.</li>
                            <li>{{\App\CPU\translate('Name your OAuth 2.0 client')}}</li>
                            <li>{{\App\CPU\translate('Click')}} <b>{{\App\CPU\translate('ADD URI')}}</b> {{\App\CPU\translate('from')}} <b>{{\App\CPU\translate('Authorized redirect URIs')}}</b> , {{\App\CPU\translate('provide the')}} <code>{{\App\CPU\translate('Callback URI')}}</code> {{\App\CPU\translate('from below and click')}} <b>{{\App\CPU\translate('Create')}}</b></li>
                            <li>{{\App\CPU\translate('Copy')}} <b>{{\App\CPU\translate('Client ID')}}</b> {{\App\CPU\translate('and')}} <b>{{\App\CPU\translate('Client Secret')}}</b>, {{\App\CPU\translate('paste in the input filed below and')}} <b>{{\App\CPU\translate('Save')}}</b>.</li>
                        </ol>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--primary" data-dismiss="modal">{{\App\CPU\translate('Close')}}</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Facebook -->
        <div class="modal fade" id="facebook-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">{{\App\CPU\translate('Facebook API Set up Instructions')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"><b></b>
                        <ol>
                            <li>{{\App\CPU\translate('Go to the facebook developer page')}} (<a href="https://developers.facebook.com/apps/" target="_blank">{{\App\CPU\translate('Click Here')}}</a>)</li>
                            <li>{{\App\CPU\translate('Go to')}} <b>{{\App\CPU\translate('Get Started')}}</b> {{\App\CPU\translate('from Navbar')}}</li>
                            <li>{{\App\CPU\translate('From Register tab press')}} <b>{{\App\CPU\translate('Continue')}}</b> <small>({{\App\CPU\translate('If needed')}})</small></li>
                            <li>{{\App\CPU\translate('Provide Primary Email and press')}} <b>{{\App\CPU\translate('Confirm Email')}}</b> <small>({{\App\CPU\translate('If needed')}})</small></li>
                            <li>{{\App\CPU\translate('In about section select')}} <b>{{\App\CPU\translate('Other')}}</b> {{\App\CPU\translate('and press')}} <b>{{\App\CPU\translate('Complete Registration')}}</b></li>

                            <li><b>{{\App\CPU\translate('Create App')}}</b> > {{\App\CPU\translate('Select an app type and press')}} <b>{{\App\CPU\translate('Next')}}</b></li>
                            <li>{{\App\CPU\translate('Complete the add details form and press')}} <b>{{\App\CPU\translate('Create App')}}</b></li><br/>

                            <li>{{\App\CPU\translate('From')}} <b>{{\App\CPU\translate('Facebook Login')}}</b> {{\App\CPU\translate('press')}} <b>{{\App\CPU\translate('Set Up')}}</b></li>
                            <li>{{\App\CPU\translate('Select')}} <b>{{\App\CPU\translate('Web')}}</b></li>
                            <li>{{\App\CPU\translate('Provide')}} <b>{{\App\CPU\translate('Site URL')}}</b> <small>({{\App\CPU\translate('Base URL of the site ex:')}} https://example.com)</small> > <b>{{\App\CPU\translate('Save')}}</b></li><br/>
                            <li>{{\App\CPU\translate('Now go to')}} <b>{{\App\CPU\translate('Setting')}}</b> {{\App\CPU\translate('from')}} <b>{{\App\CPU\translate('Facebook Login')}}</b> ({{\App\CPU\translate('left sidebar')}})</li>
                            <li>{{\App\CPU\translate('Make sure to check')}} <b>{{\App\CPU\translate('Client OAuth Login')}}</b> <small>({{\App\CPU\translate('must on')}})</small></li>
                            <li>{{\App\CPU\translate('Provide')}} <code>{{\App\CPU\translate('Valid OAuth Redirect URIs')}}</code> {{\App\CPU\translate('from below and click')}} <b>{{\App\CPU\translate('Save Changes')}}</b></li>

                            <li>{{\App\CPU\translate('Now go to')}} <b>{{\App\CPU\translate('Setting')}}</b> ({{\App\CPU\translate('from left sidebar')}}) > <b>{{\App\CPU\translate('Basic')}}</b></li>
                            <li>{{\App\CPU\translate('Fill the form and press')}} <b>{{\App\CPU\translate('Save Changes')}}</b></li>
                            <li>{{\App\CPU\translate('Now, copy')}} <b>{{\App\CPU\translate('Client ID')}}</b> & <b>{{\App\CPU\translate('Client Secret')}}</b>, {{\App\CPU\translate('paste in the input filed below and')}} <b>{{\App\CPU\translate('Save')}}</b>.</li>
                        </ol>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--primary float-right" data-dismiss="modal">{{\App\CPU\translate('Close')}}</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Twitter -->
        <div class="modal fade" id="twitter-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">{{\App\CPU\translate('Twitter API Set up Instructions')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"><b></b>
                        {{\App\CPU\translate('Instruction will be available very soon')}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--primary float-right" data-dismiss="modal">{{\App\CPU\translate('Close')}}</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Modal Ends--}}
    </div>
@endsection

@push('script')
    <script>
        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();

            toastr.success("{{\App\CPU\translate('Copied to the clipboard')}}");
        }
    </script>

@endpush
