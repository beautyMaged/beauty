@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('My Order List'))


@section('content')

    <div class="container text-center">
        <h3 class="headerTitle my-3">{{\App\CPU\translate('my_order')}}</h3>
    </div>

    <!-- Page Content-->
    <div class="container pb-5 mb-2 mb-md-4 mt-3 rtl"
         style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row">
            <!-- Sidebar-->
        @include('web-views.partials._profile-aside')
        <!-- Content  -->
            <section class="col-lg-9 col-md-9">
                <div class="card __card shadow-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table __table text-center">
                                <thead class="thead-light">
                                <tr>
                                    <td class="tdBorder">
                                        <div class="py-2"><span
                                                class="d-block spandHeadO ">{{\App\CPU\translate('Order ID')}}</span></div>
                                    </td>

                                    <td class="tdBorder orderDate">
                                        <div class="py-2"><span
                                                class="d-block spandHeadO">{{\App\CPU\translate('Order Date')}} </span>
                                        </div>
                                    </td>
                                    <td class="tdBorder">
                                        <div class="py-2"><span
                                                class="d-block spandHeadO"> {{\App\CPU\translate('Status')}}</span></div>
                                    </td>
                                    <td class="tdBorder">
                                        <div class="py-2"><span
                                                class="d-block spandHeadO"> {{\App\CPU\translate('Total')}}</span></div>
                                    </td>
                                    <td class="tdBorder">
                                        <div class="py-2"><span
                                                class="d-block spandHeadO"> {{\App\CPU\translate('action')}}</span></div>
                                    </td>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td class="bodytr font-weight-bold">
                                            {{\App\CPU\translate('ID')}}: {{$order['id']}}
                                        </td>
                                        <td class="bodytr orderDate"><span class="">{{$order['created_at']}}</span></td>
                                        <td class="bodytr">
                                            @if($order['order_status']=='failed' || $order['order_status']=='canceled')
                                                <span class="badge badge-danger text-capitalize">
                                                    {{\App\CPU\translate($order['order_status'] =='failed' ? 'Failed To Deliver' : $order['order_status'])}}
                                                </span>
                                            @elseif($order['order_status']=='confirmed' || $order['order_status']=='processing' || $order['order_status']=='delivered')
                                                <span class="badge badge-success text-capitalize">
                                                    {{\App\CPU\translate($order['order_status']=='processing' ? 'packaging' : $order['order_status'])}}
                                                </span>
                                            @else
                                                <span class="badge badge-info text-capitalize">
                                                    {{\App\CPU\translate($order['order_status'])}}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="bodytr">
                                            {{\App\CPU\Helpers::currency_converter($order['order_amount'])}}
                                        </td>
                                        <td class="bodytr">
                                            <div class="__btn-grp-sm flex-nowrap">
                                                <a href="{{ route('account-order-details', ['id'=>$order->id]) }}"
                                                class="btn btn--primary __action-btn" title="{{\App\CPU\translate('View')}}">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                @if($order['payment_method']=='cash_on_delivery' && $order['order_status']=='pending')
                                                    <a href="javascript:" title="{{\App\CPU\translate('Cancel')}}"
                                                    onclick="route_alert('{{ route('order-cancel',[$order->id]) }}','{{\App\CPU\translate('want_to_cancel_this_order?')}}')"
                                                    class="btn btn-danger __action-btn">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                @else
                                                    <button class="btn btn-danger __action-btn" title="{{\App\CPU\translate('Cancel')}}" onclick="cancel_message()">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            @if($orders->count()==0)
                                <center class="mb-2 mt-3">{{\App\CPU\translate('no_order_found')}}</center>
                            @endif

                            <div class="card-footer border-0">
                                {{$orders->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function cancel_message() {
            toastr.info('{{\App\CPU\translate('order_can_be_canceled_only_when_pending.')}}', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    </script>
@endpush
