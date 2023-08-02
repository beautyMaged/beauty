<div id="order_summary">



    <div class="__cart-total">
        <div class="cart_total ">
            @php($shippingMethod=\App\CPU\Helpers::get_business_settings('shipping_method'))
            @php($sub_total=0)
            @php($total_tax=0)
            @php($total_shipping_cost=0)
            @php($order_wise_shipping_discount=\App\CPU\CartManager::order_wise_shipping_discount())
            @php($total_discount_on_product=0)
            @php($cart=\App\CPU\CartManager::get_cart())
            @php($cart_group_ids=\App\CPU\CartManager::get_cart_group_ids())
            @php($shipping_cost=\App\CPU\CartManager::get_shipping_cost())
            @if($cart->count() > 0)
                @foreach($cart as $key => $cartItem)
                    @php($sub_total+=$cartItem['price']*$cartItem['quantity'])
                    @php($total_tax+=$cartItem['tax_model']=='exclude' ? ($cartItem['tax']*$cartItem['quantity']):0)
                    @php($total_discount_on_product+=$cartItem['discount']*$cartItem['quantity'])
                @endforeach

                @php($total_shipping_cost=$shipping_cost)
            @else
{{--                <span>{{\App\CPU\translate('empty_cart')}}</span>--}}
                <span class="bold">{{\App\CPU\translate('Cart is Empty')}}</span>
            @endif
            <div class="d-flex justify-content-between">
                <span class="cart_title">{{\App\CPU\translate('sub_total')}}</span>
{{--                <span class="cart_title bold">المبلغ</span>--}}
                <span class="cart_value bold num_fam">
                    {{\App\CPU\Helpers::currency_converter($sub_total)}}
                </span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="cart_title">{{\App\CPU\translate('tax')}}</span>
{{--                <span class="cart_title">الضريبة</span>--}}
                <span class="cart_value num_fam">
                    {{\App\CPU\Helpers::currency_converter($total_tax)}}
                </span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="cart_title">{{\App\CPU\translate('shipping')}}</span>
{{--                <span class="cart_title">الشحن</span>--}}
                <span class="cart_value num_fam">
                    {{\App\CPU\Helpers::currency_converter($total_shipping_cost)}}
                </span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="cart_title">{{\App\CPU\translate('discount_on_product')}}</span>
{{--                <span class="cart_title">خصم علي المنتج</span>--}}
                <span class="cart_value num_fam">
                    - {{\App\CPU\Helpers::currency_converter($total_discount_on_product)}}
                </span>
            </div>
            @if(session()->has('coupon_discount'))
                @php($coupon_discount = session()->has('coupon_discount')?session('coupon_discount'):0)
                <div class="d-flex justify-content-between">
                    <span class="cart_title">{{\App\CPU\translate('coupon_discount')}}</span>
{{--                    <span class="cart_title">كود خصم</span>--}}
                    <span class="cart_value num_fam" id="coupon-discount-amount">
                        - {{\App\CPU\Helpers::currency_converter($coupon_discount+$order_wise_shipping_discount)}}
                    </span>
                </div>
                @php($coupon_dis=session('coupon_discount'))
            @else
                <div class="pt-2">
                    <form class="needs-validation" action="javascript:" method="post" novalidate id="coupon-code-ajax">
                        <div class="form-group">
                            <input class="form-control input_code" type="text" name="code" placeholder="{{\App\CPU\translate('Coupon code')}}"
                                required>
{{--                            <input class="form-control input_code" type="text" name="code" placeholder="كود الخصم"--}}
{{--                                required>--}}
                            <div class="invalid-feedback">{{\App\CPU\translate('please_provide_coupon_code')}}</div>
{{--                            <div class="invalid-feedback bold">احصل علي الخصم الان</div>--}}
                        </div>
                        <button class="btn btn--primary btn-block" type="button" onclick="couponCode()">{{\App\CPU\translate('apply_code')}}
                        </button>
{{--                        <button class="btn btn-custom btn-block bold" type="button" onclick="couponCode()">استخدام كود الخصم--}}
{{--                        </button>--}}
                    </form>
                </div>
                @php($coupon_dis=0)
            @endif
            <hr class="mt-2 mb-2">
            <div class="d-flex justify-content-between">
                <span class="cart_title bold">{{\App\CPU\translate('total')}}</span>
{{--                <span class="cart_title bold">الإجمالي</span>--}}
                <span class="cart_value num_fam">
                {{\App\CPU\Helpers::currency_converter($sub_total+$total_tax+$total_shipping_cost-$coupon_dis-$total_discount_on_product-$order_wise_shipping_discount)}}
                </span>
            </div>
        </div>
        <div class="container mt-2">
            <div class="row p-0">
                <div class="col-md-3 p-0 text-center mobile-padding">
                    <img class="order-summery-footer-image" src="{{asset("assets/front-end/png/delivery.png")}}" alt="">
                    <div class="deal-title"> {{\App\CPU\translate('Fast Delivery')}} </div>
{{--                    <div class="deal-title bold px-1">التوصيل مجانا لمدة <span class="num_fam">3</span> أيام</div>--}}
                </div>

                <div class="col-md-3 p-0 text-center">
                    <img class="order-summery-footer-image" src="{{asset("assets/front-end/png/money.png")}}" alt="">
                    <div class="deal-title">{{\App\CPU\translate('money_back_guarantee')}}</div>
{{--                    <div class="deal-title bold px-1">ضمان استرداد المبلغ</div>--}}
                </div>
                <div class="col-md-3 p-0 text-center">
                    <img class="order-summery-footer-image" src="{{asset("assets/front-end/png/Genuine.png")}}" alt="">
                    <div class="deal-title bold px-1">100% {{\App\CPU\translate('genuine')}} {{\App\CPU\translate('product')}}</div>
{{--                    <div class="deal-title bold px-1"><span class="num_fam">100%</span> منتج أصلي</div>--}}
                </div>
                <div class="col-md-3 p-0 text-center">
                    <img class="order-summery-footer-image" src="{{asset("assets/front-end/png/Payment.png")}}" alt="">
                    <div class="deal-title bold px-1">{{\App\CPU\translate('authentic_payment')}}</div>
{{--                    <div class="deal-title bold px-1">دفع موثق</div>--}}
                </div>
            </div>
        </div>
    </div>
</div>

