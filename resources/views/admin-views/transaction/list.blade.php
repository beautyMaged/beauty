@extends('layouts.back-end.app')

@section('content')
    <div class="content container-fluid ">
        <!-- Page Title -->
        <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('assets/back-end/img/order_report.png')}}" alt="">
                {{\App\CPU\translate('transaction_table')}}
                <span class="badge badge-soft-dark radius-50 fz-12">{{$transactions->total()}}</span>
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="card">
            <div class="px-3 py-4">
                <div class="row gy-2">
                    <div class="col-xl-3">
                        <form action="" method="GET">
                            <!-- Search -->
                            <div class="input-group input-group-merge input-group-custom">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="tio-search"></i>
                                    </div>
                                </div>
                                <input id="datatableSearch_" type="search" name="search" class="form-control"
                                       placeholder="{{ \App\CPU\translate('Search by orders id or transaction id')}}"
                                       aria-label="Search orders"
                                       value="{{ $search }}"
                                       required>
                                <button type="submit"
                                        class="btn btn--primary">{{ \App\CPU\translate('search')}}</button>
                            </div>
                            <!-- End Search -->
                        </form>
                    </div>
                    <div class="col-xl-9">
                        <form action="#" id="form-data" method="GET">
                            <div
                                class="row  gy-2 align-items-center text-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}">
                                <div class="col-md-3">
                                    <div class="">
                                        <select class="js-select2-custom form-control" name="customer_id">
                                            <option class="text-center" value="0">
                                                ---{{\App\CPU\translate('select_customer')}}---
                                            </option>
                                            @foreach($customers as $customer)
                                                <option class="text-left text-capitalize"
                                                        value="{{ $customer->id }}" {{ $customer->id == $customer_id ? 'selected' : '' }}>
                                                    {{ $customer->f_name.' '.$customer->l_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="">
                                        <select class="form-control" name="status">
                                            <option class="text-center" value="0" disabled>
                                                ---{{\App\CPU\translate('select_status')}}---
                                            </option>
                                            <option class="text-capitalize"
                                                    value="all" {{ $status == 'all'? 'selected' : '' }} >{{\App\CPU\translate('all')}} </option>
                                            <option class="text-capitalize"
                                                    value="disburse" {{ $status == 'disburse'? 'selected' : '' }} >{{\App\CPU\translate('disburse')}} </option>
                                            <option class="text-capitalize"
                                                    value="hold" {{ $status == 'hold'? 'selected' : '' }}>{{\App\CPU\translate('hold')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="">
                                        <input type="date" name="from" value="{{$from}}" id="from_date"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="">
                                        <input type="date" value="{{$to}}" name="to" id="to_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12 d-flex justify-content-end gap-2">
                                    <button type="submit" class="btn btn--primary px-4" onclick="formUrlChange(this)"
                                            data-action="{{ url()->current() }}">
                                        {{\App\CPU\translate('filter')}}
                                    </button>
                                    <div>
                                        <button type="button" class="btn btn--primary text-nowrap btn-block"
                                                data-toggle="dropdown">
                                            <i class="tio-download-to"></i>
                                            {{\App\CPU\translate('Export')}}
                                            <i class="tio-chevron-down"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li><a class="dropdown-item"
                                                   href="{{ route('admin.transaction.transaction-export', ['customer_id'=>request('customer_id'), 'status'=>request('status'), 'from'=>request('from'), 'to'=>request('to')]) }}"  >{{\App\CPU\translate('Excel')}}</a>
                                            </li>
                                            <div class="dropdown-divider"></div>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="datatable"
                       style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                       class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                    <thead class="thead-light thead-50 text-capitalize">
                    <tr>
                        <th>{{\App\CPU\translate('SL')}}</th>
                        <th>{{\App\CPU\translate('seller_name')}}</th>
                        <th>{{\App\CPU\translate('customer_name')}}</th>
                        <th>{{\App\CPU\translate('order_id')}}</th>
                        <th>{{\App\CPU\translate('transaction_id')}}</th>
                        <th>{{\App\CPU\translate('order_amount')}}</th>
                        <th>{{ \App\CPU\translate('seller_amount') }}</th>
                        <th>{{\App\CPU\translate('admin_commission')}}</th>
                        <th>{{\App\CPU\translate('received_by')}}</th>
                        <th>{{\App\CPU\translate('delivered_by')}}</th>
                        <th>{{\App\CPU\translate('delivery_charge')}}</th>
                        <th>{{\App\CPU\translate('payment_method')}}</th>
                        <th>{{\App\CPU\translate('tax')}}</th>
                        <th>{{\App\CPU\translate('date')}}</th>
                        <th>{{\App\CPU\translate('status')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($transactions as $key=>$transaction)
                        <tr>
                            <td>{{$transactions->firstItem()+$key}}</td>
                            <td>
                                @if($transaction['seller_is'] == 'admin')
                                    {{ \App\CPU\Helpers::get_business_settings('company_name') }}
                                @else
                                    @if (isset($transaction->seller))
                                        {{ $transaction->seller->f_name }} {{ $transaction->seller->l_name }}
                                    @else
                                        {{\App\CPU\translate('not_found')}}
                                    @endif
                                @endif

                            </td>
                            <td>
                                @if (isset($transaction->customer))
                                    {{ $transaction->customer->f_name}} {{ $transaction->customer->l_name }}
                                @else
                                    {{\App\CPU\translate('not_found')}}
                                @endif
                            </td>
                            <td>{{$transaction['order_id']}}</td>
                            <td>{{$transaction['transaction_id']}}</td>
                            <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($transaction['order_amount']))}}</td>
                            <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($transaction['seller_amount']))}}</td>
                            <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($transaction['admin_commission']))}}</td>
                            <td>{{$transaction['received_by']}}</td>
                            <td>{{$transaction['delivered_by']}}</td>
                            <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($transaction['delivery_charge']))}}</td>
                            <td>{{str_replace('_',' ',$transaction['payment_method'])}}</td>
                            <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($transaction['tax']))}}</td>
                            <td>{{ date('d M Y',strtotime($transaction['created_at'])) }}</td>
                            <td>
                                @if($transaction['status'] == 'disburse')
                                    <span class="badge badge-soft-success">
                                        {{$transaction['status']}}
                                    </span>
                                @else
                                    <span class="badge badge-soft-warning ">
                                        {{$transaction['status']}}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
                @if(count($transactions)==0)
                    <div class="text-center p-4">
                        <img class="mb-3 w-160" src="{{asset('assets/back-end/svg/illustrations/sorry.svg')}}"
                             alt="Image Description">
                        <p class="mb-0">{{ \App\CPU\translate('No_data_to_show')}}</p>
                    </div>
                @endif
            </div>

            <div class="table-responsive mt-4">
                <div class="px-4 d-flex justify-content-lg-end">
                    <!-- Pagination -->
                    {{$transactions->links()}}
                </div>
            </div>

        </div>

    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function () {
            $('.js-select2-custom').select2();
        });

        $('#from_date,#to_date').change(function () {
            let fr = $('#from_date').val();
            let to = $('#to_date').val();
            if (fr != '') {
                $('#to_date').attr('required', 'required');
            }
            if (to != '') {
                $('#from_date').attr('required', 'required');
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
