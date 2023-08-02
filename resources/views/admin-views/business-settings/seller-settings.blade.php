@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Seller Information'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/business-setup.png')}}" alt="">
                {{\App\CPU\translate('Business_Setup')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
        @include('admin-views.business-settings.business-setup-inline-menu')
        <!-- End Inlile Menu -->

        <div class="row gy-3">
            @php($commission=\App\Model\BusinessSetting::where('type','sales_commission')->first())
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">{{\App\CPU\translate('Sales Commission')}}</h5>
                    </div>

                    <div class="card-body">
                        <form action="{{route('admin.business-settings.seller-settings.update-seller-settings')}}"
                              method="post">
                            @csrf
                            <label class="title-color d-flex">{{\App\CPU\translate('Default Sales Commission')}} ( % )</label>
                            <input type="number" class="form-control" name="commission"
                                   value="{{isset($commission)?$commission->value:0}}"
                                   min="0" max="100">
                            <div class="mt-3 d-flex flex-wrap justify-content-end gap-10">
                                <button type="submit" class="btn btn--primary px-4">{{\App\CPU\translate('Save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @php($seller_registration=\App\Model\BusinessSetting::where('type','seller_registration')->first()->value)
            <div class="col-md-6 mt-md-0">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">{{\App\CPU\translate('Seller Registration')}}</h5>
                    </div>

                    <div class="card-body">
                        <form action="{{route('admin.business-settings.seller-settings.update-seller-registration')}}"
                              method="post">
                            @csrf
                            <label class="title-color d-flex mb-3">{{\App\CPU\translate('Seller Registration on/off')}}</label>
                            <div class="d-flex flex-column">
                                <div class="d-flex gap-2 align-items-center mb-2">
                                    <input class="" name="seller_registration" type="radio" value="1"
                                        id="defaultCheck1" {{$seller_registration==1?'checked':''}}>
                                    <label class="title-color mb-0" for="defaultCheck1">
                                        {{\App\CPU\translate('Turn on')}}
                                    </label>
                                </div>
                                <div class="d-flex gap-2 align-items-center">
                                    <input name="seller_registration" type="radio" value="0"
                                        id="defaultCheck2" {{$seller_registration==0?'checked':''}}>
                                    <label class="title-color mb-0" for="defaultCheck2">
                                        {{\App\CPU\translate('Turn off')}}
                                    </label>
                                </div>
                            </div>
                            <div class="mt-3 d-flex flex-wrap justify-content-end gap-10">
                                <button type="submit" class="btn btn--primary px-4">{{\App\CPU\translate('Save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @php($seller_pos=\App\Model\BusinessSetting::where('type','seller_pos')->first()->value)
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">{{\App\CPU\translate('Seller POS')}}</h5>
                    </div>

                    <div class="card-body">
                        <form action="{{route('admin.business-settings.seller-settings.seller-pos-settings')}}"
                              method="post">
                            @csrf
                            <label class="title-color d-flex mb-3">{{\App\CPU\translate('Seller POS permission on/off')}}</label>
                            <div class="d-flex flex-column">
                                <div class="d-flex gap-2 align-items-center mb-2">
                                    <input name="seller_pos" type="radio" value="1"
                                        id="seller_pos1" {{$seller_pos==1?'checked':''}}>
                                    <label class="title-color mb-0" for="seller_pos1">
                                        {{\App\CPU\translate('Turn on')}}
                                    </label>
                                </div>
                                <div class="d-flex gap-2 align-items-center">
                                    <input name="seller_pos" type="radio" value="0"
                                        id="seller_pos2" {{$seller_pos==0?'checked':''}}>
                                    <label class="title-color mb-0" for="seller_pos2">
                                        {{\App\CPU\translate('Turn off')}}
                                    </label>
                                </div>
                            </div>
                            <div class="mt-3 d-flex flex-wrap justify-content-end gap-10">
                                <button type="submit" class="btn btn--primary px-4">{{\App\CPU\translate('Save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="text-center text-capitalize mb-0"> {{\App\CPU\translate('Business_mode')}}</h5>
                    </div>
                    <div class="card-body">
                        @php($business_mode=\App\CPU\Helpers::get_business_settings('business_mode'))
                        <div class="form-row gy-2">
                            <div class="col-sm">
                                <!-- Custom Radio -->
                                <div class="form-control">
                                    <div class="custom-control custom-radio custom-radio-reverse"
                                         onclick="business_mode('{{route('admin.business-settings.seller-settings.business-mode-settings',['single'])}}','{{\App\CPU\translate('For single vendor operation, deactive all seller!!')}}')">
                                        <input type="radio" class="custom-control-input"
                                               name="projectViewNewProjectTypeRadio"
                                               id="projectViewNewProjectTypeRadio1" {{(isset($business_mode) && $business_mode=='single')?'checked':''}}>
                                        <label class="custom-control-label media align-items-center"
                                               for="projectViewNewProjectTypeRadio1">

                                            <span class="media-body">
                                                {{\App\CPU\translate('single_vendor')}}
                                              </span>
                                        </label>
                                    </div>
                                </div>
                                <!-- End Custom Radio -->
                            </div>

                            <div class="col-sm">
                                <!-- Custom Radio -->
                                <div class="form-control">
                                    <div class="custom-control custom-radio custom-radio-reverse"
                                         onclick="business_mode('{{route('admin.business-settings.seller-settings.business-mode-settings',['multi'])}}','{{\App\CPU\translate('Now, your multi vendor business mode is opening, you can add new seller !!')}}')">
                                        <input type="radio" class="custom-control-input"
                                               name="projectViewNewProjectTypeRadio"
                                               id="projectViewNewProjectTypeRadio2" {{(isset($business_mode) && $business_mode=='multi')?'checked':''}}>
                                        <label class="custom-control-label media align-items-center"
                                               for="projectViewNewProjectTypeRadio2">

                                            <span
                                                class="media-body">{{\App\CPU\translate('multi_vendor')}}</span>
                                        </label>
                                    </div>
                                </div>
                                <!-- End Custom Radio -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{\App\CPU\translate('admin_approval_for_products')}}</h5>
                    </div>
                    @php($new_product_approval=\App\CPU\Helpers::get_business_settings('new_product_approval'))
                    @php($product_wise_shipping_cost_approval=\App\CPU\Helpers::get_business_settings('product_wise_shipping_cost_approval'))
                    <div class="card-body">
                        <form action="{{route('admin.business-settings.seller-settings.product-approval')}}"
                              method="post">
                            @csrf
                            <label class="title-color d-flex mb-3">{{\App\CPU\translate('approval_for_products')}}</label>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <input name="new_product_approval" type="checkbox"
                                       id="new_product_approval" {{$new_product_approval==1?'checked':''}}>
                                <label class="title-color mb-0" for="new_product_approval">
                                    {{\App\CPU\translate('new_product')}}
                                </label>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <input name="product_wise_shipping_cost_approval" type="checkbox"
                                       id="product_wise_shipping_cost_approval" {{$product_wise_shipping_cost_approval==1?'checked':''}}>
                                <label class="title-color mb-0 {{ Session::get('direction') === 'rtl' ? 'text-right' : 'text-left' }}" for="product_wise_shipping_cost_approval">
                                    {{\App\CPU\translate('product_wise_shipping_cost')}}
                                    <span class="text-info">( {{\App\CPU\translate('if the shipping responsibility is inhouse and product wise shipping is activated then this function will work')}} )</span>
                                </label>
                            </div>
                            <div class="mt-3 d-flex flex-wrap justify-content-end gap-10">
                                <button type="submit" class="btn btn--primary px-4">{{\App\CPU\translate('Save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

    <script>
        function business_mode(route,message) {
            Swal.fire({
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#377dff',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.get({
                        url: route,
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            $('#loading').show();
                        },
                        success: function (data) {
                            toastr.success(data.message);
                        },
                        complete: function () {
                            $('#loading').hide();
                        },
                    });
                } else {
                    location.reload();
                }
            })

        }
    </script>
@endpush
