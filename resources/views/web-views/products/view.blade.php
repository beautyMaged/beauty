@extends('layouts.front-end.app')

@section('title',\App\CPU\translate($data['data_from']).' '.\App\CPU\translate('products'))

@push('css_or_js')
    <meta property="og:image" content="{{asset('storage/company')}}/{{$web_config['web_logo']}}"/>
    <meta property="og:title" content="Products of {{$web_config['name']}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

    <meta property="twitter:card" content="{{asset('storage/company')}}/{{$web_config['web_logo']}}"/>
    <meta property="twitter:title" content="Products of {{$web_config['name']}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">

    <style>
        .product_imge {height: 100%}
        .product_imge a {height: 100%}
        .product_imge img {height: 100%}

        .offer-title h4 {
            margin-top: 52px !important;
        }

        .for-count-value {

        {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 0.6875 rem;;
        }

        .for-count-value {

        {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 0.6875 rem;
        }

        .for-brand-hover:hover {
            color: {{$web_config['primary_color']}};
        }

        .for-hover-lable:hover {
            color: {{$web_config['primary_color']}}                        !important;
        }

        .page-item.active .page-link {
            background-color: {{$web_config['primary_color']}}                       !important;
        }

        .for-shoting {
            padding- {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 9px;
        }

        .sidepanel {
        {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 0;
        }

        .sidepanel .closebtn {
        {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 25 px;
        }

        @media (max-width: 360px) {
            .for-shoting-mobile {
                margin- {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 0% !important;
            }

            .for-mobile {

                margin- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 10% !important;
            }

        }

        @media (max-width: 500px) {
            .for-mobile {

                margin- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 27%;
            }
        }

    </style>
    <link rel="stylesheet" href="{{asset('assets/front-end/css/pretty-checkbox.min.css')}}">
    <style>
        .pretty.p-svg .state .svg {
            top: calc((0% - (100% - 1.15em)) - 33%);
            left: auto;
            right: 15px
        }

        .pretty .state label:after, .pretty .state label:before {
            width: calc(1.1em + 0px);
            height: calc(1.2em + 0px);
            left: auto;
            right: 16px;
            font-size: 14px;
            top: calc((0% - (100% - 1.3em)) - 26%);
        }

        .pretty .state label {
            font-size: 14px;
            text-indent: 1.9em;
            font-weight: bold;
            top: calc((0% - (100% - 1.09em)) - 8%);
        }

        .pretty input:checked ~ .state.p-success label:after, .pretty.p-toggle .state.p-success label:after {
            background-color: #FF7C86 !important;
        }

        .custom_custom_check .pretty .state label {
            font-size: 15px !important;
        }

        /*.custom_custom_check .pretty.p-svg .state .svg {*/
        /*    top: calc((0% - (100% - 1.3em)) - -23%) !important;*/
        /*}*/

        .custom_custom_check .pretty .state label:after, .custom_custom_check .pretty .state label:before {
            top: calc((0% - (100% - 1.3em)) - 15%) !important;
        }
    </style>
@endpush

@section('content')
    @php
        if(isset($_GET['id']) && $_GET['data_from'] == 'brand') {$brand_name =\App\Model\Brand::find($_GET['id'])->name; $url = route('products',['id'=> $_GET['id'],'data_from'=>'brand','page'=>1]);}
        elseif (isset($_GET['id']) && $_GET['data_from'] != 'brand') {$brand_name =\App\Model\Category::find($_GET['id'])->name;}

        if(isset($_GET['id']) && $_GET['data_from'] == 'category') {$url = route('products',['id'=> $_GET['id'],'data_from'=>'category','page'=>1]);}
        if(isset($_GET['id']) && $_GET['data_from'] == 'brand') {$brand =\App\Model\Brand::find($_GET['id']);}
        elseif (isset($_GET['id']) && $_GET['data_from']  != 'brand') {$brand =\App\Model\Category::find($_GET['id']);}

        if(isset($_GET['id']) && $_GET['data_from']  != 'brand') {$category_banners =\App\Model\Category::find($_GET['id']);} else {$category_banners = null;}
        if(!isset($_GET['id']) && isset($_GET['data_from'])) {$url = route('products',['data_from'=>$_GET['data_from'] ,'page'=>1]);}
        if (!isset($_GET['id']) && $_GET['data_from'] == 'best-selling') {$brand_name = \App\CPU\translate('top_sell_pro');}
        if (!isset($_GET['id']) && $_GET['data_from'] == 'top-rated') {$brand_name = \App\CPU\translate('top_rate_pro');}
        if (!isset($_GET['id']) && $_GET['data_from'] == 'latest') {$brand_name = \App\CPU\translate('recent_pro');}
        if (!isset($_GET['id']) && $_GET['data_from'] == 'featured_deal') {$brand_name = \App\CPU\translate('special_offers');}

    @endphp

    @php($decimal_point_settings = \App\CPU\Helpers::get_business_settings('decimal_point_settings'))


    <!-- Page Title-->
    {{--    <div class="d-flex w-100 justify-content-center align-items-center mb-3 __min-h-70px __inline-35"--}}
    {{--         style="background:{{$web_config['primary_color']}}10;">--}}

    {{--        <div class="text-capitalize container text-center">--}}
    {{--            <span--}}
    {{--                class="__text-18px font-semibold">{{\App\CPU\translate(str_replace('_',' ',$data['data_from']))}} {{\App\CPU\translate('products')}} {{ isset($brand_name) ? '('.$brand_name.')' : ''}}</span>--}}
    {{--        </div>--}}

    {{--    </div>--}}
    <div class="row mb-3 __inline-35"
         style="background:{{$web_config['primary_color']}}10;">
        <div class="container" dir="{{session('direction')}}">
            <div class="row">

                <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-9 pl-3 py-3 serial_route">
                    <a href="{{route('home')}}" class="bold">{{\App\CPU\translate('Home')}}</a>
                    <div class="d-inline-block position-relative" style="width: 25px"><i
                            style="position: absolute;top: -15px;right: 3px;"
                            class="fa-solid fa-chevron-{{session('direction') == 'rtl' ? 'left' : 'right'}} mt-1  px-1 "></i>
                    </div>
                    <span class="bold">{{ isset($brand_name) ? $brand_name : ''}}</span>

                </div>

                <div
                    class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-3 py-3 text-{{session('direction') == 'rtl' ? 'left' : 'right'}} back">
                    <a href="{{ url()->previous() }}" class="bold">{{\App\CPU\translate('back')}}</a>
                    <div class="d-inline-block position-relative" style="width: 25px"><i
                            style="position: absolute;top: -15px;right: 3px;"
                            class="fa-solid fa-chevron-{{session('direction') == 'rtl' ? 'left' : 'right'}} mt-1  px-1 "></i>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Page Content-->
    <div class="container pb-5 mb-2 mb-md-4 rtl __inline-35"
         style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row" dir="{{session('direction')}}">
            <div class="col-2 mb-2 open_filter">
                <img src="{{asset('assets/front-end/img/filter.png')}}" alt="filter" width="30">
            </div>
            <!-- Sidebar-->
            <aside
                class="col-lg-3 hidden-xs col-md-3 col-sm-4 mt-5 pt-5 SearchParameters __search-sidebar {{Session::get('direction') === "rtl" ? 'pl-4' : 'pr-4'}}"
                id="SearchParameters" style="border-left: 1px solid #f8a2bf">

                <!--Price Sidebar-->
                <div class="cz-sidebar  __inline-35" id="shop-sidebar" style="background: inherit!important;">
                    <div class="cz-sidebar-header box-shadow-sm">
                        <button class="close close_btn {{Session::get('direction') === "rtl" ? 'mr-auto' : 'ml-auto'}}"
                                type="button" data-dismiss="sidebar" aria-label="Close"><span
                                class="d-inline-block font-size-xs font-weight-normal align-middle"><span
                                    class="d-inline-block align-middle close_btn_icon"
                                    aria-hidden="true">&times;</span>
                            </span>
                        </button>
                    </div>
                    <div class="pb-0">
                        <!-- Filter by price-->
                        <div class="{{session('direction') == 'rtl' ? 'text-right' : 'text-left'}}">
{{--                            <div class="row">--}}
{{--                                @if($url != 'null')--}}
{{--                                    <div class="col-xl-12 col-lg-12 col-md-12 col-12 text-left">--}}
{{--                                        <a class="btn btn-primary" href="{{$url}}">{{\App\CPU\translate('Reset Filter')}}</a>--}}
{{--                                    </div>--}}
{{--                                @endif--}}
{{--                            </div>--}}
                            <div class="__cate-side-title pr-4">
                                <span
                                    class="widget-title font-semibold s_27">{{isset($brand_name) ? $brand_name : ''}}</span>
                            </div>
                            <div class="__cate-side-title  pr-4 border-bottom"
                                 style="border-bottom: 1px solid #f8a2bf!important;">
                                <span class="widget-title s_18 font-semibold">{{\App\CPU\translate('Filter')}}</span>
                            </div>


                            <div class="__cate-side-sub-title  pr-4 border-bottom"
                                 style="">
                                <span
                                    class="widget-title s_18 font-semibold">{{\App\CPU\translate('Filter Type')}}</span>
                            </div>

                            @if(isset($_GET['id']))
                                <div class=" custom_custom_check"
                                     style="  width: 100%">
                                    <div class="pretty p-svg p-curve mt-3 w-100">
                                        <input type="checkbox" name="filter_type"
                                               value="{{route('products',['id'=> $_GET['id'],'data_from'=>'latest','page'=>1])}}"
                                               id="latest" {{isset($data['data_from'])!=null?$data['data_from']=='latest'?'checked':'':''}}/>
                                        <div class="state p-success" style="padding-right: 17px">
                                            <!-- svg path -->
                                            <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                <path
                                                    d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"
                                                    style="stroke: white;fill:white;"></path>
                                            </svg>
                                            <label class="bold"
                                                   for="latest">{{\App\CPU\translate('recent_pro')}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class=" custom_custom_check"
                                     style="  width: 100%">

                                    <div class="pretty p-svg p-curve mt-3 w-100">
                                        <input type="checkbox" name="filter_type"
                                               id="best_sell"
                                               value="{{route('products',['id'=>  $_GET['id'],'data_from'=>'best-selling','page'=>1])}}" {{isset($data['data_from'])!=null?$data['data_from']=='best-selling'?'checked':'':''}}/>
                                        <div class="state p-success" style="padding-right: 17px">
                                            <!-- svg path -->
                                            <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                <path
                                                    d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"
                                                    style="stroke: white;fill:white;"></path>
                                            </svg>
                                            <label class="bold"
                                                   for="best_sell">{{\App\CPU\translate('top_sell_pro')}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class=" custom_custom_check"
                                     style="  width: 100%">

                                    <div class="pretty p-svg p-curve mt-3 w-100">
                                        <input type="checkbox" name="filter_type"
                                               value="{{route('products',['id'=>  $_GET['id'],'data_from'=>'top-rated','page'=>1])}}"
                                               id="top_rate" {{isset($data['data_from'])!=null?$data['data_from']=='top-rated'?'checked':'':''}} />
                                        <div class="state p-success" style="padding-right: 17px">
                                            <!-- svg path -->
                                            <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                <path
                                                    d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"
                                                    style="stroke: white;fill:white;"></path>
                                            </svg>
                                            <label class="bold"
                                                   for="top_rate">{{\App\CPU\translate('top_rate_pro')}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class=" custom_custom_check"
                                     style="  width: 100%">

                                    <div class="pretty p-svg p-curve mt-3 w-100">
                                        <input type="checkbox" name="filter_type"
                                               value="{{route('products',['id' =>  $_GET['id'], 'data_from'=>'featured_deal','page'=>1])}}"
                                               id="featured_deal" {{isset($data['data_from'])!=null?$data['data_from']=='featured_deal'?'checked':'':''}} />
                                        <div class="state p-success" style="padding-right: 17px">
                                            <!-- svg path -->
                                            <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                <path
                                                    d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"
                                                    style="stroke: white;fill:white;"></path>
                                            </svg>
                                            <label class="bold"
                                                   for="featured_deal">{{\App\CPU\translate('special_offers')}}</label>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class=" custom_custom_check"
                                     style="  width: 100%">
                                    <div class="pretty p-svg p-curve mt-3 w-100">
                                        <input type="checkbox" name="filter_type"
                                               value="{{route('products',['data_from'=>'latest','page'=>1])}}"
                                               id="latest" {{isset($data['data_from'])!=null?$data['data_from']=='latest'?'checked':'':''}}/>
                                        <div class="state p-success" style="padding-right: 17px">
                                            <!-- svg path -->
                                            <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                <path
                                                    d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"
                                                    style="stroke: white;fill:white;"></path>
                                            </svg>
                                            <label class="bold"
                                                   for="latest">{{\App\CPU\translate('recent_pro')}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class=" custom_custom_check"
                                     style="  width: 100%">

                                    <div class="pretty p-svg p-curve mt-3 w-100">
                                        <input type="checkbox" name="filter_type"
                                               id="best_sell"
                                               value="{{route('products',['data_from'=>'best-selling','page'=>1])}}" {{isset($data['data_from'])!=null?$data['data_from']=='best-selling'?'checked':'':''}}/>
                                        <div class="state p-success" style="padding-right: 17px">
                                            <!-- svg path -->
                                            <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                <path
                                                    d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"
                                                    style="stroke: white;fill:white;"></path>
                                            </svg>
                                            <label class="bold"
                                                   for="best_sell">{{\App\CPU\translate('top_sell_pro')}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class=" custom_custom_check"
                                     style="  width: 100%">

                                    <div class="pretty p-svg p-curve mt-3 w-100">
                                        <input type="checkbox" name="filter_type"
                                               value="{{route('products',['data_from'=>'top-rated','page'=>1])}}"
                                               id="top_rate" {{isset($data['data_from'])!=null?$data['data_from']=='top-rated'?'checked':'':''}} />
                                        <div class="state p-success" style="padding-right: 17px">
                                            <!-- svg path -->
                                            <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                <path
                                                    d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"
                                                    style="stroke: white;fill:white;"></path>
                                            </svg>
                                            <label class="bold"
                                                   for="top_rate">{{\App\CPU\translate('top_rate_pro')}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class=" custom_custom_check"
                                     style="  width: 100%">

                                    <div class="pretty p-svg p-curve mt-3 w-100">
                                        <input type="checkbox" name="filter_type"
                                               value="{{route('products',['data_from'=>'featured_deal','page'=>1])}}"
                                               id="featured_deal" {{isset($data['data_from'])!=null?$data['data_from']=='featured_deal'?'checked':'':''}} />
                                        <div class="state p-success" style="padding-right: 17px">
                                            <!-- svg path -->
                                            <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                <path
                                                    d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"
                                                    style="stroke: white;fill:white;"></path>
                                            </svg>
                                            <label class="bold"
                                                   for="featured_deal">{{\App\CPU\translate('special_offers')}}</label>
                                        </div>
                                    </div>
                                </div>
                            @endif




                            {{-- Start Price Range --}}
                            <div class="__cate-side-sub-title  pr-4 border-bottom"
                                 style="">
                                <span class="widget-title s_18 font-semibold">{{\App\CPU\translate('Price')}}</span>
                            </div>

                            <div class="mt-3">
                                <!-- Filter by price-->
                                <div class="text-center">


                                    <div class="d-flex justify-content-between align-items-center __cate-side-price">
                                        <div class="__w-35p">
                                            <input
                                                class="bg-white cz-filter-search form-control form-control-sm appended-form-control"
                                                type="number" value="0" min="0" max="1000000" id="min_price"
                                                placeholder="Min">

                                        </div>
                                        <div class="__w-10p">
                                            <p class="m-0">{{\App\CPU\translate('To')}}</p>
                                        </div>
                                        <div class="__w-35p">
                                            <input value="100" min="100" max="1000000"
                                                   class="bg-white cz-filter-search form-control form-control-sm appended-form-control"
                                                   type="number" id="max_price" placeholder="Max">

                                        </div>

                                        <div
                                            class="d-flex justify-content-center align-items-center __number-filter-btn">

                                            <a class=""
                                               onclick="searchByPrice()">
                                                <i class="__inline-37 czi-arrow-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}"></i>
                                            </a>

                                        </div>
                                    </div>

                                </div>
                            </div>

                            {{-- End Price Range --}}
                            @php($categories=\App\CPU\CategoryManager::parents())




                            @if(isset($_GET['id']) && $_GET['id'] != null && $_GET['data_from'] != 'brand')
                                @php($category_ = \App\Model\Category::find($_GET['id']))
                                @if($category_->position == 0 && $category_->childes->count() > 0)
                                    <div class="__cate-side-sub-title  pr-4 border-bottom"
                                         style="border-bottom: 1px solid #f8a2bf!important;">
                                        <span
                                            class="widget-title s_18 font-semibold">{{\App\CPU\translate('Category')}}</span>
                                    </div>
                                    <div
                                        class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xm-12 col-12 text-center"
                                        style="">
                                        <div class="row">
                                            <div
                                                class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xm-12 col-12 text-right pr-2 position-relative">
                                                <div class="sub_menu mt-3">
                                                    <ul id="list" class="row"
                                                        style="padding-left: 0!important;list-style: none; padding-right: 15px;">
                                                        <li class="locations col-xxl-12 col-xl-12 col-md-12 col-sm-12 col-12  mx-0 px-2 position-relative">
                                                            {{--                                                            Hereeeeeeee--}}
                                                            <div>

                                                                <div
                                                                    class="card-body p-1 {{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}}"
                                                                    id="collapse-{{$category_['id']}}"
                                                                    style="">
                                                                    @foreach($category_->childes as $child)
                                                                        <div
                                                                            class=" for-hover-lable card-header p-1 flex-between bold">
                                                                            <div>
                                                                                <label class="cursor-pointer"
                                                                                       onclick="location.href='{{route('products',['id'=> $child['id'],'data_from'=>'category','page'=>1])}}'">
                                                                                    {{$child['name']}}
                                                                                </label>
                                                                            </div>
                                                                            <div class="px-2 cursor-pointer"
                                                                                 onclick="$('#collapse-{{$child['id']}}').slideToggle(300); if($(this).find('.pull-right.sub_sub_{{$child['id']}}').hasClass('active')){
                                                                                     $(this).find('.pull-right.sub_sub_{{$child['id']}}').removeClass('active');
                                                                                     $(this).find('.pull-right.sub_sub_{{$child['id']}}').find('.opened').addClass('d-none');
                                                                                     $(this).find('.pull-right.sub_sub_{{$child['id']}}').find('.closed').removeClass('d-none');
                                                                                     }else {$(this).find('.pull-right.sub_sub_{{$child['id']}}').addClass('active');
                                                                                     $(this).find('.pull-right.sub_sub_{{$child['id']}}').find('.opened').removeClass('d-none');
                                                                                     $(this).find('.pull-right.sub_sub_{{$child['id']}}').find('.closed').addClass('d-none');
                                                                                     }">
                                                                                <strong
                                                                                    class="pull-right sub_sub_{{$child['id']}}">
                                                                                    {!! $child->childes->count()>0?'<i class="fa fa-chevron-left closed"></i>':''!!}
                                                                                    <i class="fa fa-chevron-down opened d-none"></i>
                                                                                </strong>
                                                                            </div>
                                                                        </div>
                                                                        <div
                                                                            class="card-body p-0 {{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}}"
                                                                            id="collapse-{{$child['id']}}"
                                                                            style="display: none">
                                                                            @foreach($child->childes as $ch)
                                                                                <div class="card-header p-1">
                                                                                    <label
                                                                                        class="for-hover-lable d-block cursor-pointer text-right"
                                                                                        onclick="location.href='{{route('products',['id'=> $ch['id'],'data_from'=>'category','page'=>1])}}'">{{$ch['name']}}</label>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($category_->position == 1)
                                    <div class="__cate-side-sub-title  pr-4 border-bottom"
                                         style="border-bottom: 1px solid #f8a2bf!important;">
                                        <span
                                            class="widget-title s_18 font-semibold">{{\App\CPU\translate('Category')}}</span>
                                    </div>
                                    <div
                                        class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xm-12 col-12 text-center"
                                        style="">
                                        <div class="row">
                                            <div
                                                class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xm-12 col-12 text-right pr-2 position-relative">
                                                <div class="sub_menu mt-3">
                                                    <ul id="list" class="row"
                                                        style="padding-left: 0!important;list-style: none; padding-right: 15px;">
                                                        <li class="locations col-xxl-12 col-xl-12 col-md-12 col-sm-12 col-12  mx-0 px-2 position-relative">
                                                            {{--                                                            Hereeeeeeee--}}
                                                            <div>

                                                                <div
                                                                    class="card-body p-1 {{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}}"
                                                                    id="collapse-{{$category_['id']}}"
                                                                    style="">
                                                                    @foreach($category_->childes as $child)
                                                                        <div
                                                                            class=" for-hover-lable card-header p-1 flex-between bold">
                                                                            <div>
                                                                                <label class="cursor-pointer"
                                                                                       onclick="location.href='{{route('products',['id'=> $child['id'],'data_from'=>'category','page'=>1])}}'">
                                                                                    {{$child['name']}}
                                                                                </label>
                                                                            </div>
                                                                            <div class="px-2 cursor-pointer"
                                                                                 onclick="$('#collapse-{{$child['id']}}').slideToggle(300); if($(this).find('.pull-right.sub_sub_{{$child['id']}}').hasClass('active')){
                                                                                     $(this).find('.pull-right.sub_sub_{{$child['id']}}').removeClass('active');
                                                                                     $(this).find('.pull-right.sub_sub_{{$child['id']}}').find('.opened').addClass('d-none');
                                                                                     $(this).find('.pull-right.sub_sub_{{$child['id']}}').find('.closed').removeClass('d-none');
                                                                                     }else {$(this).find('.pull-right.sub_sub_{{$child['id']}}').addClass('active');
                                                                                     $(this).find('.pull-right.sub_sub_{{$child['id']}}').find('.opened').removeClass('d-none');
                                                                                     $(this).find('.pull-right.sub_sub_{{$child['id']}}').find('.closed').addClass('d-none');
                                                                                     }">
                                                                                <strong
                                                                                    class="pull-right sub_sub_{{$child['id']}}">
                                                                                    {!! $child->childes->count()>0?'<i class="fa fa-chevron-left closed"></i>':''!!}
                                                                                    <i class="fa fa-chevron-down opened d-none"></i>
                                                                                </strong>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else

                                @endif
                            @else
                                <div class="__cate-side-sub-title  pr-4 border-bottom"
                                     style="border-bottom: 1px solid #f8a2bf!important;">
                                    <span
                                        class="widget-title s_18 font-semibold">{{\App\CPU\translate('Category')}}</span>
                                </div>
                                <div
                                    class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xm-12 col-12 text-center"
                                    style="">
                                    <div class="row">
                                        <div
                                            class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xm-12 col-12 text-right pr-2 position-relative">
                                            <div class="sub_menu mt-3">
                                                <ul id="list" class="row"
                                                    style="padding-left: 0!important;list-style: none; padding-right: 15px;">
                                                    @foreach($categories as $category)

                                                        <li class="locations col-xxl-12 col-xl-12 col-md-12 col-sm-12 col-12  mx-0 px-2 position-relative">

                                                            <div>
                                                                <div class="card-header p-1 flex-between">
                                                                    <div>
                                                                        <label
                                                                            class="for-hover-lable cursor-pointer bold"
                                                                            onclick="location.href='{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}'">
                                                                            {{$category['name']}}
                                                                        </label>
                                                                    </div>
                                                                    <div class="px-2 cursor-pointer"
                                                                         onclick="$('#collapse-{{$category['id']}}').slideToggle(300); if($(this).find('.pull-right.subs_{{$category['id']}}').hasClass('active')){
                                                                             $(this).find('.pull-right.subs_{{$category['id']}}').removeClass('active');
                                                                             $(this).find('.pull-right.subs_{{$category['id']}}').find('.opened').addClass('d-none');
                                                                             $(this).find('.pull-right.subs_{{$category['id']}}').find('.closed').removeClass('d-none');
                                                                             }else {$(this).find('.pull-right.subs_{{$category['id']}}').addClass('active');
                                                                             $(this).find('.pull-right.subs_{{$category['id']}}').find('.opened').removeClass('d-none');
                                                                             $(this).find('.pull-right.subs_{{$category['id']}}').find('.closed').addClass('d-none');
                                                                             }

                                                                             ">
                                                                        <strong
                                                                            class="pull-right subs_{{$category['id']}}  for-brand-hover">
                                                                            {!! $category->childes->count()>0?'<i class="fa fa-chevron-left closed"></i>':''!!}
                                                                            <i class="fa fa-chevron-down opened d-none"></i>
                                                                        </strong>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="card-body p-0 {{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}}"
                                                                    id="collapse-{{$category['id']}}"
                                                                    style="display: none">
                                                                    @foreach($category->childes as $child)
                                                                        <div
                                                                            class=" for-hover-lable card-header p-1 flex-between">
                                                                            <div>
                                                                                <label class="cursor-pointer"
                                                                                       onclick="location.href='{{route('products',['id'=> $child['id'],'data_from'=>'category','page'=>1])}}'">
                                                                                    {{$child['name']}}
                                                                                </label>
                                                                            </div>
                                                                            <div class="px-2 cursor-pointer"
                                                                                 onclick="$('#collapse-{{$child['id']}}').slideToggle(300); if($(this).find('.pull-right.sub_sub_{{$child['id']}}').hasClass('active')){
                                                                                     $(this).find('.pull-right.sub_sub_{{$child['id']}}').removeClass('active');
                                                                                     $(this).find('.pull-right.sub_sub_{{$child['id']}}').find('.opened').addClass('d-none');
                                                                                     $(this).find('.pull-right.sub_sub_{{$child['id']}}').find('.closed').removeClass('d-none');
                                                                                     }else {$(this).find('.pull-right.sub_sub_{{$child['id']}}').addClass('active');
                                                                                     $(this).find('.pull-right.sub_sub_{{$child['id']}}').find('.opened').removeClass('d-none');
                                                                                     $(this).find('.pull-right.sub_sub_{{$child['id']}}').find('.closed').addClass('d-none');
                                                                                     }">
                                                                                <strong
                                                                                    class="pull-right sub_sub_{{$child['id']}}">
                                                                                    {!! $child->childes->count()>0?'<i class="fa fa-chevron-left closed"></i>':''!!}
                                                                                    <i class="fa fa-chevron-down opened d-none"></i>
                                                                                </strong>
                                                                            </div>
                                                                        </div>
                                                                        <div
                                                                            class="card-body p-0 {{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}}"
                                                                            id="collapse-{{$child['id']}}"
                                                                            style="display: none">
                                                                            @foreach($child->childes as $ch)
                                                                                <div class="card-header p-1">
                                                                                    <label
                                                                                        class="for-hover-lable d-block cursor-pointer text-right"
                                                                                        onclick="location.href='{{route('products',['id'=> $ch['id'],'data_from'=>'category','page'=>1])}}'">{{$ch['name']}}</label>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </li>

                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            {{--                                                        <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xm-12 col-12 text-center"--}}
                            {{--                                                             style="">--}}
                            {{--                                                            <div class="row">--}}
                            {{--                                                                <div--}}
                            {{--                                                                    class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xm-12 col-12 text-right pr-2 position-relative">--}}
                            {{--                                                                    <div class="sub_menu mt-3">--}}
                            {{--                                                                        <ul id="list" class="row"--}}
                            {{--                                                                            style="padding-left: 0!important;list-style: none; padding-right: 15px;">--}}

                            {{--                                                @foreach($categories as $category)--}}
                            {{--                                                    <li class="locations col-xxl-12 col-xl-12 col-md-12 col-sm-12 col-12 custom_check_filter mx-0 px-2 position-relative">--}}
                            {{--                                                        {{dd($children_attributes)}}--}}

                            {{--                                                        <a href="{{route('home')}}/products?id={{$category->id}}&data_from=category&page=1"--}}
                            {{--                                                           class="bold main_toggle_item">--}}
                            {{--                                                            {{$category->name}}--}}
                            {{--                                                        </a>--}}
                            {{--                                                        <span class="toggle_icon">--}}
                            {{--                                                                                                                <i class="fa fa-chevron-left"></i>--}}
                            {{--                                                                                                                <i class="fa fa-chevron-down d-none"></i>--}}
                            {{--                                                                                                            </span>--}}
                            {{--                                                        <ul class="cities sub_from_main"--}}
                            {{--                                                            style="padding-left: 0!important;padding-right: 20px;margin-top: 2px;list-style:none;display: none">--}}
                            {{--                                                            @php($subs=\App\CPU\CategoryManager::child($category->id))--}}
                            {{--                                                            @foreach($subs as $sub)--}}


                            {{--                                                                <li class="locations col-xxl-12 col-xl-12 col-md-12 col-sm-12 col-12 custom_sub_check_filter mx-0">--}}

                            {{--                                                                    <a class="bold"--}}
                            {{--                                                                       href="{{route('home')}}/products?id={{$sub->id}}&data_from=category&page=1">--}}
                            {{--                                                                        {{$sub->name}}--}}
                            {{--                                                                    </a>--}}
                            {{--                                                                </li>--}}
                            {{--                                                                <span class="toggle_icon">--}}
                            {{--                                                                                                                <i class="fa fa-chevron-left"></i>--}}
                            {{--                                                                                                                <i class="fa fa-chevron-down d-none"></i>--}}
                            {{--                                                                                                            </span>--}}
                            {{--                                                                <ul class="cities sub_from_main"--}}
                            {{--                                                                    style="padding-left: 0!important;padding-right: 20px;margin-top: 2px;list-style:none;display: none">--}}
                            {{--                                                                    @php($sub_subs=\App\CPU\CategoryManager::child($sub->id))--}}

                            {{--                                                                    @foreach($sub_subs as $sub_sub)--}}
                            {{--                                                                        <li class="locations col-xxl-12 col-xl-12 col-md-12 col-sm-12 col-12 custom_sub_check_filter mx-0">--}}

                            {{--                                                                            <a class="bold"--}}
                            {{--                                                                               href="{{route('home')}}/products?id=1&data_from=category&page=1">--}}
                            {{--                                                                                {{$sub_sub->name}}--}}
                            {{--                                                                            </a>--}}
                            {{--                                                                        </li>--}}

                            {{--                                                                    @endforeach--}}
                            {{--                                                                </ul>--}}
                            {{--                                                            @endforeach--}}
                            {{--                                                        </ul>--}}

                            {{--                                                    </li>--}}
                            {{--                                                @endforeach--}}

                            {{--                                            </ul>--}}
                            {{--                                        </div>--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}


                            <div class="__cate-side-sub-title  pr-4 border-bottom"
                                 style="border-bottom: 1px solid #f8a2bf!important;">
                                <span class="widget-title s_18 font-semibold">{{\App\CPU\translate('Brand')}}</span>
                            </div>
                            <ul id="lista1" class="__brands-cate-wrap"
                                data-simplebar data-simplebar-auto-hide="false">
                                @foreach(\App\CPU\BrandManager::get_active_brands() as $brand)
                                    <div
                                        class="brand mt-2 for-brand-hover {{Session::get('direction') === "rtl" ? 'mr-2' : ''}}"
                                        id="brand">
                                        <li class="flex-between __inline-39"
                                            onclick="location.href='{{route('products',['id'=> $brand['id'],'data_from'=>'brand','page'=>1])}}'">
                                            <div>
                                                {{ $brand['name'] }}
                                            </div>
                                            @if($brand['brand_products_count'] > 0 )
                                                <div class="__brands-cate-badge">
                                                    <span class="">
                                                    {{ $brand['brand_products_count'] }}
                                                    </span>
                                                </div>
                                            @endif
                                        </li>
                                    </div>
                                @endforeach
                            </ul>
                            {{--                            --}}
                            {{--                            @php($brands=\App\CPU\BrandManager::get_active_brands())--}}
                            {{--                            @if(isset($brands) && $brands->count() > 0)--}}
                            {{--                                @foreach($brands as $brand)--}}


                            {{--                                    <div class="pretty p-svg p-curve mt-3 w-100">--}}
                            {{--                                        <input type="checkbox" name="brands[]" value="{{$brand->id}}"--}}
                            {{--                                               id="check_{{$brand->id}}"/>--}}
                            {{--                                        <div class="state p-success" style="padding-right: 17px">--}}
                            {{--                                            <!-- svg path -->--}}
                            {{--                                            <svg class="svg svg-icon" viewBox="0 0 20 20">--}}
                            {{--                                                <path--}}
                            {{--                                                    d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"--}}
                            {{--                                                    style="stroke: white;fill:white;"></path>--}}
                            {{--                                            </svg>--}}
                            {{--                                            <label class="bold">{{$brand->name}}</label>--}}
                            {{--                                        </div>--}}
                            {{--                                    </div>--}}

                            {{--                                @endforeach--}}
                            {{--                            @endif--}}


                            {{--                            <div class="__cate-side-sub-title  pr-4 border-bottom"--}}
                            {{--                                 style="border-bottom: 1px solid #f8a2bf!important;">--}}
                            {{--                                <span class="widget-title s_18 font-semibold">المقاس</span>--}}
                            {{--                            </div>--}}

                            {{--                            --}}{{-- Start Sizes--}}
                            {{--                            <div class="pretty p-svg p-curve mt-3 w-100">--}}
                            {{--                                <input type="checkbox" name="sizes[xxxl]" value=""--}}
                            {{--                                       id="check_xxxl"/>--}}
                            {{--                                <div class="state p-success" style="padding-right: 17px">--}}
                            {{--                                    <!-- svg path -->--}}
                            {{--                                    <svg class="svg svg-icon" viewBox="0 0 20 20">--}}
                            {{--                                        <path--}}
                            {{--                                            d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"--}}
                            {{--                                            style="stroke: white;fill:white;"></path>--}}
                            {{--                                    </svg>--}}
                            {{--                                    <label class="bold">XXXL</label>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            {{--                            <div class="pretty p-svg p-curve mt-3 w-100">--}}
                            {{--                                <input type="checkbox" name="sizes[xxl]" value=""--}}
                            {{--                                       id="check_xxl"/>--}}
                            {{--                                <div class="state p-success" style="padding-right: 17px">--}}
                            {{--                                    <!-- svg path -->--}}
                            {{--                                    <svg class="svg svg-icon" viewBox="0 0 20 20">--}}
                            {{--                                        <path--}}
                            {{--                                            d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"--}}
                            {{--                                            style="stroke: white;fill:white;"></path>--}}
                            {{--                                    </svg>--}}
                            {{--                                    <label class="bold">XXL</label>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            {{--                            <div class="pretty p-svg p-curve mt-3 w-100">--}}
                            {{--                                <input type="checkbox" name="sizes[xl]" value=""--}}
                            {{--                                       id="check_xl"/>--}}
                            {{--                                <div class="state p-success" style="padding-right: 17px">--}}
                            {{--                                    <!-- svg path -->--}}
                            {{--                                    <svg class="svg svg-icon" viewBox="0 0 20 20">--}}
                            {{--                                        <path--}}
                            {{--                                            d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"--}}
                            {{--                                            style="stroke: white;fill:white;"></path>--}}
                            {{--                                    </svg>--}}
                            {{--                                    <label class="bold">XL</label>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            {{--                            <div class="pretty p-svg p-curve mt-3 w-100">--}}
                            {{--                                <input type="checkbox" name="sizes[l]" value=""--}}
                            {{--                                       id="check_l"/>--}}
                            {{--                                <div class="state p-success" style="padding-right: 17px">--}}
                            {{--                                    <!-- svg path -->--}}
                            {{--                                    <svg class="svg svg-icon" viewBox="0 0 20 20">--}}
                            {{--                                        <path--}}
                            {{--                                            d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"--}}
                            {{--                                            style="stroke: white;fill:white;"></path>--}}
                            {{--                                    </svg>--}}
                            {{--                                    <label class="bold">L</label>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            {{--                            <div class="pretty p-svg p-curve mt-3 w-100">--}}
                            {{--                                <input type="checkbox" name="sizes[m]" value=""--}}
                            {{--                                       id="check_m"/>--}}
                            {{--                                <div class="state p-success" style="padding-right: 17px">--}}
                            {{--                                    <!-- svg path -->--}}
                            {{--                                    <svg class="svg svg-icon" viewBox="0 0 20 20">--}}
                            {{--                                        <path--}}
                            {{--                                            d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"--}}
                            {{--                                            style="stroke: white;fill:white;"></path>--}}
                            {{--                                    </svg>--}}
                            {{--                                    <label class="bold">M</label>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            {{--                            <div class="pretty p-svg p-curve mt-3 w-100">--}}
                            {{--                                <input type="checkbox" name="sizes[s]" value=""--}}
                            {{--                                       id="check_s"/>--}}
                            {{--                                <div class="state p-success" style="padding-right: 17px">--}}
                            {{--                                    <!-- svg path -->--}}
                            {{--                                    <svg class="svg svg-icon" viewBox="0 0 20 20">--}}
                            {{--                                        <path--}}
                            {{--                                            d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"--}}
                            {{--                                            style="stroke: white;fill:white;"></path>--}}
                            {{--                                    </svg>--}}
                            {{--                                    <label class="bold">S</label>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            {{--                            <div class="pretty p-svg p-curve mt-3 w-100">--}}
                            {{--                                <input type="checkbox" name="sizes[xs]" value=""--}}
                            {{--                                       id="check_xs"/>--}}
                            {{--                                <div class="state p-success" style="padding-right: 17px">--}}
                            {{--                                    <!-- svg path -->--}}
                            {{--                                    <svg class="svg svg-icon" viewBox="0 0 20 20">--}}
                            {{--                                        <path--}}
                            {{--                                            d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"--}}
                            {{--                                            style="stroke: white;fill:white;"></path>--}}
                            {{--                                    </svg>--}}
                            {{--                                    <label class="bold">XS</label>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            {{-- End Sizes--}}



                            {{-- Start Colors--}}

                            <div class="__cate-side-sub-title  pr-4 border-bottom"
                                 style="border-bottom: 1px solid #f8a2bf!important;">
                                <span class="widget-title s_18 font-semibold">{{\App\CPU\translate('Color')}}</span>
                            </div>

                            @if(isset($_GET['id']))
                                <div class=" colors_container pt-3" style="width: 80%; margin: auto">
                                    @foreach($colors as $color)
                                        <div class="d-inline-block color-item " style="background: {{$color->code}}"
                                             data-value="{{route('products',['id' => $_GET['id'],'data_from'=> $_GET['data_from'], 'color' => str_replace('#', '', $color->code), 'page'=>1])}}">
                                            <i class="fa fa-check"
                                               style="{{isset($_GET['color']) && str_replace('#', '', $color->code) == $_GET['color'] ? '' : 'display:none;'}}"></i>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class=" colors_container pt-3" style="width: 80%; margin: auto">
                                    @foreach($colors as $color)

                                        <div class="d-inline-block color-item " style="background: {{$color->code}}"
                                             data-value="{{route('products',['data_from'=> $_GET['data_from'], 'color' => str_replace('#', '', $color->code), 'page'=>1])}}">
                                            <i class="fa fa-check  "
                                               style="{{isset($_GET['color']) && str_replace('#', '', $color->code) == $_GET['color'] ? '' : 'display:none;'}}"></i>
                                        </div>
                                    @endforeach


                                </div>
                            @endif
                            {{-- End Colors--}}

                            {{-- Start Custom Custom Checkbox --}}

                            {{-- End Custom Custom Checkbox --}}




                            {{--                                                        <div class="__p-25-10 w-100 pt-4">--}}
                            {{--                                                            <label class="w-100 opacity-75 text-nowrap for-shoting d-block mb-0" for="sorting"--}}
                            {{--                                                                   style="padding-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 0">--}}
                            {{--                                                                <select class="form-control custom-select" id="searchByFilterValue">--}}
                            {{--                                                                    <option selected disabled>{{\App\CPU\translate('Choose')}}</option>--}}
                            {{--                                                                    <option--}}
                            {{--                                                                        value="{{route('products',['id'=> $data['id'],'data_from'=>'best-selling','page'=>1])}}" {{isset($data['data_from'])!=null?$data['data_from']=='best-selling'?'selected':'':''}}>{{\App\CPU\translate('best_selling_product')}}</option>--}}
                            {{--                                                                    <option--}}
                            {{--                                                                        value="{{route('products',['id'=> $data['id'],'data_from'=>'top-rated','page'=>1])}}" {{isset($data['data_from'])!=null?$data['data_from']=='top-rated'?'selected':'':''}}>{{\App\CPU\translate('top_rated')}}</option>--}}
                            {{--                                                                    <option--}}
                            {{--                                                                        value="{{route('products',['id'=> $data['id'],'data_from'=>'most-favorite','page'=>1])}}" {{isset($data['data_from'])!=null?$data['data_from']=='most-favorite'?'selected':'':''}}>{{\App\CPU\translate('most_favorite')}}</option>--}}
                            {{--                                                                    <option--}}
                            {{--                                                                        value="{{route('products',['id'=> $data['id'],'data_from'=>'featured_deal','page'=>1])}}" {{isset($data['data_from'])!=null?$data['data_from']=='featured_deal'?'selected':'':''}}>{{\App\CPU\translate('featured_deal')}}</option>--}}
                            {{--                                                                </select>--}}
                            {{--                                                            </label>--}}
                            {{--                                                        </div>--}}

                            {{--                            <div class="__p-25-10 w-100 pt-4">--}}
                            {{--                                <label class="w-100 opacity-75 text-nowrap for-shoting d-block mb-0" for="sorting"--}}
                            {{--                                       style="padding-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 0">--}}
                            {{--                                    <select class="form-control custom-select" id="searchByFilterValue">--}}
                            {{--                                        <option selected disabled>{{\App\CPU\translate('Choose')}}</option>--}}
                            {{--                                        <option--}}
                            {{--                                            value="{{route('products',['id'=> $data['id'],'data_from'=>'best-selling','page'=>1])}}" {{isset($data['data_from'])!=null?$data['data_from']=='best-selling'?'selected':'':''}}>{{\App\CPU\translate('best_selling_product')}}</option>--}}
                            {{--                                        <option--}}
                            {{--                                            value="{{route('products',['id'=> $data['id'],'data_from'=>'top-rated','page'=>1])}}" {{isset($data['data_from'])!=null?$data['data_from']=='top-rated'?'selected':'':''}}>{{\App\CPU\translate('top_rated')}}</option>--}}
                            {{--                                        <option--}}
                            {{--                                            value="{{route('products',['id'=> $data['id'],'data_from'=>'most-favorite','page'=>1])}}" {{isset($data['data_from'])!=null?$data['data_from']=='most-favorite'?'selected':'':''}}>{{\App\CPU\translate('most_favorite')}}</option>--}}
                            {{--                                        <option--}}
                            {{--                                            value="{{route('products',['id'=> $data['id'],'data_from'=>'featured_deal','page'=>1])}}" {{isset($data['data_from'])!=null?$data['data_from']=='featured_deal'?'selected':'':''}}>{{\App\CPU\translate('featured_deal')}}</option>--}}
                            {{--                                    </select>--}}
                            {{--                                </label>--}}
                            {{--                            </div>--}}
                        </div>
                    </div>

                    {{--                                        <div>--}}
                    {{--                                            <div class="text-center">--}}
                    {{--                                                <div class="__cate-side-title border-top border-bottom">--}}
                    {{--                                                    <span class="widget-title font-semibold">{{\App\CPU\translate('brands')}}</span>--}}
                    {{--                                                </div>--}}
                    {{--                                                <div class="__cate-side-price pb-0">--}}
                    {{--                                                    <div class="input-group-overlay input-group-sm">--}}
                    {{--                                                        <input style="{{Session::get('direction') === "rtl" ? 'padding-right: 32px;' : ''}}"--}}
                    {{--                                                               placeholder="{{__('Search by brands')}}"--}}
                    {{--                                                               class="__inline-38 cz-filter-search form-control form-control-sm appended-form-control"--}}
                    {{--                                                               type="text" id="search-brand">--}}
                    {{--                                                        <div class="input-group-append-overlay">--}}
                    {{--                                                            <span class="input-group-text">--}}
                    {{--                                                                <i class="czi-search"></i>--}}
                    {{--                                                            </span>--}}
                    {{--                                                        </div>--}}
                    {{--                                                    </div>--}}
                    {{--                                                </div>--}}
                    {{--                                                <ul id="lista1" class="__brands-cate-wrap"--}}
                    {{--                                                    data-simplebar data-simplebar-auto-hide="false">--}}

                    {{--                                                </ul>--}}
                    {{--                                            </div>--}}
                    {{--                                        </div>--}}

                </div>

            </aside>

            <!-- Content  -->
            <section class="col-lg-9 products_container pr-4">
                {{--                <div class="d-flex flex-wrap align-items-center justify-content-between __inline-43 __gap-6px p-2">--}}
                {{--                    <div class="filter-show-btn btn btn--primary py-1 px-2">--}}
                {{--                        <i class="tio-filter"></i>--}}
                {{--                    </div>--}}
                {{--                    <h1 class="max-sm-order-1">--}}
                {{--                        <label--}}
                {{--                            id="price-filter-count"> {{$products->total()}} {{\App\CPU\translate('items found')}} </label>--}}
                {{--                    </h1>--}}
                {{--                    <div class="d-flex align-items-center ml-auto">--}}

                {{--                        <div class="w-100">--}}
                {{--                            <form id="search-form" action="{{ route('products') }}" method="GET">--}}
                {{--                                <input hidden name="data_from" value="{{$data['data_from']}}">--}}
                {{--                                <div class=" {{Session::get('direction') === "rtl" ? 'float-left' : 'float-right'}}">--}}
                {{--                                    <label class="for-shoting" for="sorting">--}}
                {{--                                        <span>{{\App\CPU\translate('sort_by')}}</span>--}}
                {{--                                    </label>--}}
                {{--                                    <select class="__inline-44"--}}
                {{--                                            onchange="filter(this.value)">--}}
                {{--                                        <option value="latest">{{\App\CPU\translate('Latest')}}</option>--}}
                {{--                                        <option--}}
                {{--                                            value="low-high">{{\App\CPU\translate('Low_to_High')}} {{\App\CPU\translate('Price')}} </option>--}}
                {{--                                        <option--}}
                {{--                                            value="high-low">{{\App\CPU\translate('High_to_Low')}} {{\App\CPU\translate('Price')}}</option>--}}
                {{--                                        <option--}}
                {{--                                            value="a-z">{{\App\CPU\translate('A_to_Z')}} {{\App\CPU\translate('Order')}}</option>--}}
                {{--                                        <option--}}
                {{--                                            value="z-a">{{\App\CPU\translate('Z_to_A')}} {{\App\CPU\translate('Order')}}</option>--}}
                {{--                                    </select>--}}
                {{--                                </div>--}}
                {{--                            </form>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                @if(isset($category_banners) && $category_banners->count() > 0)
                    <div class="row">
                        <div class="owl-carousel owl-theme footer_cat_banners" dir="ltr">

                            @if($category_banners->position == 0)
                                @php($banners = $category_banners->banners->where('banner_type', 'Main Section Banner'))
                            @elseif($category_banners->position == 1)
                                @php($banners = $category_banners->parent->banners->where('banner_type', 'Main Section Banner'))
                            @else
                                @php($banners = $category_banners->parent->parent->banners->where('banner_type', 'Main Section Banner'))
                            @endif
                            @foreach($banners as $banner)
                                <div
                                    class="item m-auto large-featured-offer text-center">
                                    <div class="row">
                                        <div class="col-xxl-12 col-xl-12 col-md-12 col-sm-12 col-12 my-2">
                                            <div class="large-offer">
                                                <img src="{{asset('storage/banner/'. $banner->photo)}}"
                                                     class="w-100 offer-image" alt="offer" style="height: 150px">
                                                <div class="offer-title">
                                                    <h4 class="bold">{{$banner->title}}</h4>
                                                    <a href="{{$banner->url}}" class="btn btn-sm btn-custom bold">
                                                        {{\App\CPU\translate('Shop Now')}}
                                                    </a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach


                        </div>

                    </div>
                @endif

                @if(isset($brands) && $brands->count() > 0 && $_GET['data_from'] == 'category')
                    <div class="col-xxl-12 col-xl-12 col-md-12 col-sm-12 col-12 m-auto pt-3">
                        <div class="owl-carousel owl-theme featured_brands" dir="ltr">
                            @foreach($brands as $brand)
                                <div class=" featured_brands_div m-auto text-center">
                                    <a href="javascript:void(0);">
                                        <img src="{{asset('storage/brand/'. $brand->image)}}"
                                             class="featured_brands_image"
                                             alt="" style="height: 77px">
                                        <h5 class="mt-3 bold sub_cat_head primary_color">{{$brand->name}}
                                        </h5>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif



                @if (count($products) > 0)
                    <div class="row mt-3 products_row" id="ajax-products">
                        @include('web-views.products._ajax-products',['products'=>$products,'decimal_point_settings'=>$decimal_point_settings])
                    </div>
                @else
                    <div class="text-center pt-5">
                        <h2>{{\App\CPU\translate('No Product Found')}}</h2>
                    </div>
                @endif
            </section>
        </div>
    </div>
@endsection

@push('script')

    <script src="{{asset('assets/front-end/js/price-range.js')}}"></script>

    @if(isset($_GET['max']))
        <script>

            $(document).ready(function () {
                $.get({
                    url: '{{url('/')}}/products',
                    data: {
                        id: '{{$data['id']}}',
                        name: '{{$data['name']}}',
                        data_from: '{{$data['data_from']}}',
                        sort_by: '{{$data['sort_by']}}',
                        min_price: 0,
                        max_price: '{{$_GET['max']}}',
                    },
                    dataType: 'json',
                    beforeSend: function () {
                        $('#loading').show();
                    },
                    success: function (response) {
                        $('#ajax-products').html(response.view);
                        $('#paginator-ajax').html(response.paginator);

                        console.log(response.data);
                        $('#price-filter-count').text(response.total_product + ' {{\App\CPU\translate('items found')}}')
                    },
                    complete: function () {
                        $('#loading').hide();
                    },
                });

            });
        </script>
    @endif

    <script>

        $(document).ready(function () {


            let color_item = $('.color-item');
            color_item.on('click', function () {
                let icon = $(this).find('i');
                let url = $(this).attr('data-value');
                color_item.find('i').hide();
                icon.show();
                if (url) {
                    window.location = url;
                }
            });

            $('.footer_cat_banners').owlCarousel({
                loop: true,
                autoplay: true,
                autoplayTimeout: 2000,
                autoplayHoverPause: true,
                margin: 10,
                dots: false,
                nav: false,
                // center: true,

                responsive: {
                    //X-Small
                    0: {
                        items: 1,
                        nav: false,
                    },
                    360: {
                        items: 1,
                        nav: false,
                    },
                    375: {
                        items: 1,
                        nav: false,

                    },
                    540: {
                        items: 2,
                        nav: false,

                    },
                    //Small
                    576: {
                        items: 2,
                        nav: false,

                    },
                    //Medium
                    768: {
                        items: 2,
                        nav: false,

                    },
                    //Large
                    992: {
                        items: 2,
                        nav: false,

                    },
                    //Extra large
                    1200: {
                        items: 2,
                        nav: false,

                    },
                    //Extra extra large
                    1400: {
                        items: 2,
                        nav: false,

                    }
                }
            });


        });

        function openNav() {
            document.getElementById("mySidepanel").style.width = "70%";
            document.getElementById("mySidepanel").style.height = "100vh";
        }

        function closeNav() {
            document.getElementById("mySidepanel").style.width = "0";
        }

        function filter(value) {
            $.get({
                url: '{{url('/')}}/products',
                data: {
                    id: '{{$data['id']}}',
                    name: '{{$data['name']}}',
                    data_from: '{{$data['data_from']}}',
                    min_price: '{{$data['min_price']}}',
                    max_price: '{{$data['max_price']}}',
                    sort_by: value
                },
                dataType: 'json',
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (response) {
                    $('#ajax-products').html(response.view);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }

        function searchByPrice() {
            let min = $('#min_price').val();
            let max = $('#max_price').val();
            $.get({
                url: '{{url('/')}}/products',
                data: {
                    id: '{{$data['id']}}',
                    name: '{{$data['name']}}',
                    data_from: '{{$data['data_from']}}',
                    sort_by: '{{$data['sort_by']}}',
                    min_price: min,
                    max_price: max,
                },
                dataType: 'json',
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (response) {
                    $('#ajax-products').html(response.view);
                    $('#paginator-ajax').html(response.paginator);

                    console.log(response.data);
                    $('#price-filter-count').text(response.total_product + ' {{\App\CPU\translate('items found')}}')
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }

        $('#searchByFilterValue, #searchByFilterValue-m').change(function () {
            var url = $(this).val();
            if (url) {
                window.location = url;
            }
            return false;
        });


        $('input[name="filter_type"]').change(function () {
            var url = $(this).val();
            if (url) {
                window.location = url;
            }
            return false;
        });

        $("#search-brand").on("keyup", function () {
            var value = this.value.toLowerCase().trim();
            $("#lista1 div>li").show().filter(function () {
                return $(this).text().toLowerCase().trim().indexOf(value) == -1;
            }).hide();
        });

        let toggle_icon = $('.toggle_icon');
        toggle_icon.on('click', function () {
            let target = $(this).next('ul.sub_from_main');
            let icon_on = $(this).find('.fa.fa-chevron-left');
            let icon_off = $(this).find('.fa.fa-chevron-down');

            if (icon_on.hasClass('d-none')) {
                icon_on.removeClass('d-none');
                icon_off.addClass('d-none');
            } else {
                icon_off.removeClass('d-none');
                icon_on.addClass('d-none');
            }
            target.slideToggle();
        });

        let open_filter = $('.open_filter');
        let cz_sidebar = $('.cz-sidebar');
        open_filter.on('click', function () {
            cz_sidebar.addClass('opened');
        });


        let close_btn = $('.close_btn');
        close_btn.on('click', function () {
            cz_sidebar.removeClass('opened');
        });

        $('.featured_brands').owlCarousel({
            loop: true,
            autoplay: true,
            autoplayTimeout: 2000,
            autoplayHoverPause: true,
            margin: 10,
            dots: false,
            // center: true,
            navText: ["<img src='{{asset('assets/front-end/img/pink-chevron-left.png')}}'>", "<img src='{{asset('assets/front-end/img/pink-chevron-right.png')}}'>"],

            responsive: {
                //X-Small
                0: {
                    items: 2,
                    nav: false,
                },
                360: {
                    items: 2,
                    nav: false,
                },
                375: {
                    items: 2,
                    nav: false,

                },
                540: {
                    items: 2,
                    nav: false,

                },
                //Small
                576: {
                    items: 3,
                    nav: false,

                },
                //Medium
                768: {
                    items: 4,
                    nav: false,

                },
                //Large
                992: {
                    items: 5,
                    nav: false,

                },
                //Extra large
                1200: {
                    items: 7,
                    nav: false,

                },
                //Extra extra large
                1400: {
                    items: 7,
                    nav: false,

                }
            }
        });
    </script>
    <script>
        $(document).ready(function () {
            let products_row = $('.products_row');
            let single_product_card = $('.single_product_card');
            let single_product_card_height = single_product_card.height() + 24;
            products_row.height(single_product_card_height * 3);

            // $(window).scroll(function() {
            //     showMoreProducts()
            // });
            $(window).on('scroll', function() {
                if ($(window).scrollTop() - 450 >= $(
                    '.products_row').offset().top + $('.products_row').
                outerHeight() - window.innerHeight) {
                    if((single_product_card.length / 4) * (single_product_card_height) > products_row.height()) {
                        products_row.height(products_row.height() + (single_product_card_height * 3));
                        // alert('still has products to show ')
                    }
                    // alert('You reached the end of the DIV');
                }
            });




        });

    </script>


@endpush
