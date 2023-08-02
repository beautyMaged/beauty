@extends('layouts.back-end.app')

@section('title',\App\CPU\translate('customer_wallet'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3 d-flex justify-content-between flex-wrap gap-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/assets/back-end/img/admin-wallet.png')}}" alt="">
                {{\App\CPU\translate('wallet')}}
            </h2>
            @if($customer_status == 1)
                <button type="button" class="btn btn--primary" data-toggle="modal" data-target="#addFundModal">
                    {{\App\CPU\translate('Add_Fund')}}
                </button>
            @endif
        </div>
        <!-- End Page Title -->

        <div class="modal fade" id="addFundModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header d-flex justify-content-between">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{\App\CPU\translate('Add_Fund')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin.customer.wallet.add-fund')}}" method="post" enctype="multipart/form-data" id="add_fund">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6 col-12">
                                    <div class="form-group">
                                        <label class="input-label d-flex" for="customer">{{\App\CPU\translate('customer')}}</label>
                                        <select id='form-customer' name="customer_id" data-placeholder="{{\App\CPU\translate('select_customer')}}" class="js-data-example-ajax form-control w-100" required>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="form-group">
                                        <label class="input-label d-flex" for="amount">{{\App\CPU\translate('amount')}}</label>

                                        <input type="number" class="form-control" name="amount" id="amount" step=".01" placeholder="Ex: 500" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="input-label d-flex align-items-center gap-1" for="referance">{{\App\CPU\translate('reference')}} <small>({{\App\CPU\translate('optional')}})</small></label>

                                        <input type="text" class="form-control" name="referance" placeholder="Ex: abc990" id="referance">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-3">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{\App\CPU\translate('close')}}</button>
                                <button type="submit" id="submit" class="btn btn--primary px-4">{{\App\CPU\translate('submit')}}</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <div class="card  mt-3">
            <div class="card-header text-capitalize">
                <h4 class="mb-0">{{\App\CPU\translate('filter')}} {{\App\CPU\translate('options')}}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 pt-3">
                        <form action="{{route('admin.customer.wallet.report')}}" method="get">
                            <div class="row">
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <input type="date" name="from" id="from_date" value="{{request()->get('from')}}" class="form-control" title="{{\App\CPU\translate('from')}} {{\App\CPU\translate('date')}}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <input type="date" name="to" id="to_date" value="{{request()->get('to')}}" class="form-control" title="{{ucfirst(\App\CPU\translate('to'))}} {{\App\CPU\translate('date')}}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        @php
                                        $transaction_status=request()->get('transaction_type');
                                        @endphp
                                        <select name="transaction_type" id="" class="form-control" title="{{\App\CPU\translate('select')}} {{\App\CPU\translate('transaction_type')}}">
                                            <option value="">{{\App\CPU\translate('all')}}</option>
                                            <option value="add_fund_by_admin" {{isset($transaction_status) && $transaction_status=='add_fund_by_admin'?'selected':''}} >{{\App\CPU\translate('add_fund_by_admin')}}</option>
                                            <!-- <option value="add_fund" {{isset($transaction_status) && $transaction_status=='add_fund'?'selected':''}}>{{\App\CPU\translate('add_fund_by_customer')}}</option> -->
                                            <option value="order_refund" {{isset($transaction_status) && $transaction_status=='order_refund'?'selected':''}}>{{\App\CPU\translate('refund_order')}}</option>
                                            <option value="loyalty_point" {{isset($transaction_status) && $transaction_status=='loyalty_point'?'selected':''}}>{{\App\CPU\translate('customer_loyalty_point')}}</option>
                                            <option value="order_place" {{isset($transaction_status) && $transaction_status=='order_place'?'selected':''}}>{{\App\CPU\translate('order_place')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12">
                                    <div class="mb-3">
                                        <select id='customer' name="customer_id" data-placeholder="{{\App\CPU\translate('select_customer')}}" class="js-data-example-ajax form-control" title="{{\App\CPU\translate('select_customer')}}">
                                            @if (request()->get('customer_id') && $customer_info = \App\User::find(request()->get('customer_id')))
                                                <option value="{{$customer_info->id}}" selected>{{$customer_info->f_name.' '.$customer_info->l_name}}({{$customer_info->phone}})</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn--primary px-4"><i class="tio-filter-list mr-1"></i>{{\App\CPU\translate('filter')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <div class="card mt-3">
            <div class="card-header text-capitalize">
                <h4 class="mb-0">{{\App\CPU\translate('summary')}}</h4>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-3">
                    @php
                        $credit = $data[0]->total_credit;
                        $debit = $data[0]->total_debit;
                        $balance = $credit - $debit;
                    @endphp
                    <!--Debit earned-->
                    <div class="order-stats flex-grow-1">
                        <div class="order-stats__content">
                            <i class="tio-atm"></i>
                            <h6 class="order-stats__subtitle">{{\App\CPU\translate('debit')}}</h6>
                        </div>
                        <span class="order-stats__title fz-14 text--primary">
                            {{\App\CPU\Helpers::currency_converter($debit)}}
                        </span>
                    </div>
                    <!--Debit earned End-->

                    <!--credit earned-->
                    <div class="order-stats flex-grow-1">
                        <div class="order-stats__content">
                            <i class="tio-money"></i>
                            <h6 class="order-stats__subtitle">{{\App\CPU\translate('credit')}}</h6>
                        </div>
                        <span class="order-stats__title fz-14 text-warning">
                        {{\App\CPU\Helpers::currency_converter($credit)}}
                        </span>
                    </div>
                    <!--credit earned end-->

                    <!--balance earned-->
                    <div class="order-stats flex-grow-1">
                        <div class="order-stats__content">
                            <i class="tio-wallet"></i>
                            <h6 class="order-stats__subtitle">{{\App\CPU\translate('balance')}}</h6>
                        </div>
                        <span class="order-stats__title fz-14 text-success">
                            {{\App\CPU\Helpers::currency_converter($balance)}}
                        </span>
                    </div>
                    <!--balance earned end-->
                </div>
            </div>

        </div>

        <!-- End Stats -->
        <!-- Card -->
        <div class="card mt-3">
            <!-- Header -->
            <div class="card-header text-capitalize">
                <h4 class="mb-0">
                    {{\App\CPU\translate('transactions')}}
                    <span class="badge badge-soft-dark radius-50 fz-12 ml-1">{{$transactions->count()}}</span>
                </h4>
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive">
                <table id="datatable"
                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}">
                    <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>{{\App\CPU\translate('sl')}}</th>
                            <th>{{\App\CPU\translate('transaction id')}}</th>
                            <th>{{\App\CPU\translate('Customer')}}</th>
                            <th>{{\App\CPU\translate('credit')}}</th>
                            <th>{{\App\CPU\translate('debit')}}</th>
                            <th>{{\App\CPU\translate('balance')}}</th>
                            <th>{{\App\CPU\translate('transaction_type')}}</th>
                            <th>{{\App\CPU\translate('reference')}}</th>
                            <th class="text-center">{{\App\CPU\translate('created_at')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($transactions as $k=>$wt)
                        <tr>
                            <td >{{$k+$transactions->firstItem()}}</td>
                            <td>{{$wt->transaction_id}}</td>
                            <td><a href="{{route('admin.customer.view',['user_id'=>$wt->user_id])}}" class="title-color hover-c1">{{Str::limit($wt->user?$wt->user->f_name.' '.$wt->user->l_name:\App\CPU\translate('not_found'),20,'...')}}</a></td>
                            <td>{{\App\CPU\Helpers::currency_converter($wt->credit)}}</td>
                            <td>{{\App\CPU\Helpers::currency_converter($wt->debit)}}</td>
                            <td>{{\App\CPU\Helpers::currency_converter($wt->balance)}}</td>
                            <td>
                                <span class="badge badge-soft-{{$wt->transaction_type=='order_refund'
                                    ?'danger'
                                    :($wt->transaction_type=='loyalty_point'?'warning'
                                        :($wt->transaction_type=='order_place'
                                            ?'info'
                                            :'success'))
                                    }}">
                                    {{\App\CPU\translate($wt->transaction_type)}}
                                </span>
                            </td>
                            <td>{{ str_replace('_',' ',$wt->reference) }}</td>
                            <td class="text-center">{{date('Y/m/d '.config('timeformat'), strtotime($wt->created_at))}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- End Body -->

            <div class="table-responsive mt-4">
                <div class="px-4 d-flex justify-content-lg-end">
                    <!-- Pagination -->
                    {!!$transactions->links()!!}
                </div>
            </div>

            @if(count($transactions)==0)
                <div class="text-center p-4">
                    <img class="mb-3 w-160" src="{{asset('assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description">
                    <p class="mb-0">{{ \App\CPU\translate('No_data_to_show')}}</p>
                </div>
            @endif

        </div>
        <!-- End Card -->
    </div>
@endsection

@push('script')

@endpush

@push('script_2')
    <script>
        $(document).on('ready', function () {
            $('.js-data-example-ajax').select2({
                ajax: {
                    url: '{{route('admin.customer.customer-list-search')}}',
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            all:true,
                            page: params.page
                        };
                    },
                    processResults: function (data) {
                        return {
                        results: data
                        };
                    },
                    __port: function (params, success, failure) {
                        var $request = $.ajax(params);

                        $request.then(success);
                        $request.fail(failure);

                        return $request;
                    }
                }
            });

            // INITIALIZATION OF FLATPICKR
            // =======================================================
            $('.js-flatpickr').each(function () {
                $.HSCore.components.HSFlatpickr.init($(this));
            });


            // INITIALIZATION OF NAV SCROLLER
            // =======================================================
            $('.js-nav-scroller').each(function () {
                new HsNavScroller($(this)).init()
            });


            // INITIALIZATION OF DATERANGEPICKER
            // =======================================================
            $('.js-daterangepicker').daterangepicker();

            $('.js-daterangepicker-times').daterangepicker({
                timePicker: true,
                startDate: moment().startOf('hour'),
                endDate: moment().startOf('hour').add(32, 'hour'),
                locale: {
                    format: 'M/DD hh:mm A'
                }
            });

            var start = moment();
            var end = moment();

            function cb(start, end) {
                $('#js-daterangepicker-predefined .js-daterangepicker-predefined-preview').html(start.format('MMM D') + ' - ' + end.format('MMM D, YYYY'));
            }

            $('#js-daterangepicker-predefined').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            cb(start, end);


            // INITIALIZATION OF CHARTJS
            // =======================================================
            $('.js-chart').each(function () {
                $.HSCore.components.HSChartJS.init($(this));
            });

            var updatingChart = $.HSCore.components.HSChartJS.init($('#updatingData'));

            // Call when tab is clicked
            $('[data-toggle="chart"]').click(function (e) {
                let keyDataset = $(e.currentTarget).attr('data-datasets')

                // Update datasets for chart
                updatingChart.data.datasets.forEach(function (dataset, key) {
                    dataset.data = updatingChartDatasets[keyDataset][key];
                });
                updatingChart.update();
            })


            // INITIALIZATION OF MATRIX CHARTJS WITH CHARTJS MATRIX PLUGIN
            // =======================================================
            function generateHoursData() {
                var data = [];
                var dt = moment().subtract(365, 'days').startOf('day');
                var end = moment().startOf('day');
                while (dt <= end) {
                    data.push({
                        x: dt.format('YYYY-MM-DD'),
                        y: dt.format('e'),
                        d: dt.format('YYYY-MM-DD'),
                        v: Math.random() * 24
                    });
                    dt = dt.add(1, 'day');
                }
                return data;
            }

            $.HSCore.components.HSChartMatrixJS.init($('.js-chart-matrix'), {
                data: {
                    datasets: [{
                        label: 'Commits',
                        data: generateHoursData(),
                        width: function (ctx) {
                            var a = ctx.chart.chartArea;
                            return (a.right - a.left) / 70;
                        },
                        height: function (ctx) {
                            var a = ctx.chart.chartArea;
                            return (a.bottom - a.top) / 10;
                        }
                    }]
                },
                options: {
                    tooltips: {
                        callbacks: {
                            title: function () {
                                return '';
                            },
                            label: function (item, data) {
                                var v = data.datasets[item.datasetIndex].data[item.index];

                                if (v.v.toFixed() > 0) {
                                    return '<span class="font-weight-bold">' + v.v.toFixed() + ' hours</span> on ' + v.d;
                                } else {
                                    return '<span class="font-weight-bold">No time</span> on ' + v.d;
                                }
                            }
                        }
                    },
                    scales: {
                        xAxes: [{
                            position: 'bottom',
                            type: 'time',
                            offset: true,
                            time: {
                                unit: 'week',
                                round: 'week',
                                displayFormats: {
                                    week: 'MMM'
                                }
                            },
                            ticks: {
                                "labelOffset": 20,
                                "maxRotation": 0,
                                "minRotation": 0,
                                "fontSize": 12,
                                "fontColor": "rgba(22, 52, 90, 0.5)",
                                "maxTicksLimit": 12,
                            },
                            gridLines: {
                                display: false
                            }
                        }],
                        yAxes: [{
                            type: 'time',
                            offset: true,
                            time: {
                                unit: 'day',
                                parser: 'e',
                                displayFormats: {
                                    day: 'ddd'
                                }
                            },
                            ticks: {
                                "fontSize": 12,
                                "fontColor": "rgba(22, 52, 90, 0.5)",
                                "maxTicksLimit": 2,
                            },
                            gridLines: {
                                display: false
                            }
                        }]
                    }
                }
            });


            // INITIALIZATION OF CLIPBOARD
            // =======================================================
            $('.js-clipboard').each(function () {
                var clipboard = $.HSCore.components.HSClipboard.init(this);
            });


            // INITIALIZATION OF CIRCLES
            // =======================================================
            $('.js-circle').each(function () {
                var circle = $.HSCore.components.HSCircles.init($(this));
            });
        });
    </script>

    <script>
        $('#from_date,#to_date').change(function () {
            let fr = $('#from_date').val();
            let to = $('#to_date').val();
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
    </script>

    <script>
        $('#add_fund').on('submit', function (e) {

            e.preventDefault();
            var formData = new FormData(this);

            Swal.fire({
                title: '{{\App\CPU\translate('are_you_sure')}}',
                text: '{{\App\CPU\translate('you_want_to_add_fund')}}'+$('#amount').val()+' {{\App\CPU\Helpers::currency_code().' '.\App\CPU\translate('to')}} '+$('#form-customer option:selected').text()+'{{\App\CPU\translate('to_wallet')}}',
                type: 'info',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: 'primary',
                cancelButtonText: '{{\App\CPU\translate('no')}}',
                confirmButtonText: '{{\App\CPU\translate('add')}}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.post({
                        url: '{{route('admin.customer.wallet.add-fund')}}',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            if (data.errors) {
                                for (var i = 0; i < data.errors.length; i++) {
                                    toastr.error(data.errors[i].message, {
                                        CloseButton: true,
                                        ProgressBar: true
                                    });
                                }
                            } else {
                                toastr.success('{{\App\CPU\translate("fund_added_successfully")}}', {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                                location.reload();
                            }
                        }
                    });
                }
            })
        })

        $('.js-data-example-ajax').select2({
            ajax: {
                url: '{{route('admin.customer.customer-list-search')}}',
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                __port: function (params, success, failure) {
                    var $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }
            }
        });
    </script>
@endpush
