@extends('layouts.back-end.app')
@section('title',\App\CPU\translate('gallery'))
@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">

        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/assets/back-end/img/file-manager.png')}}" width="20" alt="">
                {{\App\CPU\translate('file_manager')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Page Heading -->
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="mb-0">{{\App\CPU\translate('file_manager')}}</h5>
            <button type="button" class="btn btn--primary modalTrigger" data-toggle="modal" data-target="#exampleModal">
                <i class="tio-add"></i>
                <span class="text">{{\App\CPU\translate('add')}} {{\App\CPU\translate('new')}}</span>
            </button>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        @php
                        //dd(base64_decode($folder_path));
                            $pwd = explode('/',base64_decode($folder_path));
                        @endphp
                        <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2">
                            {{\App\CPU\translate(end($pwd))}}
                            <span class="badge badge-soft-dark radius-50" id="itemCount">{{count($data)}}</span>
                        </h5>
                        <a class="btn btn--primary" href="{{url()->previous()}}">
                            <i class="tio-chevron-left"></i>
                            {{\App\CPU\translate('back')}}
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($data as $key=>$file)
                                <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                                    @if($file['type']=='folder')
                                        <a class="btn p-0"
                                           href="{{route('admin.file-manager.index', base64_encode(str_replace('public/', '', $file['path'])))}}">
                                            <img class="img-thumbnail mb-2"
                                                 src="{{asset('assets/back-end/img/folder.png')}}" alt="">
                                            <p class="title-color">{{Str::limit($file['name'],10)}}</p>
                                        </a>
                                    @elseif($file['type']=='file')
                                    <!-- <a class="btn" href="{{asset('storage/'.str_replace('public/', '', $file['path']))}}" download> -->
                                        <button class="btn p-0 w-100" data-toggle="modal"
                                                data-target="#imagemodal{{$key}}" title="{{$file['name']}}">
                                            <div class="gallary-card">
{{--                                                {{dd( $file['path'])}}--}}
                                                <img src="{{asset('storage/'.str_replace('public/', '', $file['path']))}}"
                                                     alt="{{$file['name']}}" class="h-auto w-100 mb-2">
                                            </div>
                                            <p class="overflow-hidden">{{Str::limit($file['name'],10)}}</p>
                                        </button>
                                        <div class="modal fade" id="imagemodal{{$key}}" tabindex="-1" role="dialog"
                                             aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="myModalLabel">{{$file['name']}}</h4>
                                                        <button type="button" class="close" data-dismiss="modal"><span
                                                                aria-hidden="true">&times;</span><span
                                                                class="sr-only">{{\App\CPU\translate('close')}}</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <img src="{{asset('storage/'.str_replace('public/', '', $file['path']))}}"
                                                             class="w-100 h-auto">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a class="btn btn--primary"
                                                           href="{{route('admin.file-manager.download', base64_encode(str_replace('public/', '', $file['path'])))}}"><i
                                                                class="tio-download"></i> {{\App\CPU\translate('download')}}
                                                        </a>
                                                        <button class="btn btn-info"
                                                                onclick="copy_test('{{$file['db_path']}}')"><i
                                                                class="tio-copy"></i> {{\App\CPU\translate('copy_path')}}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="indicator"></div>
                    <div class="modal-header">
                        <h5 class="modal-title"
                            id="exampleModalLabel">{{\App\CPU\translate('upload')}} {{\App\CPU\translate('file')}} </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin.file-manager.image-upload')}}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <input type="text" name="path" value="{{base64_decode($folder_path)}}" hidden>
                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" name="images[]" id="customFileUpload" class="custom-file-input"
                                           accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" multiple>
                                    <label class="custom-file-label"
                                           for="customFileUpload">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('images')}}</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" name="file" id="customZipFileUpload" class="custom-file-input"
                                           accept=".zip">
                                    <label class="custom-file-label" id="zipFileLabel"
                                           for="customZipFileUpload">{{\App\CPU\translate('upload_zip_file')}}</label>
                                </div>
                            </div>

                            <div class="row" id="files"></div>
                            <div class="form-group">
                                <input class="btn btn--primary" type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                       onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                       value="{{\App\CPU\translate('upload')}}">
                            </div>
                        </form>

                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        function readURL(input) {
            $('#files').html("");
            for (var i = 0; i < input.files.length; i++) {
                if (input.files && input.files[i]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#files').append('<div class="col-md-2 col-sm-4 m-1"><img class="__empty-img" id="viewer" src="' + e.target.result + '"/></div>');
                    }
                    reader.readAsDataURL(input.files[i]);
                }
            }

        }

        $("#customFileUpload").change(function () {
            readURL(this);
        });

        $('#customZipFileUpload').change(function (e) {
            var fileName = e.target.files[0].name;
            $('#zipFileLabel').html(fileName);
        });

        function copy_test(copyText) {
            /* Copy the text inside the text field */
            navigator.clipboard.writeText(copyText);

            toastr.success('{{\App\CPU\translate('file_path_copied_successfully!')}}', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    </script>
@endpush
