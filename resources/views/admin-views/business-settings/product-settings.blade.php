@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('product_settings'))

@push('css_or_js')
    <link href="{{ asset('assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/back-end/css/custom.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/assets/back-end/img/business-setup.png')}}" alt="">
                {{\App\CPU\translate('Business_Setup')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
        @include('admin-views.business-settings.business-setup-inline-menu')
        <!-- End Inlile Menu -->

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.product-settings.stock-limit-warning') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            @php($stock_limit=\App\Model\BusinessSetting::where('type','stock_limit')->first())
                            <div class="form-group">
                                <label class="title-color d-flex">{{\App\CPU\translate('minimum_stock_limit_for_warning')}}</label>
                                <input class="form-control" type="number" name="stock_limit"
                                        value="{{ $stock_limit->value?$stock_limit->value:"" }}"
                                        placeholder="{{\App\CPU\translate('EX:123')}}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex">
                                <button type="submit" class="btn btn--primary px-4">{{\App\CPU\translate('submit')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-6 mt-2 mt-md-0">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{\App\CPU\translate('Digital Product')}}</h5>
                    </div>

                    <div class="card-body">
                        <form action="{{route('admin.product-settings.update-digital-product')}}"
                              method="post">
                            @csrf
                            <label class="title-color d-flex mb-3">{{\App\CPU\translate('Digital Product on/off')}}</label>
                            <div class="d-flex gap-2 align-items-center mb-2">
                                <input class="" name="digital_product" type="radio" value="1"
                                       id="defaultCheck1" {{$digital_product==1?'checked':''}}>
                                <label class="title-color mb-0" for="defaultCheck1">
                                    {{\App\CPU\translate('Turn on')}}
                                </label>
                            </div>
                            <div class="d-flex gap-2 align-items-center">
                                <input class="" name="digital_product" type="radio" value="0"
                                       id="defaultCheck2" {{$digital_product==0?'checked':''}}>
                                <label class="title-color mb-0" for="defaultCheck2">
                                    {{\App\CPU\translate('Turn off')}}
                                </label>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn--primary">{{\App\CPU\translate('Save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mt-2 mt-md-0">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{\App\CPU\translate('Product_Brand')}}</h5>
                    </div>

                    <div class="card-body">
                        <form action="{{route('admin.product-settings.update-product-brand')}}"
                              method="post">
                            @csrf
                            <label class="title-color d-flex mb-3">{{\App\CPU\translate('Product Brand on/off')}}</label>
                            <div class="d-flex gap-2 align-items-center mb-2">
                                <input class="" name="product_brand" type="radio" value="1"
                                       id="defaultCheck3" {{$brand==1?'checked':''}}>
                                <label class="title-color mb-0" for="defaultCheck3">
                                    {{\App\CPU\translate('Turn on')}}
                                </label>
                            </div>
                            <div class="d-flex gap-2 align-items-center">
                                <input class="" name="product_brand" type="radio" value="0"
                                       id="defaultCheck4" {{$brand==0?'checked':''}}>
                                <label class="title-color mb-0" for="defaultCheck4">
                                    {{\App\CPU\translate('Turn off')}}
                                </label>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn--primary">{{\App\CPU\translate('Save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
