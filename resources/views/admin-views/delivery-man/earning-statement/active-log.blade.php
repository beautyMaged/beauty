@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Earning_Statement'))

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/add-new-seller.png')}}" alt="">
                {{\App\CPU\translate('earning_statement')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
        @include('admin-views.delivery-man.pages-inline-menu')

        <div class="card mb-3">
            <div class="card-body">
                <div class="px-3 py-4">
                    <div class="row align-items-center">
                        <div class="col-md-4 col-lg-6 mb-2 mb-md-0">

                            <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                                {{ \App\CPU\translate('order_list') }}
                                <span class="badge badge-soft-dark radius-50 fz-12 ml-1">{{ $orders->total() }}</span>
                            </h4>
                        </div>
                        <div class="col-md-8 col-lg-6">
                            <div class="d-flex align-items-center justify-content-md-end flex-wrap flex-sm-nowrap gap-2">
                                <!-- Search -->
                                <form action="" method="GET">
                                    <div class="input-group input-group-merge input-group-custom">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control" placeholder="{{ \App\CPU\translate('Search_by_order_no') }}" aria-label="Search orders" value="{{ $search?? '' }}">
                                        <input type="hidden" name="page_name" value="active_log">
                                        <button type="submit" class="btn btn--primary">
                                            {{ \App\CPU\translate('Search') }}
                                        </button>
                                    </div>
                                </form>
                                <!-- End Search -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col-sm-12 mb-3">
                        <!-- Card -->
                        <div class="card">

                            <!-- Table -->
                            <div class="table-responsive datatable-custom">
                                <table class="table table-hover table-borderless table-thead-bordered table-align-middle card-table text-left">
                                    <thead class="thead-light thead-50 text-capitalize table-nowrap">
                                    <tr>
                                        <th>{{ \App\CPU\translate('SL') }}</th>
                                        <th>{{ \App\CPU\translate('order_no') }}</th>
                                        <th class="text-center">{{ \App\CPU\translate('current_status') }}</th>
                                        <th>{{ \App\CPU\translate('history') }}</th>
                                    </tr>
                                    </thead>

                                    <tbody id="set-rows">
                                    @forelse($orders as $key=>$order)
                                    <tr>
                                        <td>{{ $orders->firstItem()+$key }}</td>
                                        <td>
                                            <div class="media align-items-center gap-10 flex-wrap">
                                                <a class="title-color" title="{{\App\CPU\translate('order_details')}}"
                                                   href="{{route('admin.orders.details',['id'=>$order['id']])}}">
                                                    {{ $order->id }}
                                                </a>
                                            </div>
                                        </td>
                                        <td class="text-center text-capitalize">
                                            @if($order['order_status']=='pending')
                                                <span class="badge badge-soft-info fz-12">
                                                    {{$order['order_status']}}
                                            </span>

                                            @elseif($order['order_status']=='processing' || $order['order_status']=='out_for_delivery')
                                                <span class="badge badge-soft-warning fz-12">
                                                {{str_replace('_',' ',$order['order_status'] == 'processing' ? 'packaging':$order['order_status'])}}
                                            </span>
                                            @elseif($order['order_status']=='confirmed')
                                                <span class="badge badge-soft-success fz-12">
                                                {{$order['order_status']}}
                                            </span>
                                            @elseif($order['order_status']=='failed')
                                                <span class="badge badge-danger fz-12">
                                                {{$order['order_status'] == 'failed' ? 'Failed To Deliver' : ''}}
                                            </span>
                                            @elseif($order['order_status']=='delivered')
                                                <span class="badge badge-soft-success fz-12">
                                                {{$order['order_status']}}
                                            </span>
                                            @else
                                                <span class="badge badge-soft-danger fz-12">
                                                {{$order['order_status']}}
                                            </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="media align-items-center gap-10 flex-wrap">
                                                <button onclick="test({{ $order->id }})" class="btn btn-info"  data-toggle="modal" data-target="#exampleModalLong"><i class="tio-history"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">
                                                <div class="text-center p-4">
                                                    <img class="mb-3 w-160" src="{{ asset('public/assets/back-end/svg/illustrations/sorry.svg') }}" alt="Image Description">
                                                    <p class="mb-0">{{ \App\CPU\translate('no_order_history_found') }}</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse

                                    </tbody>
                                </table>
                            </div>

                            <div class="table-responsive mt-4">
                                <div class="px-4 d-flex justify-content-lg-end">
                                    <!-- Pagination -->
                                    {{ $orders->links() }}
                                </div>
                            </div>
                        </div>
                        <!-- End Card -->
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content load-with-ajax">

            </div>
        </div>
    </div>



@endsection

@push('script')
    <script>
        function test(id)
        {
            let url = "{{ route('admin.delivery-man.ajax-order-status-history', ['order' => ':id'] ) }}"
            url = url.replace(":id", id)
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: url,
                method: 'GET',
                success: function (data) {
                    $(".load-with-ajax").empty().append(data);
                }
            });
        }
    </script>
@endpush
