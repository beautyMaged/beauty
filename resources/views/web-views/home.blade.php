@extends('layouts.front-end.app')

@section('title', $web_config['name']->value.' '.\App\CPU\translate('Online Shopping').' | '.$web_config['name']->value.' '.\App\CPU\translate(' Ecommerce'))

@push('css_or_js')
    {{--    <meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>--}}
    <meta property="og:title" content="Welcome To {{$web_config['name']->value}} Home"/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">
    {{--    <meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>--}}
    <meta property="twitter:title" content="Welcome To {{$web_config['name']->value}} Home"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">
    <link rel="stylesheet" href="{{asset('assets/front-end')}}/css/home.css"/>
    <style>
        .cz-countdown-days { border: .5px solid{{$web_config['primary_color']}}; } .btn-scroll-top { background: {{$web_config['primary_color']}}; } .__best-selling:hover .ptr, .flash_deal_product:hover .flash-product-title { color: {{$web_config['primary_color']}}; } .cz-countdown-hours { border: .5px solid{{$web_config['primary_color']}}; } .cz-countdown-minutes { border: .5px solid{{$web_config['primary_color']}}; } .cz-countdown-seconds { border: .5px solid{{$web_config['primary_color']}}; } .flash_deal_product_details .flash-product-price { color: {{$web_config['primary_color']}}; } .featured_deal_left { background: {{$web_config['primary_color']}} 0% 0% no-repeat padding-box; } .category_div:hover { color: {{$web_config['secondary_color']}}; } .deal_of_the_day { background: {{$web_config['secondary_color']}}; } .best-selleing-image { background: {{$web_config['primary_color']}}10; } .top-rated-image { background: {{$web_config['primary_color']}}10; } @media (max-width: 800px) { .categories-view-all { {{session('direction') === "rtl" ? 'margin-left: 10px;' : 'margin-right: 6px;'}} } .categories-title { {{Session::get('direction') === "rtl" ? 'margin-right: 0px;' : 'margin-left: 6px;'}} } .seller-list-title { {{Session::get('direction') === "rtl" ? 'margin-right: 0px;' : 'margin-left: 10px;'}} } .seller-list-view-all { {{Session::get('direction') === "rtl" ? 'margin-left: 20px;' : 'margin-right: 10px;'}} } .category-product-view-title { {{Session::get('direction') === "rtl" ? 'margin-right: 16px;' : 'margin-left: -8px;'}} } .category-product-view-all { {{Session::get('direction') === "rtl" ? 'margin-left: -7px;' : 'margin-right: 5px;'}} } } @media (min-width: 801px) { .categories-view-all { {{session('direction') === "rtl" ? 'margin-left: 30px;' : 'margin-right: 27px;'}} } .categories-title { {{Session::get('direction') === "rtl" ? 'margin-right: 25px;' : 'margin-left: 25px;'}} } .seller-list-title { {{Session::get('direction') === "rtl" ? 'margin-right: 6px;' : 'margin-left: 10px;'}} } .seller-list-view-all { {{Session::get('direction') === "rtl" ? 'margin-left: 12px;' : 'margin-right: 10px;'}} } .seller-card { {{Session::get('direction') === "rtl" ? 'padding-left:0px !important;' : 'padding-right:0px !important;'}} } .category-product-view-title { {{Session::get('direction') === "rtl" ? 'margin-right: 10px;' : 'margin-left: -12px;'}} } .category-product-view-all { {{Session::get('direction') === "rtl" ? 'margin-left: -20px;' : 'margin-right: 0px;'}} } } .countdown-card { background: {{$web_config['primary_color']}}10; } .flash-deal-text { color: {{$web_config['primary_color']}}; } .countdown-background { background: {{$web_config['primary_color']}}; } .czi-arrow-left { color: {{$web_config['primary_color']}}; background: {{$web_config['primary_color']}}10; } .czi-arrow-right { color: {{$web_config['primary_color']}}; background: {{$web_config['primary_color']}}10; } .flash-deals-background-image { background: {{$web_config['primary_color']}}10; } .view-all-text { color: {{$web_config['secondary_color']}} !important; } .feature-product .czi-arrow-left { color: {{$web_config['primary_color']}}; background: {{$web_config['primary_color']}}10 } .feature-product .czi-arrow-right { color: {{$web_config['primary_color']}}; background: {{$web_config['primary_color']}}10; font-size: 12px; }
        /*  */
    </style>
@endpush

