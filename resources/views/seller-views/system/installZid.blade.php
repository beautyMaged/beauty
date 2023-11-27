<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Title -->
    <title>{{ \App\CPU\translate('seller_login') }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="favicon.ico">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet">
    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{ asset('assets/back-end') }}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/back-end') }}/css/vendor.min.css">
    <link rel="stylesheet" href="{{ asset('assets/back-end') }}/vendor/icon-set/style.css">
    <!-- CSS Front Template -->
    <link rel="stylesheet" href="{{ asset('assets/back-end') }}/css/toastr.css">
    <link rel="stylesheet" href="{{ asset('assets/back-end') }}/css/theme.minc619.css?v=1.0">
    <link rel="stylesheet" href="{{ asset('assets/back-end') }}/css/style.css">
</head>

<style>
    .zid-btn {
        background-color: #6557ca;
        color: white;
    }

    .zid-btn:hover {
        color: white;
        background-color: #9087d9;
    }
</style>

<body>
    <!-- ========== MAIN CONTENT ========== -->
    <main id="content" role="main" class="main">
        <div class="position-fixed top-0 right-0 left-0 bg-img-hero __h-32rem"
            style="background-image: url({{ asset('assets/admin') }}/svg/components/abstract-bg-4.svg);">
            <!-- SVG Bottom Shape -->
            <figure class="position-absolute right-0 bottom-0 left-0">
                <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                    viewBox="0 0 1921 273">
                    <polygon fill="#fff" points="0,273 1921,273 1921,0 " />
                </svg>
            </figure>
            <!-- End SVG Bottom Shape -->
        </div>

        <!-- Content -->
        <div class="container py-5 py-sm-7">
            @php($e_commerce_logo = \App\Model\BusinessSetting::where(['type' => 'company_web_logo'])->first()->value)
            <a class="d-flex justify-content-center mb-5" href="javascript:">
                <img class="z-index-2" height="40" src="{{ asset('storage/company/' . $e_commerce_logo) }}"
                    alt="Logo"
                    onerror="this.onerror=null;this.src='{{ asset('assets/back-end/img/400x400/img2.jpg') }}'">
            </a>

            <div class="row justify-content-center">
                <div class="col-md-7 col-lg-5">
                    <!-- Card -->
                    <div class="card card-lg mb-5">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center">
                                <img class="m-4 p-4 w-50"
                                    src="https://zid.sa/wp-content/uploads/2023/03/zid-en-logo-colored-1-768x450.png"
                                    alt="Zid">
                                <a href="https://web.zid.sa/account/settings/shipping-requirements/{{ env('ZID_SHIPPING_METHOD_ID') }}"
                                    class="btn btn-lg btn-block zid-btn">{{ \App\CPU\translate('install_zid') }}</a>
                            </div>
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
            </div>
        </div>
        <!-- End Content -->
    </main>
    <!-- ========== END MAIN CONTENT ========== -->


    <!-- JS Implementing Plugins -->
    <script src="{{ asset('assets/back-end') }}/js/vendor.min.js"></script>

    <!-- JS Front -->
    <script src="{{ asset('assets/back-end') }}/js/theme.min.js"></script>
    <script src="{{ asset('assets/back-end') }}/js/toastr.js"></script>
    {!! Toastr::message() !!}


    <!-- JS Plugins Init. -->
    <script></script>

</body>

</html>
