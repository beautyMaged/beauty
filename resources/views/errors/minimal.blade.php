<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>
        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
        <link rel="stylesheet" media="screen"
              href="{{asset('public/assets/front-end')}}/vendor/simplebar/dist/simplebar.min.css"/>
        <link rel="stylesheet" media="screen"
              href="{{asset('public/assets/front-end')}}/vendor/tiny-slider/dist/tiny-slider.css"/>
        <link rel="stylesheet" media="screen"
              href="{{asset('public/assets/front-end')}}/vendor/drift-zoom/dist/drift-basic.min.css"/>
        <link rel="stylesheet" media="screen"
              href="{{asset('public/assets/front-end')}}/vendor/lightgallery.js/dist/css/lightgallery.min.css"/>
        <link rel="stylesheet" href="{{asset('public/assets/back-end')}}/css/toastr.css"/>
        <!-- Main Theme Styles + Bootstrap-->
        <link rel="stylesheet" media="screen" href="{{asset('public/assets/front-end')}}/css/theme.min.css">
        <link rel="stylesheet" media="screen" href="{{asset('public/assets/front-end')}}/css/slick.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
              integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <link rel="stylesheet" href="{{asset('public/assets/back-end')}}/css/toastr.css"/>
        <link rel="stylesheet" href="{{asset('public/assets/front-end')}}/css/master.css"/>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Titillium+Web:wght@400;600;700&display=swap"
              rel="stylesheet">
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .code {
                border-right: 2px solid;
                font-size: 26px;
                padding: 0 15px 0 15px;
                text-align: center;
            }

            .message {
                font-size: 18px;
                text-align: center;
            }
        </style>
        <style>
            .password-toggle-btn .password-toggle-indicator:hover {
                color: blue;
            }

            .password-toggle-btn .custom-control-input:checked ~ .password-toggle-indicator {
                color: black;
            }

            .dropdown-item:hover, .dropdown-item:focus {
                color: blue;
                text-decoration: none;
                background-color: rgba(0, 0, 0, 0)
            }

            .dropdown-item.active, .dropdown-item:active {
                color: black;
                text-decoration: none;
                background-color: rgba(0, 0, 0, 0)
            }

            .topbar {
                background-color: #efefef;
            }

            .topbar a {
                color: black !important;
            }

            .navbar-light .navbar-tool-icon-box {
                color: blue;
            }

            .search_button {
                background-color: blue;
                border: none;
            }

            .search_form {
                border: 1px solidblue;
                border-radius: 5px;
            }

            .nav-link {
                color: white !important;
            }

            .navbar-stuck-menu {
                background-color: blue;
                min-height: 0;
                padding-top: 0;
                padding-bottom: 0;
            }

            .mega-nav {
                background: white;
                position: relative;
                margin-top: 6px;
                line-height: 17px;
                width: 251px;
                border-radius: 3px;
            }

            .mega-nav .nav-item .nav-link {
                padding-top: 11px !important;
                color: blue                !important;
                font-size: 20px;
                font-weight: 600;
                padding-left: 20px !important;
            }

            .nav-item .dropdown-toggle::after {
                margin-left: 20px !important;
            }

            .navbar-tool-text {
                padding-left: 5px !important;
                font-size: 16px;
            }

            .navbar-tool-text > small {
                color: #4b566b !important;
            }

            .modal-header .nav-tabs .nav-item .nav-link {
                color: black !important;
                /*border: 1px solid #E2F0FF;*/
            }

            .checkbox-alphanumeric::after,
            .checkbox-alphanumeric::before {
                content: '';
                display: table;
            }

            .checkbox-alphanumeric::after {
                clear: both;
            }

            .checkbox-alphanumeric input {
                left: -9999px;
                position: absolute;
            }

            .checkbox-alphanumeric label {
                width: 2.25rem;
                height: 2.25rem;
                float: left;
                padding: 0.375rem 0;
                margin-right: 0.375rem;
                display: block;
                color: #818a91;
                font-size: 0.875rem;
                font-weight: 400;
                text-align: center;
                background: transparent;
                text-transform: uppercase;
                border: 1px solid #e6e6e6;
                border-radius: 2px;
                -webkit-transition: all 0.3s ease;
                -moz-transition: all 0.3s ease;
                -o-transition: all 0.3s ease;
                -ms-transition: all 0.3s ease;
                transition: all 0.3s ease;
                transform: scale(0.95);
            }

            .checkbox-alphanumeric-circle label {
                border-radius: 100%;
            }

            .checkbox-alphanumeric label > img {
                max-width: 100%;
            }

            .checkbox-alphanumeric label:hover {
                cursor: pointer;
                border-color: blue;
            }

            .checkbox-alphanumeric input:checked ~ label {
                transform: scale(1.1);
                border-color: red !important;
            }

            .checkbox-alphanumeric--style-1 label {
                width: auto;
                padding-left: 1rem;
                padding-right: 1rem;
                border-radius: 2px;
            }

            .d-table.checkbox-alphanumeric--style-1 {
                width: 100%;
            }

            .d-table.checkbox-alphanumeric--style-1 label {
                width: 100%;
            }

            /* CUSTOM COLOR INPUT */
            .checkbox-color::after,
            .checkbox-color::before {
                content: '';
                display: table;
            }

            .checkbox-color::after {
                clear: both;
            }

            .checkbox-color input {
                left: -9999px;
                position: absolute;
            }

            .checkbox-color label {
                width: 2.25rem;
                height: 2.25rem;
                float: left;
                padding: 0.375rem;
                margin-right: 0.375rem;
                display: block;
                font-size: 0.875rem;
                text-align: center;
                opacity: 0.7;
                border: 2px solid #d3d3d3;
                border-radius: 50%;
                -webkit-transition: all 0.3s ease;
                -moz-transition: all 0.3s ease;
                -o-transition: all 0.3s ease;
                -ms-transition: all 0.3s ease;
                transition: all 0.3s ease;
                transform: scale(0.95);
            }

            .checkbox-color-circle label {
                border-radius: 100%;
            }

            .checkbox-color label:hover {
                cursor: pointer;
                opacity: 1;
            }

            .checkbox-color input:checked ~ label {
                transform: scale(1.1);
                opacity: 1;
                border-color: red !important;
            }

            .checkbox-color input:checked ~ label:after {
                content: "\f121";
                font-family: "Ionicons";
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                color: rgba(255, 255, 255, 0.7);
                font-size: 14px;
            }

            .card-img-top img, figure {
                max-width: 200px;
                max-height: 200px !important;
                vertical-align: middle;
            }

            .product-card {
                box-shadow: 1px 1px 6px #00000014;
                border-radius: 5px;
                height: 380px;
            }

            .product-card .card-header {
                /*background-color: #F9F9F9 ;*/
                height: 268px;
                text-align: center;
                background: #F9F9F9 0% 0% no-repeat padding-box;
                border-radius: 5px 5px 0px 0px;
            }

            .product-title1 {
                font-family: 'Roboto', sans-serif !important;
                font-weight: 400 !important;
                font-size: 22px !important;
                color: #000000 !important;
                position: relative;
                display: inline-block;
                word-wrap: break-word;
                overflow: hidden;
                max-height: 2.4em; /* (Number of lines you want visible) * (line-height) */
                line-height: 1.2em;
            }

            .product-title {
                font-family: 'Roboto', sans-serif !important;
                font-weight: 400 !important;
                font-size: 22px !important;
                color: #000000 !important;
            }

            .product-price {
                max-width: 160px;
            }

            .product-price .text-accent {
                font-family: 'Roboto', sans-serif;
                font-weight: 700;
                font-size: 17px;
                color: blue;
            }

            .feature_header {
                display: flex;
                justify-content: center;

            }

            .feature_header span {
                padding-right: 15px;
                padding-left: 15px;
                font-weight: 700;
                font-size: 25px;
                background-color: #ffffff;
                text-transform: uppercase;
            }

            @media (max-width: 768px ) {
                .feature_header {
                    margin-top: 0;
                    display: flex;
                    justify-content: flex-start !important;

                }

                .feature_header span {
                    padding-right: 0;
                    padding-left: 0;
                    font-weight: 700;
                    font-size: 25px;
                    background-color: #ffffff;
                    text-transform: uppercase;
                }

                .view_border {
                    margin: 16px 0px;
                    border-top: 2px solid #E2F0FF !important;
                }

            }

            .scroll-bar {
                max-height: calc(100vh - 100px);
                overflow-y: auto !important;
            }

            ::-webkit-scrollbar-track {
                box-shadow: inset 0 0 5px grey;
                border-radius: 5px;
            }

            ::-webkit-scrollbar {
                width: 3px;
            }

            ::-webkit-scrollbar-thumb {
                background: blue;
                border-radius: 5px;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: blue;
            }

            .mobileshow {
                display: none;
            }

            @media screen and (max-width: 500px) {
                .mobileshow {
                    display: block;
                }
            }

            [type="radio"] {
                border: 0;
                clip: rect(0 0 0 0);
                height: 1px;
                margin: -1px;
                overflow: hidden;
                padding: 0;
                position: absolute;
                width: 1px;
            }

            [type="radio"] + span:after {
                content: '';
                display: inline-block;
                width: 1.1em;
                height: 1.1em;
                vertical-align: -0.10em;
                border-radius: 1em;
                border: 0.35em solid #fff;
                box-shadow: 0 0 0 0.10emblack;
                margin-left: 0.75em;
                transition: 0.5s ease all;
            }

            [type="radio"]:checked + span:after {
                background: black;
                box-shadow: 0 0 0 0.10emblack;
            }

            [type="radio"]:focus + span::before {
                font-size: 1.2em;
                line-height: 1;
                vertical-align: -0.125em;
            }


            .checkbox-color label {
                box-shadow: 0px 3px 6px #0000000D;
                border: none;
                border-radius: 3px !important;
                max-height: 35px;
            }

            .checkbox-color input:checked ~ label {
                transform: scale(1.1);
                opacity: 1;
                border: 1px solid #ffb943 !important;
            }

            .checkbox-color input:checked ~ label:after {
                font-family: "Ionicons", serif;
                position: absolute;
                content: "\2713" !important;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                color: rgba(255, 255, 255, 0.7);
                font-size: 14px;
            }

            .navbar-tool .navbar-tool-label {
                position: absolute;
                top: -.3125rem;
                right: -.3125rem;
                width: 1.25rem;
                height: 1.25rem;
                border-radius: 50%;
                background-color: black               !important;
                color: #fff;
                font-size: .75rem;
                font-weight: 500;
                text-align: center;
                line-height: 1.25rem;
            }

            .btn--primary {
                color: #fff;
                background-color: blue               !important;
                border-color: blue               !important;
            }

            .btn--primary:hover {
                color: #fff;
                background-color: blue               !important;
                border-color: blue               !important;
            }

            .btn-secondary {
                color: #fff;
                background-color: black               !important;
                border-color: black               !important;
            }

            .btn-secondary:hover {
                color: #fff;
                background-color: black               !important;
                border-color: black               !important;
            }

            .btn-outline-accent:hover {
                color: #fff;
                background-color: blue;
                border-color: blue;
            }

            .btn-outline-accent {
                color: blue;
                border-color: blue;
            }

            .text-accent {
                font-family: 'Roboto', sans-serif;
                font-weight: 700;
                font-size: 18px;
                color: blue;
            }

            a {
                color: blue;
                text-decoration: none;
                background-color: transparent
            }

            a:hover {
                color: black;
                text-decoration: none
            }

            .active-menu {
                color: black    !important;
            }

            .page-item.active > .page-link {
                box-shadow: 0 0.5rem 1.125rem -0.425remblue






        }

            .page-item.active .page-link {
                z-index: 3;
                color: #fff;
                background-color: blue;
                border-color: rgba(0, 0, 0, 0)
            }

            .btn-outline-accent:not(:disabled):not(.disabled):active, .btn-outline-accent:not(:disabled):not(.disabled).active, .show > .btn-outline-accent.dropdown-toggle {
                color: #fff;
                background-color: black;
                border-color: black;
            }

            .btn-outline-primary {
                color: blue;
                border-color: blue;
            }

            .btn-outline-primary:hover {
                color: #fff;
                background-color: black;
                border-color: black;
            }

            .btn-outline-primary:focus, .btn-outline-primary.focus {
                box-shadow: 0 0 0 0black;
            }

            .btn-outline-primary.disabled, .btn-outline-primary:disabled {
                color: #6f6f6f;
                background-color: transparent
            }

            .btn-outline-primary:not(:disabled):not(.disabled):active, .btn-outline-primary:not(:disabled):not(.disabled).active, .show > .btn-outline-primary.dropdown-toggle {
                color: #fff;
                background-color: blue;
                border-color: blue;
            }

            .btn-outline-primary:not(:disabled):not(.disabled):active:focus, .btn-outline-primary:not(:disabled):not(.disabled).active:focus, .show > .btn-outline-primary.dropdown-toggle:focus {
                box-shadow: 0 0 0 0blue;
            }

            .product-title > a {
                transition: color 0.25s ease-in-out;
                color: blue;
                text-decoration: none !important
            }

            .product-title > a:hover {
                color: black




        }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="code">
                @yield('code')
            </div>

            <div class="message" style="padding: 10px;">
                @yield('message')
            </div>
        </div>
    </body>
</html>
