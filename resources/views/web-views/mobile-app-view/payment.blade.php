<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>
        @yield('title')
    </title>
    <!-- SEO Meta Tags-->
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <!-- Viewport-->
    <meta name="_token" content="{{csrf_token()}}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon and Touch Icons-->
    <link rel="apple-touch-icon" sizes="180x180" href="">
    <link rel="icon" type="image/png" sizes="32x32" href="">
    <link rel="icon" type="image/png" sizes="16x16" href="">

    {{-- <link rel="stylesheet" href="{{asset('public/assets/back-end')}}/css/toastr.css"/> --}}
    <!-- Main Theme Styles + Bootstrap-->
    <link rel="stylesheet" media="screen" href="{{asset('public/assets/front-end')}}/css/theme.min.css">
    <link rel="stylesheet" media="screen" href="{{asset('public/assets/front-end')}}/css/slick.css">
    <link rel="stylesheet" href="{{asset('public/assets/back-end')}}/css/toastr.css"/>
    @stack('css_or_js')

    {{--stripe--}}
    <script src="https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch"></script>
    <script src="https://js.stripe.com/v3/"></script>
    {{--stripe--}}
</head>
<!-- Body-->
<body class="toolbar-enabled">

{{--loader--}}
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div id="loading" style="display: none;">
                <div style="position: fixed;z-index: 9999; left: 40%;top: 37% ;width: 100%">
                    <img width="200"
                         src="{{asset('storage/app/public/company')}}/{{\App\CPU\Helpers::get_business_settings('loader_gif')}}"
                         onerror="this.src='{{asset('public/assets/front-end/img/loader.gif')}}'">
                </div>
            </div>
        </div>
    </div>
</div>
{{--loader--}}

