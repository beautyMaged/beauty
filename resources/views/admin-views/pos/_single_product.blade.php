<div class="pos-product-item card" onclick="quickView('{{$product->id}}')">
    <div class="pos-product-item_thumb">
        <img class="img-fit" src="{{asset('storage/app/public/product/thumbnail')}}/{{$product->thumbnail}}"
                 onerror="this.src='{{asset('public/assets/back-end/img/160x160/img2.jpg')}}'">
    </div>

    <div class="pos-product-item_content clickable">
        <div class="pos-product-item_title">
            <!-- {{ Str::limit($product['name'], 26) }} -->
            {{ $product['name'] }}
        </div>
        <div class="pos-product-item_price">
            {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($product['unit_price']- \App\CPU\Helpers::get_product_discount($product, $product['unit_price'])))  }}
            <!-- {{--@if($product->discount > 0)
                <strike class="fz-12">
                    {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($product['unit_price'])) }}
                </strike>
            @endif--}} -->
        </div>
{{--        <div class="pos-product-item_hover-content">--}}
{{--            <div class="d-flex flex-wrap gap-2">--}}
{{--                <span>Qty:</span>--}}
{{--                <span>12</span>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>
</div>
