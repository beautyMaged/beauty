@extends('layouts.back-end.app')

@section('title', 'Landing Pages')

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
            <h2 class="h1 mb-0">
                <img src="{{asset('/assets/back-end/img/brand-setup.png')}}" class="mb-1 mr-1" alt="">

                Landing Pages Update
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <img src="{{asset('storage/static-pages/'. $page->image)}}" alt="">
                        </div>
                    </div>
                        <div class="card-body" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        <form action="{{route('admin.static-pages.update',[$page['id']])}}" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="{{$page->id}}">
                            @csrf
                            @php($language=\App\Model\BusinessSetting::where('type','pnc_language')->first())
                            @php($language = $language->value ?? null)
                            @php($default_lang = 'en')

                            @php($default_lang = json_decode($language)[0])
                            <ul class="nav nav-tabs w-fit-content mb-4">
                                @foreach(json_decode($language) as $lang)
                                    <li class="nav-item text-capitalize">
                                        <a class="nav-link lang_link {{$lang == $default_lang? 'active':''}}"
                                           href="#"
                                           id="{{$lang}}-link">{{\App\CPU\Helpers::get_language_name($lang).'('.strtoupper($lang).')'}}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="row">
                                <div class="col-lg-12">
                                    @foreach(json_decode($language) as $lang)
                                        <div class="{{$lang != $default_lang ? 'd-none':''}} lang_form"  id="{{$lang}}-form">
                                            <?php
                                            if (count($page['translations'])) {
                                                $translate = [];
                                                foreach ($page['translations'] as $t) {
                                                    if ($t->locale == $lang && $t->key == "title") {
                                                        $translate[$lang]['title'] = $t->value;
                                                    }
                                                    if ($t->locale == $lang && $t->key == "description") {
                                                        $translate[$lang]['description'] = $t->value;
                                                    }
                                                }
                                            }
                                            ?>
                                            <div class="form-group">
                                                <label class="title-color">{{\App\CPU\translate('Title')}}
                                                    ({{strtoupper($lang)}})</label>
                                                <input type="text" name="title[]"
                                                       value="{{$lang==$default_lang?$page['title']:($translate[$lang]['title']??'')}}"
                                                       class="form-control"
                                                       placeholder="{{\App\CPU\translate('New')}} {{\App\CPU\translate('title')}}" {{$lang == $default_lang? 'required':''}}>
                                            </div>
                                                <div class="form-group ">
                                                    <label for="editor"
                                                           class="title-color text-capitalize">{{ \App\CPU\translate('description')}}</label>
                                                    <textarea name="description[]" class="editor" {{$lang == $default_lang? 'required':''}} >{!! $lang==$default_lang?$page['description']:($translate[$lang]['description']??'')!!}</textarea>
                                                </div>
                                            <input type="hidden" name="lang[]" value="{{$lang}}">
                                        </div>
                                    @endforeach


                                    <div class="from_part_2">
                                        <label class="title-color">{{\App\CPU\translate('Image')}}</label>
                                        <div class="custom-file text-left">
                                            <input type="file" name="image" id="customFileEg1"
                                                   class="custom-file-input"
                                                   accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label"
                                                   for="customFileEg1">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                        </div>
                                    </div>
                                </div>


                            </div>

                            <div class="d-flex justify-content-end gap-3">
                                <button type="reset" id="reset" class="btn btn-secondary px-4">{{ \App\CPU\translate('reset')}}</button>
                                <button type="submit" class="btn btn--primary px-4">{{ \App\CPU\translate('update')}}</button>
                            </div>
                            {{--                            @endif--}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{asset('/')}}vendor/ckeditor/ckeditor/ckeditor.js"></script>
    <script src="{{asset('/')}}vendor/ckeditor/ckeditor/adapters/jquery.js"></script>


    <script>
        $('.editor').ckeditor({
            contentsLangDirection : '{{Session::get('direction')}}',
        });
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
                $(".from_part_2").removeClass('d-none');
            } else {
                $(".from_part_2").addClass('d-none');
            }
        });

        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });
    </script>
@endpush
