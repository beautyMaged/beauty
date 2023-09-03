@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Brand List'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 d-flex gap-2">
                <img width="20" src="{{asset('/assets/back-end/img/brand.png')}}" alt="">
                {{\App\CPU\translate('Brand')}} {{\App\CPU\translate('List')}}
                <span class="badge badge-soft-dark radius-50 fz-14">{{ $br->total() }}</span>
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="row mt-20">
            <div class="col-md-12">
                <div class="card">
                    <!-- Data Table Top -->
                    <div class="px-3 py-4">
                        <div class="row g-2 flex-grow-1">
                            <div class="col-sm-8 col-md-6 col-lg-4">
                                <!-- Search -->
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-custom input-group-merge">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search_by_Name')}}" aria-label="Search by ID or name" value="{{ $search }}" required>
                                        <button type="submit" class="btn btn--primary input-group-text">{{ \App\CPU\translate('Search')}}</button>
                                    </div>
                                </form>
                                <!-- End Search -->
                            </div>
                            <div class="col-sm-4 col-md-6 col-lg-8 d-flex justify-content-end">
                                <button type="button" class="btn btn-outline--primary" data-toggle="dropdown">
                                    <i class="tio-download-to"></i>
                                    {{\App\CPU\translate('export')}}
                                    <i class="tio-chevron-down"></i>
                                </button>

                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('admin.brand.export') }}">Excel</a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- End Row -->
                    </div>
                    <!-- End Data Table Top -->

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                                <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>
                                        {{ \App\CPU\translate('SL')}}
                                    </th>
                                    <th>{{ \App\CPU\translate('Brand_Logo')}}</th>
                                    <th>{{ \App\CPU\translate('name')}}</th>
                                    <th>{{ \App\CPU\translate('Total Product')}}</th>
                                    <th>{{ \App\CPU\translate('Total Order')}}</th>
                                    <th class="text-center">{{\App\CPU\translate('Status')}}</th>
                                    <th class="text-center">
                                        {{ \App\CPU\translate('action')}}
                                    </th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($br as $k=>$b)
                                    <tr>
                                        <td>{{$br->firstItem()+$k}}</td>
                                        <td>
                                            <img class="rounded avatar-60"
                                                 onerror="this.onerror=null;this.src='{{asset('assets/back-end/img/160x160/img2.jpg')}}'"
                                                 src="{{asset('storage/brand')}}/{{$b['image']}}">
                                        </td>
                                        <td>{{$b['name']}}</td>
                                        <td>{{ $b['brand_all_products_count'] }}</td>
                                        <td>{{ $b['brandAllProducts']->sum('order_details_count') }}</td>
                                        <td>
                                            <label class="switcher mx-auto">
                                                <input type="checkbox" class="status switcher_input"
                                                       id="{{$b['id']}}" {{$b['status'] == 1 ? 'checked':''}}>
                                                <span class="switcher_control"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a class="btn btn-outline-info btn-sm square-btn" title="{{ \App\CPU\translate('Edit')}}"
                                                href="{{route('admin.brand.update',[$b['id']])}}">
                                                <i class="tio-edit"></i>
                                                </a>
                                                <a class="btn btn-outline-danger btn-sm delete square-btn" title="{{ \App\CPU\translate('Delete')}}"
                                                id="{{$b['id']}}">
                                                <i class="tio-delete"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class="table-responsive mt-4">
                        <div class="d-flex justify-content-lg-end">
                            <!-- Pagination -->
                            {{$br->links()}}
                        </div>
                    </div>
                    @if(count($br)==0)
                        <div class="text-center p-4">
                            <img class="mb-3 w-160" src="{{asset('assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description">
                            <p class="mb-0">{{ \App\CPU\translate('No_data_to_show')}}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).on('click', '.delete', function () {
            var id = $(this).attr("id");
            Swal.fire({
                title: '{{ \App\CPU\translate('Are_you_sure_delete_this_brand')}}?',
                text: "{{ \App\CPU\translate('You_will_not_be_able_to_revert_this')}}!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ \App\CPU\translate('Yes')}}, {{ \App\CPU\translate('delete_it')}}!',
                cancelButtonText: "{{ \App\CPU\translate('cancel')}}",
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.brand.delete')}}",
                        method: 'POST',
                        data: {id: id},
                        success: function () {
                            toastr.success('{{ \App\CPU\translate('Brand_deleted_successfully')}}');
                            location.reload();
                        }
                    });
                }
            })
        });

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
                url: "{{route('admin.brand.status-update')}}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function (data) {
                    console.log(data)
                    if (data.success == true) {
                        toastr.success('{{\App\CPU\translate('Status updated successfully')}}');
                    } else {
                        toastr.error('{{\App\CPU\translate('Status updated failed. Product must be approved')}}');
                        location.reload();
                    }
                }
            });
        });
    </script>
@endpush
