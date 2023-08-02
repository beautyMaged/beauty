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

    <link rel="stylesheet" href="{{asset('public/assets/back-end')}}/css/toastr.css"/>
    <!-- Main Theme Styles + Bootstrap-->
    <link rel="stylesheet" media="screen" href="{{asset('public/assets/front-end')}}/css/theme.min.css">
    <link rel="stylesheet" media="screen" href="{{asset('public/assets/front-end')}}/css/slick.css">
    <link rel="stylesheet" href="{{asset('public/assets/back-end')}}/css/toastr.css"/>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif
        }

        .container {
            margin: 30px auto
        }

        .container .card {
            width: 100%;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            background: #fff;
            border-radius: 0px
        }

        body {
            background: #eee
        }

        .btn.btn--primary {
            background-color: #ddd;
            color: black;
            box-shadow: none;
            border: none;
            font-size: 20px;
            width: 100%;
            height: 100%
        }

        .btn.btn--primary:focus {
            box-shadow: none
        }

        .container .card .img-box {
            width: 80px;
            height: 50px
        }

        .container .card img {
            width: 100%;
            object-fit: fill
        }

        .container .card .number {
            font-size: 24px
        }

        .container .card-body .btn.btn--primary .fab.fa-cc-paypal {
            font-size: 32px;
            color: #3333f7
        }

        .fab.fa-cc-amex {
            color: #1c6acf;
            font-size: 32px
        }

        .fab.fa-cc-mastercard {
            font-size: 32px;
            color: red
        }

        .fab.fa-cc-discover {
            font-size: 32px;
            color: orange
        }

        .c-green {
            color: green
        }

        .box {
            height: 40px;
            width: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #ddd
        }

        .btn.btn--primary.payment {
            background-color: #1c6acf;
            color: white;
            border-radius: 0px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 24px
        }

        .form__div {
            height: 50px;
            position: relative;
            margin-bottom: 24px
        }

        .form-control {
            width: 100%;
            height: 45px;
            font-size: 14px;
            border: 1px solid #DADCE0;
            border-radius: 0;
            outline: none;
            padding: 2px;
            background: none;
            z-index: 1;
            box-shadow: none
        }

        .form__label {
            position: absolute;
            left: 16px;
            top: 10px;
            background-color: #fff;
            color: #80868B;
            font-size: 16px;
            transition: .3s;
            text-transform: uppercase
        }

        .form-control:focus + .form__label {
            top: -8px;
            left: 12px;
            color: #1A73E8;
            font-size: 12px;
            font-weight: 500;
            z-index: 10
        }

        .form-control:not(:placeholder-shown).form-control:not(:focus) + .form__label {
            top: -8px;
            left: 12px;
            font-size: 12px;
            font-weight: 500;
            z-index: 10
        }

        .form-control:focus {
            border: 1.5px solid #1A73E8;
            box-shadow: none
        }
    </style>
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

<div class="container">
    <div class="row">
        <div class="col-12 mb-2">
            <center>
                <img style="width: 300px" src="{{asset('public/assets/front-end/img/fawry.svg')}}">
            </center>
        </div>

        <div class="col-12 mt-4">
            <div class="card p-3">
                <p class="mb-0 fw-bold h4">Payment BY Card ( Fawry Pay )</p>
            </div>
        </div>
        <div class="col-12">
            <div class="card p-3">
                <div class="card-body border p-0">
                    <div class="collapse show p-3 pt-0">
                        <div class="row">
                            <div class="col-lg-5 mb-lg-0 mb-3">
                                @php($coupon_discount = session()->has('coupon_discount') ? session('coupon_discount') : 0)
                                @php($amount = \App\CPU\CartManager::cart_grand_total() - $coupon_discount)

                                <p class="h4 mb-0">Order Amount</p>
                                <p class="mb-0"><span class="fw-bold">Price : </span> <span
                                        class="c-green">{{\App\CPU\Helpers::set_symbol($amount)}}</span></p>
                            </div>
                            <div class="col-lg-7">
                                <form action="{{route('fawry-payment')}}" class="form" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form__div">
                                                <input type="number" name="card_number" class="form-control" placeholder=" " required>
                                                <label for="" class="form__label">Card Number</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form__div">
                                                <input type="number" name="month" class="form-control" placeholder=" " required>
                                                <label for="" class="form__label">MM</label>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form__div">
                                                <input type="number" name="year" class="form-control" placeholder=" " required>
                                                <label for="" class="form__label">yy</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form__div">
                                                <input type="password" name="cvv" class="form-control" placeholder=" " required>
                                                <label for="" class="form__label">cvv code</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form__div">
                                                <input type="text" name="card_name" class="form-control" placeholder="ex : visa" required>
                                                <label for="" class="form__label">name of the card</label></div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn--primary payment w-100">Sumbit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('public/assets/front-end')}}/vendor/jquery/dist/jquery-2.2.4.min.js"></script>
<script src="{{asset('public/assets/front-end')}}/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('public/assets/front-end')}}/js/sweet_alert.js"></script>

{{--Toastr--}}
<script src={{asset("public/assets/back-end/js/toastr.js")}}></script>
{!! Toastr::message() !!}

@if ($errors->any())
    <script>
        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif
</body>
</html>
