@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('environment_setup'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Title -->
    <div class="mb-4 pb-2">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img src="{{asset('/public/assets/back-end/img/system-setting.png')}}" alt="">
            {{\App\CPU\translate('System_Setup')}}
        </h2>
    </div>
    <!-- End Page Title -->

    <!-- Inlile Menu -->
    @include('admin-views.business-settings.system-settings-inline-menu')
    <!-- End Inlile Menu -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="border-bottom px-4 py-3">
                    <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                        <img width="20" src="{{asset('/public/assets/back-end/img/environment.png')}}" alt="">
                        {{\App\CPU\translate('Environment_Information')}}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{route('admin.business-settings.web-config.update-environment')}}" method="post"
                            enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="title-color d-flex">{{\App\CPU\translate('APP_NAME')}}</label>
                                    <input type="text" value="{{ env('APP_NAME') }}"
                                            name="app_name" class="form-control"
                                            placeholder="Ex : EFood" required disabled>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label class="title-color d-flex">{{\App\CPU\translate('APP_DEBUG')}}</label>
                                    <select name="app_debug" class="form-control js-select2-custom">
                                        <option value="true" {{env('APP_DEBUG')==1?'selected':''}}>
                                            {{\App\CPU\translate('True')}}
                                        </option>
                                        <option value="false" {{env('APP_DEBUG')==0?'selected':''}}>
                                            {{\App\CPU\translate('False')}}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label class="title-color d-flex">{{\App\CPU\translate('APP_MODE')}}</label>
                                    <select name="app_mode" class="form-control js-select2-custom">
                                        <option value="live" {{env('APP_MODE')=='live'?'selected':''}}>
                                            {{\App\CPU\translate('Live')}}
                                        </option>
                                        <option value="dev" {{env('APP_MODE')=='dev'?'selected':''}}>
                                            {{\App\CPU\translate('Dev')}}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label class="title-color d-flex">{{\App\CPU\translate('APP_URL')}}</label>
                                    <input type="text" value="{{ env('APP_URL') }}"
                                            name="app_url" class="form-control"
                                            placeholder="Ex : http://localhost" required disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label class="title-color d-flex">{{\App\CPU\translate('DB_CONNECTION')}}</label>
                                    <input type="text" value="{{ env('APP_MODE') != 'demo' ? env('DB_CONNECTION') : '---' }}"
                                            name="db_connection" class="form-control"
                                            placeholder="Ex : mysql" required disabled>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label class="title-color d-flex">{{\App\CPU\translate('DB_HOST')}}</label>
                                    <input type="text" value="{{ env('APP_MODE') != 'demo' ? env('DB_HOST') : '---' }}"
                                            name="db_host" class="form-control"
                                            placeholder="Ex : http://localhost/" required disabled>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label class="title-color d-flex">{{\App\CPU\translate('DB_PORT')}}</label>
                                    <input type="text" value="{{ env('APP_MODE') != 'demo' ? env('DB_PORT') : '---' }}"
                                            name="db_port" class="form-control"
                                            placeholder="Ex : 3306" required disabled>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label class="title-color d-flex">{{\App\CPU\translate('DB_DATABASE')}}</label>
                                    <input type="text" value="{{ env('APP_MODE') != 'demo' ? env('DB_DATABASE') : '---' }}"
                                            name="db_database" class="form-control"
                                            placeholder="Ex : demo_db" required disabled>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label class="title-color d-flex">{{\App\CPU\translate('DB_USERNAME')}}</label>
                                    <input type="text" value="{{ env('APP_MODE') != 'demo' ? env('DB_USERNAME') : '---' }}"
                                            name="db_username" class="form-control"
                                            placeholder="Ex : root" required disabled>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label class="title-color d-flex">{{\App\CPU\translate('DB_PASSWORD')}}</label>
                                    <input type="text" value="{{ env('APP_MODE') != 'demo' ? env('DB_PASSWORD') : '---' }}"
                                            name="db_password" class="form-control"
                                            placeholder="Ex : password" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="title-color d-flex">{{\App\CPU\translate('BUYER_USERNAME')}}</label>

                                    <input type="text" value="{{ env('BUYER_USERNAME') }}" class="form-control"
                                            disabled>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group" id="purchase_code_div">
                                    <label class="title-color d-flex">{{\App\CPU\translate('PURCHASE_CODE')}}</label>
                                    <div class="input-icons">
                                        <input type="password" value="{{ env('PURCHASE_CODE') }}" class="form-control" id="purchase_code" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-end flex-wrap gap-10">
                            <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                class="btn btn--primary px-4">{{\App\CPU\translate('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')

@endpush
