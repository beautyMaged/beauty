@php($overallRating = \App\CPU\ProductManager::get_overall_rating($product->reviews))

<div class="product-single-hover">
    <div class="overflow-hidden position-relative">
        <div class=" inline_product clickable d-flex justify-content-center text-center"
             style="cursor: pointer;background:{{$web_config['primary_color']}}10;border-radius: 5px 5px 0px 0px;">
            @if($product->discount > 0)
                <div class="d-flex" style="left:8px;top:8px;">
                        <span class="for-discoutn-value p-1 pl-2 pr-2 bold" style="left: 0" dir="rtl">
                            تخفيض -
                        <span class="num_fam">
                            @if ($product->discount_type == 'percent')
                                {{round($product->discount, (!empty($decimal_point_settings) ? $decimal_point_settings: 0))}}%
                            @elseif($product->discount_type =='flat')
                                {{\App\CPU\Helpers::currency_converter($product->discount)}}
                            @endif
                        </span>

                        </span>
                </div>
            @else
                <div class="d-flex justify-content-end for-dicount-div-null">
                    <span class="for-discoutn-value-null"></span>
                </div>
            @endif
            <div class="d-flex d-block">
                <a href="{{route('product',$product->slug)}}">
                    <img src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                         onerror="this.onerror=null;this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'">
                </a>
            </div>
        </div>
        <div class="single-product-details">
            <div class="text-center bold" style="color: #818c91">
{{--                <span>{{$brand_name}}</span>--}}
            </div>
            <div class="text-center bold">
                <a href="{{route('product',$product->slug)}}">
                    {{ Str::limit($product['name'], 23) }}
                </a>
            </div>
            {{--            <div class="rating-show justify-content-between text-center">--}}
            {{--                <span class="d-inline-block font-size-sm text-body">--}}
            {{--                    @for($inc=0;$inc<5;$inc++)--}}
            {{--                        @if($inc<$overallRating[0])--}}
            {{--                            <i class="sr-star czi-star-filled active"></i>--}}
            {{--                        @else--}}
            {{--                            <i class="sr-star czi-star" style="color:#fea569 !important"></i>--}}
            {{--                        @endif--}}
            {{--                    @endfor--}}
            {{--                    <label class="badge-style">( {{$product->reviews_count}} )</label>--}}
            {{--                </span>--}}
            {{--            </div>--}}
            <div class="justify-content-between text-center">
                <div class="product-price text-center">
                                        @if($product->discount > 0)
                                            <strike style="font-size: 12px!important;color: #E96A6A!important;">
                                                {{\App\CPU\Helpers::currency_converter($product->unit_price)}}
                                            </strike><br>
                                        @endif
                    <span class="second_color bold s_14 d-block">التوصيل في اليوم التالي</span>
                    <span class="second_color bold s_14 d-block">
                        متبقي
                        <span class="num_fam">
                            9
                        </span>
                        في المخزون
                    </span>
                    <span class="bold s_15 d-block" style="color: #1A3945">
                        <span class="num_fam">1000.00 ريال</span>
                    </span>
                    {{--                    {{dd($product->sub)}}--}}
                    {{--                    <span class="text-accent">--}}
                    {{--                        {{\App\CPU\Helpers::currency_converter(--}}
                    {{--                            $product->unit_price-(\App\CPU\Helpers::get_product_discount($product,$product->unit_price))--}}
                    {{--                        )}}--}}
                    {{--                    </span>--}}
                </div>
            </div>

        </div>
        <div class="text-center quick-view">
            @if(Request::is('product/*'))
                <a class="btn btn--primary btn-sm bold" style="background: #ed165f!important" href="{{route('product',$product->slug)}}">
                    عرض
                    <i class="czi-forward align-middle {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}"></i>

                </a>
            @else
                <a class="btn btn--primary btn-sm bold"
                   style="background:#ed165f!important; border-color: #ed165f!important; margin-top:0px;padding-top:5px;padding-bottom:5px;padding-left:10px;padding-right:10px;" href="javascript:"
                   onclick="quickView('{{$product->id}}')">
                    <i class="czi-eye align-middle {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}"></i>
{{--                    {{\App\CPU\translate('Quick')}}   {{\App\CPU\translate('View')}}--}}
                    عرض سريع
                </a>
            @endif
        </div>
    </div>
</div>


