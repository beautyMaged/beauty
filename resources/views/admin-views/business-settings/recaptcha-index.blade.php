@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('reCaptcha Setup'))

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

        <div class="row">
            <div class="col-12">
                <div class="card">
                    @php($config=\App\CPU\Helpers::get_business_settings('recaptcha'))
                    <form
                        action="{{env('APP_MODE')!='demo'?route('admin.business-settings.recaptcha_update',['recaptcha']):'javascript:'}}"
                        method="post">
                        @csrf
                        <div class="card-header flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <h5 class="mb-0">{{\App\CPU\translate('Google_Recapcha_Information')}}</h5>
                                <label class="switcher">
                                    <input class="switcher_input" type="checkbox" name="status" {{isset($config) && $config['status']==1?'checked':''}} value="1">
                                    <span class="switcher_control"></span>
                                </label>
                            </div>
                            <a href="https://www.google.com/recaptcha/admin/create" type="button"
                               class="btn btn-sm btn-outline--primary p-2">
                                <i class="tio-info-outined"></i> {{\App\CPU\translate('Credentials_SetUp_page')}}
                            </a>
                        </div>
                        <div class="card-body">
                            <div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="title-color font-weight-bold d-flex">{{\App\CPU\translate('Site Key')}}</label>
                                            <input type="text" class="form-control" name="site_key"
                                                   value="{{env('APP_MODE')!='demo'?$config['site_key']??"":''}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="title-color font-weight-bold d-flex">{{\App\CPU\translate('Secret Key')}}</label>
                                            <input type="text" class="form-control" name="secret_key"
                                                   value="{{env('APP_MODE')!='demo'?$config['secret_key']??"":''}}">
                                        </div>
                                    </div>
                                </div>

                                <h5 class="mb-3 d-flex">{{\App\CPU\translate('Instructions')}}</h5>
                                <ol class="pl-3 instructions-list">
                                    <li>
                                        {{\App\CPU\translate('To  get site key and secret keyGo to the Credentials page')}}
                                        (<a href="https://www.google.com/recaptcha/admin/create"
                                            target="_blank">{{\App\CPU\translate('Click_here')}}</a>)
                                    </li>
                                    <li>{{\App\CPU\translate('Add_a _abel_(Ex:_abc_company)')}}</li>
                                    <li>{{\App\CPU\translate('Select_reCAPTCHA_v2_as_ReCAPTCHA_Type')}}</li>
                                    <li>{{\App\CPU\translate('select_sub_type')}}: {{\App\CPU\translate('im_not_a_robot_checkbox ')}}</li>
                                    <li>{{\App\CPU\translate('Add_Domain_(For_ex:_demo.6amtech.com)')}}</li>
                                    <li>{{\App\CPU\translate('Check_in_“Accept_the_reCAPTCHA_Terms_of_Service”')}}</li>
                                    <li>{{\App\CPU\translate('Press_Submit')}}</li>
                                    <li>{{\App\CPU\translate('Copy_Site_Key_and_Secret_Key,_Paste_in_the_input_filed_below_and_Save.')}}</li>
                                </ol>

                                <div class="d-flex justify-content-end">
                                    <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                            onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                            class="btn btn--primary px-4">{{\App\CPU\translate('save')}}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')

@endpush
