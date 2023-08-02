<!-- Header -->
<div class="card-header">
    <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
        <img src="{{asset('/assets/back-end/img/top-customers.png')}}" alt="">
        {{\App\CPU\translate('Top_Delivery_Man')}}
    </h4>
</div>
<!-- End Header -->

<!-- Body -->
<div class="card-body">
    @if($top_deliveryman)
        <div class="grid-card-wrap">
            @foreach($top_deliveryman as $key=>$item)
                @if(isset($item->delivery_man))
                    <div class="cursor-pointer">
                        <div class="grid-card">
                            <div class="text-center">
                                <img class="avatar rounded-circle avatar-lg"
                                     onclick="location.href='{{route('admin.delivery-man.earning-statement-overview',[$item['delivery_man_id']])}}'"
                                     onerror="this.src='{{asset('assets/back-end/img/160x160/img1.jpg')}}'"
                                     src="{{asset('storage/delivery-man/'.$item->delivery_man->image??'')}}">
                            </div>

                            <h5 class="mb-0">
                                {{Str::limit($item->delivery_man['f_name'], 15)}}
                            </h5>

                            <div class="orders-count d-flex gap-1">
                                <div>{{\App\CPU\translate('delivered')}} : </div>
                                <div>{{$item['count']}}</div>
                            </div>
                        </div>
                    </div>
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