@section('content')
    <div class="__inline-61">
    @php($decimal_point_settings = !empty(\App\CPU\Helpers::get_business_settings('decimal_point_settings')) ? \App\CPU\Helpers::get_business_settings('decimal_point_settings') : 0)
    <!-- Hero (Banners + Slider)-->
        <section class="bg-transparent mb-3">
            <div class="">
                <div class="row ">
                    <div class="col-12 col-lg-12 px-0 mx-0">
                        @include('web-views.partials._home-top-slider')
                    </div>
                </div>
            </div>
        </section>

        {{--  Start Featured Sub Categories--}}
        @php($main_cats = \App\Model\Category::where('position', 0)->where('home_status', true)->get())
        @if(isset($main_cats) && $main_cats->count() > 0)
            <section class="featured_subs mt-1" dir="rtl">
                <div class="container-fluid" style="">
                    <div class="row d-flex justify-content-center">
                        <div class="col-xxl-12 col-xl-12 col-md-12 col-sm-12 col-12">
                            {{-- <h4 class="main_title  bold s_25 mt-5 {{session('direction') == 'rtl' ? 'mr-5 text-right' : 'ml-5 text-left'}}">{{\App\CPU\translate('main_cats')}} </h4> --}}
                        </div>
                        <div class="col-xxl-11 col-xl-11 col-md-11 col-sm-12 col-12 m-auto pt-1">
                            <div class="owl-carousel owl-theme featured_cats1 mains" dir="ltr" data-items="10">
                                @foreach($main_cats as $main_cat)
                                    <div class=" featured_sub_cat  text-center d-flex justify-content-center align-items-center"> <a href="{{route('home')}}/products?id={{$main_cat->id}}&data_from=category&page=1" class=""> <img src="{{asset('storage/category/'. $main_cat->icon)}}" alt="" class="img-fluid" style="border-radius:50%;width:120px;height:120px"> <h5 class="mt-1 bold sub_cat_head primary_color"> {{$main_cat->name}} </h5> </a> </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>    
            </section>
        @endif
        {{-- End Featured Sub Categories--}}



        {{-- Start Sell by budget --}}
        @php($budget_filter = \App\Model\BudgetFilter::first())

        {{-- <section class="mt-1 px-2 py-2" >
            <div class="row">
                <div class="col-lg-9 col-md-9 col-12 m-auto p-4">
                    <div class="row text-center">
                        <div class="col-12 col-lg-12 col-md-12">
                            <h4 class="primary_color bold s_20 mb-4"> {{\App\CPU\translate('but_according')}} </h4>
                        </div>
                        <div class="col-lg-3 col-md-3 col-6 mb-2">
                            <div class="pricingTable yellow">
                                <div class="pricingTable-header">
                                    <div class="price-value"><span class="duration"> تسوق الآن </span> </div>
                                </div>
                                <ul class="pricing-content">
                                    <li> <div class="single_price_item position-relative" data-value="{{route('products', ['data_from' => 'latest', 'page' => '1', 'max' => $budget_filter->f_num])}}">
                                        <h4 class="num_fam bold s_14">  {{$budget_filter->f_num}}</h4>
                                    </div> </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-6 mb-2">
                            <div class="pricingTable yellow">
                                <div class="pricingTable-header">
                                    <div class="price-value"><span class="duration"> تسوق الآن </span> </div>
                                </div>
                                <ul class="pricing-content">
                                    <li><div class="single_price_item position-relative" data-value="{{route('products', ['data_from' => 'latest', 'page' => '1', 'max' => $budget_filter->s_num])}}">
                                        <h4 class="num_fam bold s_14">{{$budget_filter->s_num}}</h4>
                                    </div>  </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-6 mb-2">
                            <div class="pricingTable yellow">
                                <div class="pricingTable-header">
                                    <div class="price-value"><span class="duration"> تسوق الآن </span> </div>
                                </div>
                                <ul class="pricing-content">
                                    <li><div class="single_price_item position-relative" data-value="{{route('products', ['data_from' => 'latest', 'page' => '1', 'max' => $budget_filter->t_num])}}">
                                        <h4 class="num_fam bold s_14">{{$budget_filter->t_num}}</h4>
                                    </div>  </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-6 mb-2">
                            <div class="pricingTable yellow">
                                <div class="pricingTable-header">
                                    <div class="price-value"><span class="duration"> تسوق الآن </span> </div>
                                </div>
                                <ul class="pricing-content">
                                    <li> <div class="single_price_item position-relative" data-value="{{route('products', ['data_from' => 'latest', 'page' => '1', 'max' => $budget_filter->fo_num])}}">
                                        <h4 class="num_fam bold s_14">{{$budget_filter->fo_num}}</h4>
                                    </div> </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section> --}}

        {{-- End Sell by budget --}}

        {{-- Start flash deal--}}
        @php($flash_deals=\App\Model\FlashDeal::with(['products'=>function($query){
            $query->with('product')->whereHas('product',function($q){
                $q->active();
            });
            }])->where(['status'=>1])->where(['deal_type'=>'flash_deal'])->whereDate('start_date','<=',date('Y-m-d'))->whereDate('end_date','>=',date('Y-m-d'))->first())
            @if (isset($flash_deals))
                <section class="overflow-hidden" dir="rtl">
                    <div class="container">
                        <div class="flash-deal-view-all-web row d-lg-flex justify-content-{{Session::get('direction') === "rtl" ? 'start' : 'end'}}"
                            style="{{Session::get('direction') === "rtl" ? 'margin-left: 2px;' : 'margin-right:2px;'}}">
                            @if (count($flash_deals->products)>0)
                                <a class="text-capitalize view-all-text" href="{{route('flash-deals',[isset($flash_deals)?$flash_deals['id']:0])}}">
                                    {{ \App\CPU\translate('view_all')}}
                                    <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left mr-1 ml-n1 mt-1 float-left' : 'right ml-1 mr-n1'}}"></i>
                                </a>
                            @endif
                        </div>
                        <div class="row d-flex {{Session::get('direction') === "rtl" ? 'flex-row-reverse' : 'flex-row'}}">
                            <div class="col-xl-3 col-lg-4 mt-2 countdown-card">
                                <div class="m-2">
                                    <div class="flash-deal-text">
                                        <span>{{ \App\CPU\translate('flash deal')}}</span>
                                    </div>
                                    <div class="text-center text-white">
                                        <div class="countdown-background">
                                            <span class="cz-countdown d-flex justify-content-center align-items-center" data-countdown="{{isset($flash_deals)?date('m/d/Y',strtotime($flash_deals['end_date'])):''}} 11:59:00 PM">
                                                <span class="cz-countdown-days">
                                                    <span class="cz-countdown-value"></span>
                                                    <span>{{ \App\CPU\translate('day')}}</span>
                                                </span>
                                                <span class="cz-countdown-value p-1">:</span>
                                                <span class="cz-countdown-hours">
                                                    <span class="cz-countdown-value"></span>
                                                    <span>{{ \App\CPU\translate('hrs')}}</span>
                                                </span>
                                                <span class="cz-countdown-value p-1">:</span>
                                                <span class="cz-countdown-minutes">
                                                    <span class="cz-countdown-value"></span>
                                                    <span>{{ \App\CPU\translate('min')}}</span>
                                                </span>
                                                <span class="cz-countdown-value p-1">:</span>
                                                <span class="cz-countdown-seconds">
                                                    <span class="cz-countdown-value"></span>
                                                    <span>{{ \App\CPU\translate('sec')}}</span>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flash-deal-view-all-mobile col-lg-12 d-block d-xl-none" style="{{Session::get('direction') === "rtl" ? 'margin-left: 2px;' : 'margin-right:2px;'}}">
                            </div>
                            <div class="col-xl-9 col-lg-8 {{Session::get('direction') === "rtl" ? 'pr-md-4' : 'pl-md-4'}}">
                                <div class="d-lg-none {{Session::get('direction') === "rtl" ? 'text-left' : 'text-right'}}">
                                    <a class="mt-2 text-capitalize view-all-text" href="{{route('flash-deals',[isset($flash_deals)?$flash_deals['id']:0])}}">
                                        {{ \App\CPU\translate('view_all')}}
                                        <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left mr-1 ml-n1 mt-1 float-left' : 'right ml-1 mr-n1'}}"></i>
                                    </a>
                                </div>
                                <div class="carousel-wrap">
                                    <div class="owl-carousel owl-theme mt-2" id="flash-deal-slider" dir="ltr">
                                        @foreach($flash_deals->products as $key=>$deal)
                                            @if( $deal->product)
                                                @include('web-views.partials._product-card-1',['product'=>$deal->product,'decimal_point_settings'=>$decimal_point_settings])
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @endif
        {{-- End flash deal--}}

        {{-- Start Featured Deals --}}
        @php($featured_deals=\App\Model\FlashDeal::with(['products'=>function($query_one){
            $query_one->with('product.reviews')->whereHas('product',function($query_two){
                $query_two->active();
                });
            }])
            ->whereDate('start_date', '<=', date('Y-m-d'))->whereDate('end_date', '>=', date('Y-m-d'))
            ->where(['status'=>1])->where(['deal_type'=>'feature_deal'])
            ->first())
            @if(isset($featured_deals))
                <section class="featured_deal rtl mt-5">
                    <div class="container-fluid ">
                        <div class="row __featured-deal-wrap" style="background: {{$web_config['primary_color']}};">
                            <div class="col-12 pb-2">
                                @if (count($featured_deals->products)>0)
                                    <div class="{{Session::get('direction') === "rtl" ? 'text-left ml-lg-3' : 'text-right mr-lg-3'}}">
                                        <a class="text-capitalize text-white" href="{{route('products',['data_from'=>'featured_deal'])}}">
                                            {{ \App\CPU\translate('view_all')}}
                                            <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left mr-1 ml-n1 mt-1' : 'right ml-1'}} text-white"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="col-xl-3 col-lg-4">
                                <div class="m-lg-4 mb-4">
                                    <span class="featured_deal_title __pt-12">{{ \App\CPU\translate('featured_deal')}}</span>
                                    <br>
                                    <span class="text-white text-left">{{ \App\CPU\translate('See the latest deals and exciting new offers')}}!</span>
                                </div>
                            </div>
                            <div class="col-xl-9 col-lg-8 d-flex align-items-center justify-content-center {{Session::get('direction') === "rtl" ? 'pl-md-4' : 'pr-md-4'}}">
                                <div class="owl-carousel owl-theme" id="web-feature-deal-slider">
                                    @foreach($featured_deals->products as $key=>$product)
                                        @include('web-views.partials._feature-deal-product',['product'=>$product->product, 'decimal_point_settings'=>$decimal_point_settings])
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @endif
        {{-- End Featured Deals --}}

        {{-- Start latest Products--}}
        @if(isset($latest_products) && $latest_products->count() > 0)
            <section class="featured_subs_with_products mt-5" dir="rtl">
                <div class="container" style="">
                    <div class="row d-flex justify-content-center" dir="{{session('direction')}}">
                        <div class="col-md-2 col-6">
                            <h4 class="main_title s_25 bold mb-1 mt-5 {{session('direction') == 'rtl' ? 'mr-1' : 'ml-1'}} mb-0">
                                <a href="{{route('home')}}/products?data_from=latest&page=1"><span class="">{{\App\CPU\translate('recent_pro')}}</span></a>
                            </h4>
                        </div>
                        <div class="col-md-8 col-12 small-bann">
                            <img src="{{asset('assets/front-end/img/aaa.webp')}}" alt="" class="img-fluid" style="margin-top: 10px;">
                        </div>
                        <div class="col-md-2 col-6">
                            <div class="sorting_div s_12 bold mb-1 mt-5" dir="ltr">
                                <div class="w-100 text-right">
                                    <span class="bold sort-span"
                                        dir="rtl">{{\App\CPU\translate('sort_by')}} : <span>{{\App\CPU\translate('Most Favourit')}}</span> &nbsp;&nbsp;<i class="fa-solid fa-chevron-down s_12"></i></span>
                                </div>
                                <div
                                    class="sorting_list w-100 {{session('direction') == 'rtl' ? 'text-right' : 'text-left'}}">
                                    <span class="sort-item d-block px-2 active"
                                        data-value="{{route('products', ['data_from' => 'most-favorite', 'page' => 1])}}">{{\App\CPU\translate('Most Favourit')}}</span>
                                    <span class="sort-item d-block px-2"
                                        data-value="{{route('products', ['data_from' => 'best-selling', 'page' => 1])}}">{{\App\CPU\translate('top_sell_pro')}}</span>
                                    <span class="sort-item d-block px-2"
                                        data-value="{{route('products', ['data_from' => 'latest', 'page' => 1, 'sort_by' => 'high-low'])}}">{{\App\CPU\translate('price_low_high')}}</span>
                                    <span class="sort-item d-block px-2"
                                        data-value="{{route('products', ['data_from' => 'latest', 'page' => 1, 'sort_by' => 'low-high'])}}">{{\App\CPU\translate('price_high_low')}}</span>
                                </div>
                            </div>
                        </div>
                        @if(isset($latest_products) && $latest_products->count() > 0)
                        <div class="col-xxl-12 col-xl-12 col-md-12 col-sm-12 col-12 m-auto pt-0">
                            <div class="row slider_products_row" style="height: 800px">
                                <div class="col-xxl-10 col-xl-10 col-lg-10 col-md-10 col-sm-12 col-12">
                                    <div class="owl-carousel owl-theme products_carousel_container" dir="ltr">
                                        @foreach($latest_products as $k => $one_chunk)
                                            @if ($k == 0)
                                                @php($k = 0)
                                            @else
                                                @php($k = $k + 6)
                                            @endif
                                            {{--    @dd(json_decode($one_chunk[$k]))--}}
                                            <div class="row slider_products_item" style="height: 803px" dir="{{session('direction') == 'rtl' ? 'ltr' : 'rtl'}}">
                                                <div class="col-xxl-5 col-xl-5 col-md-5 col-sm-11 col-12 m-auto large-featured-product text-center"style="height:97%;">
                                                    <div class="row">
                                                        @php($images = json_decode($one_chunk[$k]->images))
                                                        <div class="real_image_container larg_container col-lg-12 col-md-12">
                                                            @if(count(json_decode($one_chunk[$k]->colors)) > 0)
                                                                @foreach(json_decode($one_chunk[$k]->color_image) as $k_img => $large_image)
                                                                    <img src="{{asset('storage/product/' . $large_image->image_name)}}"
                                                                        alt="product"
                                                                        style="{{$k_img == 0 ? 'display:block;' : 'display:none;'}}height:100%"
                                                                        class=" px-1 pt-2 {{$one_chunk[$k]->id}}_{{$k_img}}_img product_image">
                                                                @endforeach
                                                            @else
                                                                <img src="{{asset('storage/product/thumbnail/' . $one_chunk[$k]->thumbnail)}}"
                                                                    alt="product" style="height:100%"
                                                                    class="w-100 px-1 pt-2 product_image">
                                                            @endif
                                                        </div>
                                                        <div class="col-lg-12 col-md-12">
                                                            <a href="{{route('product', $one_chunk[$k]->slug)}}"><h4 class="s_27 primary_color bold"> {{$one_chunk[$k]->name}} </h4></a>
                                                        </div>
                                                        @php($overallRating = \App\CPU\ProductManager::get_overall_rating($one_chunk[$k]->reviews))
                                                        <div class="col-lg-12 col-md-12">
                                                            <div class="rate">
                                                                @for($inc=0;$inc<5;$inc++)
                                                                    @if($inc<$overallRating[0])
                                                                        <i class="p-0 sr-star czi-star-filled active"></i>
                                                                    @else
                                                                        <i class="p-0 sr-star czi-star" style="color:#fea569 !important"></i>
                                                                    @endif
                                                                @endfor
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12">
                                                            <div dir="rtl">
                                                                <span class="real_price s_27 bold ">
                                                                    {{\App\CPU\Helpers::currency_converter($one_chunk[$k]->unit_price-(\App\CPU\Helpers::get_product_discount($one_chunk[$k],$one_chunk[$k]->unit_price)))}}
                                                                </span>
                                                                @if($one_chunk[$k]->discount > 0)
                                                                    <span class="pre_price primary_color s_27 bold num_fam">{{\App\CPU\Helpers::currency_converter($one_chunk[$k]->unit_price)}}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        {{--{{dd(json_decode($one_chunk[$k]->colors))}}--}}
                                                        <div class="mt11 col-lg-12 col-md-12">
                                                            <ul class="list-inline checkbox-color mb-1 flex-start ml-2" style="padding-left: 0;min-height:37px;" dir="ltr">
                                                                @if(count(json_decode($one_chunk[$k]->colors)) > 0)
                                                                    @foreach(json_decode($one_chunk[$k]->colors) as $k_img => $large_image)
                                                                        <li>
                                                                            <input type="radio" id="{{$one_chunk[$k]->id}}_{{$k_img}}-color-{{$large_image}}" name="color_{{$one_chunk[$k]->id}}" value="#{{$large_image}}" checked="">
                                                                            <label style="background: {{$large_image}}; margin-bottom: 0!important;" data-target="{{$one_chunk[$k]->id}}_{{$k_img}}" for="{{$one_chunk[$k]->id}}_{{$k_img}}-color-{{$large_image}}" class="color-changer" data-toggle="tooltip" onclick="focus_preview_image_by_color('{{$large_image}}')"> <span class="outline"></span></label>
                                                                        </li>
                                                                    @endforeach
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xxl-7 col-xl-7 col-md-7 col-sm-12 col-12 m-auto text-center">
                                                    <div class="row">
                                                        @php($one_chunk->shift())
                                                        @foreach($one_chunk as $small_product)
                                                            <div class="col-xxl-4 col-xl-4 col-md-4 col-sm-6 col-6 my-2">
                                                                <div class="row">
                                                                    <div class="col-xxl-11 col-xl-11 col-md-11 col-sm-11 col-11 product_container m-auto">
                                                                        <div class=" row">
                                                                            @php($images = json_decode($small_product->images))
                                                                            <div class="real_image_container col-lg-12 col-md-12">
                                                                                @if(count(json_decode($small_product->colors)) > 0)
                                                                                    @foreach(json_decode($small_product->color_image) as $k_img => $large_image)
                                                                                        <img src="{{asset('storage/product/' . $large_image->image_name)}}"
                                                                                            alt="product" style="{{$k_img == 0 ? 'display:block;' : 'display:none;'}}height:100%"
                                                                                            class="w-100 px-1 pt-2 {{$small_product->id}}_{{$k_img+1}}_img product_image">
                                                                                    @endforeach
                                                                                @else
                                                                                    <img src="{{asset('storage/product/thumbnail/' . $small_product->thumbnail)}}" alt="product" style="height:100%" class="w-100 px-1 pt-2 product_image">
                                                                                @endif
                                                                            </div>
                                                                            <div class="col-lg-12 col-md-12  col-12">
                                                                                <a href="{{route('product', $small_product->slug)}}"> 
                                                                                    <h4 class="s_16 primary_color bold pt-3">  {{$small_product->name}} </h4>
                                                                                </a>
                                                                            </div>
                                                                            @php($overallRating = \App\CPU\ProductManager::get_overall_rating($small_product->reviews))
                                                                            <div class="col-lg-12 col-md-12">
                                                                                <div class="rate">
                                                                                    @for($inc=0;$inc<5;$inc++)
                                                                                        @if($inc<$overallRating[0])
                                                                                            <i class="p-0 sr-star czi-star-filled active"></i>
                                                                                        @else
                                                                                            <i class="p-0 sr-star czi-star" style="color:#fea569 !important"></i>
                                                                                        @endif
                                                                                    @endfor
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-12 col-md-12">
                                                                                <div dir="rtl">
                                                                            <span class="real_price s_16 bold ">
                                                                                {{\App\CPU\Helpers::currency_converter($small_product->unit_price-(\App\CPU\Helpers::get_product_discount($small_product,$small_product->unit_price)))}}
                                                                            </span>
                                                                                    @if($small_product->discount > 0)
                                                                                        <span
                                                                                            class="pre_price primary_color s_16 bold num_fam">{{\App\CPU\Helpers::currency_converter($small_product->unit_price)}}</span>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            <div class="mt13 col-lg-12 col-md-12">
                                                                                <ul class="list-inline checkbox-color mb-1 flex-start ml-2" style="padding-left: 0;" dir="ltr">
                                                                                    @if(count(json_decode($small_product->colors)) > 0)
                                                                                        @foreach(json_decode($small_product->colors) as $k_img => $small_color)
                                                                                            <li>
                                                                                                <input type="radio"
                                                                                                        id="{{$small_product->id}}_{{$k_img+1}}-color-{{$small_color}}"
                                                                                                        name="color_{{$small_product->id}}"
                                                                                                        value="#{{$small_color}}"
                                                                                                        checked="">
                                                                                                <label style="background: {{$small_color}};" data-target="{{$small_product->id}}_{{$k_img+1}}" for="{{$small_product->id}}_{{$k_img+1}}-color-{{$small_color}}" class="color-changer" data-toggle="tooltip" onclick="focus_preview_image_by_color('{{$small_color}}')"> <span class="outline"></span></label>
                                                                                            </li>
                                                                                        @endforeach
                                                                                    @endif
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-xxl-2 col-xl-2 col-md-2 col-sm-6 col-6 small-featured-product m-auto text-center">
                                    <div class="row">
                                        <div class="col-xxl-11 col-xl-11 col-md-11 col-sm-11 col-11 product_container_side m-auto p-0"
                                            style="background-image: url({{asset('assets/front-end/img/offer-2.png')}});background-size: 100% 100%">
                                            <a href="#" class="">
                                                <div class="featured-product-title p-3">
                                                    <h4 class="bold mb-1">{{\App\CPU\translate('Shop Now')}}</h4>
                                                    <span class="bold">{{\App\CPU\translate('recent_side_cont')}}</span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </section>
        @endif
        {{-- End latest Products--}}
        

        {{-- Start Featured Sub Categories --}}   
        @if(isset($featured_mains ) && $featured_mains->count() > 0)
            {{-- {{$featured_mains->pluck('name')->first()}}--}}
            @foreach($featured_mains as $k_m => $main)
                @php($porduct_d = App\Model\Product::active()->with(['reviews']))
                @php($all_products = $porduct_d->get())
                @php($id = $main->id)
                @php($caro_products_ids = [])
                @foreach ($all_products as $all_product)
                    @foreach (json_decode($all_product['category_ids'], true) as $product_cat)
                        @if ($product_cat['id'] == $id)
                            @php(array_push($caro_products_ids, $all_product['id']))
                        @endif
                    @endforeach
                @endforeach
                @php($porduct_d = $porduct_d->whereIn('id', $caro_products_ids)->inRandomOrder()->limit(21)->get()->chunk(7))
                @if($k_m == 1 )
                    @if($porduct_d->count() > 0 && $porduct_d->first()->count() > 6)
                        @if($main->childes->count() > 0)
                            <section class="featured_subs" dir="rtl">
                                <div class="container" style="">
                                    <div class="row" dir="{{session('direction')}}">
                                        <div class="col-md-2 col-6">
                                            <h4 class="main_title  bold s_25 mt-5 mr-5"> {{$main->name}} </h4>
                                        </div>
                                        <div class="col-md-8 col-12 small-bann">
                                            <img src="{{asset('assets/front-end/img/aaa.webp')}}" alt="" class="img-fluid" style="margin-top: 10px;">
                                        </div>
                                        <div class="col-md-2 col-6">
                                            <div class="sorting_div s_12 bold mb-1 mt-5" dir="ltr">
                                                <div class="w-100 text-right">
                                                    <span class="bold sort-span" dir="rtl">{{\App\CPU\translate('sort_by')}} : <span>{{\App\CPU\translate('Most Favourit')}}</span> &nbsp;&nbsp;<i class="fa-solid fa-chevron-down s_12"></i></span>
                                                </div>
                                                <div class="sorting_list w-100 {{session('direction') == 'rtl' ? 'text-right' : 'text-left'}}">
                                                    <span class="sort-item d-block px-2 active"
                                                        data-value="{{route('products', ['id' => $main->id, 'data_from' => 'most-favorite', 'page' => 1])}}">{{\App\CPU\translate('Most Favourit')}}</span>
                                                    <span class="sort-item d-block px-2"
                                                        data-value="{{route('products', ['id' => $main->id, 'data_from' => 'best-selling', 'page' => 1])}}">{{\App\CPU\translate('top_sell_pro')}}</span>
                                                    <span class="sort-item d-block px-2"
                                                        data-value="{{route('products', ['id' => $main->id, 'data_from' => 'category', 'page' => 1, 'sort_by' => 'high-low'])}}">{{\App\CPU\translate('price_low_high')}}</span>
                                                    <span class="sort-item d-block px-2"
                                                        data-value="{{route('products', ['id' => $main->id, 'data_from' => 'category', 'page' => 1, 'sort_by' => 'low-high'])}}">{{\App\CPU\translate('price_high_low')}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-11 col-xl-11 col-md-11 col-sm-12 col-12 m-auto pt-3">
                                            <div class="owl-carousel owl-theme featured_cats" dir="ltr">
                                                @if(isset($main->childes) && $main->childes->count() > 0)
                                                    @foreach($main->childes as $sub)
                                                        <div class=" featured_sub_cat m-auto text-center">
                                                            <a href="{{route('home')}}/products?id={{$sub->id}}&data_from=category&page=1">
                                                                <img src="{{asset('storage/category/'. $sub->icon)}}"alt="">
                                                                <h5 class="mt-3 bold sub_cat_head primary_color">{{$sub->name}}</h5>
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @endif
                        @if(isset($porduct_d) && $porduct_d->count() > 0)
                            <section class="featured_subs_with_products" dir="rtl">
                                <div class="container" style="">
                                    <div class="row" dir="{{session('direction')}}">
                                        <div class="col-xxl-12 col-xl-12 col-md-12 col-sm-12 col-12 m-auto pt-0">
                                            <div class="row slider_products_row" style="height: 800px">
                                                <div class="col-xxl-10 col-xl-10 col-lg-10 col-md-10 col-sm-12 col-12">
                                                    <div class="owl-carousel owl-theme products_carousel_container" dir="ltr">
                                                        @foreach($porduct_d as $k => $one_chunk)
                                                            @if(count($one_chunk) > 6)
                                                                @if ($k == 0)
                                                                    @php($k = 0)
                                                                @else
                                                                    @php($k = $k + 6)
                                                                @endif
                                                                <div class="row slider_products_item" style="height: 803px" dir="{{session('direction') == 'rtl' ? 'ltr' : 'rtl'}}">
                                                                    <div class="col-xxl-5 col-xl-5 col-md-5 col-sm-11 col-12 m-auto   large-featured-product text-center" style="height:97%;">
                                                                        <div class="row">
                                                                            @php($images = json_decode($one_chunk[$k]->images))
                                                                            <div class="real_image_container larg_container col-lg-12 col-md-12">
                                                                                @if(count(json_decode($one_chunk[$k]->colors)) > 0)
                                                                                    @foreach(json_decode($one_chunk[$k]->color_image) as $k_img => $large_image)
                                                                                        <img src="{{asset('storage/product/' . $large_image->image_name)}}"
                                                                                            alt="product"
                                                                                            style="{{$k_img == 0 ? 'display:block;' : 'display:none;'}}height:100%"
                                                                                            class=" px-1 pt-2 {{$one_chunk[$k]->id}}_{{$k_img}}_img product_image">
                                                                                    @endforeach
                                                                                @else
                                                                                    <img src="{{asset('storage/product/thumbnail/' . $one_chunk[$k]->thumbnail)}}"
                                                                                        alt="product"
                                                                                        style="height:100%"
                                                                                        class="w-100 px-1 pt-2 product_image">
                                                                                @endif
                                                                            </div>
                                                                            <div class="col-lg-12 col-md-12">
                                                                                <a href="{{route('product', $one_chunk[$k]->slug)}}">
                                                                                    <h4 class="s_27 primary_color bold"> {{$one_chunk[$k]->name}} </h4></a>
                                                                            </div>
                                                                            @php($overallRating = \App\CPU\ProductManager::get_overall_rating($one_chunk[$k]->reviews))
                                                                            <div class="col-lg-12 col-md-12">
                                                                                <div class="rate">
                                                                                    @for($inc=0;$inc<5;$inc++)
                                                                                        @if($inc<$overallRating[0])
                                                                                            <i class="p-0 sr-star czi-star-filled active"></i>
                                                                                        @else
                                                                                            <i class="p-0 sr-star czi-star" style="color:#fea569 !important"></i>
                                                                                        @endif
                                                                                    @endfor
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-12 col-md-12">
                                                                                <div dir="rtl">
                                                                                <span class="real_price s_27 bold ">
                                                                                    {{\App\CPU\Helpers::currency_converter($one_chunk[$k]->unit_price-(\App\CPU\Helpers::get_product_discount($one_chunk[$k],$one_chunk[$k]->unit_price)))}}
                                                                                </span>
                                                                                    @if($one_chunk[$k]->discount > 0)
                                                                                        <span
                                                                                            class="pre_price primary_color s_27 bold num_fam">{{\App\CPU\Helpers::currency_converter($one_chunk[$k]->unit_price)}}</span>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            {{-- {{dd(json_decode($one_chunk[$k]->colors))}}--}}
                                                                            <div class="mt13 col-lg-12 col-md-12">
                                                                                <ul class="list-inline checkbox-color mb-1 flex-start ml-2" style="padding-left: 0;min-height:37px;" dir="ltr">
                                                                                    @if(count(json_decode($one_chunk[$k]->colors)) > 0)
                                                                                        @foreach(json_decode($one_chunk[$k]->colors) as $k_img => $large_image)
                                                                                            <li>
                                                                                                <input type="radio" id="{{$one_chunk[$k]->id}}_{{$k_img}}-color-{{$large_image}}" name="color_{{$one_chunk[$k]->id}}" value="#{{$large_image}}" checked="">
                                                                                                <label style="background: {{$large_image}};" data-target="{{$one_chunk[$k]->id}}_{{$k_img}}" for="{{$one_chunk[$k]->id}}_{{$k_img}}-color-{{$large_image}}" class="color-changer" data-toggle="tooltip" onclick="focus_preview_image_by_color('{{$large_image}}')"> <span class="outline"></span></label>
                                                                                            </li>
                                                                                        @endforeach
                                                                                    @endif
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div
                                                                        class="col-xxl-7 col-xl-7 col-md-7 col-sm-12 col-12 m-auto text-center">
                                                                        <div class="row">
                                                                            @php($one_chunk->shift())
                                                                            @foreach($one_chunk as $small_product)
                                                                                <div class="col-xxl-4 col-xl-4 col-md-4 col-sm-6 col-6 my-2">
                                                                                    <div class="row">
                                                                                        <div class="col-xxl-11 col-xl-11 col-md-11 col-sm-11 col-11 product_container m-auto">
                                                                                            <div class=" row">
                                                                                                @php($images = json_decode($small_product->images))
                                                                                                <div class="real_image_container col-lg-12 col-md-12">
                                                                                                    @if(count(json_decode($small_product->colors)) > 0)
                                                                                                        @foreach(json_decode($small_product->color_image) as $k_img => $large_image)
                                                                                                            <img src="{{asset('storage/product/' . $large_image->image_name)}}"
                                                                                                                alt="product"
                                                                                                                style="{{$k_img == 0 ? 'display:block;' : 'display:none;'}}height:100%"
                                                                                                                class="w-100 px-1 pt-2 {{$small_product->id}}_{{$k_img}}_img product_image">
                                                                                                        @endforeach
                                                                                                    @else
                                                                                                        <img src="{{asset('storage/product/thumbnail/' . $small_product->thumbnail)}}"
                                                                                                            alt="product"
                                                                                                            style="height:100%"
                                                                                                            class="w-100 px-1 pt-2 product_image">
                                                                                                    @endif
                                                                                                </div>
                                                                                                <div
                                                                                                    class="col-lg-12 col-md-12  col-12">
                                                                                                    <a href="{{route('product', $small_product->slug)}}">
                                                                                                        <h4 class="s_16 primary_color bold pt-3"> {{$small_product->name}} </h4></a>
                                                                                                </div>
                                                                                                @php($overallRating = \App\CPU\ProductManager::get_overall_rating($small_product->reviews))
                                                                                                <div
                                                                                                    class="col-lg-12 col-md-12">
                                                                                                    <div class="rate">
                                                                                                        @for($inc=0;$inc<5;$inc++)
                                                                                                            @if($inc<$overallRating[0])
                                                                                                                <i class="p-0 sr-star czi-star-filled active"></i>
                                                                                                            @else
                                                                                                                <i class="p-0 sr-star czi-star" style="color:#fea569 !important"></i>
                                                                                                            @endif
                                                                                                        @endfor
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-lg-12 col-md-12">
                                                                                                    <div dir="rtl">
                                                                                                        <span class="real_price s_16 bold ">
                                                                                                            {{\App\CPU\Helpers::currency_converter($small_product->unit_price-(\App\CPU\Helpers::get_product_discount($small_product,$small_product->unit_price)))}}
                                                                                                        </span>
                                                                                                        @if($small_product->discount > 0)
                                                                                                            <span class="pre_price primary_color s_16 bold num_fam">{{\App\CPU\Helpers::currency_converter($small_product->unit_price)}}</span>
                                                                                                        @endif
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="mt-1 col-lg-12 col-md-12">
                                                                                                    <ul class="list-inline checkbox-color mb-1 flex-start ml-2" style="padding-left: 0;" dir="ltr">
                                                                                                        @if(count(json_decode($small_product->colors)) > 0)
                                                                                                            @foreach(json_decode($small_product->colors) as $k_img => $small_color)
                                                                                                                <li>
                                                                                                                    <input type="radio" id="{{$small_product->id}}_{{$k_img}}-color-{{$small_color}}" name="color_{{$small_product->id}}" value="#{{$small_color}}" checked="">
                                                                                                                    <label style="background: {{$small_color}};" data-target="{{$small_product->id}}_{{$k_img}}" for="{{$small_product->id}}_{{$k_img}}-color-{{$small_color}}" class="color-changer" data-toggle="tooltip" onclick="focus_preview_image_by_color('{{$small_color}}')"> <span class="outline"></span></label>
                                                                                                                </li>
                                                                                                            @endforeach
                                                                                                        @endif
                                                                                                    </ul>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="col-xxl-2 col-xl-2 col-md-2 col-sm-6 col-6 small-featured-product m-auto text-center">
                                                    @if($main->banners->where('banner_type', 'Main Section Banner') != null && $main->banners->where('banner_type', 'Main Section Banner')->count() > 0)
                                                        <div class="row">
                                                            <div class="col-xxl-11 col-xl-11 col-md-11 col-sm-11 col-11 product_container_side m-auto p-0"
                                                                style="background-image: url({{asset('storage/banner/' . $main->banners->where('banner_type', 'Main Section Banner')->first()->photo)}});background-size: 100% 100%">
                                                                <a href="{{$main->banners->where('banner_type', 'Main Section Banner')->first()->url}}" class="">
                                                                    <div class="featured-product-title p-3">
                                                                        <h4 class="bold mb-1">{{$main->banners->where('banner_type', 'Main Section Banner')->first()->title}}</h4>
                                                                        <span class="bold">{{$main->banners->where('banner_type', 'Main Section Banner')->first()->description}}</span>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @if($main->banners->where('banner_type', 'Footer Banner') != null && $main->banners->where('banner_type', 'Footer Banner')->count() > 0)
                                            <div class="col-xxl-12 col-xl-12 col-md-12 col-sm-12 col-12 mt-5  pt-3">
                                                <div class="owl-carousel owl-theme footer_cat_banners" dir="ltr">
                                                    @foreach($main->banners->where('banner_type', 'Footer Banner') as $footer_cat_banner)
                                                        <div class="item m-auto large-featured-offer text-center">
                                                            <div class="row">
                                                                <div class="col-xxl-12 col-xl-12 col-md-12 col-sm-12 col-12 my-2">
                                                                    <div class="large-offer">
                                                                        <img src="{{asset('storage/banner/'. $footer_cat_banner->photo)}}"
                                                                            class="w-100 offer-image" alt="offer">
                                                                        <div class="offer-title">
                                                                            <h4 class="bold">{{$footer_cat_banner->title}}</h4>
                                                                            <a href="{{$footer_cat_banner->url}}" class="btn btn-sm btn-custom bold"> {{\App\CPU\translate('Shop Now')}} </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </section>
                    @endif
                @endif

                @elseif($k_m == 2)
                    @if($porduct_d->count() > 0 && $porduct_d->first()->count() > 6)
                    @if($main->childes->count() > 0)
                        <section class="featured_subs" dir="rtl">
                            <div class="container" style="">
                                <div class="row" dir="{{session('direction')}}">
                                    <div class="col-md-2 col-6">
                                        <h4 class="main_title  bold s_25 mt-5 mr-5"> {{$main->name}} </h4>
                                    </div>
                                    <div class="col-md-8 col-12 small-bann">
                                        <img src="{{asset('assets/front-end/img/aaa.webp')}}" alt="" class="img-fluid" style="margin-top: 10px;">
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <div class="sorting_div s_12 bold mb-1 mt-5" dir="ltr">
                                            <div class="w-100 text-right">
                                                <span class="bold sort-span" dir="rtl">{{\App\CPU\translate('sort_by')}} : <span>{{\App\CPU\translate('Most Favourit')}}</span> &nbsp;&nbsp;<i class="fa-solid fa-chevron-down s_12"></i></span>
                                            </div>
                                            <div class="sorting_list w-100 {{session('direction') == 'rtl' ? 'text-right' : 'text-left'}}">
                                                <span class="sort-item d-block px-2 active"
                                                    data-value="{{route('products', ['id' => $main->id, 'data_from' => 'most-favorite', 'page' => 1])}}">{{\App\CPU\translate('Most Favourit')}}</span>
                                                <span class="sort-item d-block px-2"
                                                    data-value="{{route('products', ['id' => $main->id, 'data_from' => 'best-selling', 'page' => 1])}}">{{\App\CPU\translate('top_sell_pro')}}</span>
                                                <span class="sort-item d-block px-2"
                                                    data-value="{{route('products', ['id' => $main->id, 'data_from' => 'category', 'page' => 1, 'sort_by' => 'high-low'])}}">{{\App\CPU\translate('price_low_high')}}</span>
                                                <span class="sort-item d-block px-2"
                                                    data-value="{{route('products', ['id' => $main->id, 'data_from' => 'category', 'page' => 1, 'sort_by' => 'low-high'])}}">{{\App\CPU\translate('price_high_low')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-11 col-xl-11 col-md-11 col-sm-12 col-12 m-auto pt-3">
                                        <div class="owl-carousel owl-theme featured_cats" dir="ltr">
                                            @if(isset($main->childes) && $main->childes->count() > 0)
                                                @foreach($main->childes as $sub)
                                                    <div class=" featured_sub_cat m-auto text-center">
                                                        <a href="{{route('home')}}/products?id={{$sub->id}}&data_from=category&page=1">
                                                            <img src="{{asset('storage/category/'. $sub->icon)}}" alt="">
                                                            <h5 class="mt-3 bold sub_cat_head primary_color">{{$sub->name}} </h5>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    @endif
                    @if(isset($porduct_d) && $porduct_d->count() > 0)
                        <section class="featured_subs_with_products" dir="rtl">
                            <div class="container" style="">
                                <div class="row" dir="{{session('direction')}}">
                                    <div class="col-xxl-12 col-xl-12 col-md-12 col-sm-12 col-12 m-auto pt-0">
                                        <div class="row slider_products_row" style="height: 800px">
                                            <div class="col-xxl-10 col-xl-10 col-lg-10 col-md-10 col-sm-12 col-12">
                                                <div class="owl-carousel owl-theme products_carousel_container" dir="ltr">
                                                    @foreach($porduct_d as $k => $one_chunk)
                                                        @if(count($one_chunk) > 6)
                                                            @if ($k == 0)
                                                                @php($k = 0)
                                                            @else
                                                                @php($k = $k + 6)
                                                            @endif
                                                            <div class="row slider_products_item" style="height: 803px" dir="{{session('direction') == 'rtl' ? 'ltr' : 'rtl'}}">
                                                                <div class="col-xxl-5 col-xl-5 col-md-5 col-sm-11 col-12 m-auto  large-featured-product text-center" style="height:97%;">
                                                                    <div class="row">
                                                                        @php($images = json_decode($one_chunk[$k]->images))
                                                                        <div class="real_image_container larg_container col-lg-12 col-md-12">
                                                                            @if(count(json_decode($one_chunk[$k]->colors)) > 0)
                                                                                @foreach(json_decode($one_chunk[$k]->color_image) as $k_img => $large_image)
                                                                                    <img src="{{asset('storage/product/' . $large_image->image_name)}}"
                                                                                        alt="product"
                                                                                        style="{{$k_img == 0 ? 'display:block;' : 'display:none;'}}height:100%"
                                                                                        class=" px-1 pt-2 {{$one_chunk[$k]->id}}_{{$k_img}}_img product_image">
                                                                                @endforeach
                                                                            @else
                                                                                <img src="{{asset('storage/product/thumbnail/' . $one_chunk[$k]->thumbnail)}}"
                                                                                    alt="product"
                                                                                    style="height:100%"
                                                                                    class="w-100 px-1 pt-2 product_image">
                                                                            @endif
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12">
                                                                            <a href="{{route('product', $one_chunk[$k]->slug)}}">
                                                                                <h4 class="s_27 primary_color bold"> {{$one_chunk[$k]->name}} </h4></a>
                                                                        </div>
                                                                        @php($overallRating = \App\CPU\ProductManager::get_overall_rating($one_chunk[$k]->reviews))
                                                                        <div class="col-lg-12 col-md-12">
                                                                            <div class="rate">
                                                                                @for($inc=0;$inc<5;$inc++)
                                                                                    @if($inc<$overallRating[0])
                                                                                        <i class="p-0 sr-star czi-star-filled active"></i>
                                                                                    @else
                                                                                        <i class="p-0 sr-star czi-star" style="color:#fea569 !important"></i>
                                                                                    @endif
                                                                                @endfor
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12">
                                                                            <div dir="rtl">
                                                                                <span class="real_price s_27 bold ">
                                                                                    {{\App\CPU\Helpers::currency_converter($one_chunk[$k]->unit_price-(\App\CPU\Helpers::get_product_discount($one_chunk[$k],$one_chunk[$k]->unit_price)))}}
                                                                                </span>
                                                                                @if($one_chunk[$k]->discount > 0)
                                                                                    <span
                                                                                        class="pre_price primary_color s_27 bold num_fam">{{\App\CPU\Helpers::currency_converter($one_chunk[$k]->unit_price)}}</span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        {{-- {{dd(json_decode($one_chunk[$k]->colors))}}--}}
                                                                        <div class="mt13 col-lg-12 col-md-12">
                                                                            <ul class="list-inline checkbox-color mb-1 flex-start ml-2" style="padding-left: 0;min-height:37px;" dir="ltr">
                                                                                @if(count(json_decode($one_chunk[$k]->colors)) > 0)
                                                                                    @foreach(json_decode($one_chunk[$k]->colors) as $k_img => $large_image)
                                                                                        <li>
                                                                                            <input type="radio" id="{{$one_chunk[$k]->id}}_{{$k_img}}-color-{{$large_image}}" name="color_{{$one_chunk[$k]->id}}" value="#{{$large_image}}" checked="">
                                                                                            <label style="background: {{$large_image}};" data-target="{{$one_chunk[$k]->id}}_{{$k_img}}" for="{{$one_chunk[$k]->id}}_{{$k_img}}-color-{{$large_image}}" class="color-changer" data-toggle="tooltip" onclick="focus_preview_image_by_color('{{$large_image}}')"> <span class="outline"></span></label>
                                                                                        </li>
                                                                                    @endforeach
                                                                                @endif
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xxl-7 col-xl-7 col-md-7 col-sm-12 col-12 m-auto text-center">
                                                                    <div class="row">
                                                                        @php($one_chunk->shift())
                                                                        @foreach($one_chunk as $small_product)
                                                                            <div class="col-xxl-4 col-xl-4 col-md-4 col-sm-6 col-6 my-2">
                                                                                <div class="row">
                                                                                    <div class="col-xxl-11 col-xl-11 col-md-11 col-sm-11 col-11 product_container m-auto">
                                                                                        <div class=" row">
                                                                                            @php($images = json_decode($small_product->images))
                                                                                            <div class="real_image_container col-lg-12 col-md-12">
                                                                                                @if(count(json_decode($small_product->colors)) > 0)
                                                                                                    @foreach(json_decode($small_product->color_image) as $k_img => $large_image)
                                                                                                        <img src="{{asset('storage/product/' . $large_image->image_name)}}"
                                                                                                            alt="product"
                                                                                                            style="{{$k_img == 0 ? 'display:block;' : 'display:none;'}}height:100%"
                                                                                                            class="w-100 px-1 pt-2 {{$small_product->id}}_{{$k_img}}_img product_image">
                                                                                                    @endforeach
                                                                                                @else
                                                                                                    <img src="{{asset('storage/product/thumbnail/' . $small_product->thumbnail)}}"
                                                                                                        alt="product"
                                                                                                        style="height:100%"
                                                                                                        class="w-100 px-1 pt-2 product_image">
                                                                                                @endif
                                                                                            </div>
                                                                                            <div
                                                                                                class="col-lg-12 col-md-12  col-12">
                                                                                                <a href="{{route('product', $small_product->slug)}}">
                                                                                                    <h4 class="s_16 primary_color bold pt-3"> {{$small_product->name}} </h4></a>
                                                                                            </div>
                                                                                            @php($overallRating = \App\CPU\ProductManager::get_overall_rating($small_product->reviews))
                                                                                            <div
                                                                                                class="col-lg-12 col-md-12">
                                                                                                <div class="rate">
                                                                                                    @for($inc=0;$inc<5;$inc++)
                                                                                                        @if($inc<$overallRating[0])
                                                                                                            <i class="p-0 sr-star czi-star-filled active"></i>
                                                                                                        @else
                                                                                                            <i class="p-0 sr-star czi-star" style="color:#fea569 !important"></i>
                                                                                                        @endif
                                                                                                    @endfor
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-12 col-md-12">
                                                                                                <div dir="rtl">
                                                                                                    <span class="real_price s_16 bold ">
                                                                                                        {{\App\CPU\Helpers::currency_converter($small_product->unit_price-(\App\CPU\Helpers::get_product_discount($small_product,$small_product->unit_price)))}}
                                                                                                    </span>
                                                                                                    @if($small_product->discount > 0)
                                                                                                        <span
                                                                                                            class="pre_price primary_color s_16 bold num_fam">{{\App\CPU\Helpers::currency_converter($small_product->unit_price)}}</span>
                                                                                                    @endif
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="mt-1 col-lg-12 col-md-12">
                                                                                                <ul class="list-inline checkbox-color mb-1 flex-start ml-2" style="padding-left: 0;" dir="ltr">
                                                                                                    @if(count(json_decode($small_product->colors)) > 0)
                                                                                                        @foreach(json_decode($small_product->colors) as $k_img => $small_color)
                                                                                                            <li>
                                                                                                                <input type="radio" id="{{$small_product->id}}_{{$k_img}}-color-{{$small_color}}" name="color_{{$small_product->id}}" value="#{{$small_color}}" checked="">
                                                                                                                <label style="background: {{$small_color}};" data-target="{{$small_product->id}}_{{$k_img}}" for="{{$small_product->id}}_{{$k_img}}-color-{{$small_color}}" class="color-changer" data-toggle="tooltip" onclick="focus_preview_image_by_color('{{$small_color}}')"> <span class="outline"></span></label>
                                                                                                            </li>
                                                                                                        @endforeach
                                                                                                    @endif
                                                                                                </ul>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="col-xxl-2 col-xl-2 col-md-2 col-sm-6 col-6 small-featured-product m-auto text-center">
                                                @if($main->banners->where('banner_type', 'Main Section Banner') != null && $main->banners->where('banner_type', 'Main Section Banner')->count() > 0)
                                                    <div class="row">
                                                        <div class="col-xxl-11 col-xl-11 col-md-11 col-sm-11 col-11 product_container_side m-auto p-0" style="background-image: url({{asset('storage/banner/' . $main->banners->where('banner_type', 'Main Section Banner')->first()->photo)}});background-size: 100% 100%">
                                                            <a href="{{$main->banners->where('banner_type', 'Main Section Banner')->first()->url}}" class="">
                                                                <div class="featured-product-title p-3">
                                                                    <h4 class="bold mb-1">{{$main->banners->where('banner_type', 'Main Section Banner')->first()->title}}</h4>
                                                                    <span class="bold">{{$main->banners->where('banner_type', 'Main Section Banner')->first()->description}}</span>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @if($main->banners->where('banner_type', 'Footer Banner') != null && $main->banners->where('banner_type', 'Footer Banner')->count() > 0)
                                        <div class="col-xxl-12 col-xl-12 col-md-12 col-sm-12 col-12 mt-5  pt-3">
                                            <div class="owl-carousel owl-theme footer_cat_banners" dir="ltr">
                                                @foreach($main->banners->where('banner_type', 'Footer Banner') as $footer_cat_banner)
                                                    <div class="item m-auto large-featured-offer text-center" style="max-height: 340px;">
                                                        <div class="row">
                                                            <div class="col-xxl-12 col-xl-12 col-md-12 col-sm-12 col-12 my-2">
                                                                <div class="large-offer">
                                                                    <img src="{{asset('storage/banner/'. $footer_cat_banner->photo)}}"
                                                                        class="w-100 offer-image" alt="offer">
                                                                    <div class="offer-title">
                                                                        <h4 class="bold">{{$footer_cat_banner->title}}</h4>
                                                                        <a href="{{$footer_cat_banner->url}}" class="btn btn-sm btn-custom bold">
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
                                    @if(isset($main_products_banners) && $main_products_banners->count() > 0)
                                        <div class="col-xxl-12 col-xl-12 col-md-12 col-sm-12 col-12 mt-5  pt-3">
                                            <div class="owl-carousel owl-theme main_products_banners" dir="ltr">
                                                @foreach($main_products_banners as $main_products_banner)
                                                    <div class="item custom-product-banner_div position-relative">
                                                        <div style="width: 100%;height: 100%;position: absolute;z-index: 100;top: 0;right: 0;background: rgba(0,0,0,0.1);border-radius: 5px;"></div>
                                                        <img src="{{asset('storage/banner/'. $main_products_banner->photo)}}" alt="" style="border-radius: 5px;max-height: 250px">
                                                        <div class="banner-details" dir="rtl">
                                                            <h4 class="bold s_30"> {{$main_products_banner->title}} </h4>
                                                                <span class="bold"> {{$main_products_banner->description}} </span>
                                                            <div>
                                                                <div class="price d-inline-block">
                                                                    <span class="num_fam primary_color pl-3 s_30">{{\App\CPU\Helpers::currency_converter($main_products_banner->product->unit_price)}}</span>
                                                                </div>
                                                                <div class="redirect_btn  d-inline-block mt-2 mr-3">
                                                                    <a href="{{$main_products_banner->url}}" class="btn btn-sm btn-custom bold">
                                                                        {{\App\CPU\translate('Shop Now')}}
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </section>
                    @endif
                @endif
            @else
                @if($porduct_d->count() > 0 && $porduct_d->first()->count() > 6)
                    @if($main->childes->count() > 0)
                        <section class="featured_subs" dir="rtl">
                            <div class="container" style="">
                                <div class="row" dir="{{session('direction')}}">
                                    <div class="col-md-2 col-6">
                                        <h4 class="main_title  bold s_25 mt-5 {{session('direction') == 'rtl' ? 'mr-5' : 'ml-5'}}"> {{$main->name}} </h4>
                                    </div>
                                    <div class="col-md-8 col-12 small-bann">
                                        <img src="{{asset('assets/front-end/img/aaa.webp')}}" alt="" class="img-fluid" style="margin-top: 10px;">
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <div class="sorting_div s_12 bold mb-1 mt-5" dir="ltr">
                                            <div class="w-100 text-right">
                                                <span class="bold sort-span" dir="rtl">{{\App\CPU\translate('sort_by')}} : <span>{{\App\CPU\translate('Most Favourit')}}</span> &nbsp;&nbsp;<i class="fa-solid fa-chevron-down s_12"></i></span>
                                            </div>
                                            <div class="sorting_list w-100 {{session('direction') == 'rtl' ? 'text-right' : 'text-left'}}">
                                                <span class="sort-item d-block px-2 active"
                                                    data-value="{{route('products', ['id' => $main->id, 'data_from' => 'most-favorite', 'page' => 1])}}">{{\App\CPU\translate('Most Favourit')}}</span>
                                                <span class="sort-item d-block px-2"
                                                    data-value="{{route('products', ['id' => $main->id, 'data_from' => 'best-selling', 'page' => 1])}}">{{\App\CPU\translate('top_sell_pro')}}</span>
                                                <span class="sort-item d-block px-2"
                                                    data-value="{{route('products', ['id' => $main->id, 'data_from' => 'category', 'page' => 1, 'sort_by' => 'high-low'])}}">{{\App\CPU\translate('price_low_high')}}</span>
                                                <span class="sort-item d-block px-2"
                                                    data-value="{{route('products', ['id' => $main->id, 'data_from' => 'category', 'page' => 1, 'sort_by' => 'low-high'])}}">{{\App\CPU\translate('price_high_low')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-11 col-xl-11 col-md-11 col-sm-12 col-12 m-auto pt-3">
                                        <div class="owl-carousel owl-theme featured_cats" dir="ltr">
                                            @if(isset($main->childes) && $main->childes->count() > 0)
                                                @foreach($main->childes as $sub)
                                                    <div class=" featured_sub_cat m-auto text-center">
                                                        <a href="{{route('home')}}/products?id={{$sub->id}}&data_from=category&page=1">
                                                            <img src="{{asset('storage/category/'. $sub->icon)}}" alt="">
                                                            <h5 class="mt-3 bold sub_cat_head primary_color"> {{$sub->name}} </h5>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    @endif
                    @if(isset($porduct_d) && $porduct_d->count() > 0)
                        <section class="featured_subs_with_products" dir="rtl">
                            <div class="container" style="">
                                <div class="row" dir="{{session('direction')}}">
                                    <div class="col-xxl-12 col-xl-12 col-md-12 col-sm-12 col-12 m-auto pt-0">
                                        <div class="row slider_products_row" style="height: 800px">
                                            <div class="col-xxl-10 col-xl-10 col-lg-10 col-md-10 col-sm-12 col-12">
                                                <div class="owl-carousel owl-theme products_carousel_container"
                                                    dir="ltr">
                                                    @foreach($porduct_d as $k => $one_chunk)
                                                        @if(count($one_chunk) > 6)
                                                            @if ($k == 0)
                                                                @php($k = 0)
                                                            @else
                                                                @php($k = $k + 6)
                                                            @endif
                                                            <div class="row slider_products_item" style="height: 803px"dir="{{session('direction') == 'rtl' ? 'ltr' : 'rtl'}}">
                                                                <div class="col-xxl-5 col-xl-5 col-md-5 col-sm-11 col-12 m-auto   large-featured-product text-center" style="height:97%;">
                                                                    <div class=" row">
                                                                        @php($images = json_decode($one_chunk[$k]->images))
                                                                        <div class="real_image_container larg_container col-lg-12 col-md-12">
                                                                            @if(count(json_decode($one_chunk[$k]->colors)) > 0)
                                                                                @foreach(json_decode($one_chunk[$k]->color_image) as $k_img => $large_image)
                                                                                    <img src="{{asset('storage/product/' . $large_image->image_name)}}"
                                                                                        alt="product"
                                                                                        style="{{$k_img == 0 ? 'display:block;' : 'display:none;'}}height:100%"
                                                                                        class=" px-1 pt-2 {{$one_chunk[$k]->id}}_{{$k_img}}_img product_image">
                                                                                @endforeach
                                                                            @else
                                                                                <img src="{{asset('storage/product/thumbnail/' . $one_chunk[$k]->thumbnail)}}"
                                                                                    alt="product" style="height:100%" class="w-100 px-1 pt-2 product_image">
                                                                            @endif
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12">
                                                                            <a href="{{route('product', $one_chunk[$k]->slug)}}">
                                                                                <h4 class="s_27 primary_color bold"> {{$one_chunk[$k]->name}} </h4></a>
                                                                        </div>
                                                                        @php($overallRating = \App\CPU\ProductManager::get_overall_rating($one_chunk[$k]->reviews))
                                                                        <div class="col-lg-12 col-md-12">
                                                                            <div class="rate">
                                                                                @for($inc=0;$inc<5;$inc++)
                                                                                    @if($inc<$overallRating[0])
                                                                                        <i class="p-0 sr-star czi-star-filled active"></i>
                                                                                    @else
                                                                                        <i class="p-0 sr-star czi-star" style="color:#fea569 !important"></i>
                                                                                    @endif
                                                                                @endfor
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-12 col-md-12">
                                                                            <div dir="rtl">
                                                                                <span class="real_price s_27 bold ">
                                                                                    {{\App\CPU\Helpers::currency_converter($one_chunk[$k]->unit_price-(\App\CPU\Helpers::get_product_discount($one_chunk[$k],$one_chunk[$k]->unit_price)))}}
                                                                                </span>
                                                                                @if($one_chunk[$k]->discount > 0)
                                                                                    <span
                                                                                        class="pre_price primary_color s_27 bold num_fam">{{\App\CPU\Helpers::currency_converter($one_chunk[$k]->unit_price)}}</span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        {{-- {{dd(json_decode($one_chunk[$k]->colors))}}--}}
                                                                        <div class="mt13 col-lg-12 col-md-12">
                                                                            <ul class="list-inline checkbox-color mb-1 flex-start ml-2" style="padding-left: 0;min-height:37px;" dir="ltr">
                                                                                @if(count(json_decode($one_chunk[$k]->colors)) > 0)
                                                                                    @foreach(json_decode($one_chunk[$k]->colors) as $k_img => $large_image)
                                                                                        <li>
                                                                                            <input type="radio" id="{{$one_chunk[$k]->id}}_{{$k_img}}-color-{{$large_image}}" name="color_{{$one_chunk[$k]->id}}" value="#{{$large_image}}" checked="">
                                                                                            <label style="background: {{$large_image}};" data-target="{{$one_chunk[$k]->id}}_{{$k_img}}" for="{{$one_chunk[$k]->id}}_{{$k_img}}-color-{{$large_image}}" class="color-changer" data-toggle="tooltip" onclick="focus_preview_image_by_color('{{$large_image}}')"> <span class="outline"></span></label>
                                                                                        </li>
                                                                                    @endforeach
                                                                                @endif
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xxl-7 col-xl-7 col-md-7 col-sm-12 col-12 m-auto text-center">
                                                                    <div class="row">
                                                                        @php($one_chunk->shift())
                                                                        @foreach($one_chunk as $small_product)
                                                                            <div class="col-xxl-4 col-xl-4 col-md-4 col-sm-6 col-6 my-2">
                                                                                <div class="row">
                                                                                    <div class="col-xxl-11 col-xl-11 col-md-11 col-sm-11 col-11 product_container m-auto">
                                                                                        <div class=" row">
                                                                                            @php($images = json_decode($small_product->images))
                                                                                            <div class="real_image_container col-lg-12 col-md-12">
                                                                                                @if(count(json_decode($small_product->colors)) > 0)
                                                                                                    @foreach(json_decode($small_product->color_image) as $k_img => $large_image)
                                                                                                        <img src="{{asset('storage/product/' . $large_image->image_name)}}"
                                                                                                            alt="product"
                                                                                                            style="{{$k_img == 0 ? 'display:block;' : 'display:none;'}}height:100%"
                                                                                                            class="w-100 px-1 pt-2 {{$small_product->id}}_{{$k_img}}_img product_image">
                                                                                                    @endforeach
                                                                                                @else
                                                                                                    <img src="{{asset('storage/product/thumbnail/' . $small_product->thumbnail)}}"
                                                                                                        alt="product" style="height:100%" class="w-100 px-1 pt-2 product_image">
                                                                                                @endif
                                                                                            </div>
                                                                                            <div
                                                                                                class="col-lg-12 col-md-12  col-12">
                                                                                                <a href="{{route('product', $small_product->slug)}}">
                                                                                                    <h4 class="s_16 primary_color bold pt-3"> {{$small_product->name}} </h4></a>
                                                                                            </div>
                                                                                            @php($overallRating = \App\CPU\ProductManager::get_overall_rating($small_product->reviews))
                                                                                            <div class="col-lg-12 col-md-12">
                                                                                                <div class="rate">
                                                                                                    @for($inc=0;$inc<5;$inc++)
                                                                                                        @if($inc<$overallRating[0])
                                                                                                            <i class="p-0 sr-star czi-star-filled active"></i>
                                                                                                        @else
                                                                                                            <i class="p-0 sr-star czi-star" style="color:#fea569 !important"></i>
                                                                                                        @endif
                                                                                                    @endfor
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-lg-12 col-md-12">
                                                                                                <div dir="rtl">
                                                                                                    <span class="real_price s_16 bold ">
                                                                                                        {{\App\CPU\Helpers::currency_converter($small_product->unit_price-(\App\CPU\Helpers::get_product_discount($small_product,$small_product->unit_price)))}}
                                                                                                    </span>
                                                                                                    @if($small_product->discount > 0)
                                                                                                        <span
                                                                                                            class="pre_price primary_color s_16 bold num_fam">{{\App\CPU\Helpers::currency_converter($small_product->unit_price)}}</span>
                                                                                                    @endif
                                                                                                </div>
                                                                                            </div>
                                                                                            <div
                                                                                                class="mt-1 col-lg-12 col-md-12">
                                                                                                <ul class="list-inline checkbox-color mb-1 flex-start ml-2" style="padding-left: 0;" dir="ltr">
                                                                                                    @if(count(json_decode($small_product->colors)) > 0)
                                                                                                        @foreach(json_decode($small_product->colors) as $k_img => $small_color)
                                                                                                            <li>
                                                                                                                <input type="radio" id="{{$small_product->id}}_{{$k_img}}-color-{{$small_color}}" name="color_{{$small_product->id}}" value="#{{$small_color}}" checked="">
                                                                                                                <label style="background: {{$small_color}};" data-target="{{$small_product->id}}_{{$k_img}}" for="{{$small_product->id}}_{{$k_img}}-color-{{$small_color}}" class="color-changer" data-toggle="tooltip" onclick="focus_preview_image_by_color('{{$small_color}}')"> <span class="outline"></span></label>
                                                                                                            </li>
                                                                                                        @endforeach
                                                                                                    @endif
                                                                                                </ul>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="col-xxl-2 col-xl-2 col-md-2 col-sm-6 col-6 small-featured-product m-auto text-center">
                                                @if($main->banners->where('banner_type', 'Main Section Banner') != null && $main->banners->where('banner_type', 'Main Section Banner')->count() > 0)
                                                    <div class="row">
                                                        <div class="col-xxl-11 col-xl-11 col-md-11 col-sm-11 col-11 product_container_side m-auto p-0"
                                                            style="background-image: url({{asset('storage/banner/' . $main->banners->where('banner_type', 'Main Section Banner')->first()->photo)}});background-size: 100% 100%">
                                                            <a href="{{$main->banners->where('banner_type', 'Main Section Banner')->first()->url}}" class="">
                                                                <div class="featured-product-title p-3">
                                                                    <h4 class="bold mb-1">{{$main->banners->where('banner_type', 'Main Section Banner')->first()->title}}</h4>
                                                                    <span class="bold">{{$main->banners->where('banner_type', 'Main Section Banner')->first()->description}}</span>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @if($main->banners->where('banner_type', 'Footer Banner') != null && $main->banners->where('banner_type', 'Footer Banner')->count() > 0)
                                        <div class="col-xxl-12 col-xl-12 col-md-12 col-sm-12 col-12 mt-5  pt-3">
                                            <div class="owl-carousel owl-theme footer_cat_banners" dir="ltr">
                                                @foreach($main->banners->where('banner_type', 'Footer Banner') as $footer_cat_banner)
                                                    <div class="item m-auto large-featured-offer text-center">
                                                        <div class="row">
                                                            <div class="col-xxl-12 col-xl-12 col-md-12 col-sm-12 col-12 my-2">
                                                                <div class="large-offer">
                                                                    <img src="{{asset('storage/banner/'. $footer_cat_banner->photo)}}"
                                                                        class="w-100 offer-image" alt="offer">
                                                                    <div class="offer-title">
                                                                        <h4 class="bold">{{$footer_cat_banner->title}}</h4>
                                                                        <a href="{{$footer_cat_banner->url}}" class="btn btn-sm btn-custom bold">
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
                                </div>
                            </div>
                        </section>
                    @endif
                @endif
            @endif
        @endforeach
    @endif
    {{-- End Featured Sub Categories --}}




    {{-- Start Small Banner--}}
    <section class="small_banner_section mt-2" dir="rtl">
        <div class="container">
            <div class="row s_16">
                <div
                    class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12 text-right banner-single-item">
                    <div class="d-inline-block photo-item">
                        <img src="{{asset('assets/front-end/img/delivery-truck.png')}}"
                            alt="delivery-truck">
                    </div>
                    <div class="d-inline-block span_item">
                        <span
                            class="bold">{{\App\CPU\translate('Easy to Order .. Easy to deliver')}}</span>

                    </div>
                </div>

                <div
                    class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12 text-center banner-single-item">
                    <div class="d-inline-block photo-item">

                        <img src="{{asset('assets/front-end/img/handshake.png')}}" alt="handshake"
                            class="handshake">
                    </div>

                    <div class="d-inline-block span_item">

                        <span
                            class="bold">{{\App\CPU\translate('Partners with alot of Suppliers .. Be a Partner with us')}}</span>
                    </div>

                </div>
                <div
                    class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12 text-left banner-single-item">
                    <div class="d-inline-block photo-item">

                        <img src="{{asset('assets/front-end/img/earth-grid.png')}}" alt="earth-grid">
                    </div>


                    <div class="d-inline-block span_item">

                        <span
                            class="bold">{{\App\CPU\translate('We Deliver all over the world')}}</span>
                    </div>

                </div>
            </div>
        </div>
    </section>
    {{--End Small Banner--}}



    {{-- Start Custom Banner--}}
    @if(isset($footer_products_banners) && $footer_products_banners->count() > 0)
        <section class="custom-product-banner container mt-2 pt-2">
            <div class="row">
                <div class="col-xxl-12 col-xl-12 col-md-12 col-sm-12 col-12 mt-3  pt-3">
                    <div class="owl-carousel owl-theme main_products_banners" dir="ltr">
                        @foreach($footer_products_banners as $footer_products_banner)
                            <div class="item custom-product-banner_div position-relative">
                                <div style="width: 100%;height: 100%;position: absolute;z-index: 100;top: 0;right: 0;background: rgba(0,0,0,0.1);border-radius: 5px;"></div>
                                <img src="{{asset('storage/banner/'. $footer_products_banner->photo)}}" alt="" style="border-radius: 5px;max-height: 250px">
                                <div class="banner-details" dir="rtl">
                                    <h4 class="bold s_30"> {{$footer_products_banner->title}} </h4>
                                    <span class="bold"> {{$footer_products_banner->description}}</span>
                                    <div>
                                        <div class="price d-inline-block">
                                            <span class="num_fam primary_color pl-3 s_30">{{\App\CPU\Helpers::currency_converter($footer_products_banner->product->unit_price)}}</span>
                                        </div>
                                        <div class="redirect_btn  d-inline-block mt-2 mr-3">
                                            <a href="{{$footer_products_banner->url}}" class="btn btn-sm btn-custom bold">
                                                {{\App\CPU\translate('Shop Now')}}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif
    {{-- End Custom Banner--}}


    {{--   Start Brands Carousel --}}
    @if($brands && $brands->count() > 0)
        <section class="partners pt-2 mt-2">
            <div class="row text-center">
                <div class="col-xxl-10 col-xl-10 col-lg-10 col-md-10 col-sm-12 col-12 m-auto">
                    <div class="row partners_slider" dir="rtl">
                        <div class="owl-carousel owl-theme p-2" id="partners_slider" dir="ltr">
                            @foreach($brands as $brand)
                                <div class="m-auto pb-3">
                                    <a href="javascript:void(0);">
                                        <img style="margin:auto;height: 150px;width: 150px;padding: 5px;border: 2px dashed #f1406130;border-radius: 10px;" src="{{asset('storage/brand/'. $brand->image)}}" alt="" class="">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    {{-- End Brands Carousel --}}

    {{-- Start All Cats With Counts --}}
        <section class="cats_counts mt-2" style="max-width: 1450px; margin:auto;">
            <div class="row text-center list_of_counts p-4" style="background: #fff;">
                <div class="col-lg-12 col-md-12 col-12">
                    <h4 class="bold s_30 mb-3" style="color: #000"> {{\App\CPU\translate('All Products')}} </h4>
                </div>
                <div class="col-lg-12 col-md-12 col-12">
                    <div class="row">
                        <div class="col-xxl-11 col-xl-11 col-md-11 col-sm-12 col-12 m-auto pt-1">
                            <div class="owl-carousel owl-theme featured_cats1 mains" dir="ltr" data-items="10">
                                @if(isset($all_cats) && $all_cats->count() > 0)
                                    @foreach($all_cats as $cat)
                                        @php($porduct_d = App\Model\Product::active()->with(['reviews']))
                                        @php($all_products = $porduct_d->get())
                                        @php($id = $cat->id)
                                        @php($caro_products_ids = [])
                                        @foreach ($all_products as $all_product)
                                            @foreach (json_decode($all_product['category_ids'], true) as $product_cat)
                                                @if ($product_cat['id'] == $id)
                                                    @php(array_push($caro_products_ids, $all_product['id']))
                                                @endif
                                            @endforeach
                                        @endforeach
                                        @php($porducts_count = $porduct_d->whereIn('id', $caro_products_ids)->count())
                                                <div class="pricingTable yellow" style="padding: 8px 4px"><div class="pricingTable-header"><div class="price-value"><span class="duration" style="font-size: 10px;"> {{$cat->name}} </span> </div></div><ul class="pricing-content"><li> <div class="single_price_item position-relative"><h4 class="num_fam bold s_14">  {{$porducts_count }}</h4></div> </li></ul></div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- <div class="row mt-3 d-flex justify-content-center"><div class="col-md-2 col-6"><div class="pricingTable yellow" style="padding: 8px 4px"><div class="pricingTable-header"><div class="price-value"><span class="duration" style="font-size: 10px;"> {{\App\CPU\translate('top sell')}} </span> </div></div><ul class="pricing-content"><li> <div class="single_price_item position-relative"><h4 class="num_fam bold s_14"> {{$bestSellProduct->count()}}</h4></div> </li></ul></div></div></div> --}}
                </div>
            </div>
        </section>
        {{-- End All Cats With Counts --}}


        {{-- Strar beauty_vendors --}}
        {{-- <section class="beauty_vendors container py-4 mt-5">
            <div class="text-center"><h4 class="bold s_24">{{\App\CPU\translate('Beauty Center Stores')}}</h4></div><div class="{{session('direction') == 'rtl' ? 'text-right' : 'text-left'}}  vendors_list"dir="{{session('direction')}}"><a href="javascript:void(0);" class="bold primary_color s_18">{{\App\CPU\translate('Products From USA')}}</a><span class="bold px-1">-</span><a href="javascript:void(0);" class="bold primary_color s_18">{{\App\CPU\translate('Products From Japan')}}</a><span class="bold px-1">-</span><a href="javascript:void(0);" class="bold primary_color s_18">{{\App\CPU\translate('Products From UK')}}</a><span class="bold px-1">-</span><a href="javascript:void(0);" class="bold primary_color s_18">{{\App\CPU\translate('Products From Hong Kong')}}</a><span class="bold px-1">-</span><a href="javascript:void(0);" class="bold primary_color s_18">{{\App\CPU\translate('Products From korea')}}</a><span class="bold px-1">-</span><a href="javascript:void(0);" class="bold primary_color s_18">{{\App\CPU\translate('Products From China')}}</a></div>
        </section> --}}
        {{-- End beauty_vendors --}}

        <section class="safe_support mt-2 pt-3" dir="{{session('direction')}}" style="background: #f1406110;padding:20px;">
            <div class="">
                <div class="row mx-0 text-center d-flex justify-content-center">
                    <div class=" col-xs-6 col-6 m-auto support_item">
                        <div class="d-inline-block item_img">
                            <img src="{{asset('assets/front-end/img/icon-4.png')}}" alt="">
                        </div>
                        <div class="d-inline-block item_details" style="text-align: center;">
                        <span class="item_title s_18 bold"> <span class="num_fam">100%</span> {{\App\CPU\translate('Secured Payment')}}
                        </span> <br>
                            <span class="item_desc s_18 bold">
                            {{\App\CPU\translate('Payment Methods in Store are Secured & Trusted')}}
                        </span>
                        </div>
                    </div>
                    {{-- <div class=" col-xs-6 col-6 m-auto support_item">
                        <div class="d-inline-block item_img">
                            <img src="{{asset('assets/front-end/img/icon-5.png')}}" alt="">
                        </div>
                        <div class="d-inline-block item_details" style="text-align: center;">
                            <span class="item_title s_18 bold"> {{\App\CPU\translate('Free Phone Number')}} </span>
                                <br>
                                <span class="item_desc s_18 bold">
                                <span class="num_fam" dir="ltr">+966 53 085 2675</span>
                            </span>
                        </div>
                    </div> --}}
                    <div class=" col-xs-6 col-6 m-auto support_item">
                        <div class="d-inline-block item_img"> <img src="{{asset('assets/front-end/img/icon-3.png')}}" alt=""> </div>
                        <div class="d-inline-block item_details" style="text-align: center;">
                            <span class="item_title s_18 bold"> {{\App\CPU\translate('Order Return')}} </span> <br>
                            <span class="item_desc s_18 bold"> {{\App\CPU\translate('Easy Return Policy in 24 Hours')}} </span>
                        </div>
                    </div>
                    {{-- <div class=" col-xs-6 col-6 m-auto support_item">
                        <div class="d-inline-block item_img">
                            <img src="{{asset('assets/front-end/img/icon-1.png')}}" alt="">
                        </div>
                        <div class="d-inline-block item_details" style="text-align: center;">
                            <span class="item_title s_18 bold"> {{\App\CPU\translate('Free Shipping')}} </span> <br>
                            <span class="item_desc s_18 bold">{{\App\CPU\translate('Free Shipping For All Orders')}} </span>
                        </div>
                    </div> --}}
                </div>
            </div>
        </section>
    </div>

@endsection

@push('script')
    {{-- Owl Carousel --}}

    <script>
        let color_changer = $('.color-changer');
        color_changer.on('click', function () {
            let id = $(this).attr('data-target');
            // alert(id);
            let main_product = $(this).parent().parent().parent().parent().parent();
            let target = main_product.find('.' + id + '_img');
            let all_product_images = main_product.find('.product_image');
            all_product_images.css('display', 'none');
            target.css('display', 'inline-block');

        });

        $('#flash-deal-slider').owlCarousel({
            loop: false,
            autoplay: false,
            margin: 20,
            nav: true,
            navText: ["<i class='czi-arrow-left'></i>", "<i class='czi-arrow-right'></i>"],
            dots: false,
            autoplayHoverPause: true,
            '{{session('direction')}}': false,
            // center: true,
            responsive: {
                //X-Small
                0: {
                    items: 1
                },
                360: {
                    items: 1
                },
                375: {
                    items: 1
                },
                540: {
                    items: 2
                },
                //Small
                576: {
                    items: 2
                },
                //Medium
                768: {
                    items: 2
                },
                //Large
                992: {
                    items: 2
                },
                //Extra large
                1200: {
                    items: 2
                },
                //Extra extra large
                1400: {
                    items: 3
                }
            }
        })

        $('#web-feature-deal-slider').owlCarousel({
            loop: false,
            autoplay: true,
            margin: 20,
            nav: false,
            //navText: ["<i class='czi-arrow-left'></i>", "<i class='czi-arrow-right'></i>"],
            dots: false,
            autoplayHoverPause: true,
            '{{session('direction')}}': true,
            // center: true,
            responsive: {
                //X-Small
                0: {
                    items: 1
                },
                360: {
                    items: 1
                },
                375: {
                    items: 1
                },
                540: {
                    items: 2
                },
                //Small
                576: {
                    items: 2
                },
                //Medium
                768: {
                    items: 2
                },
                //Large
                992: {
                    items: 2
                },
                //Extra large
                1200: {
                    items: 3
                },
                //Extra extra large
                1400: {
                    items: 3
                }
            }
        })

        $('#new-arrivals-product').owlCarousel({
            loop: true,
            autoplay: false,
            margin: 20,
            nav: true,
            navText: ["<i class='czi-arrow-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}'></i>", "<i class='czi-arrow-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}'></i>"],
            dots: false,
            autoplayHoverPause: true,
            '{{session('direction')}}': true,
            // center: true,
            responsive: {
                //X-Small
                0: {
                    items: 1
                },
                360: {
                    items: 1
                },
                375: {
                    items: 1
                },
                540: {
                    items: 2
                },
                //Small
                576: {
                    items: 2
                },
                //Medium
                768: {
                    items: 2
                },
                //Large
                992: {
                    items: 2
                },
                //Extra large
                1200: {
                    items: 4
                },
                //Extra extra large
                1400: {
                    items: 4
                }
            }
        })
    </script>
    <script>
        $('#featured_products_list').owlCarousel({
            loop: true,
            autoplay: false,
            margin: 20,
            nav: false,
            navText: ["<i class='czi-arrow-left'></i>", "<i class='czi-arrow-right'></i>"],
            dots: false,
            autoplayHoverPause: true,
            '{{session('direction')}}': false,
            // center: true,
            responsive: {
                //X-Small
                0: {
                    items: 1
                },
                360: {
                    items: 1
                },
                375: {
                    items: 1
                },
                540: {
                    items: 2
                },
                //Small
                576: {
                    items: 2
                },
                //Medium
                768: {
                    items: 3
                },
                //Large
                992: {
                    items: 4
                },
                //Extra large
                1200: {
                    items: 5
                },
                //Extra extra large
                1400: {
                    items: 5
                }
            }
        });
    </script>
    <script>
        $('#brands-slider').owlCarousel({
            loop: false,
            autoplay: false,
            margin: 10,
            nav: false,
            '{{session('direction')}}': true,
            dots: true,
            autoplayHoverPause: true,
            // center: true,
            responsive: {
                //X-Small
                0: {
                    items: 4
                },
                360: {
                    items: 5
                },
                375: {
                    items: 5
                },
                540: {
                    items: 5
                },
                //Small
                576: {
                    items: 6
                },
                //Medium
                768: {
                    items: 7
                },
                //Large
                992: {
                    items: 9
                },
                //Extra large
                1200: {
                    items: 11
                },
                //Extra extra large
                1400: {
                    items: 12
                }
            }
        })
    </script>

    <script>
        $('#category-slider, #top-seller-slider').owlCarousel({
            loop: false,
            autoplay: false,
            margin: 20,
            nav: false,
            // navText: ["<i class='czi-arrow-left'></i>","<i class='czi-arrow-right'></i>"],
            dots: true,
            autoplayHoverPause: true,
            '{{session('direction')}}': true,
            // center: true,
            responsive: {
                //X-Small
                0: {
                    items: 2
                },
                360: {
                    items: 3
                },
                375: {
                    items: 3
                },
                540: {
                    items: 4
                },
                //Small
                576: {
                    items: 5
                },
                //Medium
                768: {
                    items: 6
                },
                //Large
                992: {
                    items: 8
                },
                //Extra large
                1200: {
                    items: 10
                },
                //Extra extra large
                1400: {
                    items: 11
                }
            }
        })

        $('#clients_reviews').owlCarousel({
            loop: false,
            autoplay: false,
            margin: 10,
            nav: false,
            '{{session('direction')}}': true,
            dots: true,
            autoplayHoverPause: true,
            // center: true,
            navText: ["<i class='fa fa-arrow-left' aria-hidden='true'></i>", "<i class='fa fa-arrow-right' aria-hidden='true'></i>"],

            responsive: {
                //X-Small
                0: {
                    items: 1
                },
                360: {
                    items: 1
                },
                375: {
                    items: 1
                },
                540: {
                    items: 1
                },
                //Small
                576: {
                    items: 1
                },
                //Medium
                768: {
                    items: 2
                },
                //Large
                992: {
                    items: 2
                },
                //Extra large
                1200: {
                    items: 2
                },
                //Extra extra large
                1400: {
                    items: 2
                }
            }
        });
        $('#partners_slider').owlCarousel({
            loop: false,
            autoplay: false,
            margin: 10,
            nav: false,
            dots: false,
            autoplayHoverPause: true,
            // center: true,
            // navText: ["<i class='fa fa-arrow-left' aria-hidden='true'></i>", "<i class='fa fa-arrow-right' aria-hidden='true'></i>"],

            responsive: {
                //X-Small
                0: {
                    items: 2
                },
                360: {
                    items: 2
                },
                375: {
                    items: 2
                },
                540: {
                    items: 2
                },
                //Small
                576: {
                    items: 2
                },
                //Medium
                768: {
                    items: 5
                },
                //Large
                992: {
                    items: 8
                },
                //Extra large
                1200: {
                    items: 8
                },
                //Extra extra large
                1400: {
                    items: 8
                }
            }
        });

        $('#banner_slider').owlCarousel({
            loop: false,
            autoplay: false,
            margin: 10,
            nav: true,
            '{{session('direction')}}': true,
            dots: false,
            autoplayHoverPause: true,
            // center: true,
            navText: ["<img src='{{asset('assets/front-end/img/chevron-left.png')}}'>", "<img src='{{asset('assets/front-end/img/chevron-right.png')}}'>"],

            responsive: {
                //X-Small
                0: {
                    items: 1
                },
                360: {
                    items: 1
                },
                375: {
                    items: 1
                },
                540: {
                    items: 1
                },
                //Small
                576: {
                    items: 1
                },
                //Medium
                768: {
                    items: 1
                },
                //Large
                992: {
                    items: 1
                },
                //Extra large
                1200: {
                    items: 1
                },
                //Extra extra large
                1400: {
                    items: 1
                }
            }
        })

        $('.products_carousel_container').owlCarousel({
            loop: true,
            margin: 10,
            autoplay: false,
            autoplayTimeout: 3000,
            autoplayHoverPause: true,
            nav: false,
            dots: false,
            // center: true,
            navText: ["<img src='{{asset('assets/front-end/img/pink-chevron-left.png')}}'>", "<img src='{{asset('assets/front-end/img/pink-chevron-right.png')}}'>"],

            responsive: {
                //X-Small
                0: {
                    items: 1
                },
                360: {
                    items: 1
                },
                375: {
                    items: 1
                },
                540: {
                    items: 1
                },
                //Small
                576: {
                    items: 1
                },
                //Medium
                768: {
                    items: 1
                },
                //Large
                992: {
                    items: 1
                },
                //Extra large
                1200: {
                    items: 1
                },
                //Extra extra large
                1400: {
                    items: 1
                }
            }
        });
        $('.featured_cats').owlCarousel({
            loop: true,
            autoplay: true,
            autoplayTimeout: 2000,
            autoplayHoverPause: true,
            margin: 10,
            dots: true,
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
                    items: 5,
                    nav: false,

                },
                //Extra extra large
                1400: {
                    items: 5,
                    nav: false,

                }
            }
        });
        $('.featured_cats1').owlCarousel({
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
                    items: 10,
                    nav: false,

                },
                //Extra large
                1200: {
                    items: 10,
                    nav: false,

                },
                //Extra extra large
                1400: {
                    items: 10,
                    nav: false,

                }
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

        $('.main_products_banners').owlCarousel({
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
                    items: 1,
                    nav: false,

                },
                //Small
                576: {
                    items: 1,
                    nav: false,

                },
                //Medium
                768: {
                    items: 1,
                    nav: false,

                },
                //Large
                992: {
                    items: 1,
                    nav: false,

                },
                //Extra large
                1200: {
                    items: 1,
                    nav: false,

                },
                //Extra extra large
                1400: {
                    items: 1,
                    nav: false,

                }
            }
        });


        // let real_image_container = $('.real_image_container');
        // real_image_container.hover(function () {
        //     $(this).css('display', 'none');
        //     // alert('test');
        //     $(this).parent().find('.product_images_slider').css('display', 'block');
        // });
        // $(".product_images_slider").hover(function () {
        //     $(this).carousel('cycle');
        // }, function () {
        //     $(this).carousel('pause');
        //     $(this).css('display', 'none');
        //     $(this).parent().parent().find('.real_image_container').css('display', 'block')
        // });
        $(document).ready(function () {
            let featured_cats_item = $('.featured_cats .featured_sub_cat img');

            let maxHeight = Math.max.apply(null, featured_cats_item.map(function () {
                return $(this).height();
            }).get());

            // alert(maxHeight);
            // featured_cats_item.height(maxHeight);

            if ($(window).width() > 720) {
                let product_container = $('.product_container');

                let productMaxHeight = Math.max.apply(null, product_container.map(function () {
                    // return $(this).height();
                    return 380;
                }).get());
                product_container.height(productMaxHeight);

            } else {
                let product_container = $('.product_container');

                let productMaxHeight = Math.max.apply(null, product_container.map(function () {
                    return $(this).height();
                }).get());
                product_container.height(productMaxHeight);
            }


            // alert(maxHeight);


            let single_price_item = $('.single_price_item');
            single_price_item.on('click', function () {
                let url = $(this).attr('data-value');
                window.location = url;
            });


            let sort_item = $('.sort-item');
            sort_item.on('click', function () {
                let url = $(this).attr('data-value');
                window.location = url;
            });
        });
    </script>
@endpush

