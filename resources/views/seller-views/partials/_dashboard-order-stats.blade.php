<div class="col-sm-6 col-lg-3">
    <!-- Card -->
    <a class="order-stats order-stats_pending" href="{{route('seller.orders.list',['pending'])}}">
        <div class="order-stats__content">
            <img width="20" src="{{asset('assets/back-end/img/pending.png')}}" alt="">
            <h6 class="order-stats__subtitle">{{\App\CPU\translate('pending')}}</h6>
        </div>
        <span class="order-stats__title">{{$data['pending']}}</span>
    </a>
    <!-- End Card -->
</div>
<div class="col-sm-6 col-lg-3">
    <!-- Card -->
    <a class="order-stats order-stats_confirmed" href="{{route('seller.orders.list',['confirmed'])}}">
        <div class="order-stats__content">
            <img width="20" src="{{asset('assets/back-end/img/confirmed.png')}}" alt="">
            <h6 class="order-stats__subtitle">{{\App\CPU\translate('Confirmed')}}</h6>
        </div>
        <span class="order-stats__title">{{$data['confirmed']}}</span>
    </a>
    <!-- End Card -->
</div>
<div class="col-sm-6 col-lg-3">
    <!-- Card -->
    <a class="order-stats order-stats_packaging" href="{{route('seller.orders.list',['processing'])}}">
        <div class="order-stats__content">
            <img width="20" src="{{asset('assets/back-end/img/packaging.png')}}" alt="">
            <h6 class="order-stats__subtitle">{{\App\CPU\translate('packaging')}}</h6>
        </div>
        <span class="order-stats__title">{{$data['processing']}}</span>
    </a>
    <!-- End Card -->
</div>
<div class="col-sm-6 col-lg-3">
    <!-- Card -->
    <a class="order-stats order-stats_out-for-delivery" href="{{route('seller.orders.list',['out_for_delivery'])}}">
        <div class="order-stats__content">
            <img width="20" src="{{asset('assets/back-end/img/out-of-delivery.png')}}" alt="">
            <h6 class="order-stats__subtitle">{{\App\CPU\translate('Out_For_Delivery')}}</h6>
        </div>
        <span class="order-stats__title">{{$data['out_for_delivery']}}</span>
    </a>
    <!-- End Card -->
</div>


<div class="ol-sm-6 col-lg-3">
    <!-- Card -->
    <a class="order-stats order-stats_delivered" href="{{route('seller.orders.list',['delivered'])}}">
        <div class="order-stats__content">
            <img width="20" src="{{asset('assets/back-end/img/delivered.png')}}" alt="">
            <h6 class="order-stats__subtitle">{{\App\CPU\translate('delivered')}}</h6>
        </div>
        <span class="order-stats__title">{{$data['delivered']}}</span>
    </a>
    <!-- End Card -->
</div>
<div class="ol-sm-6 col-lg-3">
    <!-- Card -->
    <a class="order-stats order-stats_canceled" href="{{route('seller.orders.list',['canceled'])}}">
        <div class="order-stats__content">
            <img width="20" src="{{asset('assets/back-end/img/canceled.png')}}" alt="">
            <h6 class="order-stats__subtitle">{{\App\CPU\translate('canceled')}}</h6>
        </div>
        <span class="order-stats__title">{{$data['canceled']}}</span>
    </a>
    <!-- End Card -->
</div>
<div class="ol-sm-6 col-lg-3">
    <!-- Card -->
    <a class="order-stats order-stats_returned" href="{{route('seller.orders.list',['returned'])}}">
        <div class="order-stats__content">
            <img width="20" src="{{asset('assets/back-end/img/returned.png')}}" alt="">
            <h6 class="order-stats__subtitle">{{\App\CPU\translate('returned')}}</h6>
        </div>
        <span class="order-stats__title">{{$data['returned']}}</span>
    </a>
    <!-- End Card -->
</div>
<div class="ol-sm-6 col-lg-3">
    <!-- Card -->
    <a class="order-stats order-stats_failed" href="{{route('seller.orders.list',['failed'])}}">
        <div class="order-stats__content">
            <img width="20" src="{{asset('assets/back-end/img/failed-to-deliver.png')}}" alt="">
            <h6 class="order-stats__subtitle">{{\App\CPU\translate('Failed_To_Delivery')}}</h6>
        </div>
        <span class="order-stats__title">{{$data['failed']}}</span>
    </a>
    <!-- End Card -->
</div>
