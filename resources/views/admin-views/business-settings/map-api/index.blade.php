@extends('layouts.back-end.app')

@section('title', trans('messages.third_party_apis'))

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

        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3 mt-2">
        @php($map_api_key=\App\CPU\Helpers::get_business_settings('map_api_key'))
        @php($map_api_key_server=\App\CPU\Helpers::get_business_settings('map_api_key_server'))

            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.map-api-update'):'javascript:'}}" method="post"
                          enctype="multipart/form-data">
                        @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="title-color d-flex">{{\App\CPU\translate('map_api_key')}} ({{\App\CPU\translate('client')}})</label>
                                        <input type="text" placeholder="{{\App\CPU\translate('map_api_key')}} ({{\App\CPU\translate('client')}})" class="form-control" name="map_api_key" value="{{env('APP_MODE')!='demo'?$map_api_key??'':''}}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="title-color d-flex">{{\App\CPU\translate('map_api_key')}} ({{\App\CPU\translate('server')}})</label>
                                        <input type="text" placeholder="{{\App\CPU\translate('map_api_key')}} ({{\App\CPU\translate('server')}})" class="form-control" name="map_api_key_server"
                                            value="{{env('APP_MODE')!='demo'?$map_api_key_server??'':''}}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn--primary px-4">{{\App\CPU\translate('save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')

@endpush
