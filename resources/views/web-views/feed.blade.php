@if ($products->count() > 0 && $products->first()->count() > 6)
    <section class="featured_subs" dir="rtl">
        <div class="container" style="">
            <div class="row" dir="{{ session('direction') }}">
                <div class="col-md-2 col-6">
                    <h4 class="main_title  bold s_25 mt-5 {{ session('direction') == 'rtl' ? 'mr-5' : 'ml-5' }}">
                        {{ $category->name }} </h4>
                </div>
                <div class="col-md-8 col-12 small-bann">
                    <img src="{{ asset('assets/front-end/img/aaa.webp') }}" alt="" class="img-fluid"
                        style="margin-top: 10px;">
                </div>
                <div class="col-md-2 col-6">
                    <div class="sorting_div s_12 bold mb-1 mt-5" dir="ltr">
                        <div class="w-100 text-right">
                            <span class="bold sort-span" dir="rtl">{{ \App\CPU\translate('sort_by') }} :
                                <span>{{ \App\CPU\translate('Most Favourit') }}</span> &nbsp;&nbsp;<i
                                    class="fa-solid fa-chevron-down s_12"></i></span>
                        </div>
                        <div
                            class="sorting_list w-100 {{ session('direction') == 'rtl' ? 'text-right' : 'text-left' }}">
                            <span class="sort-item d-block px-2 active"
                                data-value="{{ route('products', ['id' => $category->id, 'data_from' => 'most-favorite', 'page' => 1]) }}">{{ \App\CPU\translate('Most Favourit') }}</span>
                            <span class="sort-item d-block px-2"
                                data-value="{{ route('products', ['id' => $category->id, 'data_from' => 'best-selling', 'page' => 1]) }}">{{ \App\CPU\translate('top_sell_pro') }}</span>
                            <span class="sort-item d-block px-2"
                                data-value="{{ route('products', ['id' => $category->id, 'data_from' => 'category', 'page' => 1, 'sort_by' => 'high-low']) }}">{{ \App\CPU\translate('price_low_high') }}</span>
                            <span class="sort-item d-block px-2"
                                data-value="{{ route('products', ['id' => $category->id, 'data_from' => 'category', 'page' => 1, 'sort_by' => 'low-high']) }}">{{ \App\CPU\translate('price_high_low') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-11 col-xl-11 col-md-11 col-sm-12 col-12 m-auto pt-3">
                    <div class="owl-carousel owl-theme featured_cats" dir="ltr">
                        @if (isset($category->childes) && $category->childes->count() > 0)
                            @foreach ($category->childes as $sub)
                                <div class=" featured_sub_cat m-auto text-center">
                                    <a
                                        href="{{ route('home') }}/products?id={{ $sub->id }}&data_from=category&page=1">
                                        <img src="{{ asset('storage/category/' . $sub->icon) }}" style="border-radius:50%;width:120px;height:120px">
                                        <h5 class="mt-3 bold sub_cat_head primary_color"> {{ $sub->name }}
                                        </h5>
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if (isset($products) && $products->count() > 0)
        <section class="featured_subs_with_products" dir="rtl">
            <div class="container" style="">
                <div class="row" dir="{{ session('direction') }}">
                    <div class="col-xxl-12 col-xl-12 col-md-12 col-sm-12 col-12 m-auto pt-0">
                        <div class="row slider_products_row" style="height: 800px">
                            <div class="col-xxl-10 col-xl-10 col-lg-10 col-md-10 col-sm-12 col-12">
                                <div class="owl-carousel owl-theme products_carousel_container" dir="ltr">
                                    @foreach ($products as $k => $one_chunk)
                                        @if (count($one_chunk) > 6)
                                            @if ($k == 0)
                                                @php($k = 0)
                                            @else
                                                @php($k = $k + 6)
                                            @endif
                                            <div class="row slider_products_item"
                                                style="height: 803px"dir="{{ session('direction') == 'rtl' ? 'ltr' : 'rtl' }}">
                                                <div class="col-xxl-5 col-xl-5 col-md-5 col-sm-11 col-12 m-auto   large-featured-product text-center"
                                                    style="height:97%;">
                                                    <div class=" row">
                                                        @php($images = json_decode($one_chunk[$k]->images))
                                                        <div
                                                            class="real_image_container larg_container col-lg-12 col-md-12">
                                                            @if (count(json_decode($one_chunk[$k]->colors)) > 0)
                                                                @foreach (json_decode($one_chunk[$k]->color_image) as $k_img => $large_image)
                                                                    <img src="{{ asset('storage/product/' . $large_image->image_name) }}"
                                                                        alt="product"
                                                                        style="{{ $k_img == 0 ? 'display:block;' : 'display:none;' }}height:100%"
                                                                        class=" px-1 pt-2 {{ $one_chunk[$k]->id }}_{{ $k_img }}_img product_image">
                                                                @endforeach
                                                            @else
                                                            @php($thumbnail_cdn = json_decode($one_chunk[$k]->thumbnail))
                                                            @if($thumbnail_cdn)
                                                                <img src="{{$thumbnail_cdn->cdn}}"
                                                                    alt="product" style="height:100%"
                                                                    class="w-100 px-1 pt-2 product_image">
                                                            @else
                                                                <img src="{{ asset('storage/product/thumbnail/' . $one_chunk[$k]->thumbnail) }}"
                                                                    alt="product" style="height:100%"
                                                                    class="w-100 px-1 pt-2 product_image">
                                                            @endif
                                                            @endif
                                                        </div>
                                                        <div class="col-lg-12 col-md-12">
                                                            <a href="{{ route('product', $one_chunk[$k]->slug) }}">
                                                                <h4 class="s_27 primary_color bold">
                                                                    {{ $one_chunk[$k]->name }} </h4>
                                                            </a>
                                                        </div>
                                                        @php($overallRating = \App\CPU\ProductManager::get_overall_rating($one_chunk[$k]->reviews))
                                                        <div class="col-lg-12 col-md-12">
                                                            <div class="rate">
                                                                @for ($inc = 0; $inc < 5; $inc++)
                                                                    @if ($inc < $overallRating[0])
                                                                        <i
                                                                            class="p-0 sr-star czi-star-filled active"></i>
                                                                    @else
                                                                        <i class="p-0 sr-star czi-star"
                                                                            style="color:#fea569 !important"></i>
                                                                    @endif
                                                                @endfor
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12">
                                                            <div dir="rtl">
                                                                <span class="real_price s_27 bold ">
                                                                    {{ \App\CPU\Helpers::currency_converter($one_chunk[$k]->unit_price - \App\CPU\Helpers::get_product_discount($one_chunk[$k], $one_chunk[$k]->unit_price)) }}
                                                                </span>
                                                                @if ($one_chunk[$k]->discount > 0)
                                                                    <span
                                                                        class="pre_price primary_color s_27 bold num_fam">{{ \App\CPU\Helpers::currency_converter($one_chunk[$k]->unit_price) }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        {{-- {{dd(json_decode($one_chunk[$k]->colors))}} --}}
                                                        <div class="mt13 col-lg-12 col-md-12">
                                                            <ul class="list-inline checkbox-color mb-1 flex-start ml-2"
                                                                style="padding-left: 0;min-height:37px;"
                                                                dir="ltr">
                                                                @if (count(json_decode($one_chunk[$k]->colors)) > 0)
                                                                    @foreach (json_decode($one_chunk[$k]->colors) as $k_img => $large_image)
                                                                        <li>
                                                                            <input type="radio"
                                                                                id="{{ $one_chunk[$k]->id }}_{{ $k_img }}-color-{{ $large_image }}"
                                                                                name="color_{{ $one_chunk[$k]->id }}"
                                                                                value="#{{ $large_image }}"
                                                                                checked="">
                                                                            <label
                                                                                style="background: {{ $large_image }};"
                                                                                data-target="{{ $one_chunk[$k]->id }}_{{ $k_img }}"
                                                                                for="{{ $one_chunk[$k]->id }}_{{ $k_img }}-color-{{ $large_image }}"
                                                                                class="color-changer"
                                                                                data-toggle="tooltip"
                                                                                onclick="focus_preview_image_by_color('{{ $large_image }}')">
                                                                                <span
                                                                                    class="outline"></span></label>
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
                                                        @foreach ($one_chunk as $small_product)
                                                            <div
                                                                class="col-xxl-4 col-xl-4 col-md-4 col-sm-6 col-6 my-2">
                                                                <div class="row">
                                                                    <div
                                                                        class="col-xxl-11 col-xl-11 col-md-11 col-sm-11 col-11 product_container m-auto">
                                                                        <div class=" row">
                                                                            @php($images = json_decode($small_product->images))
                                                                            <div
                                                                                class="real_image_container col-lg-12 col-md-12">
                                                                                @if (count(json_decode($small_product->colors)) > 0)
                                                                                    @foreach (json_decode($small_product->color_image) as $k_img => $large_image)
                                                                                        <img src="{{ asset('storage/product/' . $large_image->image_name) }}"
                                                                                            alt="product"
                                                                                            style="{{ $k_img == 0 ? 'display:block;' : 'display:none;' }}height:100%"
                                                                                            class="w-100 px-1 pt-2 {{ $small_product->id }}_{{ $k_img }}_img product_image">
                                                                                    @endforeach
                                                                                @else
                                                                                    @php($thumbnail_cdn = json_decode($small_product->thumbnail))
                                                                                    @if($thumbnail_cdn)
                                                                                        <img src="{{$thumbnail_cdn->cdn}}"
                                                                                        alt="product"
                                                                                        style="height:100%"
                                                                                        class="w-100 px-1 pt-2 product_image">
                                                                                    @else
                                                                                        <img src="{{ asset('storage/product/thumbnail/' . $small_product->thumbnail) }}"
                                                                                        alt="product"
                                                                                        style="height:100%"
                                                                                        class="w-100 px-1 pt-2 product_image">
                                                                                    @endif
                                                                                @endif
                                                                            </div>
                                                                            <div
                                                                                class="col-lg-12 col-md-12  col-12">
                                                                                <a
                                                                                    href="{{ route('product', $small_product->slug) }}">
                                                                                    <h4
                                                                                        class="s_16 primary_color bold pt-3">
                                                                                        {{ $small_product->name }}
                                                                                    </h4>
                                                                                </a>
                                                                            </div>
                                                                            @php($overallRating = \App\CPU\ProductManager::get_overall_rating($small_product->reviews))
                                                                            <div class="col-lg-12 col-md-12">
                                                                                <div class="rate">
                                                                                    @for ($inc = 0; $inc < 5; $inc++)
                                                                                        @if ($inc < $overallRating[0])
                                                                                            <i
                                                                                                class="p-0 sr-star czi-star-filled active"></i>
                                                                                        @else
                                                                                            <i class="p-0 sr-star czi-star"
                                                                                                style="color:#fea569 !important"></i>
                                                                                        @endif
                                                                                    @endfor
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-12 col-md-12">
                                                                                <div dir="rtl">
                                                                                    <span
                                                                                        class="real_price s_16 bold ">
                                                                                        {{ \App\CPU\Helpers::currency_converter($small_product->unit_price - \App\CPU\Helpers::get_product_discount($small_product, $small_product->unit_price)) }}
                                                                                    </span>
                                                                                    @if ($small_product->discount > 0)
                                                                                        <span
                                                                                            class="pre_price primary_color s_16 bold num_fam">{{ \App\CPU\Helpers::currency_converter($small_product->unit_price) }}</span>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            <div class="mt-1 col-lg-12 col-md-12">
                                                                                <ul class="list-inline checkbox-color mb-1 flex-start ml-2"
                                                                                    style="padding-left: 0;"
                                                                                    dir="ltr">
                                                                                    @if (count(json_decode($small_product->colors)) > 0)
                                                                                        @foreach (json_decode($small_product->colors) as $k_img => $small_color)
                                                                                            <li>
                                                                                                <input
                                                                                                    type="radio"
                                                                                                    id="{{ $small_product->id }}_{{ $k_img }}-color-{{ $small_color }}"
                                                                                                    name="color_{{ $small_product->id }}"
                                                                                                    value="#{{ $small_color }}"
                                                                                                    checked="">
                                                                                                <label
                                                                                                    style="background: {{ $small_color }};"
                                                                                                    data-target="{{ $small_product->id }}_{{ $k_img }}"
                                                                                                    for="{{ $small_product->id }}_{{ $k_img }}-color-{{ $small_color }}"
                                                                                                    class="color-changer"
                                                                                                    data-toggle="tooltip"
                                                                                                    onclick="focus_preview_image_by_color('{{ $small_color }}')">
                                                                                                    <span
                                                                                                        class="outline"></span></label>
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
                            <div
                                class="col-xxl-2 col-xl-2 col-md-2 col-sm-6 col-6 small-featured-product m-auto text-center">
                                @if (
                                    $category->banners->where('banner_type', 'Main Section Banner') != null &&
                                        $category->banners->where('banner_type', 'Main Section Banner')->count() > 0)
                                    <div class="row">
                                        <div class="col-xxl-11 col-xl-11 col-md-11 col-sm-11 col-11 product_container_side m-auto p-0"
                                            style="background-image: url({{ asset('storage/banner/' . $category->banners->where('banner_type', 'Main Section Banner')->first()->photo) }});background-size: 100% 100%">
                                            <a href="{{ $category->banners->where('banner_type', 'Main Section Banner')->first()->url }}"
                                                class="">
                                                <div class="featured-product-title p-3">
                                                    <h4 class="bold mb-1">
                                                        {{ $category->banners->where('banner_type', 'Main Section Banner')->first()->title }}
                                                    </h4>
                                                    <span
                                                        class="bold">{{ $category->banners->where('banner_type', 'Main Section Banner')->first()->description }}</span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if (
                        $category->banners->where('banner_type', 'Footer Banner') != null &&
                            $category->banners->where('banner_type', 'Footer Banner')->count() > 0)
                        <div class="col-xxl-12 col-xl-12 col-md-12 col-sm-12 col-12 mt-5  pt-3">
                            <div class="owl-carousel owl-theme footer_cat_banners" dir="ltr">
                                @foreach ($category->banners->where('banner_type', 'Footer Banner') as $footer_cat_banner)
                                    <div class="item m-auto large-featured-offer text-center">
                                        <div class="row">
                                            <div class="col-xxl-12 col-xl-12 col-md-12 col-sm-12 col-12 my-2">
                                                <div class="large-offer">
                                                    <img src="{{ asset('storage/banner/' . $footer_cat_banner->photo) }}"
                                                        class="w-100 offer-image" alt="offer">
                                                    <div class="offer-title">
                                                        <h4 class="bold">{{ $footer_cat_banner->title }}</h4>
                                                        <a href="{{ $footer_cat_banner->url }}"
                                                            class="btn btn-sm btn-custom bold">
                                                            {{ \App\CPU\translate('Shop Now') }}
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
