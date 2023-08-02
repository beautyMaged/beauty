@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('analytics_script'))

@push('css_or_js')

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
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            @php($pixel_analytics=\App\CPU\Helpers::get_business_settings('pixel_analytics'))
                            <div class="col-12 mb-3">
                                <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.analytics-update'):'javascript:'}}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label class="title-color d-flex">{{\App\CPU\translate('pixel_analytics_your_pixel_id')}}</label>
                                        <textarea type="text" placeholder="{{\App\CPU\translate('pixel_analytics_your_pixel_id_from_facebook')}}" class="form-control" name="pixel_analytics">{{env('APP_MODE')!='demo'?$pixel_analytics??'':''}}</textarea>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn--primary px-4">{{\App\CPU\translate('save')}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            @php($google_tag_manager_id=\App\CPU\Helpers::get_business_settings('google_tag_manager_id'))
                                <div class="col-12">
                                    <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.analytics-update-google-tag'):'javascript:'}}" method="post"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label class="title-color d-flex">{{\App\CPU\translate('google_tag_manager_id')}}</label>
                                            <textarea type="text" placeholder="{{\App\CPU\translate('google_tag_manager_script_id_from_google')}}" class="form-control" name="google_tag_manager_id">{{env('APP_MODE')!='demo'?$google_tag_manager_id??'':''}}</textarea>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn--primary px-4">{{\App\CPU\translate('save')}}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('script_2')

@endpush
