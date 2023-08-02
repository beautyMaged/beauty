@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Update Currency'))

@push('css_or_js')

@endpush

@section('content')
    @php($currency_model=\App\CPU\Helpers::get_business_settings('currency_model'))
    <div class="content container-fluid">
        <!-- <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{route('admin.dashboard')}}">{{\App\CPU\translate('Dashboard')}}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('Currency')}}</li>
            </ol>
        </nav> -->
        <!-- Page Heading -->
    
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/coupon_setup.png')}}" alt="">
                {{\App\CPU\translate('Currency_update')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="tio-money"></i>
                            {{\App\CPU\translate('Update Currency')}}
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.currency.update',[$data['id']])}}" method="post"
                              style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                            @csrf
                            <div class="">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="title-color">{{\App\CPU\translate('Currency Name')}} :</label>
                                        <input type="text" name="name"
                                               placeholder="{{\App\CPU\translate('Currency Name')}}"
                                               class="form-control" id="name"
                                               value="{{$data->name}}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="title-color">{{\App\CPU\translate('Currency Symbol')}} :</label>
                                        <input type="text" name="symbol"
                                               placeholder="{{\App\CPU\translate('Currency Symbol')}}"
                                               class="form-control" id="symbol"
                                               value="{{$data->symbol}}">
                                    </div>
                                </div>

                            </div>
                            <div class="">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="title-color">{{\App\CPU\translate('Currency Code')}} :</label>
                                        <input type="text" name="code"
                                               placeholder="{{\App\CPU\translate('Currency Code')}}"
                                               class="form-control" id="code"
                                               value="{{$data->code}}">
                                    </div>
                                    @if($currency_model=='multi_currency')
                                        <div class="col-md-6 mb-3">
                                            <label class="title-color">{{\App\CPU\translate('Exchange Rate')}} :</label>
                                            <input type="number" min="0" max="1000000"
                                                   name="exchange_rate" step="0.00000001"
                                                   placeholder="{{\App\CPU\translate('Exchange Rate')}}"
                                                   class="form-control" id="exchange_rate"
                                                   value="{{$data->exchange_rate}}">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="d-flex flex-wrap gap-10 justify-content-center">
                                <button type="submit" id="add" class="btn btn--primary">{{\App\CPU\translate('Update')}}
                                </button>
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
