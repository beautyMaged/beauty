 @extends('layouts.back-end.app')

@section('title',\App\CPU\translate('Deliveryman List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">

        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/deliveryman.png')}}" width="20" alt="">
                {{\App\CPU\translate('delivery_man')}} <span class="badge badge-soft-dark radius-50 fz-12">{{ $delivery_men->count() }}</span>
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="row">
            <div class="col-sm-12 mb-3">
                <!-- Card -->
                <div class="card">
                    <!-- Header -->
                    <div class="px-3 py-4">
                        <div class="d-flex justify-content-between gap-10 flex-wrap align-items-center">
                            <div class="">
                                <form action="{{url()->current()}}" method="GET">
                                    <!-- Search -->
                                    <div class="input-group input-group-merge input-group-custom">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                                placeholder="{{\App\CPU\translate('search')}}" aria-label="Search" value="{{$search}}" required>
                                        <button type="submit" class="btn btn--primary">{{\App\CPU\translate('search')}}</button>

                                    </div>
                                    <!-- End Search -->
                                </form>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{route('admin.delivery-man.add')}}" class="btn btn--primary">
                                    <i class="tio-add"></i>
                                    {{\App\CPU\translate('add')}} {{\App\CPU\translate('deliveryman')}}
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- End Header -->

                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table class="table table-hover table-borderless table-thead-bordered table-align-middle card-table {{ Session::get('direction') === 'rtl' ? 'text-right' : 'text-left' }}">
                            <thead class="thead-light thead-50 text-capitalize table-nowrap">
                            <tr>
                                <th>{{\App\CPU\translate('SL')}}</th>
                                <th>{{\App\CPU\translate('name')}}</th>
                                <th>{{\App\CPU\translate('Contact info')}}</th>
                                <th>{{\App\CPU\translate('Total_Orders')}}</th>
                                <th>{{\App\CPU\translate('Rating')}}</th>
                                <th class="text-center">{{\App\CPU\translate('status')}}</th>
                                <th class="text-center">{{\App\CPU\translate('action')}}</th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                            @forelse($delivery_men as $key=>$dm)
                                <tr>
                                    <td>{{$delivery_men->firstitem()+$key}}</td>
                                    <td>
                                        <div class="media align-items-center gap-10">
                                            <img class="rounded-circle avatar avatar-lg"
                                                 onerror="this.src='{{asset('public/assets/back-end/img/160x160/img1.jpg')}}'"
                                                 src="{{asset('storage/app/public/delivery-man')}}/{{$dm['image']}}">
                                            <div class="media-body">
                                                <a title="Earning Statement"
                                                   class="title-color hover-c1"
                                                   href="{{ route('admin.delivery-man.earning-statement-overview', ['id' => $dm['id']]) }}">
                                                    {{$dm['f_name'].' '.$dm['l_name']}}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <div><a class="title-color hover-c1" href="mailto:{{$dm['email']}}"><strong>{{$dm['email']}}</strong></a></div>
                                            <a class="title-color hover-c1" href="tel:+{{$dm['country_code']}}{{$dm['phone']}}">+{{ $dm['country_code'].' '. $dm['phone']}}</a>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.orders.list', ['all', 'delivery_man_id' => $dm['id']]) }}" class="badge fz-14 badge-soft--primary">
                                            <span>{{ $dm->orders_count }}</span>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.delivery-man.rating', ['id' => $dm['id']]) }}" class="badge fz-14 badge-soft-info">
                                            <span>{{ isset($dm->rating[0]->average) ? number_format($dm->rating[0]->average, 2, '.', ' ') : 0 }} <i class="tio-star"></i> </span>
                                        </a>
                                    </td>
                                    <td>
                                        <label class="mx-auto switcher">
                                            <input type="checkbox" class="switcher_input status"
                                                   id="{{$dm['id']}}" {{$dm->is_active == 1?'checked':''}}>
                                            <span class="switcher_control"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center align-items-center gap-10">
                                            <a  class="btn btn-outline--primary btn-sm edit"
                                                title="{{\App\CPU\translate('edit')}}"
                                                href="{{route('admin.delivery-man.edit',[$dm['id']])}}">
                                                <i class="tio-edit"></i></a>
                                            <a title="Earning Statement"
                                               class="btn btn-outline-info btn-sm square-btn"
                                               href="{{ route('admin.delivery-man.earning-statement-overview', ['id' => $dm['id']]) }}">
                                                <i class="tio-money"></i>
                                            </a>
                                            <a class="btn btn-outline-danger btn-sm delete" href="javascript:"
                                                onclick="form_alert('delivery-man-{{$dm['id']}}','Want to remove this information ?')"
                                                title="{{ \App\CPU\translate('Delete')}}">
                                                <i class="tio-delete"></i>
                                            </a>
                                            <form action="{{route('admin.delivery-man.delete',[$dm['id']])}}"
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
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-lg-end">
                            <!-- Pagination -->
                            {!! $delivery_men->links() !!}
                        </div>
                    </div>
                </div>
                <!-- End Card -->
            </div>
        </div>
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
                url: "{{route('admin.delivery-man.status-update')}}",
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
