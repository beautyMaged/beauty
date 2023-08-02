@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('cookie_settings'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">

        <!-- Page Title -->
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/assets/back-end/img/business-setup.png')}}" alt="">
                {{\App\CPU\translate('Business_Setup')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
    @include('admin-views.business-settings.business-setup-inline-menu')
    <!-- End Inlile Menu -->
        <form action="{{ route('admin.business-settings.cookie-settings-update') }}" method="post"
              enctype="multipart/form-data" id="update-settings">
            @csrf
            <div class="row gy-2 pb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="border-bottom py-3 px-4">
                            <div class="d-flex justify-content-between align-items-center gap-10">
                                <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                                    <i class="tio-award"></i>
                                    {{\App\CPU\translate('cookie_settings')}}:
                                </h5>
                                <label class="switcher" for="cookie_setting_status">
                                    <input type="checkbox" class="switcher_input"
                                           name="status" id="cookie_setting_status"
                                           data-section="cookie_setting_status"
                                           value="1" {{isset($data['cookie_setting'])&&$data['cookie_setting']['status']==1?'checked':''}}>
                                    <span class="switcher_control"></span>
                                </label>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="loyalty-point-section" id="cookie_setting_status_section">
                                <div class="form-group">
                                    <label class="title-color d-flex"
                                           for="loyalty_point_exchange_rate">{{\App\CPU\translate('cookie_text')}}</label>
                                    <textarea name="cookie_text" id="" cols="30" rows="6" class="form-control">{{isset($data['cookie_setting']) ? $data['cookie_setting']['cookie_text'] : ''}}</textarea>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" id="submit"
                                            class="btn px-4 btn--primary">{{\App\CPU\translate('save')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
