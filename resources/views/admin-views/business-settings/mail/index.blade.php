@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Mail Config'))
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

        <div class="row" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-10">
                                <button class="btn btn--primary" type="button" data-toggle="collapse"
                                        data-target="#collapseExample" aria-expanded="false"
                                        aria-controls="collapseExample">
                                    <i class="tio-email-outlined"></i>
                                    {{\App\CPU\translate('test_your_email_integration')}}
                                </button>
                            </div>
                            <div class="col-2 d-flex justify-content-end">
                                <i class="tio-telegram"></i>
                            </div>
                        </div>

                        <div class="collapse" id="collapseExample">
                            <div class="card card-body">
                                <form class="" action="javascript:">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="form-group mb-2">
                                                <label for="inputPassword2"
                                                       class="sr-only">{{\App\CPU\translate('mail')}}</label>
                                                <input type="email" id="test-email" class="form-control"
                                                       placeholder="Ex : jhon@email.com">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <button type="button" onclick="send_mail()" class="btn btn--primary mb-2 btn-block">
                                                <i class="tio-telegram"></i>
                                                {{\App\CPU\translate('send_mail')}}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 mb-3 mb-sm-0">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">{{\App\CPU\translate('smtp_mail_config')}}</h5>
                    </div>
                    <div class="card-body">
                        @php($data_smtp=\App\CPU\Helpers::get_business_settings('mail_config'))
                        <form action="{{route('admin.business-settings.mail.update')}}"
                              method="post">
                            @csrf
                            @if(isset($data_smtp))

                                <label class="mb-3 d-block title-color">{{\App\CPU\translate('smtp_mail')}}</label>

                                <div class="d-flex gap-10 align-items-center mb-2">
                                    <input type="radio" name="status"
                                           value="1" {{$data_smtp['status']==1?'checked':''}}>
                                    <label class="title-color mb-0">{{\App\CPU\translate('Active')}}</label>
                                    <br>
                                </div>
                                <div class="d-flex gap-10 align-items-center mb-4">
                                    <input type="radio" name="status"
                                           value="0" {{$data_smtp['status']==0?'checked':''}}>
                                    <label class="title-color mb-0">{{\App\CPU\translate('Inactive')}}</label>
                                    <br>
                                </div>
                                <div class="form-group">
                                    <label class="title-color">{{\App\CPU\translate('mailer_name')}}</label><br>
                                    <input type="text" placeholder="ex : Alex" class="form-control" name="name"
                                           value="{{env('APP_MODE')=='demo'?'':$data_smtp['name']}}">
                                </div>

                                <div class="form-group">
                                    <label class="title-color">{{\App\CPU\translate('Host')}}</label><br>
                                    <input type="text" class="form-control" name="host"
                                           value="{{env('APP_MODE')=='demo'?'':$data_smtp['host']}}">
                                </div>
                                <div class="form-group">
                                    <label class="title-color">{{\App\CPU\translate('Driver')}}</label><br>
                                    <input type="text" class="form-control" name="driver"
                                           value="{{env('APP_MODE')=='demo'?'':$data_smtp['driver']}}">
                                </div>
                                <div class="form-group">
                                    <label class="title-color">{{\App\CPU\translate('port')}}</label><br>
                                    <input type="text" class="form-control" name="port"
                                           value="{{env('APP_MODE')=='demo'?'':$data_smtp['port']}}">
                                </div>

                                <div class="form-group">
                                    <label class="title-color">{{\App\CPU\translate('Username')}}</label><br>
                                    <input type="text" placeholder="ex : ex@yahoo.com" class="form-control"
                                           name="username"
                                           value="{{env('APP_MODE')=='demo'?'':$data_smtp['username']}}">
                                </div>

                                <div class="form-group">
                                    <label class="title-color">{{\App\CPU\translate('email_id')}}</label><br>
                                    <input type="text" placeholder="ex : ex@yahoo.com" class="form-control" name="email"
                                           value="{{env('APP_MODE')=='demo'?'':$data_smtp['email_id']}}">
                                </div>

                                <div class="form-group">
                                    <label class="title-color">{{\App\CPU\translate('Encryption')}}</label><br>
                                    <input type="text" placeholder="ex : tls" class="form-control" name="encryption"
                                           value="{{env('APP_MODE')=='demo'?'':$data_smtp['encryption']}}">
                                </div>

                                <div class="form-group">
                                    <label class="title-color">{{\App\CPU\translate('password')}}</label><br>
                                    <input type="text" class="form-control" name="password"
                                           value="{{env('APP_MODE')=='demo'?'':$data_smtp['password']}}">
                                </div>
                                <div class="d-flex flex-wrap justify-content-end gap-10">
                                    <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                            onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                            class="btn btn--primary px-4">{{\App\CPU\translate('save')}}</button>
                                    @else
                                        <button type="submit"
                                                class="btn btn--primary px-4">{{\App\CPU\translate('Configure')}}</button>
                                    @endif
                                </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">{{\App\CPU\translate('sendgrid_mail_config')}}</h5>
                    </div>
                    <div class="card-body">
                        @php($data_sendgrid=\App\CPU\Helpers::get_business_settings('mail_config_sendgrid'))
                        <form action="{{route('admin.business-settings.mail.update-sendgrid')}}"
                              method="post">
                            @csrf
                            @if(isset($data_sendgrid))
                            <label class="mb-3 d-block title-color">{{\App\CPU\translate('sendgrid_mail')}}</label>

                            <div class="d-flex gap-10 align-items-center mb-2">
                                <input type="radio" name="status"
                                        value="1" {{$data_sendgrid['status']==1?'checked':''}}>
                                <label class="title-color mb-0">{{\App\CPU\translate('Active')}}</label>
                            </div>
                            <div class="d-flex gap-10 align-items-center mb-4">
                                <input type="radio" name="status"
                                        value="0" {{$data_sendgrid['status']==0?'checked':''}}>
                                <label class="title-color mb-0">{{\App\CPU\translate('Inactive')}}</label>
                            </div>
                            <div class="form-group">
                                <label class="title-color">{{\App\CPU\translate('mailer_name')}}</label>
                                <input type="text" placeholder="ex : Alex" class="form-control" name="name"
                                        value="{{env('APP_MODE')=='demo'?'':$data_sendgrid['name']}}">
                            </div>

                            <div class="form-group">
                                <label class="title-color">{{\App\CPU\translate('Host')}}</label>
                                <input type="text" class="form-control" name="host"
                                        value="{{env('APP_MODE')=='demo'?'':$data_sendgrid['host']}}">
                            </div>
                            <div class="form-group">
                                <label class="title-color">{{\App\CPU\translate('Driver')}}</label>
                                <input type="text" class="form-control" name="driver"
                                        value="{{env('APP_MODE')=='demo'?'':$data_sendgrid['driver']}}">
                            </div>
                            <div class="form-group">
                                <label class="title-color">{{\App\CPU\translate('port')}}</label>
                                <input type="text" class="form-control" name="port"
                                        value="{{env('APP_MODE')=='demo'?'':$data_sendgrid['port']}}">
                            </div>

                            <div class="form-group">
                                <label class="title-color">{{\App\CPU\translate('Username')}}</label>
                                <input type="text" placeholder="ex : ex@yahoo.com" class="form-control"
                                        name="username"
                                        value="{{env('APP_MODE')=='demo'?'':$data_sendgrid['username']}}">
                            </div>

                            <div class="form-group">
                                <label class="title-color">{{\App\CPU\translate('email_id')}}</label>
                                <input type="text" placeholder="ex : ex@yahoo.com" class="form-control" name="email"
                                        value="{{env('APP_MODE')=='demo'?'':$data_sendgrid['email_id']}}">
                            </div>

                            <div class="form-group">
                                <label class="title-color">{{\App\CPU\translate('Encryption')}}</label>
                                <input type="text" placeholder="ex : tls" class="form-control" name="encryption"
                                        value="{{env('APP_MODE')=='demo'?'':$data_sendgrid['encryption']}}">
                            </div>

                            <div class="form-group">
                                <label class="title-color">{{\App\CPU\translate('password')}}</label>
                                <input type="text" class="form-control" name="password"
                                        value="{{env('APP_MODE')=='demo'?'':$data_sendgrid['password']}}">
                            </div>
                            <div class="d-flex flex-wrap justify-content-end gap-10">
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                        onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                        class="btn btn--primary px-4">{{\App\CPU\translate('save')}}</button>
                                @else
                                    <button type="submit"
                                            class="btn btn--primary px-4">{{\App\CPU\translate('Configure')}}</button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function ValidateEmail(inputText) {
            var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            if (inputText.match(mailformat)) {
                return true;
            } else {
                return false;
            }
        }

        function send_mail() {
            if (ValidateEmail($('#test-email').val())) {
                Swal.fire({
                    title: '{{\App\CPU\translate('Are you sure?')}}?',
                    text: "{{\App\CPU\translate('a_test_mail_will_be_sent_to_your_email')}}!",
                    showCancelButton: true,
                    confirmButtonColor: '#377dff',
                    cancelButtonColor: 'secondary',
                    confirmButtonText: '{{\App\CPU\translate('Yes')}}!'
                }).then((result) => {
                    if (result.value) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{route('admin.business-settings.mail.send')}}",
                            method: 'POST',
                            data: {
                                "email": $('#test-email').val()
                            },
                            beforeSend: function () {
                                $('#loading').show();
                            },
                            success: function (data) {
                                if (data.success === 2) {
                                    toastr.error('{{\App\CPU\translate('email_configuration_error')}} !!');
                                } else if (data.success === 1) {
                                    toastr.success('{{\App\CPU\translate('email_configured_perfectly!')}}!');
                                } else {
                                    toastr.info('{{\App\CPU\translate('email_status_is_not_active')}}!');
                                }
                            },
                            complete: function () {
                                $('#loading').hide();

                            }
                        });
                    }
                })
            } else {
                toastr.error('{{\App\CPU\translate('invalid_email_address')}} !!');
            }
        }
    </script>
@endpush
