<style>
    .product {
        background-color: #fcfcfc;
        border: 2px solid #efefef;
        margin-bottom: 10px;
    }

    .product_pic {
        width: 40%;
    }

    .product_details {
        width: 60%;
        padding: 5px;
    }

    .image_center {
        height: 126px;
    }

    .image_center img {
        min-width: 100px;
        vertical-align: middle;
    }

    .product-title {
        position: relative;
    }

    .product-title > a {
        color: #373f50;
    }

    .star-rating > i {
        font-size: 8px !important;
    }

    .ptr1 {
        position: relative;
        display: inline-block;
        word-wrap: break-word;
        overflow: hidden;
        max-height: 2.4em; /* (Number of lines you want visible) * (line-height) */
        line-height: 1.2em;
        /*text-align:justify;*/
    }

    .ptr {
        font-weight: 600;
        font-size: 16px !important;
    }

    .inline_product_image {
        height: 100px;
    }

    .ptp {
        font-weight: 700;
        font-size: 16px !important;
    }

    .star-rating .sr-star {
        margin: 0 !important;
    }

    @media (max-width: 768px) {
        .product_pic {
            width: 200px !important;
        }

        .product {
            margin-right: 16px;
        }

        .product_details {
            width: 100% !important;
        }
    }

    .stock-out-side {
        position: absolute;
        left: 47% !important;
        top: 83% !important;
        color: white !important;
        font-weight: 900;
        font-size: 15px;
    }

    .stock-card {
        /*cursor: not-allowed !important;*/
        /*pointer-events: none!important;*/
        filter: contrast(0.8)!important;
    }

</style>
<div class="d-flex product justify-content-between inline_product" style="cursor: pointer;"
     data-href="{{route('product',$product->slug)}}">
    <div class="product_pic d-flex align-items-center justify-content-center" style=" text-align: center;">
        <a href="{{route('product',$product->slug)}}" class="image_center">
            <img class="inline_product_image"
                 onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                 src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                 width="100%" style="height: 100%;">
        </a>
    </div>
    <div class="product_details {{$product['current_stock']==0?'stock-card':''}}">
        <h3 class="product-title">
            <a class="ptr ptr1" href="{{route('product',$product->slug)}}">{{$product['name']}}</a>
        </h3>
        @php($overallRating=\App\CPU\ProductManager::get_overall_rating($product->reviews))
        <h6 class="ptr">
            @for($inc=0;$inc<5;$inc++)
                @if($inc<$overallRating[0])
                    <i class="sr-star czi-star-filled active" style="color: gold"></i>
                @else
                    <i class="sr-star czi-star active"></i>
                @endif
            @endfor
        </h6>
        <div class="product-price">
            <span class="text-accent ptp">
            {{\App\CPU\Helpers::currency_converter(
            $product->unit_price-(\App\CPU\Helpers::get_product_discount($product,$product->unit_price))
            )}}
            </span>
            @if($product->discount > 0)
                <strike style="font-size: 12px!important;color: grey!important;">
                    {{\App\CPU\Helpers::currency_converter($product->unit_price)}}
                </strike>
            @endif
        </div>
        @if($product['current_stock']<=0)
            <label class="badge badge-danger stock-out-side">{{\App\CPU\translate('Stock Out')}}</label>
        @endif
    </div>
</div>