@foreach($latest_products as $chunks)
    <div class="row slider_products_item" style="height: 670px" dir="ltr">

        @foreach($chunks as $key => $chunk_products)
            @php
                $large = $chunk_products[0];
                $normals = array_shift($chunk_products->toArray());
            @endphp
            <div class="col-xxl-5 col-xl-5 col-md-5 col-sm-11 col-12 m-auto   large-featured-product text-center"

                <div class=" row" style="    width: calc(100% - 0px);
                                        border: solid 1px rgba(0, 0, 0, 0.3);
                                        height: 100%;
                                        border-radius: 10px;margin-bottom: 5px">
                    <div class="real_image_container col-lg-12 col-md-12">
                        @foreach($large->images as $k_img => $large_image)
                            <img src="{{asset('storage/product/' . $large_image)}}"
                                 data-order="{{$k_img}}"
                                 alt="product"
                                 class="w-100 px-4 pt-3 {{$large->id}}_{{$k_img}}_img product_image">
                        @endforeach
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <div class="carousel slide" id="product_images_slider"
                             dir="ltr" data-pause="true" data-interval="400"
                             style="display: none">
                            <div class="carousel-inner">
                                @foreach(json_decode($large->images) as $key_image => $large_image)
                                    <div
                                        class="carousel-item {{$key_image=0?'active': ''}}">
                                        <img
                                            src="{{asset('assets/front-end/img/product-1.png')}}"
                                            data-order="{{$k}}"
                                            alt="product" class="w-100 px-4 pt-3">
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <a href="#"><h4 class="s_27 primary_color bold">
                                {{$large->name}}
                            </h4></a>
                    </div>
                    @php($overallRating = \App\CPU\ProductManager::get_overall_rating($large->reviews))

                    <div class="col-lg-12 col-md-12">
                        <div class="rate">

                            @for($inc=0;$inc<5;$inc++)
                                @if($inc<$overallRating[0])
                                    <i class="p-0 sr-star czi-star-filled active"></i>
                                @else
                                    <i class="p-0 sr-star czi-star"
                                       style="color:#fea569 !important"></i>
                                @endif
                            @endfor
                            <label class="badge-style">( {{$large->reviews_count}})</label>
                        </div>



                    </div>

                    <div class="col-lg-12 col-md-12">
                        <div>
                            <span class="real_price s_16 bold ">
                                {{\App\CPU\Helpers::currency_converter($product->unit_price-(\App\CPU\Helpers::get_product_discount($large,$large->unit_price)))}}
                            </span>
                            @if($product->discount > 0)

                                <span class="pre_price primary_color s_16 bold num_fam">{{\App\CPU\Helpers::currency_converter($large->unit_price)}}</span>
                            @endif
                        </div>
                    </div>
                    {{--                                                        <div class="mt-3 col-lg-12 col-md-12">--}}
                    {{--                                                            <ul class="list-inline checkbox-color mb-1 flex-start ml-2"--}}
                    {{--                                                                style="padding-left: 0;" dir="ltr">--}}

                    {{--                                                                <li>--}}
                    {{--                                                                    <input type="radio" id="9_1-color-9966CC"--}}
                    {{--                                                                           name="color_9" value="#9966CC" checked="">--}}
                    {{--                                                                    <label style="background: #9966CC;"--}}
                    {{--                                                                           data-target="9_1" for="9_1-color-9966CC"--}}
                    {{--                                                                           class="color-changer" data-toggle="tooltip"--}}
                    {{--                                                                           onclick="focus_preview_image_by_color('9966CC')">--}}
                    {{--                                                                        <span class="outline"></span></label>--}}
                    {{--                                                                </li>--}}


                    {{--                                                                <li>--}}
                    {{--                                                                    <input type="radio" id="9_2-color-00FFFF"--}}
                    {{--                                                                           name="color_9" value="#00FFFF">--}}
                    {{--                                                                    <label style="background: #00FFFF;"--}}
                    {{--                                                                           data-target="9_2" for="9_2-color-00FFFF"--}}
                    {{--                                                                           class="color-changer" data-toggle="tooltip"--}}
                    {{--                                                                           onclick="focus_preview_image_by_color('00FFFF')">--}}
                    {{--                                                                        <span class="outline"></span></label>--}}
                    {{--                                                                </li>--}}


                    {{--                                                                <li>--}}
                    {{--                                                                    <input type="radio" id="9_3-color-000"--}}
                    {{--                                                                           name="color_9" value="#000">--}}
                    {{--                                                                    <label style="background: #000;" data-target="9_3"--}}
                    {{--                                                                           for="9_3-color-000" class="color-changer"--}}
                    {{--                                                                           data-toggle="tooltip"--}}
                    {{--                                                                           onclick="focus_preview_image_by_color('000')">--}}
                    {{--                                                                        <span class="outline"></span></label>--}}
                    {{--                                                                </li>--}}

                    {{--                                                            </ul>--}}
                    {{--                                                        </div>--}}
                </div>

            </div>

        @endforeach
    </div>
@endforeach




