<!-- Header -->
<div class="card-header gap-10">
    <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
        <img src="{{asset('/assets/back-end/img/top-selling-store.png')}}" alt="">
        {{\App\CPU\translate('Most_Popular_Stores')}}
    </h4>
</div>
<!-- End Header -->

<!-- Body -->
<div class="card-body">
    @if($top_store_by_order_received)
        <div class="grid-item-wrap">
            @foreach($top_store_by_order_received as $key=>$item)
                @php($shop=\App\Model\Shop::where('seller_id',$item['seller_id'])->first())
                @if(isset($shop))
                    <a href="{{route('admin.sellers.view',$item['seller_id'])}}" class="grid-item">
                        <div class="d-flex align-items-center gap-10">
                            <img onerror="this.src='{{asset('assets/back-end/img/160x160/img1.jpg')}}'"
                                 src="{{asset('storage/shop/'.$shop->image??'')}}" class="avatar rounded-circle avatar-sm">

                            <h5 class="shop-name">{{$shop['name']??'Not exist'}}</h5>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="shop-sell c2">{{$item['count']}}</h5>
                            <img src="{{asset('/assets/back-end/img/love.png')}}" alt="">
                        </div>
                    </a>
                @endif
            @endforeach
        </div>
    @else
        <div class="text-center">
            <p class="text-muted">{{\App\CPU\translate('No_Top_Selling_Products')}}</p>
            <img class="w-75" src="{{asset('/assets/back-end/img/no-data.png')}}" alt="">
        </div>
    @endif
</div>
<!-- End Body -->
