@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('Shipping Address Choose'))
{{--@section('title', 'اختيار تفاصيل الشحن')--}}

@push('css_or_js')
    <link rel="stylesheet" href="{{ asset('assets/front-end/css/bootstrap-select.min.css') }}">

    <style>
        .btn-outline {
            border-color: {{$web_config['primary_color']}} ;
        }
        .widget-cart-item .close {
        {{session('direction') == 'rtl' ? 'left:15px!important;' : 'left:0!important;'}}
        }

        .btn-outline {
            border-color: {{$web_config['primary_color']}}      !important;
        }

        .btn-outline:hover {
            background: {{$web_config['primary_color']}};

        }

        .btn-outline:focus {
            border-color: {{$web_config['primary_color']}}      !important;
        }

        /*#location_map_canvas {*/
        /*    height: 100%;*/
        /*}*/

        .filter-option {
            display: block;
            width: 100%;
            height: calc(1.5em + 1.25rem + 2px);
            padding: 0.625rem 1rem;
            font-size: .9375rem;
            font-weight: 400;
            line-height: 1.5;
            color: #4b566b;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #dae1e7;
            border-radius: 0.3125rem;
            box-shadow: 0 0 0 0 transparent;
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .btn-light + .dropdown-menu {
            transform: none !important;
            top: 41px !important;
        }

        /*@media only screen and (max-width: 768px) {*/
        /*    !* For mobile phones: *!*/
        /*    #location_map_canvas {*/
        /*        height: 200px;*/
        /*    }*/
        /*}*/
    </style>
@endpush

@section('content')
    @php($billing_input_by_customer=\App\CPU\Helpers::get_business_settings('billing_input_by_customer'))
    <div class="container pb-5 mb-2 mb-md-4 rtl __inline-56"
         style="text-align: {{Session::get('direction') === "rtl" ? 'left' : 'right'}};" dir="{{session('direction')}}">
        <div class="row">
            <div class="col-md-12 mb-5 pt-5">
                <div class="feature_header {{session('direction') == 'rtl' ? 'text-right' : 'text-left'}}">
                                        <span>{{ \App\CPU\translate('shipping')}} {{$billing_input_by_customer==1?\App\CPU\translate('and').' '.\App\CPU\translate('billing'):' '}} {{\App\CPU\translate('address')}}</span>
{{--                    <span>تفاصيل الشحن والفاتورة</span>--}}
                </div>
            </div>
            <section class="col-lg-8" dir="{{session('direction')}}">
                <div class="checkout_details {{session('direction') == 'rtl' ? 'text-right' : 'text-left'}}">
                    <!-- Steps-->
                    @include('web-views.partials._checkout-steps',['step'=>2])
                    @php($default_location=\App\CPU\Helpers::get_business_settings('default_location'))
                    <input type="hidden" id="physical_product" name="physical_product"
                           value="{{ $physical_product_view ? 'yes':'no'}}">

                    <!-- Shipping methods table-->
                    @if($physical_product_view)
                                                <h2 class="h4 pb-3 mb-2 mt-5">{{ \App\CPU\translate('choose_shipping_address')}}</h2>
{{--                        <h2 class="h4 pb-3 mb-2 mt-5 bold">ادخل عنوان الشحن </h2>--}}
                        @php($shipping_addresses=\App\Model\ShippingAddress::where('customer_id',auth('customer')->id())->where('is_billing',0)->get())
                        <form method="post" class="card __card {{session('direction') == 'rtl' ? 'text-right' : 'text-left'}}" id="address-form">
                            <div class="card-body p-0">
                                <ul class="list-group">
                                    @foreach($shipping_addresses as $key => $address)

                                        <li class="list-group-item __inline-57"
                                            onclick="$('#sh-{{$address['id']}}').prop( 'checked', true )">
                                            <input type="radio" name="shipping_method_id"
                                                   id="sh-{{$address['id']}}"
                                                   value="{{$address['id']}}" {{$key==0?'checked':''}}>
                                            <span class="checkmark"
                                                  style="margin-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 10px"></span>
                                            <label class="badge"
                                                   style="background: {{$web_config['primary_color']}}; color:white !important;">{{$address['address_type']}}</label>
                                            <small>
                                                <i class="fa fa-phone"></i> {{$address['phone']}}
                                            </small>
                                            <hr>
                                            <div class="d-flex">
                                                <div class="w-0 flex-grow-1 justify-content-between">
                                                    <span>{{ \App\CPU\translate('contact_person_name')}}: {{$address['contact_person_name']}}</span><br>
                                                    <span>{{ \App\CPU\translate('address')}} : {{$address['address']}}, {{$address['city']}}, {{$address['zip']}}.</span>
                                                </div>
                                                <div class="">
                                                    <a href="{{ route('address-edit', ['id' => $address->id]) }}"
                                                       title="{{ \App\CPU\translate('edit_address')}}" class="mt-2"><i
                                                            class="fa fa-edit fa-lg"></i></a>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                    <li class="list-group-item" onclick="anotherAddress()">
                                        <input type="radio" name="shipping_method_id"
                                               id="sh-0" value="0" data-toggle="collapse"
                                               data-target="#collapseThree" {{$shipping_addresses->count()==0?'checked':''}}>
                                        <span class="checkmark"
                                              style="margin-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 10px"></span>

                                                                                <button type="button" class="btn btn-outline" data-toggle="collapse"
                                                                                        data-target="#collapseThree">{{ \App\CPU\translate('Another')}} {{ \App\CPU\translate('address')}}
                                                                                </button>
{{--                                        <button type="button" class="btn btn-outline bold" data-toggle="collapse"--}}
{{--                                                data-target="#collapseThree">عنوان آخر--}}
{{--                                        </button>--}}
                                        <div id="accordion">
                                            <div id="collapseThree"
                                                 class="collapse {{$shipping_addresses->count()==0?'show':''}}"
                                                 aria-labelledby="headingThree"
                                                 data-parent="#accordion">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                                                                                <label
                                                                                                                    for="exampleInputEmail1" class="bold">{{ \App\CPU\translate('contact_person_name')}}
                                                                                                                    <span class="text-danger">*</span></label>
{{--                                                        <label--}}
{{--                                                            for="exampleInputEmail1" class="bold">الإسم--}}
{{--                                                            <span class="text-danger">*</span></label>--}}
                                                        <input type="text" class="form-control"
                                                               name="contact_person_name"
                                                               {{$shipping_addresses->count()==0?'required':''}} value="{{auth('customer')->check() ? auth('customer')->user()->f_name : ''}}">
                                                    </div>
                                                    <div class="form-group">
                                                                                                                <label for="exampleInputEmail1" class="bold">{{ \App\CPU\translate('Phone')}}
{{--                                                        <label for="exampleInputEmail1" class="bold">رقم الهاتف--}}
                                                            <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control"
                                                               name="phone"
                                                               {{$shipping_addresses->count()==0?'required':''}} value="{{auth('customer')->check() ? auth('customer')->user()->phone : ''}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label
                                                                                                                        for="exampleInputPassword1">{{ \App\CPU\translate('address')}} {{ \App\CPU\translate('Type')}}</label>
{{--                                                            for="exampleInputPassword1" class="bold">نوع العنوان</label>--}}
                                                        <select class="form-control" name="address_type">
                                                                                                                        <option
                                                                                                                            value="permanent">{{ \App\CPU\translate('Permanent')}}</option>
                                                                                                                        <option value="home">{{ \App\CPU\translate('Home')}}</option>
                                                                                                                        <option
                                                                                                                            value="others">{{ \App\CPU\translate('Others')}}</option>
{{--                                                            <option--}}
{{--                                                                value="permanent">عنوان دائم--}}
{{--                                                            </option>--}}
{{--                                                            <option value="home">عنوان المنزل</option>--}}
{{--                                                            <option--}}
{{--                                                                value="others">عنوان آخر--}}
{{--                                                            </option>--}}
                                                        </select>
                                                    </div>
                                                    @if(auth('customer')->check() && auth('customer')->user()->city != null)
                                                        <div class="form-group">
                                                            {{--                                                        <label for="exampleInputEmail1" class="bold">{{ \App\CPU\translate('City')}}<span--}}
                                                            <label for="exampleInputEmail1" class="bold">المدينة<span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control"
                                                                   value="{{auth('customer')->user()->city}}"
                                                                   name="city" {{$shipping_addresses->count()==0?'required':''}}>
                                                        </div>
                                                    @elseif(Session::has('city'))

                                                        <div class="form-group">
                                                            {{--                                                        <label for="exampleInputEmail1" class="bold">{{ \App\CPU\translate('City')}}<span--}}
                                                            <label for="exampleInputEmail1" class="bold">المدينة<span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control"
                                                                   value="{{Session::get('city')}}"
                                                                   name="city" {{$shipping_addresses->count()==0?'required':''}}>
                                                        </div>
                                                    @else
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1" class="bold">المدينة<span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control"
                                                                   name="city" {{$shipping_addresses->count()==0?'required':''}}>
                                                        </div>
                                                    @endif

                                                    <div class="form-group d-none">
                                                        <label
                                                            for="exampleInputEmail1" class="bold">
                                                            {{ \App\CPU\translate('zip_code')}} for="exampleInputEmail1"
                                                            class="bold">
                                                            الرقم البريدي
                                                            <span
                                                                class="text-danger">*</span></label>
                                                        <input type="hidden" class="form-control"
                                                               name="zip"
                                                               {{$shipping_addresses->count()==0?'required':''}} value="11011">
                                                    </div>
                                                    <div class="form-group d-none">
                                                        <label
                                                            {{--                                                            for="exampleInputEmail1" class="bold">{{ \App\CPU\translate('Country')}}--}}
                                                            for="exampleInputEmail1" class="bold">الدولة
                                                            <span
                                                                style="color: red">*</span></label>
                                                        <input name="country" id="" class="form-control selectpicker"
                                                               value="السعودية" data-live-search="true" required>
                                                    </div>

                                                    @if(auth('customer')->check() && auth('customer')->user()->street_address != null)

                                                        <div class="form-group">
                                                            <label
                                                                {{--                                                            for="exampleInputEmail1" class="bold">{{ \App\CPU\translate('address')}}<span--}}
                                                                for="exampleInputEmail1" class="bold">العنوان<span
                                                                    class="text-danger">*</span></label>
                                                            <textarea class="form-control" id="address"
                                                                      type="text"
                                                                      name="address" {{$shipping_addresses->count()==0?'required':''}}>{{auth('customer')->user()->street_address}}</textarea>
                                                        </div>
                                                    @elseif(Session::has('current_location'))

                                                        <div class="form-group">
                                                            <label
                                                                {{--                                                            for="exampleInputEmail1" class="bold">{{ \App\CPU\translate('address')}}<span--}}
                                                                for="exampleInputEmail1" class="bold">العنوان<span
                                                                    class="text-danger">*</span></label>
                                                            <textarea class="form-control" id="address"
                                                                      type="text"
                                                                      name="address" {{$shipping_addresses->count()==0?'required':''}}>{{Session::get('current_location')}}</textarea>
                                                        </div>
                                                    @else

                                                        <div class="form-group">
                                                            <label
                                                                {{--                                                            for="exampleInputEmail1" class="bold">{{ \App\CPU\translate('address')}}<span--}}
                                                                for="exampleInputEmail1" class="bold">العنوان<span
                                                                    class="text-danger">*</span></label>
                                                            <textarea class="form-control" id="address"
                                                                      type="text"
                                                                      name="address" {{$shipping_addresses->count()==0?'required':''}}></textarea>
                                                        </div>

                                                    @endif


                                                    <div class="form-group">
                                                        <input id="pac-input" class="controls rounded __inline-46"
                                                               title="ابحث هنا عن العنوان" type="text"
                                                               placeholder="ابحث هنا عن العنوان"
                                                               style="padding-right: 11px;"/>
                                                        <div class="__h-200px" id="location_map_canvas"></div>
                                                    </div>
                                                    <div class="form-check"
                                                         style="padding-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 1.25rem;">
                                                        <input type="checkbox" name="save_address"
                                                               class="form-check-input"
                                                               id="exampleCheck1">
                                                        <label class="form-check-label bold" for="exampleCheck1"
                                                               style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 1.09rem">
                                                            {{--                                                            {{ \App\CPU\translate('save_this_address')}}--}}
                                                            احفظ العنوان
                                                        </label>
                                                    </div>
                                                    <input type="hidden" id="latitude"
                                                           name="latitude" class="form-control d-inline"
                                                           placeholder="Ex : -94.22213"
                                                           value="{{$default_location?$default_location['lat']:0}}"
                                                           required
                                                           readonly>
                                                    <input type="hidden"
                                                           name="longitude" class="form-control"
                                                           placeholder="Ex : 103.344322" id="longitude"
                                                           value="{{$default_location?$default_location['lng']:0}}"
                                                           required
                                                           readonly>

                                                    <button type="submit" class="btn btn--primary" style="display: none"
                                                            id="address_submit"></button>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </form>
                    @endif

                    <div style="display: {{$billing_input_by_customer?'':'none'}}">
                        <!-- billing methods table-->
                                                <h2 class="h4 pb-3 mb-2 mt-4">{{ \App\CPU\translate('choose_billing_address')}}</h2>
{{--                        <h2 class="h4 pb-3 mb-2 mt-4">ادخل عنوان الفاتورة</h2>--}}

                        @php($billing_addresses=\App\Model\ShippingAddress::where('customer_id',auth('customer')->id())->where('is_billing',1)->get())
                        @if($physical_product_view)
                            <div class="form-check mb-2"
                                 style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 1.25rem;">
                                <input type="checkbox" id="same_as_shipping_address" onclick="hide_billingAddress()"
                                       name="same_as_shipping_address"
                                       class="form-check-input" {{$billing_input_by_customer==1?'':'checked'}}>
                                <label class="form-check-label" for="same_as_shipping_address"
                                       style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 1.09rem">
                                    {{ \App\CPU\translate('same_as_shipping_address')}}
                                    {{--                                    استخدام عنوان الشحن ؟--}}
                                </label>
                            </div>
                        @endif
                        <form method="post" class="card __card" id="billing-address-form">
                            <div id="hide_billing_address" class="card-body p-0">
                                <ul class="list-group">
                                    @foreach($billing_addresses as $key=>$address)

                                        <li class="list-group-item __inline-57"
                                            onclick="$('#bh-{{$address['id']}}').prop( 'checked', true )">
                                            <input type="radio" name="billing_method_id"
                                                   id="bh-{{$address['id']}}"
                                                   value="{{$address['id']}}">
                                            <span class="checkmark"
                                                  style="margin-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 10px"></span>
                                            <label class="badge"
                                                   style="background: {{$web_config['primary_color']}}; color:white !important;">{{$address['address_type']}}</label>
                                            <small>
                                                <i class="fa fa-phone"></i> {{$address['phone']}}
                                            </small>
                                            <hr>
                                            <div class="d-flex">
                                                <div class="w-0 flex-grow-1 justify-content-between">
                                                    <span>الإسم: {{$address['contact_person_name']}}</span><br>
                                                    <span>العنوان : {{$address['address']}}, {{$address['city']}}, {{$address['zip']}}.</span>
                                                </div>
                                                <div>
                                                    <a href="{{ route('address-edit', ['id' => $address->id]) }}"
                                                       title="Edit Address" class="mt-2"><i
                                                            class="fa fa-edit fa-lg"></i></a>
                                                </div>
                                            </div>

                                        </li>
                                    @endforeach
                                    <li class="list-group-item" onclick="billingAddress()">
                                        <input type="radio" name="billing_method_id"
                                               id="bh-0" value="0" data-toggle="collapse"
                                               data-target="#billing_model" checked>
                                        {{--                                        {{$billing_addresses->count()==0?'checked':''}}--}}
                                        <span class="checkmark"
                                              style="margin-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 10px"></span>

                                        <button type="button" class="btn btn-outline" data-toggle="collapse"
                                                data-target="#billing_model">{{\App\CPU\translate('Another Address')}}
                                        </button>
                                        <div id="accordion">
                                            <div id="billing_model"
                                                 class="collapse {{$billing_addresses->count()==0?'show':''}}"
                                                 aria-labelledby="headingThree"
                                                 data-parent="#accordion">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label
                                                            for="exampleInputEmail1"
                                                            class="bold">{{\App\CPU\translate('Name')}}
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control"
                                                               name="billing_contact_person_name"
                                                               {{$billing_addresses->count()==0?'required':''}} value="{{auth('customer')->check() ? auth('customer')->user()->f_name : ''}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1" class="bold">رقم الهاتف
                                                            <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control"
                                                               name="billing_phone"
                                                               {{$billing_addresses->count()==0?'required':''}} value="{{auth('customer')->check() ? auth('customer')->user()->phone : ''}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label
                                                            for="exampleInputPassword1">نوع العنوان</label>
                                                        <select class="form-control" name="billing_address_type">
                                                            <option
                                                                value="permanent">عنوان دائم
                                                            </option>
                                                            <option value="home">عنوان المنزل</option>
                                                            <option
                                                                value="others">عنوان اخر
                                                            </option>
                                                        </select>
                                                    </div>


                                                    @if(auth('customer')->check() && auth('customer')->user()->city != null)
                                                        <div class="form-group">
                                                            {{--                                                        <label for="exampleInputEmail1" class="bold">{{ \App\CPU\translate('City')}}<span--}}
                                                            <label for="exampleInputEmail1"
                                                                   class="bold">{{\App\CPU\translate('City')}}<span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control"
                                                                   value="{{auth('customer')->user()->city}}"
                                                                   name="billing_city" {{$billing_addresses->count()==0?'required':''}}>
                                                        </div>
                                                    @elseif(Session::has('city'))

                                                        <div class="form-group">
                                                            {{--                                                        <label for="exampleInputEmail1" class="bold">{{ \App\CPU\translate('City')}}<span--}}
                                                            <label for="exampleInputEmail1"
                                                                   class="bold">{{\App\CPU\translate('City')}}<span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control"
                                                                   value="{{Session::get('city')}}"
                                                                   name="billing_city" {{$billing_addresses->count()==0?'required':''}}>
                                                        </div>
                                                    @else
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"
                                                                   class="bold">{{\App\CPU\translate('City')}}<span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control"
                                                                   name="billing_city" {{$billing_addresses->count()==0?'required':''}}>
                                                        </div>
                                                    @endif
                                                    <div class="form-group d-none">
                                                        <label
                                                            for="exampleInputEmail1" class="bold">الرقم البريدي
                                                            <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control"
                                                               name="billing_zip"
                                                               {{$billing_addresses->count()==0?'required':''}} value="11011">

                                                    </div>

                                                    <div class="form-group d-none">
                                                        <label
                                                            for="exampleInputEmail1" class="bold">الدولة
                                                            <span style="color: red">*</span></label>
                                                        <input name="billing_country" id=""
                                                               class="form-control selectpicker" value="السعودية"
                                                               data-live-search="true" required>

                                                    </div>

                                                    @if(auth('customer')->check() && auth('customer')->user()->street_address != null)

                                                        <div class="form-group">
                                                            <label
                                                                {{--                                                            for="exampleInputEmail1" class="bold">{{ \App\CPU\translate('address')}}<span--}}
                                                                for="exampleInputEmail1" class="bold">العنوان<span
                                                                    class="text-danger">*</span></label>
                                                            <textarea class="form-control" id="billing_address"
                                                                      type="billing_text"
                                                                      name="billing_address" {{$shipping_addresses->count()==0?'required':''}}>{{auth('customer')->user()->street_address}}</textarea>
                                                        </div>
                                                    @elseif(Session::has('current_location'))

                                                        <div class="form-group">
                                                            <label
                                                                {{--                                                            for="exampleInputEmail1" class="bold">{{ \App\CPU\translate('address')}}<span--}}
                                                                for="exampleInputEmail1" class="bold">العنوان<span
                                                                    class="text-danger">*</span></label>
                                                            <textarea class="form-control" id="billing_address"
                                                                      type="billing_text"
                                                                      name="billing_address" {{$shipping_addresses->count()==0?'required':''}}>{{Session::get('current_location')}}</textarea>
                                                        </div>
                                                    @else

                                                        <div class="form-group">
                                                            <label
                                                                {{--                                                            for="exampleInputEmail1" class="bold">{{ \App\CPU\translate('address')}}<span--}}
                                                                for="exampleInputEmail1" class="bold">العنوان<span
                                                                    class="text-danger">*</span></label>
                                                            <textarea class="form-control" id="billing_address"
                                                                      type="billing_text"
                                                                      name="billing_address" {{$billing_addresses->count()==0?'required':''}}></textarea>
                                                        </div>

                                                    @endif


                                                    <div class="form-group">
                                                        <input id="pac-input-billing"
                                                               class="controls rounded __inline-46"
                                                               style="padding-right: 11px;"
                                                               title="ابحث عن عنوانك هنا"
                                                               type="text"
                                                               placeholder="ابحث عن عنوانك هنا"/>
                                                        <div class="__h-200px" id="location_map_canvas_billing"></div>
                                                    </div>
                                                    <div class="form-check"
                                                         style="padding-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 1.25rem;">
                                                        <input type="checkbox" name="save_address_billing"
                                                               class="form-check-input"
                                                               id="save_address_billing">
                                                        <label class="form-check-label bold" for="save_address_billing"
                                                               style="padding-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 1.09rem">
                                                            احفظ العنوان
                                                        </label>
                                                    </div>
                                                    <input type="hidden" id="billing_latitude"
                                                           name="billing_latitude" class="form-control d-inline"
                                                           placeholder="Ex : -94.22213"
                                                           value="{{$default_location?$default_location['lat']:0}}"
                                                           required
                                                           readonly>
                                                    <input type="hidden"
                                                           name="billing_longitude" class="form-control"
                                                           placeholder="Ex : 103.344322" id="billing_longitude"
                                                           value="{{$default_location?$default_location['lng']:0}}"
                                                           required
                                                           readonly>

                                                    <button type="submit" class="btn btn--primary" style="display: none"
                                                            id="address_submit"></button>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </form>
                    </div>


                    <!-- Navigation (desktop)-->
                    <div class="row mt-3" dir="rtl">
                        <div class="col-6">
                            <a class="btn btn-secondary btn-block bold" href="{{route('shop-cart')}}" dir="rtl">
                                <i class="czi-arrow-right mt-sm-0 mx-1"></i>
                                <span class="d-inline ">{{\App\CPU\translate('Cart')}}</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a class="btn btn--primary btn-block bold" href="javascript:" onclick="proceed_to_next()" dir="rtl">
                                <span class="d-none d-sm-inline">{{\App\CPU\translate('Proceed To Pay')}}</span>
                                <span class="d-inline d-sm-none">{{\App\CPU\translate('Next')}}</span>
                                <i class="czi-arrow-left mt-sm-0 mx-1"></i>
                            </a>
                        </div>
                    </div>
                    <!-- Sidebar-->
                </div>
            </section>
            <aside class="col-lg-4 pt-4 pt-lg-2">

                @include('web-views.partials._order-summary')
            </aside>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/front-end/js/bootstrap-select.min.js') }}"></script>
    <script>
        function anotherAddress() {
            $('#sh-0').prop('checked', true);
            $("#collapseThree").collapse();
        }

        function billingAddress() {
            $('#bh-0').prop('checked', true);
            $("#billing_model").collapse();
        }

    </script>
    <script>
        function hide_billingAddress() {
            let check_same_as_shippping = $('#same_as_shipping_address').is(":checked");
            console.log(check_same_as_shippping);
            if (check_same_as_shippping) {
                $('#hide_billing_address').hide();
            } else {
                $('#hide_billing_address').show();
            }
        }
    </script>
    <script

        src="https://maps.googleapis.com/maps/api/js?key={{\App\CPU\Helpers::get_business_settings('map_api_key')}}&libraries=places&v=3.49"></script>
    <script>
        function initAutocomplete() {
            var myLatLng = {
                lat: {{$default_location?$default_location['lat']:'-33.8688'}},
                lng: {{$default_location?$default_location['lng']:'151.2195'}}
            };

            const map = new google.maps.Map(document.getElementById("location_map_canvas"), {
                center: {
                    lat: {{$default_location?$default_location['lat']:'-33.8688'}},
                    lng: {{$default_location?$default_location['lng']:'151.2195'}}
                },
                zoom: 13,
                mapTypeId: "roadmap",
            });

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
            });

            marker.setMap(map);
            var geocoder = geocoder = new google.maps.Geocoder();
            google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
                var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                var coordinates = JSON.parse(coordinates);
                var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
                marker.setPosition(latlng);
                map.panTo(latlng);

                document.getElementById('latitude').value = coordinates['lat'];
                document.getElementById('longitude').value = coordinates['lng'];

                geocoder.geocode({'latLng': latlng}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[1]) {
                            document.getElementById('address').value = results[1].formatted_address;
                            console.log(results[1].formatted_address);
                        }
                    }
                });
            });

            // Create the search box and link it to the UI element.
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });
            let markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }
                // Clear out the old markers.
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    var mrkr = new google.maps.Marker({
                        map,
                        title: place.name,
                        position: place.geometry.location,
                    });

                    google.maps.event.addListener(mrkr, "click", function (event) {
                        document.getElementById('latitude').value = this.position.lat();
                        document.getElementById('longitude').value = this.position.lng();

                    });

                    markers.push(mrkr);

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        };
        $(document).on('ready', function () {
            initAutocomplete();

        });

        $(document).on("keydown", "input", function (e) {
            if (e.which == 13) e.preventDefault();
        });
    </script>

    <script>
        function initAutocompleteBilling() {
            var myLatLng = {
                lat: {{$default_location?$default_location['lat']:'-33.8688'}},
                lng: {{$default_location?$default_location['lng']:'151.2195'}}
            };

            const map = new google.maps.Map(document.getElementById("location_map_canvas_billing"), {
                center: {
                    lat: {{$default_location?$default_location['lat']:'-33.8688'}},
                    lng: {{$default_location?$default_location['lng']:'151.2195'}}
                },
                zoom: 13,
                mapTypeId: "roadmap",
            });

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
            });

            marker.setMap(map);
            var geocoder = geocoder = new google.maps.Geocoder();
            google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
                var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                var coordinates = JSON.parse(coordinates);
                var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
                marker.setPosition(latlng);
                map.panTo(latlng);

                document.getElementById('billing_latitude').value = coordinates['lat'];
                document.getElementById('billing_longitude').value = coordinates['lng'];

                geocoder.geocode({'latLng': latlng}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[1]) {
                            document.getElementById('billing_address').value = results[1].formatted_address;
                            console.log(results[1].formatted_address);
                        }
                    }
                });
            });

            // Create the search box and link it to the UI element.
            const input = document.getElementById("pac-input-billing");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });
            let markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }
                // Clear out the old markers.
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    var mrkr = new google.maps.Marker({
                        map,
                        title: place.name,
                        position: place.geometry.location,
                    });

                    google.maps.event.addListener(mrkr, "click", function (event) {
                        document.getElementById('billing_latitude').value = this.position.lat();
                        document.getElementById('billing_longitude').value = this.position.lng();

                    });

                    markers.push(mrkr);

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        };
        $(document).on('ready', function () {
            initAutocompleteBilling();

        });

        $(document).on("keydown", "input", function (e) {
            if (e.which == 13) e.preventDefault();
        });
    </script>
    <script>
        function proceed_to_next() {
            let physical_product = $('#physical_product').val();

            if (physical_product === 'yes') {
                var billing_addresss_same_shipping = $('#same_as_shipping_address').is(":checked");

                let allAreFilled = true;
                document.getElementById("address-form").querySelectorAll("[required]").forEach(function (i) {
                    if (!allAreFilled) return;
                    if (!i.value) allAreFilled = false;
                    if (i.type === "radio") {
                        let radioValueCheck = false;
                        document.getElementById("address-form").querySelectorAll(`[name=${i.name}]`).forEach(function (r) {
                            if (r.checked) radioValueCheck = true;
                        });
                        allAreFilled = radioValueCheck;
                    }
                });

                //billing address saved
                let allAreFilled_shipping = true;

                if (billing_addresss_same_shipping != true) {

                    document.getElementById("billing-address-form").querySelectorAll("[required]").forEach(function (i) {
                        if (!allAreFilled_shipping) return;
                        if (!i.value) allAreFilled_shipping = false;
                        if (i.type === "radio") {
                            let radioValueCheck = false;
                            document.getElementById("billing-address-form").querySelectorAll(`[name=${i.name}]`).forEach(function (r) {
                                if (r.checked) radioValueCheck = true;
                            });
                            allAreFilled_shipping = radioValueCheck;
                        }
                    });
                }
            } else {
                var billing_addresss_same_shipping = false;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('customer.choose-shipping-address')}}',
                data: {
                    physical_product: physical_product,
                    shipping: physical_product === 'yes' ? $('#address-form').serialize() : null,
                    billing: $('#billing-address-form').serialize(),
                    billing_addresss_same_shipping: billing_addresss_same_shipping
                },

                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    if (data.errors) {
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        location.href = '{{route('checkout-payment')}}';
                    }
                },
                complete: function () {
                    $('#loading').hide();
                },
                error: function (data) {
                    let error_msg = data.responseJSON.errors;
                    toastr.error(error_msg, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });


        }
    </script>
@endpush
