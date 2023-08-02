@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Social-Media-Chatting'))

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

        @php($messenger = \App\CPU\Helpers::get_business_settings('messenger'))
        <div class="row gy-3">
{{--                <div class="col-lg-6">--}}
{{--                    <div class="card">--}}
{{--                        <div class="card-body text-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}">--}}
{{--                            <form--}}
{{--                                action="{{route('admin.social-media-chat.update',['messenger'])}}"--}}
{{--                                method="post">--}}
{{--                                @csrf--}}
{{--                                <div class="d-flex flex-column align-items-center gap-2 mb-3">--}}
{{--                                    <h4 class="text-center">{{\App\CPU\translate('messenger')}}</h4>--}}
{{--                                </div>--}}
{{--                                @if($messenger)--}}
{{--                                    <label class="switcher position-absolute right-3 top-3">--}}
{{--                                        <input class="switcher_input" type="checkbox" value="1" name="status" {{$messenger['status']==1?'checked':''}}>--}}
{{--                                        <span class="switcher_control"></span>--}}
{{--                                    </label>--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label class="title-color font-weight-bold text-capitalize">{{\App\CPU\translate('paste_script')}}</label>--}}
{{--                                        <textarea class="form-control" rows="8" name="script">--}}
{{--                                            {{ $messenger['script'] }}--}}
{{--                                        </textarea>--}}
{{--                                    </div>--}}
{{--                                    <div class="d-flex justify-content-end flex-wrap">--}}
{{--                                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" class="btn btn--primary px-4">{{\App\CPU\translate('save')}}</button>--}}
{{--                                    </div>--}}
{{--                                @else--}}
{{--                                    <div class="mt-3 d-flex flex-wrap justify-content-center gap-10">--}}
{{--                                        <button type="submit" class="btn btn--primary px-4 text-uppercase">{{\App\CPU\translate('Configure')}}</button>--}}
{{--                                    </div>--}}
{{--                                @endif--}}
{{--                            </form>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

                @php($whatsapp = \App\CPU\Helpers::get_business_settings('whatsapp'))
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body text-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}">
                            <form
                                action="{{route('admin.social-media-chat.update',['whatsapp'])}}"
                                method="post">
                                @csrf
                                <div class="d-flex flex-column align-items-center gap-2 mb-3">
                                    <h4 class="text-center">{{\App\CPU\translate('WhatsApp')}}</h4>
                                </div>
                                @if($whatsapp)
                                    <label class="switcher position-absolute right-3 top-3">
                                        <input class="switcher_input" type="checkbox" value="1" name="status" {{$whatsapp['status']==1?'checked':''}}>
                                        <span class="switcher_control"></span>
                                    </label>
                                    <div class="form-group">
                                        <label class="title-color font-weight-bold text-capitalize">{{\App\CPU\translate('whatsapp_phone_number')}}</label>
                                        <span class="ml-2" data-toggle="tooltip" data-placement="top" title="{{\App\CPU\translate('provide_a_WhatsApp_number_without_country_code')}}">
                                            <img class="info-img" src="{{asset('/assets/back-end/img/info-circle.svg')}}" alt="img">
                                        </span>
                                        <input type="text" class="form-control form-ellipsis" name="phone" value="{{ $whatsapp['phone'] }}">
                                    </div>
                                    <div class="d-flex justify-content-end flex-wrap">
                                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" class="btn btn--primary px-4">{{\App\CPU\translate('save')}}</button>
                                    </div>
                                @else
                                    <div class="mt-3 d-flex flex-wrap justify-content-center gap-10">
                                        <button type="submit" class="btn btn--primary px-4 text-uppercase">{{\App\CPU\translate('Configure')}}</button>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
        </div>
    </div>
@endsection

@push('script')
@endpush
