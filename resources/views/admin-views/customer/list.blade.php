@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Customer List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">

        <!-- Page Title -->
        <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/assets/back-end/img/customer.png')}}" alt="">
                {{\App\CPU\translate('Customer_list')}}
                <span class="badge badge-soft-dark radius-50">{{\App\User::count()}}</span>
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="px-3 py-4">
                <div class="row gy-2 align-items-center">
                    <div class="col-sm-8 col-md-6 col-lg-4">
                        <!-- Search -->
                        <form action="{{ url()->current() }}" method="GET">
                            <div class="input-group input-group-merge input-group-custom">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="tio-search"></i>
                                    </div>
                                </div>
                                <input id="datatableSearch_" type="search" name="search" class="form-control"
                                       placeholder="{{\App\CPU\translate('Search by Name or Email or Phone')}}"
                                       aria-label="Search orders" value="{{ $search }}">
                                <button type="submit" class="btn btn--primary">{{\App\CPU\translate('search')}}</button>
                            </div>
                        </form>
                        <!-- End Search -->
                    </div>
                    <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                        <div class="d-flex justify-content-sm-end">
                            <button type="button" class="btn btn-outline--primary" data-toggle="dropdown">
                                <i class="tio-download-to"></i>
                                {{\App\CPU\translate('export')}}
                                <i class="tio-chevron-down"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a class="dropdown-item" href="{{route('admin.customer.export')}}">{{\App\CPU\translate('excel')}}</a></li>
                                <div class="dropdown-divider"></div>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- End Row -->
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table
                    style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                    <thead class="thead-light thead-50 text-capitalize">
                    <tr>
                        <th>{{\App\CPU\translate('SL')}}</th>
                        <th>{{\App\CPU\translate('customer_name')}}</th>
                        <th>{{\App\CPU\translate('contact_info')}}</th>
                        <th>{{\App\CPU\translate('Total')}} {{\App\CPU\translate('Order')}} </th>
                        <th>{{\App\CPU\translate('block')}} / {{\App\CPU\translate('unblock')}}</th>
                        <th class="text-center">{{\App\CPU\translate('Action')}}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($customers as $key=>$customer)
                        <tr>
                            <td>
                                {{$customers->firstItem()+$key}}
                            </td>
                            <td>
                                <a href="{{route('admin.customer.view',[$customer['id']])}}"
                                   class="title-color hover-c1 d-flex align-items-center gap-10">
                                    <img src="{{asset('storage/profile')}}/{{$customer->image}}"
                                         onerror="this.onerror=null;this.src='{{asset('assets/back-end/img/160x160/img1.jpg')}}'"
                                         class="rounded-circle" alt="" width="40">
                                    {{\Illuminate\Support\Str::limit($customer['f_name']." ".$customer['l_name'],20)}}
                                </a>
                            </td>
                            <td>
                                <div class="mb-1">
                                    <strong><a class="title-color hover-c1" href="mailto:{{$customer->email}}">{{$customer->email}}</a></strong>

                                </div>
                                <a class="title-color hover-c1" href="tel:{{$customer->phone}}">{{$customer->phone}}</a>

                            </td>
                            <td>
                                <label class="btn text-info bg-soft-info font-weight-bold px-3 py-1 mb-0 fz-12">
                                    {{$customer->orders->count()}}
                                </label>
                            </td>

                            <td>
                                <label class="switcher">
                                    <input type="checkbox" class="switcher_input"
                                           id="{{$customer['id']}}" {{$customer->is_active == 1?'checked':''}}>
                                    <span class="switcher_control"></span>
                                </label>
                            </td>

                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a title="{{\App\CPU\translate('View')}}"
                                       class="btn btn-outline-info btn-sm square-btn"
                                       href="{{route('admin.customer.view',[$customer['id']])}}">
                                        <i class="tio-invisible"></i>
                                    </a>
                                    <a title="{{\App\CPU\translate('delete')}}"
                                       class="btn btn-outline-danger btn-sm delete square-btn" href="javascript:"
                                       onclick="form_alert('customer-{{$customer['id']}}','Want to delete this customer ?')">
                                        <i class="tio-delete"></i>
                                    </a>
                                </div>
                                <form action="{{route('admin.customer.delete',[$customer['id']])}}"
                                        method="post" id="customer-{{$customer['id']}}">
                                    @csrf @method('delete')
                                </form>
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
                    {!! $customers->links() !!}
                </div>
            </div>

            @if(count($customers)==0)
                <div class="text-center p-4">
                    <img class="mb-3 w-160" src="{{asset('assets/back-end')}}/svg/illustrations/sorry.svg"
                         alt="Image Description">
                    <p class="mb-0">{{\App\CPU\translate('No data to show')}}</p>
                </div>
        @endif
        <!-- End Footer -->
        </div>
        <!-- End Card -->
    </div>
@endsection

@push('script_2')
    <script>
        $(document).on('change', '.switcher_input', function () {
            let id = $(this).attr("id");

            let status = 0;
            if (jQuery(this).prop("checked") === true) {
                status = 1;
            }

            Swal.fire({
                title: '{{\App\CPU\translate('Are you sure')}}?',
                text: '{{\App\CPU\translate('want_to_change_status')}}',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.customer.status-update')}}",
                        method: 'POST',
                        data: {
                            id: id,
                            status: status
                        },
                        success: function () {
                            toastr.success('{{\App\CPU\translate('Status updated successfully')}}');
                        }
                    });
                }
            })
        });
    </script>
@endpush
