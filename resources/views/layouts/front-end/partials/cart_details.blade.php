<div class="feature_header mb-2 {{session('direction') == 'rtl' ? 'text-right' : 'text-left'}}">
    <span>{{\App\CPU\translate('Cart')}}</span>
</div>

@php($shippingMethod=\App\CPU\Helpers::get_business_settings('shipping_method'))

@if(isset($cart) == false)
    @php($cart=\App\Model\Cart::where(['customer_id' => auth('customer')->id()])->get()->groupBy('cart_group_id'))
@endif

@if(App\CPU\Helpers::get_customer() == 'offline')
    @php($cart = session('offline_cart')->groupBy('cart_group_id'))
@endif
<div class="row g-3" dir="{{session('direction')}}">
    <!-- List of items-->
    <section class="col-lg-8">
        @if(count($cart)==0)
            @php($physical_product = false)
        @endif
        @if(count($cart) == 1)
            <input type="hidden" id="payment_scenario" name="payment_scenario" value="first_payment_scenario">
            <input type="hidden" id="shop_name" name="shop_name" value="{{$cart->first()->first()->get('shop_info')}}">
        @endif
        @foreach($cart as $group_key=>$group)
            <div class="card __card cart_information mb-3">
                @foreach($group as $cart_key=>$cartItem)
                    @if ($shippingMethod=='inhouse_shipping')
                            <?php

                            $admin_shipping = \App\Model\ShippingType::where('seller_id', 0)->first();
                            $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';

                            ?>
                    @else
                            <?php
                            if ($cartItem->seller_is == 'admin') {
                                $admin_shipping = \App\Model\ShippingType::where('seller_id', 0)->first();
                                $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                            } else {
                                $seller_shipping = \App\Model\ShippingType::where(
                                    'seller_id',
                                    $cartItem->seller_id
                                )->first();
                                $shipping_type = isset($seller_shipping) == true ? $seller_shipping->shipping_type : 'order_wise';
                            }
                            ?>
                    @endif

                    @if($cart_key==0)
                        <div class="card-header {{session('direction') == 'rtl' ? 'text-right' : 'text-left'}}">
                            @if($cartItem['seller_is']=='admin')
                                <b>
                                    <span>{{\App\CPU\translate('Seller Name')}}: </span>
                                    <a href="{{route('shopView',['id'=>0])}}">{{\App\CPU\Helpers::get_business_settings('company_name')}}</a>
                                </b>
                            @else
                                <b>
                                    <span>{{\App\CPU\translate('Seller Name')}}: </span>
                                    <a href="{{route('shopView',['id'=>$cartItem['seller_id']])}}">
                                        {{\App\Model\Shop::where(['seller_id'=>$cartItem['seller_id']])->first()->name}}
                                    </a>
                                </b>
                            @endif
                        </div>
                    @endif
                @endforeach
                <div class="table-responsive mt-3">
                    <table
                        class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table __cart-table">
                        <thead class="thead-light">
                        <tr class="">
                            <th class="font-weight-bold __w-5p">#</th>
                            @if ( $shipping_type != 'order_wise')
                                <th class="font-weight-bold __w-30p">{{\App\CPU\translate('Product Details')}}</th>
                            @else
                                <th class="font-weight-bold __w-45">{{\App\CPU\translate('Product Details')}}</th>
                            @endif
                            <th class="font-weight-bold __w-15p">{{\App\CPU\translate('Unit Price')}}</th>
                            <th class="font-weight-bold __w-15p">{{\App\CPU\translate('Quantity')}}</th>
                            <th class="font-weight-bold __w-15p">{{\App\CPU\translate('Total')}}</th>
                            @if ( $shipping_type != 'order_wise')
                                <th class="font-weight-bold __w-15p">{{\App\CPU\translate('Shipping')}}</th>
                            @endif
                            <th class="font-weight-bold __w-5p"></th>
                        </tr>
                        </thead>

                        <tbody>
                            <?php
                            $physical_product = false;
                            foreach ($group as $row) {
                                if ($row['product_type'] == 'physical') {
                                    $physical_product = true;
                                }
                            }
                            ?>
                        @foreach($group as $cart_key=>$cartItem)
                            <tr>
                                <td class="num_fam">{{$cart_key+1}}</td>
                                <td>
                                    <div class="d-flex">
                                        <div class="__w-30p">
                                            <a href="{{route('product',$cartItem['slug'])}}">
                                                <img class="rounded __img-62"
                                                     onerror="this.onerror=null;this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                                     {{--                                                     src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$cartItem['thumbnail']}}"--}}
                                                     src="{{is_object(json_decode($cartItem['thumbnail'])) ? (json_decode($cartItem['thumbnail']))->cdn : \App\CPU\ProductManager::product_image_path('thumbnail').'/'.$cartItem['thumbnail']}}"
                                                     alt="Product">
                                            </a>
                                        </div>
                                        <div class="ml-2 text-break __line-2 __w-70p">
                                            <a href="{{route('product',$cartItem['slug'])}}"
                                               class="bold">{{$cartItem['name']}}</a>

                                        </div>

                                    </div>
                                    <div class="d-flex">

                                        @foreach(json_decode($cartItem['variations'],true) as $key1 =>$variation)
                                            <div class="text-muted mr-2 bold">
                                                <span
                                                    class="{{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}} __text-12px num_fam">
                                                    {{$key1}} : {{$variation}}</span>

                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <div
                                        class="primary_color num_fam">{{ \App\CPU\Helpers::currency_converter($cartItem['price']-$cartItem['discount']) }}</div>
                                    @if($cartItem['discount'] > 0)
                                        <strike class="__inline-18 num_fam">
                                            {{\App\CPU\Helpers::currency_converter($cartItem['price'])}}
                                        </strike>
                                    @endif
                                </td>
                                <td>
                                    <div class="num_fam">
                                        @php($minimum_order=\App\Model\Product::select('minimum_order_qty')->find($cartItem['product_id']))
                                        <input class="__cart-input num_fam" type="number"
                                               name="quantity[{{ $cartItem['id'] }}]"
                                               id="cartQuantity{{$cartItem['id']}}"
                                               onchange="updateCartQuantity('{{ $minimum_order->minimum_order_qty }}', '{{$cartItem['id']}}')"
                                               min="{{ $minimum_order->minimum_order_qty ?? 1 }}"
                                               value="{{$cartItem['quantity']}}">
                                    </div>
                                </td>
                                <td>
                                    <div class="num_fam">
                                        {{ \App\CPU\Helpers::currency_converter(($cartItem['price']-$cartItem['discount'])*$cartItem['quantity']) }}
                                    </div>
                                </td>
                                <td class="num_fam">
                                    @if ( $shipping_type != 'order_wise')
                                        {{ \App\CPU\Helpers::currency_converter($cartItem['shipping_cost']) }}
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-link px-0 text-danger"
                                            onclick="removeFromCart({{ $cartItem['id'] }})" type="button"><i
                                            class="czi-close-circle {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}"></i>
                                    </button>
                                </td>
                            </tr>

                            @if($physical_product && $shippingMethod=='sellerwise_shipping' && $shipping_type == 'order_wise')
                                @php($choosen_shipping=\App\Model\CartShipping::where(['cart_group_id'=>$cartItem['cart_group_id']])->first())

                                @if(isset($choosen_shipping)==false)
                                    @php($choosen_shipping['shipping_method_id']=0)
                                @endif

                                @php($shippings=\App\CPU\Helpers::get_shipping_methods($cartItem['seller_id'],$cartItem['seller_is']))
                                <tr class="num_fam">
                                    <td colspan="4">

                                        @if($cart_key==$group->count()-1)

                                            <!-- choosen shipping method-->

                                            <div class="row">

                                                <div class="col-12">
                                                    <select class="form-control"
                                                            onchange="set_shipping_id(this.value,'{{$cartItem['cart_group_id']}}')">
                                                        {{--                                                            <option>{{\App\CPU\translate('choose_shipping_method')}}</option>--}}
                                                        <option>اختر طريقة الشحن</option>
                                                        @foreach($shippings as $shipping)
                                                            <option
                                                                value="{{$shipping['id']}}" {{$choosen_shipping['shipping_method_id']==$shipping['id']?'selected':''}}>
                                                                {{$shipping['title'].' ( '.$shipping['duration'].' ) '.\App\CPU\Helpers::currency_converter($shipping['cost'])}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                        @endif
                                    </td>
                                    <td colspan="3">
                                        @if($cart_key==$group->count()-1)
                                            <div class="row">
                                                <div class="col-12">
                                                    <span>
                                                        <b>{{\App\CPU\translate('shipping_cost')}} : </b>
                                                    </span>
                                                    {{\App\CPU\Helpers::currency_converter($choosen_shipping['shipping_method_id']!= 0?$choosen_shipping->shipping_cost:0)}}
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

        @if($shippingMethod=='inhouse_shipping')
                <?php
                $admin_shipping = \App\Model\ShippingType::where('seller_id', 0)->first();
                $shipping_type = isset($admin_shipping) == true ? $admin_shipping->shipping_type : 'order_wise';
                ?>
            @if ($shipping_type == 'order_wise' && $physical_product)
                @php($shippings=\App\CPU\Helpers::get_shipping_methods(1,'admin'))
                @php($choosen_shipping=\App\Model\CartShipping::where(['cart_group_id'=>$cartItem['cart_group_id']])->first())

                @if(isset($choosen_shipping)==false)
                    @php($choosen_shipping['shipping_method_id']=0)
                @endif
                <div class="row">
                    <div class="col-12">
                        <select class="form-control bold" onchange="set_shipping_id(this.value,'all_cart_group')">
                            {{--                    <option>{{\App\CPU\translate('choose_shipping_method')}}</option>--}}
                            <option>اختر طريقة الشحن</option>
                            @foreach($shippings as $shipping)
                                <option
                                    value="{{$shipping['id']}}" {{$choosen_shipping['shipping_method_id']==$shipping['id']?'selected':''}}>
                                    {{$shipping['title'].' ( '.$shipping['duration'].' ) '.\App\CPU\Helpers::currency_converter($shipping['cost'])}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
        @endif

        @if( $cart->count() == 0)
            <div class="d-flex justify-content-center align-items-center">
                <h4 class="text-danger text-capitalize">{{\App\CPU\translate('cart_empty')}}</h4>
            </div>
        @endif


        <form method="get">
            <div class="form-group">
                <div class="row">
                    <div class="col-12 text-right">
                        {{--                        <label for="phoneLabel" class="form-label input-label">{{\App\CPU\translate('order_note')}} --}}
                        {{--                            <span class="input-label-secondary">({{\App\CPU\translate('Optional')}})</span>--}}
                        {{--                        </label>--}}
                        <label for="phoneLabel"
                               class="form-label input-label bold pr-3">{{\App\CPU\translate('order_note')}}
                            <span class="input-label-secondary">({{\App\CPU\translate('Optional')}})</span>
                        </label>
                        <textarea class="form-control w-100" id="order_note"
                                  name="order_note">{{ session('order_note')}}</textarea>
                    </div>
                </div>
            </div>
        </form>


        <div class="d-flex btn-full-max-sm align-items-center __gap-6px flex-wrap justify-content-between cart_btns"
             dir="rtl">
            <a href="{{route('home')}}" class="btn btn-custom bold" style="color: #fff">
                {{--                <i class="fa fa-{{Session::get('direction') === "rtl" ? 'forward' : 'backward'}} px-1"></i> {{\App\CPU\translate('continue_shopping')}}--}}
                <i class="fa fa-{{Session::get('direction') === "rtl" ? 'forward' : 'backward'}} px-1"></i> {{\App\CPU\translate('continue_shopping')}}
            </a>
            @if(auth('customer')->check())
                <a onclick="checkout()"
                   class="btn btn-custom bold " style="color: #fff">
                    {{--                {{\App\CPU\translate('checkout')}}--}}
                    {{\App\CPU\translate('checkout')}}
                    <i class="fa fa-{{Session::get('direction') === "rtl" ? 'backward' : 'forward'}} px-1"></i>
                </a>
            @endif

        </div>
    </section>
    <!-- Sidebar-->
    <style>
        .cart_title {
            font-weight: bold !important;
            font-size: 16px;
        }

        .cart_value {
            font-weight: bold !important;
            font-size: 16px;
        }

        .cart_total_value {
            font-weight: 700 !important;
            font-size: 25px !important;
            color: {{$web_config['primary_color']}}         !important;
        }
    </style>
    <aside class="col-lg-4 pt-4 pt-lg-2">
        @if(!auth('customer')->check())
            <div class="w-100 card __card cart_information mb-3 text-center">
                <ul class="nav nav-tabs" id="myTab" role="tablist"
                    style="padding: 0;margin: 20px auto 5px auto;border-bottom: 1px solid #ddd">
                    <li class="nav-item " role="presentation">
                        <a class="nav-link primary_color bold" id="login_tab" data-toggle="tab" href="#login" role="tab"
                           aria-controls="login" aria-selected="false">تسجيل الدخول</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active primary_color bold" id="quick_order_tab" data-toggle="tab"
                           href="#quick_order"
                           role="tab" aria-controls="quick_order"
                           aria-selected="true">{{\App\CPU\translate('Safe Payment')}}</a>
                    </li>

                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane row text-right px-2 fade show active" id="quick_order" role="tabpanel"
                         aria-labelledby="quick_order-tab">
                        <div class="form-group col-lg-12 col-md-12 col-12 col-sm-12 mb-0 pb-0">
                            <label for="contact_person_name" class="bold s_16">الإسم بالكامل</label>
                            <input type="text" value="{{old('contact_person_name')}}" onkeyup='saveValue(this);'
                                   class="form-control" id="contact_person_name" name="contact_person_name"
                                   placeholder="ادخل الإسم بالكامل">
                        </div>
                        <div class="form-group col-lg-12 col-md-12 col-12 col-sm-12 mb-0 pb-0">
                            <label for="person_email" class="bold s_16">البريد الإلكتروني</label>
                            <input type="email" value="{{old('person_email')}}" onkeyup='saveValue(this);'
                                   class="form-control" id="person_email" name="person_email"
                                   placeholder="ادخل البريد الإلكتروني" required>
                        </div>
                        <div class="form-group col-lg-12 col-md-12 col-12 col-sm-12 mb-0 pb-0">
                            <label for="order_phone" class="bold s_16">رقم الهاتف</label>
                            <input type="text" value="{{old('order_phone')}}" onkeyup='saveValue(this);'
                                   class="form-control" id="order_phone" name="order_phone"
                                   placeholder="ادخل رقم الهاتف">
                        </div>
                        <div class="col-lg-6 col-md-6 col-12 d-inline-block"
                             style="max-width: calc(50% - 5px)!important;">
                            <label for="country" class="bold s_16">المنطقة</label>
                            <select name="country" id="area" style="width: 100%">
                                <option value="">اختر المنطقة</option>
                                <option value="reyad" selected>الرياض</option>
                                <option value="gadda">جدة</option>
                                <option value="dammam">الدمام</option>
                                <option value="mecca">مكة</option>
                                <option value="gezan">جيزان</option>
                                <option value="taif">الطائف</option>
                                <option value="najran">نجران</option>
                                <option value="tabuk">تابوك</option>
                            </select>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12 d-inline-block"
                             style="max-width: calc(50% - 5px)!important;">
                            <label for="city" class="bold s_16">المدينة</label>
                            <select name="city" id="city" style="width: calc(100% - 3px)">
                                <option value="">اختر المدينة</option>
                                <option value="reyad" selected>الاحساء</option>
                                <option value="gadda">القطيف</option>
                                <option value="dammam">الهفوف</option>
                                <option value="mecca">الجبيل</option>
                                <option value="gezan">الثقبة</option>
                                <option value="taif">الخبر</option>
                                <option value="najran">ضباء</option>
                                <option value="tabuk">عرعر</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-12 col-md-12 col-12 col-sm-12 mb-0 pb-0 d-none">
                            <label for="zip" class="bold s_16">الرمز البريدي</label>
                            <input type="text" class="form-control" id="zip" value="11011" name="zip"
                                   placeholder="ادخل الرمز البريدي">
                        </div>

                        <div class="form-group col-lg-12 col-md-12 col-12 col-sm-12 mb-0 pb-0">
                            <label for="address" class="bold s_16" data-toggle="modal" data-target="#location_modal">العنوان
                                الحالي &nbsp;&nbsp;<span><i
                                        class="fa-solid fa-location-dot pl-1 pr-0 primary_color cursor-pointer"
                                        style=" font-size: 18px"></i></span></label>

                            @if(auth('customer')->check() && auth('customer')->user()->street_address != null)
                                <textarea class="form-control" id="address" name="address"
                                          placeholder="ادخل العنوان">{{auth('customer')->user()->street_address}}</textarea>
                            @elseif(Session::has('current_location'))
                                <textarea class="form-control" id="address" name="address"
                                          placeholder="ادخل العنوان">{{Session::get('current_location')}}</textarea>
                            @else
                                <textarea class="form-control" id="address" name="address"
                                          placeholder="ادخل العنوان"></textarea>
                            @endif
                        </div>
                        <div class="col-lg-12 col-md-12 col-12 col-sm-12 mb-0 pb-0">
                            <a onclick="checkoutGuest()"
                               class="btn btn-custom bold w-100" style="color: #fff">
                                {{--                {{\App\CPU\translate('checkout')}}--}}
                                إتمام الطلب
                                <i class="fa fa-{{Session::get('direction') === "rtl" ? 'backward' : 'forward'}} px-1"></i>
                            </a>
                        </div>
                    </div>
                    <div class="tab-pane row text-right px-2 fade" id="login" role="tabpanel"
                         aria-labelledby="login-tab">
                        <form action="{{route('customer.auth.login')}}" method="post" id="form-id"
                              class="row needs-validation mt-2">
                            @csrf
                            <div class="form-group col-lg-12 col-md-12 col-12 col-sm-12 mb-0 pb-0">
                                <label for="user_id" class="bold s_16">البريد الإلكتروني </label>
                                <input type="email" class="form-control" id="user_id" name="user_id"
                                       placeholder="ادخل البريد الإلكتروني " required>
                            </div>
                            <div class="form-group col-lg-12 col-md-12 col-12 col-sm-12 mb-0 pb-0">
                                <label for="password" class="bold s_16">كلمة المرور</label>
                                <input type="password" class="form-control" id="password" name="password"
                                       placeholder="ادخل كلمة المرور">
                            </div>
                            <div class="form-group col-lg-12 col-md-12 col-12 col-sm-12 mb-0 pb-0 text-left">
                                <a href="#" class="bold s_16">نسيت كلمة المرور؟</a>
                            </div>
                            <div class="col-lg-12 col-md-12 col-12 col-sm-12 mb-0 pb-0">
                                <button class="btn btn-custom bold w-100">
                                    تسجيل الدخول
                                </button>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        @endif
        @include('web-views.partials._order-summary')
    </aside>
</div>


<script>


    cartQuantityInitialize();

    function set_shipping_id(id, cart_group_id) {
        $.get({
            url: '{{url('/')}}/customer/set-shipping-method',
            dataType: 'json',
            data: {
                id: id,
                cart_group_id: cart_group_id
            },
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (data) {
                location.reload();
            },
            complete: function () {
                $('#loading').hide();
            },
        });
    }
</script>
<script>
    function checkout() {
        let order_note = $('#order_note').val();
        let payment_scenario = $('#payment_scenario').val();
        let shopName = $('#shop_name').val()
        //console.log(order_note);
        $.post({
            url: "{{route('order_note')}}",
            data: {
                _token: '{{csrf_token()}}',
                order_note: order_note,
                payment_scenario: (payment_scenario == null) ? 'second_payment_scenario' : payment_scenario,
                shop_name : shopName ?? null

            },
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (data) {
                {{--if (payment_scenario == 'first_payment_scenario') {--}}
                {{--    let url = "{{ route('pay_to_partner') }}";--}}
                {{--    location.href = url;--}}
                {{--}else{--}}
                    let url = "{{ route('checkout-details') }}";
                    location.href = url;
                // }

            },
            complete: function () {
                $('#loading').hide();
            },
        });
    }

    function checkoutGuest() {
        let order_note = $('#order_note').val();
        //console.log(order_note);
        let contact_person_name = $('#contact_person_name').val();
        let order_phone = $('#order_phone').val();
        let area = $('#area').val();
        let city = $('#city').val();
        let zip = $('#zip').val();
        let address = $('#address').val();
        let email = $('#person_email').val();
        let payment_scenario = $('#payment_scenario').val()
        let shopName = $('#shop_name').val()
        if (
            contact_person_name === '' ||
            phone === '' ||
            area === '' ||
            city === '' ||
            zip === '' ||
            address === ''
        ) {
            // alert(order_phone + ' ' + area + ' ' + city + ' ' + zip + ' ' + address)
            Swal.fire({
                'icon': 'danger',
                'text': 'يرجي ملئ بيانات الطلب'
            });
        } else {
            // alert(order_phone)
            $.post({
                url: "{{route('complete_order_as_guest')}}",
                data: {
                    _token: '{{csrf_token()}}',
                    order_note: order_note,
                    contact_person_name: contact_person_name,
                    order_phone: order_phone,
                    area: area,
                    city: city,
                    zip: zip,
                    address: address,
                    email: email,
                    payment_scenario: (payment_scenario == null) ? 'second_payment_scenario' : payment_scenario,
                    shop_name : shopName ?? null

                },
                type: "POST",
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (response) {
                    if (payment_scenario == 'first_payment_scenario') {
                        let url = "{{ route('pay_to_partner') }}";
                        location.href = url;
                    } else {
                        let url = "{{ route('checkout-payment') }}";
                        location.href = url;
                    }
                    // if (typeof (response) != 'object') {
                    //     response = $.parseJSON(response)
                    // }
                    // console.log(response.data);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }

    }
</script>

