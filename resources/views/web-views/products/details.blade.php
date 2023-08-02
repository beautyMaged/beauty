@extends('layouts.front-end.app')

@section('title',$product['name'])

@push('css_or_js')
    <meta name="description" content="{{$product->slug}}">
    <meta name="keywords" content="@foreach(explode(' ',$product['name']) as $keyword) {{$keyword.' , '}} @endforeach">
    @if($product->added_by=='seller')
        <meta name="author" content="{{ $product->seller->shop?$product->seller->shop->name:$product->seller->f_name}}">
    @elseif($product->added_by=='admin')
        <meta name="author" content="{{$web_config['name']->value}}">
    @endif
    <!-- Viewport-->

    @if($product['meta_image']!=null)
        <meta property="og:image" content="{{asset("storage/product/meta")}}/{{$product->meta_image}}"/>
        <meta property="twitter:card"
              content="{{asset("storage/product/meta")}}/{{$product->meta_image}}"/>
    @else
        <meta property="og:image" content="{{asset("storage/product/thumbnail")}}/{{$product->thumbnail}}"/>
        <meta property="twitter:card"
              content="{{asset("storage/product/thumbnail/")}}/{{$product->thumbnail}}"/>
    @endif

    @if($product['meta_title']!=null)
        <meta property="og:title" content="{{$product->meta_title}}"/>
        <meta property="twitter:title" content="{{$product->meta_title}}"/>
    @else
        <meta property="og:title" content="{{$product->name}}"/>
        <meta property="twitter:title" content="{{$product->name}}"/>
    @endif
    <meta property="og:url" content="{{route('product',[$product->slug])}}">

    @if($product['meta_description']!=null)
        <meta property="twitter:description" content="{!! $product['meta_description'] !!}">
        <meta property="og:description" content="{!! $product['meta_description'] !!}">
    @else
        <meta property="og:description"
              content="@foreach(explode(' ',$product['name']) as $keyword) {{$keyword.' , '}} @endforeach">
        <meta property="twitter:description"
              content="@foreach(explode(' ',$product['name']) as $keyword) {{$keyword.' , '}} @endforeach">
    @endif
    <meta property="twitter:url" content="{{route('product',[$product->slug])}}">

    <link rel="stylesheet" href="{{asset('assets/front-end/css/product-details.css')}}"/>
    {{--    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>--}}

    <style>
        .btn-number:hover {
            color: {{$web_config['secondary_color']}};

        }

        .for-total-price {
            margin- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: -30%;
        }

        .feature_header span {
            padding- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 15px;
        }

        .flash-deals-background-image {
            background: {{$web_config['primary_color']}}10;
        }

        @media (max-width: 768px) {
            .for-total-price {
                padding- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 30%;
            }

            .product-quantity {
                padding- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 4%;
            }

            .for-margin-bnt-mobile {
                margin- {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 7px;
            }

        }

        @media (max-width: 375px) {
            .for-margin-bnt-mobile {
                margin- {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 3px;
            }

            .for-discount {
                margin- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 10% !important;
            }

            .for-dicount-div {
                margin-top: -5%;
                margin- {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: -7%;
            }

            .product-quantity {
                margin- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 4%;
            }

        }

        @media (max-width: 500px) {
            .for-dicount-div {
                margin- {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: -5%;
            }

            .for-total-price {
                margin- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: -20%;
            }

            .view-btn-div {
                float: {{Session::get('direction') === "rtl" ? 'left' : 'right'}};
            }

            .for-discount {
                margin- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 7%;
            }

            .for-mobile-capacity {
                margin- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 7%;
            }
        }
    </style>
    <style>
        thead {
            background: {{$web_config['primary_color']}}            !important;
        }

        .simplebar-offset {
            left: 26px !important;
        }

        .product_details {
            max-width: 1060px;
            margin: auto
        }

        .product_review {
            background: #F7F8FA
        }
    </style>
@endpush

@section('content')

    <div class="row mb-3 __inline-35"
         style="background:{{$web_config['primary_color']}}10;">
        <div class="container" dir="{{session('direction')}}">
            <div class="row">
                <div class="col-xxl-8 col-xl-8 col-lg-8 col-md-8 col-sm-8 col-9 serial_route py-4">
                    <a href="{{route('home')}}" class="bold">{{\App\CPU\translate('Home')}}</a>
                    <div class="d-inline-block position-relative" style="width: 25px"><i
                            style="position: absolute;top: -15px;right: 3px;"
                            class="fa-solid fa-chevron-{{session('direction') == 'rtl' ? 'left' : 'right'}} mt-1  px-1 "></i>
                    </div>
                    @php
                        if (count(json_decode($product->category_ids)) == 3) {
                            $brand = \App\Model\Category::find(json_decode($product->category_ids)[2]->id);
                        } else if (count(json_decode($product->category_ids)) == 2) {
                            $brand = \App\Model\Category::find(json_decode($product->category_ids)[1]->id);
                        } else {
                            $brand = \App\Model\Category::find(json_decode($product->category_ids)[0]->id);
                        }
                    @endphp
                    <a href="{{route('home')}}/products?id={{$brand->id}}&data_from=category&page=1"
                       class="bold">{{$brand->name}}</a>
                    <div class="d-inline-block position-relative" style="width: 25px"><i
                            style="position: absolute;top: -15px;right: 3px;"
                            class="fa-solid fa-chevron-left mt-1  px-1 "></i></div>
                    <span class="bold">{{$product->name}}</span>
                </div>

                <div
                    class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-4 col-3 py-4 text-{{session('direction') == 'rtl' ? 'left' : 'right'}} back">
                    <a href="{{ url()->previous() }}" class="bold back">{{\App\CPU\translate('Back')}}</a>
                    <div class="d-inline-block position-relative" style="width: 25px"><i
                            style="position: absolute;top: -15px;right: 3px;"
                            class="fa-solid fa-chevron-{{session('direction') == 'rtl' ? 'left' : 'right'}} mt-1  px-1 "></i>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <?php
    $overallRating = \App\CPU\ProductManager::get_overall_rating($product->reviews);
    $rating = \App\CPU\ProductManager::get_rating($product->reviews);
    $decimal_point_settings = \App\CPU\Helpers::get_business_settings('decimal_point_settings');
    ?>
    <div class="__inline-23" dir="{{session('direction')}}">
        <!-- Page Content-->
        <div class=" mt-4 rtl" style="max-width: 86%; margin: auto; text-align: right;">
            <!-- General info tab-->
            <div class="row {{Session::get('direction') === "rtl" ? '__dir-rtl' : ''}}" dir="{{session('direction')}}">
                <!-- Product gallery-->
                <div class="col-lg-12 col-12">
                    <div class="row">
                        <div class="col-lg-7 col-md-6 col-12">
                            <div
                                class="cz-product-gallery row {{session('direction') == 'rtl' ? 'text-right' : 'text-left'}}"
                                dir="{{session('direction') == 'rtl' ? 'ltr' : 'rtl'}}">
                                <div class="cz-preview col-lg-9 d-inline-block" dir="ltr">
                                    @if($product->images!=null && json_decode($product->images)>0)
                                        @if(json_decode($product->colors) && $product->color_image)
                                            @foreach (json_decode($product->color_image) as $key => $photo)
                                                @if($photo->color != null)
                                                    <div
                                                        class="cz-preview-item d-flex align-items-center justify-content-center {{$key==0?'active':''}}"
                                                        id="image{{$photo->color}}">
                                                        <img class="cz-image-zoom img-responsive w-100 "
                                                             onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                                             src="{{asset("storage/product/$photo->image_name")}}"
                                                             data-zoom="{{asset("storage/product/$photo->image_name")}}"
                                                             alt="Product image" width="">
                                                        <div class="cz-image-zoom-pane"></div>
                                                    </div>
                                                @else
                                                    <div
                                                        class="cz-preview-item d-flex align-items-center justify-content-center {{$key==0?'active':''}}"
                                                        id="image{{$key}}">
                                                        <img class="cz-image-zoom img-responsive w-100 "
                                                             onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                                             src="{{asset("storage/product/$photo->image_name")}}"
                                                             data-zoom="{{asset("storage/product/$photo->image_name")}}"
                                                             alt="Product image" width="">
                                                        <div class="cz-image-zoom-pane"></div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @else
                                            @foreach (json_decode($product->images) as $key => $photo)
                                                <div
                                                    class="cz-preview-item d-flex align-items-center justify-content-center {{$key==0?'active':''}}"
                                                    id="image{{$key}}">
                                                    <img class="cz-image-zoom img-responsive w-100 "
                                                         onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                                         src="{{asset("storage/product/$photo")}}"
                                                         data-zoom="{{asset("storage/product/$photo")}}"
                                                         alt="Product image" width="">
                                                    <div class="cz-image-zoom-pane"></div>
                                                </div>
                                            @endforeach
                                        @endif
                                    @endif
                                </div>
                                <div class="cz col-lg-2 d-inline-block">
                                    <div class="table-responsive __max-h-515px" data-simplebar
                                         style="max-height:450px;">
                                        <div class="thumb_list">
                                            @if($product->images!=null && json_decode($product->images)>0)
                                                @if(json_decode($product->colors) && $product->color_image)
                                                    @foreach (json_decode($product->color_image) as $key => $photo)
                                                        @if($photo->color != null)
                                                            <div class="cz-thumblist">
                                                                <a class="cz-thumblist-item  {{$key==0?'active':''}} d-flex align-items-center justify-content-center"
                                                                   id="preview-img{{$photo->color}}"
                                                                   href="#image{{$photo->color}}">
                                                                    <img
                                                                        onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                                                        src="{{asset("storage/product/$photo->image_name")}}"
                                                                        alt="Product thumb" style="border-radius: 3px">
                                                                </a>
                                                            </div>
                                                        @else
                                                            <div class="cz-thumblist">
                                                                <a class="cz-thumblist-item  {{$key==0?'active':''}} d-flex align-items-center justify-content-center"
                                                                   id="preview-img{{$key}}" href="#image{{$key}}">
                                                                    <img
                                                                        onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                                                        src="{{asset("storage/product/$photo->image_name")}}"
                                                                        alt="Product thumb">
                                                                </a>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    @foreach (json_decode($product->images) as $key => $photo)
                                                        <div class="cz-thumblist">
                                                            <a class="cz-thumblist-item  {{$key==0?'active':''}} d-flex align-items-center justify-content-center"
                                                               id="preview-img{{$key}}" href="#image{{$key}}">
                                                                <img
                                                                    onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                                                    src="{{asset("storage/product/$photo")}}"
                                                                    alt="Product thumb">
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Product details-->
                        <div class="col-lg-5 col-md-6 col-12 mt-md-0 mt-sm-3"
                             style="direction: {{ Session::get('direction') }}">
                            <div class="details __h-100 text-{{session('direction') == 'rtl' ? 'right' : 'left'}} pt-4">
                                <span class="mb-2 __inline-24 s_30">{{$product->name}}</span>
                                <div
                                    class="d-flex flex-wrap mb-2 pro text-{{session('direction') == 'rtl' ? 'right' : 'left'}} mt-1"
                                    dir="{{session('direction')}}">

                                </div>
                                <div class="row tex-{{session('direction') == 'rtl' ? 'right' : 'left'}} mt-3"
                                     dir="{{session('direction') }}">
                                    <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-sm-4 col-3">
                                        <span class="bold s_19">{{\App\CPU\translate('Price')}} :</span>
                                    </div>
                                    <div class="col-xxl-9 col-xl-9 col-lg-9 col-md-9 col-sm-8 col-9"
                                         dir="{{session('direction') == 'rtl' ? 'ltr' : 'rtl'}}">
                                        @if($product->discount > 0)
                                            <strike style="color: #E96A6A;" id="deleted_price"
                                                    class="{{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-3'}}"
                                                    dir="rtl">
                                                {{\App\CPU\Helpers::currency_converter($product->purchase_price)}}
                                            </strike>
                                        @endif
                                        <span class="bold num_fam s_19" style="color: #ED165F"
                                              dir="{{session('direction')}}">
                                            @if($product->tax_model == 'include')
                                                {{\App\CPU\Helpers::get_price_range($product) }}
                                            @else
                                                @if($product->tax_type == 'percent')

                                                    {{\App\CPU\Helpers::get_price_range($product) }}
                                                @else

                                                @endif
                                            @endif


                                        </span>
                                    </div>
                                </div>
                                <div class="row tex-{{session('direction') == 'rtl' ? 'right' : 'left'}} mt-3"
                                     dir="{{session('direction') }}">
                                    <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-sm-4 col-3">
                                        <span class="bold s_19">{{\App\CPU\translate('country')}} :</span>
                                    </div>

                                    <div class="col-xxl-9 col-xl-9 col-lg-9 col-md-9 col-sm-8 col-9"
                                         dir="{{session('direction') == 'rtl' ? 'rtl' : 'ltr'}}">
                                        <span class="s_19 bold"> {{ \App\CPU\translate('Imported From China') }} </span>
                                        <img src="{{asset('assets/front-end/img/china.png')}}" alt="" style="width: 30px;border-radius: 2px">

                                    </div>
                                </div>
                                {{--                                Colors--}}
                                {{--                                @if($product->discount > 0)--}}
                                {{--                                    <div class="row tex-right mt-3" dir="rtl">--}}
                                {{--                                        <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-sm-4 col-3">--}}
                                {{--                                            <span class="bold s_19">{{\App\CPU\translate('discount')}} :</span>--}}
                                {{--                                        </div>--}}
                                {{--                                        <div class="col-xxl-9 col-xl-9 col-lg-9 col-md-9 col-sm-8 col-9" dir="ltr">--}}

                                {{--                                            <div><strong id="set-discount-amount" class="mx-2" dir="rtl"></strong></div>--}}
                                {{--                                        </div>--}}
                                {{--                                    </div>--}}
                                {{--                                @endif--}}
                                <form id="add-to-cart-form" class="mb-2">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $product->id }}">
                                    <div
                                        class="position-relative {{Session::get('direction') === "rtl" ? 'ml-n4' : 'mr-n4'}} mb-2">
                                        @if (count(json_decode($product->colors)) > 0)

                                            <div
                                                class="row tex-{{session('direction') == 'rtl' ? 'right' : 'left'}} py-1 mt-3"
                                                dir="{{session('direction')}}">

                                                <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-sm-4 col-3">
                                                    <span class="bold s_19">{{\App\CPU\translate('color')}} :</span>
                                                </div>
                                                <div class="col-xxl-9 col-xl-9 col-lg-9 col-md-9 col-sm-8 col-9">
                                                    <ul class="list-inline checkbox-color mb-1 flex-start ml-2"
                                                        style="padding-left: 0;"
                                                        dir="{{session('direction') == 'rtl' ? 'ltr' : 'rtl'}}">

                                                        @foreach (json_decode($product->colors) as $key => $color)
                                                            <li>
                                                                <input type="radio"
                                                                       id="{{ $product->id }}-color-{{ str_replace('#','',$color) }}"
                                                                       name="color" value="{{ $color }}"
                                                                       @if($key == 0) checked @endif>
                                                                <label style="background: {{ $color }};"
                                                                       class="color_btn"
                                                                       for="{{ $product->id }}-color-{{ str_replace('#','',$color) }}"
                                                                       data-toggle="tooltip"
                                                                       onclick="focus_preview_image_by_color('{{ str_replace('#','',$color) }}')">
                                                                    {{--                                                                <span class="outline"></span>--}}
                                                                </label>
                                                            </li>
                                                        @endforeach


                                                    </ul>

                                                </div>

                                            </div>
                                        @endif
                                    </div>
                                    @php
                                        $qty = 0;
                                        if(!empty($product->variation)){
                                        foreach (json_decode($product->variation) as $key => $variation) {
                                                $qty += $variation->qty;
                                            }
                                        }
                                    @endphp
                                    @foreach (json_decode($product->choice_options) as $key => $choice)
                                        <div class="row tex-{{session('direction') == 'rtl' ? 'right' : 'left'}} mt-1"
                                             dir="{{session('direction')}}">
                                            <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-sm-4 col-3">
                                                <span class="bold s_19">{{ $choice->title }} :</span>
                                            </div>

                                            <div>
                                                <ul class="list-inline checkbox-alphanumeric checkbox-alphanumeric--style-1 mb-2 mx-1 flex-start row"
                                                    style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 0;">
                                                    @foreach ($choice->options as $key => $option)
                                                        <div class="d-inline-block">
                                                            <li class="for-mobile-capacity">
                                                                <input type="radio"
                                                                       id="{{ $choice->name }}-{{ $option }}"
                                                                       name="{{ $choice->name }}" value="{{ $option }}"
                                                                       @if($key == 0) checked @endif >
                                                                <label class="__text-12px option_btn"
                                                                       for="{{ $choice->name }}-{{ $option }}">{{ $option }}</label>
                                                            </li>
                                                        </div>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endforeach

                                <!-- Quantity + Add to cart -->
                                    <div class="row tex-{{session('direction') == 'rtl' ? 'right' : 'left'}} mt-3"
                                         dir="{{session('direction')}}">
                                        <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-sm-4 col-3">
                                            <span class="bold s_19">{{\App\CPU\translate('Quantity')}} :</span>
                                        </div>
                                        <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-8 col-4" dir="rtl">
                                            <div
                                                class="num_fam "
                                                style="color: #ED165F;   width: 140px;text-align: center; background: #fff;   border: 1px solid #ED165F;   border-radius: 5px;">
                                                    <span class="input-group-btn d-inline-block"
                                                          style="border-left: 1px solid #ED165F;">
                                                        <button class="btn btn-number " type="button"
                                                                product-type="{{ $product->product_type }}"
                                                                data-type="plus"
                                                                data-field="quantity"
                                                                style="color: #ED165F;     padding: 0 10px;">
                                                        +
                                                        </button>
                                                    </span>
                                                <input type="text" name="quantity"
                                                       style="border: none;    color: #ED165F;    background: inherit;"
                                                       class="d-inline-block form-control input-number text-center cart-qty-field __inline-29"
                                                       placeholder="1" value="{{ $product->minimum_order_qty ?? 1 }}"
                                                       product-type="{{ $product->product_type }}"
                                                       min="{{ $product->minimum_order_qty ?? 1 }}" max="100">
                                                <span class="input-group-btn d-inline-block"
                                                      style="border-right: 1px solid #ED165F;">
                                                        <button class="btn btn-number " type="button"
                                                                data-type="minus" data-field="quantity"
                                                                disabled="disabled"
                                                                style="color: #ED165F;    padding: 0 10px;">
                                                            -
                                                        </button>
                                                    </span>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="row tex-{{session('direction') == 'rtl' ? 'right' : 'left'}} mt-3"
                                         dir="{{session('direction')}}">
                                        <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-sm-4 col-3">
                                            <span class="bold s_19">{{\App\CPU\translate('total')}} :</span>
                                        </div>
                                        <div class="col-xxl-9 col-xl-9 col-lg-9 col-md-9 col-sm-8 col-9">
                                            <div id="chosen_price_div"
                                                 class="d-inline-block">
                                                <div class="product-description-label"><strong
                                                        id="chosen_price"></strong></div>

                                            </div>


                                        </div>

                                    </div>
                                    {{--                                    <div class="row tex-right mt-3" dir="rtl">--}}
                                    {{--                                        <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-sm-4 col-3">--}}
                                    {{--                                            <span class="bold s_19">مكان الشحن :</span>--}}
                                    {{--                                        </div>--}}
                                    {{--                                        <div class="col-xxl-9 col-xl-9 col-lg-9 col-md-9 col-sm-8 col-9">--}}
                                    {{--                                            <div class="row">--}}
                                    {{--                                                <div class="col-lg-4 col-md-4 col-12">--}}
                                    {{--                                                    <select name="area" id="area" style="width: 100%">--}}
                                    {{--                                                        <option value="">اختر المنطقة</option>--}}
                                    {{--                                                        <option value="reyad" selected>الرياض</option>--}}
                                    {{--                                                        <option value="gadda">جدة</option>--}}
                                    {{--                                                        <option value="dammam">الدمام</option>--}}
                                    {{--                                                        <option value="mecca">مكة</option>--}}
                                    {{--                                                        <option value="gezan">جيزان</option>--}}
                                    {{--                                                        <option value="taif">الطائف</option>--}}
                                    {{--                                                        <option value="najran">نجران</option>--}}
                                    {{--                                                        <option value="tabuk">تابوك</option>--}}
                                    {{--                                                    </select>--}}
                                    {{--                                                </div>--}}
                                    {{--                                                <div class="col-lg-4 col-md-4 col-12">--}}
                                    {{--                                                    <select name="city" id="city" style="width: 100%">--}}
                                    {{--                                                        <option value="">اختر المدينة</option>--}}
                                    {{--                                                        <option value="reyad" selected>الاحساء</option>--}}
                                    {{--                                                        <option value="gadda">القطيف</option>--}}
                                    {{--                                                        <option value="dammam">الهفوف</option>--}}
                                    {{--                                                        <option value="mecca">الجبيل</option>--}}
                                    {{--                                                        <option value="gezan">الثقبة</option>--}}
                                    {{--                                                        <option value="taif">الخبر</option>--}}
                                    {{--                                                        <option value="najran">ضباء</option>--}}
                                    {{--                                                        <option value="tabuk">عرعر</option>--}}
                                    {{--                                                    </select>--}}
                                    {{--                                                </div>--}}
                                    {{--                                                <div class="col-lg-4 col-md-4 col-12">--}}
                                    {{--                                                    <span class="bold num_fam s_16 primary_color">{{number_format(100,1)}} ريال</span>--}}
                                    {{--                                                </div>--}}
                                    {{--                                            </div>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}


                                    <div class="row tex-{{session('direction') == 'rtl' ? 'right' : 'left'}} mt-3"
                                         dir="{{session('direction') }}">
                                        <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                                            @if(($product['product_type'] == 'physical') && ($product['current_stock']<=0))
                                                <span class="bold s_17 "
                                                      style="color: #FF7C86;">{{\App\CPU\translate('out_of_stock')}}</span>
                                            @else
                                                <span class="bold s_17 " style="color: #FF7C86;"
                                                      id="choices_stock"></span>
                                            @endif
{{--                                            <div class="d-inline-block"><strong id="set-tax-amount"--}}
{{--                                                                                class="mx-2 primary_color"--}}
{{--                                                                                dir="{{session('direction')}}"></strong>--}}
{{--                                            </div>--}}
                                        </div>
                                    </div>
                                    <div class="row mt-4" dir="{{session('direction')}}">

                                        @if(($product->added_by == 'seller' && ($seller_temporary_close || (isset($product->seller->shop) && $product->seller->shop->vacation_status && $current_date >= $seller_vacation_start_date && $current_date <= $seller_vacation_end_date))) ||
                                             ($product->added_by == 'admin' && ($inhouse_temporary_close || ($inhouse_vacation_status && $current_date >= $inhouse_vacation_start_date && $current_date <= $inhouse_vacation_end_date))))

                                            <div class="col-xxl-9 col-xl-9 col-lg-9 col-md-9 col-sm-6 col-9">
                                                <button class="bold s_22 w-100" disabled type="button"
                                                        style="border: 1px solid #FF7C86; color: #FF7C86">{{\App\CPU\translate('Add To Cart')}}
                                                </button>
                                            </div>
                                        @else

                                            <div class="col-xxl-9 col-xl-9 col-lg-9 col-md-9 col-sm-6 col-9">
                                                <button class="bold s_22 w-100" onclick="addToCart()" type="button"
                                                        style="border: 1px solid #FF7C86;
    color: #FF7C86;
    background: #fff;
    border-radius: 8px;
    font-size: 18px;    padding: 3px;">{{\App\CPU\translate('Add To Cart')}}
                                                </button>
                                            </div>
                                        @endif
                                        <div class="col-xxl-2 col-xl-2 col-lg-2 col-md-2 col-sm-6 col-3">
                                            <button class=" text-danger" onclick="addWishlist('{{$product['id']}}')"
                                                    type="button" style="    border: 1px solid #FF7C86;
    background: #fff;
    border-radius: 18px;
    font-size: 21px;
position: relative;
    width: 35px;
    height: 35px;">
                                                <i class="fa fa-heart-o " aria-hidden="true" style="    position: absolute;
    top: 5px;
    right: 6px;"></i>
                                                <span class="countWishlist-{{$product['id']}}"></span>
                                            </button>
                                        </div>

                                    </div>
                                </form>

                                <div style="text-align:{{Session::get('direction') === "rtl" ? 'right ; margin-right: 105px' : 'left;margin-left: 105px'}};"
                                     class="sharethis-inline-share-buttons">

                                </div>
                                <div class="row mt-3" dir="{{session('direction')}}">
                                    <div
                                        class="col-lg-10 col-md-10 col-sm-10 col-12 installments bold px-3 pt-1 pb-2">
                                        <h5 class="primary_color s_16 mb-2 pt-2 bold">{{\App\CPU\translate('Installments System')}}</h5>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-6 ">
                                                <div class="tabby text-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="103" height="41" fill="none" viewBox="0 0 103 41" class="installments-promo-widget_widget__icon-tabby__8apMt"><path fill="#000" d="m89.3 12.35-6.06 23.18-.02.05h4.72l6.08-23.23H89.3ZM14.35 25.54a5.1 5.1 0 0 1-2.28.53c-1.7 0-2.66-.27-2.76-1.65V12.45l-4.22.56c2.85-.55 4.49-2.81 4.49-5.07V6.56H4.84v6.49l-.26.07v12.02c.15 3.37 2.37 5.38 6.02 5.38 1.28 0 2.7-.3 3.79-.79h.02V25.5l-.06.03Z"></path><path fill="#000" d="M15.1 11.48 1.8 13.53v3.38l13.29-2.05v-3.38ZM15.1 16.42 1.8 18.47v3.23l13.29-2.06v-3.22ZM30 17.97c-.18-3.75-2.52-5.97-6.33-5.97-2.2 0-4 .85-5.22 2.45-1.23 1.6-1.87 3.96-1.87 6.8 0 2.86.65 5.22 1.87 6.82a6.31 6.31 0 0 0 5.22 2.45c3.81 0 6.15-2.23 6.34-6v5.65h4.72V12.39l-4.72.74v4.84Zm.25 3.3c0 3.32-1.74 5.46-4.43 5.46-2.77 0-4.43-2.04-4.43-5.47 0-3.44 1.65-5.5 4.43-5.5a4 4 0 0 1 3.26 1.52 6.37 6.37 0 0 1 1.17 3.98ZM48.55 12c-3.82 0-6.16 2.22-6.34 5.98V7.22l-4.73.73v22.22h4.73V24.5c.18 3.78 2.52 6.02 6.34 6.02 4.46 0 7.12-3.47 7.12-9.27 0-5.8-2.66-9.26-7.12-9.26ZM46.4 26.73c-2.7 0-4.44-2.15-4.44-5.47 0-1.63.4-3 1.17-3.98a4 4 0 0 1 3.27-1.52c2.77 0 4.43 2.06 4.43 5.5 0 3.42-1.66 5.47-4.43 5.47ZM68.56 12c-3.8 0-6.15 2.22-6.33 5.98V7.22l-4.74.73v22.22h4.74V24.5c.18 3.78 2.52 6.02 6.33 6.02 4.47 0 7.13-3.47 7.13-9.27 0-5.8-2.66-9.26-7.13-9.26Zm-2.15 14.73c-2.69 0-4.43-2.15-4.43-5.47 0-1.63.4-3 1.17-3.98a4 4 0 0 1 3.26-1.52c2.78 0 4.44 2.06 4.44 5.5 0 3.42-1.66 5.47-4.44 5.47ZM75.7 12.35h5.05l4.11 17.78h-4.53L75.7 12.35Z"></path></svg>
                                                    <span class="d-block">{{\App\CPU\translate('Split over 4 payments with no extra fees starting from SAR 107')}}</span>

                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-6 ">
                                                <div class="tamara text-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="129" height="25" fill="none" viewBox="0 0 129 25" class="installments-promo-widget_widget__icon-tamara__Paydr"><path fill="#000" d="M32.79 5.88a12.09 12.09 0 0 0-7.28-.67c-2.2.47-3.94 1.34-5.08 3.55.54.4.94.8 1.4 1.2.74.67 1.48 1.27 2.48 2.07.8-1.14 2.2-2.4 3.74-2.47 1.73-.07 3.4.93 3.67 2.34.13.6.2 3 .2 3l-.94.2c-.06 0-.26.07-.53 0-.07 0-.07-.06-.13-.06-.07 0-.07 0-.14-.07-2.07-.73-4-1.14-5.87-1.14-.67 0-1.34.07-2 .14-2.08.33-4.48 1.47-4.48 5.21 0 1.4.33 2.47.93 3.34A5.8 5.8 0 0 0 23.64 25c2.34 0 4.4-.6 6.4-2.2.08 0 .48-.41.61-.48l1.27-1V25h5.27V12.03a6.64 6.64 0 0 0-4.4-6.15Zm-1.67 12.03a5.74 5.74 0 0 1-5.08 3.14h-.26c-.4 0-.87-.06-1.34-.13-1.6-.4-2.47-1.67-2.4-3.4l.06-.74h9.69l-.67 1.13ZM87.68 5.88a12.09 12.09 0 0 0-7.28-.67c-2.2.47-3.94 1.34-5.08 3.55.54.4.94.8 1.4 1.2.74.67 1.47 1.27 2.48 2.07.8-1.14 2.2-2.4 3.74-2.47 1.73-.07 3.4.93 3.67 2.34.13.6.2 3 .2 3l-.94.2c-.06 0-.26.07-.53 0-.07 0-.07-.06-.13-.06-.07 0-.07 0-.14-.07-2.07-.73-4-1.14-5.87-1.14-.67 0-1.34.07-2 .14-2.08.33-4.48 1.47-4.48 5.21 0 1.4.33 2.47.93 3.34A5.8 5.8 0 0 0 78.53 25c2.34 0 4.4-.6 6.4-2.2.08 0 .48-.41.61-.48l1.27-1V25h5.27V12.03a6.55 6.55 0 0 0-4.4-6.15ZM86 17.91a5.73 5.73 0 0 1-5.08 3.14h-.27c-.4 0-.86-.06-1.33-.13-1.6-.4-2.47-1.67-2.4-3.4l.06-.74h9.68l-.66 1.13ZM123.6 5.88a12.08 12.08 0 0 0-7.28-.67c-2.2.47-3.94 1.34-5.07 3.55.53.4.93.8 1.4 1.2.73.67 1.47 1.27 2.47 2.07.8-1.14 2.2-2.4 3.74-2.47 1.74-.07 3.4.93 3.67 2.34.14.6.2 3 .2 3l-.93.2c-.07 0-.27.07-.54 0-.06 0-.06-.06-.13-.06s-.07 0-.13-.07c-2.07-.73-4.01-1.14-5.88-1.14-.67 0-1.34.07-2 .14-2.07.33-4.48 1.47-4.48 5.21 0 1.4.34 2.47.94 3.34a5.8 5.8 0 0 0 4.87 2.48c2.34 0 4.41-.6 6.41-2.2.07 0 .47-.41.6-.48l1.27-1V25h5.28V12.03a6.64 6.64 0 0 0-4.4-6.15Zm-1.67 12.03a5.74 5.74 0 0 1-5.07 3.14h-.27c-.4 0-.87-.06-1.34-.13-1.6-.4-2.47-1.67-2.4-3.4l.07-.74h9.68l-.67 1.13ZM106.37 6.55c-1.67.07-2.94.8-3.74 2.27-.06.14-.66 1.27-.66 1.27l-1.27-.26V6.82h-5.08v18.11h5.08v-7.08c0-.6-.07-1.2 0-1.88a4.65 4.65 0 0 1 4.14-4.34c.73-.07 2.47-.13 2.67-.13V6.55h-1.14ZM13.82 20.72c-.4 0-.87 0-1.33-.13-1.34-.2-2.2-.87-2.54-1.94-.13-.47-.27-.94-.27-1.4v-8.1h.8c1.67-.06 3.48-.2 5.21-.66L14.62 4.8l-4.87.74V0H4v5.41H0v3.75h3.87v9.69a6 6 0 0 0 1 3.4 5.43 5.43 0 0 0 3.54 2.35c1.8.46 3.54.4 5.48.4h.47v-4.28h-.54ZM57.96 14.7c0-1.8 1.2-3.27 2.87-3.4 2-.2 3.4.8 3.81 2.67.07.33.07.67.07 1v9.96h5.07V12.7c0-.74-.07-1.27-.13-1.74-.4-2.27-1.6-3.6-3.67-4.21-2.2-.6-5.88-.13-7.62 2.87l-.8 1.54c-.4-2.27-1.73-3.88-3.74-4.48-2.2-.6-5.54-.06-7.2 2.88l-.74 1.33V6.82h-5.35v18.11h5.28V15.1c0-.33 0-.66.07-1a3.15 3.15 0 0 1 2.33-2.67c1.47-.4 2.94.07 3.74 1.14.6.8.67 1.67.67 2.33v10.03h5.34"></path></svg>
                                                    <span class="d-block">{{\App\CPU\translate('Split over 4 payments with no extra fees starting from SAR 107')}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        {{--                                        <img src="{{asset('assets/front-end/img/tamara.png')}}" alt="tamara" class=""--}}
                                        {{--                                             style="width: 82px;">--}}
                                        {{--                                        <img src="{{asset('assets/front-end/img/tabby.png')}}" alt="tamara" class=""--}}
                                        {{--                                             style="width:68px">--}}
                                        <span class="s_16 bold mt-2">
                                            {{\App\CPU\translate('Now You Can use Installments System From Beauty Center')}}
                                        </span>
                                        <a class="s_16 bold primary_color" href="#">
                                            {{\App\CPU\translate('For More Informations')}}
                                        </a>
                                    </div>
                                </div>

                                <div class="row mt-3" dir="{{session('direction')}}">
                                    <div
                                        class="col-lg-10 col-md-10 col-sm-10 col-12 s_15 instructions bold px-3 pt-1 pb-2">
                                        <h5 style="color: #000"
                                            class="s_15 mb-0 pt-2 bold">{{\App\CPU\translate('Free Shipping')}}</h5>
                                        <span class="d-block" style="color: #767676">
                                            {{\App\CPU\translate('Free Shipping For Orders More Than 500 SAR')}}
                                        </span>
                                        <span class="d-block" style="color: #767676">
                                            {{\App\CPU\translate('Delivery Date in ')}}  21/03/2023 - 24/03/2023.
                                        </span>
                                        <h5 style="color: #000" class="s_11 mb-0 pt-2 bold">
                                            {{\App\CPU\translate('Refund Policy')}}
                                        </h5>
                                        <span class="d-block" style="color: #767676">
                                            {{\App\CPU\translate('Free Shipping For Orders More Than 500 SAR')}}
                                        </span>
                                        <a class="d-block" href="#" style="color: #767676">
                                            {{\App\CPU\translate('See More')}}
                                        </a>
                                    </div>
                                </div>
                                {{--                                                                <div class="mb-3">--}}
                                {{--                                                                    @if($product->discount > 0)--}}
                                {{--                                                                        <strike style="color: #E96A6A;" class="{{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-3'}}">--}}
                                {{--                                                                            {{\App\CPU\Helpers::currency_converter($product->unit_price)}}--}}
                                {{--                                                                        </strike>--}}
                                {{--                                                                    @endif--}}
                                {{--                                                                    <span class="h3 font-weight-normal text-accent ">--}}
                                {{--                                                                        {{\App\CPU\Helpers::get_price_range($product) }}--}}
                                {{--                                                                    </span>--}}
                                {{--                                                                    <span class="{{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}} __text-12px font-regular">--}}
                                {{--                                                                        (<span>{{\App\CPU\translate('tax')}} : </span>--}}
                                {{--                                                                        <span id="set-tax-amount"></span>)--}}
                                {{--                                                                    </span>--}}
                                {{--                                                                </div>--}}

                                {{--                                                                <form id="add-to-cart-form" class="mb-2">--}}
                                {{--                                                                    @csrf--}}
                                {{--                                                                    <input type="hidden" name="id" value="{{ $product->id }}">--}}
                                {{--                                                                    <div class="position-relative {{Session::get('direction') === "rtl" ? 'ml-n4' : 'mr-n4'}} mb-2">--}}
                                {{--                                                                        @if (count(json_decode($product->colors)) > 0)--}}
                                {{--                                                                            <div class="flex-start">--}}
                                {{--                                                                                <div class="product-description-label mt-2 text-body">{{\App\CPU\translate('color')}}:--}}
                                {{--                                                                                </div>--}}
                                {{--                                                                                <div>--}}
                                {{--                                                                                    <ul class="list-inline checkbox-color mb-1 flex-start {{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}}"--}}
                                {{--                                                                                        style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 0;">--}}
                                {{--                                                                                        @foreach (json_decode($product->colors) as $key => $color)--}}
                                {{--                                                                                            <div>--}}
                                {{--                                                                                                <li>--}}
                                {{--                                                                                                    <input type="radio"--}}
                                {{--                                                                                                        id="{{ $product->id }}-color-{{ str_replace('#','',$color) }}"--}}
                                {{--                                                                                                        name="color" value="{{ $color }}"--}}
                                {{--                                                                                                        @if($key == 0) checked @endif>--}}
                                {{--                                                                                                    <label style="background: {{ $color }};"--}}
                                {{--                                                                                                        for="{{ $product->id }}-color-{{ str_replace('#','',$color) }}"--}}
                                {{--                                                                                                        data-toggle="tooltip" onclick="focus_preview_image_by_color('{{ str_replace('#','',$color) }}')">--}}
                                {{--                                                                                                    <span class="outline"></span></label>--}}
                                {{--                                                                                                </li>--}}
                                {{--                                                                                            </div>--}}
                                {{--                                                                                        @endforeach--}}
                                {{--                                                                                    </ul>--}}
                                {{--                                                                                </div>--}}
                                {{--                                                                            </div>--}}
                                {{--                                                                        @endif--}}
                                {{--                                                                        @php--}}
                                {{--                                                                            $qty = 0;--}}
                                {{--                                                                            if(!empty($product->variation)){--}}
                                {{--                                                                            foreach (json_decode($product->variation) as $key => $variation) {--}}
                                {{--                                                                                    $qty += $variation->qty;--}}
                                {{--                                                                                }--}}
                                {{--                                                                            }--}}
                                {{--                                                                        @endphp--}}
                                {{--                                                                    </div>--}}
                                {{--                                                                    @foreach (json_decode($product->choice_options) as $key => $choice)--}}
                                {{--                                                                        <div class="row flex-start mx-0">--}}
                                {{--                                                                            <div--}}
                                {{--                                                                                class="product-description-label text-body mt-2 {{Session::get('direction') === "rtl" ? 'pl-2' : 'pr-2'}}">{{ $choice->title }}--}}
                                {{--                                                                                :--}}
                                {{--                                                                            </div>--}}
                                {{--                                                                            <div>--}}
                                {{--                                                                                <ul class="list-inline checkbox-alphanumeric checkbox-alphanumeric--style-1 mb-2 mx-1 flex-start row"--}}
                                {{--                                                                                    style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 0;">--}}
                                {{--                                                                                    @foreach ($choice->options as $key => $option)--}}
                                {{--                                                                                        <div>--}}
                                {{--                                                                                            <li class="for-mobile-capacity">--}}
                                {{--                                                                                                <input type="radio"--}}
                                {{--                                                                                                    id="{{ $choice->name }}-{{ $option }}"--}}
                                {{--                                                                                                    name="{{ $choice->name }}" value="{{ $option }}"--}}
                                {{--                                                                                                    @if($key == 0) checked @endif >--}}
                                {{--                                                                                                <label class="__text-12px"--}}
                                {{--                                                                                                    for="{{ $choice->name }}-{{ $option }}">{{ $option }}</label>--}}
                                {{--                                                                                            </li>--}}
                                {{--                                                                                        </div>--}}
                                {{--                                                                                    @endforeach--}}
                                {{--                                                                                </ul>--}}
                                {{--                                                                            </div>--}}
                                {{--                                                                        </div>--}}
                                {{--                                                                @endforeach--}}

                                {{--                                                                <!-- Quantity + Add to cart -->--}}
                                {{--                                                                    <div class="mt-2">--}}
                                {{--                                                                        <div class="product-quantity d-flex flex-wrap align-items-center __gap-15">--}}
                                {{--                                                                            <div class="d-flex align-items-center">--}}
                                {{--                                                                                <div class="product-description-label text-body mt-2">{{\App\CPU\translate('Quantity')}}:</div>--}}
                                {{--                                                                                <div--}}
                                {{--                                                                                    class="d-flex justify-content-center align-items-center __w-160px"--}}
                                {{--                                                                                    style="color: {{$web_config['primary_color']}}">--}}
                                {{--                                                                                    <span class="input-group-btn">--}}
                                {{--                                                                                        <button class="btn btn-number __p-10" type="button"--}}
                                {{--                                                                                                data-type="minus" data-field="quantity"--}}
                                {{--                                                                                                disabled="disabled" style="color: {{$web_config['primary_color']}}">--}}
                                {{--                                                                                            ---}}
                                {{--                                                                                        </button>--}}
                                {{--                                                                                    </span>--}}
                                {{--                                                                                    <input type="text" name="quantity"--}}
                                {{--                                                                                        class="form-control input-number text-center cart-qty-field __inline-29"--}}
                                {{--                                                                                        placeholder="1" value="{{ $product->minimum_order_qty ?? 1 }}" product-type="{{ $product->product_type }}" min="{{ $product->minimum_order_qty ?? 1 }}" max="100">--}}
                                {{--                                                                                    <span class="input-group-btn">--}}
                                {{--                                                                                        <button class="btn btn-number __p-10" type="button" product-type="{{ $product->product_type }}" data-type="plus"--}}
                                {{--                                                                                                data-field="quantity" style="color: {{$web_config['primary_color']}}">--}}
                                {{--                                                                                        +--}}
                                {{--                                                                                        </button>--}}
                                {{--                                                                                    </span>--}}
                                {{--                                                                                </div>--}}
                                {{--                                                                            </div>--}}
                                {{--                                                                            <div id="chosen_price_div">--}}
                                {{--                                                                                <div class="d-flex justify-content-center align-items-center {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}">--}}
                                {{--                                                                                    <div class="product-description-label"><strong>{{\App\CPU\translate('total_price')}}</strong> : </div>--}}
                                {{--                                                                                    &nbsp; <strong id="chosen_price"></strong>--}}
                                {{--                                                                                </div>--}}
                                {{--                                                                            </div>--}}
                                {{--                                                                        </div>--}}
                                {{--                                                                    </div>--}}
                                {{--                                                                    <div class="row no-gutters d-none mt-2 flex-start d-flex">--}}
                                {{--                                                                        <div class="col-12">--}}
                                {{--                                                                            @if(($product['product_type'] == 'physical') && ($product['current_stock']<=0))--}}
                                {{--                                                                                <h5 class="mt-3 text-danger">{{\App\CPU\translate('out_of_stock')}}</h5>--}}
                                {{--                                                                            @endif--}}
                                {{--                                                                        </div>--}}
                                {{--                                                                    </div>--}}

                                {{--                                                                    <div class="__btn-grp mt-2 mb-3">--}}
                                {{--                                                                        @if(($product->added_by == 'seller' && ($seller_temporary_close || (isset($product->seller->shop) && $product->seller->shop->vacation_status && $current_date >= $seller_vacation_start_date && $current_date <= $seller_vacation_end_date))) ||--}}
                                {{--                                                                         ($product->added_by == 'admin' && ($inhouse_temporary_close || ($inhouse_vacation_status && $current_date >= $inhouse_vacation_start_date && $current_date <= $inhouse_vacation_end_date))))--}}
                                {{--                                                                            <button class="btn btn-secondary" type="button" disabled>--}}
                                {{--                                                                                {{\App\CPU\translate('buy_now')}}--}}
                                {{--                                                                            </button>--}}
                                {{--                                                                            <button class="btn btn--primary string-limit" type="button" disabled>--}}
                                {{--                                                                                {{\App\CPU\translate('add_to_cart')}}--}}
                                {{--                                                                            </button>--}}
                                {{--                                                                        @else--}}
                                {{--                                                                            <button class="btn btn-secondary element-center __iniline-26 btn-gap-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}" onclick="buy_now()" type="button">--}}
                                {{--                                                                                <span class="string-limit">{{\App\CPU\translate('buy_now')}}</span>--}}
                                {{--                                                                            </button>--}}
                                {{--                                                                            <button--}}
                                {{--                                                                                class="btn btn--primary element-center btn-gap-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}"--}}
                                {{--                                                                                onclick="addToCart()" type="button">--}}
                                {{--                                                                                <span class="string-limit">{{\App\CPU\translate('add_to_cart')}}</span>--}}
                                {{--                                                                            </button>--}}
                                {{--                                                                        @endif--}}
                                {{--                                                                        <button type="button" onclick="addWishlist('{{$product['id']}}')"--}}
                                {{--                                                                                class="btn __text-18px text-danger">--}}
                                {{--                                                                            <i class="fa fa-heart-o "--}}
                                {{--                                                                            aria-hidden="true"></i>--}}
                                {{--                                                                            <span class="countWishlist-{{$product['id']}}">{{$countWishlist}}</span>--}}
                                {{--                                                                        </button>--}}
                                {{--                                                                        @if(($product->added_by == 'seller' && ($seller_temporary_close || (isset($product->seller->shop) && $product->seller->shop->vacation_status && $current_date >= $seller_vacation_start_date && $current_date <= $seller_vacation_end_date))) ||--}}
                                {{--                                                                         ($product->added_by == 'admin' && ($inhouse_temporary_close || ($inhouse_vacation_status && $current_date >= $inhouse_vacation_start_date && $current_date <= $inhouse_vacation_end_date))))--}}
                                {{--                                                                            <div class="alert alert-danger" role="alert">--}}
                                {{--                                                                                {{\App\CPU\translate('this_shop_is_temporary_closed_or_on_vacation._You_cannot_add_product_to_cart_from_this_shop_for_now')}}--}}
                                {{--                                                                            </div>--}}
                                {{--                                                                        @endif--}}
                                {{--                                                                    </div>--}}
                                {{--                                                                </form>--}}

                                {{--                                                                <div style="text-align:{{Session::get('direction') === "rtl" ? 'right' : 'left'}};"--}}
                                {{--                                                                    class="sharethis-inline-share-buttons"></div>--}}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mt-4 rtl col-12"
                             style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                            <div class="row">
                                <div class="col-12">
                                    <div class=" mt-1">
                                        <!-- Tabs-->
                                        <ul class="nav nav-tabs d-flex  __mt-35" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link __inline-27 active " href="#overview"
                                                   data-toggle="tab" role="tab">
                                                    {{\App\CPU\translate('overview')}}
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link __inline-27" href="#reviews" data-toggle="tab"
                                                   role="tab">
                                                    {{\App\CPU\translate('reviews')}}
                                                </a>
                                            </li>
                                        </ul>
                                        <div
                                            class="px-4 pt-lg-3 pb-3 mb-3 mr-0 mr-md-2 bg-white __review-overview __rounded-10">
                                            <div class="tab-content px-lg-3">
                                                <!-- Tech specs tab-->
                                                <div class="tab-pane fade show active" id="overview" role="tabpanel">
                                                    <div class="row pt-2 specification">
                                                        @if($product->video_url!=null)
                                                            <div class="col-12 mb-4">
                                                                <iframe width="420" height="315"
                                                                        src="{{$product->video_url}}">
                                                                </iframe>
                                                            </div>
                                                        @endif

                                                        <div class="text-body col-lg-12 col-md-12 overflow-scroll">
                                                            {!! $product['details'] !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            @php($reviews_of_product = App\Model\Review::where('product_id',$product->id)->paginate(2))
                                            <!-- Reviews tab-->
                                                <div class="tab-pane fade" id="reviews" role="tabpanel">
                                                    <div class="row pt-2 pb-3">
                                                        <div class="col-lg-4 col-md-5 ">
                                                            <div
                                                                class=" row d-flex justify-content-center align-items-center">
                                                                <div
                                                                    class="col-12 d-flex justify-content-center align-items-center">
                                                                    <h2 class="overall_review mb-2 __inline-28">
                                                                        {{$overallRating[1]}}
                                                                    </h2>
                                                                </div>
                                                                <div
                                                                    class="d-flex justify-content-center align-items-center star-rating ">
                                                                    @if (round($overallRating[0])==5)
                                                                        @for ($i = 0; $i < 5; $i++)
                                                                            <i class="czi-star-filled font-size-sm text-accent {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}"></i>
                                                                        @endfor
                                                                    @endif
                                                                    @if (round($overallRating[0])==4)
                                                                        @for ($i = 0; $i < 4; $i++)
                                                                            <i class="czi-star-filled font-size-sm text-accent {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}"></i>
                                                                        @endfor
                                                                        <i class="czi-star font-size-sm text-muted {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}"></i>
                                                                    @endif
                                                                    @if (round($overallRating[0])==3)
                                                                        @for ($i = 0; $i < 3; $i++)
                                                                            <i class="czi-star-filled font-size-sm text-accent {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}"></i>
                                                                        @endfor
                                                                        @for ($j = 0; $j < 2; $j++)
                                                                            <i class="czi-star font-size-sm text-accent {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}"></i>
                                                                        @endfor
                                                                    @endif
                                                                    @if (round($overallRating[0])==2)
                                                                        @for ($i = 0; $i < 2; $i++)
                                                                            <i class="czi-star-filled font-size-sm text-accent {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}"></i>
                                                                        @endfor
                                                                        @for ($j = 0; $j < 3; $j++)
                                                                            <i class="czi-star font-size-sm text-accent {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}"></i>
                                                                        @endfor
                                                                    @endif
                                                                    @if (round($overallRating[0])==1)
                                                                        @for ($i = 0; $i < 4; $i++)
                                                                            <i class="czi-star font-size-sm text-accent {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}"></i>
                                                                        @endfor
                                                                        <i class="czi-star-filled font-size-sm text-accent {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}"></i>
                                                                    @endif
                                                                    @if (round($overallRating[0])==0)
                                                                        @for ($i = 0; $i < 5; $i++)
                                                                            <i class="czi-star font-size-sm text-muted {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}"></i>
                                                                        @endfor
                                                                    @endif
                                                                </div>
                                                                <div
                                                                    class="col-12 d-flex justify-content-center align-items-center mt-2">
                                                                    <span class="text-center">
                                                                        {{$reviews_of_product->total()}} {{\App\CPU\translate('ratings')}}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-8 col-md-7 pt-sm-3 pt-md-0">
                                                            <div class="d-flex align-items-center mb-2 font-size-sm">
                                                                <div
                                                                    class="__rev-txt"><span
                                                                        class="d-inline-block align-middle text-body">{{\App\CPU\translate('Excellent')}}</span>
                                                                </div>
                                                                <div class="w-0 flex-grow">
                                                                    <div class="progress text-body __h-5px">
                                                                        <div class="progress-bar " role="progressbar"
                                                                             style="background-color: {{$web_config['primary_color']}} !important;width: <?php echo $widthRating = ($rating[0] != 0) ? ($rating[0] / $overallRating[1]) * 100 : (0); ?>%;"
                                                                             aria-valuenow="60" aria-valuemin="0"
                                                                             aria-valuemax="100"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-1 text-body">
                                                                    <span
                                                                        class=" {{Session::get('direction') === "rtl" ? 'mr-3 float-left' : 'ml-3 float-right'}} ">
                                                                        {{$rating[0]}}
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div
                                                                class="d-flex align-items-center mb-2 text-body font-size-sm">
                                                                <div
                                                                    class="__rev-txt"><span
                                                                        class="d-inline-block align-middle ">{{\App\CPU\translate('Good')}}</span>
                                                                </div>
                                                                <div class="w-0 flex-grow">
                                                                    <div class="progress __h-5px">
                                                                        <div class="progress-bar" role="progressbar"
                                                                             style="background-color: {{$web_config['primary_color']}} !important;width: <?php echo $widthRating = ($rating[1] != 0) ? ($rating[1] / $overallRating[1]) * 100 : (0); ?>%; background-color: #a7e453;"
                                                                             aria-valuenow="27" aria-valuemin="0"
                                                                             aria-valuemax="100"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-1">
                                                                    <span
                                                                        class="{{Session::get('direction') === "rtl" ? 'mr-3 float-left' : 'ml-3 float-right'}}">
                                                                            {{$rating[1]}}
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div
                                                                class="d-flex align-items-center mb-2 text-body font-size-sm">
                                                                <div
                                                                    class="__rev-txt"><span
                                                                        class="d-inline-block align-middle ">{{\App\CPU\translate('Average')}}</span>
                                                                </div>
                                                                <div class="w-0 flex-grow">
                                                                    <div class="progress __h-5px">
                                                                        <div class="progress-bar" role="progressbar"
                                                                             style="background-color: {{$web_config['primary_color']}} !important;width: <?php echo $widthRating = ($rating[2] != 0) ? ($rating[2] / $overallRating[1]) * 100 : (0); ?>%; background-color: #ffda75;"
                                                                             aria-valuenow="17" aria-valuemin="0"
                                                                             aria-valuemax="100"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-1">
                                                                    <span
                                                                        class="{{Session::get('direction') === "rtl" ? 'mr-3 float-left' : 'ml-3 float-right'}}">
                                                                        {{$rating[2]}}
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div
                                                                class="d-flex align-items-center mb-2 text-body font-size-sm">
                                                                <div
                                                                    class="__rev-txt "><span
                                                                        class="d-inline-block align-middle">{{\App\CPU\translate('Below Average')}}</span>
                                                                </div>
                                                                <div class="w-0 flex-grow">
                                                                    <div class="progress __h-5px">
                                                                        <div class="progress-bar" role="progressbar"
                                                                             style="background-color: {{$web_config['primary_color']}} !important;width: <?php echo $widthRating = ($rating[3] != 0) ? ($rating[3] / $overallRating[1]) * 100 : (0); ?>%; background-color: #fea569;"
                                                                             aria-valuenow="9" aria-valuemin="0"
                                                                             aria-valuemax="100"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-1">
                                                                    <span
                                                                        class="{{Session::get('direction') === "rtl" ? 'mr-3 float-left' : 'ml-3 float-right'}}">
                                                                        {{$rating[3]}}
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div
                                                                class="d-flex align-items-center text-body font-size-sm">
                                                                <div
                                                                    class="__rev-txt"><span
                                                                        class="d-inline-block align-middle ">{{\App\CPU\translate('Poor')}}</span>
                                                                </div>
                                                                <div class="w-0 flex-grow">
                                                                    <div class="progress __h-5px">
                                                                        <div class="progress-bar" role="progressbar"
                                                                             style="background-color: {{$web_config['primary_color']}} !important;backbround-color:{{$web_config['primary_color']}};width: <?php echo $widthRating = ($rating[4] != 0) ? ($rating[4] / $overallRating[1]) * 100 : (0); ?>%;"
                                                                             aria-valuenow="4" aria-valuemin="0"
                                                                             aria-valuemax="100"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-1">
                                                                    <span
                                                                        class="{{Session::get('direction') === "rtl" ? 'mr-3 float-left' : 'ml-3 float-right'}}">
                                                                            {{$rating[4]}}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row pb-4 mb-3">
                                                        <div class="__inline-30">
                                                            <span
                                                                class="text-capitalize">{{\App\CPU\translate('Product Review')}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row pb-4">
                                                        <div class="col-12" id="product-review-list">
                                                            {{-- @foreach($reviews_of_product as $productReview) --}}
                                                            {{-- @include('web-views.partials.product-reviews',['productRevie'=>$productRevie]) --}}
                                                            {{-- @endforeach --}}
                                                            @if(count($product->reviews)==0)
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <h6 class="text-danger text-center m-0">{{\App\CPU\translate('product_review_not_available')}}</h6>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                        </div>
                                                        @if(count($product->reviews) > 2)
                                                            <div class="col-12">
                                                                <div
                                                                    class="card-footer d-flex justify-content-center align-items-center">
                                                                    <button class="btn text-white"
                                                                            style="background: {{$web_config['primary_color']}};"
                                                                            onclick="load_review()">{{\App\CPU\translate('view more')}}</button>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    @if (count($relatedProducts)>0)

        <!-- Product carousel (You may also like)-->
            <div class="container mt-5  mb-3 ltr"
                 style="text-align: {{Session::get('direction') === "ltr" ? 'right' : 'left'}};">
                <div class="row flex-between">
                    <div class="text-capitalize font-bold __text-30px"
                         style="{{Session::get('direction') === "ltr" ? 'margin-right: 5px;' : 'margin-left: 5px;'}}">
                        <span>{{\App\CPU\translate('Suggested For You')}}</span>
                    </div>

                    <div class="view_all d-flex justify-content-center align-items-center">
                        <div>
                            @php($category=json_decode($product['category_ids']))
                            @if($category)
                                <a class="text-capitalize view-all-text"
                                   style="color:{{$web_config['primary_color']}} !important;{{Session::get('direction') === "ltr" ? 'margin-left:10px;' : 'margin-right: 8px;'}}"
                                   href="{{route('products',['id'=> $category[0]->id,'data_from'=>'category','page'=>1])}}">
                                    {{\App\CPU\translate('View All')}}
                                    <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left mr-1 ml-n1 mt-1 ' : 'right ml-1 mr-n1'}}"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Grid-->

                <!-- Product-->
                <div class="row mt-4">
                    @if (count($relatedProducts)>0)
                        @foreach($relatedProducts as $key => $relatedProduct)
                            <div class="col-xl-2 col-sm-3 col-6 mb-4">
                                @include('web-views.partials._single-product',['product'=>$relatedProduct,'decimal_point_settings'=>$decimal_point_settings])
                            </div>
                            {{--                        <div class="col-xl-2 col-sm-3 col-6 mb-4">--}}
                            {{--                            @include('web-views.partials._single-product',['product'=>$relatedProduct,'decimal_point_settings'=>$decimal_point_settings])--}}
                            {{--                        </div>--}}
                            {{--                        <div class="col-xl-2 col-sm-3 col-6 mb-4">--}}
                            {{--                            @include('web-views.partials._single-product',['product'=>$relatedProduct,'decimal_point_settings'=>$decimal_point_settings])--}}
                            {{--                        </div>--}}
                            {{--                        <div class="col-xl-2 col-sm-3 col-6 mb-4">--}}
                            {{--                            @include('web-views.partials._single-product',['product'=>$relatedProduct,'decimal_point_settings'=>$decimal_point_settings])--}}
                            {{--                        </div>--}}
                            {{--                        <div class="col-xl-2 col-sm-3 col-6 mb-4">--}}
                            {{--                            @include('web-views.partials._single-product',['product'=>$relatedProduct,'decimal_point_settings'=>$decimal_point_settings])--}}
                            {{--                        </div>--}}
                            {{--                        <div class="col-xl-2 col-sm-3 col-6 mb-4">--}}
                            {{--                            @include('web-views.partials._single-product',['product'=>$relatedProduct,'decimal_point_settings'=>$decimal_point_settings])--}}
                            {{--                        </div>--}}
                            {{--                        <div class="col-xl-2 col-sm-3 col-6 mb-4">--}}
                            {{--                            @include('web-views.partials._single-product',['product'=>$relatedProduct,'decimal_point_settings'=>$decimal_point_settings])--}}
                            {{--                        </div>--}}
                            {{--                        <div class="col-xl-2 col-sm-3 col-6 mb-4">--}}
                            {{--                            @include('web-views.partials._single-product',['product'=>$relatedProduct,'decimal_point_settings'=>$decimal_point_settings])--}}
                            {{--                        </div>--}}
                        @endforeach
                    @else
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h6>{{\App\CPU\translate('similar')}} {{\App\CPU\translate('product_not_available')}}</h6>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
        <div class="modal fade rtl" id="show-modal-view" tabindex="-1" role="dialog" aria-labelledby="show-modal-image"
             aria-hidden="true" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body flex justify-content-center">
                        <button class="btn btn-default __inline-33"
                                style="{{Session::get('direction') === "rtl" ? 'left' : 'right'}}: -7px;"
                                data-dismiss="modal">
                            <i class="fa fa-close"></i>
                        </button>
                        <img class="element-center" id="attachment-view" src="">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

    <script type="text/javascript">
        cartQuantityInitialize();
        getVariantPrice();
        $('#add-to-cart-form input').on('change', function () {
            getVariantPrice();
        });

        function showInstaImage(link) {
            $("#attachment-view").attr("src", link);
            $('#show-modal-view').modal('toggle')
        }

        function focus_preview_image_by_color(key) {
            $('a[href="#image' + key + '"]')[0].click();
            // alert('test')
        }
    </script>
    <script>
        $(document).ready(function () {

            $('#city').select2();
            $('#area').select2();

            load_review();
        });
        let load_review_count = 1;

        function load_review() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                type: "post",
                url: '{{route('review-list-product')}}',
                data: {
                    product_id: "{{$product->id}}",
                    offset: load_review_count
                },
                success: function (data) {
                    $('#product-review-list').append(data.productReview)
                    if (data.not_empty == 0 && load_review_count > 2) {
                        toastr.info('{{\App\CPU\translate('no more review remain to load')}}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        console.log('iff');
                    }
                }
            });
            load_review_count++
        }
    </script>

    {{-- Messaging with shop seller --}}
    <script>
        $('#contact-seller').on('click', function (e) {
            // $('#seller_details').css('height', '200px');
            $('#seller_details').animate({'height': '276px'});
            $('#msg-option').css('display', 'block');
        });
        $('#sendBtn').on('click', function (e) {
            e.preventDefault();
            let msgValue = $('#msg-option').find('textarea').val();
            let data = {
                message: msgValue,
                shop_id: $('#msg-option').find('textarea').attr('shop-id'),
                seller_id: $('.msg-option').find('.seller_id').attr('seller-id'),
            }
            if (msgValue != '') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "post",
                    url: '{{route('messages_store')}}',
                    data: data,
                    success: function (respons) {
                        console.log('send successfully');
                    }
                });
                $('#chatInputBox').val('');
                $('#msg-option').css('display', 'none');
                $('#contact-seller').find('.contact').attr('disabled', '');
                $('#seller_details').animate({'height': '125px'});
                $('#go_to_chatbox').css('display', 'block');
            } else {
                console.log('say something');
            }
        });
        $('#cancelBtn').on('click', function (e) {
            e.preventDefault();
            $('#seller_details').animate({'height': '114px'});
            $('#msg-option').css('display', 'none');
        });
    </script>

    <script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=64c7a559c094360012b348a0&product=inline-share-buttons&source=platform" async="async"></script>

@endpush
