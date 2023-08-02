@extends('layouts.back-end.app')

@section('title', 'إعدادات البانر الرئيسي')

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
                إعدادات البانر الرئيسي
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <form class="product-form" action="{{route('admin.home-banner-settings.update')}}" method="post"
                      style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                      enctype="multipart/form-data"
                      id="product_form">
                    @csrf

                    <div class="card">
                        <div class="px-4 pt-3">
                            @php($language=\App\Model\BusinessSetting::where('type','pnc_language')->first())
                            @php($language = $language->value ?? null)
                            @php($default_lang = 'en')

                            @php($default_lang = json_decode($language)[0])
                            <ul class="nav nav-tabs w-fit-content mb-4">
                                @foreach(json_decode($language) as $lang)
                                    <li class="nav-item text-capitalize">
                                        <a class="nav-link lang_link {{$lang == $default_lang? 'active':''}}" href="#"
                                           id="{{$lang}}-link">{{\App\CPU\Helpers::get_language_name($lang).'('.strtoupper($lang).')'}}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="card-body">
                            @foreach(json_decode($language) as $lang)
                                <?php
                                if (count($settings_of_banner['translations'])) {
                                    $translate = [];
                                    foreach ($settings_of_banner['translations'] as $t) {

                                        if ($t->locale == $lang && $t->key == "title_o") {
                                            $translate[$lang]['title_o'] = $t->value;
                                        }
                                        if ($t->locale == $lang && $t->key == "title_t") {
                                            $translate[$lang]['title_t'] = $t->value;
                                        }
                                        if ($t->locale == $lang && $t->key == "description_o") {
                                            $translate[$lang]['description_o'] = $t->value;
                                        }
                                        if ($t->locale == $lang && $t->key == "description_t") {
                                            $translate[$lang]['description_t'] = $t->value;
                                        }

                                    }
                                }
                                ?>
                                <div class="{{$lang != 'en'? 'd-none':''}} lang_form" id="{{$lang}}-form">
                                    <div class="form-group">
                                        <label class="title-color" for="{{$lang}}_title_o">العنوان الأول<span class="text-danger">*</span>
                                            ({{strtoupper($lang)}})</label>
                                        <input type="text" {{$lang == 'en'? 'required':''}} name="title_o[]"
                                               id="{{$lang}}_title_o"
                                               value="{{$translate[$lang]['title_o']??$settings_of_banner['title_o']}}"
                                               class="form-control" placeholder="تعديل العنوان الأول" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="title-color" for="{{$lang}}_title_t">العنوان الثاني<span class="text-danger">*</span>
                                            ({{strtoupper($lang)}})</label>
                                        <input type="text" {{$lang == 'en'? 'required':''}} name="title_t[]"
                                               id="{{$lang}}_title_t"
                                               value="{{$translate[$lang]['title_t']??$settings_of_banner['title_t']}}"
                                               class="form-control" placeholder="تعديل العنوان الثاني" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="title-color" for="{{$lang}}_description_o">الوصف الأول<span class="text-danger">*</span>
                                            ({{strtoupper($lang)}})</label>
                                        <input type="text" {{$lang == 'en'? 'required':''}} name="description_o[]"
                                               id="{{$lang}}_description_o"
                                               value="{{$translate[$lang]['description_o']??$settings_of_banner['description_o']}}"
                                               class="form-control" placeholder="تعديل الوصف الأول" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="title-color" for="{{$lang}}_description_t">الوصف الثاني<span class="text-danger">*</span>
                                            ({{strtoupper($lang)}})</label>
                                        <input type="text" {{$lang == 'en'? 'required':''}} name="description_t[]"
                                               id="{{$lang}}_description_t"
                                               value="{{$translate[$lang]['description_t']??$settings_of_banner['description_t']}}"
                                               class="form-control" placeholder="تعديل الوصف الثاني" required>
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{$lang}}">

                                </div>
                            @endforeach
                        </div>
                    </div>


                    <div class="card mt-2 rest-part">
                        <div class="card-body row">
                            <div class="col-md-6 d-flex flex-column justify-content-end">
                                <div>
                                    <center>
                                        <img
                                            class=""
                                            id=""
                                            src="{{asset('uploads/banners_home')}}/{{$settings_of_banner['image_o']}}"
                                            {{--                                                onerror='this.src="{{asset('assets/front-end/img/placeholder.png')}}"'--}}
                                            alt=""/>
                                    </center>
                                    <label for="name" class="mt-3">{{ \App\CPU\translate('Image')}}</label>
                                    <br>
                                    <div class="custom-file text-left">
                                        <input type="file" name="image_o" id="mbimageFileUploader"
                                               class="custom-file-input"
                                               accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label"
                                               for="mbimageFileUploader">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex flex-column justify-content-end">
                                <div>
                                    <center>
                                        <img
                                            class=""
                                            id=""
                                            src="{{asset('uploads/banners_home')}}/{{$settings_of_banner['image_t']}}"
                                            {{--                                                onerror='this.src="{{asset('assets/front-end/img/placeholder.png')}}"'--}}
                                            alt="" style="height: 150px!important;"/>
                                    </center>
                                    <label for="name" class="mt-3">{{ \App\CPU\translate('Image')}}</label>
                                    <br>
                                    <div class="custom-file text-left">
                                        <input type="file" name="image_t" id="mbimageFileUploader"
                                               class="custom-file-input"
                                               accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label"
                                               for="mbimageFileUploader">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit"  class="btn btn--primary">{{\App\CPU\translate('Update')}}</button>

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
        $(".lang_link").click(function (e) {
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];
            console.log(lang);
            $("#" + lang + "-form").removeClass('d-none');
            if (lang == '{{$default_lang}}') {
                $(".rest-part").removeClass('d-none');
            } else {
                $(".rest-part").addClass('d-none');
            }
        })
    </script>

    {{--ck editor--}}
    <script src="{{asset('/')}}vendor/ckeditor/ckeditor/ckeditor.js"></script>
    <script src="{{asset('/')}}vendor/ckeditor/ckeditor/adapters/jquery.js"></script>
    <script>

    </script>
    {{--ck editor--}}
@endpush
