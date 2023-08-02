@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Product Bulk Import'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-4">
            <h2 class="h1 mb-1 text-capitalize d-flex gap-2">
                <img src="{{asset('/assets/back-end/img/bulk-import.png')}}" alt="">
                {{\App\CPU\translate('bulk_Import')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Content Row -->
        <div class="row" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <div class="col-12">
                <div class="card card-body">
                    <h1 class="display-5">{{\App\CPU\translate('Instructions')}} : </h1>
                    <p> 1. {{\App\CPU\translate('Download the format file and fill it with proper data.')}}</p>

                    <p>2. {{\App\CPU\translate('You can download the example file to understand how the data must be filled.')}}</p>

                    <p>3. {{\App\CPU\translate('Once you have downloaded and filled the format file')}}, {{\App\CPU\translate('upload it in the form below and submit.')}}</p>

                    <p>4. {{\App\CPU\translate('After uploading products you need to edit them and set product images and choices.')}}</p>

                    <p>5. {{\App\CPU\translate('You can get brand and category id from their list')}}, {{\App\CPU\translate('please input the right ids.')}}</p>

                    <p>6. {{\App\CPU\translate('You can upload your product images in product folder from gallery')}}, {{\App\CPU\translate('and copy image`s path.')}}</p>
                </div>
            </div>

            <div class="col-md-12 mt-2">
                <form class="product-form" action="{{route('admin.product.bulk-import')}}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="card rest-part">
                        <div class="px-3 py-4 d-flex flex-wrap align-items-center gap-10 justify-content-center">
                            <h4 class="mb-0">{{\App\CPU\translate("Don`t_have_the_template_?")}}</h4>
                            <a href="{{asset('assets/product_bulk_format.xlsx')}}" download=""
                               class="btn-link text-capitalize fz-16 font-weight-medium">{{\App\CPU\translate('download_here')}}</a>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row justify-content-center">
                                    <div class="col-auto">
                                        <div class="upload-file">
                                            <input type="file" name="products_file" accept=".xlsx, .xls" class="upload-file__input">
                                            <div class="upload-file__img_drag upload-file__img">
                                                <img src="{{asset('/assets/back-end/img/drag-upload-file.png')}}" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-10 align-items-center justify-content-end">
                                <button type="reset" class="btn btn-secondary px-4" onclick="resetImg();">{{\App\CPU\translate('reset')}}</button>
                                <button type="submit" class="btn btn--primary px-4">{{\App\CPU\translate('Submit')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    // File Upload
    "use strict";

    $('.upload-file__input').on('change', function() {
        $(this).siblings('.upload-file__img').find('img').attr({
            'src': '{{asset('/assets/back-end/img/excel.png')}}',
            'width': 80
        });
    });

    function resetImg() {
        $('.upload-file__img img').attr({
            'src': '{{asset('/assets/back-end/img/drag-upload-file.png')}}',
            'width': 'auto'
        });
    }
</script>

@endpush
