@extends('layouts.back-end.app')

@section('title', 'إعدادات التسوق حسب الميزانية')

@push('css_or_js')
    <link href="{{asset('assets/back-end/css/tags-input.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <!-- Page Heading -->
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
            <h2 class="h1 mb-0 d-flex gap-2">
                <img src="{{asset('/assets/back-end/img/inhouse-product-list.png')}}" alt="">
                إعدادات التسوق حسب الميزانية
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <form class="product-form" action="{{route('admin.budget-filter.update')}}" method="post"
                      style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                      enctype="multipart/form-data"
                      id="product_form">
                    @csrf

                    <div class="card">


                        <div class="card-body">

                            <div class="form-group">
                                <label class="title-color" for="f_num">الرقم الأول<span
                                        class="text-danger">*</span></label>
                                <input type="number" name="f_num"
                                       id="f_num"
                                       value="{{$data->f_num}}"
                                       class="form-control" placeholder="تعديل الرقم الأول" required>
                            </div>
                            <div class="form-group">
                                <label class="title-color" for="s_num">الرقم الثاني<span
                                        class="text-danger">*</span></label>
                                <input type="number" name="s_num"
                                       id="s_num"
                                       value="{{$data->s_num}}"
                                       class="form-control" placeholder="تعديل الرقم الثاني" required>
                            </div>
                            <div class="form-group">
                                <label class="title-color" for="t_num">الرقم الثالث<span
                                        class="text-danger">*</span></label>
                                <input type="number" name="t_num"
                                       id="t_num"
                                       value="{{$data->t_num}}"
                                       class="form-control" placeholder="تعديل الرقم الثالث" required>
                            </div>
                            <div class="form-group">
                                <label class="title-color" for="fo_num">الرقم الرابع<span
                                        class="text-danger">*</span></label>
                                <input type="number" name="fo_num"
                                       id="fo_num"
                                       value="{{$data->fo_num}}"
                                       class="form-control" placeholder="تعديل الرقم الرابع" required>
                            </div>
                            <div class="form-group">

                                <label for="image" class="mt-3">{{ \App\CPU\translate('Image')}} (150 : 1050)</label>
                                <br>
                                <div class="custom-file text-left">
                                    <input type="file" name="image" id="mbimageFileUploader"
                                           class="custom-file-input"
                                           accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    <label class="custom-file-label"
                                           for="mbimageFileUploader">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}} </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-lg-2 col-12 form-group">
                                    <button type="submit" class="btn btn--primary">{{\App\CPU\translate('Update')}}</button>

                                </div>
                            </div>
                        </div>

                    </div>


                </form>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script src="{{asset('assets/back-end')}}/js/tags-input.min.js"></script>
    <script src="{{asset('assets/back-end/js/spartan-multi-image-picker.js')}}"></script>



    <script>

    </script>

    {{--ck editor--}}
    <script src="{{asset('/')}}vendor/ckeditor/ckeditor/ckeditor.js"></script>
    <script src="{{asset('/')}}vendor/ckeditor/ckeditor/adapters/jquery.js"></script>
    <script>

    </script>
    {{--ck editor--}}
@endpush
