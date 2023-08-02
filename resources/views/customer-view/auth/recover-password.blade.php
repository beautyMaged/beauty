@extends('layouts.front-end.app')
@section('title', \App\CPU\translate('Forgot Password'))
@push('css_or_js')
    <style>
        .text-primary {
            color: {{$web_config['primary_color']}}  !important;
        }
    </style>
@endpush

@section('content')
    @php($verification_by=\App\CPU\Helpers::get_business_settings('forgot_password_verification'))
    <!-- Page Content-->
    <div class="container py-4 py-lg-5 my-4 rtl">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 text-start">
                <h2 class="h3 mb-4">{{\App\CPU\translate('Forgot your password')}}?</h2>
                <p class="font-size-md">{{\App\CPU\translate('Change your password in three easy steps. This helps to keep your new password secure')}}
                    .</p>
                    <ol class="list-unstyled font-size-md p-0">
                        <li><span
                                class="text-primary mr-2">{{\App\CPU\translate('1')}}.</span>{{\App\CPU\translate('Fill in your email address below')}}
                            .
                        </li>
                        <li><span
                                class="text-primary mr-2">{{\App\CPU\translate('2')}}.</span>{{\App\CPU\translate('We will email you a temporary code')}}
                            .
                        </li>
                        <li><span
                                class="text-primary mr-2">{{\App\CPU\translate('3')}}.</span>{{\App\CPU\translate('Use the code to change your password on our secure website')}}
                            .
                        </li>
                    </ol>
                @if($verification_by=='email')

                    <div class="card py-2 mt-4">
                        <form class="card-body needs-validation" action="{{route('customer.auth.forgot-password')}}"
                              method="post">
                            @csrf
                            <div class="form-group">
                                <label for="recover-email">{{\App\CPU\translate('Enter your email address')}}</label>
                                <input class="form-control" type="email" name="identity" id="recover-email" required>
                                <div
                                    class="invalid-feedback">{{\App\CPU\translate('Please provide valid email address')}}
                                    .
                                </div>
                            </div>
                            <button class="btn btn--primary"
                                    type="submit">{{\App\CPU\translate('Get new password')}}</button>
                        </form>
                    </div>
                @else
                    <div class="card py-2 mt-4">
                        <form class="card-body needs-validation" action="{{route('customer.auth.forgot-password')}}"
                              method="post">
                            @csrf
                            <div class="form-group">
                                <label for="recover-email">{{\App\CPU\translate('Enter your phone number')}}</label>
                                <input class="form-control" type="text" name="identity" required>
                                <div
                                    class="invalid-feedback">{{\App\CPU\translate('Please provide valid phone number')}}
                                </div>
                            </div>
                            <button class="btn btn--primary"
                                    type="submit">{{\App\CPU\translate('proceed')}}</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
