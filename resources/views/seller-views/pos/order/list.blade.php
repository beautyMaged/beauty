@extends('layouts.back-end.app-seller')

@section('title', \App\CPU\translate('POS Order List'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <!-- <div class="page-header mb-1">
            <div class="flex-between align-items-center">
                <div>
                    <h1 class="page-header-title">{{\App\CPU\translate('pos_orders')}} <span
                            class="badge badge-soft-dark mx-2">{{$orders->total()}}</span></h1>
                </div>
                <div>
                    <i class="tio-shopping-cart" style="font-size: 30px"></i>
                </div>
            </div>

            <div class="js-nav-scroller hs-nav-scroller-horizontal">
            <span class="hs-nav-scroller-arrow-prev" style="display: none;">
              <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                <i class="tio-chevron-left"></i>
              </a>
            </span>

                <span class="hs-nav-scroller-arrow-next" style="display: none;">
              <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                <i class="tio-chevron-right"></i>
              </a>
            </span>
            
                <ul class="nav nav-tabs page-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">{{\App\CPU\translate('order_list')}}</a>
                    </li>
                </ul>
            </div>
        </div> -->
        <!-- End Page Header -->

        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex flex-wrap align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/inhouse-product-list.png')}}" class="mb-1 mr-1" alt="">
                {{\App\CPU\translate('POS_Orders')}}
                <span class="badge badge-soft-dark radius-50 fz-14">{{$orders->total()}}</span>
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="px-3 py-4">
                <div class="row gy-2 justify-content-between align-items-center">
                    <div class="col-lg-4">
                        <form action="{{ url()->current() }}" method="GET">
                            <!-- Search -->
                            <div class="input-group input-group-merge input-group-custom">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="tio-search"></i>
                                    </div>
                                </div>
                                <input id="datatableSearch_" type="search" name="search" class="form-control"
                                       placeholder="{{\App\CPU\translate('Search orders')}}" aria-label="Search orders" value="{{ $search }}"
                                       required>
                                <button type="submit" class="btn btn--primary">{{\App\CPU\translate('search')}}</button>
                            </div>
                            <!-- End Search -->
                        </form>
                    </div>
                    <div class="col-lg-7">
                        <form action="" method="GET" id="form-data">
                            <div class="d-flex justify-content-end flex-wrap flex-md-nowrap gap-3">
                                <input type="date" name="from" value="{{$from}}" id="from_date" class="form-control">
                                <input type="date" value="{{$to}}" name="to" id="to_date" class="form-control">
                                <button type="submit" class="btn btn--primary px-4" onclick="formUrlChange(this)" data-action="{{ url()->current() }}">
                                    {{\App\CPU\translate('filter')}}
                                </button>
                                <!-- <button type="submit" class="btn btn-success" onclick="formUrlChange(this)" data-action="{{ route('seller.pos.order-bulk-export') }}">
                                    {{\App\CPU\translate('export')}}
                                </button> -->
                                <div>
                                    <button type="button" class="btn btn-outline--primary text-nowrap" data-toggle="dropdown">
                                        <i class="tio-download-to"></i>
                                        Export
                                        <i class="tio-chevron-down"></i>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a class="dropdown-item" href="#">Excel</a></li>
                                        <div class="dropdown-divider"></div>
                                        <li><a class="dropdown-item" href="#">.CSV</a></li>
                                        <div class="dropdown-divider"></div>
                                        <li><a class="dropdown-item" href="#">Word</a></li>
                                    </ul>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- End Row -->
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                       style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}}">
                    <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>{{\App\CPU\translate('SL')}}</th>
                            <th>{{\App\CPU\translate('Order')}}</th>
                            <th>{{\App\CPU\translate('Date')}}</th>
                            <th>{{\App\CPU\translate('customer_name')}}</th>
                            <th>{{\App\CPU\translate('Status')}}</th>
                            <th>{{\App\CPU\translate('Total')}}</th>
                            <th>{{\App\CPU\translate('Order')}} {{\App\CPU\translate('Status')}} </th>
                            <th class="text-center">{{\App\CPU\translate('Action')}}</th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach($orders as $key=>$order)
                        <tr class="status-{{$order['order_status']}} class-all">
                            <td class="">
                                {{$orders->firstItem()+$key}}
                            </td>
                            <td>
                                <a href="{{route('seller.pos.order-details',['id'=>$order['id']])}}" class="title-color hover-c1">{{$order['id']}}</a>
                            </td>
                            <td>{{date('d M Y',strtotime($order['created_at']))}}</td>
                            <td>
                                @if($order->customer)
                                    <a class="text-body text-capitalize"
                                       href="{{route('seller.orders.details',['id'=>$order['id']])}}">{{$order->customer['f_name'].' '.$order->customer['l_name']}}</a>
                                @else
                                    <label class="badge badge-danger">{{\App\CPU\translate('invalid_customer_data')}}</label>
                                @endif
                            </td>
                            <td>
                                @if($order->payment_status=='paid')
                                    <span class="badge badge-soft-success">{{\App\CPU\translate('paid')}}</span>
                                @else
                                    <span class="badge badge-soft-danger">{{\App\CPU\translate('unpaid')}}
                                @endif
                            </td>
                            <td> {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->order_amount))}}</td>
                            <td class="text-capitalize">
                                @if($order['order_status']=='pending')
                                    <span class="badge badge-soft-info">{{$order['order_status']}}</span>
                                @elseif($order['order_status']=='processing' || $order['order_status']=='out_for_delivery')
                                    <span class="badge badge-soft-warning">{{$order['order_status']}}</span>
                                @elseif($order['order_status']=='confirmed')
                                    <span class="badge badge-soft-success">{{$order['order_status']}}</span>
                                @elseif($order['order_status']=='failed')
                                    <span class="badge badge-danger">{{$order['order_status']}}</span>
                                @elseif($order['order_status']=='delivered')
                                    <span class="badge badge-soft-success">{{$order['order_status']}}</span>
                                @else
                                    <span class="badge badge-soft-danger">{{$order['order_status']}}</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a class="btn btn-outline--primary btn-sm"
                                        title="{{\App\CPU\translate('view')}}"
                                        href="{{route('seller.pos.order-details',['id'=>$order['id']])}}"><i
                                            class="tio-invisible"></i>
                                    </a>
                                    <a class="btn btn-outline-info btn-sm" target="_blank"
                                        title="{{\App\CPU\translate('invoice')}}"
                                        href="{{route('seller.orders.generate-invoice',[$order['id']])}}"><i
                                            class="tio-download"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if(count($orders)==0)
                    <div class="text-center p-4">
                        <img class="mb-3 w-160" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description">
                        <p class="mb-0">{{ \App\CPU\translate('No_data_to_show')}}</p>
                    </div>
                @endif
            </div>
            <!-- End Table -->

            <div class="table-responsive mt-4">
                <div class="px-4 d-flex justify-content-lg-end">
                    <!-- Pagination -->
                    {!! $orders->links() !!}
                </div>
            </div>
            <!-- Footer -->
            <!-- <div class="card-footer">
                <div class="row table-responsive">
                    <div class="">
                        <div class="">
                            {!! $orders->links() !!}
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- End Footer -->
        </div>
        <!-- End Card -->
    </div>
@endsection

@push('script_2')
    <script>
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
                    toastr.error('{{\App\CPU\translate('Invalid date range')}}!', Error, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }

        })
    </script>
@endpush
