@extends('layouts.back-end.app-seller')

@section('title', \App\CPU\translate('coupons'))

@push('css_or_js')
    <link href="{{ asset('public/assets/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/back-end/css/custom.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{ asset('/public/assets/back-end/img/coupon_setup.png') }}" alt="">
                {{ \App\CPU\translate('coupon_setup') }}
            </h2>
        </div>
        <!-- End Page Title -->
        <div class="row mt-20">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row justify-content-between align-items-center flex-grow-1">
                            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                <h5 class="mb-0 text-capitalize d-flex gap-2">
                                    {{ \App\CPU\translate('coupon_list') }}
                                    <span class="badge badge-soft-dark radius-50 fz-12 ml-1">{{ $coupons->total() }}</span>
                                </h5>
                            </div>
                            <div class="col-sm-8 col-md-6 col-lg-4">
                                <div
                                    class="d-flex align-items-center justify-content-md-end flex-wrap flex-sm-nowrap gap-2">
                                    <!-- Search -->
                                    <form action="{{ url()->current() }}" method="GET">
                                        <div class="input-group input-group-merge input-group-custom">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="tio-search"></i>
                                                </div>
                                            </div>
                                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                                placeholder="{{ \App\CPU\translate('Search by Title or Code or Discount Type') }}"
                                                value="{{ $search }}" aria-label="Search orders" required>
                                            <button type="submit"
                                                class="btn btn--primary">{{ \App\CPU\translate('search') }}</button>
                                        </div>
                                    </form>
                                    <!-- End Search -->
                                    <div id="banner-btn">
                                        <a href="{{ route('seller.coupon.create') }}" class="btn btn--primary text-nowrap">
                                            <i class="tio-add"></i>
                                            {{ \App\CPU\translate('Coupon Add') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable"
                            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table {{ Session::get('direction') === 'rtl' ? 'text-right' : 'text-left' }}">
                            <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{ \App\CPU\translate('SL') }}</th>
                                    <th>{{ \App\CPU\translate('coupon') }}</th>
                                    {{-- <th>{{ \App\CPU\translate('coupon_type') }}</th> --}}
                                    <th>{{ \App\CPU\translate('start_at') }}</th>
                                    <th>{{ \App\CPU\translate('end_at') }}</th>
                                    {{-- <th>{{ \App\CPU\translate('user_limit') }}</th> --}}
                                    {{-- <th>{{ \App\CPU\translate('discount_bearer') }}</th> --}}
                                    <th>
                                        {{ \App\CPU\translate('Status') }}
                                        <i class="tio-info font-130 info-color"
                                            title="{{ \App\CPU\translate('some_status_buttons_are_disabled_because_the_admin_added_coupons') }}, {{ \App\CPU\translate('the_coupon_discount_bearer_is_admin') }}, {{ \App\CPU\translate('or_some_coupons_are_for_all_sellers') }}">

                                        </i>
                                    </th>
                                    <th class="text-center">
                                        {{ \App\CPU\translate('Action') }}
                                        <i class="tio-info font-130 info-color"
                                            title="{{ \App\CPU\translate('some_actions_are_disabled_because_the_admin_added_coupons') }}, {{ \App\CPU\translate('the_coupon_discount_bearer_is_admin') }}, {{ \App\CPU\translate('or_some_coupons_are_for_all_sellers') }}">

                                        </i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($coupons as $k => $coupon)
                                    <tr id="data-{{ $coupon->id }}">
                                        <td>{{ $coupons->firstItem() + $k }}</td>
                                        <td>
                                            <div>{{ substr($coupon['title'], 0, 20) }}</div>
                                            <strong>{{ \App\CPU\translate('code') }}: {{ $coupon['code'] }}</strong>
                                        </td>

                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                <span>{{ $coupon->start_at->format('M d - h:m') }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                <span>{{ $coupon->end_at->format('M d - h:m') }}</span>
                                            </div>
                                        <td>
                                            <label class="switcher">
                                                <input id="switcher-{{ $coupon->id }}" type="checkbox"
                                                    class="switcher_input status" class="toggle-switch-input"
                                                    {{ $coupon->status ? 'checked' : '' }}>
                                                <span class="switcher_control"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-10 justify-content-center">
                                                <a class="btn btn-outline--primary btn-sm edit"
                                                    href="{{ route('seller.coupon.update', [$coupon['id']]) }}"
                                                    title="{{ \App\CPU\translate('Edit') }}">
                                                    <i class="tio-edit"></i>
                                                </a>
                                                <a id="{{ $coupon->id }}" class="btn btn-outline-danger btn-sm delete"
                                                    title="{{ \App\CPU\translate('delete') }}">
                                                    <i class="tio-delete"></i>
                                                </a>
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="modal fade" id="quick-view" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered coupon-details" role="document">
                                <div class="modal-content" id="quick-view-modal">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-lg-end">
                            <!-- Pagination -->
                            {{ $coupons->links() }}
                        </div>
                    </div>

                    @if (count($coupons) == 0)
                        <div class="text-center p-4">
                            <img class="mb-3 w-160" src="{{ asset('assets/back-end') }}/svg/illustrations/sorry.svg"
                                alt="Image Description">
                            <p class="mb-0">{{ \App\CPU\translate('No data to show') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <!-- Page level plugins -->
    <script src="{{ asset('public/assets/back-end') }}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('public/assets/back-end') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('public/assets/back-end') }}/js/demo/datatables-demo.js"></script>
    <script>
        $(document).on('click', '.delete', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ \App\CPU\translate('Are_you_sure_delete_this_coupon') }} ?",
                text: "{{ \App\CPU\translate('You_will_not_be_able_to_revert_this') }} !",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ \App\CPU\translate('Yes') }}, {{ \App\CPU\translate('delete_it') }}!',
                type: 'warning',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: `{{ route('seller.coupon.delete', ['id' => '/']) }}/${id}`,
                        method: 'DELETE',
                        success: function(response) {
                            toastr.success(
                                '{{ \App\CPU\translate('coupon_deleted_successfully') }}');
                            $('#data-' + id).hide();
                        }
                    });
                }
            })
        });
        $(document).on('change', '.status', function() {
            const id = $(this).attr("id").slice(9);
            const status = $(this).prop("checked") ? 1 : 0
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('seller.coupon.status') }}",
                method: 'POST',
                data: {
                    id,
                    status
                },
                success: function(data) {
                    if (data == 1) {
                        toastr.success('{{ \App\CPU\translate('Banner_published_successfully') }}');
                    } else {
                        toastr.success('{{ \App\CPU\translate('Banner_unpublished_successfully') }}');
                    }
                }
            });
        });
    </script>
@endpush
