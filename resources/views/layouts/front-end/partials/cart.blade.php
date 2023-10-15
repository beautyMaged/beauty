{{--code improved Md. Al imrun Khandakar--}}


<div class="navbar-tool dropdown {{Session::get('direction') === "rtl" ? 'mr-lg-1' : 'ml-lg-1'}}"
     style="margin-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}:0 ">
    <a class="navbar-tool-icon-box bg-secondary dropdown-toggle" href="{{route('shop-cart')}}">
        <span class="navbar-tool-label bold">
            {{-- WTF!!! --}}
            @if(auth()->check())
                @php($cart=\App\CPU\CartManager::get_cart())
            @else
                @php($cart=\App\CPU\CartManager::get_cart())
            @endif
            {{$cart->count()}}
        </span>
        <i class="fa-solid fa-cart-shopping px-1 s_19"></i>
    </a>
    <a class="navbar-tool-text mt-2 {{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}}" href="{{route('shop-cart')}}">
        {{\App\CPU\Helpers::currency_converter(\App\CPU\CartManager::cart_total_applied_discount(\App\CPU\CartManager::get_cart()))}}
    </a>
    <!-- Cart dropdown-->
    <div class="dropdown-menu dropdown-menu-{{Session::get('direction') === "rtl" ? 'left' : 'right'}} __w-20rem ">
        <div class="widget widget-cart px-3 pt-2 pb-3">
            @if($cart->count() > 0)
                <div class="__h-15rem" data-simplebar data-simplebar-auto-hide="false">
                    @php($sub_total=0)
                    @php($total_tax=0)
                    @foreach($cart as  $cartItem)
                        <div class="widget-cart-item pb-2">
                            <button class="close text-danger " type="button"
                                    onclick="removeFromCart({{ $cartItem['id'] }})"
                                    aria-label="Remove"><span
                                    aria-hidden="true">&times;</span>
                            </button>
                            <div class="media">
                                <a class="d-block {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}"
                                   href="{{route('product',$cartItem['slug'])}}">
                                    <img width="64"
                                         onerror="this.onerror=null;this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
{{--                                         src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$cartItem['thumbnail']}}"--}}
                                         src="{{is_object(json_decode($cartItem['thumbnail'])) ? (json_decode($cartItem['thumbnail']))->cdn : \App\CPU\ProductManager::product_image_path('thumbnail').'/'.$cartItem['thumbnail']}}"

                                         alt="Product"/>
                                </a>
                                <div class="media-body">
                                    <h6 class="widget-product-title">
                                        <a href="{{route('product',$cartItem['slug'])}}">{{Str::limit($cartItem['name'],30)}}</a>
                                    </h6>
                                    @foreach(json_decode($cartItem['variations'],true) as $key =>$variation)
                                        <span class="__text-14px">{{$key}} : {{$variation}}</span><br>
                                    @endforeach
                                    <div class="widget-product-meta">
                                        <span
                                            class="text-muted {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}">x {{$cartItem['quantity']}}</span>
                                        <span
                                            class="text-accent {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}">
                                                {{\App\CPU\Helpers::currency_converter(($cartItem['price']-$cartItem['discount'])*$cartItem['quantity'])}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @php($sub_total+=($cartItem['price']-$cartItem['discount'])*$cartItem['quantity'])
                        @php($total_tax+=$cartItem['tax']*$cartItem['quantity'])
                    @endforeach
                </div>
                <hr>
                <div class="d-flex flex-wrap justify-content-between align-items-center py-3">
                    <div
                        class="font-size-sm {{Session::get('direction') === "rtl" ? 'ml-2 float-left' : 'mr-2 float-right'}} py-2 ">
                        <span class="">{{\App\CPU\translate('Subtotal')}} :</span>
                        <span
                            class="text-accent font-size-base {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                             {{\App\CPU\Helpers::currency_converter($sub_total)}}
                        </span>
                    </div>

                    <a class="btn btn--primary btn-sm btn-block w-100" href="{{route('shop-cart')}}">
                        {{\App\CPU\translate('Go to Cart')}}<i
                            class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left mr-1 ml-n1' : 'right ml-1 mr-n1'}}"></i>
                    </a>
                </div>
                @if(auth('customer')->check())
                    <a class="btn btn--primary btn-sm btn-block" href="{{route('checkout-details')}}">
                        <i class="czi-card {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}} font-size-base align-middle"></i>{{\App\CPU\translate('Checkout')}}
                    </a>
                @endif
            @else
                <div class="widget-cart-item">
                    <h6 class="text-danger text-center m-0"><i
                            class="fa fa-cart-arrow-down"></i> {{\App\CPU\translate('Cart is Empty')}}
                    </h6>
                </div>
            @endif
        </div>
    </div>
</div>
{{--code improved Md. Al imrun Khandakar--}}
{{--to do discount--}}
