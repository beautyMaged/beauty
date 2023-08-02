<!-- Header -->
<div class="card-header gap-10">
    <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
        <img width="20" src="{{asset('/assets/back-end/img/top-selling-product.png')}}" alt="">
        {{\App\CPU\translate('top_selling_products')}}
    </h4>
</div>
<!-- End Header -->

<!-- Body -->
<div class="card-body">
    <div class="grid-item-wrap">
        @if($top_sell)
            @foreach($top_sell as $key=>$item)
                @if(isset($item->product))
                    <div class="cursor-pointer"
                         onclick="location.href='{{route('admin.product.view',[$item['product_id']])}}'">
                        <div class="grid-item px-0 bg-transparent">
                            <div class="d-flex gap-10">
                                <img src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$item->product['thumbnail']}}" class="avatar avatar-lg rounded avatar-bordered" onerror="this.src='{{asset('assets/back-end/img/160x160/img2.jpg')}}'" alt="{{$item->product->name}} image">
                                <div class="title-color">{{substr($item->product['name'],0,20)}} {{strlen($item->product['name'])>20?'...':''}}</div>
                            </div>

                            <div class="orders-count py-2 px-3 d-flex gap-1">
                                <div>{{\App\CPU\translate('sold')}} : </div>
                                <div class="sold-count">{{$item['count']}}</div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @else
            <div class="text-center">
                <p class="text-muted">{{\App\CPU\translate('No_Top_Selling_Products')}}</p>
                <img class="w-75" src="{{asset('/assets/back-end/img/no-data.png')}}" alt="">
            </div>
        @endif
    </div>
</div>
<!-- End Body -->
