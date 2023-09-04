<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title> @yield('title') </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('storage/company')}}/{{$web_config['fav_icon']->value}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('storage/company')}}/{{$web_config['fav_icon']->value}}">
    <link rel="stylesheet" media="screen" href="{{asset('assets/front-end')}}/vendor/simplebar/dist/simplebar.min.css"/>
    <link rel="stylesheet" media="screen" href="{{asset('assets/front-end')}}/vendor/tiny-slider/dist/tiny-slider.css"/>
    <link rel="stylesheet" media="screen" href="{{asset('assets/front-end')}}/vendor/drift-zoom/dist/drift-basic.min.css"/>
    <link rel="stylesheet" media="screen" href="{{asset('assets/front-end')}}/vendor/lightgallery.js/dist/css/lightgallery.min.css"/>
    <link rel="stylesheet" href="{{asset('assets/back-end')}}/css/toastr.css"/>
    <!-- Main Theme Styles + Bootstrap-->
    <link rel="stylesheet" media="screen" href="{{asset('assets/front-end')}}/css/theme.min.css">
    <link rel="stylesheet" media="screen" href="{{asset('assets/front-end')}}/css/slick.css">
    <link rel="stylesheet" media="screen" href="{{asset('assets/front-end')}}/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="{{asset('assets/back-end')}}/css/toastr.css"/>
    <link rel="stylesheet" href="{{asset('assets/front-end')}}/css/master.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Titillium+Web:wght@400;600;700&display=swap" rel="stylesheet">
    {{-- light box --}}
    <link rel="stylesheet" href="{{asset('css/lightbox.css')}}">
    <link rel="stylesheet" href="{{asset('assets/back-end')}}/vendor/icon-set/style.css">
    @stack('css_or_js')

    <link rel="stylesheet" href="{{asset('assets/front-end')}}/css/home.css"/>
    <link rel="stylesheet" href="{{asset('assets/front-end')}}/css/responsive1.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/font-awesome-line-awesome/css/all.min.css">
    <link rel="stylesheet" href="{{asset('assets/front-end')}}/css/owl.carousel.min.css"/>
    <link rel="stylesheet" href="{{asset('assets/front-end')}}/css/owl.theme.default.min.css"/>
    <link rel="stylesheet" href="{{asset('assets/front-end')}}/css/style.css">
    <link rel="stylesheet" href="{{asset('assets/front-end')}}/css/my_style.css">
    <link rel="stylesheet" href="{{asset('assets/front-end')}}/css/alaa_ar.css">
    <link rel="stylesheet" href="{{asset('assets/front-end')}}/css/media-queries.css">
    <link rel="stylesheet" href="{{asset('assets/front-end/css/select2.min.css')}}">
    {{--dont touch this--}}
    <meta name="_token" content="{{csrf_token()}}">
    {{--dont touch this--}}
