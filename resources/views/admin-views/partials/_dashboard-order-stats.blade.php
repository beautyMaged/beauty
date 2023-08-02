<div class="col-sm-6 col-lg-3">
    <!-- Business Analytics Card -->
    <div class="business-analytics">
        <h5 class="business-analytics__subtitle">{{\App\CPU\translate('Total Sale')}}</h5>
        <h2 class="business-analytics__title">{{ $data['total_sale'] }}</h2>
        <img src="{{asset('/assets/back-end/img/total-sale.png')}}" class="business-analytics__img" alt="">
    </div>
    <!-- End Business Analytics Card -->
</div>
<div class="col-sm-6 col-lg-3">
    <!-- Business Analytics Card -->
    <div class="business-analytics">
        <h5 class="business-analytics__subtitle">{{\App\CPU\translate('Total Stores')}}</h5>
        <h2 class="business-analytics__title">{{ $data['store'] }}</h2>
        <img src="{{asset('/assets/back-end/img/total-stores.png')}}" class="business-analytics__img" alt="">
    </div>
    <!-- End Business Analytics Card -->
</div>
<div class="col-sm-6 col-lg-3">
    <!-- Business Analytics Card -->
    <div class="business-analytics">
        <h5 class="business-analytics__subtitle">{{\App\CPU\translate('Total Products')}}</h5>
        <h2 class="business-analytics__title">{{ $data['product'] }}</h2>
        <img src="{{asset('/assets/back-end/img/total-product.png')}}" class="business-analytics__img" alt="">
    </div>
    <!-- End Business Analytics Card -->
</div>
<div class="col-sm-6 col-lg-3">
    <!-- Business Analytics Card -->
    <div class="business-analytics">
        <h5 class="business-analytics__subtitle">{{\App\CPU\translate('Total Customers')}}</h5>
        <h2 class="business-analytics__title">{{ $data['customer'] }}</h2>
        <img src="{{asset('/assets/back-end/img/total-customer.png')}}" class="business-analytics__img" alt="">
    </div>
    <!-- End Business Analytics Card -->
</div>


<div class="col-sm-6 col-lg-3">
    <!-- Card -->
    <a class="order-stats order-stats_pending" href="{{route('admin.orders.list',['pending'])}}">
        <div class="order-stats__content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <img width="20" src="{{asset('/assets/back-end/img/pending.png')}}" alt="">
            <h6 class="order-stats__subtitle">{{\App\CPU\translate('pending')}}</h6>
        </div>
        <span class="order-stats__title">
            {{$data['pending']}}
        </span>
    </a>
    <!-- End Card -->
</div>

<div class="col-sm-6 col-lg-3">
    <!-- Card -->
    <a class="order-stats order-stats_confirmed" href="{{route('admin.orders.list',['confirmed'])}}">
        <div class="order-stats__content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <img width="20" src="{{asset('/assets/back-end/img/confirmed.png')}}" alt="">
            <h6 class="order-stats__subtitle">{{\App\CPU\translate('confirmed')}}</h6>
        </div>
        <span class="order-stats__title">
            {{$data['confirmed']}}
        </span>
    </a>
    <!-- End Card -->
</div>

<div class="col-sm-6 col-lg-3">
    <!-- Card -->
    <a class="order-stats order-stats_packaging" href="{{route('admin.orders.list',['processing'])}}">
        <div class="order-stats__content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <img width="20" src="{{asset('/assets/back-end/img/packaging.png')}}" alt="">
            <h6 class="order-stats__subtitle">{{\App\CPU\translate('Packaging')}}</h6>
        </div>
        <span class="order-stats__title">
            {{$data['processing']}}
        </span>
    </a>
    <!-- End Card -->
</div>

<div class="col-sm-6 col-lg-3">
    <!-- Card -->
    <a class="order-stats order-stats_out-for-delivery" href="{{route('admin.orders.list',['out_for_delivery'])}}">
        <div class="order-stats__content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <img width="20" src="{{asset('/assets/back-end/img/out-of-delivery.png')}}" alt="">
            <h6 class="order-stats__subtitle">{{\App\CPU\translate('out_for_delivery')}}</h6>
        </div>
        <span class="order-stats__title">
            {{$data['out_for_delivery']}}
        </span>
    </a>
    <!-- End Card -->
</div>



<div class="col-sm-6 col-lg-3">
    <div class="order-stats order-stats_delivered cursor-pointer" onclick="location.href='{{route('admin.orders.list',['delivered'])}}'">
        <div class="order-stats__content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <img width="20" src="{{asset('/assets/back-end/img/delivered.png')}}" alt="">
            <h6 class="order-stats__subtitle">{{\App\CPU\translate('delivered')}}</h6>
        </div>
        <span class="order-stats__title">{{$data['delivered']}}</span>
    </div>
</div>

<div class="col-sm-6 col-lg-3">
    <div class="order-stats order-stats_canceled cursor-pointer" onclick="location.href='{{route('admin.orders.list',['canceled'])}}'">
        <div class="order-stats__content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <img width="20" src="{{asset('/assets/back-end/img/canceled.png')}}" alt="">
            <h6 class="order-stats__subtitle">{{\App\CPU\translate('canceled')}}</h6>
        </div>
        <span class="order-stats__title h3">{{$data['canceled']}}</span>
    </div>
</div>

<div class="col-sm-6 col-lg-3">
    <div class="order-stats order-stats_returned cursor-pointer" onclick="location.href='{{route('admin.orders.list',['returned'])}}'">
        <div class="order-stats__content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <img width="20" src="{{asset('/assets/back-end/img/returned.png')}}" alt="">
            <h6 class="order-stats__subtitle">{{\App\CPU\translate('returned')}}</h6>
        </div>
        <span class="order-stats__title h3">{{$data['returned']}}</span>
    </div>
</div>

<div class="col-sm-6 col-lg-3">
    <div class="order-stats order-stats_failed cursor-pointer" onclick="location.href='{{route('admin.orders.list',['failed'])}}'">
        <div class="order-stats__content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <img width="20" src="{{asset('/assets/back-end/img/failed-to-deliver.png')}}" alt="">
            <h6 class="order-stats__subtitle">{{\App\CPU\translate('failed to delivery')}}</h6>
        </div>
        <span class="order-stats__title h3">{{$data['failed']}}</span>
    </div>
</div>
