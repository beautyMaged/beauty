@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('inhouse_shop'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">

        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/business-setup.png')}}" alt="">
                {{\App\CPU\translate('Business_Setup')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
    @include('admin-views.business-settings.business-setup-inline-menu')
    <!-- End Inlile Menu -->

        <div class="card mb-3">
            <div class="card-body">
                <div class="border rounded border-color-c1 px-4 py-3 d-flex justify-content-between mb-1">
                    <h5 class="mb-0 d-flex gap-1 c1">
                        {{\App\CPU\translate('temporary_close')}}
                    </h5>
                    <div class="position-relative">
                        <label class="switcher">
                            <input type="checkbox" class="switcher_input" id="temporary_close" {{$temporary_close['status'] == 1?'checked':''}}>
                            <span class="switcher_control"></span>
                        </label>
                    </div>
                </div>
                <p>*{{\App\CPU\translate('By_turning_on_temporary_close_mode,_your_shop_will_be_shown_as_temporary_off_in_the_website_and_app_for_the_customers._they_cannot_purchase_or_place_order_from_your_shop')}}</p>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 d-flex gap-2 flex-wrap">
                            <img src="{{asset('/public/assets/back-end/img/footer-logo.png')}}" alt="">
                            {{\App\CPU\translate('Shop_cover_Image')}}
                        </h5>
                        <div class="d-inline-flex gap-2">
                            <button class="btn btn-block __inline-70" data-toggle="modal" data-target="#balance-modal">
                                {{\App\CPU\translate('go_to_Vacation_Mode')}}
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.product-settings.inhouse-shop') }}" method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            <center>
                                <img id="viewerShop" width="300"
                                     onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                     src="{{asset('storage/app/public/shop')}}/{{\App\CPU\Helpers::get_business_settings('shop_banner')}}">
                            </center>
                            <div class="position-relative mt-4">
                                <input type="file" name="shop_banner" id="customFileUploadShop"
                                       class="custom-file-input"
                                       accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label" for="customFileUploadShop">
                                    {{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}
                                </label>
                            </div>
                            <div class="d-flex justify-content-end mt-30">
                                <button type="submit" class="btn btn--primary px-4">{{\App\CPU\translate('Upload')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="balance-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content"
                 style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                <form action="{{route('admin.product-settings.vacation-add')}}" method="post">
                    <div class="modal-header border-bottom pb-2">
                        <div>
                            <h5 class="modal-title" id="exampleModalLabel">{{\App\CPU\translate('Vacation_Mode')}}</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="switcher">
                                    <input type="checkbox" name="status" class="switcher_input" id="vacation_close" {{$vacation['status'] == 1?'checked':''}}>
                                    <span class="switcher_control"></span>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="close pt-0" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="mb-5">*{{\App\CPU\translate('set_vacation_mode_for_shop_means_you_will_be_not_available_receive_order_and_provider_products_for_placed_order_at_that_time')}}</div>

                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label>{{\App\CPU\translate('Vacation_Start')}}</label>
                                <input type="date" name="vacation_start_date" value="{{ $vacation['vacation_start_date'] }}" id="vacation_start_date" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>{{\App\CPU\translate('Vacation_End')}}</label>
                                <input type="date" name="vacation_end_date" value="{{ $vacation['vacation_end_date'] }}" id="vacation_end_date" class="form-control" required>
                            </div>
                            <div class="col-md-12 mt-2 ">
                                <label>{{\App\CPU\translate('Vacation_Note')}}</label>
                                <textarea class="form-control" name="vacation_note" id="vacation_note">{{ $vacation['vacation_note'] }}</textarea>
                            </div>
                        </div>

                        <div class="text-end gap-5 mt-2">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{\App\CPU\translate('Close')}}</button>
                            <button type="submit" class="btn btn--primary">{{\App\CPU\translate('update')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('script')
    <script>
        $('#temporary_close').on('change', function (){
            let status = $(this).prop("checked") === true ? 'checked':'unchecked';
            Swal.fire({
                title: '{{\App\CPU\translate('are_you_sure_change_this')}}?',
                text: "",
                showCancelButton: true,
                confirmButtonColor: '#377dff',
                cancelButtonColor: 'secondary',
                confirmButtonText: '{{\App\CPU\translate('Yes, Change it')}}!'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.product-settings.inhouse-shop-temporary-close')}}",
                        method: 'POST',
                        data: {
                            status: status
                        },
                        success: function (data) {
                            toastr.success('{{\App\CPU\translate('temporary_close_updated_successfully')}}!');
                            location.reload();
                        }
                    });
                }
            });
        });

        $('#vacation_start_date,#vacation_end_date').change(function () {
            let fr = $('#vacation_start_date').val();
            let to = $('#vacation_end_date').val();
            if(fr != ''){
                $('#vacation_end_date').attr('required','required');
            }
            if(to != ''){
                $('#vacation_start_date').attr('required','required');
            }
            if (fr != '' && to != '') {
                if (fr > to) {
                    $('#vacation_start_date').val('');
                    $('#vacation_end_date').val('');
                    toastr.error('{{\App\CPU\translate('Invalid date range')}}!', Error, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }

        })
    </script>
@endpush