<!--to make http ajax request to https-->
    <!--<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">-->
    <style>
    .rtl { direction: {{ Session::get('direction') }}; } .password-toggle-btn .password-toggle-indicator:hover { color: {{$web_config['primary_color']}}; } .password-toggle-btn .custom-control-input:checked ~ .password-toggle-indicator { color: {{$web_config['secondary_color']}}; } .dropdown-item:hover, .dropdown-item:focus { color: {{$web_config['primary_color']}}; } .dropdown-item.active, .dropdown-item:active { color: {{$web_config['secondary_color']}}; } .navbar-light .navbar-tool-icon-box { color: {{$web_config['primary_color']}}; } .search_button { background-color: {{$web_config['primary_color']}}; } .first_banner_item_details { position: absolute; width: 62%; top: 21%; {{session('direction') == 'rtl' ? 'right: 19%;text-align: right;' : 'left: 13%;text-align: left;'}} } .sorting_div { {{session('direction') == 'ltr' ? 'right: 4%;left:auto;' : ''}} } .item_details { width: 70% !important; {{session('direction') == 'ltr' ? 'margin-left: 10px;text-align: left' : 'margin-right: 10px;text-align: right'}} } .footer-web-logo a img { {{session('direction') == 'ltr' ? 'object-position: left center;' : 'object-position: right center;'}} } .large-featured-product { padding: {{session('direction') == 'ltr' ? '0 14px 0 5px!important' : '0 0 0 19px!important;'}} } .navbar-stuck-menu { background-color: {{$web_config['primary_color']}}; } .mega-nav .nav-item .nav-link { color: {{$web_config['primary_color']}} !important; } .checkbox-alphanumeric label:hover { border-color: {{$web_config['primary_color']}}; } ::-webkit-scrollbar-thumb:hover { background: {{$web_config['secondary_color']}} !important; } [type="radio"] { border: 0; clip: rect(0 0 0 0); height: 1px; margin: -1px; overflow: hidden; padding: 0; position: absolute; width: 1px; } [type="radio"] + span:after { box-shadow: 0 0 0 0.10em{{$web_config['secondary_color']}}; } [type="radio"]:checked + span:after { background: {{$web_config['secondary_color']}}; box-shadow: 0 0 0 0.10em{{$web_config['secondary_color']}}; } .navbar-tool .navbar-tool-label { background-color: {{$web_config['secondary_color']}} !important; } .btn--primary { color: #fff; background-color: {{$web_config['primary_color']}} !important; border-color: {{$web_config['primary_color']}} !important; } .btn--primary:hover { color: #fff; background-color: {{$web_config['primary_color']}} !important; border-color: {{$web_config['primary_color']}} !important; } .btn-secondary { background-color: {{$web_config['secondary_color']}} !important; border-color: {{$web_config['secondary_color']}} !important; } .btn-outline-accent:hover { color: #fff; background-color: {{$web_config['primary_color']}}; border-color: {{$web_config['primary_color']}}; } .btn-outline-accent { color: {{$web_config['primary_color']}}; border-color: {{$web_config['primary_color']}}; } .text-accent { color: {{$web_config['primary_color']}}; } a:hover { color: {{$web_config['secondary_color']}}; } .active-menu { color: {{$web_config['secondary_color']}} !important; } .page-item.active > .page-link { box-shadow: 0 0.5rem 1.125rem -0.425rem{{$web_config['primary_color']}} } .page-item.active .page-link { background-color: {{$web_config['primary_color']}}; } .btn-outline-accent:not(:disabled):not(.disabled):active, .btn-outline-accent:not(:disabled):not(.disabled).active, .show > .btn-outline-accent.dropdown-toggle { background-color: {{$web_config['secondary_color']}}; border-color: {{$web_config['secondary_color']}}; } .btn-outline-primary { color: {{$web_config['primary_color']}}; border-color: {{$web_config['primary_color']}}; } .btn-outline-primary:hover { background-color: {{$web_config['secondary_color']}}; border-color: {{$web_config['secondary_color']}}; } .btn-outline-primary:focus, .btn-outline-primary.focus { box-shadow: 0 0 0 0{{$web_config['secondary_color']}}; } .btn-outline-primary:not(:disabled):not(.disabled):active, .btn-outline-primary:not(:disabled):not(.disabled).active, .show > .btn-outline-primary.dropdown-toggle { background-color: {{$web_config['primary_color']}}; border-color: {{$web_config['primary_color']}}; } .btn-outline-primary:not(:disabled):not(.disabled):active:focus, .btn-outline-primary:not(:disabled):not(.disabled).active:focus, .show > .btn-outline-primary.dropdown-toggle:focus { box-shadow: 0 0 0 0{{$web_config['primary_color']}}; } .for-discoutn-value { background: #ed165f; } .dropdown-menu { margin- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: -8px !important; }
        /* */
    </style>

    @php($google_tag_manager_id = \App\CPU\Helpers::get_business_settings('google_tag_manager_id'))
    @if($google_tag_manager_id )
    <!-- Google Tag Manager -->
        <script>(function (w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start':
                        new Date().getTime(), event: 'gtm.js'
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', '{{$google_tag_manager_id}}');</script>
        <!-- End Google Tag Manager -->
    @endif

    @php($pixel_analytices_user_code =\App\CPU\Helpers::get_business_settings('pixel_analytics'))
    @if($pixel_analytices_user_code)
    <!-- Facebook Pixel Code -->
        <script>
            !function (f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function () {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window, document, 'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{your-pixel-id-goes-here}');
            fbq('track', 'PageView');
        </script>
        <noscript>
            <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={your-pixel-id-goes-here}&ev=PageView&noscript=1"/>
        </noscript>
        <!-- End Facebook Pixel Code -->
    @endif
</head>
<!-- Body-->
<body class="toolbar-enabled">

@if($google_tag_manager_id)
    <!-- Google Tag Manager (noscript) -->
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id={{$google_tag_manager_id}}"
                height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    <!-- End Google Tag Manager (noscript) -->
@endif
<!-- Sign in / sign up modal-->
@include('layouts.front-end.partials._modals')
@include('layouts.front-end.partials._location_modal')
<!-- Navbar-->
<!-- Quick View Modal-->
@include('layouts.front-end.partials._quick-view-modal')
<!-- Navbar Electronics Store-->

{{--loader--}}
<div class="row" style="width: 100%;height: 100vh;z-index: 100000;position: fixed;top:0;background: #fff;padding: 0!important;margin: 0!important;">
    <div class="col-12" style="margin-top:13rem;position: fixed;z-index: 9999;">
        <div id="loading" style="">
            <center>
                <img width="200" src="{{asset('storage/company')}}/{{\App\CPU\Helpers::get_business_settings('loader_gif')}}" onerror="this.onerror=null;this.src='{{asset('assets/front-end/img/loader.gif')}}'">
            </center>
        </div>
    </div>
</div>
{{--loader--}}
<div class="overlay">

</div>
@include('layouts.front-end.partials._header')
<!-- Page title-->

<!-- Page Content-->
@yield('content')

<!-- Footer-->
<!-- Footer-->
@include('layouts.front-end.partials._footer')
<!-- Toolbar for handheld devices-->

<!-- Back To Top Button-->
<a class="btn-scroll-top" href="#top" data-scroll>
    <span class="btn-scroll-top-tooltip text-muted font-size-sm mr-2">Top</span><i
        class="btn-scroll-top-icon czi-arrow-up"> </i>
</a>
<div class="__floating-btn d-none">
    @php($whatsapp = \App\CPU\Helpers::get_business_settings('whatsapp'))
    @if(isset($whatsapp['status']) && $whatsapp['status'] == 1 )
        <div class="wa-widget-send-button">
            <a href="https://web.whatsapp.com/send/?phone={{ $whatsapp['phone'] }}?text=Hello%20there!" target="_blank">
                <img src="{{asset('assets/front-end/img/whatsapp.svg')}}" class="wa-messenger-svg-whatsapp wh-svg-icon" alt="Chat with us on WhatsApp">
            </a>
        </div>
@endif

</div>

{{--<script src="{{asset('assets/front-end')}}/vendor/jquery/dist/jquery.slim.min.js"></script>--}}
<script src="{{asset('assets/front-end')}}/vendor/jquery/dist/jquery-2.2.4.min.js"></script>
<script src="{{asset('assets/front-end')}}/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('assets/front-end')}}/vendor/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
<script src="{{asset('assets/front-end')}}/vendor/simplebar/dist/simplebar.min.js"></script>
<script src="{{asset('assets/front-end')}}/vendor/tiny-slider/dist/min/tiny-slider.js"></script>
<script src="{{asset('assets/front-end')}}/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>

{{-- light box --}}
<script src="{{asset('js/lightbox.min.js')}}"></script>
<script src="{{asset('assets/front-end')}}/vendor/drift-zoom/dist/Drift.min.js"></script>
<script src="{{asset('assets/front-end')}}/vendor/lightgallery.js/dist/js/lightgallery.min.js"></script>
<script src="{{asset('assets/front-end')}}/vendor/lg-video.js/dist/lg-video.min.js"></script>
<script src="{{asset('assets/front-end/js/select2.min.js')}}"></script>
{{--Toastr--}}
<script src={{asset("assets/back-end/js/toastr.js")}}></script>
<!-- Main theme script-->
<script src="{{asset('assets/front-end')}}/js/theme.min.js"></script>
<script src="{{asset('assets/front-end')}}/js/slick.min.js"></script>

<script src="{{asset('assets/front-end')}}/js/sweet_alert.js"></script>
{{--Toastr--}}
<script src={{asset("assets/back-end/js/toastr.js")}}></script>
<script src="{{asset('assets/front-end')}}/js/owl.carousel.min.js"></script>

{!! Toastr::message() !!}

<script>
    $(document).ready(function () {
        $('#loading').parent().parent().css({
            'width': 'auto',
            'height': 'auto',
            'z-index': '100000;',
            'position': 'absolute;',
            'top': '0',
            'background': 'inherit'
        });
        $('#loading').hide();
    });

    function addWishlist(product_id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
            }
        });
        $.ajax({
            url: "{{route('store-wishlist')}}",
            method: 'POST',
            data: {
                product_id: product_id
            },
            success: function (data) {
                if (data.value == 1) {
                    Swal.fire({
                        // position: 'top-end',
                        type: 'success',
                        title: data.success,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('.countWishlist').html(data.count);
                    $('.countWishlist-' + product_id).text(data.product_count);
                    $('.tooltip').html('');

                } else if (data.value == 2) {
                    Swal.fire({
                        type: 'info',
                        title: 'WishList',
                        text: data.error
                    });
                } else {
                    Swal.fire({
                        type: 'error',
                        title: 'WishList',
                        text: data.error
                    });
                }
            }
        });
    }

    function removeWishlist(product_id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{route('delete-wishlist')}}",
            method: 'POST',
            data: {
                id: product_id
            },
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (data) {
                Swal.fire({
                    type: 'success',
                    title: 'WishList',
                    text: data.success
                });
                $('.countWishlist').html(data.count);
                $('#set-wish-list').html(data.wishlist);
                $('.tooltip').html('');
            },
            complete: function () {
                $('#loading').hide();
            },
        });
    }

    function quickView(product_id) {
        $.get({
            url: '{{route('quick-view')}}',
            dataType: 'json',
            data: {
                product_id: product_id
            },
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (data) {
                console.log("success...")
                $('#quick-view').modal('show');
                $('#quick-view-modal').empty().html(data.view);
            },
            complete: function () {
                $('#loading').hide();
            },
        });
    }

    function getStockFromChoices() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        window.setTimeout(function () {
            $.post({
                url: '{{ route('cart.getStock') }}',
                data: $('#add-to-cart-form').serializeArray(),

                success: function (response) {
                    console.log(response);
                    if (response.status == 1) {
                        choices_stock.text(response.message)
                        deleted_price.text(response.price)
                    } else if (response.status == 0) {
                        choices_stock.text(response.message)
                    }
                }
            });
        }, 100);
    }

    getStockFromChoices();

    let option_btn = $('.option_btn');
    let choices_stock = $('#choices_stock');
    let deleted_price = $('#deleted_price');
    let color_btn = $('.color_btn');

    option_btn.on('click', function () {
        getStockFromChoices();
    });
    color_btn.on('click', function () {
        getStockFromChoices();
    });


    function addToCart(form_id = 'add-to-cart-form', redirect_to_checkout = false) {
        if (checkAddToCartValidity()) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('cart.add') }}',
                data: $('#' + form_id).serializeArray(),
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (response) {
                    console.log(response);
                    if (response.status == 1) {
                        updateNavCart();
                        Swal.fire({
                            icon: 'success',
                            text: response.message,
                            dangerMode: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'حسنا',
                            showCloseButton: true,
                        });
                        // toastr.success(response.message, {
                        //     CloseButton: true,
                        //     ProgressBar: true
                        // });
                        $('.call-when-done').click();
                        if (redirect_to_checkout) {
                            location.href = "{{route('checkout-details')}}";
                        }
                        return false;
                    } else if (response.status == 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Cart',
                            text: response.message
                        });
                        return false;
                    }
                },
                complete: function () {
                    $('#loading').hide();

                }
            });
        } else {
            Swal.fire({
                type: 'info',
                title: 'Cart',
                text: '{{\App\CPU\translate('please_choose_all_the_options')}}'
            });
        }
    }

    function buy_now() {
        addToCart('add-to-cart-form', true);
        /* location.href = "{{route('checkout-details')}}"; */
    }

    function currency_change(currency_code) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: '{{route('currency.change')}}',
            data: {
                currency_code: currency_code
            },
            success: function (data) {
                toastr.success('{{\App\CPU\translate('Currency changed to')}}' + data.name);
                location.reload();
            }
        });
    }

    let user_type = "{{App\CPU\Helpers::get_customer()}}";

    // alert(user_type);
    function removeFromCart(key) {
        $.post('{{ route('cart.remove') }}', {_token: '{{ csrf_token() }}', key: key}, function (response) {
            $('#cod-for-cart').hide();
            updateNavCart();
            $('#cart-summary').empty().html(response.data);

            toastr.info('{{\App\CPU\translate('Item has been removed from cart')}}', {
                CloseButton: true,
                ProgressBar: true
            });
            let segment_array = window.location.pathname.split('/');
            let segment = segment_array[segment_array.length - 1];
            if (segment === 'checkout-payment' || segment === 'checkout-details') {
                location.reload();
            }
        });
    }

    function updateNavCart() {
        $.post('{{route('cart.nav-cart')}}', {_token: '{{csrf_token()}}'}, function (response) {
            $('#cart_items').html(response.data);
            $('#cart_items_mobile').html(response.data);
        });
    }

    function cartQuantityInitialize() {
        $('.btn-number').click(function (e) {
            e.preventDefault();

            fieldName = $(this).attr('data-field');
            type = $(this).attr('data-type');
            productType = $(this).attr('product-type');
            var input = $("input[name='" + fieldName + "']");
            var currentVal = parseInt(input.val());

            if (!isNaN(currentVal)) {
                console.log(productType)
                if (type == 'minus') {

                    if (currentVal > input.attr('min')) {
                        input.val(currentVal - 1).change();
                    }
                    if (parseInt(input.val()) == input.attr('min')) {
                        $(this).attr('disabled', true);
                    }

                } else if (type == 'plus') {

                    if (currentVal < input.attr('max') || (productType === 'digital')) {
                        input.val(currentVal + 1).change();
                    }

                    if ((parseInt(input.val()) == input.attr('max')) && (productType === 'physical')) {
                        $(this).attr('disabled', true);
                    }

                }
            } else {
                input.val(0);
            }
        });

        $('.input-number').focusin(function () {
            $(this).data('oldValue', $(this).val());
        });

        $('.input-number').change(function () {
            productType = $(this).attr('product-type');
            minValue = parseInt($(this).attr('min'));
            maxValue = parseInt($(this).attr('max'));
            valueCurrent = parseInt($(this).val());

            var name = $(this).attr('name');
            if (valueCurrent >= minValue) {
                $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Cart',
                    text: '{{\App\CPU\translate('Sorry, the minimum order quantity does not match')}}'
                });
                $(this).val($(this).data('oldValue'));
            }
            if (productType === 'digital' || valueCurrent <= maxValue) {
                $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Cart',
                    text: '{{\App\CPU\translate('Sorry, stock limit exceeded')}}.'
                });
                $(this).val($(this).data('oldValue'));
            }


        });
        $(".input-number").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    }

    function updateQuantity(key, element) {
        $.post('<?php echo e(route('cart.updateQuantity')); ?>', {
            _token: '<?php echo e(csrf_token()); ?>',
            key: key,
            quantity: element.value
        }, function (data) {
            updateNavCart();
            $('#cart-summary').empty().html(data);
        });
    }

    function updateCartQuantity(minimum_order_qty, key) {
        /* var quantity = $("#cartQuantity" + key).children("option:selected").val(); */
        var quantity = $("#cartQuantity" + key).val();
        if (minimum_order_qty > quantity) {
            toastr.error('{{\App\CPU\translate("minimum_order_quantity_cannot_be_less_than_")}}' + minimum_order_qty);
            $("#cartQuantity" + key).val(minimum_order_qty);
            return false;
        }

        $.post('{{route('cart.updateQuantity')}}', {
            _token: '{{csrf_token()}}',
            key: key,
            quantity: quantity
        }, function (response) {
            if (response.status == 0) {
                toastr.error(response.message, {
                    CloseButton: true,
                    ProgressBar: true
                });
                $("#cartQuantity" + key).val(response['qty']);
            } else {
                updateNavCart();
                $('#cart-summary').empty().html(response);
            }
        });
    }

    $('#add-to-cart-form input').on('change', function () {
        getVariantPrice();
    });

    function getVariantPrice() {
        if ($('#add-to-cart-form input[name=quantity]').val() > 0 && checkAddToCartValidity()) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '{{ route('cart.variant_price') }}',
                data: $('#add-to-cart-form').serializeArray(),
                success: function (data) {
                    $('#add-to-cart-form #chosen_price_div').removeClass('d-none');
                    $('#add-to-cart-form #chosen_price_div #chosen_price').html(data.price);
                    $('#set-tax-amount').html(data.tax);
                    $('#set-discount-amount').html(data.discount);
                    $('#available-quantity').html(data.quantity);
                    $('.cart-qty-field').attr('max', data.quantity);
                }
            });
        }
    }

    function checkAddToCartValidity() {
        var names = {};
        $('#add-to-cart-form input:radio').each(function () { // find unique names
            names[$(this).attr('name')] = true;
        });
        var count = 0;
        $.each(names, function () { // then count them
            count++;
        });
        if ($('input:radio:checked').length == count) {
            return true;
        }
        return false;
    }

    if ($(window).width() > 720) {
        @if(Request::is('/') &&  \Illuminate\Support\Facades\Cookie::has('popup_banner')==false)
        $(document).ready(function () {
            $('#popup-modal').appendTo("body").modal('show');
        });
        @php(\Illuminate\Support\Facades\Cookie::queue('popup_banner', 'off', 1))
        @endif
    }


    $(".clickable").click(function () {
        window.location = $(this).find("a").attr("href");
        return false;
    });
