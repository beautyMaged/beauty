@extends('layouts.back-end.app-seller')

@section('title',\App\CPU\translate('Deliveryman List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/public/assets/back-end/img/deliveryman.png')}}" alt="">
                {{\App\CPU\translate('deliveryman')}} {{\App\CPU\translate('list')}}
                <span class="badge badge-soft-dark radius-50 fz-14">{{ $delivery_men->total() }}</span>
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header flex-wrap gap-2">
                <div class="flex-start">
                    <div>
                        <form action="{{url()->current()}}" method="GET">
                            <!-- Search -->
                            <div class="input-group input-group-merge input-group-custom">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="tio-search"></i>
                                    </div>
                                </div>
                                <input id="datatableSearch_" type="search" name="search" class="form-control"
                                        placeholder="Search" aria-label="Search" value="{{$search}}" required>
                                <button type="submit" class="btn btn--primary">{{\App\CPU\translate('search')}}</button>

                            </div>
                            <!-- End Search -->
                        </form>
                    </div>
                </div>
                <a href="{{route('seller.delivery-man.add')}}" class="btn btn--primary">
                    <i class="tio-add-circle"></i> {{\App\CPU\translate('add')}} {{\App\CPU\translate('deliveryman')}}
                </a>
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table class="table table-hover table-borderless table-thead-bordered table-align-middle card-table">
                    <thead class="thead-light thead-50 text-capitalize table-nowrap">
                    <tr>
                        <th>{{\App\CPU\translate('SL')}}</th>
                        <th>{{\App\CPU\translate('name')}}</th>
                        <th>{{\App\CPU\translate('Contact_Info')}}</th>
                        <th>{{\App\CPU\translate('Total_Orders')}}</th>
                        <th>{{\App\CPU\translate('Rating')}}</th>
                        <th>{{\App\CPU\translate('status')}}</th>
                        <th class="text-center">{{\App\CPU\translate('action')}}</th>
                    </tr>
                    </thead>

                    <tbody id="set-rows">
                    @forelse($delivery_men as $key=>$dm)
                        <tr>
                            <td>{{$delivery_men->firstitem()+$key}}</td>
                            <td>
                                <div class="media align-items-center gap-10">
                                    <img class="avatar avatar-lg rounded-circle"
                                            onerror="this.src='{{asset('public/assets/back-end/img/160x160/img1.jpg')}}'"
                                            src="{{asset('storage/app/public/delivery-man')}}/{{$dm['image']}}">
                                    <div class="media-body">
                                        <a title="Earning Statement"
                                           class="title-color hover-c1"
                                           href="{{ route('seller.delivery-man.earning-statement', ['id' => $dm['id']]) }}">
                                            {{$dm['f_name'].' '.$dm['l_name']}}
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <div><a class="title-color hover-c1" href="mailto:{{$dm['email']}}"><strong>{{$dm['email']}}</strong></a></div>
                                    <a class="title-color hover-c1" href="tel:+{{$dm['country_code']}}{{$dm['phone']}}">+{{$dm['country_code']. ' ' .$dm['phone']}}</a>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('seller.orders.list', ['all', 'delivery_man_id' => $dm['id']]) }}" class="badge fz-14 badge-soft--primary">
                                    <span>{{ $dm->orders_count }}</span>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('seller.delivery-man.rating', ['id' => $dm['id']]) }}" class="badge fz-14 badge-soft-info">
                                    <span>{{ isset($dm->rating[0]->average) ? number_format($dm->rating[0]->average, 2, '.', ' ') : 0 }} <i class="tio-star"></i> </span>
                                </a>
                            </td>
                            <td>
                                <label class="switcher">
                                    <input type="checkbox" class="status switcher_input"
                                            id="{{$dm['id']}}" {{$dm->is_active == 1?'checked':''}}>
                                    <span class="switcher_control"></span>
                                </label>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a  class="btn btn-outline--primary btn-sm square-btn"
                                        title="{{\App\CPU\translate('Edit')}}"
                                        href="{{route('seller.delivery-man.edit',[$dm['id']])}}">
                                        <i class="tio-edit"></i>
                                    </a>
                                    <a title="Earning Statement"
                                       class="btn btn-outline-info btn-sm square-btn"
                                       href="{{ route('seller.delivery-man.earning-statement', ['id' => $dm['id']]) }}">
                                        <i class="tio-money"></i>
                                    </a>
                                    <a class="btn btn-outline-danger btn-sm square-btn"
                                        title="{{\App\CPU\translate('Delete')}}"
                                        href="javascript:"
                                        onclick="form_alert('delivery-man-{{$dm['id']}}','Want to remove this information ?')">
                                        <i class="tio-delete"></i>
                                    </a>
                                    <form action="{{route('seller.delivery-man.delete',[$dm['id']])}}"
                                            method="post" id="delivery-man-{{$dm['id']}}">
                                        @csrf @method('delete')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="text-center p-4">
                                    <img class="mb-3 w-160" src="{{ asset('public/assets/back-end/svg/illustrations/sorry.svg') }}" alt="Image Description">
                                    <p class="mb-0">{{\App\CPU\translate('No_delivery_man_found')}}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                <!-- <div class="page-area">
                    <table>
                        <tfoot>
                        {!! $delivery_men->links() !!}
                        </tfoot>
                    </table>
                </div> -->

            </div>
            <!-- End Table -->


            <div class="table-responsive mt-4">
                <div class="px-4 d-flex justify-content-lg-end">
                    <!-- Pagination -->
                    {!! $delivery_men->links() !!}
                </div>
            </div>
        </div>
        <!-- End Card -->
    </div>

@endsection

@push('script_2')
    <script>
        $(document).on('change', '.status', function () {
            var id = $(this).attr("id");
            if ($(this).prop("checked") == true) {
                var status = 1;
            } else if ($(this).prop("checked") == false) {
                var status = 0;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('seller.delivery-man.status-update')}}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function (data) {
                    toastr.success('{{\App\CPU\translate('Status updated successfully')}}');
                }
            });
        });
    </script>
@endpush
