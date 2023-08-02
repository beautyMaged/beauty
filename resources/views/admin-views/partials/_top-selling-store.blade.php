<!-- Header -->
<div class="card-header gap-10">
    <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
        <img width="20" src="{{asset('/assets/back-end/img/shop-info.png')}}" alt="">
        {{\App\CPU\translate('top_selling_store')}}
    </h4>
</div>
<!-- End Header -->

<!-- Body -->
<div class="card-body">
    <div class="grid-item-wrap">
        @if($top_store_by_earning)
            @foreach($top_store_by_earning as $key=>$item)
                @php($shop=\App\Model\Shop::where('seller_id',$item['seller_id'])->first())
                @if(isset($shop))
                    <div class="cursor-pointer"
                         onclick="location.href='{{route('admin.sellers.view',$item['seller_id'])}}'">
                        <div class="grid-item">
                            <div class="d-flex align-items-center gap-10">
                                <img class="avatar rounded-circle avatar-sm"
                                     onerror="this.src='{{asset('assets/back-end/img/160x160/img1.jpg')}}'"
                                     src="{{asset('storage/shop/'.$shop->image??'')}}">

                                <h5 class="shop-name">{{$shop['name']??'Not exist'}}</h5>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <h5 class="shop-sell">{{\App\CPU\Helpers::currency_converter($item['count'])}}</h5>
                                <img src="{{asset('/assets/back-end/img/cart.png')}}" alt="">
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
