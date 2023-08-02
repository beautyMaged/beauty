@extends('layouts.back-end.app')

@section('title',\App\CPU\translate('reund_requests'))

@section('content')
    <div class="content container-fluid">

        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/assets/back-end/img/refund-request.png')}}" alt="">
                @if($status == 'pending')
                    {{\App\CPU\translate('Pending_Refund_Requests')}}
                @elseif($status == 'approved')
                    {{\App\CPU\translate('approved_Refund_Requests')}}

                @elseif($status == 'refunded')
                    {{\App\CPU\translate('refunded_Refund_Requests')}}

                @elseif($status == 'rejected')
                    {{\App\CPU\translate('rejected_Refund_Requests')}}

                @endif
                <span class="badge badge-soft-dark radius-50">{{$refund_list->total()}}</span>
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="p-3">
                <div class="row justify-content-between align-items-center">
                    <div class="col-12 col-md-4">
                        <form action="{{ url()->current() }}" method="GET">
                            <!-- Search -->
                            <div class="input-group input-group-merge input-group-custom">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="tio-search"></i>
                                    </div>
                                </div>
                                <input id="datatableSearch_" type="search" name="search" class="form-control"
                                       placeholder="{{\App\CPU\translate('Search_by_order_id_or_refund_id')}}"
                                       aria-label="Search orders" value="{{ $search }}">
                                <button type="submit" class="btn btn--primary">{{\App\CPU\translate('search')}}</button>
                            </div>
                            <!-- End Search -->
                        </form>
                    </div>
                    <div class="col-12 mt-3 col-md-8">
                        <div class="d-flex gap-3 justify-content-md-end">
                            <label class="mb-0"> {{\App\CPU\translate('inhouse_orders_only')}} </label>
                            <label class="switcher">
                                <input type="checkbox" class="switcher_input"
                                       onclick="filter_order()" {{session()->has('show_inhouse_orders') && session('show_inhouse_orders')==1?'checked':''}}>
                                <span class="switcher_control"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <!-- End Row -->
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table
                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100"
                    style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}}">
                    <thead class="thead-light thead-50 text-capitalize">
                    <tr>
                        <th>{{\App\CPU\translate('SL')}}</th>
                        <th>{{\App\CPU\translate('order_id')}} </th>
                        <th>{{\App\CPU\translate('product_info')}}</th>
                        <th>{{\App\CPU\translate('customer_info')}}</th>
                        <th class="text-end">{{\App\CPU\translate('total_amount')}}</th>
                        <th>{{\App\CPU\translate('refund_status')}}</th>
                        <th class="text-center">{{\App\CPU\translate('Action')}}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($refund_list as $key=>$refund)
                        <tr>
                            <td>{{$refund_list->firstItem()+$key}}</td>
                            <td>
                                <a href="{{route('admin.orders.details',['id'=>$refund->order_id])}}"
                                   class="title-color hover-c1">
                                    {{$refund->order_id}}
                                </a>
                            </td>
                            <td>
                                @if ($refund->product!=null)
                                    {{--                                @dd($refund)--}}
                                    {{--                                @dd($refund->product->thumbnail )--}}
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="{{route('admin.product.view',[$refund->product->id])}}">
                                            <img onerror="this.src='{{asset('/assets/back-end/img/brand-logo.png')}}'"
                                                 src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{ $refund->product->thumbnail }}"
                                                 class="avatar border" alt="">
                                        </a>
                                        <div class="d-flex flex-column gap-1">
                                            <a href="{{route('admin.product.view',[$refund->product->id])}}"
                                               class="title-color font-weight-bold hover-c1">
                                                {{\Illuminate\Support\Str::limit($refund->product->name,35)}}
                                            </a>
                                            <span
                                                class="fz-12">{{\App\CPU\translate('QTY')}} : {{ $refund->order_details->qty }}</span>
                                        </div>
                                    </div>
                                @else
                                    {{\App\CPU\translate('product_name_not_found')}}
                                @endif

                            </td>
                            <td>
                                @if ($refund->customer !=null)
                                    <div class="d-flex flex-column gap-1">
                                        <a href="{{route('admin.customer.view',[$refund->customer->id])}}"
                                           class="title-color font-weight-bold hover-c1">
                                            {{$refund->customer->f_name. ' '.$refund->customer->l_name}}
                                        </a>
                                        <a href="tel:{{$refund->customer->phone}}"
                                           class="title-color hover-c1 fz-12">{{$refund->customer->phone}}</a>
                                    </div>
                                @else
                                    <a href="#" class="title-color hover-c1">
                                        {{\App\CPU\translate('customer_not_found')}}
                                    </a>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1 text-end">
                                    <div>{{\App\CPU\Helpers::currency_converter($refund->amount)}}</div>
                                </div>
                            </td>
                            <td>
                                <div class="d-inline-flex flex-column gap-1">
                                    @if($refund->status=='pending')
                                        <span
                                            class="badge badge-soft--primary">{{\App\CPU\translate($refund->status)}}</span>
                                    @elseif($refund->status=='approved')
                                        <span
                                            class="badge badge-soft-success">{{\App\CPU\translate($refund->status)}}</span>
                                    @elseif($refund->status=='rejected')
                                        <span
                                            class="badge badge-soft-danger">{{\App\CPU\translate($refund->status)}}</span>
                                    @else
                                        <span
                                            class="badge badge-soft-warning">{{\App\CPU\translate($refund->status)}}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <a class="btn btn-outline--primary btn-sm"
                                       title="{{\App\CPU\translate('view')}}"
                                       href="{{route('admin.refund-section.refund.details',['id'=>$refund['id']])}}">
                                        <i class="tio-invisible"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- End Table -->

            <div class="table-responsive mt-4">
                <div class="px-4 d-flex justify-content-lg-end">
                    <!-- Pagination -->
                    {!! $refund_list->links() !!}
                </div>
            </div>

            @if(count($refund_list)==0)
                <div class="text-center p-4">
                    <img class="mb-3 w-160" src="{{asset('assets/back-end')}}/svg/illustrations/sorry.svg"
                         alt="Image Description">
                    <p class="mb-0">{{ \App\CPU\translate('No_data_to_show')}}</p>
                </div>
        @endif
        <!-- End Footer -->
        </div>
        <!-- End Card -->
    </div>
@endsection

@push('script_2')
    <script>
        function filter_order() {
            $.get({
                url: '{{route('admin.refund-section.refund.inhouse-order-filter')}}',
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    toastr.success('{{\App\CPU\translate('order_filter_success')}}');
                    location.reload();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        };
    </script>
@endpush
