@extends('layouts.back-end.app-seller')

@section('title', \App\CPU\translate('Coupon Add'))

@push('css_or_js')
    <link href="{{ asset('public/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{ asset('public/assets/back-end/css/custom.css')}}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/coupon_setup.png')}}" alt="">
                {{\App\CPU\translate('coupon_setup')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Content Row -->
        <div class="row">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('seller.coupon.store-coupon')}}" method="post">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="name"
                                           class="title-color font-weight-medium d-flex">{{\App\CPU\translate('coupon_type')}}</label>
                                    <select class="form-control" id="coupon_type" name="coupon_type" required>
                                        <option disabled selected>{{\App\CPU\translate('Select_coupon_type')}}</option>
                                        <option
                                            value="discount_on_purchase">{{\App\CPU\translate('Discount_on_Purchase')}}</option>
                                        <option value="free_delivery">{{\App\CPU\translate('Free_Delivery')}}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="name"
                                           class="title-color font-weight-medium d-flex">{{\App\CPU\translate('coupon_title')}}</label>
                                    <input type="text" name="title" class="form-control" value="{{ old('title') }}"
                                           id="title"
                                           placeholder="{{\App\CPU\translate('Title')}}" required>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <div class="d-flex justify-content-between">
                                        <label for="name"
                                               class="title-color font-weight-medium text-capitalize">{{\App\CPU\translate('coupon_code')}}</label>
                                        <a href="javascript:void(0)" class="float-right c1 fz-12"
                                           onclick="generateCode()">{{\App\CPU\translate('generate_code')}}</a>
                                    </div>
                                    <input type="text" name="code" value=""
                                           class="form-control" id="code"
                                           placeholder="{{\App\CPU\translate('Ex: EID100')}}" required>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group coupon_type">
                                    <label for="name"
                                           class="title-color font-weight-medium d-flex">{{\App\CPU\translate('customer')}}</label>
                                    <select
                                        class="js-example-basic-multiple js-states js-example-responsive form-control"
                                        name="customer_id">
                                        <option disabled selected>{{\App\CPU\translate('Select_customer')}}</option>
                                        <option value="0">{{\App\CPU\translate('all_customer')}}</option>
                                        @foreach($customers as $customer)
                                            <option
                                                value="{{ $customer->id }}">{{ $customer->f_name. ' '. $customer->l_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label
                                        for="exampleFormControlInput1"
                                        class="title-color font-weight-medium d-flex">{{\App\CPU\translate('limit')}} {{\App\CPU\translate('for')}} {{\App\CPU\translate('same')}} {{\App\CPU\translate('user')}}</label>
                                    <input type="number" name="limit" value="{{ old('limit') }}" min="0"
                                           id="coupon_limit" class="form-control"
                                           placeholder="{{\App\CPU\translate('EX')}}: {{\App\CPU\translate('10')}}">
                                </div>
                                <div class="col-md-6 col-lg-4 form-group free_delivery">
                                    <label for="name"
                                           class="title-color font-weight-medium d-flex">{{\App\CPU\translate('discount_type')}}</label>
                                    <select id="discount_type" class="form-control w-100" name="discount_type"
                                            onchange="checkDiscountType(this.value)">
                                        <option value="amount">{{\App\CPU\translate('Amount')}}</option>
                                        <option value="percentage">{{\App\CPU\translate('percentage (%)')}}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group free_delivery">
                                    <label for="name"
                                           class="title-color font-weight-medium d-flex">{{\App\CPU\translate('Discount_Amount')}}
                                        <span id="discount_percent"> (%)</span></label>
                                    <input type="number" min="1" max="1000000" name="discount"
                                           value="{{ old('discount') }}" class="form-control"
                                           id="discount"
                                           placeholder="{{\App\CPU\translate('Ex: 500')}}">
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="name"
                                           class="title-color font-weight-medium d-flex">{{\App\CPU\translate('minimum_purchase ($)')}}</label>
                                    <input type="number" min="1" max="1000000" name="min_purchase"
                                           value="{{ old('min_purchase') }}" class="form-control"
                                           id="minimum purchase"
                                           placeholder="{{\App\CPU\translate('Ex: 100')}}">
                                </div>
                                <div class="col-md-6 col-lg-4 form-group free_delivery" id="max-discount">
                                    <label for="name"
                                           class="title-color font-weight-medium d-flex">{{\App\CPU\translate('maximum_discount ($)')}}</label>
                                    <input type="number" min="1" max="1000000" name="max_discount"
                                           value="{{ old('max_discount') }}"
                                           class="form-control" id="maximum discount"
                                           placeholder="{{\App\CPU\translate('Ex: 5000')}}">
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="name"
                                           class="title-color font-weight-medium d-flex">{{\App\CPU\translate('start_date')}}</label>
                                    <input id="start_date" type="date" name="start_date" value="{{ old('start_date') }}"
                                           class="form-control"
                                           placeholder="{{\App\CPU\translate('start date')}}" required>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="name"
                                           class="title-color font-weight-medium d-flex">{{\App\CPU\translate('expire_date')}}</label>
                                    <input id="expire_date" type="date" name="expire_date"
                                           value="{{ old('expire_date') }}" class="form-control"
                                           placeholder="{{\App\CPU\translate('expire date')}}" required>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-end flex-wrap gap-10">
                                <button type="reset"
                                        class="btn btn-secondary px-4">{{\App\CPU\translate('reset')}}</button>
                                <button type="submit"
                                        class="btn btn--primary px-4">{{\App\CPU\translate('Submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-20">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row justify-content-between align-items-center flex-grow-1">
                            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                <h5 class="mb-0 text-capitalize d-flex gap-2">
                                    {{\App\CPU\translate('coupon_list')}}
                                    <span
                                        class="badge badge-soft-dark radius-50 fz-12 ml-1">{{ $coupons->total() }}</span>
                                </h5>
                            </div>
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
                                               placeholder="{{\App\CPU\translate('Search by Title or Code or Discount Type')}}"
                                               value="{{ $search }}" aria-label="Search orders" required>
                                        <button type="submit"
                                                class="btn btn--primary">{{\App\CPU\translate('search')}}</button>
                                    </div>
                                </form>
                                <!-- End Search -->
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable"
                               class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table {{ Session::get('direction') === 'rtl' ? 'text-right' : 'text-left' }}">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{\App\CPU\translate('SL')}}</th>
                                <th>{{\App\CPU\translate('coupon')}}</th>
                                <th>{{\App\CPU\translate('coupon_type')}}</th>
                                <th>{{\App\CPU\translate('Duration')}}</th>
                                <th>{{\App\CPU\translate('user_limit')}}</th>
                                <th>{{\App\CPU\translate('discount_bearer')}}</th>
                                <th>
                                    {{\App\CPU\translate('Status')}}
                                    <i class="tio-info font-130 info-color"
                                       title="{{\App\CPU\translate('some_status_buttons_are_disabled_because_the_admin_added_coupons')}}, {{\App\CPU\translate('the_coupon_discount_bearer_is_admin')}}, {{\App\CPU\translate('or_some_coupons_are_for_all_sellers')}}">

                                    </i>
                                </th>
                                <th class="text-center">
                                    {{\App\CPU\translate('Action')}}
                                    <i class="tio-info font-130 info-color"
                                       title="{{\App\CPU\translate('some_actions_are_disabled_because_the_admin_added_coupons')}}, {{\App\CPU\translate('the_coupon_discount_bearer_is_admin')}}, {{\App\CPU\translate('or_some_coupons_are_for_all_sellers')}}">

                                    </i>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($coupons as $k=>$coupon)
                                <tr>
                                    <td>{{$coupons->firstItem() + $k}}</td>
                                    <td>
                                        <div>{{substr($coupon['title'],0,20)}}</div>
                                        <strong>{{\App\CPU\translate('code')}}: {{$coupon['code']}}</strong>
                                    </td>
                                    <td class="text-capitalize">{{\App\CPU\translate(str_replace('_',' ',$coupon['coupon_type']))}}</td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            <span>{{date('d M, y',strtotime($coupon['start_date']))}} - </span>
                                            <span>{{date('d M, y',strtotime($coupon['expire_date']))}}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span>{{\App\CPU\translate('Limit')}}: <strong>{{ $coupon['limit'] }},</strong></span>
                                        <span
                                            class="ml-1">{{\App\CPU\translate('Used')}}: <strong>{{ $coupon['order_count'] }}</strong></span>
                                    </td>
                                    <td>{{ $coupon['coupon_bearer'] == 'inhouse' ? 'admin':$coupon['coupon_bearer'] }}</td>
                                    <td>
                                        <label class="switcher">
                                            @if($coupon->added_by=='seller' || ($coupon->added_by=='admin' && $coupon->coupon_bearer=='seller' && $coupon->seller_id==auth('seller')->id()))
                                                <input type="checkbox" class="switcher_input"
                                                       onclick="location.href='{{route('seller.coupon.status',[$coupon['id'],$coupon->status?0:1])}}'"
                                                       class="toggle-switch-input" {{$coupon->status?'checked':''}}>
                                                <span class="switcher_control"></span>
                                            @else
                                                <input type="checkbox"
                                                       class="switcher_input toggle-switch-input" {{$coupon->status?'checked':''}} disabled>
                                                <span class="switcher_control opacity--40"></span>
                                            @endif
                                        </label>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-10 justify-content-center">
                                            <button class="btn btn-outline-info btn-sm square-btn"
                                                    onclick="get_details(this)" data-id="{{ $coupon['id'] }}"
                                                    data-toggle="modal" data-target="#exampleModalCenter">
                                                <i class="tio-invisible"></i>
                                            </button>
                                            @if($coupon->added_by=='seller' || ($coupon->added_by=='admin' && $coupon->coupon_bearer=='seller' && $coupon->seller_id==auth('seller')->id()))
                                                <a class="btn btn-outline--primary btn-sm edit"
                                                   href="{{route('seller.coupon.update',[$coupon['id']])}}"
                                                   title="{{ \App\CPU\translate('Edit')}}">
                                                    <i class="tio-edit"></i>
                                                </a>
                                                <a class="btn btn-outline-danger btn-sm delete"
                                                   href="javascript:"
                                                   onclick="form_alert('coupon-{{$coupon['id']}}','Want to delete this coupon ?')"
                                                   title="{{\App\CPU\translate('delete')}}">
                                                    <i class="tio-delete"></i>
                                                </a>
                                                <form action="{{route('seller.coupon.delete',[$coupon['id']])}}"
                                                      method="post" id="coupon-{{$coupon['id']}}">
                                                    @csrf @method('delete')
                                                </form>
                                            @else
                                                <button
                                                    class="btn btn-outline-primary btn-sm edit disabled cursor-default"
                                                    title="Tooltip on top">
                                                    <i class="tio-edit"></i>
                                                </button>
                                                <button
                                                    class="btn btn-outline-danger btn-sm delete disabled cursor-default"
                                                    title="Tooltip on top">
                                                    <i class="tio-delete"></i>
                                                </button>
                                                <span></span>
                                            @endif
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
                            {{$coupons->links()}}
                        </div>
                    </div>

                    @if(count($coupons)==0)
                        <div class="text-center p-4">
                            <img class="mb-3 w-160"
                                 src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg"
                                 alt="Image Description">
                            <p class="mb-0">{{\App\CPU\translate('No data to show')}}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>

        $(document).ready(function () {
            generateCode();

            let discount_type = $('#discount_type').val();
            if (discount_type == 'amount') {
                $('#max-discount').hide()
            } else if (discount_type == 'percentage') {
                $('#max-discount').show()
            }

            $('#start_date').attr('min', (new Date()).toISOString().split('T')[0]);
            $('#expire_date').attr('min', (new Date()).toISOString().split('T')[0]);

        });

        $("#start_date").on("change", function () {
            $('#expire_date').attr('min', $(this).val());
        });

        $("#expire_date").on("change", function () {
            $('#start_date').attr('max', $(this).val());
        });

        function get_details(t) {
            let id = $(t).data('id')

            $.ajax({
                type: 'GET',
                url: '{{route('seller.coupon.quick-view-details')}}',
                data: {
                    id: id
                },
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    console.log(data)
                    $('#loading').hide();
                    $('#quick-view').modal('show');
                    $('#quick-view-modal').empty().html(data.view);
                }
            });
        }

        function checkDiscountType(val) {
            if (val == 'amount') {
                $('#max-discount').hide()
            } else if (val == 'percentage') {
                $('#max-discount').show()
            }
        }

        function generateCode() {
            let code = Math.random().toString(36).substring(2, 12);
            $('#code').val(code)
        }

        $('#discount_type').on('change', function () {
            let type = $('#discount_type').val();
            if (type === 'amount') {
                $('#discount').attr({
                    'placeholder': 'Ex: 500',
                    "max": "1000000"
                });
                $('#discount_percent').hide();
            } else if (type === 'percentage') {
                $('#discount').attr({
                    "max": "100",
                    "placeholder": "Ex: 10%"
                });
                $('#discount_percent').show();
            }
        });


    </script>

    <script src="{{asset('public/assets/back-end')}}/js/select2.min.js"></script>
    <script>
        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });
    </script>

    <!-- Page level plugins -->
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="{{asset('public/assets/back-end')}}/js/demo/datatables-demo.js"></script>

    <script>
        $('#discount_type').on('change', function () {
            let type = $('#discount_type').val();
            if (type === 'amount') {
                $('#discount').attr('placeholder', 'Ex: 500');
            } else if (type === 'percentage') {
                $('#discount').attr('placeholder', 'Ex: 10%');
            }
        });

        $('#coupon_type').on('change', function () {
            let discount_type = $('#discount_type').val();
            let type = $('#coupon_type').val();

            if (type === 'free_delivery') {
                if (discount_type === 'amount') {
                    $('.free_delivery').hide();
                } else if (discount_type === 'percentage') {
                    $('.free_delivery').hide();
                }
            } else {
                if (discount_type === 'amount') {
                    $('.free_delivery').show();
                    $('#max-discount').hide()
                } else if (discount_type === 'percentage') {
                    $('.free_delivery').show();
                    $('#max-discount').show()
                }
            }
        });
    </script>
@endpush
