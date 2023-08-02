@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Product Report'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">

        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex gap-2 align-items-center">
                <img width="20" src="{{asset('/assets/back-end/img/seller_sale.png')}}" alt="">
                {{\App\CPU\translate('product_report')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
        @include('admin-views.report.product-report-inline-menu')
        <!-- End Inlile Menu -->

        <div class="card mb-2">
            <div class="card-body">
                <form action="" id="form-data" method="GET">
                    <h4 class="mb-3">{{\App\CPU\translate('Filter_Data')}}</h4>
                    <div class="row gx-2 gy-3 align-items-center text-left">
                        <div class="col-sm-6 col-md-3">
                            <select class="js-select2-custom form-control __form-control" name="seller_id">
                                <option class="text-center" value="all" {{ $seller_id == 'all' ? 'selected' : '' }}>
                                    {{\App\CPU\translate('all')}}
                                </option>
                                <option class="text-center" value="inhouse" {{ $seller_id == 'inhouse' ? 'selected' : '' }}>
                                    {{\App\CPU\translate('inhouse')}}
                                </option>
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
                    <img src="{{asset('/assets/back-end/img/cart.svg')}}" alt="">
                    <div class="info">
                        <h4 class="subtitle">{{ $product_count['reject_product_count']+$product_count['active_product_count']+$product_count['pending_product_count'] }}</h4>
                        <h6 class="subtext">{{\App\CPU\translate('Total_Product')}}</h6>
                    </div>
                    <div class="coupon__discount w-100 text-right d-flex justify-content-between">
                        <div class="text-center">
                            <strong class="text-danger">{{ $product_count['reject_product_count'] }}</strong>
                            <div class="d-flex">
                                <span>{{\App\CPU\translate('rejected')}}</span>
                            </div>
                        </div>
                        <div class="text-center">
                            <strong class="text-primary">{{ $product_count['pending_product_count'] }}</strong>
                            <div class="d-flex">
                                <span>{{\App\CPU\translate('pending')}}</span>
                            </div>
                        </div>
                        <div class="text-center">
                            <strong class="text-success">{{ $product_count['active_product_count'] }}</strong>
                            <div class="d-flex">
                                <span>{{\App\CPU\translate('active')}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="left-content-card">
                    <img src="{{asset('/assets/back-end/img/products.svg')}}" alt="">
                    <div class="info">
                        <h4 class="subtitle">
                            {{ $total_product_sale }}
                        </h4>
                        <h6 class="subtext">{{\App\CPU\translate('Total_Product_Sale')}}</h6>
                    </div>
                </div>
                <div class="left-content-card">
                    <img src="{{asset('/assets/back-end/img/stores.svg')}}" alt="">
                    <div class="info">
                        <h4 class="subtitle">
                            {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total_discount_given)) }}
                        </h4>
                        <h6 class="subtext d-flex">
                            {{\App\CPU\translate('Total_Discount_Given')}}
                            <span class="ml-2" data-toggle="tooltip" data-placement="top" title="{{\App\CPU\translate('product_wise_discounted_amount_will_be_shown_here')}}">
                                <img class="info-img" src="{{asset('/assets/back-end/img/info-circle.svg')}}" alt="img">
                            </span>
                        </h6>
                    </div>
                </div>
            </div>
            @foreach(array_values($chart_data['total_product']) as $amount)
                @php($chart_val[] = \App\CPU\BackEndHelper::usd_to_currency($amount))
            @endforeach
            <div class="center-chart-area size-lg">
                <div class="center-chart-header">
                    <h3 class="title d-flex">{{\App\CPU\translate('Product_Statistics')}}
                        <span class="ml-2" data-toggle="tooltip" data-placement="top" title="{{\App\CPU\translate('The_product_report_will_show_based_on_the_product_added_date')}}">
                            <img class="info-img" src="{{asset('/assets/back-end/img/info-circle.svg')}}" alt="img">
                        </span>
                    </h3>
                </div>
                <canvas id="updatingData" class="store-center-chart"
                    data-hs-chartjs-options='{
                "type": "bar",
                "data": {
                  "labels": [{{ '"'.implode('","', array_keys($chart_data['total_product'])).'"' }}],
                  "datasets": [{
                    "label": "{{\App\CPU\translate('total_product')}}",
                    "data": [{{ '"'.implode('","', array_values($chart_val)).'"' }}],
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
                        "postfix": " "
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
        </div>

        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex flex-wrap w-100 gap-3 align-items-center">
                    <h4 class="mb-0 mr-auto">
                        {{\App\CPU\translate('Total_Product')}}
                        <span class="badge badge-soft-dark radius-50 fz-12"> {{ $products->total() }}</span>
                    </h4>
                    <form action="#" method="GET">
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
                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                    placeholder="{{\App\CPU\translate('Search Product Name')}}" aria-label="Search orders" value="{{ $search }}">
                            <button type="submit" class="btn btn--primary">{{\App\CPU\translate('search')}}</button>
                        </div>
                        <!-- End Search -->
                    </form>
                    <div>
                        <button type="button" class="btn btn-outline--primary text-nowrap btn-block" data-toggle="dropdown">
                            <i class="tio-download-to"></i>
                            {{ \App\CPU\translate('export') }}
                            <i class="tio-chevron-down"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a class="dropdown-item" href="{{ route('admin.report.all-product-excel', ['seller_id' => request('seller_id'), 'search' => request('search'), 'date_type' => request('date_type'), 'from' => request('from'), 'to' => request('to')]) }}">{{\App\CPU\translate('excel')}}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" id="products-table">
                    <table class="table table-hover __table table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}">
                        <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>{{\App\CPU\translate('SL')}}</th>
                            <th>
                                {{\App\CPU\translate('Product_Name')}}
                            </th>
                            <th>
                                {{\App\CPU\translate('Product_Unit_Price')}}
                            </th>
                            <th>
                                {{\App\CPU\translate('Total_Amount_Sold')}}
                            </th>
                            <th>
                                {{\App\CPU\translate('Total_Quantity_Sold')}}
                            </th>
                            <th>
                                <div class="d-flex">
                                    <span>{{\App\CPU\translate('Average_Product_Value')}} </span>
                                    <span class="ml-2" data-toggle="tooltip" data-placement="right" title="lorem ipsum dolor set amet">
                                        <img class="info-img" src="{{asset('/assets/back-end/img/info-circle.svg')}}" alt="img">
                                    </span>
                                </div>
                            </th>
                            <th>
                                {{\App\CPU\translate('Current_Stock_Amount')}}
                            </th>
                            <th>
                                {{\App\CPU\translate('Average_Ratings')}}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $key=>$product)
                            <tr>
                                <td>{{ $products->firstItem()+$key }}</td>
                                <td>
                                    <a href="{{route('admin.product.view',[$product['id']])}}">
                                            <span class="media-body title-color hover-c1">
                                                {{\Illuminate\Support\Str::limit($product['name'], 20)}}
                                            </span>
                                    </a>
                                </td>
                                <td>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($product->unit_price)) }}</td>
                                <td>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency(isset($product->order_details[0]->total_sold_amount) ? $product->order_details[0]->total_sold_amount : 0)) }}</td>
                                <td>{{ isset($product->order_details[0]->product_quantity) ? $product->order_details[0]->product_quantity : 0 }}</td>
                                <td>
                                    {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency(
                                        (isset($product->order_details[0]->total_sold_amount) ? $product->order_details[0]->total_sold_amount : 0) /
                                        (isset($product->order_details[0]->product_quantity) ? $product->order_details[0]->product_quantity : 1)))
                                    }}
                                </td>
                                <td>
                                    {{ $product->product_type == 'digital' ? ($product->status==1 ? \App\CPU\translate('available') : \App\CPU\translate('not_available')) : $product->current_stock }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rating mr-1"><i class="tio-star"></i>
                                            {{count($product->rating)>0?number_format($product->rating[0]->average, 2, '.', ' '):0}}
                                        </div>
                                        <div>
                                            ( {{$product->reviews->count()}} )
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @if(count($products)==0)
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
            </div>
            <div class="table-responsive mt-4">
                <div class="px-4 d-flex justify-content-center justify-content-md-end">
                    <!-- Pagination -->
                    {!! $products->links() !!}
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

    <!-- Apex Charts -->
    <script src="{{ asset('/assets/back-end/js/apexcharts.js') }}"></script>
    <!-- Apex Charts -->

@endpush

@push('script_2')
    <script>
        // Bar Charts
        Chart.plugins.unregister(ChartDataLabels);

        $('.js-chart').each(function() {
            $.HSCore.components.HSChartJS.init($(this));
        });

        var updatingChart = $.HSCore.components.HSChartJS.init($('#updatingData'));

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
