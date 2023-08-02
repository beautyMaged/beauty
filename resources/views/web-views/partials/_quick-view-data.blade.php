@php
    $overallRating = \App\CPU\ProductManager::get_overall_rating($product->reviews);
    $rating = \App\CPU\ProductManager::get_rating($product->reviews);
    $productReviews = \App\CPU\ProductManager::get_product_review($product->id);
@endphp

<style>
    .product-title2 {
        font-family: 'Roboto', sans-serif !important;
        font-weight: 400 !important;
        font-size: 22px !important;
        color: #000000 !important;
        position: relative;
        display: inline-block;
        word-wrap: break-word;
        overflow: hidden;
        max-height: 1.2em; /* (Number of lines you want visible) * (line-height) */
        line-height: 1.2em;
    }
    .g-3 *[class*="col"] {
        padding:0!important;
    }
    .cz-product-gallery {
        display: block;
    }

    .cz-preview {
        width: 100%;
        margin-top: 0;
        margin- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 0;
        max-height: 100% !important;
    }

    .cz-preview-item > img {
        width: 80%;
    }

    .details {
        border: 1px solid #E2F0FF;
        border-radius: 3px;
        padding: 16px;
    }

    img, figure {
        max-width: 100%;
        vertical-align: middle;
    }

    .cz-thumblist-item {
        display: block;
        position: relative;
        width: 64px;
        height: 64px;
        margin: .625rem;
        transition: border-color 0.2s ease-in-out;
        border: 1px solid #E2F0FF;
        border-radius: .3125rem;
        text-decoration: none !important;
        overflow: hidden;
    }

    .for-hover-bg {
        font-size: 18px;
        height: 45px;
    }

    .cz-thumblist-item > img {
        display: block;
        width: 80%;
        transition: opacity .2s ease-in-out;
        max-height: 58px;
        opacity: .6;
    }

    @media (max-width: 767.98px) and (min-width: 576px) {
        .cz-preview-item > img {
            width: 100%;
        }
    }

    @media (max-width: 575.98px) {
        .cz-thumblist {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            -ms-flex-pack: center;
            justify-content: center;
            margin- {{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 0;
            padding-top: 1rem;
            padding-right: 22px;
            padding-bottom: 10px;
        }

        .cz-thumblist-item {
            margin: 0px;
        }

        .cz-thumblist {
            padding-top: 8px !important;
        }

        .cz-preview-item > img {
            width: 100%;
        }
    }
</style>

<div class="modal-header " dir="rtl">
    <div>
        <h4 class="modal-title product-title">
            <a class="product-title2" href="{{route('product',$product->slug)}}" data-toggle="tooltip"
               data-placement="right"
               title="Go to product page">
                <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left mr-2' : 'right ml-2'}} font-size-lg"
                   style="margin-right: 0px !important;"></i>
                {{$product['name']}}
            </a>
        </h4>
    </div>
    <div>
        <button class="close call-when-done" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>

<div class="modal-body rtl">
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="cz-product-gallery">
                <div class="cz-preview">
                    @if($product->images!=null && json_decode($product->images)>0)
                        @if(json_decode($product->colors) && $product->color_image)
                            @foreach (json_decode($product->color_image) as $key => $photo)
                                @if($photo->color != null)
                                    <div
                                        class="cz-preview-item d-flex align-items-center justify-content-center  {{$key==0?'active':''}}">
                                        <img class="show-imag img-responsive" style="max-height: 500px!important;"
                                             onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                             src="{{asset("storage/product/$photo->image_name")}}"
                                             alt="Product image" width="">
                                    </div>
                                @else
                                    <div
                                        class="cz-preview-item d-flex align-items-center justify-content-center  {{$key==0?'active':''}}">
                                        <img class="show-imag img-responsive" style="max-height: 500px!important;"
                                             onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                             src="{{asset("storage/product/$photo->image_name")}}"
                                             alt="Product image" width="">
                                    </div>
                                @endif
                            @endforeach
                        @else
                            @foreach (json_decode($product->images) as $key => $photo)
                                <div
                                    class="cz-preview-item d-flex align-items-center justify-content-center  {{$key==0?'active':''}}">
                                    <img class="show-imag img-responsive" style="max-height: 500px!important;"
                                         onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                         src="{{asset("storage/product/$photo")}}"
                                         alt="Product image" width="">
                                </div>
                            @endforeach
                        @endif
                    @endif
                </div>
                <div class="table-responsive" style="max-height: 515px;">
                    <div class="d-flex">
                        @if($product->images!=null && json_decode($product->images)>0)
                            @if(json_decode($product->colors) && $product->color_image)
                                @foreach (json_decode($product->color_image) as $key => $photo)
                                    @if($photo->color != null)
                                        <div class="cz-thumblist">
                                            <a href="javascript:"
                                               class=" cz-thumblist-item d-flex align-items-center justify-content-center">
                                                <img class="click-img" id="preview-img{{$photo->color}}"
                                                     src="{{asset("storage/product/$photo->image_name")}}"
                                                     onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                                     alt="Product thumb">
                                            </a>
                                        </div>
                                    @else
                                        <div class="cz-thumblist">
                                            <a href="javascript:"
                                               class=" cz-thumblist-item d-flex align-items-center justify-content-center">
                                                <img class="click-img" id="preview-img{{$key}}"
                                                     src="{{asset("storage/product/$photo->image_name")}}"
                                                     onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                                     alt="Product thumb">
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                @foreach (json_decode($product->images) as $key => $photo)
                                    <div class="cz-thumblist">
                                        <a href="javascript:"
                                           class=" cz-thumblist-item d-flex align-items-center justify-content-center">
                                            <img class="click-img" id="preview-img{{$key}}"
                                                 src="{{asset("storage/product/$photo")}}"
                                                 onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
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

        <!-- Product details-->
        <div class="col-lg-6 col-md-6 col-12 mt-md-0 mt-sm-3"
             style="direction: {{ Session::get('direction') }}">
            <div class="details __h-100 text-right pt-4">
                <span class="mb-2 __inline-24 s_30">{{$product->name}}</span>
                <div class="d-flex flex-wrap mb-2 pro text-right mt-1" dir="rtl">

                </div>
                <div class="row tex-right mt-3" dir="rtl">
                    <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-sm-4 col-3">
                        <span class="bold s_19">السعر :</span>
                    </div>
                    <div class="col-xxl-9 col-xl-9 col-lg-9 col-md-9 col-sm-8 col-9" dir="ltr">
                        @if($product->discount > 0)
                            <strike style="color: #E96A6A;"
                                    class="{{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-3'}}"
                                    dir="rtl">
                                {{\App\CPU\Helpers::currency_converter($product->unit_price)}}
                            </strike>
                        @endif
                        <span class="bold num_fam s_19" style="color: #ED165F" dir="rtl">
                                            {{\App\CPU\Helpers::get_price_range($product) }}
                                        </span>
                    </div>
                </div>
                @if($product->discount > 0)
                    <div class="row tex-right mt-3" dir="rtl">
                        <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-sm-4 col-3">
                            <span class="bold s_19">{{\App\CPU\translate('discount')}} :</span>
                        </div>
                        <div class="col-xxl-9 col-xl-9 col-lg-9 col-md-9 col-sm-8 col-9" dir="ltr">

                        <div><strong id="set-discount-amount" class="mx-2" dir="rtl"></strong></div>
                        </div>
                    </div>
                @endif
                <div class="row tex-right mt-3" dir="rtl">
                    <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-sm-4 col-3">
                        <span class="bold s_19">{{\App\CPU\translate('tax')}} :</span>
                    </div>
                    <div class="col-xxl-9 col-xl-9 col-lg-9 col-md-9 col-sm-8 col-9" dir="ltr">

                        <div><strong id="set-tax-amount" class="mx-2" dir="rtl"></strong></div>
                    </div>
                </div>
                {{--                                Colors--}}

                <form id="add-to-cart-form" class="mb-2">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <div
                        class="position-relative {{Session::get('direction') === "rtl" ? 'ml-n4' : 'mr-n4'}} mb-2">
                        @if (count(json_decode($product->colors)) > 0)

                            <div class="row tex-right py-1 mt-3" dir="rtl">

                                <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-sm-4 col-3">
                                    <span class="bold s_19">اللون :</span>
                                </div>
                                <div class="col-xxl-9 col-xl-9 col-lg-9 col-md-9 col-sm-8 col-9">
                                    <ul class="list-inline checkbox-color mb-1 ml-2"
                                        style="padding-left: 0;" dir="ltr">

                                        @foreach (json_decode($product->colors) as $key => $color)
                                            <li>
                                                <input type="radio"
                                                       id="{{ $product->id }}-color-{{ str_replace('#','',$color) }}"
                                                       name="color" value="{{ $color }}"
                                                       @if($key == 0) checked @endif>
                                                <label style="background: {{ $color }};"
                                                       for="{{ $product->id }}-color-{{ str_replace('#','',$color) }}"
                                                       data-toggle="tooltip"
                                                       onclick="quick_view_preview_image_by_color('{{ str_replace('#','',$color) }}')">
                                                    <span class="outline" style="border-color: {{ $color }}"></span>
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
                        <div class="row tex-right mt-1" dir="rtl">
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
                                                <label class="__text-12px"
                                                       for="{{ $choice->name }}-{{ $option }}">{{ $option }}</label>
                                            </li>
                                        </div>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach

                <!-- Quantity + Add to cart -->
                    <div class="row tex-right mt-3" dir="rtl">
                        <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-sm-4 col-3">
                            <span class="bold s_19">الكمية :</span>
                        </div>
                        <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-8 col-4">
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

                    <div class="row tex-right mt-3" dir="rtl">
                        <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-sm-4 col-3">
                            <span class="bold s_19">الإجمالي :</span>
                        </div>
                        <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-8 col-4">
                            <div id="chosen_price_div"
                                 class="col-xxl-5 col-xl-5 col-lg-5 col-md-5 col-sm-8 col-5">
                                <div class="product-description-label"> <strong
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

                    <div class="row no-gutters mt-2 flex-start d-flex">
                        <div class="col-12 col-lg-12">
                            @if(($product['product_type'] == 'physical') && ($product['current_stock']<=0))
                                <h5 class="mt-3 text-danger">{{\App\CPU\translate('out_of_stock')}}</h5>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-4" dir="rtl">

                        @if(($product->added_by == 'seller' && ($seller_temporary_close || (isset($product->seller->shop) && $product->seller->shop->vacation_status && $current_date >= $seller_vacation_start_date && $current_date <= $seller_vacation_end_date))) ||
                             ($product->added_by == 'admin' && ($inhouse_temporary_close || ($inhouse_vacation_status && $current_date >= $inhouse_vacation_start_date && $current_date <= $inhouse_vacation_end_date))))

                            <div class="col-xxl-9 col-xl-9 col-lg-9 col-md-9 col-sm-6 col-9">
                                <button class="bold s_22 w-100" disabled type="button"
                                        style="border: 1px solid #FF7C86; color: #FF7C86">أضف للسلة
                                </button>
                            </div>
                        @else

                            <div class="col-xxl-9 col-xl-9 col-lg-9 col-md-9 col-sm-6 col-9">
                                <button class="bold s_22 w-100" onclick="addToCart()" type="button"
                                        style="border: 1px solid #FF7C86;
    color: #FF7C86;
    background: #fff;
    border-radius: 8px;
    font-size: 18px;    padding: 3px;">أضف للسلة
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
                <div class="row mt-3" dir="rtl">
                    <div
                        class="col-lg-10 col-md-10 col-sm-10 col-12 s_15 instructions bold px-3 pt-1 pb-2">
                        <h5 style="color: #000" class="s_15 mb-0 pt-2 bold">الشحن مجانا</h5>
                        <span class="d-block" style="color: #767676">
                                            شحن قياسي مجاني للطلبات التي تزيد عن <span class="num_fam">9.00</span> ريال
                                        </span>
                        <span class="d-block" style="color: #767676">
                                            تاريخ التسليم المقدّر في <span
                                class="num_fam"> 21/03/2023 - 24/03/2023.</span>
                                        </span>
                        <h5 style="color: #000" class="s_11 mb-0 pt-2 bold">سياسة الترجيع
                        </h5>
                        <span class="d-block" style="color: #767676">
                                            شحن قياسي مجاني للطلبات التي تزيد عن <span class="num_fam">9.00</span> ريال
                                        </span>
                        <a class="d-block" href="#" style="color: #767676">
                            اعرف أكثر
                        </a>
                    </div>
                </div>
                <div class="row mt-3" dir="rtl">
                    <div
                        class="col-lg-10 col-md-10 col-sm-10 col-12 installments bold px-3 pt-1 pb-2">
                        <h5 class="primary_color s_16 mb-2 pt-2 bold">نظام التقسيط</h5>
                        <img src="{{asset('assets/front-end/img/tamara.png')}}" alt="tamara" class=""
                             style="width: 82px;">
                        <img src="{{asset('assets/front-end/img/tabby.png')}}" alt="tamara" class=""
                             style="width:68px">
                        <span class="s_16 bold">
                                            يمكنك الان استخدام نظام التقسيم المريح فقط من خلال موقع بيوتي سنتر
                                        </span>
                        <a class="s_16 bold primary_color" href="#">
                            للمزيد من المعلومات
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
        <!-- Product details-->
{{--        <div class="col-lg-6 col-md-6">--}}
{{--            <div class="details __h-100">--}}
{{--                <a href="{{route('product',$product->slug)}}" class="h3 mb-2 product-title">{{$product->name}}</a>--}}
{{--                <div class="d-flex flex-wrap align-items-center mb-2 pro">--}}
{{--                    <div class="d-flex flex-wrap align-items-center">--}}
{{--                        <span--}}
{{--                            class="d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "rtl" ? 'ml-2 pl-2' : 'mr-2 pr-2'}}">{{$overallRating[0]}}</span>--}}
{{--                        <div class="star-rating">--}}
{{--                            @for($inc=0;$inc<5;$inc++)--}}
{{--                                @if($inc<$overallRating[0])--}}
{{--                                    <i class="sr-star czi-star-filled active"></i>--}}
{{--                                @else--}}
{{--                                    <i class="sr-star czi-star"></i>--}}
{{--                                @endif--}}
{{--                            @endfor--}}
{{--                        </div>--}}
{{--                        <span--}}
{{--                            class="d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "rtl" ? 'ml-2 mr-1' : 'ml-1 mr-2'}} pl-2 pr-2">{{$overallRating[1]}} {{\App\CPU\translate('reviews')}}</span>--}}
{{--                        <span style="width: 0px;height: 10px;border: 0.5px solid #707070; margin-top: 6px"></span>--}}
{{--                        <span--}}
{{--                            class="d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "rtl" ? 'ml-2 mr-1' : 'ml-1 mr-2'}} pl-2 pr-2">{{$countOrder}} {{\App\CPU\translate('orders')}}  </span>--}}
{{--                        <span style="width: 0px;height: 10px;border: 0.5px solid #707070; margin-top: 6px">    </span>--}}
{{--                        <span--}}
{{--                            class="d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "rtl" ? 'ml-2 mr-1' : 'ml-1 mr-2'}} pl-2 pr-2">  {{$countWishlist}}  {{\App\CPU\translate('wishlist')}}</span>--}}

{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="mb-3">--}}
{{--                    <span--}}
{{--                        class="h3 font-weight-normal text-accent {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}">--}}
{{--                        {{\App\CPU\Helpers::get_price_range($product) }}--}}
{{--                    </span>--}}
{{--                    @if($product->discount > 0)--}}
{{--                        <strike style="font-size: 12px!important;color: grey!important;">--}}
{{--                            {{\App\CPU\Helpers::currency_converter($product->unit_price)}}--}}
{{--                        </strike>--}}
{{--                    @endif--}}
{{--                </div>--}}

{{--                @if($product->discount > 0)--}}
{{--                    <div class="flex-start mb-3">--}}
{{--                        <div><strong>{{\App\CPU\translate('discount')}} : </strong></div>--}}
{{--                        <div><strong id="set-discount-amount" class="mx-2"></strong></div>--}}
{{--                    </div>--}}
{{--                @endif--}}

{{--                <div class="flex-start mb-3">--}}
{{--                    <div><strong>{{\App\CPU\translate('tax')}} : </strong></div>--}}
{{--                    <div><strong id="set-tax-amount" class="mx-2"></strong></div>--}}
{{--                </div>--}}

{{--                <form id="add-to-cart-form" class="mb-2">--}}
{{--                    @csrf--}}
{{--                    <input type="hidden" name="id" value="{{ $product->id }}">--}}
{{--                    <div class="position-relative {{Session::get('direction') === "rtl" ? 'ml-n4' : 'mr-n4'}} mb-3">--}}
{{--                        @if (count(json_decode($product->colors)) > 0)--}}
{{--                            <div class="flex-start">--}}
{{--                                <div class="product-description-label mt-1">--}}
{{--                                    {{\App\CPU\translate('color')}}:--}}
{{--                                </div>--}}
{{--                                <div class="__pl-15">--}}
{{--                                    <ul class="flex-start checkbox-color mb-0 p-0" style="list-style: none;">--}}
{{--                                        @foreach (json_decode($product->colors) as $key => $color)--}}
{{--                                            <li>--}}
{{--                                                <input type="radio"--}}
{{--                                                       id="{{ $product->id }}-color-{{ str_replace('#','',$color) }}"--}}
{{--                                                       name="color" value="{{ $color }}"--}}
{{--                                                       @if($key == 0) checked @endif>--}}
{{--                                                <label style="background: {{ $color }};"--}}
{{--                                                       for="{{ $product->id }}-color-{{ str_replace('#','',$color) }}"--}}
{{--                                                       data-toggle="tooltip"--}}
{{--                                                       onclick="quick_view_preview_image_by_color('{{ str_replace('#','',$color) }}')">--}}
{{--                                                    <span class="outline" style="border-color: {{ $color }}"></span>--}}
{{--                                                </label>--}}
{{--                                            </li>--}}
{{--                                        @endforeach--}}
{{--                                    </ul>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                        @php--}}
{{--                            $qty = 0;--}}
{{--                            foreach (json_decode($product->variation) as $key => $variation) {--}}
{{--                                $qty += $variation->qty;--}}
{{--                            }--}}
{{--                        @endphp--}}

{{--                    </div>--}}
{{--                    @foreach (json_decode($product->choice_options) as $key => $choice)--}}
{{--                        <div class="flex-start">--}}
{{--                            <div class="product-description-label mt-1">--}}
{{--                                {{ $choice->title }}:--}}
{{--                            </div>--}}
{{--                            <div>--}}
{{--                                <ul class=" checkbox-alphanumeric checkbox-alphanumeric--style-1 mb-2">--}}
{{--                                    @foreach ($choice->options as $key => $option)--}}
{{--                                        <span>--}}
{{--                                            <input type="radio"--}}
{{--                                                   id="{{ $choice->name }}-{{ $option }}"--}}
{{--                                                   name="{{ $choice->name }}" value="{{ $option }}"--}}
{{--                                                   @if($key == 0) checked @endif>--}}
{{--                                            <label for="{{ $choice->name }}-{{ $option }}">{{ $option }}</label>--}}
{{--                                        </span>--}}
{{--                                    @endforeach--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    @endforeach--}}

{{--                    <!-- Quantity + Add to cart -->--}}
{{--                    <div class="d-flex __gap-6 mt-0">--}}
{{--                        <div class="product-description-label mt-2 mr-2">{{\App\CPU\translate('Quantity')}}:</div>--}}
{{--                        <div class="product-quantity d-flex align-items-center">--}}
{{--                            <div class="input-group input-group--style-2 pr-3"--}}
{{--                                 style="width: 160px;">--}}
{{--                                <span class="input-group-btn">--}}
{{--                                    <button class="btn btn-number" type="button"--}}
{{--                                            data-type="minus" data-field="quantity"--}}
{{--                                            disabled="disabled" style="padding: 10px">--}}
{{--                                        ---}}
{{--                                    </button>--}}
{{--                                </span>--}}
{{--                                <input type="text" name="quantity"--}}
{{--                                       class="form-control input-number text-center cart-qty-field"--}}
{{--                                       placeholder="1" value="{{ $product->minimum_order_qty ?? 1 }}"--}}
{{--                                       product-type="{{ $product->product_type }}"--}}
{{--                                       min="{{ $product->minimum_order_qty ?? 1 }}" max="100">--}}
{{--                                <span class="input-group-btn">--}}
{{--                                    <button class="btn btn-number" product-type="{{ $product->product_type }}"--}}
{{--                                            type="button" data-type="plus"--}}
{{--                                            data-field="quantity" style="padding: 10px">--}}
{{--                                        +--}}
{{--                                    </button>--}}
{{--                                </span>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="d-flex flex-wrap mt-3 __gap-15" id="chosen_price_div">--}}
{{--                        <div>--}}
{{--                            <div class="product-description-label">{{\App\CPU\translate('Total Price')}}:</div>--}}
{{--                        </div>--}}
{{--                        <div>--}}
{{--                            <div class="product-price">--}}
{{--                                <strong id="chosen_price"></strong>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-12">--}}
{{--                            @if(($product['product_type'] == 'physical') && ($product['current_stock']<=0))--}}
{{--                                <h5 class="mt-3" style="color: red">{{\App\CPU\translate('out_of_stock')}}</h5>--}}
{{--                            @endif--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    --}}{{--to do--}}
{{--                    <div class="__btn-grp align-items-center mt-2">--}}
{{--                        @if(($product->added_by == 'seller' && ($seller_temporary_close || (isset($product->seller->shop) && $product->seller->shop->vacation_status && $current_date >= $seller_vacation_start_date && $current_date <= $seller_vacation_end_date))) ||--}}
{{--                             ($product->added_by == 'admin' && ($inhouse_temporary_close || ($inhouse_vacation_status && $current_date >= $inhouse_vacation_start_date && $current_date <= $inhouse_vacation_end_date))))--}}
{{--                            <button class="btn btn-secondary" type="button" disabled>--}}
{{--                                {{\App\CPU\translate('buy_now')}}--}}
{{--                            </button>--}}
{{--                            <button class="btn btn--primary string-limit" type="button" disabled>--}}
{{--                                {{\App\CPU\translate('add_to_cart')}}--}}
{{--                            </button>--}}
{{--                        @else--}}
{{--                            <button class="btn btn-secondary" onclick="buy_now()" type="button">--}}
{{--                                {{\App\CPU\translate('buy_now')}}--}}
{{--                            </button>--}}
{{--                            <button class="btn btn--primary string-limit" onclick="addToCart()" type="button">--}}
{{--                                {{\App\CPU\translate('add_to_cart')}}--}}
{{--                            </button>--}}
{{--                        @endif--}}
{{--                        <button type="button" onclick="addWishlist('{{$product['id']}}')"--}}
{{--                                class="text-danger btn string-limit">--}}
{{--                            <i class="fa fa-heart-o mr-2"--}}
{{--                               aria-hidden="true"></i>--}}
{{--                            <span class="countWishlist-{{$product['id']}}">{{$countWishlist}}</span>--}}
{{--                        </button>--}}

{{--                        @if(($product->added_by == 'seller' && ($seller_temporary_close || (isset($product->seller->shop) && $product->seller->shop->vacation_status && $current_date >= $seller_vacation_start_date && $current_date <= $seller_vacation_end_date))) ||--}}
{{--                             ($product->added_by == 'admin' && ($inhouse_temporary_close || ($inhouse_vacation_status && $current_date >= $inhouse_vacation_start_date && $current_date <= $inhouse_vacation_end_date))))--}}
{{--                            <div class="alert alert-danger" role="alert">--}}
{{--                                {{\App\CPU\translate('this_shop_is_temporary_closed_or_on_vacation._You_cannot_add_product_to_cart_from_this_shop_for_now')}}--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--                <!-- Product panels-->--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>
</div>
<script type="text/javascript">
    cartQuantityInitialize();
    getVariantPrice();
    $('#add-to-cart-form input').on('change', function () {
        getVariantPrice();
    });

    $(document).ready(function () {

        $('[data-toggle="tooltip"]').tooltip(), $('[data-toggle="popover"]').popover()

        $('.click-img').click(function () {
            var idimg = $(this).attr('id');
            var srcimg = $(this).attr('src');
            $(".show-imag").attr('src', srcimg);
        });
    });

    function quick_view_preview_image_by_color(key) {
        let id = $('#preview-img' + key);
        $(id).click();
    }
</script>
