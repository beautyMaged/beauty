@extends('layouts.back-end.app')
{{--@section('title','Customer')--}}
@section('title', \App\CPU\translate('order_settings'))

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">

        <!-- Page Title -->
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/assets/back-end/img/business-setup.png')}}" alt="">
                {{\App\CPU\translate('order_settings')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
        @include('admin-views.business-settings.business-setup-inline-menu')
        <!-- End Inlile Menu -->

        <div class="card">
            <div class="border-bottom px-4 py-3">
                <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                    <img src="{{asset('/assets/back-end/img/header-logo.png')}}" alt="">
                    {{\App\CPU\translate('Order_Settings')}}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{route('admin.business-settings.order-settings.update-order-settings')}}" method="post" enctype="multipart/form-data" id="add_fund">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            @php($billing_input_by_customer=\App\CPU\Helpers::get_business_settings('billing_input_by_customer'))
                            <div class="form-group">
                                <div class="d-flex gap-1 mb-2">
                                    <label class="title-color mb-0">{{\App\CPU\translate('Show_Billing_Address_In_Checkout')}}</label>
                                    <span class="text-danger">*</span>
                                </div>
                                <div class="input-group input-group-md-down-break">
                                    <!-- Custom Radio -->
                                    <div class="form-control">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" value="1"
                                                name="billing_input_by_customer"
                                                id="billing_input_by_customer1" {{$billing_input_by_customer==1?'checked':''}}>
                                            <label class="custom-control-label"
                                                for="billing_input_by_customer1">{{\App\CPU\translate('active')}}</label>
                                        </div>
                                    </div>
                                    <!-- End Custom Radio -->

                                    <!-- Custom Radio -->
                                    <div class="form-control">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" value="0"
                                                name="billing_input_by_customer"
                                                id="billing_input_by_customer2" {{$billing_input_by_customer==0?'checked':''}}>
                                            <label class="custom-control-label"
                                                for="billing_input_by_customer2">{{\App\CPU\translate('deactive')}}</label>
                                        </div>
                                    </div>
                                    <!-- End Custom Radio -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" id="submit" class="btn btn--primary px-4">{{\App\CPU\translate('submit')}}</button>
                    </div>
                </form>
            </div>
            <!-- End Table -->
        </div>
    </div>
@endsection

@push('script_2')

@endpush
