@php($overallRating = \App\CPU\ProductManager::get_overall_rating($product->reviews))

<div class="product-single-hover">
    <div class="overflow-hidden position-relative">
        <div class=" inline_product clickable d-flex justify-content-center text-center"
             style="cursor: pointer;background:{{$web_config['primary_color']}}10;border-radius: 5px 5px 0px 0px;">
            @if($product->discount > 0)
                <div class="d-flex" style="left:8px;top:8px;">
                        <span class="for-discoutn-value p-1 pl-2 pr-2 bold" style="left: 0" dir="rtl">
                            {{\App\CPU\translate('Discount')}} -
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
                        <div class="rating-show justify-content-between text-center">
                            <span class="d-inline-block font-size-sm text-body">
                                @for($inc=0;$inc<5;$inc++)
                                    @if($inc<$overallRating[0])
                                        <i class="sr-star czi-star-filled active"></i>
                                    @else
                                        <i class="sr-star czi-star" style="color:#fea569 !important"></i>
                                    @endif
                                @endfor
                                <label class="badge-style">( {{$product->reviews_count}} )</label>
                            </span>
                        </div>
            <div class="justify-content-between text-center">
                <div class="product-price text-center">
                    {{--                    @if($product->discount > 0)--}}
                    {{--                        <strike style="font-size: 12px!important;color: #E96A6A!important;">--}}
                    {{--                            {{\App\CPU\Helpers::currency_converter($product->unit_price)}}--}}
                    {{--                        </strike><br>--}}
                    {{--                    @endif--}}
                    <span class="second_color bold s_14 d-block"
                          style="font-size: 11px!important;">{{\App\CPU\translate('Delivery in 1 Day')}}</span>


                    @php($qty = 0)
                    @foreach(json_decode($product['variation']) as $item)
                        @php($qty += $item->qty)
                    @endforeach


                    @if($qty < 1)
                        <span class="second_color bold s_14 d-block" style="font-size: 11px!important;">

                        {{\App\CPU\translate('Out of Stock')}}

                    </span>
                    @else
                        @if(session('direction') == 'rtl')
                            <span class="second_color bold s_14 d-block" style="font-size: 11px!important;">
                        متبقي
                        <span class="num_fam" style="font-size: 11px!important;">

                            {{$qty}}

                        </span>
                            في المخزون
                        </span>
                        @else
                            <span class="second_color bold s_14 d-block" style="font-size: 11px!important;">


                            {{$qty}}
                                Pieces Left
                        </span>
                        @endif
                    @endif

                    <span class="bold s_15">
                        {{\App\CPU\Helpers::currency_converter($product['purchase_price']-(\App\CPU\Helpers::get_product_discount($product,$product['purchase_price'])))}}
                    </span>
                    @if($product['discount'] > 0)

                        <span
                            class="pre_price primary_color s_16 bold num_fam">{{\App\CPU\Helpers::currency_converter($product['purchase_price'])}}</span>
                    @endif
                </div>
            </div>

        </div>
        <div class="text-center quick-view">
            <div class="text-center quick-view">
                @if(Request::is('product/*'))
{{--                    <a class="btn btn--primary btn-sm" style="background: #ed165f!important"--}}
{{--                       href="{{route('product',$product->slug)}}">--}}
{{--                        <i class="czi-forward align-middle "></i>--}}

{{--                    </a>--}}
                @else
                    <a class="btn btn--primary btn-sm"
                       style="background:#ed165f!important; border-color: #ed165f!important; margin-top:0;padding: 5px 10px 0;"
                       href="javascript:"
                       onclick="quickView('{{$product->id}}')">
                        <i class="czi-eye align-middle "></i>

                    </a>
                @endif
            </div>
        </div>
    </div>
</div>


