@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Update Notification'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/public/assets/back-end/img/push_notification.png')}}" alt="">
                {{\App\CPU\translate('push_notification_update')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- End Page Header -->
        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.notification.update',[$notification['id']])}}" method="post"
                        style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                        enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{\App\CPU\translate('Title')}}</label>
                                <input type="text" value="{{$notification['title']}}" name="title" class="form-control"
                                        placeholder="{{\App\CPU\translate('New notification')}}" required>
                            </div>
                            <div class="form-group mb-0">
                                <label class="input-label" for="exampleFormControlInput1">{{\App\CPU\translate('Description')}}</label>
                                <textarea name="description" class="form-control"
                                            required>{{$notification['description']}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <center>
                                <img class="upload-img-view mt-4" 
                                    id="viewer"
                                    onerror="this.src='{{asset('public/assets/back-end/img/160x160/img2.jpg')}}'"
                                    src="{{asset('storage/app/public/notification')}}/{{$notification['image']}}"
                                        alt="image"/>
                            </center>
                            <label class="title-color">{{\App\CPU\translate('Image')}}</label>
                            <span class="text-info"> ( {{\App\CPU\translate('Ratio_1:1')}}  )</span>
                            <div class="custom-file">
                                <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label" for="customFileEg1">{{\App\CPU\translate('Choose file')}}</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-3">
                                <button type="reset" class="btn btn-secondary">{{\App\CPU\translate('reset')}}</button>
                                <button type="submit" class="btn btn--primary">{{\App\CPU\translate('Update')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Table -->
    </div>
    </div>

@endsection

@push('script_2')
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
