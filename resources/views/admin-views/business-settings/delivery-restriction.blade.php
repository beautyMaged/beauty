@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Delivery_Restriction'))

@push('css_or_js')
    <link href="{{ asset('public/assets/back-end/css/tags-input.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .select2-selection__rendered{
            width: 100%;
        }
    </style>
@endpush

@section('content')

    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/business-setup.png')}}" alt="">
                {{\App\CPU\translate('Business_Setup')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
    @include('admin-views.business-settings.business-setup-inline-menu')
    <!-- End Inlile Menu -->
        <div class="row gy-2">
            <!-- Delivery to Country -->
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0 text-capitalize d-flex gap-1">
                            <i class="tio-briefcase"></i>
                            {{\App\CPU\translate('delivery_available_country')}}
                        </h5>
                        <label class="switcher">
                            <input type="checkbox" onchange="status_change(this)" data-id="country_area" class="status switcher_input" data-url="{{ route('admin.business-settings.delivery-restriction.country-restriction-status-change') }}" {{ isset($country_restriction_status->value) && $country_restriction_status->value  == 1 ? 'checked' : '' }}>
                            <span class="switcher_control"></span>
                        </label>
                    </div>
                    <div class="card-body country-disable">
                        <div class="row">
                            <div class="col-md-12">
                                <form action="{{ route('admin.business-settings.delivery-restriction.add-delivery-country') }}"
                                      method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label class="title-color d-flex">{{\App\CPU\translate('country')}} </label>
                                        <select
                                            class="js-example-basic-multiple js-states js-example-responsive form-control"
                                            name="country_code[]" id="choice_attributes" multiple="multiple">
                                            @foreach($countries as $country)
                                                <option value="{{ $country['code'] }}" {{ in_array($country['code'], $stored_country_code) ? 'disabled' : '' }}>
                                                    {{ $country['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="mt-3 d-flex justify-content-end">
                                            <button type="submit"
                                                    class="btn btn--primary px-4">{{\App\CPU\translate('Submit')}}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-12 mt-6">
                                <div class="table-responsive">
                                    <table id="datatable"
                                           class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                                        <thead class="thead-light thead-50 text-capitalize">
                                        <tr>
                                            <th>{{\App\CPU\translate('sl')}}</th>
                                            <th class="text-center">{{\App\CPU\translate('country_name')}}</th>
                                            <th class="text-center">{{\App\CPU\translate('action')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($stored_countries as $k=>$store)
                                            <td class="">{{$stored_countries->firstItem()+$k}}</td>
                                            @foreach($countries as $country)
                                                @if($store->country_code == $country['code'])
                                                    <td class="text-center">{{ $country['name'] }}</td>
                                                @endif
                                            @endforeach

                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a class="btn btn-outline-danger btn-sm square-btn"
                                                       href="javascript:"
                                                       title="{{\App\CPU\translate('Delete')}}"
                                                       onclick="form_alert('product-{{$store->id}}','Want to delete this item ?')">
                                                        <i class="tio-delete"></i>
                                                    </a>
                                                    <form
                                                        action="{{route('admin.business-settings.delivery-restriction.delivery-country-delete',['id' => $store->id])}}"
                                                        method="post" id="product-{{$store->id}}">
                                                        @csrf @method('delete')
                                                    </form>
                                                </div>
                                            </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">
                                                    <div class="text-center p-4">
                                                        <img class="mb-3 w-160" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description">
                                                        <p class="mb-0">{{\App\CPU\translate('No Country Found')}}</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="table-responsive mt-4">
                                    <div class="d-flex justify-content-lg-end">
                                        <!-- Pagination -->
                                        {{$stored_countries->links()}}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- End Delivery to Country -->
            <!-- Delivery to zipcode area -->
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0 text-capitalize d-flex gap-1">
                            <i class="tio-briefcase"></i>
                            {{\App\CPU\translate('delivery_available_zip_code_area')}}
                        </h5>
                        <label class="switcher">
                            <input type="checkbox" onchange="status_change(this)" data-id="zip_area" class="status switcher_input" data-url="{{ route('admin.business-settings.delivery-restriction.zipcode-restriction-status-change') }}" {{ isset($zip_code_area_restriction_status) && $zip_code_area_restriction_status->value  == 1? 'checked' : '' }}>
                            <span class="switcher_control"></span>
                        </label>

                    </div>
                    <div class="card-body zip-disable">
                        <div class="row">
                            <div class="col-lg-12">
                                <form action="{{ route('admin.business-settings.delivery-restriction.add-zip-code') }}"
                                      method="post">
                                    @csrf
                                    <label class="title-color d-flex"> {{\App\CPU\translate('zip_code')}} </label>

                                    <input type="text" class="form-control" name="zipcode"
                                           placeholder="{{ \App\CPU\translate('enter_zip_code') }}"
                                           data-role="tagsinput" required>
                                    <span class="pl-2 text-info">({{\App\CPU\translate('multiple_zip_codes_can_be_inputted_by_comma_separating_or_pressing_enter_button')}})</span>

                                    <div class="mb-3 d-flex justify-content-end">
                                        <button type="submit" class="btn btn--primary px-4">{{\App\CPU\translate('Submit')}}</button>
                                    </div>
                                </form>

                            </div>
                            <div class="col-md-12 mt-6">
                                <div class="table-responsive">
                                    <table id="datatable"
                                           class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                                        <thead class="thead-light thead-50 text-capitalize">
                                        <tr>
                                            <th>{{\App\CPU\translate('sl')}}</th>
                                            <th class="text-center">{{\App\CPU\translate('zip_code')}}</th>
                                            <th class="text-center">{{\App\CPU\translate('action')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($stored_zip as $k=>$zip)
                                            <tr>
                                                <td>{{$stored_zip->firstItem()+$k}}</td>
                                                <td class="text-center">{{ $zip->zipcode }}</td>
                                                <td>
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <a class="btn btn-outline-danger btn-sm square-btn"
                                                           href="javascript:"
                                                           title="{{\App\CPU\translate('Delete')}}"
                                                           onclick="form_alert('product-{{$zip->id}}','Want to delete this item ?')">
                                                            <i class="tio-delete"></i>
                                                        </a>
                                                        <form
                                                            action="{{route('admin.business-settings.delivery-restriction.zip-code-delete',['id' => $zip->id])}}"
                                                            method="post" id="product-{{$zip->id}}">
                                                            @csrf @method('delete')
                                                        </form>
                                                    </div>


                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">
                                                    <div class="text-center p-4">
                                                        <img class="mb-3 w-160" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description">
                                                        <p class="mb-0">{{\App\CPU\translate('No Zip Code Found')}}</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="table-responsive mt-4">
                                    <div class="d-flex justify-content-lg-end">
                                        <!-- Pagination -->
                                        {{$stored_zip->links()}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- End Delivery to zipcode area -->

        </div>
    </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('public/assets/back-end') }}/js/tags-input.min.js"></script>
    <script>

        $(".js-example-responsive").select2({
            theme: "classic",
            placeholder: "Select Country",
            allowClear: true,

        });
        $('.select2-search__field').css('width', '100%');

        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });


    </script>
    <script>
        let country_status = {{ isset($country_restriction_status)? $country_restriction_status->value: 0  }};
        let zip_status = {{ isset($zip_code_area_restriction_status)? $zip_code_area_restriction_status->value : 0 }};

        if (country_status === 0) {
            $(".country-disable").hide();
        }
        if(zip_status === 0) {
            $(".zip-disable").hide();
        }

        function status_change(t) {
            let url = $(t).data('url');
            let checked = $(t).prop("checked");
            let status = checked === true ? 1 : 0;

            Swal.fire({
                title: 'Are you sure?',
                text: 'Want to change status',
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
                        url: url,
                        method: 'POST',
                        data: {
                            status: status
                        },
                        success: function (data) {
                            if (data.status === true) {
                                toastr.success(data.message);
                                if (status === 0){
                                    $(t).parents('.card-header').siblings('.card-body').hide();
                                } else if (status === 1){
                                    $(t).parents('.card-header').siblings('.card-body').show();
                                }
                            }
                        }
                    });
                }
            }
            )
        }

    </script>
@endpush