<!-- Page Content-->
<div class="checkout_details container pb-5 mb-2 mb-md-4">
    <div class="row mt-5">
        @php($user=\App\CPU\Helpers::get_customer())
        @php($config=\App\CPU\Helpers::get_business_settings('ssl_commerz_payment'))
        @if($payment_method == 'ssl_commerz_payment' && $config['status'])
            <div class="col-md-6 mb-4" style="cursor: pointer">
                <div class="card">
                    <div class="card-body" style="height: 100px">
                        <form action="{{ url('/pay-ssl') }}" method="POST" class="needs-validation">
                            <input type="hidden" value="{{ csrf_token() }}" name="_token"/>
                            <button class="btn btn-block click-if-alone" type="submit">
                                <img width="150"
                                     src="{{asset('public/assets/front-end/img/sslcomz.png')}}"/>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        @php($config=\App\CPU\Helpers::get_business_settings('paypal'))
        @if($payment_method == 'paypal' && $config['status'])
            <div class="col-md-6 mb-4" style="cursor: pointer">
                <div class="card">
                    <div class="card-body" style="height: 100px">
                        <form class="needs-validation" method="POST" id="payment-form"
                              action="{{route('pay-paypal')}}">
                            {{ csrf_field() }}
                            <button class="btn btn-block click-if-alone" type="submit">
                                <img width="150"
                                     src="{{asset('public/assets/front-end/img/paypal.png')}}"/>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        @php($coupon_discount = session()->has('coupon_discount') ? session('coupon_discount') : 0)
        @php($amount = \App\CPU\CartManager::cart_grand_total() - $coupon_discount)

        @php($config=\App\CPU\Helpers::get_business_settings('stripe'))
        @if($payment_method == 'stripe' && $config['status'])
            <div class="col-md-6 mb-4" style="cursor: pointer">
                <div class="card">
                    <div class="card-body" style="height: 100px">
                        <button class="btn btn-block click-if-alone" type="button" id="checkout-button">
                            <i class="czi-card"></i> {{\App\CPU\translate('Credit / Debit card ( Stripe )')}}
                        </button>
                        <script type="text/javascript">
                            // Create an instance of the Stripe object with your publishable API key
                            var stripe = Stripe('{{$config['published_key']}}');
                            var checkoutButton = document.getElementById("checkout-button");
                            checkoutButton.addEventListener("click", function () {
                                fetch("{{route('pay-stripe')}}", {
                                    method: "GET",
                                }).then(function (response) {
                                    console.log(response)
                                    return response.text();
                                }).then(function (session) {
                                    /*console.log(JSON.parse(session).id)*/
                                    return stripe.redirectToCheckout({sessionId: JSON.parse(session).id});
                                }).then(function (result) {
                                    if (result.error) {
                                        alert(result.error.message);
                                    }
                                }).catch(function (error) {
                                    console.error("{{\App\CPU\translate('Error')}}:", error);
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
        @endif

        @php($config=\App\CPU\Helpers::get_business_settings('razor_pay'))
        @php($inr=\App\Model\Currency::where(['symbol'=>'₹'])->first())
        @php($usd=\App\Model\Currency::where(['code'=>'usd'])->first())
        @if($payment_method == 'razor_pay' && isset($inr) && isset($usd) && $config['status'])
            <div class="col-md-6 mb-4" style="cursor: pointer">
                <div class="card">
                    <div class="card-body" style="height: 100px">
                        <form action="{!!route('payment-razor')!!}" method="POST">
                        @csrf
                        <!-- Note that the amount is in paise = 50 INR -->
                            <!--amount need to be in paisa-->
                            <script src="https://checkout.razorpay.com/v1/checkout.js"
                                    data-key="{{ \Illuminate\Support\Facades\Config::get('razor.razor_key') }}"
                                    data-amount="{{(round(\App\CPU\Convert::usdToinr($amount)))*100}}"
                                    data-buttontext="Pay {{(\App\CPU\Convert::usdToinr($amount))*100}} INR"
                                    data-name="{{\App\Model\BusinessSetting::where(['type'=>'company_name'])->first()->value}}"
                                    data-description=""
                                    data-image="{{asset('storage/app/public/company/'.\App\Model\BusinessSetting::where(['type'=>'company_web_logo'])->first()->value)}}"
                                    data-prefill.name="{{$user->f_name}}"
                                    data-prefill.email="{{$user->email}}"
                                    data-theme.color="#ff7529">
                            </script>
                        </form>
                        <button class="btn btn-block click-if-alone" type="button"
                                onclick="$('.razorpay-payment-button').click()">
                            <img width="150"
                                 src="{{asset('public/assets/front-end/img/razor.png')}}"/>
                        </button>
                    </div>
                </div>
            </div>
        @endif


        @php($config=\App\CPU\Helpers::get_business_settings('paystack'))
        @if($payment_method == 'paystack' && $config['status'])
            <div class="col-md-6 mb-4" style="cursor: pointer">
                <div class="card">
                    <div class="card-body" style="height: 100px">
                        @php($config=\App\CPU\Helpers::get_business_settings('paystack'))
                        @php($order=\App\Model\Order::find(session('order_id')))
                        <form method="POST" action="{{ route('paystack-pay') }}" accept-charset="UTF-8"
                              class="form-horizontal"
                              role="form">
                            @csrf
                            <div class="row">
                                <div class="col-md-8 col-md-offset-2">
                                    <input type="hidden" name="email"
                                           value="{{$user->email}}"> {{-- required --}}
                                    <input type="hidden" name="orderID"
                                           value="{{session('cart_group_id')}}">
                                    <input type="hidden" name="amount"
                                           value="{{\App\CPU\Convert::usdTozar($amount*100)}}"> {{-- required in kobo --}}
                                    <input type="hidden" name="quantity" value="1">
                                    <input type="hidden" name="currency"
                                           value="{{\App\CPU\Helpers::currency_code()}}">
                                    <input type="hidden" name="metadata"
                                           value="{{ json_encode($array = ['key_name' => 'value',]) }}"> {{-- For other necessary things you want to add to your payload. it is optional though --}}
                                    <input type="hidden" name="reference"
                                           value="{{ Paystack::genTranxRef() }}"> {{-- required --}}
                                    <p>
                                        <button class="paystack-payment-button" style="display: none"
                                                type="submit"
                                                value="Pay Now!"></button>
                                    </p>
                                </div>
                            </div>
                        </form>
                        <button class="btn btn-block click-if-alone" type="button"
                                onclick="$('.paystack-payment-button').click()">
                            <img width="100"
                                 src="{{asset('public/assets/front-end/img/paystack.png')}}"/>
                        </button>
                    </div>
                </div>
            </div>
        @endif


        @php($myr=\App\Model\Currency::where(['code'=>'MYR'])->first())
        @php($usd=\App\Model\Currency::where(['code'=>'usd'])->first())
        @php($config=\App\CPU\Helpers::get_business_settings('senang_pay'))
        @if($payment_method == 'senang_pay' && isset($myr) && isset($usd) && $config['status'])
            <div class="col-md-6 mb-4" style="cursor: pointer">
                <div class="card">
                    <div class="card-body" style="height: 100px">
                        @php($config=\App\CPU\Helpers::get_business_settings('senang_pay'))
                        @php($secretkey = $config['secret_key'])
                        @php($data = new \stdClass())
                        @php($data->merchantId = $config['merchant_id'])
                        @php($data->detail = 'payment')
                        @php($data->order_id = session('cart_group_id'))
                        @php($data->amount = \App\CPU\Convert::usdTomyr($amount))
                        @php($data->name = $user->f_name.' '.$user->l_name)
                        @php($data->email = $user->email)
                        @php($data->phone = $user->phone)
                        @php($data->hashed_string = md5($secretkey . urldecode($data->detail) . urldecode($data->amount) . urldecode($data->order_id)))

                        <form name="order" method="post"
                              action="https://{{env('APP_MODE')=='live'?'app.senangpay.my':'sandbox.senangpay.my'}}/payment/{{$config['merchant_id']}}">
                            <input type="hidden" name="detail" value="{{$data->detail}}">
                            <input type="hidden" name="amount" value="{{$data->amount}}">
                            <input type="hidden" name="order_id" value="{{$data->order_id}}">
                            <input type="hidden" name="name" value="{{$data->name}}">
                            <input type="hidden" name="email" value="{{$data->email}}">
                            <input type="hidden" name="phone" value="{{$data->phone}}">
                            <input type="hidden" name="hash" value="{{$data->hashed_string}}">
                        </form>

                        <button class="btn btn-block click-if-alone" type="button"
                                onclick="document.order.submit()">
                            <img width="100"
                                 src="{{asset('public/assets/front-end/img/senangpay.png')}}"/>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @php($config=\App\CPU\Helpers::get_business_settings('paymob_accept'))
        @if($payment_method == 'paymob_accept' && $config['status'])
            <div class="col-md-6 mb-4" style="cursor: pointer">
                <div class="card">
                    <div class="card-body" style="height: 100px">
                        <form class="needs-validation" method="POST" id="payment-form-paymob"
                              action="{{route('paymob-credit')}}">
                            {{ csrf_field() }}
                            <button class="btn btn-block click-if-alone" type="submit">
                                <img width="150"
                                     src="{{asset('public/assets/front-end/img/paymob.png')}}"/>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        @php($config=\App\CPU\Helpers::get_business_settings('bkash'))
        @if($payment_method == 'bkash' && isset($config)  && $config['status'])
            <div class="col-sm-6">
                <div class="card cursor-pointer">
                    <div class="card-body __h-100px">
                        <a class="btn btn-block click-if-alone" onclick="location.href='{{route('bkash-make-payment')}}'">
                            <img width="100" src="{{asset('public/assets/front-end/img/bkash.png')}}"/>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        @php($config=\App\CPU\Helpers::get_business_settings('paytabs'))
        @if($payment_method == 'paytabs' && isset($config)  && $config['status'])
            <div class="col-md-6 mb-4" style="cursor: pointer">
                <div class="card">
                    <div class="card-body" style="height: 100px">
                        <button class="btn btn-block click-if-alone" onclick="location.href='{{route('paytabs-payment')}}'" style="margin-top: -11px">
                            <img width="150" src="{{asset('public/assets/front-end/img/paytabs.png')}}"/>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{--@php($config=\App\CPU\Helpers::get_business_settings('fawry_pay'))
        @if(isset($config)  && $config['status'])
            <div class="col-md-6 mb-4" style="cursor: pointer">
                <div class="card">
                    <div class="card-body" style="height: 100px">
                        <button class="btn btn-block" onclick="location.href='{{route('fawry')}}'" style="margin-top: -11px">
                            <img width="150" src="{{asset('public/assets/front-end/img/fawry.svg')}}"/>
                        </button>
                    </div>
                </div>
            </div>
        @endif--}}

        @php($config=\App\CPU\Helpers::get_business_settings('mercadopago'))
        @if($payment_method == 'mercadopago' && isset($config)  && $config['status'])
            <div class="col-md-6 mb-4" style="cursor: pointer">
                <div class="card">
                    <div class="card-body" style="height: 100px">
                        <a class="btn btn-block click-if-alone" onclick="location.href='{{route('mercadopago.index')}}'">
                            <img width="150" src="{{asset('public/assets/front-end/img/MercadoPago_(Horizontal).svg')}}"/>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        @php($config=\App\CPU\Helpers::get_business_settings('flutterwave'))
        @if($payment_method == 'flutterwave' && isset($config)  && $config['status'])
            <div class="col-md-6 mb-4" style="cursor: pointer">
                <div class="card">
                    <div class="card-body" style="height: 100px">
                        <form method="POST" action="{{ route('flutterwave_pay') }}">
                            {{ csrf_field() }}

                            <button class="btn btn-block click-if-alone" type="submit">
                                <img width="200"
                                    src="{{asset('public/assets/front-end/img/fluterwave.png')}}"/>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        @php($config=\App\CPU\Helpers::get_business_settings('paytm'))
        @if($payment_method == 'paytm' && isset($config) && $config['status'])
            <div class="col-md-6 mb-4" style="cursor: pointer">
                <div class="card">
                    <div class="card-body" style="height: 100px">
                        <a class="btn btn-block click-if-alone" href="{{route('paytm-payment')}}">
                            <img style="max-width: 150px; margin-top: -10px"
                                 src="{{asset('public/assets/front-end/img/paytm.png')}}"/>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        @php($config=\App\CPU\Helpers::get_business_settings('liqpay'))
        @if($payment_method == 'liqpay' && isset($config) && $config['status'])
            <div class="col-md-6 mb-4" style="cursor: pointer">
                <div class="card">
                    <div class="card-body" style="height: 100px">
                        <a class="btn btn-block click-if-alone" href="{{route('liqpay-payment')}}">
                            <img style="max-width: 150px; margin-top: 0px"
                                 src="{{asset('public/assets/front-end/img/liqpay4.png')}}"/>
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script src="{{asset('public/assets/front-end')}}/vendor/jquery/dist/jquery-2.2.4.min.js"></script>
<script src="{{asset('public/assets/front-end')}}/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
{{--Toastr--}}
<script src={{asset("public/assets/back-end/js/toastr.js")}}></script>
<script src="{{asset('public/assets/front-end')}}/js/sweet_alert.js"></script>
{!! Toastr::message() !!}

<script>
    setInterval(function () {
        $('.stripe-button-el').hide()
    }, 10)

    setTimeout(function () {
        $('.stripe-button-el').hide();
        $('.razorpay-payment-button').hide();
    }, 10)
</script>

<script>
    function click_if_alone() {
        let total = $('.checkout_details .click-if-alone').length;
        if (Number.parseInt(total) < 2) {
            $('.click-if-alone').click()
            $('.checkout_details').html('<h1>{{\App\CPU\translate('Redirecting_to_the_payment_page')}}......</h1>');
        }
    }
    click_if_alone();
</script>

</body>
</html>