</script>

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

<script>
    function couponCode() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: '{{ route('coupon.apply') }}',
            data: $('#coupon-code-ajax').serializeArray(),
            success: function (data) {
                /* console.log(data);
                return false; */
                if (data.status == 1) {
                    let ms = data.messages;
                    ms.forEach(
                        function (m, index) {
                            toastr.success(m, index, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    );
                } else {
                    let ms = data.messages;
                    ms.forEach(
                        function (m, index) {
                            toastr.error(m, index, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    );
                }
                setInterval(function () {
                    location.reload();
                }, 2000);
            }
        });
    }

    jQuery(".search-bar-input").keyup(function () {
        $(".search-card").css("display", "block");
        let name = $(".search-bar-input").val();
        if (name.length > 0) {
            $.get({
                url: '{{url('/')}}/searched-products',
                dataType: 'json',
                data: {
                    name: name
                },
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('.search-result-box').empty().html(data.result)
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        } else {
            $('.search-result-box').empty();
        }
    });

    jQuery(".search-bar-input-mobile").keyup(function () {
        $(".search-card").css("display", "block");
        let name = $(".search-bar-input-mobile").val();
        if (name.length > 0) {
            $.get({
                url: '{{url('/')}}/searched-products',
                dataType: 'json',
                data: {
                    name: name
                },
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('.search-result-box').empty().html(data.result)
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        } else {
            $('.search-result-box').empty();
        }
    });

    jQuery(document).mouseup(function (e) {
        var container = $(".search-card");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.hide();
        }
    });

    const img = document.getElementsByTagName("img")[0];
    img.addEventListener("error", function (event) {
        event.target.src = '{{asset('assets/front-end/img/image-place-holder.png')}}';
        event.onerror = null
    });


    function route_alert(route, message) {
        Swal.fire({
            title: '{{\App\CPU\translate('Are you sure')}}?',
            text: message,
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '{{$web_config['primary_color']}}',
            cancelButtonText: '{{\App\CPU\translate('No')}}',
            confirmButtonText: '{{\App\CPU\translate('Yes')}}',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                location.href = route;
            }
        })
    }
</script>
<script>
    $('.filter-show-btn').on('click', function () {
        $('#shop-sidebar').toggleClass('show')
    })
</script>

<script>
        @php($cookie = $web_config['cookie_setting'] ? json_decode($web_config['cookie_setting']['value'], true):null)
    let cookie_content = `
        <div class="cookie-section">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-between column-gap-4 row-gap-3">
                    <div class="text-wrapper">
                        <h5 class="title">{{\App\CPU\translate('Your_Privacy_Matter')}}</h5>
                        <div>{{ $cookie ? $cookie['cookie_text'] : '' }}</div>
                    </div>
                    <div class="btn-wrapper">
                        <span class="text-white cursor-pointer" id="cookie-reject">{{\App\CPU\translate('no')}}, {{\App\CPU\translate('thanks')}}</span>
                        <button class="btn btn-success cookie-accept" id="cookie-accept">{{\App\CPU\translate('yes')}}, {{\App\CPU\translate('i_Accept')}}</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    $(document).on('click', '#cookie-accept', function () {
        document.cookie = '6valley_cookie_consent=accepted; max-age=' + 60 * 60 * 24 * 30;
        $('#cookie-section').hide();
    });
    $(document).on('click', '#cookie-reject', function () {
        document.cookie = '6valley_cookie_consent=reject; max-age=' + 60 * 60 * 24;
        $('#cookie-section').hide();
    });

    $(document).ready(function () {

        // let city_loc = $("#city_loc");
        // city_loc.select2();

        if (document.cookie.indexOf("6valley_cookie_consent=accepted") !== -1) {
            $('#cookie-section').hide();
        } else {
            $('#cookie-section').html(cookie_content).show();
        }

        let map_lister = $('#map_lister');
        let list_of_countries = $('#list_of_countries');
        map_lister.hover(function () {
            list_of_countries.css('display', 'block');
        }, function () {
            list_of_countries.css('display', 'none');
        });
        list_of_countries.hover(function () {
            list_of_countries.css('display', 'block');
        }, function () {
            list_of_countries.css('display', 'none');
        });

    });
</script>
@stack('script')

<script type="text/javascript" id="zsiqchat">var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq || {widgetcode: "siqb4bb6cdb038e2e2925f53c51a1c992dbaa3417822fc48d1ac46c878102063830", values:{},ready:function(){}};var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;s.src="https://salesiq.zohopublic.com/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);</script>
// Start of Tawk.to Script

// <script type="text/javascript">
//     var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
//     (function () {
//         var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
//         s1.async = true;
//         s1.src = 'https://embed.tawk.to/64a67a9ecc26a871b026ade9/1h4l4tvbo';
//         s1.charset = 'UTF-8';
//         s1.setAttribute('crossorigin', '*');
//         s0.parentNode.insertBefore(s1, s0);
//     })();
// </script>

// End of Tawk.to Script

</body>
</html>
