@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Seller product sale Report'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/assets/back-end/img/seller-reports.png')}}" alt="">
                {{\App\CPU\translate('Seller Reports')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="card mb-2">
            <div class="card-body">
                <form action="" id="form-data" method="GET">
                    <h4 class="mb-3">{{\App\CPU\translate('Filter_Data')}}</h4>
                    <div class="row gx-2 gy-3 align-items-center text-left">
                        <div class="col-sm-6 col-md-3">
                            <select class="js-select2-custom form-control" name="seller_id">
                                <option value="all">{{\App\CPU\translate('all_sellers')}}</option>
                                @foreach($sellers as $seller)
                                    <option
                                        value="{{$seller['id']}}" {{$seller_id==$seller['id']?'selected':''}}>
                                        {{$seller['f_name']}} {{$seller['l_name']}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <select class="form-control __form-control" name="date_type" id="date_type">
                                <option value="this_year" {{ $date_type == 'this_year'? 'selected' : '' }}>{{\App\CPU\translate('This_Year')}}</option>
                                <option value="this_month" {{ $date_type == 'this_month'? 'selected' : '' }}>{{\App\CPU\translate('This_Month')}}</option>
                                <option value="this_week" {{ $date_type == 'this_week'? 'selected' : '' }}>{{\App\CPU\translate('This_Week')}}</option>
                                <option value="custom_date" {{ $date_type == 'custom_date'? 'selected' : '' }}>{{\App\CPU\translate('Custom_Date')}}</option>
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-3" id="from_div">
                            <div class="form-floating">
                                <input type="date" name="from" value="{{$from}}" id="from_date" class="form-control">
                                <label>{{\App\CPU\translate('start_date')}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3" id="to_div">
                            <div class="form-floating">
                                <input type="date" value="{{$to}}" name="to" id="to_date" class="form-control">
                                <label>{{\App\CPU\translate('end_date')}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3 filter-btn">
                            <button type="submit" class="btn btn--primary px-4 px-md-5">
                                {{\App\CPU\translate('filter')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="store-report-content mb-2">
            <div class="left-content">
                <div class="left-content-card">
                    <img src="{{asset('/assets/back-end/img/products.svg')}}" alt="back-end/img">
                    <div class="info">
                        <h4 class="subtitle">{{ $total_product }}</h4>
                        <h6 class="subtext">{{\App\CPU\translate('products')}}</h6>
                    </div>
                </div>
                <div class="left-content-card">
                    <img src="{{asset('/assets/back-end/img/cart.svg')}}" alt="back-end/img">
                    <div class="info">
                        <h4 class="subtitle">{{ $canceled_order+$ongoing_order+$delivered_order }}</h4>
                        <h6 class="subtext">{{\App\CPU\translate('Total_Orders')}}</h6>
                    </div>
                    <div class="coupon__discount w-100 text-right d-flex justify-content-between">
                        <div class="text-center">
                            <strong class="text-danger">{{ $canceled_order }}</strong>
                            <div class="d-flex">
                                <span>{{\App\CPU\translate('canceled')}}</span>
                                <span class="ml-2" data-toggle="tooltip" data-placement="top" title="{{\App\CPU\translate('this_count_is_the_summation_of')}} {{\App\CPU\translate('failed_to_deliver')}}, {{\App\CPU\translate('canceled')}}, {{\App\CPU\translate('and')}} {{\App\CPU\translate('returned_orders')}}">
                                    <img class="info-img" src="{{asset('/assets/back-end/img/info-circle.svg')}}" alt="img">
                                </span>
                            </div>
                        </div>
                        <div class="text-center">
                            <strong class="text-primary">{{ $ongoing_order }}</strong>
                            <div class="d-flex">
                                <span>{{\App\CPU\translate('ongoing')}}</span>
                                <span class="ml-2" data-toggle="tooltip" data-placement="top" title="{{\App\CPU\translate('this_count_is_the_summation_of')}} {{\App\CPU\translate('pending')}}, {{\App\CPU\translate('confirmed')}}, {{\App\CPU\translate('packaging')}}, {{\App\CPU\translate('out_for_delivery_orders')}}">
                                    <img class="info-img" src="{{asset('/assets/back-end/img/info-circle.svg')}}" alt="img">
                                </span>
                            </div>
                        </div>
                        <div class="text-center">
                            <strong class="text-success">{{ $delivered_order }}</strong>
                            <div class="d-flex">
                                <span>{{\App\CPU\translate('completed')}}</span>
                                <span class="ml-2" data-toggle="tooltip" data-placement="top" title="{{\App\CPU\translate('this_count_is_the_summation_of_delivered_orders')}}">
                                    <img class="info-img" src="{{asset('/assets/back-end/img/info-circle.svg')}}" alt="img">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="left-content-card">
                    <img src="{{asset('/assets/back-end/img/deliveryman.svg')}}" alt="back-end/img">
                    <div class="info">
                        <h4 class="subtitle">
                            {{ $deliveryman }}
                        </h4>
                        <h6 class="subtext">{{\App\CPU\translate('Total_Deliveryman')}}</h6>
                    </div>
                </div>
            </div>
            @foreach(array_values($chart_data['order_amount']) as $amount)
                @php($chart_val[] = \App\CPU\BackEndHelper::usd_to_currency($amount))
            @endforeach
            <div class="center-chart-area">
                <div class="center-chart-header">
                    <h3 class="title">{{\App\CPU\translate('Order_Statistics')}}</h3>
                </div>
                <canvas id="updatingData" class="store-center-chart"
                        data-hs-chartjs-options='{
                "type": "bar",
                "data": {
                  "labels": [{{ '"'.implode('","', array_keys($chart_data['order_amount'])).'"' }}],
                  "datasets": [{
                    "label": "{{\App\CPU\translate('total_order_amount')}}",
                    "data": [{{ '"'.implode('","', $chart_val).'"' }}],
                    "backgroundColor": "#a2ceee",
                    "hoverBackgroundColor": "#0177cd",
                    "borderColor": "#a2ceee"
                  }]
                },
                "options": {
                  "scales": {
                    "yAxes": [{
                      "gridLines": {
                        "color": "#e7eaf3",
                        "drawBorder": false,
                        "zeroLineColor": "#e7eaf3"
                      },
                      "ticks": {
                        "beginAtZero": true,
                        "fontSize": 12,
                        "fontColor": "#97a4af",
                        "fontFamily": "Open Sans, sans-serif",
                        "padding": 5,
                        "postfix": " {{ \App\CPU\BackEndHelper::currency_symbol() }}"
                      }
                    }],
                    "xAxes": [{
                      "gridLines": {
                        "display": false,
                        "drawBorder": false
                      },
                      "ticks": {
                        "fontSize": 12,
                        "fontColor": "#97a4af",
                        "fontFamily": "Open Sans, sans-serif",
                        "padding": 5
                      },
                      "categoryPercentage": 0.3,
                      "maxBarThickness": "10"
                    }]
                  },
                  "cornerRadius": 5,
                  "tooltips": {
                    "prefix": " ",
                    "hasIndicator": true,
                    "mode": "index",
                    "intersect": false
                  },
                  "hover": {
                    "mode": "nearest",
                    "intersect": true
                  }
                }
              }'>
                </canvas>
            </div>
            <div class="right-content">
                <div class="card h-100 bg-white payment-statistics-shadow">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="earning-statistics-content">
                            <img class="mb-4" src="{{asset('/assets/back-end/img/earnings.svg')}}" alt="back-end/img">
                            <h6 class="subtitle">{{\App\CPU\translate('Total_Shop_Earnings')}}</h6>
                            <h3 class="title">
                                {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total_store_earning)) }}
                            </h3>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex flex-wrap w-100 gap-3 align-items-center">
                    <h4 class="mb-0 mr-auto">
                        {{\App\CPU\translate('Total_Seller')}}
                        <span class="badge badge-soft-dark radius-50 fz-14">{{ $orders->total() }}</span>
                    </h4>
                    <form action="{{ url()->full() }}" method="GET" class="mb-0">
                        <!-- Search -->
                        <div class="input-group input-group-merge input-group-custom">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tio-search"></i>
                                </div>
                            </div>
                            <input type="hidden" name="seller_id" value="{{ $seller_id }}">
                            <input type="hidden" name="date_type" value="{{ $date_type }}">
                            <input type="hidden" name="from" value="{{ $from }}">
                            <input type="hidden" name="to" value="{{ $to }}">
                            <input id="datatableSearch_" type="search" value="{{ $search }}" name="search" class="form-control" placeholder="Search by shop" aria-label="Search orders" required="">
                            <button type="submit" class="btn btn--primary">{{\App\CPU\translate('search')}}</button>
                        </div>
                        <!-- End Search -->
                    </form>
                    <div>
                        <button type="button" class="btn btn-outline--primary text-nowrap btn-block" data-toggle="dropdown">
                            <i class="tio-download-to"></i>
                            {{\App\CPU\translate('export')}}
                            <i class="tio-chevron-down"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('admin.report.seller-report-excel', ['date_type'=>request('date_type'), 'seller_id'=>request('seller_id'),'from'=>request('from'), 'to'=>request('to'), 'search'=>request('search')]) }}"  >{{\App\CPU\translate('Excel')}}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="datatable"
                           style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                           class="table __table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                        <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>{{\App\CPU\translate('SL')}}</th>
                            <th>{{\App\CPU\translate('Seller Info')}}</th>
                            <th>{{\App\CPU\translate('Total Order')}}</th>
                            <th>{{\App\CPU\translate('Commission')}}</th>
                            <th class="text-center">{{\App\CPU\translate('Refund Rate')}}</th>
                            <th class="text-center">{{\App\CPU\translate('Action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $key=>$order)
                            <tr>
                                <td>{{ $orders->firstItem()+$key }}</td>
                                <td>
                                    <div>
                                        @if (isset($order->seller->shop))
                                            <a class="title-color" href="{{ route('admin.sellers.view', ['id' => $order->seller->id]) }}">
                                                <h6 class="mb-1">
                                                    {{ \Str::limit($order->seller->shop->name, 20)}}
                                                </h6>
                                            </a>
                                        @else
                                            {{\App\CPU\translate('not_found')}}
                                        @endif

                                    </div>
                                </td>
                                <td>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->total_order_amount)) }}</td>
                                <td>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->total_admin_commission)) }}</td>
                                <td class="text-center">
                                    <?php
                                        $arr= array();
                                        if($refunds) {
                                            foreach ($refunds as $refund) {
                                                $arr += array(
                                                    $refund['payer_id'] => $refund['total_refund_amount']
                                                );
                                            }
                                        }
                                        if(array_key_exists($order->seller_id, $arr)){
                                            echo number_format(($arr[$order->seller_id]/$order->total_order_amount)*100, 2).'%';
                                        }else{
                                            echo '0%';
                                        }
                                    ?>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('admin.sellers.view', [$order->seller_id]) }}" class="btn btn-outline--primary square-btn btn-sm">
                                            <i class="tio-invisible"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @if(count($orders)==0)
                            <tr>
                                <td colspan="7">
                                    <div class="text-center p-4">
                                        <img class="mb-3 w-160" src="{{asset('assets/back-end')}}/svg/illustrations/sorry.svg"
                                             alt="Image Description">
                                        <p class="mb-0">{{ \App\CPU\translate('No_data_to_show')}}</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive mt-4">
                    <div class="px-4 d-flex justify-content-lg-end">
                        <!-- Pagination -->
                        {!! $orders->links() !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- End Stats -->
    </div>
@endsection

@push('script')

    <!-- Chart JS -->
    <script src="{{ asset('assets/back-end') }}/js/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('assets/back-end') }}/js/chart.js.extensions/chartjs-extensions.js"></script>
    <script src="{{ asset('assets/back-end') }}/js/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js">
    </script>
    <!-- Chart JS -->
@endpush

@push('script_2')

    <script>
        // Bar Charts
        Chart.plugins.unregister(ChartDataLabels);

        $('.js-chart').each(function() {
            $.HSCore.components.HSChartJS.init($(this));
        });

        var updatingChart = $.HSCore.components.HSChartJS.init($('#updatingData'));

        $('.js-data-example-ajax').select2({
            ajax: {
                url: '{{ url('/') }}/admin/store/get-stores',
                data: function(params) {
                    return {
                        q: params.term, // search term
                        // all:true,
                        @if (isset($zone))
                        zone_ids: [{{ $zone->id }}],
                        @endif
                        page: params.page
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                __port: function(params, success, failure) {
                    var $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }
            }
        });

        $('#from_date,#to_date').change(function () {
            let fr = $('#from_date').val();
            let to = $('#to_date').val();
            if(fr != ''){
                $('#to_date').attr('required','required');
            }
            if(to != ''){
                $('#from_date').attr('required','required');
            }
            if (fr != '' && to != '') {
                if (fr > to) {
                    $('#from_date').val('');
                    $('#to_date').val('');
                    toastr.error('Invalid date range!', Error, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }

        })

        $("#date_type").change(function() {
            let val = $(this).val();
            $('#from_div').toggle(val === 'custom_date');
            $('#to_div').toggle(val === 'custom_date');

            if(val === 'custom_date'){
                $('#from_date').attr('required','required');
                $('#to_date').attr('required','required');
                $('.filter-btn').attr('class','filter-btn col-12 text-right');
            }else{
                $('#from_date').val(null).removeAttr('required')
                $('#to_date').val(null).removeAttr('required')
                $('.filter-btn').attr('class','col-sm-6 col-md-3 filter-btn');
            }
        }).change();

    </script>
@endpush
